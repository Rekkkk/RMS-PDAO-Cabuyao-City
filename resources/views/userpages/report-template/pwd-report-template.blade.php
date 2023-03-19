<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<title>Program PWD Reports</title>
		<script src="{{ asset('/js/jquery/jquery_3.6.0.js') }}"></script>
		<link rel="stylesheet" href="{{ public_path('css/bootstrap.min.css') }}">
	<style>
		*{
			font-family: open sans;
		}
		#financialAllBarangay {
			border-collapse: collapse;
			width: 60%;
			margin: auto;
		}
		.footer {
			position: fixed;
			left: 0;
			bottom: 0;
			width: 100%;
			text-align: center;
		}
		.page-break {
            page-break-after: always;
        }
		li{
			margin-bottom: -10px;
		}
		.li-value{
			font-size: 17px;
		}
	</style>
	</head>
	<body>
		<div class="container" style="max-width:660px; margin:0; padding:0" >
			<div class="receipt-main">
				<div class="d-flex">
					<img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/img/logo.jpg'))) }}" style="width: 100px;">
					<b style="margin-left: 18px; font-size: 45px;">PDAO CABUYAO CITY</b>
					<h3 class="text-center mt-2">PWD Population Report</h3>
				</div>
				<div class="row mt-4">
					<p style="font-size: 18px; margin-left: 455px; "><b>Date Created :</b>{{ date(' F d, Y') }}</p>
				</div>
				<div class="row mt-1">
					<div class="col m-auto">
						<img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/yealyChart.png'))) }}" width="700" height="350">
					</div>
				</div>
				<div class="row mt-5">
					<div class="col-12 ml-4">
						<p style="font-size: 18px;"><b>Barangay : </b>{{ ($barangay->barangay_id == 1)? "All Barangays" :  $barangay->barangay_name }}</p>
						<p style="font-size: 18px; margin-top: -15px;"><b>Time Frame :</b> <span>{{$category}}</span></p>
						<p style="font-size: 17px; line-height: 1.70; ">
							The bar graph shows the population status of the registered persons with disabilities in Cabuyao City in {{ ($barangay->barangay_id == 1)? "all Barangays" :  "Brgy. ".$barangay->barangay_name }}.  
							The increasing rate is around {{$newPwdPercent}}% over {{$category}}. {{round(($added_age / $addedPwd->count())* 100, 2)}}% ({{$added_age}}) of minorities and
							{{round(100 - (($added_age / $addedPwd->count())* 100), 2)}}% ({{$addedPwd->count()-$added_age}}) Adults, the majority are
							@if((100 - (($added_sex / $addedPwd->count())* 100)) > ($added_sex / $addedPwd->count())* 100  )
								female,
							@elseif((100 - (($added_sex / $addedPwd->count())* 100)) < ($added_sex / $addedPwd->count())* 100)
								male,
							@endif 
							and most of the added registered persons with disabilities have a
							{{$disabilitType[1] . " (" . $disabilitType[0] ."% over ". $addedPwd->count().")"}}.
						</p>
					</div>
				</div>
				<div class="row mt-5 footer">
					<div class="col-lg-12 text-center mt-5">
						<h6 >{{Auth::user()->first_name ." " . Auth::user()->middle_name. " ". Auth::user()->last_name}}</h6>
					</div>
					<p class="text-center ml-4" style="margin-top: -10px"> Signature</p>

				</div>
			</div>
			<style>
				.list{
					border:1px solid black;
				}
			</style>
			<div class="page-break"></div>
			<div class="row">
				<div class="col-lg-12">
						<h3 class="text-center">List Of New PWD</h3>	
				</div>
			</div><br>
			<table style="width: 100%">
				<tr>
					<th class="list">PWD ID</td>
					<th class="list">Name</th>
					@if($barangay->barangay_id == 1)
						<th class="list">Barangay</th>
					@endif
				</tr>
				@foreach($addedPwd as $pwd)
					<tr>
						<td class="list">04-000-{{ $pwd->pwd_id }}</td>
						<td class="list">{{ $pwd->last_name.", ".  $pwd->first_name." ".$pwd->middle_name}}</td>
						@if($barangay->barangay_id == 1)
							<td class="list">{{ $pwd->barangay->barangay_name }}</td>
						@endif
					</tr>
				
				@endforeach
			</table>
				<div class="page-break"></div>
				<div class="row">
					<div class="col-lg-12">
							<h3 class="text-center">List Of Deducted PWD</h3>	
					</div>
				</div><br>
				<table style="width: 100%">
					<tr>
						<th class="list">PWD ID</td>
						<th class="list">Name</th>
						@if($barangay->barangay_id == 1)
							<th class="list">Barangay</th>
						@endif
					</tr>
					@foreach($deductedPwd as $pwd)
						<tr>
							<td class="list">04-000-{{ $pwd->pwd_id }}</td>
							<td class="list">{{ $pwd->last_name.", ".  $pwd->first_name." ".$pwd->middle_name}}</td>
							@if($barangay->barangay_id == 1)
								<td class="list">{{ $pwd->barangay->barangay_name }}</td>
							@endif
						</tr>
					@endforeach
				</table>
		</div>	
	</body>
</html>
