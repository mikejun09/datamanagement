@extends('layouts.app')

@section('content')

<div class="mt-5">

    <h3>Select a Leader</h3>

    <div class="mt-5 col-md-12">
        <form method="GET" action="{{ route('searchHouseholdLeader') }}">
    
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

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="row mt-5 mb-5">
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
                            &nbsp;
                        @endif
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary add-members-btn"
                                data-leader-id="{{ $voter->id }}" 
                                data-bs-toggle="modal" data-bs-target="#householdLeaderModal">
                            Add Members
                        </button>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>
</div>


</div>

{{-- =======================show tagged members================================= --}}

@if(session('householdLeader'))
    <div class="alert alert-info">
        <h4>Tagged Household Leader:</h4>
        <p>
            <h2><strong>{{ session('householdLeader')->voter->first_name }} 
                {{ session('householdLeader')->voter->last_name }}</strong></h2>
        </p>
    </div>
    <div class="row">
        <div class="col-md-10">
        </div>
        <div class="col">
            <button type="button" class="btn btn-primary add-members-btn" 
                data-leader-id="{{ session('householdLeader')->household_leader_id ?? '' }}"
                data-leader-name="{{ session('householdLeader')->voter->first_name ?? '' }} {{ session('householdLeader')->voter->last_name ?? '' }}"
                data-bs-toggle="modal" data-bs-target="#householdLeaderModal">
                Add Members
            </button>
        </div>
    </div>
@endif

@if(session('householdLeader') && session('taggedMembers'))
    @php
        // Get the household leader ID
        $leaderId = session('householdLeader')->household_leader_id;

        // Filter tagged members that belong to the selected leader
        $filteredMembers = collect(session('taggedMembers'))->filter(function ($member) use ($leaderId) {
            return $member->household_leader_id == $leaderId;
        });
    @endphp

    @if($filteredMembers->count() > 0)
        <h3>Tagged Members</h3>

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
                    @foreach($filteredMembers as $member)
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
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
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




<!-- Bootstrap Extra Large Modal -->
<div class="modal fade" id="householdLeaderModal" tabindex="-1" aria-labelledby="householdLeaderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tag Members <span id="leaderName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <!-- Search Form -->
                <form id="searchMembersForm">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="last_name" placeholder="Last Name">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="first_name" placeholder="First Name">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="barangay">
                                <option value="">All Barangay</option>
                                @foreach ($barangays as $barangay)
                                    <option value="{{ $barangay->barangay }}">{{ $barangay->barangay }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </form>

                <!-- Members Table -->
                <form id="tagMembersForm" action="{{ route('tagHouseholdMembers') }}" method="POST">
                    @csrf
                    <input type="hidden" name="household_leader_id" id="household_leader_id">
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
                        <tbody id="membersList">
                            <tr>
                                <td colspan="8" class="text-center">No data available. Please search.</td>
                                <td style="display:none;"></td>
                                <td style="display:none;"></td>
                                <td style="display:none;"></td>
                                <td style="display:none;"></td>
                                <td style="display:none;"></td>
                                <td style="display:none;"></td>
                                <td style="display:none;"></td>
                            </tr>
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


<script>
 $(document).ready(function () {
    $('#searchMembersForm').submit(function (e) {
        e.preventDefault();
        let formData = $(this).serialize(); // Serialize form data

        $.ajax({
            url: "{{ route('searchHouseholdMembers') }}",
            method: "GET",
            data: formData,
            beforeSend: function () {
                $('#membersList').html('<tr><td colspan="8" class="text-center">Loading...</td></tr>');
            },
            success: function (data) {
                console.log(data); // Debugging - Check the response in console
                let rows = '';

                if (data.length === 0) {
                    rows = '<tr><td colspan="8" class="text-center">No members found.</td></tr>';
                } else {
                    data.forEach(member => {
                        let status = '&nbsp;'; // Default empty if no status

                        if (member.coordinator) {
                            status = 'BC';
                        } else if (member.purok_leader) {
                            status = 'PL';
                        } else if (member.household_leader) {
                            status = 'HL';
                        } else if (member.household_member) {
                            status = 'HM';
                        }

                        rows += `
                            <tr>
                                <td><input type="checkbox" name="members[]" value="${member.id}"></td>
                                <td>${member.last_name}</td>
                                <td>${member.first_name}</td>
                                <td>${member.middle_name || ''}</td>
                                <td>${member.address || ''}</td>
                                <td>${member.barangay || ''}</td>
                                <td>${member.precinct || ''}</td>
                                <td>${status}</td>
                            </tr>`;
                    });
                }

                $('#membersList').html(rows);
            },
            error: function () {
                $('#membersList').html('<tr><td colspan="8" class="text-center text-danger">Error loading data.</td></tr>');
            }
        });
    });
});


</script>


<script>
   document.addEventListener("DOMContentLoaded", function() {
    // Ensure leader ID is set correctly each time the modal is opened
    document.querySelectorAll(".add-members-btn").forEach(button => {
        button.addEventListener("click", function() {
            let leaderId = this.getAttribute("data-leader-id");
            let leaderName = this.getAttribute("data-leader-name");

            if (leaderId) {
                document.getElementById("household_leader_id").value = leaderId;
                document.getElementById("leaderName").innerText = leaderName;
            }
        });
    });

    @if(session('showModal') && session('householdLeader'))
        var modal = new bootstrap.Modal(document.getElementById('householdLeaderModal'));
        document.getElementById('leaderName').innerText = "{{ session('householdLeader')->voter->first_name }} {{ session('householdLeader')->voter->last_name }}";
        document.getElementById('household_leader_id').value = "{{ session('householdLeader')->household_leader_id }}";
        modal.show();
    @endif

    // Select/Deselect all checkboxes
    document.getElementById("checkAll").addEventListener("click", function() {
        let checkboxes = document.querySelectorAll("input[name='members[]']");
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });

    // Fix issue where modal backdrop remains after closing
    var modalElement = document.getElementById('householdLeaderModal');
    modalElement.addEventListener('hidden.bs.modal', function () {
        var backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.remove();
        }
        document.body.classList.remove('modal-open'); // Ensure scrolling is re-enabled
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