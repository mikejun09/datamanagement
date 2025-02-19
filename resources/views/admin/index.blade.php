@extends('layouts.app')

@section('content')

    <!-- Left side columns -->
    <div class="col mt-5">
        <div class="row">

            @foreach ($barangays as $barangay)
            <div class="col-xxl-4 col-md-6">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $barangay->barangay }}</h5>
                        <div class="d-flex align-items-center">
                            <div class="ps-3">
                                <h1>{{ $barangay->tagged_voters_count }}</h1>  <!-- Displaying the tagged voters count -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        
        </div>
      </div><!-- End Left side columns -->







   

@endsection