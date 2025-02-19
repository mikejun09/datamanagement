@extends('layouts.app')

@section('content')
    <div class="mt-5">
        <h3>Select a Coordinator</h3>
        <form method="GET" action="{{ route('select-coordinator1') }}">
            <select class="form-select" name="coordinator_id" onchange="this.form.submit()">
                <option value="" selected>Select Coordinator</option>
                @foreach($coordinators as $coordinatorItem)
                    <option value="{{ $coordinatorItem->coordinator_id }}" 
                        {{ isset($coordinator) && $coordinator->coordinator_id == $coordinatorItem->coordinator_id ? 'selected' : '' }}>
                        {{ $coordinatorItem->voter->first_name }} {{ $coordinatorItem->voter->last_name }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    @if(session('selected_coordinator'))
    <div class="mt-5">
        <h3>
            Coordinator: 
            @if(session('selected_coordinator') && session('selected_coordinator')->voter)
                {{ session('selected_coordinator')->voter->first_name }} {{ session('selected_coordinator')->voter->last_name }}
            @else
                No coordinator selected.
            @endif
        </h3>

        @if(session('purokLeaders') && session('purokLeaders')->isNotEmpty())
            @foreach(session('purokLeaders') as $purokLeader)
                <div class="mt-4">
                    <h4>Barangay Purok Leader: 
                        {{ $purokLeader->voter->first_name }} {{ $purokLeader->voter->last_name }}
                    </h4>

                    {{-- Find household leaders under this purok leader --}}
                    @php
                        $householdLeaders = session('householdLeaders')->where('purok_leader_id', $purokLeader->id);
                    @endphp

                    @foreach($householdLeaders as $householdLeader)
                        <div class="mt-3">
                            <h5>Household Leader: 
                                {{ $householdLeader->voter->first_name }} {{ $householdLeader->voter->last_name }}
                            </h5>

                            <h6>Members:</h6>
                            <ul style="list-style-type: none; padding: 0;">
                    @foreach($householdLeader->householdMembers as $index => $householdMember)
                        <li>{{ $index + 1 }}. {{ $householdMember->voter->first_name }} {{ $householdMember->voter->last_name }}</li>
                    @endforeach
                </ul>


                        </div>
                    @endforeach
                </div>
            @endforeach
        @else
            <p>No Purok Leaders found under this Coordinator.</p>
        @endif
    </div>
@endif
@endsection
