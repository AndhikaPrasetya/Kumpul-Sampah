<!DOCTYPE html>
<html lang="en">
<head>
  <?php
  $settings = \App\Models\WebsiteSetting::first();  
  ?>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $settings->website_name ?? 'Kumpul Sampah' }}</title>
  <meta name="description" content="{{ $settings->website_description ?? 'Default Website Description' }}">
  <link rel="icon" href="{{ $settings->favicon ? asset('storage/' . $settings->favicon) : asset('template/assets/3135715.png') }}"  type="image/x-icon">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href={{asset('/template/plugins/fontawesome-free/css/all.min.css')}}>
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
  <link rel="stylesheet" href={{asset('/template/plugins/select2/css/select2.min.css')}}>
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css"
        crossorigin="anonymous">
        <link rel="stylesheet" href={{asset('/template/plugins/daterangepicker/daterangepicker.css')}}>
        <link rel="stylesheet" href="{{asset('template/plugins/toastr/toastr.min.css')}}">
  <link rel="stylesheet" href="{{asset('/template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('/template/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('/template/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Theme style -->
  <link rel="stylesheet" href={{asset('/template/dist/css/adminlte.min.css')}}>
</head>
<body class="hold-transition sidebar-mini layout-fixed" style="background-color: #f4f6f9;">
<div class="wrapper">


  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
          <img src="{{ Auth::user()->photo ? asset(Auth::user()->photo) : asset('template/assets/3135715.png') }}" 
          class="img-circle elevation-2 user-image" 
          alt="User Image">
          <span class="d-none d-md-inline">{{Auth::user()->name}}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <!-- User image -->
          <li class="user-header bg-primary">
            <img src="{{ Auth::user()->image ? asset(Auth::user()->image) : asset('template/assets/3135715.png') }}" 
            class="img-circle elevation-2 user-image" 
            alt="User Image">

            <p>
              {{Auth::user()->name}}
            </p>
          </li>
          <!-- Menu Footer-->
          <li class="user-footer border-black d-flex justify-content-between w-100">
           
              <a href="{{route('users.edit', Auth::user()->id)}}" class="btn btn-default btn-flat w-50">Profile</a>
            
            <form method="POST" action="{{ route('logout') }}" id="logout-form" class="w-100 d-flex justify-content-end">
              @csrf
              <a href="#" class="btn btn-danger w-50" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  Logout
              </a>
          </form>
          </li>
        </ul>
      </li>
      
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link">
      <img src="/template/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
         <img src="{{ Auth::user()->photo ? asset(Auth::user()->photo) : asset('template/assets/3135715.png') }}">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name }}</a>
        </div>
      </div>
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

          <li class="nav-item">
            <a href="/dashboard" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          
          @if (auth()->user()->hasRole('super admin'))
          <li class="nav-item">
            <a href="/website-settings" class="nav-link {{ Route::is('website-settings.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Website Settings
              </p>
            </a>
          </li>
          <li class="nav-item {{ Route::is('users.*', 'roles.*', 'permission.*',) ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Route::is('users.*', 'roles.*', 'permission.*',) ? 'active' : '' }}">
                  <i class="fas fa-users mr-2"></i>
                  <p>
                      Users Management
                      <i class="right fas fa-angle-left"></i>
                  </p>
              </a>
              <ul class="nav nav-treeview">
                  <li class="nav-item">
                      <a href="{{ route('users.index') }}" class="nav-link {{ Route::is('users.*') ? 'active' : '' }}">
                          <i class="nav-icon fas fa-user"></i>
                          <p>Users</p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="{{ route('roles.index') }}" class="nav-link {{ Route::is('roles.*') ? 'active' : '' }}">
                          <i class="nav-icon fas fa-shield-alt"></i>
                          <p>Roles</p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="{{ route('permission.index') }}" class="nav-link {{ Route::is('permission.*') ? 'active' : '' }}">
                          <i class="nav-icon fas fa-cogs"></i>
                          <p>Permission</p>
                      </a>
                  </li>
              </ul>
          </li>
          @endif
          <li class="nav-item">
            <a href="{{ route('nasabah.index') }}" class="nav-link {{ Route::is('nasabah.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user"></i>
                <p>Data nasabah</p>
            </a>
          </li>

          <li class="nav-item {{ Route::is('sampah.*','kategori-sampah.*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Route::is('sampah.*','kategori-sampah.*') ? 'active' : '' }}">
                  <i class="fas fa-dumpster mr-2"></i>
                  <p>
                      Pengelolaan sampah
                      <i class="right fas fa-angle-left"></i>
                  </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('sampah.index')}}" class="nav-link {{ Route::is('sampah.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-recycle"></i>
                    <p>
                    Data sampah
                    </p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('kategori-sampah.index')}}" class="nav-link {{ Route::is('kategori-sampah.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-trash"></i>
                    <p>
                      Kategori Sampah
                    </p>
                  </a>
                </li>
              </ul>
          </li>

          <li class="nav-item {{ Route::is('transaction.*','history-transacation.*','saldo.*','withdraw.*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Route::is('transaction.*','history-transacation.*','saldo.*','withdraw.*') ? 'active' : '' }}">
                  <i class="fas fa-receipt mr-2"></i>
                  <p>
                       Transaksi & Saldo
                      <i class="right fas fa-angle-left"></i>
                  </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('transaction.index')}}" class="nav-link {{ Route::is('transaction.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-exchange-alt"></i>
                    <p>
                    Data transaksi
                    </p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('history-transaction.index')}}" class="nav-link {{ Route::is('history-transaction.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-history"></i>
                    <p>
                    Riwayat transaksi
                    </p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('saldo.index')}}" class="nav-link {{ Route::is('saldo.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-wallet"></i>
                    <p>
                    Saldo & Points
                    </p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('withdraw.index')}}" class="nav-link {{ Route::is('withdraw.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-history"></i>
                    <p>
                    Penarikan dana 
                    </p>
                  </a>
                </li>
              </ul>
          </li>
          <li class="nav-item {{ Route::is('rewards.*','penukaran_points.*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Route::is('rewards.*','penukaran_points.*') ? 'active' : '' }}">
                  <i class="fas fa-gifts mr-2"></i>
                  <p>
                      Manajemen Rewards
                      <i class="right fas fa-angle-left"></i>
                  </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('rewards.index')}}" class="nav-link {{ Route::is('rewards.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-gift"></i>
                    <p>
                    Data rewards 
                    </p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('penukaran-points.index')}}" class="nav-link {{ Route::is('penukaran-points.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-ticket-alt"></i>
                    <p>
                    Penukaran Poin 
                    </p>
                  </a>
                </li>
              </ul>
              
          </li>
          @if (auth()->user()->hasRole('super admin'))
          <li class="nav-item">
            <a href="{{route('article.index')}}" class="nav-link {{ Route::is('article.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-book"></i>
              <p>
              Article 
              </p>
            </a>
          </li>
          @endif
      </ul>
      
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">   
    <!-- Content Header (Page header) -->
    
    <!-- Main content -->
    @yield('content')
    
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2025 <a href="#">Kumpul Sampah</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->

<script src={{asset('/template/plugins/jquery/jquery.min.js')}}></script>
<!-- jQuery UI 1.11.4 -->
<script src={{asset('/template/plugins/jquery-ui/jquery-ui.min.js')}}></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<script>
  $(function () {
  bsCustomFileInput.init();
});
</script>
<script src="{{asset('/template/plugins/bs-custom-file-input/bs-custom-file-input.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Bootstrap 4 -->
<script src={{asset('/template/plugins/bootstrap/js/bootstrap.bundle.min.js')}}></script>
<!-- ChartJS -->
<script src={{asset('/template/plugins/chart.js/Chart.min.js')}}></script>
<!-- daterangepicker -->
<script src={{asset('/template/plugins/moment/moment.min.js')}}></script>
<script src={{asset('/template/plugins/daterangepicker/daterangepicker.js')}}></script>


<script src={{asset('/template/dist/js/index.js')}}></script>
<script src={{ asset('/template/plugins/summernote/summernote-bs4.min.js') }}></script>
<script src={{asset('/template/plugins/datatables/jquery.dataTables.min.js')}}></script>
<script src={{asset('/template/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}></script>
<script src={{asset('/template/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}></script>
<script src={{asset('/template/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}></script>
<script src={{asset('/template/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}></script>
<script src={{asset('/template/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}></script>
<script src={{asset('/template/plugins/jszip/jszip.min.js')}}></script>
<script src={{asset('/template/plugins/pdfmake/pdfmake.min.js')}}></script>
<script src={{asset('/template/plugins/pdfmake/vfs_fonts.js')}}></script>
<script src={{asset('/template/plugins/datatables-buttons/js/buttons.html5.min.js')}}></script>
<script src={{asset('/template/plugins/datatables-buttons/js/buttons.print.min.js')}}></script>
<script src={{asset('/template/plugins/datatables-buttons/js/buttons.colVis.min.js')}}></script>
<script src="{{asset('template/plugins/toastr/toastr.min.js')}}"></script>
<script src="{{asset('/template/dist/js/adminlte.js')}}"></script>
<script src="{{asset('/template/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js" integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

@yield('script')
</body>
</html>
