<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h3, h4, h5, h6 { margin-bottom: 5px; }
        ul { padding-left: 15px; }
    </style>
</head>
<body>
    <h3>Coordinator:</h3>
    <p>{{ $coordinator->voter->first_name }} {{ $coordinator->voter->last_name }}</p>

    @if($purokLeaders->isNotEmpty())
        @foreach($purokLeaders as $purokLeader)
            <h4>Purok Leader: {{ $purokLeader->voter->first_name }} {{ $purokLeader->voter->last_name }}</h4>

            @php
                $leaders = $householdLeaders->where('purok_leader_id', $purokLeader->id);
            @endphp

            @foreach($leaders as $householdLeader)
                <h5>Household Leader: {{ $householdLeader->voter->first_name }} {{ $householdLeader->voter->last_name }}</h5>
                
                <h6>Members:</h6>
                <table>
                            
                            @foreach($householdLeader->householdMembers as $index => $householdMember)
                                <tr>
                                    
                                <td style=" width: 10px; "> {{ $index + 1 }}.</td>
                                <td style=" width: 300px; ">{{ $householdMember->voter->last_name }}, {{ $householdMember->voter->first_name }} {{ $householdMember->voter->middle_name }}</td>
                                <td style=" width: 80px; ">{{ $householdMember->voter->address }}</td>
                                <td style=" width: 120px; ">{{ $householdMember->voter->barangay }}</td>
                                <td style=" width: 80px; ">{{ $householdMember->voter->precinct }}</td>

                                </tr>
                                @endforeach
                                
                            </table>
            @endforeach
        @endforeach
    @else
        <p>No Purok Leaders found under this Coordinator.</p>
    @endif
</body>
</html>
