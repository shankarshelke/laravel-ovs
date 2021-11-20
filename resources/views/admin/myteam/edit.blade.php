
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
					<form action="{{$module_url_path}}/update/{{base64_encode( $arr_data['id'])}}" id="frm_myteam_page" name="frm_myteam_page" class="form-horizontal cmxform" method="post">
						{{csrf_field()}}
					
								{{-- div class="row">
										<div class="col-lg-8">
											<div class="form-group">
												<label class="control-label col-sm-4 col-md-4 col-lg-3" for="user_name">Username<i class="red">*</i></label>
												<div class="col-sm-8 col-md-8 col-lg-9">
													<input type="text" value="{{old('user_name')}}" name="user_name" id="user_name" class="form-control" placeholder="Username" data-rule-required="true" data-rule-pattern="^[a-z A-Z]+$" data-msg-pattern="Alphabetics only" data-rule-maxlength="40" value="">
													<span class="error">{{ $errors->first('user_name') }} </span>
												</div>
											</div>
										</div>
									</div> --}}
						              <div class="row">
										<div class="col-lg-8">
											<div class="form-group">
												<label class="control-label col-sm-4 col-md-4 col-lg-3" for="first_name">First Name<i class="red">*</i></label>
												<div class="col-sm-8 col-md-8 col-lg-9">
													<input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" data-rule-required="true" data-rule-pattern="^[a-z A-Z]+$" data-msg-pattern="Alphabetics only" data-rule-maxlength="40" value="{{ $arr_data['first_name'] }}">
													<span class="error">{{ $errors->first('first_name') }} </span>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-8">
											<div class="form-group">
												<label class="control-label col-sm-4 col-md-4 col-lg-3" for="last_name">Last Name<i class="red">*</i></label>
												<div class="col-sm-8 col-md-8 col-lg-9">
													<input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" data-rule-required="true" data-rule-pattern="^[a-z A-Z]+$" data-msg-pattern="Alphabetics only" data-rule-maxlength="40" value="{{ $arr_data['last_name'] }}">
													<span class="error">{{ $errors->first('last_name') }} </span>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-8">
											<div class="form-group">
												<label class="control-label col-sm-4 col-md-4 col-lg-3" for="role">Select Role<i class="red">*</i></label>
													<div class="col-sm-8 col-md-8 col-lg-9">
														<select name="role" data-rule-required="true" id="role"  class="form-control ">
				                                        	{{-- <option value="{{old('role')}}" > </option> --}}
											 				@if(isset($arr_roles) && count($arr_roles)>0)
				                                        		@foreach($arr_roles as $roles)
				                                        		<option value="{{$roles['role']}}" @if($arr_data['role'] == $roles['role']) selected="selected" @endif>{{$roles['role'] or ''}}</option>
				                                        		@endforeach
				                                        	@endif
		                              					</select>
		                              				</div>	
											</div>
										</div>
									</div>
									<input type="hidden"  name="admin_type" id="admin_type" class="form-control" value="SUBADMIN">
									<div class="row">
										<div class="col-lg-8">
											<div class="form-group">
												<label class="control-label col-sm-4 col-md-4 col-lg-3" for="contact">Contact Number<i class="red">*</i></label>
												<div class="col-sm-8 col-md-8 col-lg-9">
													<input type="text" name="contact" id="contact" class="form-control" placeholder="Contact Number" data-rule-required="true" data-rule-pattern="[- +()0-9]+" data-rule-minlength="7" data-rule-maxlength="16" data-msg-minlength="Contact no should be atleast 7 numbers" data-msg-maxlength="Contact no should not be more than 16 numbers" value="{{ $arr_data['contact'] }}">
													<span class="error">{{ $errors->first('contact') }} </span>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-8">
											<div class="form-group">
												<label class="control-label col-sm-4 col-md-4 col-lg-3" for="email">Email<i class="red">*</i></label>
												<div class="col-sm-8 col-md-8 col-lg-9">
													<input type="email" value="{{ $arr_data['email'] }}" name="email" id="email" class="form-control" placeholder="Email" data-rule-required="true" >
													<span class="error">{{ $errors->first('email') }} </span>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-8">
											<div class="form-group">
												<label class="control-label col-sm-4 col-md-4 col-lg-3" for="address">Address<i class="red">*</i></label>
												<div class="col-sm-8 col-md-8 col-lg-9">
													<textarea  name="address" id="address" class="form-control" placeholder="Address" data-rule-required="true"  data-rule-maxlength="500" >{{ $arr_data['address'] }}</textarea>
													<span class="error">{{ $errors->first('address') }} </span>
												</div>
											</div>
										</div>
										{{-- <div class="col-lg-8">
											<div class="form-group">
												<label class="control-label col-sm-4 col-md-4 col-lg-3" for="address">Address<i class="red">*</i></label>
												<div class="col-sm-8 col-md-8 col-lg-9">
													<textarea  name="address" id="site_address" class="form-control" placeholder="Address" data-rule-required="true"  data-rule-maxlength="500">{{old('address')}}</textarea>
													<span class="error" style = "color:red;">{{ $errors->first('address') }} </span>
												</div>
											</div>
										</div>
									</div> --}}
									
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
	  $('#frm_myteam_page').validate();
					});

</script> 	        



@endsection



