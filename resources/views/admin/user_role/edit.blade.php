
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
				<header class="panel-heading">
					{{$sub_module_title or ''}}
				</header>
				
				<div class="panel-body">
					@include('admin.layout._operation_status') 
					<form action="{{$module_url_path}}/update/{{base64_encode( $arr_data['id'])}}" id="frm_role_page" name="frm_role_page" class="form-horizontal cmxform" method="post">
						{{csrf_field()}}
						<div class="row">
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label class="control-label col-sm-4 col-md-4 col-lg-3" for="role">Role<i class="red">*</i></label>
                                    <div class="col-sm-8 col-md-8 col-lg-9">
                                        <input type="text" id="role"  name="role"  data-rule-required="true"  class="form-control " value="{{ $arr_data['role'] }}" >
                                        <span class="error" style="color: red;">{{ $errors->first('role') }} </span>
                                        {{--<textarea  name="role" id="role" class="form-control" placeholder="role" data-rule-required="true"  data-rule-maxlength="500" >{{ $arr_data['role'] }}</textarea>--}}
                                        <span class="error">{{ $errors->first('role') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label class="control-label col-sm-4 col-md-4 col-lg-3" for="description">Description<i class="red">*</i></label>
                                    <div class="col-sm-8 col-md-8 col-lg-9">
                                        <input type="text" id="description"  name="description"  data-rule-required="true"  class="form-control " value="{{ $arr_data['description'] }}" >
                                        {{-- <textarea  name="description" id="description" class="form-control" placeholder="description" data-rule-required="true"  data-rule-maxlength="500" >{{ $arr_data['description'] }}</textarea> --}}
                                        <span class="error">{{ $errors->first('description') }} </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-right">
                            <div class="col-lg-8">
                                <button type="submit" class="btn btn-primary">Update</button>
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
	  $('#frm_role_page').validate();
					});

</script> 	        



@endsection



