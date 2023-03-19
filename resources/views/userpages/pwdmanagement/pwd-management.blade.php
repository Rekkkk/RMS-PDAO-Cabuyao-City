@extends('userpages.sidebar')

@section('content')
<style>
    .categories{
        display: none;
    }
</style>
@include('sweetalert::alert')
    <div class="container-fluid px-4">
        <div  iv class="row mb-3">
            <div class="col-lg-8">
                <h1>
                    @if(Auth::user()->barangay_id == 1)
                        <b>Walk in Transaction</b> 
                    @else
                        <b>PWD's List</b> 
                    @endif
                        {{ (Auth::user()->barangay_id == null || Auth::user()->barangay_id == 1)? "" : "Brgy. " . Auth::user()->barangay->barangay_name}}
                </h1>
            </div>
            <div class="col-lg-4 text-right">
                @if(Auth::user()->user_role == 0)  
                    <a href="{{ route('walkin.pwd') }}" class="btn btn-primary buttons">Add new PWD</a><br><br>
                    <a href="{{ route('walkin.history') }}" class="h6">View all walk-in's</a><br>
                @else
                <div class="dropdown dropleft float-right">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                            Generate Report
                        </button>
                        <div class="dropdown-menu text-center">
                            @if(Auth::user()->user_role == 2)
                                <a style="font-size: 14px;" class="dropdown-item"  href="" data-toggle="modal" data-target="#pwd-status" >PWD's Population</a>
                            @else
                                <a style="font-size: 14px;" class="dropdown-item"  data-toggle="modal" data-target="#pwd-status" href="" >PWD's Population</a>
                            @endif
                            <a style="font-size: 14px;" class="dropdown-item" data-toggle="modal" data-target="#disability-reports" href="#">Disabilty Population</a>
                        </div> 
                    </div>
                @endif
            </div>
        </div>
        @if(Auth::user()->user_role == 1)
        <div style="width: 100%; ">
            <div style="width: 90%;" class="m-auto">
                <canvas id="myChart" style="width:300px; height:80px;"></canvas>

            </div>
        </div>
            
        @endif
        
        <table id="pwd-list" class="table table-hover" style="width:100%" >
            <thead>
                <tr>
                    <th>PWD No.</th>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Barangay</th>
                    <th>Status</th>
                   
                </tr>
            </thead>
            <tbody id="myTable" style="cursor: pointer">
                @foreach($pwd as $pwds)       
                <tr onclick="window.location='{{ route('view.pwd', $pwds->pwd_id) }}';">
                    <td>{{$pwds->pwd_number}}</td>
                    <td>{{$pwds->last_name}}</td>
                    <td>{{$pwds->first_name}}</td>
                    <td>{{$pwds->middle_name}}</td>
                    <td>{{$pwds->barangay->barangay_name}}</td>
                        @if ($pwds->pwd_status->id_expiration > date("Y-m-d") && $pwds->pwd_status->cancelled == 0)
                            <td class="text-success">Active</td>
                        @elseif($pwds->pwd_status->id_expiration < date("Y-m-d") && $pwds->pwd_status->cancelled == 0)
                            <td class="text-danger">Inactive</td>
                        @else
                            <td class="text-danger">Cancelled</td>
                        @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if(Auth::user()->user_role == 2)
        <div class="modal" id="pwd-status">
            <div class="modal-dialog">
                <div class="modal-content"  >
                    <div class="modal-header">
                        <h4 class="modal-title">PWD Population Report</h4>
                        <button type="button" id="close-button-modal" class="close" data-dismiss="modal" >&times;</button>
                    </div>
                        <div class="modal-body">
                            <div class="m-auto">
                                <form action="{{ route('pwd.report.generate') }}" id="pwd-status-form" method="POST" target="_blank">
                                    @csrf
                                        <div class="row mt-1">
                                            <div class="col">
                                                <label class="title-detail">Select Barangay : </label>
                                                <select name="barangay_id" class="form-control" required>
                                                    <option selected disabled value="" >Please Select Barangay</option>
                                                    <option value="20" {{ old('barangay_id') == 'All Barangays' ? 'selected' : '' }}>All Barangays</option>
                                                    @foreach($barangays as $key => $barangay)
                                                        @if($key > 0)
                                                            <option value="{{ $barangay->barangay_id }}" {{ old('barangay_id') == $barangay->barangay_name ? 'selected' : '' }}>{{ $barangay->barangay_name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('barangay_id'))
                                                    <span class="text-danger">{{ $errors->first('barangay_id') }}</span>
                                                @endif
                                                
                                            </div>
                                        </div>
                                        <div class="text-center mt-3">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" id="statusCategory1" name="status_category" value="status_category1" required> 
                                                <label class="custom-control-label title-detail" for="statusCategory1">Yearly</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" id="statusCategory2" name="status_category" value="status_category2" required>
                                                <label class="custom-control-label title-detail" for="statusCategory2">Monthly</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio"class="custom-control-input" id="statusCategory3" name="status_category" value="status_category3" required>
                                                <label class="custom-control-label title-detail" for="statusCategory3">Quarterly</label>
                                            </div>
                                        </div>
                                        <div id="StatusYearly" class="mt-2 categories">
                                            <div class="row">
                                                <div class="col-6">
                                                    <select name="status_start_year" id="status_start_year" class="form-control yearpicker">
                                                        <option value="" selected disabled hidden >Select Start Year</option>
                                                    </select>
                                                </div>
                                                <div class="col-6">
                                                    <select name="status_end_year" id="status_end_year" class="form-control yearpicker">
                                                        <option value="" selected disabled hidden >Select End Year</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="StatusMonthly" class="mt-2 categories">
                                            <div class="row">
                                                <div class="col-12">
                                                    <select name="monthly_year" id="year_monthly" class="form-control yearpicker">
                                                        <option value="" selected disabled hidden >Select Year</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="StatusQuarterly" class="mt-2 categories">
                                            <div class="row">
                                                <div class="col-12">
                                                    <select name="quarterly_year" id="quarter_year" class="form-control yearpicker">
                                                        <option value="" selected disabled hidden >Select Year</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                
                                    <div class="row mt-3">
                                        <div class="col text-right  ">
                                            <button type="submit" class="btn btn-success w-25">Generate</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    @endif

    @if(Auth::user()->user_role == 1)
    <div class="modal" id="pwd-status">
        <div class="modal-dialog">
            <div class="modal-content"  >
                <div class="modal-header">
                    <h4 class="modal-title">PWD Population Report</h4>
                    <button type="button" id="close-button-modal" class="close" data-dismiss="modal" >&times;</button>
                </div>
                    <div class="modal-body">
                        <div class="m-auto">
                            <form action="{{ route('pwd.report.generate') }}" id="pwd-status-form" method="POST" target="_blank">
                                @csrf
                                    <div class="text-center mt-3">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="statusCategory1" name="status_category" value="status_category1" required> 
                                            <label class="custom-control-label title-detail" for="statusCategory1">Yearly</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="statusCategory2" name="status_category" value="status_category2" required>
                                            <label class="custom-control-label title-detail" for="statusCategory2">Monthly</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio"class="custom-control-input" id="statusCategory3" name="status_category" value="status_category3" required>
                                            <label class="custom-control-label title-detail" for="statusCategory3">Quarterly</label>
                                        </div>
                                    </div>
                                    <div id="StatusYearly" class="mt-2 categories">
                                        <div class="row">
                                            <div class="col-6">
                                                <select name="status_start_year" id="status_start_year" class="form-control yearpicker">
                                                    <option value="" selected disabled hidden >Select Start Year</option>
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <select name="status_end_year" id="status_end_year" class="form-control yearpicker">
                                                    <option value="" selected disabled hidden >Select End Year</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="StatusMonthly" class="mt-2 categories">
                                        <div class="row">
                                            <div class="col-12">
                                                <select name="monthly_year" id="year_monthly" class="form-control yearpicker">
                                                    <option value="" selected disabled hidden >Select Year</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="StatusQuarterly" class="mt-2 categories">
                                        <div class="row">
                                            <div class="col-12">
                                                <select name="quarterly_year" id="quarter_year" class="form-control yearpicker">
                                                    <option value="" selected disabled hidden >Select Year</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>                              
                                <div class="row mt-3">
                                    <div class="col text-right  ">
                                        <button type="submit" class="btn btn-success w-25">Generate</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
            </div>
        </div>
    </div>
    @endif
    <div class="modal" id="disability-reports">
        <div class="modal-dialog">
            <div class="modal-content"  >
                <div class="modal-header">
                    <h4 class="modal-title">Disability Population List</h4>
                    <button type="button" id="close-button-modal" class="close" data-dismiss="modal" >&times;</button>
                </div>
                <form action="{{ route('disability.report') }}" id="disability-form" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">                                    

                                <label class="title-detail">
                                     
                                    {{-- <a href="#" class="btn btn-info mr-1 mb-1" title="Message" data-toggle="popover" data-trigger="focus" data-content="If you want see all PWD's just put ALL DISABILITY" style="height: 30px; width: 30px; border-radius: 20px; display: flex; align-items: center; justify-content: center"><i class="fa fa-info" aria-hidden="true"></i></a> --}}
                                    Disability:
                                
                                </label>

                                    <input type="text" name="disability_name" id="disability_name" placeholder="Search Disability" class="form-control" autocomplete="off" required>
                                </div>
                                <div id="disability" style="margin-top: -17px; "></div>
                                @if ($errors->has('disability_name'))
                                    <span class="text-danger">{{ $errors->first('disability_name') }}</span>
                                @endif
                            </div>
                            <div class="col-lg-6">
                                <label class="title-detail">Age Range <span>(Optional)</span> : </label>
                                <div class="d-flex">
                                    <input type="number" class="form-control" value="0" min="0" id="min_age" name="min_age" >
                                    <h6 class="px-2 mt-2"><b>To</b></h6>
                                    <input type="number" class="form-control" value="0" min="0" id="max_age" name="max_age" >
                                </div>
                                @if ($errors->has('min_age'))
                                    <span class="text-danger">{{ $errors->first('min_age') }}</span>
                                @endif
                                @if ($errors->has('max_age'))
                                    <span class="text-danger">{{ $errors->first('max_age') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-6">
                                <label class="title-detail">Sex (Optional): </label>
                                <select name="sex" class="custom-select" >
                                    <option selected disabled value="null">Select Sex (Optional)</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                                @if ($errors->has('sex'))
                                    <span class="text-danger">{{ $errors->first('sex') }}</span>
                                @endif
                            </div>
                            <div class="col-lg-6 mt-4 p-1">
                                <label class="form-check-label title-detail ml-4">
                                    <input type="checkbox"
                                    class="form-check-input"  
                                    name="name_list" 
                                    value="1" >
                                    Include Name List 
                                </label>
                                @if ($errors->has('name_list'))
                                <span class="text-danger">{{ $errors->first('name_list') }}</span>
                            @endif
                            </div>
                        </div>
                            @if(Auth::user()->user_role == 2)
                                <div class="row mt-2">
                                    <div class="col">
                                        <label class="title-detail">Select Barangay : </label>
                                        <select name="barangay_id" class="form-control" required>
                                            <option selected disabled value="" >Please Select Barangay</option>
                                            <option value="20" {{ old('barangay_id') == 'All Barangays' ? 'selected' : '' }}>All Barangays</option>
                                            @foreach($barangays as $key => $barangay)
                                                @if($key > 0)
                                                    <option value="{{ $barangay->barangay_id }}" {{ old('barangay_id') == $barangay->barangay_name ? 'selected' : '' }}>{{ $barangay->barangay_name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @if ($errors->has('barangay_id'))
                                            <span class="text-danger">{{ $errors->first('barangay_id') }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endif   
                            <div class="row mt-3">
                                <div class="col text-right">
                                    <button type="submit" class="btn btn-success w-25">Generate</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @if(Auth::user()->user_role == 1)
        <script>
            var xValues = [
                "Active", 
                "Inactive", 
                "Cancelled", 
            ];
            var yValues = [{!! json_encode($active) !!}, 
                            {!! json_encode($inactive) !!}, 
                            {!! json_encode($cancelled) !!},];
            var barColors = ["green", "orange","red"];

            var myData = {
                labels: xValues,    
                datasets: [{
                            backgroundColor: barColors,
                            data: yValues
                        }]
		    };

            var myoption = {
                title: {
                    fontSize: 15,
                    display: true,
                    text: "",
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

            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',    	// Define chart type
                data: myData,    	// Chart data
                options: myoption 	// Chart Options [This is optional paramenter use to add some extra things in the chart].
            });
        </script>
    @endif
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="popover"]').popover();   


            $("input:radio[name=\'disability_category\']").change(function() { 
                $(".categories").hide();

                if(this.value == 'disability_category1' && this.checked){
                    $("#disabilityYearly").show();
                }
                else if(this.value == 'disability_category2' && this.checked){
                    $("#disabilityMonthly").show();
                }
                else if(this.value == 'disability_category3' && this.checked){
                    $("#disabilityQuarterly").show();
                }
                else if(this.value == '4' && this.checked){
                    $("#disabilityCustomize").show();
                }
            });

            function removeRequired(){
                $("#status_start_year").attr("required", false);
                $("#status_end_year").attr("required", false);
                $("#status_start_year").val("");
                $("#status_end_year").val("");
                $("#year_monthly").attr("required", false);
                $("#year_monthly").val("");
                $("#quarter_year").attr("required", false);
                $("#quarter_year").val("");
                $("#customize_date_start").attr("required", false);
                $("#customize_date_end").attr("required", false);
                $("#customize_date_start").val("");
                $("#customize_date_end").val("");
            }

            $("input:radio[name=\'status_category\']").change(function() { 

                $(".categories").hide();
                removeRequired();

                if(this.value == 'status_category1' && this.checked){
                    $("#StatusYearly").show();
                    $("#status_start_year").attr("required", true);
                    $("#status_end_year").attr("required", true);
                }
                else if(this.value == 'status_category2' && this.checked){
                    $("#StatusMonthly").show();
                    $("#year_monthly").attr("required", true);
                }
                else if(this.value == 'status_category3' && this.checked){
                    $("#StatusQuarterly").show();
                    $("#quarter_year").attr("required", true);
                }
                else if(this.value == 'status_category4' && this.checked){
                    $("#StatusCustomize").show();
                    $("#customize_date_start").attr("required", true);
                    $("#customize_date_end").attr("required", true);
                }
            });

            let startYear = 2010;
            let endYear = new Date().getFullYear()-1;
            for (i = endYear; i > startYear; i--){
                $('.yearpicker').append($('<option />').val(i).html(i));
            }
            
            $('#pwd-list').DataTable();

            $('#disability_name').on('keyup',function () {
                var query = $(this).val();
                $.ajax({
                    url:'{{ route('search') }}',
                    type:'GET',
                    data:{'disability_name':query},
                    success:function (data) {
                        $('#disability').html(data);
                    }
                })
            });
            $(document).on('click', 'li', function(){
                var value = $(this).text();
                if(value != 'No Data Found'){
                    $('#disability_name').val(value);
                    $('#disability').html("");
                }
            });
        });
    </script>   

@endsection