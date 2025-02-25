<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TaggingController;
use App\Http\Controllers\HouseholdMemberController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\VoterController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ReportController;

// 🟢 Login Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit'); // Handle login request

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// 🟢 Admin Routes (Protected)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin_index', [AdminController::class, 'index'])->name('admin_index');

    Route::get('/voters', [TaggingController::class, 'index'])->name('voters.index');
    Route::post('/add-to-coordinator', [TaggingController::class, 'addToCoordinator'])->name('coordinator.add');

    Route::get('/admin/tagging-purok-leader', [TaggingController::class, 'showPurokLeaderForm'])->name('admin.tagging_purok_leader');

    // User Management
    Route::get('/admin/users/{user}/assign-barangays', [AdminController::class, 'showAssignBarangaysForm'])->name('admin.showAssignBarangaysForm');

    Route::get('/purok_leader', [TaggingController::class, 'purok_leader'])->name('purok_leader');
Route::get('/household_leader', [PageController::class, 'household_leader'])->name('household_leader');

Route::delete('/coordinator/{id}', [TaggingController::class, 'destroy'])->name('deleteCoordinator');
Route::post('/select-coordinator', [TaggingController::class, 'selectCoordinator'])->name('select.coordinator');
Route::post('/purok-leader/store', [TaggingController::class, 'storePurokLeader'])->name('purok_leader.store');

Route::get('/search-voters', [VoterController::class, 'searchVoters']);

Route::post('/tag-household-members', [HouseholdMemberController::class, 'tagHouseholdMembers'])->name('tagHouseholdMembers');

Route::post('/select_purok_leader', [TaggingController::class, 'select_purok_leader'])->name('select_purok_leader');
Route::post('/storeHouseholdLeader', [TaggingController::class, 'storeHouseholdLeader'])->name('storeHouseholdLeader');
Route::post('/tagHouseholdMembers1', [HouseholdMemberController::class, 'tagHouseholdMembers1'])->name('tagHouseholdMembers1');

Route::get('/household_member', [TaggingController::class, 'household_member'])->name('household_member');

Route::get('/searchHouseholdLeader', [TaggingController::class, 'searchHouseholdLeader'])->name('searchHouseholdLeader');


Route::get('/reports', [ReportsController::class, 'generateTaggedVotersReport'])->name('reports');

Route::delete('/voter/{id}', [HouseholdMemberController::class, 'destroy'])->name('voter.destroy');

Route::delete('/purokLeader/{id}', [HouseholdMemberController::class, 'destroy_pl'])->name('purokLeader.destroy');
Route::get('/report/barangay', [ReportController::class, 'generateBarangayReport'])->name('barangay.report');


Route::get('/search-leader', [HouseholdMemberController::class, 'searchLeader'])->name('search-leader');
Route::get('/search-coordinator', [ReportsController::class, 'generateTaggedVotersReport'])->name('search-coordinator');

// Route to handle the selection of the Household Leader
Route::post('/select-leader', [HouseholdMemberController::class, 'selectLeader'])->name('select-leader');
Route::get('select-coordinator1', [ReportsController::class, 'selectCoordinator1'])->name('select-coordinator1');

// =======delete============
Route::delete('/purok-leader/{id}', [HouseholdMemberController::class, 'deletePurokLeader'])->name('deletePurokLeader');

// ============search===========
Route::get('/search', [AdminController::class, 'search'])->name('search');

Route::get('/generate-pdf', [ReportController::class, 'generatePDF'])->name('generate-pdf');
Route::get('/preview-pdf', [ReportController::class, 'previewPDF'])->name('preview-pdf');

Route::get('/reports/pdf', [ReportController::class, 'exportPDF'])->name('reports.pdf');


});

// 🟢 User Routes (Protected)
Route::middleware(['auth', 'user'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');



});

// 🟢 Public Routes
Route::get('/', function () {
    return redirect()->route('login');
});




