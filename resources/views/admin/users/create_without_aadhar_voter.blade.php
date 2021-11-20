@extends('admin.layout.master')
<style type="text/css">
	#map {
  height: 30%;
  /*width: 100%;*/
}
/* Optional: Makes the sample page fill the window. */
html, body {
  height: 100%;
  width: 100%;
  margin: 0;
  padding: 0;
}
.highlight-error {
  border-color: red;
}
</style>
    
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

				<div class="panel-body " >
					@include('admin.layout._operation_status') 
					<form action="{{$module_url_path}}/store" id="frm_create_page" name="frm_create_page" class="form-horizontal cmxform" method="post" enctype="multipart/form-data">
						{{csrf_field()}}
						<section class="panel">
						<header class="panel-heading">
                        Personal Details
                    </header>
                    <br>
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">First Name<i style="color:red;">*</i></label>
							<div class="col-sm-3">
								<input type="text" id="first_name"  name="first_name" value="{{old('first_name')}}" data-rule-required="true" data-rule-lettersonly=”true” class="form-control ">
								<span class="error" style="color: red;">{{ $errors->first('first_name') }} </span>
							</div>
							<label class="col-sm-2 col-sm-2 control-label">Last Name<i style="color:red;">*</i></label>
							<div class="col-sm-3">
								<input type="text" id="last_name"  name="last_name" value="{{old('last_name')}}"  data-rule-required="true"  data-rule-lettersonly=”true” class="form-control ">
								<span class="error" style="color: red;">{{ $errors->first('last_name') }} </span>
							</div>
						</div>

						<div class="form-group">
						</div>
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Father/Husband Name<i style="color:red;">*</i></label>
							<div class="col-sm-3">
								<input type="text" id="father_full_name"  name="father_full_name"  value="{{old('father_full_name')}}"data-rule-required="true"  class="form-control " data-rule-lettersonly=”true”>
								<span class="error" style="color: red;">{{ $errors->first('father_full_name') }} </span>
							</div>
							<label class="col-sm-2 col-sm-2 control-label">Email</label>
							<div class="col-sm-3">
								<input type="text" id="email"  name="email"  value="{{old('email')}}" data-rule-required="fale" data-rule-email="true"  class="form-control ">
								<span class="error" style="color: red;">{{ $errors->first('email') }} </span>
							</div>
						</div>
						<div class="form-group">
							
						</div>
						<div class="form-group">

							<label class="col-sm-2 col-sm-2 control-label">Mobile Number(add +91)<i style="color:red;">*</i></label>
							<div class="col-sm-3">
								<input type="text" id="mobile_number"  name="mobile_number" value="{{old('mobile_number')}}"  class="required  form-control " data-rule-pattern="^(?:(?:\+|0{0,2})91(\s*[\ -]\s*)?|[0]?)?[789]\d{9}|(\d[ -]?){10}\d$" data-msg-pattern="Please enter valid mobile no"
                            {{-- data-rule-number="true" --}}{{--  maxlength="10" minlength="10" --}}>

								<span class="error" style="color: red;">{{ $errors->first('mobile_number') }} </span>
							</div>
							<label class="col-sm-2 col-sm-2 control-label">Date of Birth<i style="color:red;">*</i></label>
							<div class="col-sm-3">
								<input type="text" id="datepicker" readonly="" value="{{old('date_of_birth')}}" name="date_of_birth" data-rule-required="true" class="form-control" placeholder="Date of Birth"></p>
							</div>  
                             {{-- <div class="col-sm-3">
                               <div data-date-viewmode="years" id="datepicker" data-date-format="dd-mm-yyyy" data-date="12-02-2012"  class="input-append date dpYears">
                                <input type="text" readonly=""   size="16" class="form-control">
                                 <span class="input-group-btn add-on">
                                 <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                  </span>
                                   </div>
                                 <span class="help-block">Select date</span>
                              </div> --}}          
                                                
						</div>	
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label ">Gender<i style="color:red;">*</i></label>
							<div class="col-sm-6">
						<div class="radio-btns">
                        <div class="row">
                        	<p class='container'>
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 right-redio-mrg">
                                <div class="radio-btn ">
                                    <input type="radio" id="s-option1" name="gender" class = "gender" value="male"@if(old('gender')) checked @endif >
                                    <label for="s-option1"><span class="user-login-icon"></span>Male</label>
                                    
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 left-redio-mrg">
                                <div class="radio-btn radio-mrgin-right">
                                    <input type="radio" id="s-option2" name="gender" class = "gender" value="female" @if(old('gender')) checked @endif />
                                    <label for="s-option2"><span class="user-login-icon"></span>Female</label>
                                    
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 left-redio-mrg">
                                <div class="radio-btn radio-mrgin-right">
                                    <input type="radio" id="s-option3" name="gender" class = "gender" value="other" @if(old('gender')) checked @endif >
                                    <label for="s-option3"><span class="user-login-icon"></span>Other</label>
                                    
                                </div>

                            </div>
                        </p>
                            <span class="error" style="color: red;" style="color: red;">{{ $errors->first('gender') }} </span>
                        </div>
                        </div>
						</div>
						</div>
					 
				
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Religion<i style="color:red;">*</i></label>
							<div class="col-sm-3">
								<select name="religion" data-rule-required="true" id="religion" class="form-control">
                                        <option value="">Select Religion </option>
                        				@if(isset($arr_religion) && count($arr_religion)>0)
                                        @foreach($arr_religion as $religions)
                                        	<option value="{{$religions['id']}}"@if(old('religion') == $religions['id']) selected="selected" @endif >{{$religions['religion_name'] or ''}}</option>
                                        @endforeach
                                        @endif
                                    </select>
								<span class="error" style="color: red;">{{ $errors->first('religion') }} </span>
							</div>
							
							<label class="col-sm-2 col-sm-2 control-label">Caste Category<i style="color:red;">*</i></label>
							<div class="col-sm-3">
								<select name="caste" data-rule-required="true" id="caste" class="form-control">
                                        <option value="">Select Caste Category </option>
                        				@if(isset($arr_caste) && count($arr_caste)>0)
                                        @foreach($arr_caste as $castes)
                                        	<option value="{{$castes['id']}}" @if(old('caste') == $castes['id']) selected="selected" @endif>{{$castes['caste_name'] or ''}}</option>
                                        @endforeach
                                        @endif
                                    </select>
								<span class="error" style="color: red;">{{ $errors->first('caste') }} </span>
							</div>
							
					</div>
					</section>
					<section class="panel">
						<header class="panel-heading">
                        Address Details
                    </header>
                    <br>

					<div class="form-group">
						<label class="col-sm-2 col-sm-2 control-label">Pick Voter Location<i style="color:red;">*</i></label>
						<div class="col-sm-8">
						<div id="map"></div>
						<input type="hidden" name="latitude" id="latitude" data-rule-required="true" value=""/>
						<input type="hidden" name="longitude"id="longitude" data-rule-required="true" value=""/>
						{{-- {{map($latitude,$longitude)}} --}}

						</div>
					</div>
					<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">House No<i style="color:red;">*</i></label>
							<div class="col-sm-3">
								<input type="text" id="house_no"  name="house_no" value="{{old('house_no')}}" data-rule-required="true"  class="form-control " maxlength="10">
								<span class="error" style="color: red;">{{ $errors->first('house_no') }} </span>
							</div>

							<label class="col-sm-2 col-sm-2 control-label">Street/Area/Locality<i style="color:red;">*</i></label>
							<div class="col-sm-3">
								<input type="text" id="street" readonly="street" name="street" value="{{old('street')}}" data-rule-required="true"  class="form-control " maxlength="40">
								<span class="error" style="color: red;">{{ $errors->first('street') }} </span>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Pincode<i style="color:red;">*</i></label>
							<div class="col-sm-3">
								<input type="text" id="postal_code" readonly="pincode" name="pincode" value="{{old('pincode')}}" data-rule-required="true"  class="form-control " maxlength="10" data-rule-number="true">
								<span class="error" style="color: red;">{{ $errors->first('pincode') }} </span>
							</div>
						</div>
						{{-- @if (old('item') == $item->name) selected="selected" @endif --}}
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">State<i style="color:red;">*</i></label>
								{{-- <div class="col-sm-3"> --}}
								<div class="col-sm-3">
								<input type="text" id="state" readonly="state" name="state" value="{{old('state')}}" data-rule-required="true"  class="form-control " maxlength="50" data-rule-number="false">
								<span class="error" style="color: red;">{{ $errors->first('pincode') }} </span>
							</div>
						  <label class="col-sm-2 col-sm-2 control-label">District<i style="color:red;">*</i></label>
							<div class="col-sm-3">
							  <select name="district" data-rule-required="true" id="district"  class="form-control ">
                                        <option value="" >Select district </option>
							 			@if(isset($arr_districts) && count($arr_districts)>0)
                                        @foreach($arr_districts as $districts)
                                        	<option value="{{$districts['id']}}" @if(old('district') == $districts['id']) selected="selected" @endif>{{$districts['district_name'] or ''}}</option>
                                        @endforeach
                                        @endif
                              </select>
							 <span class="error" style="color: red;">{{ $errors->first('district') }} </span>
							</div>
							
						</div>
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">City<i style="color:red;">*</i></label>
							<div class="col-sm-3">
									 <select name="city" data-rule-required="true" id="city" class="form-control ">
                                        <option value="">Select City </option>
                                        
                                    </select>
							</div>
							{{-- 	 <select name="city" data-rule-required="true" id="city" class="form-control ">
                                        <option value="">Select City </option>
                                        
                                    </select> --}}

								<span class="error" style="color: red;">{{ $errors->first('city') }} </span>
							
							<label class="col-sm-2 col-sm-2 control-label">Town/Village<i style="color:red;">*</i></label>
								<div class="col-sm-3">
								{{-- <div class="col-sm-8"> --}}
								{{-- <input type="text" id="village"  name="village" value="{{old('village')}}" data-rule-required="true"  class="form-control " maxlength="50" data-rule-number="false">
								<span class="error" style="color: red;">{{ $errors->first('pincode') }} </span>
							</div> --}}

								<select name="village" data-rule-required="true" id="village" class="form-control">
                                        <option value="">Select Town/Village </option>
           
                                    </select>
								<span class="error" style="color: red;">{{ $errors->first('village') }} </span>
							</div>

						</div>
						
						</section>

						<section class="panel">
						<header class="panel-heading">
                        Other Details
                    </header>
                    <br>
						  
						
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Ward<i style="color:red;">*</i></label>
							<div class="col-sm-6">
								<select name="ward" data-rule-required="true" id="ward" class="form-control">
                                        <option value="">Select Ward </option>
           
                                    </select>
								<span class="error" style="color: red;">{{ $errors->first('ward') }} </span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Booth<i style="color:red;">*</i></label>
							<div class="col-sm-6">
								<select name="booth" data-rule-required="true" id="booth" class="form-control">
                                        <option value="">Select Booth </option>
           
                                    </select>
								<span class="error" style="color: red;">{{ $errors->first('booth') }} </span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">List<i style="color:red;">*</i></label>
							<div class="col-sm-6">
								<select name="list" data-rule-required="true" id="list" class="form-control">
                                        <option value="">Select List </option>
           
                                    </select>
								<span class="error" style="color: red;">{{ $errors->first('list') }} </span>
							</div>
						</div>
						{{-- <div class="form-group">
							 <label class="col-sm-2 col-sm-2 control-label">Ward No<i style="color:red;">*</i></label>
							<div class="col-sm-3">
								<input type="text" id="ward_no"  name="ward_no"  data-rule-required="true"  class="form-control ">
								<span class="error" style="color: red;">{{ $errors->first('ward_no') }} </span>
							</div> 
						<label class="col-sm-2 col-sm-2 control-label">Booth<i style="color:red;">*</i></label>
							<div class="col-sm-6">
								<select name="booth" data-rule-required="true" id="booth" class="form-control">
                                        <option value="">Select Booth</option>
           								@if(isset($arr_booth) && count($arr_booth)>0)
                                        @foreach($arr_booth as $booths)
                                        	<option value="{{$booths['id']}}" @if(old('booth') == $booths['id']) selected="selected" @endif >{{'('.$booths['booth_no'].')'.' '.'('.$booths['booth_name'].')'.' '.$booths['booth_address'] }}</option>
                                        @endforeach
                                        @endif
                                    </select>
								<span class="error" style="color: red;">{{ $errors->first('booth') }} </span>
							</div>
						</div> --}}
						

						{{-- <div class="form-group">
						<label class="col-sm-2 col-sm-2 control-label">List<i style="color:red;">*</i></label>
							<div class="col-sm-6">
								<select name="list" data-rule-required="true" id="list" class="form-control">
                                        <option value="">Select List</option>
           								@if(isset($arr_booth) && count($arr_booth)>0)
                                        @foreach($arr_booth as $booths)
                                        	<option value="{{$booths['id']}}" @if(old('list') == $booths['id']) selected="selected" @endif >{{'('.$booths['booth_no'].')'.' '.'('.$booths['booth_name'].')'.' '.$booths['booth_address'] }}</option>
                                        @endforeach
                                        @endif
                                    </select>
								<span class="error" style="color: red;">{{ $errors->first('list') }} </span>
							</div>
						
						</div> --}}
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Occupation<i style="color:red;">*</i></label>
							<div class="col-sm-3">
								<select name="occupation" data-rule-required="true" id="occupation" class="form-control">
                                        <option value="">Select occupation </option>
                        				@if(isset($arr_occupation) && count($arr_occupation)>0)
                                        @foreach($arr_occupation as $occupations)
                                        	<option value="{{$occupations['id']}}" @if(old('occupation') == $occupations['id']) selected="selected" @endif>{{$occupations['occupation_name'] or ''}}</option>
                                        @endforeach
                                        @endif
                                    </select>
								<span class="error" style="color: red;">{{ $errors->first('occupation') }} </span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Face Color<i style="color:red;">*</i></label>
							<div class="col-sm-6">
						<div class="radio-btns">
                        <div class="row">
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 right-redio-mrg">
                                <div class="radio-btn ">
                                    <input type="radio" id="s-option4" name="face_color" value="fair"@if(old('face_color')) checked @endif />
                                    <label for="s-option4"><span class="user-login-icon"></span>Fair</label>
                                    
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 left-redio-mrg">
                                <div class="radio-btn radio-mrgin-right">
                                    <input type="radio" id="s-option5" name="face_color" value="medium"@if(old('face_color')) checked @endif />
                                    <label for="s-option5"><span class="user-login-icon"></span>Medium</label>
                                    
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 left-redio-mrg">
                                <div class="radio-btn radio-mrgin-right">
                                    <input type="radio" id="s-option6" name="face_color" value="dark"@if(old('face_color')) checked @endif />
                                    <label for="s-option6"><span class="user-login-icon"></span>Dark</label>
                                    
                                </div>
                            </div>
                           <span class="error" style="color: red;">{{ $errors->first('face_color') }} </span>
                        </div>
                        </div>
						</div>
						</div>

							<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Surety<i style="color:red;">*</i></label>
							<div class="col-sm-6">
						<div class="radio-btns">
                        <div class="row">
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 right-redio-mrg">
                                <div class="radio-btn ">
                                	
                                    <input type="radio" id="s-option7" name="voting_surety" value="0"@if(old('voting_surety')) checked @endif />
                                    <label for="s-option7"><span class="user-login-icon"></span>Full Surety
                                  </label>  
                                    
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 left-redio-mrg">
                                <div class="radio-btn radio-mrgin-right">
                                    <input type="radio" id="s-option8" name="voting_surety" value="1" @if(old('voting_surety')) checked @endif/>
                                    <label for="s-option8"><span class="user-login-icon"></span>Half Surety</label>
                                    
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 left-redio-mrg">
                                <div class="radio-btn radio-mrgin-right">
                                    <input type="radio" id="s-option9" name="voting_surety" value="2" @if(old('voting_surety')) checked @endif/>
                                    <label for="s-option9"><span class="user-login-icon"></span>No Surety</label>
                                    
                                </div>
                            </div>
                           <span class="error" style="color: red;">{{ $errors->first('voting_surety') }} </span>
                        </div>
                        </div>
						</div>
						</div>
					</section>
					
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
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script type="text/javascript" src="{{url('/')}}/assets/admin_assets/js/bootstrap-datepicker.js"></script>
{{-- <link href="{{url('/')}}/assets/admin_assets/css/datetimepicker-custom.css" rel="stylesheet">
<script src="{{url('/')}}/assets/admin_assets/js/bootstrap-datetimepicker.js"></script> --}}
{{-- <script src="{{url('/')}}/assets/admin_assets/js/pickers-init.js"></script>
 --}}
<script async defer
src="https://maps.googleapis.com/maps/api/js?key={{ config('app.project.google_map_api_key') }}&callback=initMap">
</script>
<script>
	$(function() {
        $( "#datepicker" ).datepicker({
            dateFormat : 'dd/mm/yy',
            changeMonth : true,
            changeYear : true,
            yearRange: '-100y:c+nn',
            maxDate: '-1d',
            closeOnDateSelect : true,
            scrollMonth : false,
            scrollInput : false,
            
        });
    });



	$.validator.addMethod('customphone', function (value, element) {
	return this.optional(element) || /(5|6|7|8|9)\d{9}/.test(value);
	}, "Please enter a valid phone number");

	$.validator.addClassRules('customphone', {
	customphone: true
	});


	$(document).ready(function(){
		/*$( "#datepicker" ).datepicker();*/
		jQuery.validator.addMethod("lettersonly", function(value, element) {
  		return this.optional(element) || /^[a-z]+$/i.test(value);}, "Letters only please");

	$('#frm_create_page').validate({
									  rules: {
									    gender: {
									      required: true
									    },
									     voting_surety: {
									      required: true
									    },
									     face_color: {
									      required: true

									  }
									}
						})

	});

	
</script>
<Script>
var map, infoWindow;
var nashik   = { lat: 19.9975 ,lng: 73.7898 };
function initMap() {
  map = new google.maps.Map(document.getElementById('map'), {
    center: nashik,
    zoom: 12
  });
  var marker    = new google.maps.Marker({position: nashik, map: map,draggable:true});
	google.maps.event.addListener(marker, 'dragend',
	            function(marker) {
	                var latLng          = marker.latLng;
	                currentLatitude     = latLng.lat();
	                currentLongitude    = latLng.lng();
	                 map.setCenter(new google.maps.LatLng(currentLatitude, currentLongitude)); 
	                $("#latitude").val(currentLatitude);
                	$("#longitude").val(currentLongitude);
                	   var geocoder= new google.maps.Geocoder();

	                var latlng = {lat: parseFloat(currentLatitude), lng: parseFloat(currentLongitude)};
	                geocoder.geocode({'location': latlng}, function(results, status) {
	                $("#state, #city").val();
	                var length = results[0].address_components.length;
	                console.log(results[0].address_components);
	                      for (var i = 0; i < length; i++)
	                        {
	                            if(results[0].address_components[i].types[0] == 'administrative_area_level_1'){
	                                $("#state").val(results[0].address_components[i].long_name);        //for state name
	                            }

	                            if(results[0].address_components[i].types[0] == 'administrative_area_level_2'){
	                                $("#district1").val(results[0].address_components[i].long_name);         //for city name
	                            }
	                            // if(results[0].address_components[i].types[0] == 'locality'){
	                            //     $("#village").val(results[0].address_components[i].long_name);//for city name
	                            // }

	                            if(results[0].address_components[i].types[0] == 'postal_code'){
	                                $("#postal_code").val(results[0].address_components[i].long_name);         //for city name
	                            }

	                            if(results[0].address_components[i].types[0] == 'route'){
	                                $("#street").val(results[0].address_components[i].long_name);         //for city name
	                            }
	                            if(results[0].address_components[i].types[0] == 'sublocality_level_1'){
	                                $("#house_no").val(results[0].address_components[i].long_name);         //for city name
	                            }

	                            if($("#street").val()=='')
	                            	{$("#street").val('Unnamed Road');}
	                              /*if(result.address_components[i].types[0] == 'administrative_area_level_1'){
	                                    $("#state").val(result.address_components[i].long_name);                //for state name
	                                }
	                                if(result.address_components[i].types[0] == 'locality'){
	                                    $("#city").val(result.address_components[i].long_name);                 //for city name
	                                }
	                                if(result.address_components[i].types[0] == 'postal_code'){
	                                    $("#postal_code").val(result.address_components[i].long_name);          //for city name
	                                }*/

	                        }
	                  if (status === 'OK') {

	                    if (results[0]) {
	                      map.setZoom(11);
	                      map.setCenter(new google.maps.LatLng(currentLatitude, currentLongitude));  //set current location as center
	                    /*  var marker = new google.maps.Marker({
	                        position: latlng,
	                        map: map,
	                        draggable:true
	                      });*/
	                      $("#address").val(results[0].formatted_address);
	                    } else {
	                      window.alert('No results found');
	                    }
	                  } else {
	                    window.alert('Geocoder failed due to: ' + status);
	                  }
	                });
	            });
  infoWindow = new google.maps.InfoWindow;
  	//return map(latitude,longitude);
}
</Script>



<!-- Script for Image validation -->
<script type="text/javascript">
	$(document).ready(function() 
	{
	
	    $('#district').change(function(){

        var district_id = $('#district').val();
        
        if(district_id!='')
        {
            var url = '{{$module_url_path}}/get_cities';
            var csrf_token      = '{{csrf_token()}}';

            $.ajax({
                type:'POST',
                url: url,
                data:{district_id:district_id,_token:'{{csrf_token()}}'},

                success:function(resp){
                    $('#city').html(resp);
                }
            });
        }
    	});
        $('#city').change(function(){

            
            var district_id = $('#district').val();
            var city_id = $('#city').val();
            if(district_id!='' && city_id!='')
            {
                var url = '{{$module_url_path}}/get_villages';
                var csrf_token      = '{{csrf_token()}}';

                $.ajax({
                    type:'POST',
                    url: url,
                    data:{district_id:district_id,city_id:city_id,_token:'{{csrf_token()}}'},
                    
                    success:function(resp){
                        $('#village').html(resp);
                    }
                });
            }
        });


        $('#village').change(function(){

            
            var village_id = $('#village').val();
            var city_id = $('#city').val();
            var district_id = $('#district').val();
            if(district_id!='' && city_id!='' && village_id!='')
            { 

                var url = '{{$module_url_path}}/get_wards';
                var csrf_token      = '{{csrf_token()}}';

                $.ajax({
                    type:'POST',
                    url: url,
                    data:{district_id:district_id,city_id:city_id,village_id:village_id,_token:'{{csrf_token()}}'},
                    
                    success:function(resp){
                        $('#ward').html(resp);
                    }
                });
            }
        });

        $('#ward').change(function(){

            
            var ward_id = $('#ward').val();
            var city_id = $('#city').val();
            var village_id = $('#village').val();
            var district_id = $('#district').val();
            if(district_id!='' && city_id!='' && village_id!='' && ward_id!='')
            { 

                var url = '{{$module_url_path}}/get_booths';
                var csrf_token      = '{{csrf_token()}}';

                $.ajax({
                    type:'POST',
                    url: url,
                    data:{district_id:district_id,city_id:city_id,village_id:village_id,ward_id:ward_id,_token:'{{csrf_token()}}'},
                    
                    success:function(resp){
                        $('#booth').html(resp);
                    }
                });
            }
        });

        $('#booth').change(function(){

            var booth_id = $('#booth').val();
            var ward_id = $('#ward').val();
            var city_id = $('#city').val();
            var village_id = $('#village').val();
            var district_id = $('#district').val();
            if(district_id!='' && city_id!='' && village_id!='' && ward_id!='' && booth_id!='')
            { 

                var url = '{{$module_url_path}}/get_list';
                var csrf_token      = '{{csrf_token()}}';

                $.ajax({
                    type:'POST',
                    url: url,
                    data:{district_id:district_id,city_id:city_id,village_id:village_id,ward_id:ward_id,booth_id:booth_id,_token:'{{csrf_token()}}'},
                    
                    success:function(resp){
                        $('#list').html(resp);
                    }
                });
            }
        });

        // Initialize select2
		  // $("#selUser").select2();

		  // // Read selected option
		  // $('#but_read').click(function(){
		  //   var username = $('#selUser option:selected').text();
		  //   var userid = $('#selUser').val();

		  //   $('#result').html("id : " + userid + ", name : " + username);

		  // });


	});
	$('[data-type="adhaar-number"]').keyup(function() {
	  var value = $(this).val();
	  value = value.replace(/\D/g, "").split(/(?:([\d]{4}))/g).filter(s => s.length > 0).join("-");
	  $(this).val(value);
	});

	$('[data-type="adhaar-number"]').on("change, blur", function() {
	  var value = $(this).val();
	  var maxLength = $(this).attr("maxLength");
	  if (value.length != maxLength) {
	    $(this).addClass("highlight-error");
	  } else {
	    $(this).removeClass("highlight-error");
	  }
	});



</script>

@endsection


