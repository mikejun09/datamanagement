<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterList;
use App\Models\Leaders;
use App\Models\Barangay;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function index(Request $request)
{
    // Fetch all voters
    $voters = MasterList::all();
    
    // Fetch all barangays with the count of tagged voters
    $barangays = Barangay::all()->map(function ($barangay) {
        // Count tagged voters in each barangay (Coordinators, Purok Leaders, Household Leaders, Household Members)
        $taggedVotersCount = MasterList::where('barangay', $barangay->barangay)
            ->whereHas('coordinator')
            ->count() + 
            MasterList::where('barangay', $barangay->barangay)
            ->whereHas('purokLeader')
            ->count() +
            MasterList::where('barangay', $barangay->barangay)
            ->whereHas('householdLeader')
            ->count() + 
            MasterList::where('barangay', $barangay->barangay)
            ->whereHas('householdMember')
            ->count();
        
        // Add the tagged voters count to the barangay object
        $barangay->tagged_voters_count = $taggedVotersCount;

        return $barangay;
    });

    return view('admin.index', compact('voters', 'barangays'));
}

public function search(Request $request)
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
        ->get();

    return view('admin.search', compact('voters' , 'barangays'));
}

public function create_user(Request $request){


    return view('admin.create_user');
}



    public function store_user(Request $request)

    {
        

        try {
                $data = new User(); 
                $data->name = $request->input('name');
                $data->email = $request->input('email');
               
                $data->role = 'user';
                $data->password =  Hash::make('password');
               

             
                $data->save();

            // Flash success message to the session
            Session::flash('success', 'Data saved successfully!');
        } catch (\Exception $e) {
            // Handle any exceptions or errors
            Session::flash('error', 'Data was saved but some field is missing. Please try again.');
            Log::error('Error saving data: ' . $e->getMessage());
        }

        
            return back();
        
        
    }

}
