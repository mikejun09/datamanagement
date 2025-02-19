@extends('layouts.app')

@section('content')


    <div class="card mt-5">
        <div class="container">

            <div class="row mt-3 mb-5">
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
                <div>
                    <label for=""><h1>Tagged LEADERS</h1></label>
                </div>
                <table class="table" id="example1">
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
                        {{-- @foreach($leaders as $leader)
                        <tr>
                            <td style="display:none">{{ $leader->id }}</td>
                            <td>{{ $leader->leader->name }}</td>
                            <td>{{ $leader->leader->precinct }}</td>
                            <td>{{ $leader->leader->address }}</td>
                            <td>
                                <a href="{{ route('members.create', ['leader_id' => $leader->id]) }}" class="btn btn-primary">
                                    Add Members
                                </a>
                            </td>
                        </tr>
                    @endforeach --}}
                    </tbody>
                </table>
               </div>

               <div class="col">
                <hr class="hr" />
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
                    {{-- @foreach($voters as $voter)
                    <tr>
                        <td style="display:none">{{ $voter->id }}</td>
                        <td>{{ $voter->name }}</td>
                        <td>{{ $voter->precinct }}</td>
                        <td>{{ $voter->address }}</td>
                        <td><a href="{{ route('tag_leader', ['id' => $voter->id]) }}" class="btn">Tag as LEADER</a></button></td>
                        
                    </tr>
                @endforeach --}}
                </tbody>
            </table>
           </div>
        </div>
    </div>





    <script>
        $(document).ready(function() {
            $('#example').DataTable(); // Replace '#example' with your table's ID or class
        });

        $(document).ready(function() {
            $('#example1').DataTable(); // Replace '#example' with your table's ID or class
        });
    </script>

@endsection