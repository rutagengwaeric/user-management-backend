<?php

namespace App\Http\Controllers;

use App\Models\Citizen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CitizenController extends Controller
{
  

    public function index()
    {
        $user = auth()->user();

        if ($user->isCitizen()) {
            // $citizen = Citizen::where('user_id', $user)->first();
           
            $citizen = Citizen::where('user_id', $user->id)->first();
            //  Log::info('Citizensss profile ',$citizen);
             return response()->json([
                'citizens' => $citizen
            ]);

        }

        if ($user->isLocalLeader() || $user->isSystemAdmin()) {
            $citizens = Citizen::with(['user', 'verifier'])->get();
            return response()->json(['citizens' => $citizens]);
        }

        if ($user->isPolicyMaker()) {
            $citizens = Citizen::where('verification_status', 'verified')
                ->with(['user', 'verifier'])
                ->get();
            return response()->json(['citizens' => $citizens]);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        Log::info("Authenticated user:" ,$user->toArray());

        if (!$user->isCitizen() && !$user->isLocalLeader()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

         Log::info('Request Data: ', $request->all());

        $request->validate([
            'national_id' => 'required|unique:citizens',
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'address' => 'required|string',
            'phone_number' => 'required|string'
        ]);

        Log::info('Creating citizen profile for user ID: ' . $user->id);
      

        $citizenData = [
            'user_id' => $user->isCitizen() ? $user->id : $request->user_id,
            'national_id' => $request->national_id,
            'full_name' => $request->full_name,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
        ];

        // If local leader is creating, set as verified
        if ($user->isLocalLeader()) {
            $citizenData['verification_status'] = 'verified';
            $citizenData['verified_by'] = $user->id;
            $citizenData['verification_notes'] = 'Created by local leader';
        }

        $citizen = Citizen::create($citizenData);

        return response()->json($citizen->load('user', 'verifier'), 201);
    }

    public function show($id)
    {
        $citizen = Citizen::with(['user', 'verifier'])->findOrFail($id);
        $user = auth()->user();

        if ($user->isCitizen() && $citizen->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(['citizen' => $citizen]);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $citizen = Citizen::findOrFail($id);

        

        // Citizens can only update their own profile
        // if ($user->isCitizen() && $citizen->user_id !== $user->id) {
        //     return response()->json(['message' => 'Unauthorized'], 403);
        // }

        // // Only system admins and local leaders can update other profiles
        // if (!$user->isSystemAdmin() && !$user->isLocalLeader() && $citizen->user_id !== $user->id) {
        //     return response()->json(['message' => 'Unauthorized'], 403);
        // }

        $request->validate([
            'full_name' => 'sometimes|string|max:255',
            'date_of_birth' => 'sometimes|date',
            'address' => 'sometimes|string',
            'phone_number' => 'sometimes|string',
        ]);

        $citizen->update($request->only(['full_name', 'date_of_birth', 'address', 'phone_number']));

        return response()->json($citizen->load('user', 'verifier'));
    }

    public function destroy($id)
    {
        if (!auth()->user()->isSystemAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $citizen = Citizen::findOrFail($id);
        $citizen->delete();

        return response()->json(['message' => 'Citizen record deleted successfully']);
    }

    public function myProfile(Request $request)
    {
        $citizen = Citizen::where('user_id', $request->user()->id)
            ->with(['user', 'verifier'])
            ->first();

        if (!$citizen) {
            return response()->json(['message' => 'Citizen profile not found'], 404);
        }

        return response()->json(['citizen' => $citizen]);
    }

    public function verify(Request $request, $id)
    {
        if (!auth()->user()->isLocalLeader() && !auth()->user()->isSystemAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'verification_status' => 'required|in:verified,rejected',
            'verification_notes' => 'nullable|string'
        ]);

        $citizen = Citizen::findOrFail($id);
        $citizen->update([
            'verification_status' => $request->verification_status,
            'verification_notes' => $request->verification_notes,
            'verified_by' => auth()->id()
        ]);

        return response()->json($citizen->load('user', 'verifier'));
    }
}
