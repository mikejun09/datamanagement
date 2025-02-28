@extends('layouts.app')

@section('content')


<div class="mt-5">
   <label><h1>TAGGING OF COORDINATOR</h1> </label> 
</div>

<div class="container">
    <h2>List of Coordinators</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Middle Name</th>
                <th>Address</th>
                <th>Barangay</th>
                <th>Precinct</th>
                <th>Remarks</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($coordinator_lists as $index => $coordinator)
            <tr>
                <td>{{ $coordinator_lists->perPage() * ($coordinator_lists->currentPage() - 1) + $index + 1 }}</td>
                <td>{{ optional($coordinator->voter)->last_name }}</td>
                <td>{{ optional($coordinator->voter)->first_name }}</td>
                <td>{{ optional($coordinator->voter)->middle_name }}</td>
                <td>{{ optional($coordinator->voter)->address }}</td>
                <td>{{ optional($coordinator->voter)->barangay }}</td>
                <td>{{ optional($coordinator->voter)->precinct }}</td>
                <td>{{ $coordinator->remarks ?? 'No Remarks' }}</td>
                <td>
                    <!-- Delete Form -->
                    <form action="{{ route('deleteCoordinator', $coordinator->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this coordinator?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn1 btn-danger">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination Links -->
<div class="pagination-wrapper">
    {{ $coordinator_lists->links() }}
</div>
</div>


<div class="mt-5 col-md-12">
    <form method="GET" action="{{ route('voters.index') }}">

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
@if ($voters->isEmpty() && !request()->filled('barangay') && !request()->filled('first_name') && !request()->filled('last_name'))
    <div class="alert alert-info text-center">No data to display. Please use the search filters above.</div>
@else
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
                                &nbsp;  <!-- Empty space for not tagged voters -->
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('coordinator.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="voter_id" value="{{ $voter->id }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-person-plus-fill"></i> Add to Coordinator
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="pagination-wrapper">
            {{ $voters->links() }}
        </div>
    </div>
@endif



<!-- Coordinator Exists Modal -->
<div class="modal fade" id="coordinatorExistsModal" tabindex="-1" aria-labelledby="coordinatorExistsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="coordinatorExistsModalLabel">Duplicate Entry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                This voter is already a coordinator.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        @if (session('coordinator_exists'))
            var modal = new bootstrap.Modal(document.getElementById('coordinatorExistsModal'));
            modal.show();
        @endif
    });
</script>
<script>
    $(document).ready(function() {
        $('#example').DataTable(); // Replace '#example' with your table's ID or class
    });
</script>

@endsection