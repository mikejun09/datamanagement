@extends('layouts.app')

@section('content')

<div class="row mt-5">
    <div class="card">
        <div class="row mb-4">
            <div>
                <label for=""><h3>LEADER : {{ $leader->leader->name}}</h3></label>
            </div>    
        </div>

        <div class="row">
            <table class="table" id="example1">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Barangay</th>
                        <th>Precinct #</th>
                      
                        <th style="display:none"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($leaders as $leader)
                    <tr>
                        <td style="display:none">{{ $leader->id }}</td>
                        <td>{{ $leader->leader->name }}</td>
                        <td>{{ $leader->leader->precinct }}</td>
                        <td>{{ $leader->leader->address }}</td>
                       
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="row mt-5">
            
            <table class="table" id="example">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Barangay</th>
                        <th>Precinct #</th>
                        <th>Action</th>
                        <th style="display:none"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($voters as $voter)
                    <tr>
                        <td style="display:none">{{ $voter->id }}</td>
                        <td>{{ $voter->name }}</td>
                        <td>{{ $voter->precinct }}</td>
                        <td>{{ $voter->address }}</td>
                        <td><a href="{{ route('tag_leader', ['id' => $voter->id]) }}" class="btn">Tag as MEMBER</a></button></td>
                        
                    </tr>
                @endforeach
                </tbody>
            </table>
           </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#example').DataTable(); // Replace '#example' with your table's ID or class
    });

</script>

@endsection