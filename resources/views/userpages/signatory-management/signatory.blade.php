@extends('userpages.sidebar')

@section('content')
    <div class="container-fluid px-4">
		<h1><b>Signatory Management</b></h1><br>
		<ul class="nav nav-tabs" role="tablist">
			<li class="nav-item">
				@if($activePage == "ID")
					<a class="nav-link active" data-toggle="tab" href="#home">PWD ID</a>
				@else
					<a class="nav-link" data-toggle="tab" href="#home">PWD ID</a>
				@endif
			</li>
			<li class="nav-item">
				@if($activePage == "Cancelation")
					<a class="nav-link active" data-toggle="tab" href="#menu1">Cancelation</a>
				@else
					<a class="nav-link" data-toggle="tab" href="#menu1">Cancelation</a>
				@endif
			</li>
		</ul>
		<div class="tab-content">
			@if($activePage == "ID")
				<div id="home" class="container tab-pane active">
			@else
				<div id="home" class="container tab-pane fade">
			@endif
				<div class="row">
					<div class="col-12">
						<div class="text-center">
							<h2 class="mb-4"><b>Current PWD ID Signatory </b></h2>
							<img src="{{asset('/signatory/'.$id->img_file)}}" style="width: 300px; height: 150px;" class="mb-4">
							<h6 class="text-danger">* Note Upload : Signatory must be transparent and .png image extension</h6>
							
						</div>
					</div>
					<form method="POST" action="{{ route('upload.id.signatory') }}" class="w-50 m-auto" enctype="multipart/form-data">
						@csrf
						<div class="row">
							<div class="col-12">
								<label class="title-detail">Upload Signatory</label>
								<div class="custom-file">
									<input type="file" class="custom-file-input" id="customFile" name="signature" accept="image/png" required >
									<label class="custom-file-label" for="customFile">Select Picture</label>		
								</div>
							</div>
							
						</div>
						<div class="row">
							<div class="col-12 text-center">
								<input type="submit" value="Upload" class="btn btn-success buttons mt-3">
							</div>
						</div>
					</form>
				</div>
			</div>
			@if($activePage == "Cancelation")
			<div id="menu1" class="container tab-pane active">
			@else
			<div id="menu1" class="container tab-pane fade">
			@endif
				<div class="row">
					<div class="col-12">
						<div class="text-center">
							<h2 class="mb-4"><b>Current Cancelation Letter Signatory</b></h2>
							<img src="{{asset('/signatory/'.$cancel->img_file)}}" style="width: 300px; height: 150px;" class="mb-4">
							<h6 class="text-danger">* Note Upload : Signatory must be transparent and .png image extension</h6>
							
						</div>
					</div>
					<form method="POST" action="{{ route('upload.cancelation.signatory') }}" class="w-50 m-auto" enctype="multipart/form-data">
						@csrf
						<div class="row">
							<div class="col-12">
								<label class="title-detail">Upload Signatory</label>
								<div class="custom-file">
									<input type="file" class="custom-file-input" id="customFile" name="signature" accept="image/png" required >
									<label class="custom-file-label" for="customFile">Select Picture</label>		
								</div>
							</div>
							
						</div>
						<div class="row">
							<div class="col-12 text-center">
								<input type="submit" value="Upload" class="btn btn-success buttons mt-3">
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		</div>
	</div>
	<script>
		$(document).ready(function () {
			$(".custom-file-input").on("change", function() {
				var files = Array.from(this.files)
				var fileName = files.map(f =>{return f.name}).join(", ")
				$(this).siblings(".custom-file-label").addClass("selected").html(fileName);
			});
		});
	</script>
@endsection



