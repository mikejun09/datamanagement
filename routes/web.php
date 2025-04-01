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
use App\Http\Controllers\UsersController;
use App\Http\Controllers\UserTaggingController;

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
    Route::get('/get-voters/{barangay}', [AdminController::class, 'getVoters']);


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
Route::post('/tag-household-members-new', [HouseholdMemberController::class, 'tagHouseholdMembers'])->name('tagHouseholdMembers_new');

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

// =============create user============

Route::get('/create_user', [AdminController::class, 'create_user'])->name('create_user');
Route::post('/add_user', [AdminController::class, 'store_user'])->name('add_user');


Route::get('/searchHouseholdMembers', [PageController::class, 'searchHouseholdMembers'])->name('searchHouseholdMembers');

Route::get('/refresh-tagged-members', [HouseholdMemberController::class, 'refreshTaggedMembers'])->name('refresh.tagged.members');

Route::delete('/household-leader/{id}', [PageController::class, 'destroy'])->name('household-leader.destroy');


Route::get('/get-overall-total', function () {
    $overallTotal = MasterList::whereHas('coordinator')
        ->orWhereHas('purokLeader')
        ->orWhereHas('householdLeader')
        ->orWhereHas('householdMember')
        ->distinct('id')
        ->count('id');

    return response()->json(['overallTotal' => $overallTotal]);
});


});

// 🟢 User Routes (Protected)
Route::middleware(['auth', 'user'])->group(function () {
   

    Route::get('/user_voters', [UsersController::class, 'index'])->name('user_voters');
    Route::post('/user_add-to-coordinator', [UserTaggingController::class, 'addToCoordinator'])->name('user_coordinator.add');
    Route::delete('/user_coordinator/{id}', [UserTaggingController::class, 'destroy'])->name('user_deleteCoordinator');


    // =======================purok leader=======================


    Route::get('/user_purok_leader', [UsersController::class, 'purok_leader'])->name('user_purok_leader');
    Route::post('/user-select-coordinator', [UserTaggingController::class, 'selectCoordinator'])->name('user_select.coordinator');
    Route::delete('/user-purok-leader/{id}', [UserTaggingController::class, 'deletePurokLeader'])->name('user_deletePurokLeader');
    Route::post('/user-purok-leader/store', [UserTaggingController::class, 'storePurokLeader'])->name('user_purok_leader.store');
    Route::get('/user_searchHouseholdMembers', [UserTaggingController::class, 'searchHouseholdMembers'])->name('user_searchHouseholdMembers');



    // =========================household leader======================

    Route::get('/user_household_leader', [UsersController::class, 'household_leader'])->name('user_household_leader');
    Route::post('/user_select_purok_leader', [UserTaggingController::class, 'select_purok_leader'])->name('user_select_purok_leader');
    Route::post('/user_storeHouseholdLeader', [UserTaggingController::class, 'storeHouseholdLeader'])->name('user_storeHouseholdLeader');
    Route::delete('/user_voter/{id}', [UserTaggingController::class, 'user_destroy'])->name('user_voter.destroy');
    Route::post('/user-tag-household-members', [UserTaggingController::class, 'tagHouseholdMembers'])->name('user_tagHouseholdMembers');


    // =============================household member=======================

    Route::get('/user_household_member', [UsersController::class, 'household_member'])->name('user_household_member');
    Route::get('/user_search-leader', [UserTaggingController::class, 'searchLeader'])->name('user_search-leader');
    Route::post('/user-select-leader', [UserTaggingController::class, 'selectLeader'])->name('user_select-leader');
    Route::delete('/member-voter/{id}', [UserTaggingController::class, 'member_destroy'])->name('member_voter.destroy');
    Route::get('/user_searchvoter', [AdminController::class, 'search'])->name('user_searchvoter');
    Route::get('/search_voter', [UsersController::class, 'search_voter'])->name('search_voter');
});

// 🟢 Public Routes
Route::get('/', function () {
    return redirect()->route('login');
});




