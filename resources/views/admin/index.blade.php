@extends('layouts.app')

@section('content')

<div class="col mt-5">

    <div class="row">
        <div class="card">
            Overall Tagged Voters: <span id="overallTotal">{{ $overallTotal }}</span>
        </div>
    </div>

    <div class="row">

        @foreach ($barangays as $barangay)
        <div class="col-xxl-4 col-md-6">
            <div class="card info-card sales-card barangay-card" 
                data-id="{{ $barangay->barangay }}" 
                style="cursor: pointer;">
                <div class="card-body">
                    <h5 class="card-title">{{ $barangay->barangay }}</h5>
                    <div class="d-flex align-items-center">
                        <div class="ps-3">
                            <h1>{{ $barangay->tagged_voters_count }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="barangayModal" tabindex="-1" aria-labelledby="barangayModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="barangayModalLabel">Tagged Voters</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <div class="modal-body">
                    <table id="voters-table" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Status</th>  <!-- Added Status column -->
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Voters will be dynamically loaded here -->
                        </tbody>
                    </table>
                </div>

        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const cards = document.querySelectorAll('.barangay-card');
        let dataTable = null;  // Store DataTable instance

        cards.forEach(card => {
            card.addEventListener('click', () => {
                const barangay = card.getAttribute('data-id');

                // Show the modal
                const modal = new bootstrap.Modal(document.getElementById('barangayModal'));
                modal.show();

                // Destroy existing DataTable instance if it exists
                if (dataTable) {
                    dataTable.destroy();
                }

                // Initialize DataTable with AJAX
                dataTable = new DataTable('#voters-table', {
                    ajax: `/get-voters/${barangay}`,
                    processing: true,
                    serverSide: false,
                    columns: [
                        { data: 'last_name' },
                        { data: 'first_name' },
                        { data: 'middle_name' },
                        { data: 'status' }  // Added Status column
                    ],
                    language: {
                        loadingRecords: "Loading voters...",
                        emptyTable: "No tagged voters found."
                    }
                });
            });
        });
    });
</script>

<script>
    // Auto-refresh every 5 seconds (5000 milliseconds)
    function fetchOverallTotal() {
        $.ajax({
            url: "{{ url('/get-overall-total') }}",  // Route to fetch the total
            method: "GET",
            success: function(response) {
                $('#overallTotal').text(response.overallTotal);  // Update the total
            },
            error: function() {
                console.log("Failed to fetch the overall total.");
            }
        });
    }

    // Fetch the total every 5 seconds
    setInterval(fetchOverallTotal, 5000);

    // Fetch immediately on page load
    $(document).ready(fetchOverallTotal);
</script>










   

@endsection