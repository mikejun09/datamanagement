<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Data Management System</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

{{-- fonts --}}
<link href="https://fonts.cdnfonts.com/css/broadsheet-ldo" rel="stylesheet">

  <!-- Google Fonts -->
  
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

 
  <link href="{{asset('theme/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('theme/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">  
  <link href="{{asset('theme/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
  <link href="{{asset('theme/vendor/quill/quill.snow.css')}}" rel="stylesheet">
  <link href="{{asset('theme/vendor/quill/quill.bubble.css')}}" rel="stylesheet">
  <link href="{{asset('theme/vendor/remixicon/remixicon.css')}}" rel="stylesheet">
  <link href="{{asset('theme/vendor/simple-datatables/style.css')}}" rel="stylesheet">


  <!-- Template Main CSS File -->
  <link href="{{asset('theme/css/style.css')}}" rel="stylesheet">
  

  {{-- datatable --}}
<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">

<!-- Include DataTables JS -->
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
 

  
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center mb-5" style="height: 100px !important; ">
  
    <div class="d-flex align-items-center justify-content-between">
      <h1>DATA MANAGEMENT</h1>
  
      <i class="bi bi-list toggle-sidebar-btn"></i>
  </div>
  <!-- End Logo -->

  
  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">
        <li class="nav-item dropdown pe-3">
            <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                <span class="d-none d-md-block dropdown-toggle ps-2">
                    {{ Auth::user()->name ?? 'Guest' }}
                </span>
            </a>

            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
              
               

                <li>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                      @csrf
                  </form>
                  
                  <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                      Logout
                  </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>
<!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar mt-5">

    <ul class="sidebar-nav" id="sidebar-nav">
  
        <li class="nav-item">
            <a class="nav-link {{ 'admin_index' == request()->path() ? 'active' : '' }}" href="{{route('admin_index')}} " href="{{route('admin_index')}}">
              <i class="bi bi-grid"></i>
              <span>Dashboard</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
              <i class="bi bi-layout-text-window-reverse"></i><span>Tagging</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
              <li>
                <a class="nav-link {{ request()->is('voters') ? 'active' : '' }}" href="{{ route('voters.index') }}">
                  <i class="bi bi-chevron-double-right"></i>Tagging Barangay Coordinator
              </a>
              
              </li>
              <li class="mt-1">
                <a class="nav-link {{ 'purok_leader' == request()->path() ? 'active' : '' }}" href="{{route('purok_leader')}} " href="{{route('purok_leader')}}">
                  <i class="bi bi-chevron-double-right"></i><span>Tagging Barangay Purok Leader</span>
                </a>
              </li>

              <li class="mt-1">
                <a class="nav-link {{ 'household_leader' == request()->path() ? 'active' : '' }}" href="{{route('household_leader')}} " href="{{route('household_leader')}}">
                  <i class="bi bi-chevron-double-right"></i><span>Tagging Household Leader</span>
                </a>
              </li>

              <li class="mt-1">
                <a class="nav-link {{ 'household_member' == request()->path() ? 'active' : '' }}" href="{{route('household_member')}} " href="{{route('household_member')}}">
                  <i class="bi bi-chevron-double-right"></i><span>Tagging Household Member</span>
                </a>
              </li>

            </ul>
          </li>

    

      <li class="nav-item">
        <a class="nav-link {{ 'reports' == request()->path() ? 'active' : '' }}" href="{{route('reports')}} " href="{{route('reports')}}">
          <i class="bi bi-layout-text-window-reverse"></i>
          <span>Reports</span>
        </a>
      </li>

      <li class="nav-item">
            <a class="nav-link {{ 'search' == request()->path() ? 'active' : '' }}" href="{{route('search')}} " href="{{route('search')}}">
              <i class="bi bi-grid"></i>
              <span>Search Voters</span>
            </a>
      </li>

      <li class="nav-item">
            <a class="nav-link {{ 'create_user' == request()->path() ? 'active' : '' }}" href="{{route('create_user')}} " href="{{route('create_user')}}">
              <i class="bi bi-grid"></i>
              <span>Create User</span>
            </a>
      </li>

    
    </ul>

  </aside>

  <main id="main" class="main mt-5">

     @yield('content')

  
  </main><!-- End #main -->


  <!-- ======= Footer ======= -->


  

  <!-- Vendor JS Files -->
  
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="{{asset('theme/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="{{asset('theme/vendor/quill/quill.min.js')}}"></script>
  <script src="{{asset('theme/vendor/simple-datatables/simple-datatables.js')}}"></script>
  <script src="{{asset('theme/vendor/tinymce/tinymce.min.js')}}"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  

  <script src="{{asset('theme/js/main.js')}}"></script>
  @yield('scripts')
</body>

</html>