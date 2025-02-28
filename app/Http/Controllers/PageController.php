<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterList;
use App\Models\Barangay;
use App\Models\Coordinator;
use App\Models\PurokLeader;
use App\Models\HouseholdLeader;


class PageController extends Controller
{
   
    public function household_leader(Request $request)
{
    $purok_leaders = Masterlist::whereIn('id', PurokLeader::pluck('purok_leader_id'))->get();
    $householdLeaders = HouseholdLeader::with('purokLeader.coordinator.voter')->get();
    $barangays = Barangay::all();
    $potentialMembers = MasterList::with(['coordinator', 'purokLeader', 'householdLeader', 'householdMember'])->get();

    $barangay = $request->input('barangay');
    $first_name = $request->input('first_name');
    $last_name = $request->input('last_name');

    // Fetch only if search criteria is provided, otherwise return an empty collection
    $voters = collect(); // Default: empty collection
    if ($barangay || $first_name || $last_name) {
        $voters = MasterList::when($barangay, function ($query, $barangay) {
                return $query->where('barangay', 'like', "%{$barangay}%");
            })
            ->when($first_name, function ($query, $first_name) {
                return $query->where('first_name', 'like', "%{$first_name}%");
            })
            ->when($last_name, function ($query, $last_name) {
                return $query->where('last_name', 'like', "%{$last_name}%");
            })
            ->get();
    }
    session([
        'potentialMembers' => $potentialMembers,
        'session_expires_at' => now()->addMinutes(30),
    ]);

    return view('admin.tagging_household_leader', compact('voters', 'purok_leaders', 'barangays', 'householdLeaders'));
}

   


}
