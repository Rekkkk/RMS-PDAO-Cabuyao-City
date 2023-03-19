<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
		<title>Disability Population</title>
		<link rel="stylesheet" href="{{ public_path('css/bootstrap.min.css') }}">
	</head>
    <style>
        
        body,
        * {
            color-adjust: exact !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        body {
            margin-top: -90px;
            font-size: 22px;
            font-family: open sans;
        }
        .text-danger strong {
            color: #006fb3;
        }
        .receipt-main {
            margin-top: 50px;
            margin-bottom: 50px;
            padding: 20px 20px !important;
            position: relative;
   
            font-family: open sans;
            
        }
        .receipt-main p {
            line-height: 1.42857;
        }
        .receipt-footer h1 {
            font-size: 15px;
            font-weight: 400 !important;
            margin: 0 !important;
        }
        .receipt-main::after {
            content: '';
            height: 5px;
            left: 0;
            position: absolute;
            right: 0;
            top: -13px;
        }
        .receipt-main thead {
            background: #c9c9c9 none repeat scroll 0 0;
        }
        .receipt-main th, .receipt-main thead tr {
            background-color: #414143;
        }
        .receipt-main thead th {
            color: #fff;
        }
        .receipt-right h5 {
            font-size: 16px;
            font-weight: bold;
            margin: 0 0 7px 0;
        }
        .receipt-right p {
            font-size: 13px;
            margin: 0px;
        }
        .receipt-right p i {
            text-align: center;
            width: 18px;
        }
        .receipt-main td {
            padding: 9px 20px !important;
        }
        .receipt-main th {
            padding: 13px 20px !important;
        }
        .receipt-main td p:last-child {
            margin: 0;
            padding: 0;
        }
        .receipt-main td h2 {
            font-size: 20px;
            font-weight: 900;
            margin: 0;
            text-transform: uppercase;
        }
        .receipt-header-mid {
            margin: 24px 0;
            overflow: hidden;
        }
        .footer {
			position: fixed;
			left: 0;
			bottom: 0;
			width: 100%;
			text-align: center;
		}
    </style>
	<body>
		<div class="container" style="max-width:100%; margin:0; padding:0" >
			<div class="receipt-main">
				<div class="d-flex">
					<img
					src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/img/logo.jpg'))) }}" style="width: 100px;">
				<b style="margin-left: 18px; font-size: 40px;">PDAO CABUYAO CITY</b>
				</div>
                <div class="row mb-3">
                    <div class="col-12">
                            <h3 class="text-center">Disability Population Report</h3>	
                    </div>
                </div>
                <div class="row">
                    <div class="col text-right">
                        <p  style="font-size: 20px;"><b>Date Created :</b>{{ date(' F d, Y') }}</p>	
                    </div>
                </div>
                <div class="row">
                    <div class="col text-left">
                        <div class="receipt-right" >						
                            <p style="font-size: 20px;"><b>Barangay :</b> 
                                @if($barangay == "All Barangay")
                                    All Barangays
                                @else
                                    {{$barangay}}
                                @endif		
                            </p>		
                            <p style="font-size: 20px;"><b>Disability :</b> {{ $disabilityName }}</p>
                            <p  style="font-size: 20px;"><b>Sex :</b>  {{ $sex }}</p>		
                            <p  style="font-size: 20px;"><b>Age Range : </b>{{$age}}</p>		
                        </div>
                    </div>
                </div><br>
                <table class="table table-bordered ">
                    <thead>
                        <tr>
                            <th>PWD</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Registered</td>
                            <td>{{ $activePwd->count() + $inActivePwd->count() }}</td>
                        </tr>
                        <tr>
                            <td>Deducted</td>
                            <td>{{ $cancelledPwd->count() }}</td>
                       
                        </tr>
                        <tr>
                            <td> <b>TOTAL</b></td>
                            <td>{{ $activePwd->count() + $inActivePwd->count() + $cancelledPwd->count() }}</td>
                        
                        </tr>
                    </tbody>
                </table>
	        </div>
            <div class="row mt-5 footer">
                <div class="col-lg-12 text-center mt-5">
                    <h6 >{{Auth::user()->first_name ." " . Auth::user()->middle_name. " ". Auth::user()->last_name}}</h6>
                </div>
                <p class="text-center ml-4" style="margin-top: -10px; font-size: 16px;">
                    @if(Auth::user()->user_role == 2)
                        PDAO Officer in Charge
                    @else
                        PDAO Brgy. {{Auth::user()->barangay->barangay_name}} President
                    @endif
                </p>
            </div>
	    </div>

        <style>
            .page-break {
                page-break-after: always;
            }
        </style>

        @if($name_list == true)
        <div class="page-break"></div>
        <h4 class="text-center" style="margin-top: 100px; ">Active PWD's</h4>
        <table style="border:1px solid black; width:100%" >
                <tr>
                    <th style="border:1px solid black;">PWD No.</th>
                    <th style="border:1px solid black;">Name</th>
                    @if($barangay == "All Barangays")
                        <th style="border:1px solid black;">Barangay</th>
                    @endif		
                    
                </tr>
           
            
                @foreach($activePwd as $pwd)
                <tr>
                    <td style="border:1px solid black;">{{ $pwd->pwd_number}}</td>
                    <td style="border:1px solid black;">{{ $pwd->last_name . ", " . $pwd->first_name . " " . $pwd->middle_name }}</td>
                    @if($barangay == "All Barangays")
                    <td style="border:1px solid black;">{{ $pwd->barangay->barangay_name }}</td>
                    @endif
                </tr>
                @endforeach
            
        </table>
        <div class="page-break"></div>

        {{-- <div style="page-break-after:always"></div> --}}
        <h4 class="text-center" style="margin-top: 100px; ">Inactive PWD's</h4>
        <table style="border:1px solid black; width:100%" >
            
                <tr>
                    <th style="border:1px solid black;">PWD No.</th>
                    <th style="border:1px solid black;">Name</th>
                    @if($barangay == "All Barangays")
                        <th style="border:1px solid black;">Barangay</th>
                    @endif		
                    
                </tr>
          
           
                @foreach($inActivePwd as $pwd)
                <tr>
                    <td style="border:1px solid black;">{{ $pwd->pwd_number}}</td>
                    <td style="border:1px solid black;">{{ $pwd->last_name . ", " . $pwd->first_name . " " . $pwd->middle_name }}</td>
                    @if($barangay == "All Barangays")
                    <td style="border:1px solid black;">{{ $pwd->barangay->barangay_name }}</td>
                    @endif
                </tr>
                @endforeach
           
        </table>
        <div class="page-break"></div>

        {{-- <div style="page-break-after:always"></div> --}}
        <h4 class="text-center" style="margin-top: 100px; ">Deducted PWD's</h4>
        <table style="border:1px solid black; width:100%" >
            
                <tr>
                    <th style="border:1px solid black;">PWD No.</th>
                    <th style="border:1px solid black;">Name</th>
                    @if($barangay == "All Barangays")
                        <th style="border:1px solid black;">Barangay</th>
                    @endif		
                    
                </tr>
           
            
                @foreach($cancelledPwd as $pwd)
                <tr>
                    <td style="border:1px solid black;">{{ $pwd->pwd_number}}</td>
                    <td style="border:1px solid black;">{{ $pwd->last_name . ", " . $pwd->first_name . " " . $pwd->middle_name }}</td>
                    @if($barangay == "All Barangays")
                    <td style="border:1px solid black;">{{ $pwd->barangay->barangay_name }}</td>
                    @endif
                </tr>
                @endforeach
          
        </table>
        @endif
	</body>
	
</html>
