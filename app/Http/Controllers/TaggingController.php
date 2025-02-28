<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterList;
use App\Models\Coordinator;
use App\Models\Barangay;
use App\Models\PurokLeader;
use App\Models\HouseholdLeader;
use App\Models\HouseholdMember;

class TaggingController extends Controller
{
    public function index(Request $request)
{   
    $barangays = Barangay::all();
    $coordinator_lists = Coordinator::with('voter')->paginate(5);

    // Get search inputs
    $barangay = $request->input('barangay');
    $first_name = $request->input('first_name');
    $last_name = $request->input('last_name');

    // Only fetch voters if a search was performed
    $voters = MasterList::query();

    if ($barangay || $first_name || $last_name) {
        $voters = $voters
            ->when($barangay, fn($query) => $query->where('barangay', 'like', "%{$barangay}%"))
            ->when($first_name, fn($query) => $query->where('first_name', 'like', "%{$first_name}%"))
            ->when($last_name, fn($query) => $query->where('last_name', 'like', "%{$last_name}%"))
            ->get();
    } else {
        $voters = collect(); // Return an empty collection if no search
    }

    return view('admin.tagging_coordinator', compact('voters', 'barangays', 'coordinator_lists'));
}



    public function addToCoordinator(Request $request)
        {
            
            $request->validate([
                'voter_id' => 'required|exists:tbl_voterslist,id',
            ]);

            $voterId = $request->input('voter_id');

            // Check if the voter is already a coordinator
            $exists = Coordinator::where('coordinator_id', $voterId)->exists();

            if ($exists) {
                return redirect()->route('voters.index')->with('coordinator_exists', $voterId);
            }

            // Insert into coordinator table
            Coordinator::create([
                'coordinator_id' => $voterId,
                'remarks' => 'Barangay Coordinator',
            ]);

            return redirect()->route('voters.index')->with('success', 'Tagged as coordinator successfully.');
        }

public function destroy($id)
    {
        // Find the coordinator by ID
        $coordinator = Coordinator::find($id);
        
        if ($coordinator) {
            // Delete the coordinator
            $coordinator->delete();
            
            // Redirect with a success message
            return redirect()->back()->with('success', 'Coordinator deleted successfully!');
        } else {
            // If coordinator is not found, redirect with an error
            return redirect()->back()->with('error', 'Coordinator not found!');
        }
    }


// ===============purok leader================================

public function purok_leader(Request $request)
{
    $coordinators = Masterlist::whereIn('id', Coordinator::pluck('coordinator_id'))->get();
    $purokLeaders = PurokLeader::with(['voter', 'coordinator.voter'])->get();
    $barangays = Barangay::all();

    $barangay = $request->input('barangay');
    $first_name = $request->input('first_name');
    $last_name = $request->input('last_name');

    // Do not fetch voters unless search criteria are provided
    $voters = collect(); // Empty collection by default
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

    return view('admin.tagging_purok_leader', compact('voters', 'coordinators', 'barangays', 'purokLeaders'));
}






public function selectCoordinator(Request $request)
{
    // Get the selected coordinator ID from the request
    $coordinator = $request->coordinator_id;
    
    // Get the associated voter from MasterList based on the selected coordinator's id
    $voter = MasterList::where('id', $coordinator)->first();
    
    // Store the selected coordinator in the session for later use
    session(['selected_coordinator' => $voter]);

    // Retrieve the PurokLeaders tagged under the selected coordinator
    $purokLeaders = PurokLeader::where('coordinator_id', $coordinator)->get();

    // Store the PurokLeaders in the session
    session(['purokLeaders' => $purokLeaders]);

    // Redirect back with a success message
    return back()->with('message', 'Coordinator selected successfully!');
}




// ====================
public function storePurokLeader(Request $request)
{
    $votersId = $request->voter_id;

    // Check if voter is already a Coordinator
    $existingCoordinator = Coordinator::where('coordinator_id', $votersId)->with('voter')->first();
    
    // Check if voter is already a Purok Leader
    $existingPurokLeader = PurokLeader::where('purok_leader_id', $votersId)->with('coordinator.voter')->first();

    // Check if voter is already a Household Leader
    $existingHouseholdLeader = HouseholdLeader::where('household_leader_id', $votersId)->with('purokLeader.coordinator.voter')->first();

    if ($existingCoordinator) {
        return redirect()->back()->with('error', 'This voter is already assigned as a Coordinator under ' . $existingCoordinator->voter->first_name . ' ' . $existingCoordinator->voter->last_name);
    }

    if ($existingPurokLeader) {
        return redirect()->back()->with('error', 'This voter is already assigned as a Purok Leader under Coordinator ' . $existingPurokLeader->coordinator->voter->first_name . ' ' . $existingPurokLeader->coordinator->voter->last_name);
    }

    if ($existingHouseholdLeader) {
        return redirect()->back()->with('error', 'This voter is already assigned as a Household Leader under Purok Leader ' . $existingHouseholdLeader->purokLeader->voter->first_name . ' ' . $existingHouseholdLeader->purokLeader->voter->last_name);
    }

    // If not assigned, proceed with saving as Purok Leader
    PurokLeader::create([
        'purok_leader_id' => $votersId,
        'coordinator_id' => $request->coordinator_id,  // Ensure coordinator_id is provided
        'remarks' => 'Purok Leader',
    ]);

    // After saving, get the updated PurokLeaders for the selected coordinator
    $purokLeaders = PurokLeader::where('coordinator_id', $request->coordinator_id)->get();

    // Store the PurokLeaders in the session
    session(['purokLeaders' => $purokLeaders]);

    return redirect()->back()->with('success', 'Voter registered successfully as a Purok Leader!');
}


// ======================end of purok leader===========================

// ===================household leader=============================

public function select_purok_leader(Request $request)
{
    // Get Purok Leader ID from the request
    $purok_leader_id = $request->purok_leader_id;

    // If ID is missing, try to find it by name
    if (!$purok_leader_id) {
        $purok_leader = PurokLeader::whereHas('voter', function ($query) use ($request) {
            $query->whereRaw("CONCAT(first_name, ' ', last_name) = ?", [$request->purok_leader_name]);
        })->first();

        if ($purok_leader) {
            $purok_leader_id = $purok_leader->purok_leader_id;
        }
    }

    // If still no ID found, return an error
    if (!$purok_leader_id) {
        return back()->with('error', 'Purok Leader not found.');
    }

    // Get the associated voter from MasterList
    $purokLeader = PurokLeader::with('voter')->where('purok_leader_id', $purok_leader_id)->first();

    // Store the selected Purok Leader in session
    session(['selected_purok_leader' => $purokLeader]);

    // Redirect back with success message
    return back()->with('message', 'Purok Leader selected successfully!');
}

public function household_leader(Request $request)
{
    $purok_leaders = Masterlist::whereIn('id', PurokLeader::pluck('purok_leader_id'))->get();
    $householdLeaders = HouseholdLeader::with('voter', 'purokLeader', 'purokLeader.coordinator')->get();

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
            ->get();
   
    // Pass the voters to the view
    return view('admin.tagging_household_leader', compact('voters', 'purok_leaders' , 'barangays' , 'householdLeaders'));
}


public function storeHouseholdLeader(Request $request)
{
    $votersId = $request->voter_id;

    // Clear session first to prevent old data from persisting
    session()->forget(['showModal', 'householdLeader', 'potentialMembers']);

    // Check if voter is already assigned
    $existingCoordinator = Coordinator::where('coordinator_id', $votersId)->exists();
    $existingPurokLeader = PurokLeader::where('purok_leader_id', $votersId)->exists();
    $existingHouseholdLeader = HouseholdLeader::where('household_leader_id', $votersId)->exists();

    if ($existingCoordinator) {
        return redirect()->back()->with('error', 'This voter is already assigned as a Coordinator.');
    }

    if ($existingPurokLeader) {
        return redirect()->back()->with('error', 'This voter is already assigned as a Purok Leader.');
    }

    if ($existingHouseholdLeader) {
        return redirect()->back()->with('error', 'This voter is already assigned as a Household Leader.');
    }

    // Store new household leader
    $householdLeader = HouseholdLeader::create([
        'household_leader_id' => $votersId,
        'purok_leader_id' => $request->purok_leader_id,
        'remarks' => 'Household Leader',
    ]);

    // Get untagged voters (potential members)
    
    $potentialMembers = MasterList::with(['coordinator', 'purokLeader', 'householdLeader', 'householdMember'])->get();
    // Get the newly tagged household leader with voter details
    $householdLeader = HouseholdLeader::with('voter')->where('household_leader_id', $votersId)->first();

    // Get household members assigned under this household leader
    $taggedMembers = HouseholdMember::where('household_leader_id', $votersId)->get();

    // Set session data only on success
    session([
        'householdLeader' => $householdLeader,
        'potentialMembers' => $potentialMembers,
        'showModal' => true,
        'session_expires_at' => now()->addMinutes(30), // Set expiration to 1 minute from now
    ]);

    return redirect()->back()->with('success', 'Voter registered successfully as a Household Leader!');
}






public function household_member(Request $request)
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

    // Optionally, filter masterList similarly
    $masterList = MasterList::when($barangay, function ($query, $barangay) {
            return $query->where('barangay', 'like', "%{$barangay}%");
        })
        ->when($first_name, function ($query, $first_name) {
            return $query->where('first_name', 'like', "%{$first_name}%");
        })
        ->when($last_name, function ($query, $last_name) {
            return $query->where('last_name', 'like', "%{$last_name}%");
        })
        ->get();


        $potentialMembers = MasterList::all();
        session(['potentialMembers' => $potentialMembers]);
session(['householdLeader' => $request->input('household_leader_id')]);

    return view('admin.search_leader', compact('voters', 'barangays', 'masterList'));
}



public function searchHouseholdLeader(Request $request)
{
    $barangays = Barangay::all();
    // Get search parameters
    $barangay = $request->input('barangay');
    $first_name = $request->input('first_name');
    $last_name = $request->input('last_name');

    // Fetch voters who are tagged as any leader
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
            $query->whereHas('coordinator')
                  ->orWhereHas('purokLeader')
                  ->orWhereHas('householdLeader');
        })
        ->get();

    // Return the results to the view
    return view('admin.tagging_household_members', compact('voters', 'barangays'));
}



}
