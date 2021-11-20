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
					<form action="{{$module_url_path}}/store" id="frm_myteam_page" name="frm_myteam_page" class="form-horizontal cmxform" method="post">
						{{csrf_field()}}
					
								{{-- <div class="row">
										<div class="col-lg-8">
											<div class="form-group">
												<label class="control-label col-sm-4 col-md-4 col-lg-3" for="user_name">Username<i class="red">*</i></label>
												<div class="col-sm-8 col-md-8 col-lg-9">
													<input type="text" value="{{old('user_name')}}" name="user_name" id="user_name" class="form-control" placeholder="Username" data-rule-required="true" data-rule-pattern="^[a-z A-Z]+$" data-msg-pattern="Alphabetics only" data-rule-maxlength="40" value="">
													<span class="error" style = "color:red;">{{ $errors->first('user_name') }} </span>
												</div>
											</div>
										</div>
									</div> --}}

									<div class="row">
                                               <div class="col-md-8">
                                                    <div class=" form-group">
                                                         <label class="control-label col-sm-4 col-md-4 col-lg-3" >Profile Image <i class="red" >*</i></label>
                                                          <div class="col-sm-8 col-md-8 col-lg-9">
                                                            <input type="file" data-rule-required="true" name="profile_image" id="input-file-now" class="dropify"  />
                                                            <label id="input-file-now-error" class="error" for="input-file-now"></label>
                                                            <span class="error" style = "color:red;">{{ $errors->first('profile_image') }} </span>
                                                         </div>
                                                    </div>
                                                </div>
                                            
                                            </div>
						              <div class="row">
										<div class="col-lg-8">
											<div class="form-group">
												<label class="control-label col-sm-4 col-md-4 col-lg-3" for="first_name">First Name<i class="red">*</i></label>
												<div class="col-sm-8 col-md-8 col-lg-9">
													<input type="text" value="{{old('first_name')}}" name="first_name" id="first_name" class="form-control" placeholder="First Name" data-rule-required="true" data-rule-pattern="^[a-z A-Z]+$" data-msg-pattern="Alphabetics only" data-rule-maxlength="40" value="">
													<span class="error" style = "color:red;">{{ $errors->first('first_name') }} </span>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-8">
											<div class="form-group">
												<label class="control-label col-sm-4 col-md-4 col-lg-3" for="last_name">Last Name<i class="red">*</i></label>
												<div class="col-sm-8 col-md-8 col-lg-9">
													<input type="text" value="{{old('last_name')}}" name="last_name" id="last_name" class="form-control" placeholder="Last Name" data-rule-required="true" data-rule-pattern="^[a-z A-Z]+$" data-msg-pattern="Alphabetics only" data-rule-maxlength="40" value="">
													<span class="error" style = "color:red;">{{ $errors->first('last_name') }} </span>
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
				                                          	<option value="{{old('role')}}" >Select Role </option>
											 				@if(isset($arr_roles) && count($arr_roles)>0)
				                                        		@foreach($arr_roles as $roles)
				                                        		<option value="{{$roles['role']}}">{{$roles['role'] or ''}}</option>
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
													<input type="text" name="contact" id="contact" value="{{old('contact')}}" class="form-control" placeholder="Contact Number" data-rule-required="true" data-rule-pattern="[- +()0-9]+" data-rule-minlength="10" data-rule-maxlength="13" maxlength="13" data-msg-minlength="Contact no should be at least 10 numbers" data-msg-maxlength="Contact no should not be more than 13 numbers">
													<span class="error" style = "color:red;">{{ $errors->first('contact') }} </span>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-8">
											<div class="form-group">
												<label class="control-label col-sm-4 col-md-4 col-lg-3" for="email">Email<i class="red">*</i></label>
												<div class="col-sm-8 col-md-8 col-lg-9">
													<input type="email" value="{{old('email')}}" name="email" id="email" class="form-control" placeholder="Email" data-rule-required="true"  value="">
													<span class="error" style = "color:red;">{{ $errors->first('email') }} </span>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-8">
											<div class="form-group">
												<label class="control-label col-sm-4 col-md-4 col-lg-3" for="password">Password<i class="red">*</i></label>
												<div class="col-sm-8 col-md-8 col-lg-9">
													<input type="password" value="{{old('password')}}" name="password" id="password" class="form-control" placeholder="Password"  data-rule-required="true" data-rule-minlength="6" data-rule-maxlength="16" >
													<span class="error" style = "color:red;">{{ $errors->first('password') }} </span>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-8">
											<div class="form-group">
												<label class="control-label col-sm-4 col-md-4 col-lg-3" for="conf_password">Confirm Password<i class="red">*</i></label>
												<div class="col-sm-8 col-md-8 col-lg-9">
													<input type="password" value="{{old('conf_password')}}" name="conf_password" id="conf_password" class="form-control" placeholder="Confirm Password" data-rule-required="true" data-rule-equalto="#password" data-rule-maxlength="10">
													<span class="error" style = "color:red;">{{ $errors->first('conf_password') }} </span>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-8">
											<div class="form-group">
												<label class="control-label col-sm-4 col-md-4 col-lg-3" for="address">Address<i class="red">*</i></label>
												<div class="col-sm-8 col-md-8 col-lg-9">
													<textarea  name="address" id="site_address" class="form-control" placeholder="Address" data-rule-required="true"  data-rule-maxlength="500">{{old('address')}}</textarea>
													<span class="error" style = "color:red;">{{ $errors->first('address') }} </span>
												</div>
											</div>
										</div>
										{{-- <div class="col-sm-12 col-md-12 col-lg-6">
										<div class="form-group">
											<label class="control-label col-sm-4 col-md-3 col-lg-3">Latitude<i class="red">*</i></label>
											<div class="col-sm-8 col-md-9 col-lg-9">
												<input type="text" name="latitude" id="latitude"  class="form-control" placeholder="Latitude" data-rule-number="true" data-rule-required="true" value="{{$arr_site_settings['lat'] or ''}}">
												<span class="error">{{ $errors->first('latitude') }} </span>
											</div>
										</div>
									</div> --}}
									</div>
									
									<div class="form-group text-right">
										<div class="col-lg-8">
											<button type="submit" class="btn btn-primary">Add</button>
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



<script type="text/javascript">
  
  var glob_fields_modified = false;

  function selectAll(ref)
  {
    var action = $(ref).attr('data-module-action');

    var is_checked = $(ref).is(":checked");

    var arr_input = $('input[data-module-action-ref="'+action+'"]');  

    if(is_checked)
    {
      $.each(arr_input,function(index,elem)
      {
        $(elem).prop('checked', true);
      });  
    }
    else
    {
      
      $.each(arr_input,function(index,elem)
      {
        $(elem).prop('checked', false);
      });   
    }
    
  }
</script>
<script src="https://maps.googleapis.com/maps/api/js?v=3&key={{config('app.project.google_map_api_key') }}&libraries=places"></script> 	        
<script>
	$(document).ready(function()
	{

		$("#site_address").geocomplete({
			details: ".geo-details",
			detailsAttribute: "data-geo"
		}).bind("geocode:result", function (event, result){                       
			$("#latitude").val(result.geometry.location.lat());
			$("#longitude").val(result.geometry.location.lng());
			var searchAddressComponents = result.address_components,
			searchPostalCode="";
		});

	});
	function chk_validation(ref)
	{
		var yourInput = $(ref).val();
		re = /[0-9`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
		var isSplChar = re.test(yourInput);
		if(isSplChar)
		{
			var no_spl_char = yourInput.replace(/[0-9`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
			$(ref).val(no_spl_char);
		}
	}

</script>


@endsection


