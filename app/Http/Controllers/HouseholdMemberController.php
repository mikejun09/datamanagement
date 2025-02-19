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
            return redirect()->back()->with('error', 'No members selected.');
        }
    
        foreach ($selectedMembers as $memberId) {
            // Check if the member is already tagged as a coordinator, purok leader, household leader, or household member
            $existingCoordinator = Coordinator::where('coordinator_id', $memberId)->exists();
            $existingPurokLeader = PurokLeader::where('purok_leader_id', $memberId)->exists();
            $existingHouseholdLeader = HouseholdLeader::where('household_leader_id', $memberId)->exists();
            $existingHouseholdMember = HouseholdMember::where('household_member_id', $memberId)->exists();
    
            if ($existingCoordinator) {
                return redirect()->back()->with('error', 'This voter is already assigned as a Coordinator.');
            }
    
            if ($existingPurokLeader) {
                return redirect()->back()->with('error', 'This voter is already assigned as a Purok Leader.');
            }
    
            if ($existingHouseholdLeader) {
                return redirect()->back()->with('error', 'This voter is already assigned as a Household Leader.');
            }
    
            if ($existingHouseholdMember) {
                return redirect()->back()->with('error', 'This voter is already assigned as a Household Member.');
            }
    
            // Only create HouseholdMember if not tagged already
            HouseholdMember::create([
                'household_member_id' => $memberId,
                'household_leader_id' => $householdLeaderId,
            ]);
        }
    
        // After adding members, get the updated tagged members
        $householdLeader = HouseholdLeader::with('voter')->where('household_leader_id', $householdLeaderId)->first();
        $taggedMembers = HouseholdMember::where('household_leader_id', $householdLeaderId)->get();
    
        session([
            'householdLeader' => $householdLeader,
            'taggedMembers' => $taggedMembers,
            'session_expires_at' => now()->addMinutes(30),
        ]);
    
        return redirect()->back()->with('success', 'Household members successfully tagged!');
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
        ->where(function ($query) {
            // Only get voters who are tagged as any of the leaders
            $query->whereHas('coordinator')
                ->orWhereHas('purokLeader')
                ->orWhereHas('householdLeader');
        })
        ->with(['coordinator', 'purokLeader', 'householdLeader']) // Eager load relationships
        ->paginate(10); // Using pagination for better performance with large data sets

    // Return the view with filtered leaders and barangays
    return view('admin.search_leader', compact('voters', 'barangays'));
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


