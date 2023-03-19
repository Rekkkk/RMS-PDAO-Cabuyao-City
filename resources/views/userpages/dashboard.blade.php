@extends('userpages.sidebar')

@section('content')

<link rel="stylesheet" href="{{ asset('/css/dashboard.css') }}">
<script src="{{ asset('/js/chart.js') }}"></script>

<div class="container-fluid p-4">
  <h1><b>Dashboard</b></h1><br>
  <canvas id="myChart" style="width:300px;height:100px;"></canvas>
  <div class="row mt-5">
    <div class="col-xl-6 col-md-6 dashboard" onclick="window.location='{{ route('pwd.management') }}';">
        <div class="card">
            <div class="card-block">
                <div class="d-flex p-2">
                    <i class="fa fa-wheelchair f-40"></i>  <h4 class="ml-2 mt-2 text-muted">Persons With Disability</h4>          
                </div>
                <div class="row align-items-center">
                    <div class="col-4 text-center " >
                        <h4 class="text-c-">{{$pwdStatus->where('id_expiration', '>', date("Y-m-d"))
                                                    ->where('cancelled', 0)->count() }}
                        </h4>
                        <h6 class="text-muted m-b-0">Active</h6>
                    </div>
                    <div class="col-4 text-center">
                        <h4 class="text-c-">{{$pwdStatus->where('id_expiration', '<', date("Y-m-d"))
                                                    ->where('cancelled', 0)->count() }}
                        </h4>
                        <h6 class="text-muted m-b-0">Inactive</h6>
                    </div>
                    <div class="col-4 text-center">
                        <h3 class="text-c-">{{$pwdStatus->where('cancelled', 1)->count() }}</h3>
                        <h6 class="text-muted m-b-0">Cancelled</h6>
                    </div>
                    
                </div>
            </div>
            <div class="card-footer bg-c-red">
                <div class="row align-items-center">
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-6 col-md-6 dashboard" onclick="window.location='{{ route('program.management', 'A-Z') }}';">
        <div class="card">
            <div class="card-block">
                <div class="d-flex p-2">
                    <i class="fa fa-th f-40"></i>  <h4 class="ml-2 mt-2 text-muted">PDAO Programs</h4>          
                </div>
                <div class="row align-items-center">
                    <div class="col-4 text-center">
                        <h4 class="text-c-">{{ $programs->where('encoding_status', 0)
                        ->where('is_done', 0)
                        ->count() }}</h4>
                        <h6 class="text-muted m-b-0">Ongoing</h6>
                    </div>
                    <div class="col-4 text-center">
                        <h4 class="text-c-">{{ $programs->where('encoding_status', 1)   
                            ->count() }}</h4>
                        <h6 class="text-muted m-b-0">Encoding   </h6>
                    </div>
                    <div class="col-4 text-center">
                        <h4 class="text-c-">{{ $programs->where('is_done', 1)
                            ->count() }}</h4>
                        <h6 class="text-muted m-b-0">Done</h6>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-c-red">
                <div class="row align-items-center">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-6 col-md-6 dashboard" onclick="window.location='{{ route('account.management') }}';">
        <div class="card">
            <div class="card-block">
                <div class="d-flex p-2">
                    <i class="fa fa-users f-40"></i>  <h4 class="ml-2 mt-2 text-muted">Staff's</h4>          
                </div>
                <div class="row align-items-center">
                    <div class="col-12 text-right">
                        <h2>{{ $numStaff }}</h2>
                        
                    </div>
                
                </div>
            </div>
            <div class="card-footer bg-c-red">
                <div class="row align-items-center">
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-md-6 dashboard" onclick="window.location='{{ route('account.management') }}';">
        <div class="card">
            <div class="card-block">
                <div class="d-flex p-2">
                    <i class="fa fa-user-circle f-40"></i>  <h4 class="ml-2 mt-2 text-muted">President (Per Brgy.)</h4>          
                </div>
                <div class="row align-items-center">
                    <div class="col-12 text-right">
                        <h2 >{{ $numAdmin }}</h2>
                    </div>
                   
                </div>
            </div>
            <div class="card-footer bg-c-red">
                <div class="row align-items-center">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var xValues = [
        "Baclaran", 
        "Banay-Banay", 
        "Banlic", 
        "Bigaa",
        "Butong",
        "Casile",
        "Diezmo",
        "Gulod",
        "Mamatid",
        "Marinig",
        "Nuigan",
        "Pitland",
        "Pulo",
        "Sala",
        "San Isidro",
        "Poblacion I",
        "Poblacion II",
        "Poblacion III",
    ];
   
    var pwdActive = {!! json_encode($numOfActive) !!};   
    var pwdInactive = {!! json_encode($numOfInactive) !!};    
    var pwdCancelled = {!! json_encode($numOfCancelled) !!};   
    
    console.log(pwdCancelled);
    
    new Chart("myChart", {
      type: "bar",
      data: {
        labels: xValues,
        datasets: [{
            label: "Active",
            backgroundColor: "green",
            data: pwdActive,
        },{
            label: "Inactive",
            backgroundColor: "orange",
            data: pwdInactive
        }
        ,{
            label: "Cancelled",
            backgroundColor: "red",
            data: pwdCancelled
        }]
      },
      options: {
        legend: {
                    display: true,
                    position: "bottom"
                },
        title: {
          display: true,
          fontColor: 'black',
          text: "Pwd Status per barangay",
          fontSize: 30
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    fontColor: 'black',
                    userCallback: function(label, index, labels) {
                        if (Math.floor(label) === label) {
                            return label;
                        }

                    },
                },
                
            }],
             xAxes: [{
                ticks: {
                    fontColor: 'black',
                    fontSize: 12,
                    
                },
            }]
        },
    }
      
    });
</script>
  
@endsection