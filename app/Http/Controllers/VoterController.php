<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterList;


class VoterController extends Controller
{
    public function searchVoters(Request $request)
{
    $query = MasterList::query();

    if ($request->last_name) {
        $query->where('last_name', 'LIKE', "%{$request->last_name}%");
    }
    if ($request->first_name) {
        $query->where('first_name', 'LIKE', "%{$request->first_name}%");
    }

    return response()->json($query->limit(10)->get());
}

}
