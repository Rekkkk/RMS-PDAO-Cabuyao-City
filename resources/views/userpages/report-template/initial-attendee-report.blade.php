<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<title>Program Expeected Attendees Reports</title>
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
	</style>
	</head>
	<body>
		<div class="container" style="max-width:100%; margin:0; padding:0" >
			<div class="receipt-main">
				<div class="d-flex">
					<img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/img/logo.jpg'))) }}" style="width: 100px;">
					<b style="margin-left: 18px; font-size: 45px;">PDAO CABUYAO CITY</b>
				</div><br>
				<div class="row">
					<div class="col-lg-12">
						
							<h2 class="text-center">Program Expected Attendees Report</h2>	
					
					</div>
				</div><br>
				<p style="font-size: 20px;"><b>Program Title : </b>{{ $program->program_title }} <br>
					<b>Barangay : </b>{{ ($barangays->count() > 2)? "All Barangays" :  $barangays->first()->barangay_name }}<br>
					<b>Date Created :</b>{{ date(' F d, Y') }}
				</p>
				@if($barangays->count() > 2)
                    <div class="row mt-3">
                        <div class="col">
                            <table id="financialAllBarangay">
                                <tr style="border-bottom: 1px solid; padding-bottom: 30px;">
                                    <td><b>Barangay</b></td>
                                    <td class="text-center"><b>Beneficiaries</b></td>
                                </tr>
            
                                @foreach($barangays as $key => $barangay)
                                    @if($key > 0)
                                        <tr>
                                            <td>{{ $barangay->barangay_name }}</td>
                                            <td class="text-center">{{ $beneficiaryList->where('barangay_id' , $barangay->barangay_id)->count() }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                <tr style="border-top: 1px solid; padding-bottom: 30px;">
                                    <td><b>Total</b></td>
                                    <td class="text-center"><b>{{ $beneficiaryList->count() }}</b></td>
                                </tr>
                
                            </table>
                        </div>
                    </div>
				@else
					<div class="row mt-5">
						<style>
			
						#financialBarangay {
							border-collapse: collapse;
							width: 80%;
							margin: auto;
							font-size: 20px;
						}
						</style>
						<div class="col mt-5">
							<table id="financialBarangay">
								<tr style="border-bottom: 1px solid; padding-bottom: 30px;">
									<td style="border: 1px solid #dddddd"></td>
									<td style="border: 1px solid #dddddd" class="text-center"><b>Beneficaries</b></td>
								</tr>
								<tr>
									<td style="border: 1px solid #dddddd">{{ $barangays->first()->barangay_name }}</td>
									<td style="border: 1px solid #dddddd" class="text-center">{{ $beneficiaryList->count() }}</td>
								</tr>
								<tr>
									<td style="border: 1px solid #dddddd">No. of Beneficiaries </td>
									<td style="border: 1px solid #dddddd" class="text-center">{{ $beneficiaryList->count() }}</td>
									
									
								</tr>
								<tr style="border-top: 1px solid;">	
									<td style="border: 1px solid #dddddd"><b>Total</b></td>
									<td style="border: 1px solid #dddddd" class="text-center"><b>{{ $beneficiaryList->count() }}</b></td>
								</tr>
							</table>
						</div>
					</div>
				@endif
				<div class="row mt-5 footer">
					<div class="col-lg-12 text-center mt-5">
						<h6 >{{Auth::user()->first_name ." " . Auth::user()->middle_name. " ". Auth::user()->last_name}}</h6>
					</div>
					<p class="text-center ml-4" style="margin-top: -10px"> 
						@if(Auth::user()->user_role == 2)
                        	PDAO Officer in Charge
						@else
							PDAO Brgy. {{Auth::user()->barangay->barangay_name}} President
						@endif
					</p>
				</div>
			</div>
			<div class="page-break"></div>
			<style>
				.list{
					border:1px solid black;
				}
			</style>
			<div class="row">
				<div class="col-lg-12">
						<h3 class="text-center">List Of PWD's</h3>	
				</div>
			</div><br>
			<table style="width: 100%">
				<tr>
					<th class="list">PWD ID</td>
					<th class="list">Name</th>
					<th class="list">Barangay</th>
				</tr>
				@foreach($beneficiaryList as $beneficiary)
				<tr>
					<td class="list">04-000-{{ $beneficiary->pwd_id }}</td>
					<td class="list">{{ $beneficiary->last_name.", ".  $beneficiary->first_name}}</td>
					<td class="list">{{ $beneficiary->barangay->barangay_name }}</td>
				</tr>
				@endforeach
			</table>
		</div>	
	</body>
</html>

