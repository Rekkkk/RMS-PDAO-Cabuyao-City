<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDAO CABUYAO</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('/img/logo.jpg') }}">
    <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}">
    <script src="{{ asset('/js/jquery/jquery_3.6.0.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('/css/jquery-ui_1.13.2.css') }}">
    <script src="{{ asset('/js/jquery/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('/js/jquery/jquery-ui.js') }}"></script>
    <link href=" {{ asset('/font-awesome/css/font-awesome.css') }}" rel="stylesheet" />
    <link href="{{ asset('/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/style-responsive.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/dataTables.css') }}" rel="stylesheet">
    <script src="{{ asset('/js/jquery/jquery-ui_1.13.2.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>

        <script>
            var disableDates = {!! json_encode($disableDate) !!}
            var appointmentLimit = {!! json_encode($appointmentLimit) !!}
            var appointmentDate = {!! json_encode($appointmentDate) !!}
        </script>   
        <script src="{{ asset('/js/calendar.js') }}"></script>

</head>
<body>
    <section id="container">
        <header class="header black-bg">
            @if(Auth::user()->user_role != 3)
            <div class="sidebar-toggle-box">
                <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
            </div>
            @endif
            <a href="{{ route('dashboard') }}" class="logo"> <img src="{{ asset('/img/logo.jpg') }}" style="width: 35px;" class="rounded-pill"> <b>PDAO<span> CABUYAO</span></b></a>
            <ul class="navbar-nav flex-row-reverse m-3 h6">
                <li class="nav-item dropdown ">
                    <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown" style="color:white">
                         <i class="fa fa-user"></i>
                         <span class="ml-2">{{Auth::user()->last_name}}</span>
                    </a>
                    <div class="dropdown-menu text-center">
                        <a class="dropdown-item" href="{{ route('my.account') }}">
                            <i class="fa fa-cog"></i>
                            <span class="ml-1">My Account</span>
                        </a>
                        <a class="dropdown-item" href="{{ route('logout') }}">
                            <i class="fa fa fa-times"></i>
                            <span class="ml-1">Sign Out</span>
                        </a>
                    </div>
                </li>
            </ul>
        </header>
        @if(Auth::user()->user_role != 3)
            <aside>
                <div id="sidebar" class="nav-collapse ">
                    <ul class="sidebar-menu" id="nav-accordion">
                        @if(Auth::user()->user_role == 0)
                            <li>
                                <a href="{{ route('appointment.page') }}">
                                    <i class="fa fa-calendar"></i>
                                    <span>APPOINTMENT</span>
                                </a>
                            </li>  
                            <li>
                                <a href="{{ route('walk.page') }}">
                                    <i class="fa fa-users"></i>
                                    <span>WALK-IN's</span>
                                </a>
                            </li>
                        @endif
                        @if(Auth::user()->user_role == 1 || Auth::user()->user_role == 2)
                            @if(Auth::user()->user_role == 2)
                                <li>
                                    <a href="{{ route('dashboard') }}">
                                        <i class="fa fa-dashboard"></i>
                                        <span>DASHBOARD</span>
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a href="{{ route('pwd.management') }}">
                                    <i class="fa fa-users"></i>
                                    <span>PWD's</span>
                                </a>
                            </li>
                            @if(Auth::user()->user_role == 2)
                            <li>
                                <a href="{{ route('signatory') }}">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                    <span>SIGNATORY</span>
                                </a>
                            </li>
                            @endif
                            <li>
                                <a href="{{ route('program.management', 'A-Z') }}">
                                    <i class="fa fa-th"></i>                                                                                                                 
                                    <span>PROGRAMS</span>
                                </a>
                            </li>
                            @if(Auth::user()->user_role == 2)
                                <li>
                                    <a href="{{ route('account.management') }}">
                                        <i class="fa fa-user-circle"></i>
                                        <span>ACCOUNTS</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('activity.log') }}">
                                        <i class="fa fa-book"></i>
                                        <span>LOGS</span>
                                    </a>
                                </li>
                            @endif
                            
                        @endif
                    </ul>
                </div>
            </aside>
            <section id="main-content">
                <section class="wrapper site-min-height">
                    <div class="row mt">
                        <div class="col-lg-12">
                            @yield('content')
                        </div>
                    </div>
                </section>
        @else
            <section class="wrapper site-min-height">
                <div class="row mt">
                    <div class="col-lg-12">
                        @yield('content')
                    </div>
                </div>
            </section>
        @endif
        <footer class="site-footer">
            <div class="text-center">
                <p> 
                    &copy; <strong>PDAO Cabuyao</strong>. All Rights Reserved 2022
                </p>
            </div>
        </footer>
    </section>
        
    <script class="include" type="text/javascript" src="{{ asset('/js/jquery.dcjqaccordion.2.7.js') }}"></script>
    <script src="{{ asset('/js/common-scripts.js') }}"></script>
    <script src="{{ asset('/js/jquery/dataTables.js') }}"></script>
</body>

</html>
