<?php

namespace App\Http\Controllers;

use App\Models\Citizen;
use App\Models\User;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:sanctum');
    //     $this->middleware('can:policy_maker');
    // }

    public function getStats()
    {
        $totalCitizens = Citizen::count();
        $verifiedCitizens = Citizen::where('verification_status', 'verified')->count();
        $pendingCitizens = Citizen::where('verification_status', 'pending')->count();
        $rejectedCitizens = Citizen::where('verification_status', 'rejected')->count();
        
        $verificationRate = $totalCitizens > 0 ? round(($verifiedCitizens / $totalCitizens) * 100, 2) : 0;

        return response()->json([
            'total_citizens' => $totalCitizens,
            'verified_citizens' => $verifiedCitizens,
            'pending_citizens' => $pendingCitizens,
            'rejected_citizens' => $rejectedCitizens,
            'verification_rate' => $verificationRate,
            'total_users' => User::count(),
        ]);
    }

    public function getVerificationTrends()
    {
        $trends = Citizen::selectRaw('
            DATE(created_at) as date,
            COUNT(*) as total,
            SUM(CASE WHEN verification_status = "verified" THEN 1 ELSE 0 END) as verified,
            SUM(CASE WHEN verification_status = "pending" THEN 1 ELSE 0 END) as pending
        ')
        ->where('created_at', '>=', now()->subDays(30))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return response()->json($trends);
    }

    public function getDemographics()
    {
        $ageGroups = Citizen::selectRaw('
            CASE
                WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) < 18 THEN "Under 18"
                WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 18 AND 25 THEN "18-25"
                WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 26 AND 35 THEN "26-35"
                WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 36 AND 50 THEN "36-50"
                ELSE "Over 50"
            END as age_group,
            COUNT(*) as count
        ')
        ->groupBy('age_group')
        ->get();

        return response()->json($ageGroups);
    }
}