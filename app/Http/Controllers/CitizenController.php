<?php

namespace App\Http\Controllers;

use App\Models\Citizen;
use Illuminate\Http\Request;

class CitizenController extends Controller
{
       public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        $user = auth()->user();

        if ($user->isCitizen()) {
            return response()->json([
                'citizen' => $user->citizen
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

}
