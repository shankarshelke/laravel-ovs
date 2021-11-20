@extends('admin.layout.master')    
@section('main_content')
<!--body wrapper start-->
<div class="wrapper">
	<div class="row">
		<div class="col-md-12">
			@include('admin.layout.breadcrumb')
			<script type="text/javascript" src="{{ url('/') }}/assets/admin_assets/js/bootstrap-datepicker.min.js"></script>
			<link rel="stylesheet" href="{{ url('/') }}/assets/admin_assets/css/bootstrap-datepicker3.css" />  
			<section class="panel">				
				<div class="panel-body">
					<section class="panel">
					@include('admin.layout._operation_status')
					<div class="form-group">
					<label class="col-sm-2 col-sm-2 control-label">Select Form</label> 
					<p align="center">
						<form name="jump" class="center">
							<select name="menu" onchange="gotoPage(this)">
							<option value="{{ url('/') }}/admin/voters/create">Voter Registration</option>
							<option value="{{ url('/') }}/admin/voters/aadhar">Aadhar Card</option>
							<option value="{{ url('/') }}/admin/voters/voter">Voter Id</option>
							<option value="{{ url('/') }}/admin/voters/aadhar_voter">Voter Id & Aadhar Card</option>
							</select>
							<br><br>
							<div class="form-group">
								<label class="col-sm-2 col-sm-2 control-label"></label> 
								<input type="button" onClick="location=document.jump.menu.options[document.jump.menu.selectedIndex].value;" value="GO">
							</div>
						</form>
					</p>
				</div>
			</section>
				</div>
			</section>
		</div>
	</div>
</div>
<!--body wrapper end-->

<script src="{{url('/')}}/assets/admin_assets/js/bootstrap-inputmask.min.js"></script>

<script type="text/javascript">
	$(document).ready(function(){
		$('#frm_blogs_page').validate();
		});
</script>

<!-- Script for Image validation -->

@endsection


