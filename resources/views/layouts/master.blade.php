<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Lara Vue</title>

  <link rel="icon" href="/img/logo.png">
  <link rel="stylesheet" href="/css/app.css">
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper" id="app">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
        </li>
      </ul>

      <!-- SEARCH FORM -->
      <form class="form-inline ml-3">
        <div class="input-group input-group-sm">

          <input class="form-control form-control-navbar" type="search" v-model="search" @keyup.passive="searchFor" placeholder="Search" aria-label="Search">
          {{-- @keyup.enter.prevent="searchFor" --}}

          <div class="input-group-append">
            <button class="btn btn-navbar" @click.prevent="searchFor">
              <i class="fa fa-search"></i>
            </button>
          </div>

        </div>
      </form>

    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="{{ url('/') }}" class="brand-link">
        <img src="img/logo.png" alt="Lara Logo" class="brand-image img-circle elevation-3"
          style="opacity: .8">
        <span class="brand-text font-weight-light">LaraVue</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="img/user1.png" class="img-circle elevation-2" alt="User Image">
          </div>

          <div class="info">
            <a href="#" class="d-block">
              {{ Auth::user()->name }}
            </a>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

            <li class="nav-item">
              <router-link to="/dashboard" class="nav-link">
                <i class="nav-icon fas fa-tachometer-alt text-blue"></i>
                <p>Dashboard</p>
              </router-link>
            </li>

            <li class="nav-item">
              <router-link to="/profile" class="nav-link">
                <i class="nav-icon fas fa-user text-purple"></i>
                <p>Profile</p>
              </router-link>
            </li>

            <li class="nav-item">
              <router-link to="/products" class="nav-link">
                <i class="nav-icon fas fa-shopping-cart text-yellow"></i>
                <p>Shopping</p>
              </router-link>
            </li>

            @can('isAdmin')
            {{-- <li class="nav-item">
              <router-link to="/developer" class="nav-link">
                <i class="nav-icon fas fa-cogs text-orange"></i>
                <p>Developer</p>
              </router-link>
            </li> --}}
            
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-cog text-green"></i>
                <p>
                  Management
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>

              <ul class="nav nav-treeview">

                <li class="nav-item">
                  <router-link to="/users" class="nav-link">
                    <i class="fas fa-users nav-icon text-yellow"></i>
                    <p>Users</p>
                  </router-link>
                </li>

              </ul>
            </li>
            @endcan

            <li class="nav-item">
              <a class="nav-link" href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="nav-icon fas fa-power-off text-red"></i>
                <p>Logout</p>         
              </a>

              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
              </form>
            </li>
            
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

      <!-- Str Main Content -->
      <div class="content">
        <div class="container-fluid">
          
          <router-view></router-view>

          <vue-progress-bar></vue-progress-bar>

        </div><!-- end container-fluid -->
      </div>
      <!-- End Main Content -->

    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
      <!-- To the right -->
      <div class="float-right d-none d-sm-block">
        <button class="btn btn-success" @click.prevent="printMe" style="margin-right: 8px;">
          <i class="fa fa-print"></i> Print
        </button>
        Anything you want
      </div>
      <!-- Default to the left -->
      <p class="pt-2">
        <strong>Copyright &copy; 2019-2020 <a href="#">Abod</a>.</strong> All rights reserved.
      </p>
    </footer>
  </div>
  <!-- ./wrapper -->

  <!-- auth -->
  @auth
    <script>
      window.user = @json(auth()->user())
    </script>
  @endauth

  <!-- REQUIRED SCRIPTS -->
  <script src="/js/app.js"></script>

</body>

</html>
