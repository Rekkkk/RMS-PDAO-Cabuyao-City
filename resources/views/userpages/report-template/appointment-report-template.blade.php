<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<title>Transaction Reports</title>
		<link rel="stylesheet" href="{{ public_path('css/bootstrap.min.css') }}">
	<style>
		*{
			font-family: open sans;
		}
		.list{
			border:1px solid rgb(0, 0, 0);
		}
		table{
			width: 100%;
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
				</div>
				<div class="row">
					<div class="col text-center">
						<h3>Office transaction Reports</h3>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<p style="font-size: 18px; margin-left: 480px;"><b>Date Created :</b>{{ date(' F d, Y') }}</p>
					</div>
				</div>
				<div class="row">
					<div style="text-align:center;">
						<img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/transaction-graph1.png'))) }}" width="700" height="310">
						<img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/transaction-graph2.png'))) }}" width="700" height="310">
					</div>
				</div>
				<div class="row">
					<div class="col">

					</div>
				</div>
				<div class="row mt-5 footer">
					<div class="col-lg-12 text-center mt-5">
						<h6 >{{Auth::user()->first_name ." " . Auth::user()->middle_name. " ". Auth::user()->last_name}}</h6>
					</div>
					<p class="text-center ml-4" style="margin-top: -10px">PDAO Office Staff</p>
				</div>
			</div>
			<div class="page-break"></div>
			<h2><b>Scheduled Transaction</b></h2><br>
            <table>
                <tr>
                    <th class="list">Name</th>
                    <th class="list">Transaction</th>
                    <th class="list">Transaction Date</th>
                </tr>
                @foreach($schedules as $appointments)
					<tr>
						<td class="list">
                            @if($appointments->transaction == "Application")
                                {{ $appointments->applicant->last_name . ", ".  $appointments->applicant->first_name . " ".  $appointments->applicant->middle_name}}
                            @elseif($appointments->transaction == "Renewal ID")
                                {{ $appointments->renewal->pwd->last_name . ", ".  $appointments->renewal->pwd->first_name . " ".  $appointments->renewal->pwd->middle_name}}
                            @elseif($appointments->transaction == "Lost ID")
                                {{ $appointments->lost_id->pwd->last_name . ", ".  $appointments->lost_id->pwd->first_name . " ".  $appointments->lost_id->pwd->middle_name}}
                            @else
                                {{ $appointments->cancellation->pwd->last_name . ", ".  $appointments->cancellation->pwd->first_name . " ".  $appointments->cancellation->pwd->middle_name}}
                            @endif
                        </td>
                        <td class="list">{{$appointments->transaction}}</td>
                        <td class="list">{{date('F d, Y', strtotime($appointments->appointment_date))}}</td>
					</tr>
                    @endforeach
            </table>
			<div class="page-break"></div>
			<h2><b>Walk in Transaction</b></h2><br>
            <table>
                <tr>
                    <th class="list">Name</th>
                    <th class="list">Transaction</th>
                    <th class="list">Transaction Date</th>
                </tr>
                @foreach($walkIns as $walkIn)      
                    <tr>
                        <td class="list">{{$walkIn->pwd->last_name . ", " .$walkIn->pwd->first_name." " .$walkIn->pwd->middle_name}}</td>
                        <td class="list">{{$walkIn->transaction}}</td>
                        <td class="list">{{date('F d, Y', strtotime($walkIn->created_at))}}</td>
                    </tr> 
                 @endforeach
            </table>
		</div>	
	</body>
</html>
