<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>FARMASI VETERAN</title>
        <!-- plugins:css -->
        <link rel="stylesheet" href="{{ asset('vendors/iconfonts/mdi/css/materialdesignicons.min.css') }}">
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset('adminlte.min.css') }}">
        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="{{ asset('css/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/shared/style.css') }}">
        <!-- endinject -->
        <!-- Layout styles -->
        <link rel="stylesheet" href="{{ asset('css/demo_1/style.css') }}">
        <!-- End Layout styles -->
        <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" />
        @yield('style')
    </head>

<body>
    <div class="container-scroller">
        <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-middle justify-content-center">
                <a class="navbar-brand brand-logo" href="{{ url('/home') }}">
                    <img src="{{ asset('FVLogo.png') }}" alt="logo" style="width: 40px; height: 40px;" />
                    <h4 class="mt-3 ml-2" style="color: gray;">Farmasi Veteran</h4>
                </a>
                <a class="navbar-brand brand-logo-mini" href="{{ route('logout') }}"
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <img src="{{ asset('FVLogo.png') }}" alt="logo" />
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center">
                <ul class="navbar-nav ml-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown d-none d-xl-inline-block user-dropdown">
                            <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-toggle="dropdown"
                                aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                    data-toggle="offcanvas">
                    <span class="mdi mdi-menu"></span>
                </button>
            </div>
        </nav>
        <div class="page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            <nav class="sidebar-offcanvas sidebar-test" id="sidebar">
                <ul class="nav">
                    <br>
                    <li class="nav-item nav-category">
                        <b>{{ str_replace(['[', ']', '"'], ' ', Auth::user()->getRoleNames()) }} - </b>
                        Main Menu
                    </li>
                    @if ($roles->role_id == 1) {{-- HQ --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/home') }}">
                                <i class="menu-icon typcn typcn-document-text"></i>
                                <span class="menu-title">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('patient') }}">
                                <i class="menu-icon"></i>
                                <span class="menu-title">Patients</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/order') }}">
                                <i class="menu-icon"></i>
                                <span class="menu-title">Order</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('batch') }}">
                                <i class="menu-icon typcn typcn-bell"></i>
                                <span class="menu-title">Batch</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false"
                                aria-controls="ui-basic">
                                <i class="menu-icon typcn typcn-coffee"></i>
                                <span class="menu-title">Reports</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="ui-basic">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item">
                                        <a class="nav-link" href={{ url('/report/report_sales') }}>Sales</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href={{ url('/report/report_refill') }}>Refill</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href={{ url('/report/report_item') }}>Items</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href={{ url('/report/report_stocks') }}>Stocks</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href={{ url('/report/sales_report') }}>Sales Report</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @elseif($roles->role_id == 2) {{-- PHARMACIST --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/home') }}">
                                <i class="menu-icon typcn typcn-document-text"></i>
                                <span class="menu-title">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('patient') }}">
                                <i class="menu-icon"></i>
                                <span class="menu-title">Patients</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/order') }}">
                                <i class="menu-icon"></i>
                                <span class="menu-title">Order</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false"
                                aria-controls="ui-basic">
                                <i class="menu-icon typcn typcn-coffee"></i>
                                <span class="menu-title">Reports</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="ui-basic">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item">
                                        <a class="nav-link" href={{ url('/report/report_sales') }}>Sales</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href={{ url('/report/report_refill') }}>Refill</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href={{ url('/report/report_item') }}>Items</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href={{ url('/report/report_stocks') }}>Stocks</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href={{ url('/report/sales_report') }}>Sales Report</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="collapse" href="#ui-mantainance" aria-expanded="false"
                                aria-controls="ui-mantainance">
                                <i class="menu-icon"></i>
                                <span class="menu-title">Maintenance</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="ui-mantainance">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ url('/hospital/index') }}">
                                            Hospital
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ url('/item/index') }}">
                                            Item
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="collapse" href="#ui-others" aria-expanded="false"
                                aria-controls="ui-others">
                                <i class="menu-icon typcn typcn-coffee"></i>
                                <span class="menu-title">Others</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="ui-others">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('sticker.index') }}">
                                            <i class="menu-icon typcn typcn-user-outline"></i>
                                            Stickers
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('purchase.index') }}">
                                            <i class="menu-icon typcn typcn-user-outline"></i>
                                            Purchase
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('location.index') }}">
                                            <i class="menu-icon typcn typcn-user-outline"></i>
                                            Location
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @else {{-- ADMIN --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/home') }}">
                                <i class="menu-icon typcn typcn-document-text"></i>
                                <span class="menu-title">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('patient') }}">
                                <i class="menu-icon"></i>
                                <span class="menu-title">Patients</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/order') }}">
                                <i class="menu-icon"></i>
                                <span class="menu-title">Order</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('batch') }}">
                                <i class="menu-icon typcn typcn-bell"></i>
                                <span class="menu-title">Batch</span>
                            </a>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false"
                                aria-controls="ui-basic">
                                <i class="menu-icon typcn typcn-coffee"></i>
                                <span class="menu-title">Reports</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="ui-basic">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item">
                                        <a class="nav-link" href={{ url('/report/report_sales') }}>Sales</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href={{ url('/report/report_refill') }}>Refill</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href={{ url('/report/report_item') }}>Items</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href={{ url('/report/report_stocks') }}>Stocks</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href={{ url('/report/sales_report') }}>Sales Report</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="collapse" href="#ui-mantainance" aria-expanded="false"
                                aria-controls="ui-mantainance">
                                <i class="menu-icon"></i>
                                <span class="menu-title">Maintenance</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="ui-mantainance">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ url('/hospital/index') }}">
                                            Hospital
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ url('/item/index') }}">
                                            Item
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="collapse" href="#ui-others" aria-expanded="false"
                                aria-controls="ui-others">
                                <i class="menu-icon typcn typcn-coffee"></i>
                                <span class="menu-title">Others</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="ui-others">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('sticker.index') }}">
                                            <i class="menu-icon typcn typcn-user-outline"></i>
                                            Stickers
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('purchase.index') }}">
                                            <i class="menu-icon typcn typcn-user-outline"></i>
                                            Purchase
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('location.index') }}">
                                            <i class="menu-icon typcn typcn-user-outline"></i>
                                            Location
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="collapse" href="#ui-management" aria-expanded="false"
                                aria-controls="ui-management">
                                <i class="menu-icon typcn typcn-coffee"></i>

                                <span class="menu-title">Management</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="ui-management">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('users.index') }}">User Management</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('roles.index') }}">Role Management</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('borang.index') }}">
                            <i class="menu-icon typcn typcn-bell"></i>
                            <span class="menu-title">Borang Jev</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- partial -->
            <div class="main-panel">
                    @if (session()->has('status')) @include('msg.msg') @endif
                    @yield('content')
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                <footer class="footer">
                    <div class=" container-fluid clearfix">
                        <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © FV-PBM
                            2021</span>
                    </div>
                </footer>
                <!-- partial -->
            </div>

            <!-- main-panel ends -->
        </div>
    </div>

    <script src="{{ asset('vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('vendors/js/vendor.bundle.addons.js') }}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page-->
    <!-- End plugin js for this page-->
    <!-- inject:js -->
    <script src="{{ asset('js/shared/off-canvas.js') }}"></script>
    <script src="{{ asset('js/shared/misc.js') }}"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="{{ asset('js/demo_1/dashboards') }}"></script>
    <!-- End custom js for this page-->
    <script src="{{ asset('js/shared/jquery.cookie.js') }}" type="text/javascript"></script>
</body>

</html>
