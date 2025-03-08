<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterList;
use App\Models\Coordinator;
use App\Models\Barangay;
use App\Models\PurokLeader;
use App\Models\HouseholdLeader;
use App\Models\HouseholdMember;

class UsersController extends Controller
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

        return view('user.user_tagging_coordinator', compact('voters' , 'barangays', 'coordinator_lists'));    

    }


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
   
    // Pass the voters to the view
    return view('user.user_tagging_purok_leader', compact('voters', 'coordinators' , 'barangays', 'purokLeaders'));
}

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
       
        // Pass the voters to the view
        return view('user.user_tagging_householdleader', compact('voters', 'purok_leaders' , 'barangays' , 'householdLeaders'));
    }


    public function household_member(Request $request)
{
    $barangays = Barangay::all();

    $barangay = $request->input('barangay');
    $first_name = $request->input('first_name');
    $last_name = $request->input('last_name');

    // Check if the user performed a search
    $hasSearch = $barangay || $first_name || $last_name;

    // Fetch voters only if a search was performed
    $voters = collect(); // Default to empty collection

    if ($hasSearch) {
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
            ->with(['coordinator', 'purokLeader', 'householdLeader'])
            ->paginate(10);
    }

    return view('user.user_tagging_householdmember', compact('voters', 'barangays', 'hasSearch'));
  
}


public function search_voter(Request $request)
{
    $barangays = Barangay::all();

    $barangay = $request->input('barangay');
    $first_name = $request->input('first_name');
    $last_name = $request->input('last_name');

    // Only fetch voters if at least one search parameter is provided
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

    return view('user.user_search', compact('voters', 'barangays'));
}




}
