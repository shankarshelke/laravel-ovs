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
					@include('admin.layout._operation_status') 
					<form action="{{$module_url_path}}/store" id="frm_blogs_page" name="frm_blogs_page" class="cmxform" method="post">
						{{csrf_field()}}
						<div class="form-group">
							<label class="control-label">Role.<i style="color:red;">*</i></label>
							<div class="input-group">
                                <span class="input-group-addon"><i class="ti-user"></i></span>
								<input type="text" id="role" name="role"  data-rule-required="true" 
								 class="form-control" >
								<span class="error" style="color: red;">{{ $errors->first('role') }} </span>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label">Description<i style="color:red;">*</i></label>
							<div class="input-group">
                                <span class="input-group-addon"><i class="ti-user"></i></span>
								<input type="text" id="description"  name="description"  data-rule-required="true"  class="form-control "  >
								<span class="error" style="color: red;">{{ $errors->first('description') }} </span>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-8 text-right">
								<a href="{{ $module_url_path or 'NA' }}" class="btn btn-primary">Back</a>
								<button class="btn btn-primary" type="submit"  id="btn_add_front_page">Create</button>
							</div>
						</div>
					</form>
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


