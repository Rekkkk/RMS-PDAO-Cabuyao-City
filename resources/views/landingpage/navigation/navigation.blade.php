<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">  
    <link rel="icon" href="{{ asset('/img/logo.jpg') }}">
    <script src="{{ asset('/js/jquery/jquery_3.6.0.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/jquery-ui_1.13.2.css') }}">
    <link href=" {{ asset('/font-awesome/css/font-awesome.css') }}" rel="stylesheet" />
    <link href="{{ asset('/css/style.css') }}" rel="stylesheet">
    <script src="{{ asset('/js/jquery/jquery-ui_1.13.2.js') }}"></script>
        <script>
            var disableDates = {!! json_encode($disableDate) !!}
            var appointmentLimit = {!! json_encode($appointmentLimit) !!}
            var appointmentDate = {!! json_encode($appointmentDate) !!}
        </script>   
        <script src="{{ asset('/js/calendar.js') }}"></script>
    <title>PDAO CABUYAO</title>
</head>
<body>
    <nav class="navbar navbar-expand-md bg-dark navbar-dark fixed-top" >
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('/img/logo.jpg') }}" style="width:40px;" class="rounded-pill">
            <span class="h5 ml-1">PDAO CABUYAO</span>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse flex-row-reverse" id="collapsibleNavbar">
            <ul class="navbar-nav ">
                <li class="nav-item dropdown dropleft float-right">
                    <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                        Appointment
                    </a>
                    <div class="dropdown-menu text-center">
                        <a class="dropdown-item" href="{{ route('appointment.new-applicant') }}">New Applicant</a>
                        <a class="dropdown-item" href="{{ route('appointment.renewal') }}"> PWD Renew ID</a>
                        <a class="dropdown-item" href="{{ route('appointment.lost.id') }}">PWD Lost ID</a>
                        <a class="dropdown-item" href="{{ route('appointment.pwd.cancellation') }}">Cancellation</a>
                    </div>
                  </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('programs') }}">Programs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('about.us') }}">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                </li>
            </ul>
        </div>  
    </nav>
    <section class="wrapper site-min-height" style="background: white">
       
                @yield('content')
      
    </section>
    <footer class="text-white text-center text-lg-start footer" style="background-color: #23242a;">
        <div class="container p-4">
            <div class="row mt-4">
                <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
                    <h5 class="text-uppercase mb-4">PWD OFFICE CABUYAO CITY</h5>
                    <p>
                        F.B. Bailon St, Cabuyao, 4025 Laguna
                    </p>
                </div>
                <div class="col-lg-6 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase mb-4">Opening hours</h5>
                    <p>   Mon - Fri: 8:00 am - 5:00 pm</p>
                </div>
            </div>

        </div>
      <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
        © 2022 Copyright :
        <a>PDAO CABUYAO CITY</a>
      </div>
    </footer>
    {{-- <script src="{{ asset('/js/jquery/jquery.slim.min.js') }}"></script> --}}
    <script src="{{ asset('/js/jquery/bootstrap.bundle.min.js') }}"></script>

</body>
</html>