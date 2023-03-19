<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta
			name="viewport"
			content="width=device-width, initial-scale=1, shrink-to-fit=no"
		/>
		<title>Appointment PWD</title>
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
		}
		.text-danger strong {
			color: #006fb3;
		}
		.receipt-main {
			background: #ffffff none repeat scroll 0 0;
			margin-top: 50px;
			margin-bottom: 50px;
			position: relative;
			font-family: open sans;
		}
		.receipt-main p {
			font-family: open sans;
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

		.receipt-main th,
		.receipt-main thead tr {
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
			padding: 6px 6px !important;
		}
		.receipt-main th {
			padding: 6px 6px !important;
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
		#container {
			background-color: #dcdcdc;
		}
		p{
			font-size: 20px;
			margin-top: -17px; 
		}
	</style>	
	<body>
		<div class="container" style="max-width:100%; margin:0; padding:0" ><br>
			<div class="receipt-main">
				<div class="d-flex">
					<img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/img/logo.jpg'))) }}" style="width: 100px;"/>
					<b style="margin-left: 20px; font-size: 40px;">PDAO CABUYAO CITY</b>
				</div><br><br>
				<div class="row">
					<div class="col-12">
						<h4>Transaction : {{ $appointment->transaction }}</h4>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<h4>Reference Number : 
							@if($appointment->transaction == "Application")
								APL{{ date('j', strtotime($appointment->appointment_date )) }}-0{{$appointment->barangay_id}}-{{$appointment->appointment_id}}0
							@elseif($appointment->transaction == "Renewal ID")
								RNW{{ date('j', strtotime($appointment->appointment_date )) }}-0{{$appointment->barangay_id}}-{{$appointment->appointment_id}}0
							@elseif($appointment->transaction == "Lost ID")
								LST{{ date('j', strtotime($appointment->appointment_date )) }}-0{{$appointment->barangay_id}}-{{$appointment->appointment_id}}0
							@else
								RCL{{ date('j', strtotime($appointment->appointment_date )) }}-0{{$appointment->barangay_id}}-{{$appointment->appointment_id}}0
							@endif							
						</h4>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<h4>Appointment Date : {{ date('F j, Y', strtotime($appointment->appointment_date )) }}</h4>
					</div>
				</div><br>
				<div class="row">
					<ol>
						@if($appointment->transaction == "Application")
							<li>Dapat Cabuyao Resident and Registered voter of Cabuyao</li>
							<li>Voter's ID or Voter's Certification</li>
							<li>Medical Certificate
								<ul>*Dapat nakasaad kung anong uri ng kapansanan</ul>
								<ul>*Remarks ay dapat tukuyin na ang mga aplikante ay kwalipikado para sa PWD ID </ul>
								<ul>*Ang Aplikante na may Visual Disability ay dapat magpakita ng medical certificate mula sa ophthalmologist na nagsasaad ng siya at kwalipikado para sa PWD ID</ul>
							</li>
					
						@elseif($appointment->transaction == "Renewal ID")
							<li>Latest Medical Certificate</li>
							<li>Old PWD ID</li>
						@elseif($appointment->transaction == "Lost ID")
							<li>Affidavit of Loss</li>
						@else
							<li>Your PWD ID</li>
						@endif	
						<li>Authorization Para sa PWD na di sila ang mag proprocess</li>
					</ol>				
				</div>
			</div>
		</div>
	</body>
</html>
