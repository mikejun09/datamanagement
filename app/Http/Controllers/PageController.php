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
    $purok_leaders = MasterList::whereIn('id', PurokLeader::pluck('purok_leader_id'))->get();
    $householdLeaders = HouseholdLeader::with('voter', 'purokLeader', 'purokLeader.coordinator')->get();
    $barangays = Barangay::all();

    $barangay = $request->input('barangay');
    $first_name = $request->input('first_name');
    $last_name = $request->input('last_name');

    $voters = collect(); // Empty collection by default

    // Only fetch data if at least one search field is provided
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

    return view('admin.tagging_household_leader', compact('voters', 'purok_leaders', 'barangays', 'householdLeaders'));
}


public function searchHouseholdMembers(Request $request)
{
    $barangay = $request->input('barangay');
    $first_name = $request->input('first_name');
    $last_name = $request->input('last_name');

    $members = collect(); // Empty collection by default

    if ($barangay || $first_name || $last_name) {
        $members = MasterList::when($barangay, function ($query, $barangay) {
                return $query->where('barangay', 'like', "%{$barangay}%");
            })
            ->when($first_name, function ($query, $first_name) {
                return $query->where('first_name', 'like', "%{$first_name}%");
            })
            ->when($last_name, function ($query, $last_name) {
                return $query->where('last_name', 'like', "%{$last_name}%");
            })
            ->with([
                'coordinator:id,coordinator_id', 
                'purokLeader:id,purok_leader_id', 
                'householdLeader:id,household_leader_id', 
                'householdMember:id,household_member_id'
            ])
            ->get();
    }

    return response()->json($members); // Return JSON response for AJAX
}


public function destroy($id)
{
    $leader = HouseholdLeader::findOrFail($id);

    // Ensure leader has no members before deleting
    if ($leader->householdMembers()->count() > 0) {
        return redirect()->back()->with('error', 'Cannot delete a Household Leader with members.');
    }

    $leader->delete();

    // Clear the selectedLeader session
    session()->forget('selectedLeader');

    return redirect()->back()->with('success', 'Household Leader deleted successfully.');
}


    

   


}
