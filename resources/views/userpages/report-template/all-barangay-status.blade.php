<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.0.0/chartjs-plugin-datalabels.min.js"></script>
    <link href="{{ asset('/css/dataTables.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}">
    <title>PWD Members Report</title>
</head>
<body>
    <div class="container-fluid px-5 mt-4">
        <div class="row">
            <div class="col-12">
                <h1><b>PWD Members Report</b>
                    @if($barangay->barangay_id == 1)
                    (All Barangays)
                    @else
                    ({{$barangay->barangay_name}})
                    @endif
                </h1>
                <h2>
                    @if($category == "status_category1")
                        {{ "Yearly ".$year[0]."-".end($year) }}
                    @elseif($category == "status_category2")
                        {{ "Monthly ".$year}}
                    @elseif($category == "status_category3")    
                        {{ "Quarterly ".$year}}
                    @endif
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-right">
                <form action="{{ route('upload.graph', $barangay) }}" style="height: -200px;" enctype="multipart/form-data"  method="POST">
                    @csrf
                    <div style="display: none">
                        <input type="file" name="file" id="tests"><br>
                        <input type="file" name="yealyChart" id="yealyChart"><br>
                        <input type="text" name="newPwd" id="newPwd"><br>
                        <input type="text" name="deductedPwd" id="deductedPwd"><br>
                        <input type="text" name="category" id="category"><br>
                        <input type="text" name="added_age" id="added-age"><br>
                        <input type="text" name="deducted_age" id="deducted-age"><br>
                        <input type="text" name="deducted_sex" id="deducted-sex"><br>
                        <input type="text" name="added_sex" id="added-sex"><br>
                    </div>
                    <textarea id="added-pwd" name="added_pwds" style="display: none;"></textarea>
                    <textarea id="deducted-pwd" name="deducted_pwds" style="display: none;"></textarea>
                    <button class="btn btn-success" style="width: 170px;">GENERATE PDF</button>
                </form>
            </div>
        </div>
        <canvas id="yearlyChart" style="margin:auto;width:100%;max-width:1000px"></canvas>
        <div style="text-align:center;">
            <img src="" id="yearlyMembersImg"style="display:none; width:100%;max-width:1000px">
        </div>
        <div class="row mt-4">
            <div class="col-xl mb-4">
                <h3 class="text-center mb-3"><b>New PWD</b></h3>
                <table id="added_pwd" class="table">
                    <thead>
                        <tr>
                            <th>PWD ID</th>
                            <th>Name</th>
                            @if($barangay->barangay_id == 1)
                                <th>Barangay</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($addedPwd as $pwd)
                        <tr>
                            <td>{{$pwd->pwd_number}}</td>
                            <td>{{$pwd->last_name.", ". $pwd->first_name . " ". $pwd->middle_name}}</td>
                            @if($barangay->barangay_id == 1)
                                <td>{{$pwd->barangay->barangay_name}}</td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                  </table>
            </div>
            <div class="col-xl mb-4">
                <h3 class="text-center mb-3"><b>Deducted PWD</b></h3>
                <table id="deducted_pwd" class="table">
                    <thead>
                        <tr>
                            <th>PWD ID</th>
                            <th>Name</th>
                            @if($barangay->barangay_id == 1)
                                <th>Barangay</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deductedPwd as $pwd)
                        <tr>
                            <td>{{$pwd->pwd_number}}</td>
                            <td>{{$pwd->last_name.", ". $pwd->first_name . " ". $pwd->middle_name}}</td>
                            @if($barangay->barangay_id == 1)
                                <td>{{$pwd->barangay->barangay_name}}</td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                  </table>
            </div>
        </div>
        

    </div>
</body>
<script src="{{ asset('/js/jquery/dataTables.js') }}"></script>
<script>
    $(document).ready(function () {
        $('#added_pwd').DataTable();
        $('#deducted_pwd').DataTable();
    });

    if({!! json_encode($category)!!} == "status_category1"){
        var year = {!! json_encode($year)!!};
        var pwdPopulation = {!! json_encode($totalPopulation)!!};
        console.log(pwdPopulation);
        var yearValueNewPwd = {!! json_encode($valueNewPwd)!!};
        var yearValueDeductedPwd = {!! json_encode($valueDeductedPwd)!!};
        var myData = {
            labels: year,    
            datasets: [{
                        label: "Current Population",
                        backgroundColor: "#03a5fc",
                        data: pwdPopulation
                    },
                    {
                        label: "Added Pwd",
                        backgroundColor: "green",
                        data: yearValueNewPwd
                    },
                    {
                        label: "Deducted Pwd",
                        backgroundColor: "red",
                        data: yearValueDeductedPwd
                    }]
		};

		var myoption = {
                title: {
                    fontSize: 15,
                    display: true,
                    text: "Yearly Members Report",
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

                        document.getElementById('yearlyMembersImg').setAttribute('src', myChart.toBase64Image());
                        var canvas = document.getElementById('yearlyChart');
                        canvas.toBlob( (blob) => {
                            const file = new File( [ blob ], "yearlyMembersImg.png" );
                            const dT = new DataTransfer();
                            dT.items.add( file );
                            document.getElementById( "yealyChart" ).files = dT.files;
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

		var ctx = document.getElementById('yearlyChart').getContext('2d');
		var myChart = new Chart(ctx, {
			type: 'bar',    	// Define chart type
			data: myData,    	// Chart data
			options: myoption 	// Chart Options [This is optional paramenter use to add some extra things in the chart].
		});

        let newPwdvalue = 0;
        let deductedPwdvalue = 0;

        let increasingRate = ((pwdPopulation[pwdPopulation.length-1] - pwdPopulation[0]) / pwdPopulation[0]) * 100
        document.getElementById( "newPwd" ).value  = Math.round((increasingRate + Number.EPSILON) * 100) / 100;
        document.getElementById( "deductedPwd" ).value  = Math.round((deductedPwdvalue/(yearValueDeductedPwd.length-1) + Number.EPSILON) * 100) / 100;
        document.getElementById( "category" ).value = "Year "+year[0]+"-"+year[year.length-1]
        document.getElementById( "added-sex" ).value  = {!! json_encode($info[0])!!}
        document.getElementById( "deducted-sex" ).value  = {!! json_encode($info[1])!!}
        document.getElementById( "added-age" ).value = {!! json_encode($info[2])!!}
        document.getElementById( "deducted-age" ).value = {!! json_encode($info[3])!!}
        document.getElementById( "added-pwd" ).value = JSON.stringify({!! json_encode($addedPwd)!!})
        document.getElementById( "deducted-pwd" ).value = JSON.stringify({!! json_encode($deductedPwd)!!})

    }
    else if({!! json_encode($category)!!} == "status_category2"){

        var year = {!! json_encode($year)!!};
        var month = ["Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec"];
        var pwdPopulation = {!! json_encode($totalPopulation)!!};

        console.log(pwdPopulation);
        var monthValueNewPwd = {!! json_encode($valueNewPwd)!!};
        var monthValueDeductedPwd = {!! json_encode($valueDeductedPwd)!!};

        var myData = {
            labels: month,    
            datasets: [{
                        label: "Current Population",
                        backgroundColor: "#03a5fc",
                        data: pwdPopulation
                    },{
                        label: "Added Pwd",
                        backgroundColor: "green",
                        data: monthValueNewPwd
                    },
                    {
                        label: "Deducted Pwd",
                        backgroundColor: "red",
                        data: monthValueDeductedPwd
                    }]
		};

		var myoption = {
                title: {
                    fontSize: 15,
                    display: true,
                    text: "Monthly Members Report",
                    padding: 20
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

                            document.getElementById('yearlyMembersImg').setAttribute('src', myChart.toBase64Image());
                            var canvas = document.getElementById('yearlyChart');
                            canvas.toBlob( (blob) => {
                                const file = new File( [ blob ], "yearlyMembersImg.png" );
                                const dT = new DataTransfer();
                                dT.items.add( file );
                                document.getElementById( "yealyChart" ).files = dT.files;
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

		var ctx = document.getElementById('yearlyChart').getContext('2d');
		var myChart = new Chart(ctx, {
			type: 'bar',    	// Define chart type
			data: myData,    	// Chart data
			options: myoption 	// Chart Options [This is optional paramenter use to add some extra things in the chart].
		});

        var newPwdvalue = 0;
        var deductedPwdvalue = 0;
     
        let increasingRate = ((pwdPopulation[pwdPopulation.length-1] - pwdPopulation[0]) / pwdPopulation[0]) * 100
        document.getElementById( "newPwd" ).value  = Math.round((increasingRate + Number.EPSILON) * 100) / 100;
        document.getElementById( "deductedPwd" ).value  = Math.round((deductedPwdvalue/(monthValueDeductedPwd.length-1) + Number.EPSILON) * 100) / 100;
        document.getElementById( "category" ).value = "Month of " + year;
        document.getElementById( "added-sex" ).value  = {!! json_encode($info[0])!!}
        document.getElementById( "deducted-sex" ).value  = {!! json_encode($info[1])!!}
        document.getElementById( "added-age" ).value = {!! json_encode($info[2])!!}
        document.getElementById( "deducted-age" ).value = {!! json_encode($info[3])!!}
        document.getElementById( "added-pwd" ).value = JSON.stringify({!! json_encode($addedPwd)!!})
        document.getElementById( "deducted-pwd" ).value = JSON.stringify({!! json_encode($deductedPwd)!!})



    }
    else if({!! json_encode($category)!!} == "status_category3"){
        var year = {!! json_encode($year)!!};
        var quarter = ["Q1", "Q2", "Q3", "Q4"];
        var pwdPopulation = {!! json_encode($totalPopulation)!!};
        var quarterValueNewPwd = {!! json_encode($valueNewPwd)!!};
        var quaterValueDeductedPwd = {!! json_encode($valueDeductedPwd)!!};

        var myData = {
            labels: quarter,    
            datasets: [{
                        label: "Current Population",
                        backgroundColor: "#03a5fc",
                        data: pwdPopulation
                    },{
                        label: "Added Pwd",
                        backgroundColor: "green",
                        data: quarterValueNewPwd
                    },
                    {
                        label: "Deducted Pwd",
                        backgroundColor: "red",
                        data: quaterValueDeductedPwd
                    }]
		};

		var myoption = {
                title: {
                    fontSize: 15,
                    display: true,
                    text: "Quarterly Members Report",
                    padding: 20
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

                            document.getElementById('yearlyMembersImg').setAttribute('src', myChart.toBase64Image());
                            var canvas = document.getElementById('yearlyChart');
                            canvas.toBlob( (blob) => {
                                const file = new File( [ blob ], "yearlyMembersImg.png" );
                                const dT = new DataTransfer();
                                dT.items.add( file );
                                document.getElementById( "yealyChart" ).files = dT.files;
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

		var ctx = document.getElementById('yearlyChart').getContext('2d');
		var myChart = new Chart(ctx, {
			type: 'bar',    	// Define chart type
			data: myData,    	// Chart data
			options: myoption 	// Chart Options [This is optional paramenter use to add some extra things in the chart].
		});

        var newPwdvalue = 0;
        let deductedPwdvalue = 0;
        let increasingRate = ((pwdPopulation[pwdPopulation.length-1] - pwdPopulation[0]) / pwdPopulation[0]) * 100
        document.getElementById( "newPwd" ).value  = Math.round((increasingRate + Number.EPSILON) * 100) / 100;
        document.getElementById( "deductedPwd" ).value  = Math.round((deductedPwdvalue/(quaterValueDeductedPwd.length-1) + Number.EPSILON) * 100) / 100;
        document.getElementById( "category" ).value = "Quarterly " + year;
        document.getElementById( "added-sex" ).value  = {!! json_encode($info[0])!!}
        document.getElementById( "deducted-sex" ).value  = {!! json_encode($info[1])!!}
        document.getElementById( "added-age" ).value = {!! json_encode($info[2])!!}
        document.getElementById( "deducted-age" ).value = {!! json_encode($info[3])!!}
        document.getElementById( "added-pwd" ).value = JSON.stringify({!! json_encode($addedPwd)!!})
        document.getElementById( "deducted-pwd" ).value = JSON.stringify({!! json_encode($deductedPwd)!!})
    }
   
</script>
</html>

