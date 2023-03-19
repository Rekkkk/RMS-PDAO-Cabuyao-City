<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>PWD Details</title>
	<link href="{{ public_path('/css/style.css') }}" rel="stylesheet">

	<link rel="stylesheet" href="{{ public_path('css/bootstrap.min.css') }}">

</head>
<style>
	label{
		font-size: 14px;
		margin-left: 45px;
	}
</style>
<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<h4><b>Personal Information</b></h4>
				<h5>PWD ID : {{ $pwd->pwd_number }}</h5>
			</div>
		</div><hr>
		<table style="width:100%">
			<tr>
				<td>
					<label><b>Last Name :</b> {{ $pwd->last_name}}</label>
				</td>
				<td>
					<label><b>First Name :</b> {{ $pwd->first_name}}</label>
				</td>
			</tr>
			<tr>
				<td>
					<label><b>Middle Name :</b> {{($pwd->middle_name == null)? "None" : $pwd->middle_name}}</label>
				</td>
				<td>
					<label><b>Suffix :</b> {{ ($pwd->suffix == null)? "None" : $pwd->suffix}}</label>
					
				</td>
			</tr>
			<tr>
				<td>
					<label><b>Sex :</b> {{ $pwd->sex}} </label>
				</td>
				<td>
					<label><b>Age : </b> {{ $pwd->age }} </label>
				</td>
			</tr>
			<tr>
				<td>
					<label><b>Date of Birth : </b> {{date('F d, Y', strtotime($pwd->birthday))}} </label>
				</td>
				<td>
					<label><b>Religion : </b> {{ $pwd->religion}} </label>
				</td>
			</tr>
			<tr>
				<td>
					<label><b>Ethnic Group :</b> {{ ($pwd->ethnic_group == null)? "None" : $pwd->ethnic_group}} </label>
				</td>
				<td>
					<label><b>Blood Type : </b> {{ $pwd->blood_type}} </label>
				</td>
			</tr>
			<tr>
				<td>
					<label><b>Type of Disability : </b> {{ $pwd->disability_type}} </label>
				</td>
				<td>
					<label><b>Cause of Disablity :</b> {{ $pwd->disability_cause}} </label>
				</td>
			</tr>
			<tr>
				<td>
					<label><b>Disablity Name : </b> {{ $pwd->disability_name}} </label>
					
				</td>
				<td>
					<label><b>Address : </b> {{ $pwd->address}} </label>
				</td>
			</tr>
			<tr>
				<td>
					<label><b>Barangay : </b> {{ $pwd->barangay->barangay_name}} </label>
				</td>
				<td>
					<label><b>City : </b> Cabuyao City</label>
					
				</td>
			</tr>
			<tr>
				<td>
					<label><b>Province  :</b> Laguna </label>
				</td>
				<td>
					<label><b>Phone No. : </b> {{ $pwd->phone_number}} </label>
				</td>
			</tr>
			<tr>
				<td>
					<label><b>Telephone No. : </b> {{ ($pwd->telephone_number == null)? "None" : $pwd->telephone_number}} </label>
					
				</td>
				<td>
					<label><b>Email Address : </b> {{ ($pwd->email == null)? "None" :  $pwd->email}} </label>
				</td>
			</tr>
			<tr>
				<td>
					<label><b>Educational Attaintment : </b> {{ $pwd->educational_attainment}} </label>
				</td>
				<td>
					<label><b>Status of Employment : </b> {{ $pwd->employment_status}} </label>
					
				</td>
			</tr>
			<tr>
				<td>
					<label><b>Category of Employment :  </b> {{ ($pwd->employment_category == null)? "None" :  $pwd->employment_category}} </label>
				</td>
				<td>
					<label><b>Type of Employment : </b>{{ ($pwd->employment_type == null)? "None" :  $pwd->employment_type}}</label>
				</td>
			</tr>
			<tr>
				<td>
					<label><b>Occupation :</b> {{ ($pwd->occupation == null)? "None" :  $pwd->occupation}} </label>
				</td>
			</tr>

		</table><br>
		<div class="row">
			<div class="col-sm-12">
				<h4><b>Organization Information</b></h4>
			</div>
		</div><hr>
		<table style="width:100%">
			<tr>
				<td>
					<label><b>Organization Affiliated : </b> {{ ($pwd->organization_affliated == null)? "None" :  $pwd->organization_affliated}} </label>
				</td>
				<td>
					<label><b>Contact Person :  </b> {{ ($pwd->organization_contact_person == null)? "None" :  $pwd->organization_contact_person}} </label>
				</td>
			</tr>
			<tr>
				<td>
					<label><b>Office Address : </b> {{ ($pwd->organization_office_address == null)? "None" :  $pwd->organization_office_address}} </label>
				</td>
				<td>
					<label><b>Tel. No. :  </b> {{ ($pwd->organization_telephone_number == null)? "None" :  $pwd->organization_telephone_number}} </label>
				</td>
			</tr>
			
		</table><br>
		<div class="row">
			<div class="col-sm-12">
				<h4><b>ID Reference No.</b></h4>
			</div>
		</div><hr>
		<table style="width:100%">
			<tr>
				<td>
					<label><b>SSS No. : </b> {{ ($pwd->sss_number == null)? "None" :  $pwd->sss_number}} </label>
				</td>
				<td>
					<label><b>GSIS No. :  </b> {{ ($pwd->gsis_number == null)? "None" :  $pwd->gsis_number}} </label>
				</td>
			</tr>
			<tr>
				<td>
					<label><b>Pag-ibig No. : </b> {{ ($pwd->pagibig_number == null)? "None" :  $pwd->pagibig_number}} </label>
				</td>
				<td>
					<label><b>PhilHealth No. :  </b> {{ ($pwd->philhealth_number == null)? "None" :  $pwd->philhealth_number}}</label>
				</td>
			</tr>
		</table><br>
		<div class="row">
			<div class="col-sm-12">
				<h4><b>Family Background</b></h4>
			</div>
		</div><hr>
		<table style="width:100%">
			<tr>
				<td>
					<label style="margin-left: 0px;"><b>Father's Name :  </b> {{ $pwd->father_first_name. " " . $pwd->father_middle_name ." ". $pwd->father_last_name }}</label>
				</td>
				<td>
					<label style="margin-left: 0px;"><b>Mother's Name  :   </b> {{ $pwd->father_first_name. " " . $pwd->father_middle_name ." ". $pwd->father_last_name }}</label>
				</td>
			</tr>
			<tr>
				<td>
					<label style="margin-left: 0px;"><b>Guardian's Name  :  </b> {{ ($pwd->guardian_last_name == null)? "None" :  $pwd->guardian_last_name. ", " . $pwd->guardian_first_name ." ". $$pwd->guardian_middle_name }}</label>
				</td>
			</tr>
		</table>

	</div>
	
</body>
</html>
