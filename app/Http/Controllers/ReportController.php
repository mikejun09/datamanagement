<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Barangay;
use App\Models\Coordinator;
use App\Models\PurokLeader;
use App\Models\HouseholdLeader;
use App\Models\HouseholdMember;

class ReportController extends Controller
{
    public function generateBarangayReport()
    {
        // Fetch all coordinators with related purok leaders, household leaders, and members
        $coordinators = Coordinator::with([
            'voter', // Coordinator's voter details
            'purokLeaders.voter', // Purok Leader's voter details
            'purokLeaders.householdLeaders.voter', // Household Leader's voter details
            'purokLeaders.householdLeaders.householdMembers.voter' // Household Members' voter details
        ])->get();
    
        // Generate PDF
        $pdf = Pdf::loadView('reports.barangay_report', compact('coordinators'));
    
        // Return PDF as a download
        return $pdf->download('barangay_report.pdf');
    }

        public function generatePDF(Request $request)
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

                // Load the view and pass the data
                $pdf = Pdf::loadView('reports.barangay_report', compact('coordinator'));

                // Return the PDF for download
                return $pdf->stream('barangay_report.pdf'); // Or use `download('filename.pdf')` to force download

           
            }



            public function exportPDF(Request $request)
    {
        // Get session data
        $coordinator = session('selected_coordinator');
        $purokLeaders = session('purokLeaders', collect());
        $householdLeaders = session('householdLeaders', collect());

        if (!$coordinator) {
            return redirect()->back()->with('error', 'No coordinator selected for PDF.');
        }

        // Load the Blade template and pass data
        $pdf = PDF::loadView('reports.pdf', compact('coordinator', 'purokLeaders', 'householdLeaders'));

        return $pdf->stream('report.pdf'); // Display PDF in browser
        // return $pdf->download('report.pdf'); // Uncomment this line to force download
    }


}
