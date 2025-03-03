@if($voters->isEmpty())
    <tr>
        <td colspan="8" class="text-center">No data available. Please perform a search.</td>
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
                @if($voter->coordinator) BC
                @elseif($voter->purokLeader) PL
                @elseif($voter->householdLeader) HL
                @elseif($voter->householdMember) HM
                @else &nbsp; @endif
            </td>
            <td>
                <button class="btn btn-primary">Tag as Leader</button>
            </td>
        </tr>
    @endforeach
@endif
