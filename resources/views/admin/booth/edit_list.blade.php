
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
					<form action="{{$module_url_path}}/update_list/{{ $enc_id or '' }}" id="frm_edit_list_page" name="frm_edit_list_page" class="form-horizontal cmxform" method="post" enctype="multipart/form-data">
						{{csrf_field()}}
						{{-- {{dd($arr_data)}} --}}
				
					<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Booth Detail<i style="color:red;">*</i></label>
							<div class="col-sm-6">
								<input type="text" id="booth_no" name="booth_no"  readonly="" data-rule-required="true" {{-- data-rule-number=”true” --}}  class="form-control" value="({{$arr_data['get_booth_details']['booth_no']}}) {{$arr_data['get_booth_details']['booth_name']}}" >
								<span class="error">{{ $errors->first('booth_no') }} </span>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">List No<i style="color:red;">*</i></label>
							<div class="col-sm-2">
								<input type="text" id="list_no"  name="list_no"  data-rule-required="true"  class="form-control "value="{{ $arr_data['list_no'] or 'NA' }}">
								<span class="error">{{ $errors->first('list_no') }} </span>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">List Name<i style="color:red;">*</i></label>
							<div class="col-sm-2">
								<input type="text" id="list_name"  name="list_name"  data-rule-required="true"  class="form-control "value="{{ $arr_data['list_name'] or 'NA' }}">
								<span class="error">{{ $errors->first('list_name') }} </span>
							</div>
						</div>
					
					

						<div class="form-group">
							<div class="col-sm-8 text-right">
								<a href="{{ $module_url_path or 'NA' }}/manage_list" class="btn btn-primary">Back</a>
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
		$('#frm_edit_list_page').validate();
		});
</script>

<!-- Script for Image validation -->

@endsection



	


