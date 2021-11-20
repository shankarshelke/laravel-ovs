
@extends('admin.layout.master')    
@section('main_content')
<!--body wrapper start-->
<div class="wrapper">
	<div class="row">
		<div class="col-md-12">
			@include('admin.layout.breadcrumb')  
			<section class="panel">
				{{-- <header class="panel-heading">
					{{$sub_module_title or ''}}
				</header> --}}

				<div class="panel-body">
					@include('admin.layout._operation_status') 
					<form action="{{$module_url_path}}/update/{{ $enc_id or '' }}" id="frm_blogs_page" name="frm_blogs_page" class="form-horizontal cmxform" method="post" enctype="multipart/form-data">
						{{csrf_field()}}				
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Wards No.<i style="color:red;">*</i></label>
							<div class="col-sm-3">
								<input type="text" id="ward_no" name="ward_no"  data-rule-required="true" data-rule-number=”true”  class="form-control" value="{{ $arr_data['ward_no'] or 'NA' }}" >
								<span class="error">{{ $errors->first('ward_no') }} </span>
							</div>
						</div>					
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label"> Wards Name<i style="color:red;">*</i></label>
							<div class="col-sm-3">
								<input type="text" id="ward_name"  name="ward_name"  data-rule-required="true"  class="form-control "value="{{ $arr_data['ward_name'] or 'NA' }}">
								<span class="error">{{ $errors->first('ward_name') }} </span>
							</div>
						</div>						
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Address<i style="color:red;">*</i></label>
							<div class="col-sm-3">
								<input id="ward_address"  name="ward_address"  data-rule-required="true"  class="form-control" rows="4" value="{{ $arr_data['ward_address'] or 'NA' }}">
								<span class="error">{{ $errors->first('ward_address') }} </span>
							</div>
							
						</div>
						<div class="form-group">
							<div class="col-sm-4 text-right">
								<button class="btn btn-danger" type="submit"  id="btn_add_front_page">Update</button>
								<a href="{{ $module_url_path or 'NA' }}" class="btn btn-primary">Back</a>	
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
@endsection



	


