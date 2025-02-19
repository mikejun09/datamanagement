<!DOCTYPE html>
<html>
<head>
    <title></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        h2, h3, h4, h5, h6 { margin-bottom: 5px; }
        ul { list-style-type: none; padding-left: 0; }
        li { margin-left: 20px; }
    </style>
</head>
<body>

    <h2 style="text-align: center;"></h2>
    <p style="text-align: center;">Generated on: {{ \Carbon\Carbon::now()->format('F d, Y') }}</p>

    <h3>
        Coordinator: 
        @if($coordinator && $coordinator->voter)
            {{ $coordinator->voter->first_name }} {{ $coordinator->voter->last_name }}
        @else
            No coordinator selected.
        @endif
    </h3>

    @if($coordinator->purokLeaders->isNotEmpty())
        @foreach($coordinator->purokLeaders as $purokLeader)
            <div style="margin-top: 20px;">
                <h4>Barangay Purok Leader: 
                    {{ $purokLeader->voter->first_name }} {{ $purokLeader->voter->last_name }}
                </h4>

                @php
                    $householdLeaders = session('householdLeaders')->where('purok_leader_id', $purokLeader->id);
                @endphp

                @foreach($householdLeaders as $householdLeader)
                    <div style="margin-left: 20px; margin-top: 10px;">
                        <h5>Household Leader: 
                            {{ $householdLeader->voter->first_name }} {{ $householdLeader->voter->last_name }}
                        </h5>

                        <h6>Members:</h6>
                        <ul>
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

</body>
</html>
