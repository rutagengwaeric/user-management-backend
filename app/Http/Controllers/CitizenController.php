<?php

namespace App\Http\Controllers;

use App\Models\Citizen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CitizenController extends Controller
{

    public function index()
    {
        $user = auth()->user();


        if ($user->isCitizen()) {
            return response()->json([
                'citizens' => Citizen::where('user_id', $user->id)->get()
            ]);

           
        }

        

        if ($user->isLocalLeader() || $user->isSystemAdmin()) {
            return response()->json([
                'citizens' => Citizen::with(['user', 'verifier'])->get()
            ]);
        }

        if ($user->isPolicyMaker()) {
            return response()->json([
                'citizens' => Citizen::where('verification_status', 'verified')->get()
            ]);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user->isCitizen() && !$user->isLocalLeader()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'national_id' => 'required|unique:citizens',
            'full_name' => 'required|string',
            'date_of_birth' => 'required|date',
            'address' => 'required|string',
            'phone_number' => 'required|string'
        ]);

        $citizen = Citizen::create([
            'user_id' => $user->isCitizen() ? $user->id : $request->user_id,
            ...$request->only(['national_id', 'full_name', 'date_of_birth', 'address', 'phone_number'])
        ]);

        return response()->json($citizen, 201);
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

        return response()->json($citizen);
    }

    public function myProfile(Request $request)
    {
        $citizen = Citizen::where('user_id', $request->user()->id)->first();

        if (!$citizen) {
            return response()->json(['message' => 'Citizen profile not found'], 404);
        }

        return response()->json(['citizen' => $citizen->load('user', 'verifier')]);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $citizen = Citizen::findOrFail($id);

        // Citizens can only update their own profile
        if ($user->isCitizen() && $citizen->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'full_name' => 'sometimes|string|max:255',
            'date_of_birth' => 'sometimes|date',
            'address' => 'sometimes|string',
            'phone_number' => 'sometimes|string',
        ]);

        $citizen->update($request->only(['full_name', 'date_of_birth', 'address', 'phone_number']));

        return response()->json($citizen);
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
}
