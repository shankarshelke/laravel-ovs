
@extends('admin.layout.master')    
@section('main_content')
<!--body wrapper start-->
<div class="wrapper">
	<div class="row">
		<div class="col-md-12">
			@include('admin.layout.breadcrumb')  
			<section class="panel">
				<header class="panel-heading">
					{{$sub_module_title or ''}}
				</header>

				<div class="panel-body">
					@include('admin.layout._operation_status') 
					<form action="{{$module_url_path}}/update/{{ $enc_id or '' }}" id="frm_blogs_page" name="frm_blogs_page" class="form-horizontal cmxform" method="post" enctype="multipart/form-data">
						{{csrf_field()}}
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Ward<i style="color:red;">*</i></label>
							<div class="col-sm-3">
								<input type="text" id="booth_no" name="booth_no"  readonly="" data-rule-required="true" {{-- data-rule-number=”true” --}}  class="form-control" value="({{$arr_data['get_ward_details']['ward_no']}}) {{$arr_data['get_ward_details']['ward_name']}}" >
								<span class="error">{{ $errors->first('ward') }} </span>
							</div>

						</div>
				
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Booth No<i style="color:red;">*</i></label>
							<div class="col-sm-3">
								<input type="text" id="booth_no" name="booth_no"  data-rule-required="true" data-rule-number=”true”  class="form-control" value="{{ $arr_data['booth_no'] or 'NA' }}" >
								<span class="error">{{ $errors->first('booth_no') }} </span>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Booth Name<i style="color:red;">*</i></label>
							<div class="col-sm-3">
								<input type="text" id="booth_name"  name="booth_name"  data-rule-required="true"  class="form-control "value="{{ $arr_data['booth_name'] or 'NA' }}">
								<span class="error">{{ $errors->first('booth_name') }} </span>
							</div>
						</div>					

						<div class="form-group">
							<div class="col-sm-5 text-right">
								<a href="{{ $module_url_path or 'NA' }}" class="btn btn-primary">Back</a>
								<button class="btn btn-primary" type="submit"  id="btn_add_front_page">Update</button>
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



	


