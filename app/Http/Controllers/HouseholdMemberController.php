<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HouseholdMember;
use App\Models\HouseholdLeader;
use App\Models\Coordinator;
use App\Models\PurokLeader;
use App\Models\MasterList;
use App\Models\Barangay;


class HouseholdMemberController extends Controller
{
    public function tagHouseholdMembers(Request $request)
    {
        $householdLeaderId = $request->household_leader_id;
        $selectedMembers = $request->members;
    
        if (!$selectedMembers) {
            return response()->json(['error' => 'No members selected.'], 400);
        }
    
        foreach ($selectedMembers as $memberId) {
            // Check if the member is already tagged
            if (Coordinator::where('coordinator_id', $memberId)->exists()) {
                return response()->json(['error' => 'This voter is already assigned as a Coordinator.'], 400);
            }
    
            if (PurokLeader::where('purok_leader_id', $memberId)->exists()) {
                return response()->json(['error' => 'This voter is already assigned as a Purok Leader.'], 400);
            }
    
            if (HouseholdLeader::where('household_leader_id', $memberId)->exists()) {
                return response()->json(['error' => 'This voter is already assigned as a Household Leader.'], 400);
            }
    
            if (HouseholdMember::where('household_member_id', $memberId)->exists()) {
                return response()->json(['error' => 'This voter is already assigned as a Household Member.'], 400);
            }
    
            // Create HouseholdMember
            HouseholdMember::create([
                'household_member_id' => $memberId,
                'household_leader_id' => $householdLeaderId,
            ]);
        }
    
        // Fetch updated members
        $taggedMembers = HouseholdMember::where('household_leader_id', $householdLeaderId)->get();
    
        return response()->json([
            'success' => 'Household members successfully tagged!',
            'taggedMembers' => $taggedMembers
        ]);
    }
    

public function searchHouseholdLeader(Request $request)
{
    // Handle search functionality
    $searchTerm = $request->input('search');

    // Search for Household Leaders based on a field (like name or ID)
    $householdLeaders = HouseholdLeader::with('voter') // Eager load the related voter
        ->whereHas('voter', function ($query) use ($searchTerm) {
            $query->where('name', 'like', '%' . $searchTerm . '%'); // Adjust this as needed
        })
        ->get();

    // Return the results to the view
    return view('tagging_household_members', compact('householdLeaders'));
}

public function destroy($id)
{
    // Find and delete the voter
    $voter = HouseholdMember::where('household_member_id', $id)->firstOrFail();
    $voter->delete();

    // Update the session to remove the deleted member
    $taggedMembers = session('taggedMembers', []);
    $updatedMembers = collect($taggedMembers)->reject(function ($member) use ($id) {
        return $member['household_member_id'] == $id;
    });

    session(['taggedMembers' => $updatedMembers]);

    return redirect()->back()->with('success', 'Voter deleted successfully.');
}


public function deletePurokLeader($id)
{
    
    $purokLeader = PurokLeader::where('purok_leader_id' , $id);

    if ($purokLeader) {
        // Delete the Purok Leader
        $purokLeader->delete();

        // Fetch the updated list of purok leaders for the selected coordinator
        $coordinatorId = session('selected_coordinator')->id;
        $purokLeaders = PurokLeader::where('coordinator_id', $coordinatorId)->get();

        // Update the session with the new list of purok leaders
        session(['purokLeaders' => $purokLeaders]);

        return redirect()->back()->with('success', 'Purok Leader deleted successfully!');
    }

    return redirect()->back()->with('error', 'Purok Leader not found.');
}







public function searchLeader(Request $request)
{
    $barangays = Barangay::all();

    $barangay = $request->input('barangay');
    $first_name = $request->input('first_name');
    $last_name = $request->input('last_name');

    // Check if a search is performed
    $hasSearch = !empty($barangay) || !empty($first_name) || !empty($last_name);

    // Fetch voters only if a search was performed
    $voters = collect(); // Default to empty collection

    if ($hasSearch) {
        $voters = MasterList::when($barangay, function ($query, $barangay) {
                return $query->where('barangay', $barangay); // Exact match for faster filtering
            })
            ->when($first_name, function ($query, $first_name) {
                return $query->where('first_name', 'like', "{$first_name}%"); // Optimized search
            })
            ->when($last_name, function ($query, $last_name) {
                return $query->where('last_name', 'like', "{$last_name}%");
            })
            ->where(function ($query) {
                // Only get voters who are tagged as leaders
                $query->whereHas('coordinator')
                    ->orWhereHas('purokLeader')
                    ->orWhereHas('householdLeader');
            })
            ->with(['coordinator', 'purokLeader', 'householdLeader']) // Eager load relationships
            ->paginate(10); // Pagination for performance
    }

    // Return the view with filtered leaders and barangays
    return view('admin.search_leader', compact('voters', 'barangays', 'hasSearch'));
}



public function selectLeader(Request $request)
{

    
    $leader = HouseholdLeader::where('household_leader_id', $request->leader_id)->first();

    $taggedMembers = HouseholdMember::where('household_leader_id', $request->leader_id)->get();
    
    // Store in session
    session([
        'selectedLeader' => $leader,
        'taggedMembers' => $taggedMembers
    ]);

    return redirect()->back()->with('success', 'Household Leader selected successfully.');
}



}


