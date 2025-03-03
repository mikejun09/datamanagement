@if(count($taggedMembers) > 0)
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Address</th>
                    <th>Barangay</th>
                    <th>Precinct</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($taggedMembers as $member)
                    @if($member->voter)
                        <tr id="row-{{ $member->voter->id }}">
                            <td>{{ $member->voter->last_name }}</td>
                            <td>{{ $member->voter->first_name }}</td>
                            <td>{{ $member->voter->middle_name }}</td>
                            <td>{{ $member->voter->address }}</td>
                            <td>{{ $member->voter->barangay }}</td>
                            <td>{{ $member->voter->precinct }}</td>
                            <td>
                                <form action="{{ route('voter.destroy', $member->voter->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn1 btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <p style="color: gray;">No members tagged under this Household Leader yet.</p>
@endif
