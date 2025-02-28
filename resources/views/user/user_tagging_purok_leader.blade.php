@extends('layouts.app_user')

@section('content')

<div class="mt-5">
    <label><h1>TAGGING OF PUROK LEADER</h1> </label> 
</div>

<!-- Select Coordinator Form -->
<div class="mt-5 col-md-10">
    <h3>Select a Barangay Coordinator</h3>
    <form method="POST" action="{{ route('user_select.coordinator') }}">
        @csrf
        <div class="d-flex mb-2">
            <div class="col-md-4 me-3">
                <select class="form-select" name="coordinator_id" required>
                    <option value="" selected disabled>Select Coordinator</optiown>
                    @foreach ($coordinators as $coordinator)
                        <option value="{{ $coordinator->id }}" {{ request('coordinator_id') == $coordinator->id ? 'selected' : '' }}>
                            {{ $coordinator->first_name }} {{ $coordinator->last_name }} - {{ $coordinator->barangay }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-success">Select Coordinator</button>
            </div>
        </div>
    </form>
</div>

<!-- Display Selected Coordinator -->
@if(session('selected_coordinator'))
    <div class="alert alert-info">
        Selected Coordinator: 
        <strong>
            <label for="" style="font-size: 30px;">
                {{ session('selected_coordinator')->first_name }} 
                {{ session('selected_coordinator')->last_name }}
            </label>
        </strong>
    </div>
@endif





<div class="container col-md-10">
@if(session('selected_coordinator') && session('purokLeaders'))
    <h3>Purok Leaders Tagged Under {{ session('selected_coordinator')->first_name }} {{ session('selected_coordinator')->last_name }}</h3>

    @if(session('purokLeaders')->count() > 0)
                <table class="table table-striped" id="example1">
                <thead>
                    <tr>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Barangay</th>
                        <th>Precinct</th>
                        <th>Action</th> <!-- Add a column for the delete button -->
                    </tr>
                </thead>
                <tbody>
                    @foreach(session('purokLeaders') as $purokLeader)
                        <tr>
                            <td>{{ $purokLeader->voter->last_name }}</td>
                            <td>{{ $purokLeader->voter->first_name }}</td>
                            <td>{{ $purokLeader->voter->middle_name }}</td>
                            <td>{{ $purokLeader->voter->barangay }}</td>
                            <td>{{ $purokLeader->voter->precinct }}</td>
                            <td>
                                <form action="{{ route('user_deletePurokLeader', $purokLeader->voter->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this Purok Leader?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn1 btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

    @else
        <p>No Purok Leaders tagged under this Coordinator yet.</p>
    @endif
@endif
</div>

<div class="mt-5 col-md-12">
    <form method="GET" action="{{ route('user_purok_leader') }}">

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

<!-- Table to Tag a Purok Leader -->
<div class="row mt-5 mb-5">
    <h3>Tag a Purok Leader</h3>


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
        @if($voters->isEmpty())
            <tr>
                <td colspan="8" class="text-center">Please enter search criteria to display results.</td>
                <td style="display:none;"></td>
                <td style="display:none;"></td>
                <td style="display:none;"></td>
                <td style="display:none;"></td>
                <td style="display:none;"></td>
                <td style="display:none;"></td>
                <td style="display:none;"></td>
                
            </tr>
        @else
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
                        @if(session('selected_coordinator'))
                            <form action="{{ route('user_purok_leader.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="voter_id" value="{{ $voter->id }}">
                                <input type="hidden" name="coordinator_id" value="{{ session('selected_coordinator')->id }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-person-plus-fill"></i> Add as Purok Leader
                                </button>
                            </form>
                        @else
                            <button class="btn btn-secondary" disabled>Select a Coordinator First</button>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>


</div>

<script>
    $(document).ready(function() {
        $('#example').DataTable();
        $('#example1').DataTable(); // Initialize DataTable
    });
</script>

@endsection
