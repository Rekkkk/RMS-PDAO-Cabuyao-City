<!DOCTYPE html>
<html>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.0.0/chartjs-plugin-datalabels.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  <link href="{{ asset('/css/dataTables.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}">
  <script src="{{ asset('/js/jquery/dataTables.js') }}"></script>
  <body>
    <div class="container-fluid px-5 mt-4">
        <div class="row">
            <div class="col-7">
               <h1>Office Transaction Reports</h1>
               <h4>Time Frame : 
                @if($category == "Yearly")
                {{$category . " " . $year[0] ."-". end($year)}}
                @else
                {{$category . " " . $year}}
                @endif
            </h4>
            </div>
            <div class="col text-right">
                <form action="{{ route('get.transaction.report') }}" enctype="multipart/form-data"  method="POST">
                    @csrf
                    <input type="file" style="display: none" name="graph1" id="graph1-files"><br>
                    <input type="file" style="display: none" name="graph2" id="graph2-files"><br>
                    <textarea id="scheduled" name="scheduled" style="display: none;"></textarea>
                    <textarea id="walk-in" name="walk_in" style="display: none;"></textarea>
                    <button class="btn btn-success">GENERATE PDF</button>
                </form>
            </div>
        </div>
        <div style="text-align:center;">
            <div class="row">
                <div class="col">
                    <canvas id="bar-chart-schedule" style=" width:100%;max-width:800px"></canvas>
                </div>
                <div class="col">
                    <canvas id="bar-chart-walkin" style=" width:100%;max-width:800px"></canvas>
                </div>
            </div>
            <img src="" id="bar-chart-schedule-img" style="display: none;width:100%;max-width:1000px">
            <img src="" id="bar-chart-walkin-img" class="mt-5" style="display: none; width:100%;max-width:1000px">
        </div>
        <div class="row mt-5">
            <div class="col">
                <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Appointment Type</th>
                                <th>Transaction Type</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                    <tbody>
                        @foreach($scheduled as $item)
                        <td>
                            @if($item->transaction == "Application" && $item->transaction != null)
                                {{ $item->applicant->last_name . ", ".  $item->applicant->first_name . " ".  $item->applicant->middle_name}}
                            @elseif($item->transaction == "Renewal ID")
                                {{ $item->renewal->pwd->last_name . ", " .  $item->renewal->pwd->first_name . " " .  $item->renewal->pwd->middle_name}}
                            @elseif($item->transaction == "Lost ID")
                                {{ $item->lostId->pwd->last_name . ", ".  $item->lostId->pwd->first_name . " ".  $item->lostId->pwd->middle_name}}
                            @else
                                {{ $item->cancellation->pwd->last_name . ", ".  $item->cancellation->pwd->first_name . " ".  $item->cancellation->pwd->middle_name}}
                            @endif
                            </td>
                            <td>Scheduled</td>
                            <td>{{ $item->transaction }}</td>
                            <td>{{date('F d, Y', strtotime($item->appointment_date))}}</td>
                        </tr>
                        @endforeach
                        @foreach($walkIn as $item)
                            <td>{{ $item->pwd->last_name . ", " .$item->pwd->first_name." " .$item->pwd->middle_name }}</td>
                            <td>Walk-In</td>
                            <td>{{ $item->transaction }}</td>
                            <td>{{date('F d, Y', strtotime($item->created_at))}}</td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    

<script>
    $('.table').DataTable({
        order: [[0, 'asc']],
    });

    if({!! json_encode($category)!!} == "Yearly"){

        var year = {!! json_encode($year)!!};
        var applications = {!! json_encode($applicationValue)!!};
        var renewalIDs = {!! json_encode($renewalValue)!!};
        var lostIDs = {!! json_encode($lostIDValue)!!};
        var cancellations = {!! json_encode($cancellationValue)!!};

        var myData = {
            labels: year,    
            datasets: [{
                        label: "Application",
                        backgroundColor: "#00ba38",
                        data: applications
                    },{
                        label: "Renewal ID",
                        backgroundColor: "#3486eb",
                        data: renewalIDs
                    },{
                        label: "Lost ID",
                        backgroundColor: "#ebb134",
                        data: lostIDs
                    },
                    {
                        label: "Cancellation",
                        backgroundColor: "red",
                        data: cancellations
                    }]
        };

        var myoption = {
                title: {
                    fontSize: 10,
                    display: true,
                    text: "Scheduled Transaction",
                    padding: 20,
                
                },
                legend: {
                    display: true,
                    position: "bottom"
                },
            
                tooltips: {
                    enabled: true
                },
                hover: {
                    animationDuration: 1
                },
                animation: {
                    duration: 1,
                    onComplete: function () {
                        var chartInstance = this.chart,
                            ctx = chartInstance.ctx;
                            ctx.textAlign = 'center';
                            ctx.fillStyle = "rgba(0, 0, 0, 1)";
                            ctx.textBaseline = 'bottom';

                            this.data.datasets.forEach(function (dataset, i) {
                                var meta = chartInstance.controller.getDatasetMeta(i);
                                meta.data.forEach(function (bar, index) {
                                    var data = dataset.data[index];
                                    ctx.fillText(data, bar._model.x, bar._model.y - 5);

                                });
                            });

                            document.getElementById('bar-chart-schedule-img').setAttribute('src', myChart.toBase64Image());
                            var canvas = document.getElementById('bar-chart-schedule');
                            canvas.toBlob( (blob) => {
                                const file = new File( [ blob ], "bar-chart-schedule-img.png" );
                                const dT = new DataTransfer();
                                dT.items.add( file );
                                document.getElementById( "graph1-files" ).files = dT.files;
                            });
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            reverse: false,
                            userCallback: function(label, index, labels) {
                                if (Math.floor(label) === label) {
                                    return label;
                                }
                            },
                        }
                    }]
                },
        };

        var ctx = document.getElementById('bar-chart-schedule').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',    	// Define chart type
            data: myData,    	// Chart data
            options: myoption 	// Chart Options [This is optional paramenter use to add some extra things in the chart].
        });

        var walkInApplications = {!! json_encode($WalkInApplicationValue)!!};
        var walkInrenewalIDs = {!! json_encode($WalkInRenewalValue)!!};
        var walkInlostIDs = {!! json_encode($WalkInLostIDValue)!!};
        var walkIncancellations = {!! json_encode($WalkInCancellationValue)!!};

        var myData = {
            labels: year,    
            datasets: [{
                        label: "Application",
                        backgroundColor: "#00ba38",
                        data: walkInApplications
                    },{
                        label: "Renewal ID",
                        backgroundColor: "#3486eb",
                        data: walkInrenewalIDs
                    },{
                        label: "Lost ID",
                        backgroundColor: "#ebb134",
                        data: walkInlostIDs
                    },
                    {
                        label: "Cancellation",
                        backgroundColor: "red",
                        data: walkIncancellations
                    }]
        };

        var myoption = {
                title: {
                    fontSize: 10,
                    display: true,
                    text: "Walk In Transaction",
                    padding: 20,
                
                },
                legend: {
                    display: true,
                    position: "bottom"
                },
            
                tooltips: {
                    enabled: true
                },
                hover: {
                    animationDuration: 1
                },
                animation: {
                    duration: 1,
                    onComplete: function () {
                        var chartInstance = this.chart,
                            ctx = chartInstance.ctx;
                            ctx.textAlign = 'center';
                            ctx.fillStyle = "rgba(0, 0, 0, 1)";
                            ctx.textBaseline = 'bottom';

                            this.data.datasets.forEach(function (dataset, i) {
                                var meta = chartInstance.controller.getDatasetMeta(i);
                                meta.data.forEach(function (bar, index) {
                                    var data = dataset.data[index];
                                    ctx.fillText(data, bar._model.x, bar._model.y - 5);

                                });
                            });

                            document.getElementById('bar-chart-walkin-img').setAttribute('src', myChart.toBase64Image());
                            var canvas = document.getElementById('bar-chart-walkin');
                            canvas.toBlob( (blob) => {
                                const file = new File( [ blob ], "bar-chart-walkin-img.png" );
                                const dT = new DataTransfer();
                                dT.items.add( file );
                                document.getElementById( "graph2-files" ).files = dT.files;
                            });

                        
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            reverse: false,
                            userCallback: function(label, index, labels) {
                                if (Math.floor(label) === label) {
                                    return label;
                                }
                            },
                        }
                    }]
                },
        };

        var ctx = document.getElementById('bar-chart-walkin').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',    	// Define chart type
            data: myData,    	// Chart data
            options: myoption 	// Chart Options [This is optional paramenter use to add some extra things in the chart].
        });

    }
    else if({!! json_encode($category)!!} == "Monthly"){

        var year = {!! json_encode($year)!!};
        var month = ["Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec"];

        var applications = {!! json_encode($applicationValue)!!};
        var renewalIDs = {!! json_encode($renewalValue)!!};
        var lostIDs = {!! json_encode($lostIDValue)!!};
        var cancellations = {!! json_encode($cancellationValue)!!};
        console.log(applications);
        var myData = {
            labels: month,    
            datasets: [{
                        label: "Application",
                        backgroundColor: "#00ba38",
                        data: applications
                    },{
                        label: "Renewal ID",
                        backgroundColor: "#3486eb",
                        data: renewalIDs
                    },{
                        label: "Lost ID",
                        backgroundColor: "#ebb134",
                        data: lostIDs
                    },
                    {
                        label: "Cancellation",
                        backgroundColor: "red",
                        data: cancellations
                    }]
        };

        var myoption = {
                title: {
                    fontSize: 10,
                    display: true,
                    text: "Scheduled Transaction",
                    padding: 20,
                
                },
                legend: {
                    display: true,
                    position: "bottom"
                },
            
                tooltips: {
                    enabled: true
                },
                hover: {
                    animationDuration: 1
                },
                animation: {
                    duration: 1,
                    onComplete: function () {
                        var chartInstance = this.chart,
                            ctx = chartInstance.ctx;
                            ctx.textAlign = 'center';
                            ctx.fillStyle = "rgba(0, 0, 0, 1)";
                            ctx.textBaseline = 'bottom';

                            this.data.datasets.forEach(function (dataset, i) {
                                var meta = chartInstance.controller.getDatasetMeta(i);
                                meta.data.forEach(function (bar, index) {
                                    var data = dataset.data[index];
                                    ctx.fillText(data, bar._model.x, bar._model.y - 5);

                                });
                            });

                            document.getElementById('bar-chart-schedule-img').setAttribute('src', myChart.toBase64Image());
                            var canvas = document.getElementById('bar-chart-schedule');
                            canvas.toBlob( (blob) => {
                                const file = new File( [ blob ], "bar-chart-schedule-img.png" );
                                const dT = new DataTransfer();
                                dT.items.add( file );
                                document.getElementById( "graph1-files" ).files = dT.files;
                            });

                        
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            reverse: false,
                            userCallback: function(label, index, labels) {
                                if (Math.floor(label) === label) {
                                    return label;
                                }
                            },
                        }
                    }]
                },
        };

        var ctx = document.getElementById('bar-chart-schedule').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',    	// Define chart type
            data: myData,    	// Chart data
            options: myoption 	// Chart Options [This is optional paramenter use to add some extra things in the chart].
        });

        var walkInApplications = {!! json_encode($WalkInApplicationValue)!!};
        var walkInrenewalIDs = {!! json_encode($WalkInRenewalValue)!!};
        var walkInlostIDs = {!! json_encode($WalkInLostIDValue)!!};
        var walkIncancellations = {!! json_encode($WalkInCancellationValue)!!};

        var myData = {
            labels: month,    
            datasets: [{
                        label: "Application",
                        backgroundColor: "#00ba38",
                        data: walkInApplications
                    },{
                        label: "Renewal ID",
                        backgroundColor: "#3486eb",
                        data: walkInrenewalIDs
                    },{
                        label: "Lost ID",
                        backgroundColor: "#ebb134",
                        data: walkInlostIDs
                    },
                    {
                        label: "Cancellation",
                        backgroundColor: "red",
                        data: walkIncancellations
                    }]
        };

        var myoption = {
                title: {
                    fontSize: 10,
                    display: true,
                    text: "Walk In Transaction",
                    padding: 20,
                
                },
                legend: {
                    display: true,
                    position: "bottom"
                },
            
                tooltips: {
                    enabled: true
                },
                hover: {
                    animationDuration: 1
                },
                animation: {
                    duration: 1,
                    onComplete: function () {
                        var chartInstance = this.chart,
                            ctx = chartInstance.ctx;
                            ctx.textAlign = 'center';
                            ctx.fillStyle = "rgba(0, 0, 0, 1)";
                            ctx.textBaseline = 'bottom';

                            this.data.datasets.forEach(function (dataset, i) {
                                var meta = chartInstance.controller.getDatasetMeta(i);
                                meta.data.forEach(function (bar, index) {
                                    var data = dataset.data[index];
                                    ctx.fillText(data, bar._model.x, bar._model.y - 5);

                                });
                            });

                            document.getElementById('bar-chart-walkin-img').setAttribute('src', myChart.toBase64Image());
                            var canvas = document.getElementById('bar-chart-walkin');
                            canvas.toBlob( (blob) => {
                                const file = new File( [ blob ], "bar-chart-walkin-img.png" );
                                const dT = new DataTransfer();
                                dT.items.add( file );
                                document.getElementById( "graph2-files" ).files = dT.files;
                            });

                        
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            reverse: false,
                            userCallback: function(label, index, labels) {
                                if (Math.floor(label) === label) {
                                    return label;
                                }
                            },
                        }
                    }]
                },
        };

        var ctx = document.getElementById('bar-chart-walkin').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',    	// Define chart type
            data: myData,    	// Chart data
            options: myoption 	// Chart Options [This is optional paramenter use to add some extra things in the chart].
        });
    }
    else if({!! json_encode($category)!!} == "Quarterly"){

        var year = {!! json_encode($year)!!};
        var quarter = ["Q1", "Q2", "Q3", "Q4"];

        var applications = {!! json_encode($applicationValue)!!};
        var renewalIDs = {!! json_encode($renewalValue)!!};
        var lostIDs = {!! json_encode($lostIDValue)!!};
        var cancellations = {!! json_encode($cancellationValue)!!};
        console.log(applications);
        var myData = {
            labels: quarter,    
            datasets: [{
                        label: "Application",
                        backgroundColor: "#00ba38",
                        data: applications
                    },{
                        label: "Renewal ID",
                        backgroundColor: "#3486eb",
                        data: renewalIDs
                    },{
                        label: "Lost ID",
                        backgroundColor: "#ebb134",
                        data: lostIDs
                    },
                    {
                        label: "Cancellation",
                        backgroundColor: "red",
                        data: cancellations
                    }]
        };

        var myoption = {
                title: {
                    fontSize: 10,
                    display: true,
                    text: "Scheduled Transaction",
                    padding: 20,
                
                },
                legend: {
                    display: true,
                    position: "bottom"
                },
            
                tooltips: {
                    enabled: true
                },
                hover: {
                    animationDuration: 1
                },
                animation: {
                    duration: 1,
                    onComplete: function () {
                        var chartInstance = this.chart,
                            ctx = chartInstance.ctx;
                            ctx.textAlign = 'center';
                            ctx.fillStyle = "rgba(0, 0, 0, 1)";
                            ctx.textBaseline = 'bottom';

                            this.data.datasets.forEach(function (dataset, i) {
                                var meta = chartInstance.controller.getDatasetMeta(i);
                                meta.data.forEach(function (bar, index) {
                                    var data = dataset.data[index];
                                    ctx.fillText(data, bar._model.x, bar._model.y - 5);

                                });
                            });

                            document.getElementById('bar-chart-schedule-img').setAttribute('src', myChart.toBase64Image());
                            var canvas = document.getElementById('bar-chart-schedule');
                            canvas.toBlob( (blob) => {
                                const file = new File( [ blob ], "bar-chart-schedule-img.png" );
                                const dT = new DataTransfer();
                                dT.items.add( file );
                                document.getElementById( "graph1-files" ).files = dT.files;
                            });

                        
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            reverse: false,
                            userCallback: function(label, index, labels) {
                                if (Math.floor(label) === label) {
                                    return label;
                                }
                            },
                        }
                    }]
                },
        };

        var ctx = document.getElementById('bar-chart-schedule').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',    	// Define chart type
            data: myData,    	// Chart data
            options: myoption 	// Chart Options [This is optional paramenter use to add some extra things in the chart].
        });

        var walkInApplications = {!! json_encode($WalkInApplicationValue)!!};
        var walkInrenewalIDs = {!! json_encode($WalkInRenewalValue)!!};
        var walkInlostIDs = {!! json_encode($WalkInLostIDValue)!!};
        var walkIncancellations = {!! json_encode($WalkInCancellationValue)!!};

        var myData = {
            labels: quarter,    
            datasets: [{
                        label: "Application",
                        backgroundColor: "#00ba38",
                        data: walkInApplications
                    },{
                        label: "Renewal ID",
                        backgroundColor: "#3486eb",
                        data: walkInrenewalIDs
                    },{
                        label: "Lost ID",
                        backgroundColor: "#ebb134",
                        data: walkInlostIDs
                    },
                    {
                        label: "Cancellation",
                        backgroundColor: "red",
                        data: walkIncancellations
                    }]
        };

        var myoption = {
                title: {
                    fontSize: 10,
                    display: true,
                    text: "Walk In Transaction",
                    padding: 20,
                
                },
                legend: {
                    display: true,
                    position: "bottom"
                },
            
                tooltips: {
                    enabled: true
                },
                hover: {
                    animationDuration: 1
                },
                animation: {
                    duration: 1,
                    onComplete: function () {
                        var chartInstance = this.chart,
                            ctx = chartInstance.ctx;
                            ctx.textAlign = 'center';
                            ctx.fillStyle = "rgba(0, 0, 0, 1)";
                            ctx.textBaseline = 'bottom';

                            this.data.datasets.forEach(function (dataset, i) {
                                var meta = chartInstance.controller.getDatasetMeta(i);
                                meta.data.forEach(function (bar, index) {
                                    var data = dataset.data[index];
                                    ctx.fillText(data, bar._model.x, bar._model.y - 5);

                                });
                            });

                            document.getElementById('bar-chart-walkin-img').setAttribute('src', myChart.toBase64Image());
                            var canvas = document.getElementById('bar-chart-walkin');
                            canvas.toBlob( (blob) => {
                                const file = new File( [ blob ], "bar-chart-walkin-img.png" );
                                const dT = new DataTransfer();
                                dT.items.add( file );
                                document.getElementById( "graph2-files" ).files = dT.files;
                            });

                        
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            reverse: false,
                            userCallback: function(label, index, labels) {
                                if (Math.floor(label) === label) {
                                    return label;
                                }
                            },
                        }
                    }]
                },
        };

        var ctx = document.getElementById('bar-chart-walkin').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',    	// Define chart type
            data: myData,    	// Chart data
            options: myoption 	// Chart Options [This is optional paramenter use to add some extra things in the chart].
        });
    }
    else{
        var year = {!! json_encode($year)!!};
        var transactions = ["Application", "Renewal ID", "Lost ID", "Cancellation"];
        var transactionColor = ["#00ba38", "#3486eb", "#ebb134", "red"];
        var transactionValue = [{!! json_encode($applicationValue)!!}[0], {!! json_encode($renewalValue)!!}[0], {!! json_encode($lostIDValue)!!}[0], {!! json_encode($cancellationValue)!!}[0]];

        var myData = {
            labels: transactions,    
            datasets: [{
                        backgroundColor: transactionColor,
                        data: transactionValue
                    }]
        };

        var myoption = {
                title: {
                    fontSize: 14,
                    display: true,
                    text: "Scheduled Transaction",
                    padding: 20,
                
                },
                legend: {
                    display: false,
                    position: "bottom"
                },
            
                tooltips: {
                    enabled: true
                },
                hover: {
                    animationDuration: 1
                },
                animation: {
                    duration: 1,
                    onComplete: function () {
                        var chartInstance = this.chart,
                            ctx = chartInstance.ctx;
                            ctx.textAlign = 'center';
                            ctx.fillStyle = "rgba(0, 0, 0, 1)";
                            ctx.textBaseline = 'bottom';

                            this.data.datasets.forEach(function (dataset, i) {
                                var meta = chartInstance.controller.getDatasetMeta(i);
                                meta.data.forEach(function (bar, index) {
                                    var data = dataset.data[index];
                                    ctx.fillText(data, bar._model.x, bar._model.y - 5);

                                });
                            });

                            document.getElementById('bar-chart-schedule-img').setAttribute('src', myChart.toBase64Image());
                            var canvas = document.getElementById('bar-chart-schedule');
                            canvas.toBlob( (blob) => {
                                const file = new File( [ blob ], "bar-chart-schedule-img.png" );
                                const dT = new DataTransfer();
                                dT.items.add( file );
                                document.getElementById( "graph1-files" ).files = dT.files;
                            });

                        
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            reverse: false,
                            userCallback: function(label, index, labels) {
                                if (Math.floor(label) === label) {
                                    return label;
                                }
                            },
                        }
                    }]
                },
        };

        var ctx = document.getElementById('bar-chart-schedule').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',    	// Define chart type
            data: myData,    	// Chart data
            options: myoption 	// Chart Options [This is optional paramenter use to add some extra things in the chart].
        });

        var WalkIntransactionValue = [{!! json_encode($WalkInApplicationValue)!!}[0], {!! json_encode($WalkInRenewalValue)!!}[0], {!! json_encode($WalkInLostIDValue)!!}[0], {!! json_encode($WalkInCancellationValue)!!}[0]];
        var myData = {
            labels: transactions,    
            datasets: [{
                        backgroundColor: transactionColor,
                        data: WalkIntransactionValue
                    }]
        };

        var myoption = {
                title: {
                    fontSize: 14,
                    display: true,
                    text: "Walk In Transaction",
                    padding: 20,
                
                },
                legend: {
                    display: false,
                    position: "bottom"
                },
            
                tooltips: {
                    enabled: true
                },
                hover: {
                    animationDuration: 1
                },
                animation: {
                    duration: 1,
                    onComplete: function () {
                        var chartInstance = this.chart,
                            ctx = chartInstance.ctx;
                            ctx.textAlign = 'center';
                            ctx.fillStyle = "rgba(0, 0, 0, 1)";
                            ctx.textBaseline = 'bottom';

                            this.data.datasets.forEach(function (dataset, i) {
                                var meta = chartInstance.controller.getDatasetMeta(i);
                                meta.data.forEach(function (bar, index) {
                                    var data = dataset.data[index];
                                    ctx.fillText(data, bar._model.x, bar._model.y - 5);

                                });
                            });

                            document.getElementById('bar-chart-walkin-img').setAttribute('src', myChart.toBase64Image());
                            var canvas = document.getElementById('bar-chart-walkin');
                            canvas.toBlob( (blob) => {
                                const file = new File( [ blob ], "bar-chart-walkin-img.png" );
                                const dT = new DataTransfer();
                                dT.items.add( file );
                                document.getElementById( "graph2-files" ).files = dT.files;
                            });

                        
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            reverse: false,
                            userCallback: function(label, index, labels) {
                                if (Math.floor(label) === label) {
                                    return label;
                                }
                            },
                        }
                    }]
                },
        };

        var ctx = document.getElementById('bar-chart-walkin').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',    	// Define chart type
            data: myData,    	// Chart data
            options: myoption 	// Chart Options [This is optional paramenter use to add some extra things in the chart].
        });
    }

    document.getElementById( "scheduled" ).value = JSON.stringify({!! json_encode($scheduled)!!})
    document.getElementById( "walk-in" ).value = JSON.stringify({!! json_encode($walkIn)!!})

</script>

</body>
</html>
