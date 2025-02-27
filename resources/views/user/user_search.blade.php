@extends('layouts.app_user')

@section('content')

<div class="mt-5 col-md-12">
    <form method="GET" action="{{ route('search_voter') }}">

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
                        Barangay Coordinator
                    @elseif($voter->purokLeader)
                    Purok Leader under Barangay Coordinator: {{ $voter->purokLeader->coordinator->voter->first_name }} {{ $voter->purokLeader->coordinator->voter->last_name }}
                    @elseif($voter->householdLeader && $voter->householdLeader->purokLeader)
                Household Leader under Purok Leader: 
                {{ $voter->householdLeader->purokLeader->voter->first_name }} 
                {{ $voter->householdLeader->purokLeader->voter->last_name }}
                    @elseif($voter->householdMember)  
                    Household Member under Household Leader: 
                {{ $voter->householdMember->householdLeader->voter->first_name }} 
                {{ $voter->householdMember->householdLeader->voter->last_name }}
                    @else
                        &nbsp;  <!-- Empty space for not tagged voters -->
                    @endif
                        </td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            $('#example').DataTable(); // Replace '#example' with your table's ID or class
        });
    </script>




@endsection