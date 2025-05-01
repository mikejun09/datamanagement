@extends('layouts.app')

@section('content')

<div class="col mt-5">

<div class="d-flex justify-content-center align-items-center">
    <div class="card text-center p-5 position-relative" style="min-width: 350px;">
        <!-- Refresh Button -->
        <button onclick="loadOverallTagged()" class="btn btn-sm btn-light position-absolute top-0 end-0 m-3" title="Refresh">
            <i class="fas fa-sync-alt" id="refreshIcon"></i>
            <div id="overallSpinner" class="spinner-border spinner-border-sm text-primary d-none" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </button>

        <!-- Content -->
        <h2>Overall Tagged Voters:</h2>
        <h1 id="overallTotal" style="font-size: 75px;"></h1>
    </div>
</div>



<script>
    function loadOverallTagged() {
        $('#overallSpinner').removeClass('d-none');
        $('#refreshIcon').addClass('d-none');

        $.ajax({
            url: '{{ route("overall.tagged.count") }}',
            method: 'GET',
            success: function(response) {
                $('#overallTotal').text(response.count);
            },
            error: function() {
                alert('Failed to load overall count.');
            },
            complete: function() {
                $('#overallSpinner').addClass('d-none');
                $('#refreshIcon').removeClass('d-none');
            }
        });
    }
</script>



    <div class="row">

  
    <div class="col-xxl-4 col-md-6">
        <div class="card info-card sales-card barangay-card" style="position: relative;">
            <div class="card-body">
                <h5 class="card-title"> BUENAVISTA</h5>
                <div class="d-flex align-items-center">
                    <div class="ps-3">
                        <h1 id="buenavista-count"></h1>
                        <div id="buenavista-spinner" class="spinner-border text-primary mt-2 d-none" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Button in the top-right corner -->
            <button class="btn btn-primary mt-2" onclick="loadTaggedCount()" style="position: absolute; top: 10px; right: 10px;">
                <i class="fas fa-sync-alt"></i> <!-- Font Awesome refresh icon -->
            </button>
        </div>
    </div>

    <script>
        function loadTaggedCount() {
        $('#buenavista-spinner').removeClass('d-none');
        $.ajax({
            url: '{{ route("barangay.tagged.count") }}',
            method: 'GET',
            data: { barangay: 'BUENAVISTA' },
            success: function(response) {
                $('#buenavista-count').text(`${response.tagged}/${response.untagged}`);
            },
            error: function() {
                alert('Failed to load count.');
            },
            complete: function() {
                $('#buenavista-spinner').addClass('d-none');
            }
        });
    }
    </script>


<div class="col-xxl-4 col-md-6">
    <div class="card info-card sales-card barangay-card" style="position: relative;">
        <div class="card-body">
            <h5 class="card-title">BALINTAD</h5>
            <div class="d-flex align-items-center">
                <div class="ps-3">
                    <h1 id="balintad-count"></h1>
                    <div id="balintad-spinner" class="spinner-border text-primary mt-2 d-none" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Button in the top-right corner -->
        <button class="btn btn-primary mt-2" onclick="loadTaggedCountbalintad()" style="position: absolute; top: 10px; right: 10px;">
            <i class="fas fa-sync-alt"></i> <!-- Font Awesome refresh icon -->
        </button>
    </div>
</div>


    <script>
        function loadTaggedCountbalintad() {
        $('#balintad-spinner').removeClass('d-none');
        $.ajax({
            url: '{{ route("barangay.tagged.count") }}',
            method: 'GET',
            data: { barangay: 'BALINTAD' },
            success: function(response) {
                $('#balintad-count').text(`${response.tagged}/${response.untagged}`);
            },
            error: function() {
                alert('Failed to load count.');
            },
            complete: function() {
                $('#balintad-spinner').addClass('d-none');
            }
        });
    }
    </script>


<div class="col-xxl-4 col-md-6">
    <div class="card info-card sales-card barangay-card" style="position: relative;">
        <div class="card-body">
            <h5 class="card-title">DANATAG</h5>
            <div class="d-flex align-items-center">
                <div class="ps-3">
                    <h1 id="danatag-count"></h1>
                    <div id="danatag-spinner" class="spinner-border text-primary mt-2 d-none" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Button in the top-right corner -->
        <button class="btn btn-primary mt-2" onclick="loadTaggedCountdanatag()" style="position: absolute; top: 10px; right: 10px;">
            <i class="fas fa-sync-alt"></i> <!-- Font Awesome refresh icon -->
        </button>
    </div>
</div>


    <script>
        function loadTaggedCountdanatag() {
        $('#danatag-spinner').removeClass('d-none');
        $.ajax({
            url: '{{ route("barangay.tagged.count") }}',
            method: 'GET',
            data: { barangay: 'DANATAG' },
            success: function(response) {
                $('#danatag-count').text(`${response.tagged}/${response.untagged}`);
            },
            error: function() {
                alert('Failed to load count.');
            },
            complete: function() {
                $('#danatag-spinner').addClass('d-none');
            }
        });
    }
    </script>



    

  
    <div class="col-xxl-4 col-md-6">
    <div class="card info-card sales-card barangay-card" style="position: relative;">
        <div class="card-body">
            <h5 class="card-title">IMBATUG</h5>
            <div class="d-flex align-items-center">
                <div class="ps-3">
                    <h1 id="imbatug-count"></h1>
                    <div id="imbatug-spinner" class="spinner-border text-primary mt-2 d-none" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Button in the top-right corner -->
        <button class="btn btn-primary mt-2" onclick="loadTaggedCountimbatug()" style="position: absolute; top: 10px; right: 10px;">
            <i class="fas fa-sync-alt"></i> <!-- Font Awesome refresh icon -->
        </button>
    </div>
</div>


    <script>
        function loadTaggedCountimbatug() {
        $('#imbatug-spinner').removeClass('d-none');
        $.ajax({
            url: '{{ route("barangay.tagged.count") }}',
            method: 'GET',
            data: { barangay: 'IMBATUG' },
            success: function(response) {
                $('#imbatug-count').text(`${response.tagged}/${response.untagged}`);
            },
            error: function() {
                alert('Failed to load count.');
            },
            complete: function() {
                $('#imbatug-spinner').addClass('d-none');
            }
        });
    }
    </script>

<div class="col-xxl-4 col-md-6">
    <div class="card info-card sales-card barangay-card" style="position: relative;">
        <div class="card-body">
            <h5 class="card-title">KALILANGAN</h5>
            <div class="d-flex align-items-center">
                <div class="ps-3">
                    <h1 id="kalilangan-count"></h1>
                    <div id="kalilangan-spinner" class="spinner-border text-primary mt-2 d-none" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Button in the top-right corner -->
        <button class="btn btn-primary mt-2" onclick="loadTaggedCountkalilangan()" style="position: absolute; top: 10px; right: 10px;">
            <i class="fas fa-sync-alt"></i> <!-- Font Awesome refresh icon -->
        </button>
    </div>
</div>


    <script>
        function loadTaggedCountkalilangan() {
        $('#kalilangan-spinner').removeClass('d-none');
        $.ajax({
            url: '{{ route("barangay.tagged.count") }}',
            method: 'GET',
            data: { barangay: 'KALILANGAN' },
            success: function(response) {
                $('#kalilangan-count').text(`${response.tagged}/${response.untagged}`);
            },
            error: function() {
                alert('Failed to load count.');
            },
            complete: function() {
                $('#kalilangan-spinner').addClass('d-none');
            }
        });
    }
    </script>

<div class="col-xxl-4 col-md-6">
    <div class="card info-card sales-card barangay-card" style="position: relative;">
        <div class="card-body">
            <h5 class="card-title">LACOLAC</h5>
            <div class="d-flex align-items-center">
                <div class="ps-3">
                    <h1 id="lacolac-count"></h1>
                    <div id="lacolac-spinner" class="spinner-border text-primary mt-2 d-none" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Button in the top-right corner -->
        <button class="btn btn-primary mt-2" onclick="loadTaggedCountlacolac()" style="position: absolute; top: 10px; right: 10px;">
            <i class="fas fa-sync-alt"></i> <!-- Font Awesome refresh icon -->
        </button>
    </div>
</div>


    <script>
        function loadTaggedCountlacolac() {
        $('#lacolac-spinner').removeClass('d-none');
        $.ajax({
            url: '{{ route("barangay.tagged.count") }}',
            method: 'GET',
            data: { barangay: 'LACOLAC' },
            success: function(response) {
                $('#lacolac-count').text(`${response.tagged}/${response.untagged}`);
            },
            error: function() {
                alert('Failed to load count.');
            },
            complete: function() {
                $('#lacolac-spinner').addClass('d-none');
            }
        });
    }
    </script>

    

    

  
    <div class="col-xxl-4 col-md-6">
    <div class="card info-card sales-card barangay-card" style="position: relative;">
        <div class="card-body">
            <h5 class="card-title">LANGAON</h5>
            <div class="d-flex align-items-center">
                <div class="ps-3">
                    <h1 id="langaon-count"></h1>
                    <div id="langaon-spinner" class="spinner-border text-primary mt-2 d-none" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Button in the top-right corner -->
        <button class="btn btn-primary mt-2" onclick="loadTaggedCountlangaon()" style="position: absolute; top: 10px; right: 10px;">
            <i class="fas fa-sync-alt"></i> <!-- Font Awesome refresh icon -->
        </button>
    </div>
</div>


    <script>
        function loadTaggedCountlangaon() {
        $('#langaon-spinner').removeClass('d-none');
        $.ajax({
            url: '{{ route("barangay.tagged.count") }}',
            method: 'GET',
            data: { barangay: 'LANGAON' },
            success: function(response) {
                $('#langaon-count').text(`${response.tagged}/${response.untagged}`);
            },
            error: function() {
                alert('Failed to load count.');
            },
            complete: function() {
                $('#langaon-spinner').addClass('d-none');
            }
        });
    }
    </script>

<div class="col-xxl-4 col-md-6">
    <div class="card info-card sales-card barangay-card" style="position: relative;">
        <div class="card-body">
            <h5 class="card-title">LIBORAN</h5>
            <div class="d-flex align-items-center">
                <div class="ps-3">
                    <h1 id="liboran-count"></h1>
                    <div id="liboran-spinner" class="spinner-border text-primary mt-2 d-none" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Button in the top-right corner -->
        <button class="btn btn-primary mt-2" onclick="loadTaggedCountliboran()" style="position: absolute; top: 10px; right: 10px;">
            <i class="fas fa-sync-alt"></i> <!-- Font Awesome refresh icon -->
        </button>
    </div>
</div>


    <script>
        function loadTaggedCountliboran() {
        $('#liboran-spinner').removeClass('d-none');
        $.ajax({
            url: '{{ route("barangay.tagged.count") }}',
            method: 'GET',
            data: { barangay: 'LIBORAN' },
            success: function(response) {
                $('#liboran-count').text(`${response.tagged}/${response.untagged}`);
            },
            error: function() {
                alert('Failed to load count.');
            },
            complete: function() {
                $('#liboran-spinner').addClass('d-none');
            }
        });
    }
    </script>

<div class="col-xxl-4 col-md-6">
    <div class="card info-card sales-card barangay-card" style="position: relative;">
        <div class="card-body">
            <h5 class="card-title">LINGATING</h5>
            <div class="d-flex align-items-center">
                <div class="ps-3">
                    <h1 id="lingating-count"></h1>
                    <div id="lingating-spinner" class="spinner-border text-primary mt-2 d-none" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Button in the top-right corner -->
        <button class="btn btn-primary mt-2" onclick="loadTaggedCountlingating()" style="position: absolute; top: 10px; right: 10px;">
            <i class="fas fa-sync-alt"></i> <!-- Font Awesome refresh icon -->
        </button>
    </div>
</div>


    <script>
        function loadTaggedCountlingating() {
        $('#lingating-spinner').removeClass('d-none');
        $.ajax({
            url: '{{ route("barangay.tagged.count") }}',
            method: 'GET',
            data: { barangay: 'LINGATING' },
            success: function(response) {
                $('#lingating-count').text(`${response.tagged}/${response.untagged}`);
            },
            error: function() {
                alert('Failed to load count.');
            },
            complete: function() {
                $('#lingating-spinner').addClass('d-none');
            }
        });
    }
    </script>

    

  
    <div class="col-xxl-4 col-md-6">
    <div class="card info-card sales-card barangay-card" style="position: relative;">
        <div class="card-body">
            <h5 class="card-title">MABUHAY</h5>
            <div class="d-flex align-items-center">
                <div class="ps-3">
                    <h1 id="mabuhay-count"></h1>
                    <div id="mabuhay-spinner" class="spinner-border text-primary mt-2 d-none" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Button in the top-right corner -->
        <button class="btn btn-primary mt-2" onclick="loadTaggedCountmabuhay()" style="position: absolute; top: 10px; right: 10px;">
            <i class="fas fa-sync-alt"></i> <!-- Font Awesome refresh icon -->
        </button>
    </div>
</div>


    <script>
        function loadTaggedCountmabuhay() {
        $('#mabuhay-spinner').removeClass('d-none');
        $.ajax({
            url: '{{ route("barangay.tagged.count") }}',
            method: 'GET',
            data: { barangay: 'MABUHAY' },
            success: function(response) {
                $('#mabuhay-count').text(`${response.tagged}/${response.untagged}`);
            },
            error: function() {
                alert('Failed to load count.');
            },
            complete: function() {
                $('#mabuhay-spinner').addClass('d-none');
            }
        });
    }
    </script>

<div class="col-xxl-4 col-md-6">
    <div class="card info-card sales-card barangay-card" style="position: relative;">
        <div class="card-body">
            <h5 class="card-title">MABUNGA</h5>
            <div class="d-flex align-items-center">
                <div class="ps-3">
                    <h1 id="mabunga-count"></h1>
                    <div id="mabunga-spinner" class="spinner-border text-primary mt-2 d-none" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Button in the top-right corner -->
        <button class="btn btn-primary mt-2" onclick="loadTaggedCountmabunga()" style="position: absolute; top: 10px; right: 10px;">
            <i class="fas fa-sync-alt"></i> <!-- Font Awesome refresh icon -->
        </button>
    </div>
</div>


    <script>
        function loadTaggedCountmabunga() {
        $('#mabunga-spinner').removeClass('d-none');
        $.ajax({
            url: '{{ route("barangay.tagged.count") }}',
            method: 'GET',
            data: { barangay: 'MABUNGA' },
            success: function(response) {
                $('#mabunga-count').text(`${response.tagged}/${response.untagged}`);
            },
            error: function() {
                alert('Failed to load count.');
            },
            complete: function() {
                $('#mabunga-spinner').addClass('d-none');
            }
        });
    }
    </script>

<div class="col-xxl-4 col-md-6">
    <div class="card info-card sales-card barangay-card" style="position: relative;">
        <div class="card-body">
            <h5 class="card-title">NICDAO</h5>
            <div class="d-flex align-items-center">
                <div class="ps-3">
                    <h1 id="nicdao-count"></h1>
                    <div id="nicdao-spinner" class="spinner-border text-primary mt-2 d-none" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Button in the top-right corner -->
        <button class="btn btn-primary mt-2" onclick="loadTaggedCountnicdao()" style="position: absolute; top: 10px; right: 10px;">
            <i class="fas fa-sync-alt"></i> <!-- Font Awesome refresh icon -->
        </button>
    </div>
</div>


    <script>
        function loadTaggedCountnicdao() {
        $('#nicdao-spinner').removeClass('d-none');
        $.ajax({
            url: '{{ route("barangay.tagged.count") }}',
            method: 'GET',
            data: { barangay: 'NICDAO' },
            success: function(response) {
                $('#nicdao-count').text(`${response.tagged}/${response.untagged}`);
            },
            error: function() {
                alert('Failed to load count.');
            },
            complete: function() {
                $('#nicdao-spinner').addClass('d-none');
            }
        });
    }
    </script>





  
<div class="col-xxl-4 col-md-6">
    <div class="card info-card sales-card barangay-card" style="position: relative;">
        <div class="card-body">
            <h5 class="card-title">PUALAS</h5>
            <div class="d-flex align-items-center">
                <div class="ps-3">
                    <h1 id="pualas-count"></h1>
                    <div id="pualas-spinner" class="spinner-border text-primary mt-2 d-none" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Button in the top-right corner -->
        <button class="btn btn-primary mt-2" onclick="loadTaggedCountpualas()" style="position: absolute; top: 10px; right: 10px;">
            <i class="fas fa-sync-alt"></i> <!-- Font Awesome refresh icon -->
        </button>
    </div>
</div>


    <script>
        function loadTaggedCountpualas() {
        $('#pualas-spinner').removeClass('d-none');
        $.ajax({
            url: '{{ route("barangay.tagged.count") }}',
            method: 'GET',
            data: { barangay: 'PUALAS' },
            success: function(response) {
                $('#pualas-count').text(`${response.tagged}/${response.untagged}`);
            },
            error: function() {
                alert('Failed to load count.');
            },
            complete: function() {
                $('#pualas-spinner').addClass('d-none');
            }
        });
    }
    </script>

<div class="col-xxl-4 col-md-6">
    <div class="card info-card sales-card barangay-card" style="position: relative;">
        <div class="card-body">
            <h5 class="card-title">SALIMBALAN</h5>
            <div class="d-flex align-items-center">
                <div class="ps-3">
                    <h1 id="salimbalan-count"></h1>
                    <div id="salimbalan-spinner" class="spinner-border text-primary mt-2 d-none" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Button in the top-right corner -->
        <button class="btn btn-primary mt-2" onclick="loadTaggedCountsalimbalan()" style="position: absolute; top: 10px; right: 10px;">
            <i class="fas fa-sync-alt"></i> <!-- Font Awesome refresh icon -->
        </button>
    </div>
</div>


    <script>
        function loadTaggedCountsalimbalan() {
        $('#salimbalan-spinner').removeClass('d-none');
        $.ajax({
            url: '{{ route("barangay.tagged.count") }}',
            method: 'GET',
            data: { barangay: 'SALIMBALAN' },
            success: function(response) {
                $('#salimbalan-count').text(`${response.tagged}/${response.untagged}`);
            },
            error: function() {
                alert('Failed to load count.');
            },
            complete: function() {
                $('#salimbalan-spinner').addClass('d-none');
            }
        });
    }
    </script>

<div class="col-xxl-4 col-md-6">
    <div class="card info-card sales-card barangay-card" style="position: relative;">
        <div class="card-body">
            <h5 class="card-title">SAN MIGUEL</h5>
            <div class="d-flex align-items-center">
                <div class="ps-3">
                    <h1 id="sanmiguel-count"></h1>
                    <div id="sanmiguel-spinner" class="spinner-border text-primary mt-2 d-none" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Button in the top-right corner -->
        <button class="btn btn-primary mt-2" onclick="loadTaggedCountsanmiguel()" style="position: absolute; top: 10px; right: 10px;">
            <i class="fas fa-sync-alt"></i> <!-- Font Awesome refresh icon -->
        </button>
    </div>
</div>


    <script>
        function loadTaggedCountsanmiguel() {
        $('#sanmiguel-spinner').removeClass('d-none');
        $.ajax({
            url: '{{ route("barangay.tagged.count") }}',
            method: 'GET',
            data: { barangay: 'SAN MIGUEL' },
            success: function(response) {
                $('#sanmiguel-count').text(`${response.tagged}/${response.untagged}`);
            },
            error: function() {
                alert('Failed to load count.');
            },
            complete: function() {
                $('#sanmiguel-spinner').addClass('d-none');
            }
        });
    }
    </script>

    

    

  
    <div class="col-xxl-4 col-md-6">
    <div class="card info-card sales-card barangay-card" style="position: relative;">
        <div class="card-body">
            <h5 class="card-title">SAN VICENTE</h5>
            <div class="d-flex align-items-center">
                <div class="ps-3">
                    <h1 id="sanvicente-count"></h1>
                    <div id="sanvicente-spinner" class="spinner-border text-primary mt-2 d-none" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Button in the top-right corner -->
        <button class="btn btn-primary mt-2" onclick="loadTaggedCountsanvicente()" style="position: absolute; top: 10px; right: 10px;">
            <i class="fas fa-sync-alt"></i> <!-- Font Awesome refresh icon -->
        </button>
    </div>
</div>


    <script>
        function loadTaggedCountsanvicente() {
        $('#sanvicente-spinner').removeClass('d-none');
        $.ajax({
            url: '{{ route("barangay.tagged.count") }}',
            method: 'GET',
            data: { barangay: 'SAN VICENTE' },
            success: function(response) {
                $('#sanvicente-count').text(`${response.tagged}/${response.untagged}`);
            },
            error: function() {
                alert('Failed to load count.');
            },
            complete: function() {
                $('#sanvicente-spinner').addClass('d-none');
            }
        });
    }
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