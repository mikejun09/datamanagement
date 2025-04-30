@extends('layouts.app')

@section('content')

<div class="col mt-5">

<div class="d-flex justify-content-center align-items-center" >
    <div class="card text-center p-5">
        <h2>Overall Tagged Voters:</h2>
        <h1 id="overallTotal" style="font-size: 75px;"></h1>
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
                                <h1 id="count-{{ $barangay->barangay }}">
                                    Loading...
                                </h1>
                                </div>
                            </div>
                        </div>  
                    </div>
                </div>
            @endforeach


            <script>
            document.addEventListener("DOMContentLoaded", function () {
                fetch("/admin/all-barangay-counts")
                    .then(res => res.json())
                    .then(data => {
                        if (data.barangays) {
                            Object.keys(data.barangays).forEach(barangay => {
                                const countElem = document.getElementById(`count-${barangay}`);
                                const info = data.barangays[barangay];
                                if (countElem) {
                                    countElem.textContent = `${info.tagged} / ${info.untagged}`;
                                }
                            });
                        }

                        // Set overall total
                        if (data.overall_tagged !== undefined) {
                            const totalElem = document.getElementById('overallTotal');
                            if (totalElem) {
                                totalElem.textContent = data.overall_tagged;
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Failed to load barangay data:', error);
                    });
            });
            </script>





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














   

@endsection