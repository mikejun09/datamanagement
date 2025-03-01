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
    
        // Fetch voters based on search criteria
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
       
        return view('admin.tagging_household_leader', compact('voters', 'purok_leaders', 'barangays', 'householdLeaders'));
    }
    

   


}
