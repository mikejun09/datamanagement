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
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index(Request $request)
{
    // Get all barangays first
    $barangays = Barangay::all();

    return view('admin.index', compact('barangays'));
}

public function countVoters($barangay)
{

    try {
        // Count tagged voters in the given barangay
        $tagged = MasterList::where('barangay', $barangay)
            ->where(function ($query) {
                $query->whereHas('coordinator')
                      ->orWhereHas('purokLeader')
                      ->orWhereHas('householdLeader')
                      ->orWhereHas('householdMember');
            })->count();

        return response()->json([
            'barangay' => $barangay,
            'tagged_count' => $tagged,
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'error' => 'Failed to get data',
            'message' => $e->getMessage(),
        ], 500);
    }
}

  

public function search(Request $request)
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


    public function getTaggedCount(Request $request)
{
    $barangay = $request->input('barangay');

    // Total in barangay
    $total = MasterList::where('barangay', $barangay)->count();

    // Tagged count
    $tagged = MasterList::where('barangay', $barangay)
        ->where(function ($query) {
            $query->whereHas('coordinator')
                  ->orWhereHas('purokLeader')
                  ->orWhereHas('householdLeader')
                  ->orWhereHas('householdMember');
        })->count();

    $untagged = $total - $tagged;

    return response()->json([
        'tagged' => $tagged,
        'untagged' => $untagged
    ]);
}


public function getOverallTaggedCount()
{
    $count = DB::table('tbl_voterslist as v')
        ->where(function ($query) {
            $query->whereExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('tbl_brgy_coordinator')
                    ->whereColumn('coordinator_id', 'v.id');
            })
            ->orWhereExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('tbl_brgy_purok_leader')
                    ->whereColumn('purok_leader_id', 'v.id');
            })
            ->orWhereExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('tbl_brgy_household_leader')
                    ->whereColumn('household_leader_id', 'v.id');
            })
            ->orWhereExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('tbl_brgy_household_member')
                    ->whereColumn('household_member_id', 'v.id');
            });
        })
        ->count();

    return response()->json(['count' => $count]);
}



}
