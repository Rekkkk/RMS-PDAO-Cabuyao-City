<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta
			name="viewport"
			content="width=device-width, initial-scale=1, shrink-to-fit=no"
		/>
		<title>New Account</title>
		<link rel="stylesheet" href="{{ public_path('css/bootstrap.min.css') }}">

	<style>
		body,
		* {
			color-adjust: exact !important;
			-webkit-print-color-adjust: exact !important;
			print-color-adjust: exact !important;
		}

		.text-danger strong {
			color: #006fb3;
		}
		.receipt-main {
			background: #ffffff none repeat scroll 0 0;
			border-bottom: 12px solid #333333;
			border-right: 1px solid #ccc;
			border-left: 1px solid #ccc;
			border-top: 12px solid #e90000;

			margin-bottom: 50px;
			/* padding: 40px 30px !important; */
			position: relative;
			color: #333333;
			font-family: open sans;
		}
		.receipt-main p {
			color: #333333;
			font-family: open sans;
			line-height: 1.42857;
		}
		.receipt-footer h1 {
			font-size: 15px;
			font-weight: 400 !important;
			margin: 0 !important;
		}
		.receipt-main::after {
			background: #414143 none repeat scroll 0 0;
			content: '';
			height: 5px;
			left: 0;
			position: absolute;
			right: 0;
			top: -13px;
		}
		.receipt-main thead {
			background: #414143 none repeat scroll 0 0;
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
			padding: 9px 20px !important;
		}
		.receipt-main th {
			padding: 13px 20px !important;
		}
		.receipt-main td {
			font-size: 14px;
			font-weight: initial !important;
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
		.receipt-header-mid .receipt-left h1 {
			font-weight: 100;
			margin: 34px 0 0;
			text-align: right;
			text-transform: uppercase;
		}
		.receipt-header-mid {
			margin: 24px 0;
			overflow: hidden;
		}

		#container {
			background-color: #dcdcdc;
		}

		@media print {
			.receipt-main thead th {
				color: #333;
			}
		}

    </style>
	</head>
	<body>
		<div class="receipt-main col-xs-10 col-sm-10 col-md-6 m-auto">
			<div class="d-flex mt-4">
				<img
				src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/img/logo.jpg'))) }}"
				style="width: 100px;"
			/>
			<b style="margin-left: 18px; font-size: 40px;">PDAO CABUYAO CITY</b>
			</div>
			<br/>
					<div class="row">
						<div class="col-12">
							<div class="text-center">
								<h2>New Account</h2>
							</div>
						</div>
					</div><br>
					<br />
					<div>
						<div class="row">
							<div class="col text-left">
								<div class="receipt-right" >				
										<p style="font-size: 20px;">
											Position/Barangay Handle : 
											@if($user->user_role == 0)
												PDAO Staff
											@elseif($user->user_role == 1)	
												{{$user->barangay->barangay_name}}
											@else
												PDAO Officer In Charge
											@endif
										</p>
								</div>
							</div>
						</div><br>
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Email</th>
									<th>Password</th>
								</tr>
							</thead>
							<tbody>
								<tr	>
									<td style="font-size: 20px;">{{$user->email}}</td>
									<td style="font-size: 20px;"><i class="fa fa-inr" ></i>{{$user->temp_password}}</td>					
								</tr>
							</tbody>
						</table>
					</div><br>
					<div style="margin-top: 320px" class="row">
						<div class="col-xs-8 col-sm-8 col-md-8 text-left">
							<div class="receipt-right">
								<p><b>Date Created :</b> {{ date('F d, Y') }}</p>
							</div>
						</div>
					</div>
				</div>
	</body>
</html>