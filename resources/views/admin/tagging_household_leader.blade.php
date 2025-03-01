@extends('layouts.app')

@section('content')

<div class="mt-5">
    <label><h1>TAGGING OF HOUSEHOLD LEADER</h1> </label> 
</div>

<div class="mt-5 col-md-10">
    <h3>Select a Barangay Purok Leader</h3>
    <form method="POST" action="{{ route('select_purok_leader') }}">
        @csrf
        <div class="d-flex mb-2">
            <div class="col-md-4 me-3">
                <input list="purokLeaders" name="purok_leader_name" id="purok_leader_name" class="form-control" required>
                <input type="hidden" name="purok_leader_id" id="purok_leader_id">
                <datalist id="purokLeaders">
                    @foreach ($purok_leaders as $purok_leader)
                        <option data-id="{{ $purok_leader->id }}" value="{{ $purok_leader->first_name }} {{ $purok_leader->last_name }}"></option>
                    @endforeach
                </datalist>
            </div>
            <div>
                <button type="submit" class="btn btn-success">Select Purok Leader</button>
            </div>
        </div>
    </form>
</div>

@if(session('selected_purok_leader'))
    <div class="alert alert-info">
        Selected Purok Leader: 
        <strong>
            <label style="font-size: 30px;">
                {{ optional(session('selected_purok_leader')->voter)->first_name }} 
                {{ optional(session('selected_purok_leader')->voter)->last_name }}
            </label>
        </strong>
    </div>
@endif

<script>
    document.getElementById('purok_leader_name').addEventListener('input', function() {
        let options = document.querySelectorAll('#purokLeaders option');
        let inputVal = this.value;
        let hiddenInput = document.getElementById('purok_leader_id');

        hiddenInput.value = ''; 
        options.forEach(option => {
            if (option.value === inputVal) {
                hiddenInput.value = option.getAttribute('data-id'); 
            }
        });
    });
</script>

{{-- Search Form --}}
<div class="mt-5 col-md-12">
    <form method="GET" action="{{ route('household_leader') }}">
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

{{-- Voter List Table --}}
<div class="row mt-5 mb-5">
    <h3>Tag a Household Leader</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
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
            @if(request('last_name') || request('first_name'))
                @foreach($voters as $voter)
                    <tr>
                        <td>{{ $voter->last_name }}</td>
                        <td>{{ $voter->first_name }}</td>
                        <td>{{ $voter->middle_name }}</td>
                        <td>{{ $voter->address }}</td>
                        <td>{{ $voter->barangay }}</td>
                        <td>{{ $voter->precinct }}</td>
                        <td>
                            @if($voter->coordinator) BC
                            @elseif($voter->purokLeader) PL
                            @elseif($voter->householdLeader) HL
                            @elseif($voter->householdMember) HM
                            @else &nbsp;
                            @endif
                        </td>
                        <td>
                            @if(session('selected_purok_leader'))
                                <form action="{{ route('storeHouseholdLeader') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="voter_id" value="{{ $voter->id }}">
                                    <input type="hidden" name="purok_leader_id" value="{{ session('selected_purok_leader')->id }}">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-person-plus-fill"></i> Add as Household Leader
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-secondary" disabled>Select a Purok Leader First</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="8" class="text-center">Search for a voter to display results.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

{{-- JavaScript for DataTables --}}
<script>
    $(document).ready(function() {
        $('#example').DataTable();
    });
</script>

@endsection
