@extends('layouts.app')

@section('content')
<div class="mt-5 col-md-12">
    <form method="GET" action="{{ route('search-leader') }}">

        <div class="d-flex mb-2">
            <div class="col-md-4 me-2">
                <select class="form-select" name="barangay" id="barangay">
                    <option value="" selected>All Barangay</option>
                    @foreach ($barangays as $barangay)
                        <option value="{{ $barangay->barangay }}" {{ request('barangay') == $barangay->barangay ? 'selected' : '' }}>
                            {{ $barangay->barangay }}
                        </option>
                    @endforeach
                </select>
            </div>
    
            <div class="col me-2">
                <input type="text" name="last_name" class="form-control" placeholder="Last Name" value="{{ request('last_name') }}">
            </div>
    
            <div class="col me-2">
                <input type="text" name="first_name" class="form-control" placeholder="First Name" value="{{ request('first_name') }}">
            </div>
    
            <div>
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
    
    </form>
</div>

<div class="row mt-5 mb-5">
    <h3>Select Household Leader</h3>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <table class="table table-hover table-bordered table-striped" id="example">
        <thead>
            <tr>
          
                <th>Last Name</th>
                <th>First Name</th>
                <th>Middle Name</th>
                <th>Address</th>
                <th>Barangay</th>
                <th>Precinct</th>
                <th>Status</th>
                <th>Action</th>
                
            </tr>
        </thead>
        <tbody>
            @foreach($voters as $voter)
                <tr>
                   
                    <td>{{ $voter->last_name }}</td>
                    <td>{{ $voter->first_name }}</td>
                    <td>{{ $voter->middle_name }}</td>
                    <td>{{ $voter->address }}</td>
                    <td>{{ $voter->barangay }}</td>
                    <td>{{ $voter->precinct }}</td>
                    <td>
                        @if($voter->coordinator)
                            BC
                        @elseif($voter->purokLeader)
                            PL
                        @elseif($voter->householdLeader)
                            HL
                        @elseif($voter->householdMember)  
                            HM
                        @else
                            &nbsp;  <!-- Empty space for not tagged voters -->
                        @endif
                    </td>
                    <td>
                        <form method="POST" action="{{ route('select-leader') }}">
                            @csrf
                            <input type="hidden" name="leader_id" value="{{ $voter->id }}">
                            <button type="submit" class="btn btn-primary">SELECT HOUSEHOLD LEADER</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Display selected leader's information -->
@if(session('selectedLeader'))
    <div class="alert alert-info">
        <h4>Selected Household Leader:</h4>
        <p>
        <h2><strong>{{ session('selectedLeader')->voter->first_name }} 
        {{ session('selectedLeader')->voter->last_name }}</strong></h2>
        </p>
    </div>
@endif

<!-- Display members under the selected leader -->
@if(session('selectedLeader') && session('taggedMembers'))
    @php
        $leader = session('selectedLeader');
        $taggedMembers = session('taggedMembers');
    @endphp

    <h3>Tagged Members</h3>

    <div class="row">
    <div class="col text-end">
                <button type="button" class="btn btn-primary add-members-btn mb-3 mt-2"
                    data-leader-id="{{ session('selectedLeader')->voter->id ?? '' }}"
                    data-leader-name="{{ session('householdLeader')->voter->first_name ?? '' }} {{ session('householdLeader')->voter->last_name ?? '' }}"
                    data-bs-toggle="modal" data-bs-target="#householdLeaderModal">
                    Add Members
                </button>

    </div>
</div>

    

    @if($taggedMembers->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Address</th>
                        <th>Barangay</th>
                        <th>Precinct</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($taggedMembers as $member)
                        @if($member->voter) {{-- Check if voter data exists --}}
                            <tr id="row-{{ $member->voter->id }}">
                                <td>{{ $member->voter->last_name }}</td>
                                <td>{{ $member->voter->first_name }}</td>
                                <td>{{ $member->voter->middle_name }}</td>
                                <td>{{ $member->voter->address }}</td>
                                <td>{{ $member->voter->barangay }}</td>
                                <td>{{ $member->voter->precinct }}</td>
                                <td>
                                    <form action="{{ route('voter.destroy', $member->voter->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this voter?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn1 btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p style="color: gray;">No members tagged under this Household Leader yet.</p>
    @endif
@endif



<!-- ==============================================add members======================================================== -->


<!-- Modal -->
<div class="modal fade" id="householdLeaderModal" tabindex="-1" aria-labelledby="householdLeaderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tag Members <span id="leaderName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="tagMembersForm" action="{{ route('tagHouseholdMembers') }}" method="POST">
                    @csrf
                    <input type="hidden" name="household_leader_id" id="household_leader_id">
                    <!-- Your table of potential members will go here -->
                    <table id="membersTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="checkAll"></th>
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Address</th>
                                <th>Barangay</th>
                                <th>Precinct</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(session('potentialMembers', []) as $member)
                                <tr>
                                    <td><input type="checkbox" name="members[]" value="{{ $member->id }}"></td>
                                    <td>{{ $member->last_name }}</td>
                                    <td>{{ $member->first_name }}</td>
                                    <td>{{ $member->middle_name }}</td>
                                    <td>{{ $member->address }}</td>
                                    <td>{{ $member->barangay }}</td>
                                    <td>{{ $member->precinct }}</td>
                                    <td> 
                                        @if($member->coordinator)
                                            BC
                                        @elseif($member->purokLeader)
                                            PL
                                        @elseif($member->householdLeader)
                                            HL
                                        @elseif($member->householdMember)  
                                            HM
                                        @else
                                            &nbsp;
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Tag Selected Members</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- ========================================= -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // When the "Add Members" button is clicked
        document.querySelectorAll(".add-members-btn").forEach(button => {
            button.addEventListener("click", function() {
                // Get the household leader ID and leader name from the button's data attributes
                let leaderId = this.getAttribute("data-leader-id");
                let leaderName = this.getAttribute("data-leader-name");

                // Set the leader ID in the hidden input inside the modal
                document.getElementById("household_leader_id").value = leaderId;

                // Set the leader's name in the modal title
                document.getElementById("leaderName").innerText = leaderName;
            });
        });

        // Select/Deselect all checkboxes
        document.getElementById("checkAll").addEventListener("click", function() {
            let checkboxes = document.querySelectorAll("input[name='members[]']");
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });
    });
</script>

    


    @if (session('showModal'))
    <script>
        // Trigger your modal here
        $('#householdLeaderModal').modal('show');  // Adjust this line to match your modal trigger
        // Clear the session key after showing the modal
        @php
            session()->forget('showModal');
        @endphp
    </script>
@endif


<script>
    $(document).ready(function() {
        $('#example').DataTable(); // Replace '#example' with your table's ID or class
    });

    $(document).ready(function() {
        $('#membersTable').DataTable(); // Replace '#example' with your table's ID or class
    });
</script>
@endsection
