<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coordinator;
use App\Models\Barangay;
use App\Models\MasterList;
use App\Models\PurokLeader;
use App\Models\HouseholdLeader;

class ReportsController extends Controller
{
    
    public function generateTaggedVotersReport(Request $request)
{
    $barangays = Barangay::all();
    $coordinators = Coordinator::with('voter')->get(); // Get all coordinators with their associated voters
    
    $barangay = $request->input('barangay');
    $first_name = $request->input('first_name');
    $last_name = $request->input('last_name');

    // Default: Select all voters if no search query is provided
    $voters = MasterList::when($barangay, function ($query, $barangay) {
            return $query->where('barangay', 'like', "%{$barangay}%");
        })
        ->when($first_name, function ($query, $first_name) {
            return $query->where('first_name', 'like', "%{$first_name}%");
        })
        ->when($last_name, function ($query, $last_name) {
            return $query->where('last_name', 'like', "%{$last_name}%");
        })
        ->whereHas('coordinator') // Only get voters who are tagged as Coordinators
        ->with(['coordinator']) // Eager load the coordinator relationship
        ->paginate(10); // Pagination for better performance

    return view('admin.reports', compact('voters', 'barangays', 'coordinators'));
}



public function selectCoordinator1(Request $request)
{
    $coordinatorId = $request->coordinator_id;

    // Get Coordinator with Purok Leaders
    $coordinator = Coordinator::with(['purokLeaders'])
        ->where('coordinator_id', $coordinatorId)
        ->first();

    if (!$coordinator) {
        return back()->with('error', 'Coordinator not found!');
    }

    // Fetch Household Leaders separately using Purok Leader IDs
    $purokLeaderIds = $coordinator->purokLeaders->pluck('id'); 
 
    $householdLeaders = HouseholdLeader::with(['householdMembers'])
        ->whereIn('purok_leader_id', $purokLeaderIds)
        ->get();
    
    // Store the data in session
    session([
        'selected_coordinator' => $coordinator,
        'purokLeaders' => $coordinator->purokLeaders,
        'householdLeaders' => $householdLeaders
    ]);

    return back()->with('message', 'Coordinator selected successfully!');
}




    
        



}
