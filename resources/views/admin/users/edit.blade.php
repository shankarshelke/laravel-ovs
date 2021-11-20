
@extends('admin.layout.master') 
<style type="text/css">
	#map {
  height: 300px;
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
    body{overflow: unset !important}
    section .panel-heading{padding: 0 0 10px;}
    section.panel{-webkit-box-shadow: none;box-shadow: none;}
    
</style>   
@section('main_content')

<!--body wrapper start-->
<div class="wrapper">
	<div class="row">
		<div class="col-md-12">
			@include('admin.layout.breadcrumb')  
			<section class="panel">
				<div class="panel-body">
					@include('admin.layout._operation_status') 
					<form action="{{$module_url_path}}/update/{{ $enc_id or '' }}" id="frm_edit_page" name="frm_edit_page" class="cmxform" method="post" enctype="multipart/form-data">
						{{csrf_field()}}
						<section class="panel">
						<header class="panel-heading">
                            Personal Details
                        </header>
                        <br>                        
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-4">                        
                                <div class="form-group">
                                    <label class="control-label">Voter Id<i style="color:red;">*</i></label>
                                    <div class="input-group">
                                        <span class="input-group-addon">#</span>
                                        <input type="text" id="voter_id" name="voter_id"  data-rule-required="true"  
                                         class="form-control" value="{{ $arr_data['voter_id'] or 'NA' }}" >
                                        <span class="error">{{ $errors->first('voter_id') }} </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Fullname<i style="color:red;">*</i></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-user"></i></span>
                                        <input type="text" id="fullname"  name="fullname"  data-rule-required="true"  class="form-control "value="{{ (($arr_data['full_name']) ? $arr_data['full_name'] : ($arr_data['first_name'] . ' ' . $arr_data['last_name'])) }}">
                                        <span class="error">{{ $errors->first('fullname') }} </span>
                                    </div>                                    
                                </div>
                            </div>
                          <!--   <div class="col-sm-12 col-md-12 col-lg-3">
                                <div class="form-group">                                   
                                    <label class="control-label">Last Name<i style="color:red;">*</i></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-user"></i></span>
                                        <input type="text" id="last_name"  name="last_name"  data-rule-required="true"  class="form-control "  data-rule-lettersonly=”true” value="{{ $arr_data['last_name'] or 'NA' }}">
                                        <span class="error">{{ $errors->first('last_name') }} </span>
                                    </div>
                                </div>
                            </div> -->
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Father/Husband Name<!-- <i style="color:red;">*</i> --></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-user"></i></span>
                                        <input type="text" id="father_full_name"  name="father_full_name"   class="form-control " value="{{ $arr_data['father_full_name'] or 'NA' }}">
                                        <span class="error">{{ $errors->first('father_full_name') }} </span>
                                    </div>                                    
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><!-- <i class="ti-email"></i> --></span>
                                        <input type="text" id="email"  name="email"  {{-- data-rule-email="true" --}}  class="form-control " value="{{ $arr_data['email'] or 'NA' }}">
                                        <span class="error">{{ $errors->first('email') }} </span>
                                    </div>
                                </div>
                            </div>                            
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Mobile Number<!-- <i style="color:red;">*</i> --></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="icon-phone"></i></span>
                                        <input type="text" id="mobile_number"  name="mobile_number" data-rule-pattern="^(?:(?:\+|0{0,2})91(\s*[\ -]\s*)?|[0]?)?[789]\d{9}|(\d[ -]?){10}\d$" data-msg-pattern="Please enter valid mobile no"  class=" form-control" value="{{ $arr_data['mobile_number'] or 'NA' }}">
                                        <span class="error">{{ $errors->first('mobile_number') }} </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Date of Birth<!-- <i style="color:red;">*</i> --></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-calendar"></i></span>
                                        <input type="text" id="datepicker" readonly="" name="date_of_birth"class="form-control" value="{{ $arr_data['date_of_birth'] or 'NA' }}">
                                    </div>
                                </div>
                            </div>   
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Gender<i style="color:red;">*</i></label>
                                    <div class="input-group">                                        
                                        <div class="radio-btns">                                            
                                            <div class="radio-btn" style="float: left;margin-right: 30px">
                                                <input type="radio" id="s-option1" name="gender" value="male" @if($arr_data['gender']=='male')
                                                checked='checked'
                                                @endif />
                                                <label for="s-option1"><span class="user-login-icon"></span>Male</label>
                                            </div>                                            
                                            <div class="radio-btn" style="float: left;margin-right: 30px">
                                                <input type="radio" id="s-option2" name="gender" value="female"@if($arr_data['gender']=='female')
                                                checked='checked'
                                                @endif />
                                                <label for="s-option2"><span class="user-login-icon"></span>Female</label>
                                            </div>                                            
<!--                                             <div class="radio-btn" style="float: left;margin-right: 30px">
                                                <input type="radio" id="s-option3" name="gender" value="other"@if($arr_data['gender']=='other')
                                                checked='checked'
                                                @endif />
                                                <label for="s-option3"><span class="user-login-icon"></span>Other</label>
                                            </div> -->                                            
                                            <span class="error">{{ $errors->first('gender') }} </span>                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Religion<!-- <i style="color:red;">*</i> --></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-user"></i></span>
                                        <select name="religion"  id="religion" class="form-control">
                                            <option value="">Select Religion </option>
                                            @if(isset($arr_religion) && count($arr_religion)>0)
                                            @foreach($arr_religion as $religions)
                                                <option @if($religions['id']==$arr_data['religion']) selected="true" @endif value="{{$religions['id']}}">{{$religions['religion_name'] or ''}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span class="error" style="color: red;">{{ $errors->first('religion') }} </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Caste Category<!-- <i style="color:red;">*</i> --></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-user"></i></span>
                                        <select name="caste"  id="caste" class="form-control">
                                            <option value="">Select Caste Category </option>
                                            @if(isset($arr_caste) && count($arr_caste)>0)
                                            @foreach($arr_caste as $castes)
                                                <option @if($castes['id']==$arr_data['caste']) selected="true" @endif value="{{$castes['id']}}">{{$castes['caste_name'] or ''}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span class="error" style="color: red;">{{ $errors->first('caste') }} </span>
                                    </div>
                                </div>
                            </div>
                        </div>
<!--
                        <div class="form-group">
                            {{-- <label class="col-sm-2 col-sm-2 control-label">Aadhar No<i style="color:red;">*</i></label>
                            <div class="col-sm-3">
                                <input type="text" id="aadhar_id" name="aadhar_id"  data-rule-required="true" class="form-control" value="{{ $arr_data['aadhar_id'] or 'NA' }}" data-type="adhaar-number" maxLength="19">
                                <span class="error">{{ $errors->first('aadhar_id') }} </span>
                            </div> --}}

                            <label class="col-sm-2 col-sm-2 control-label">Voter Id<i style="color:red;">*</i></label>
                            <div class="col-sm-3">
                                <input type="text" id="voter_id" name="voter_id"  data-rule-required="true"  
                                 class="form-control" value="{{ $arr_data['voter_id'] or 'NA' }}" >
                                <span class="error">{{ $errors->first('voter_id') }} </span>
                            </div>
                        </div>
-->
<!--
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">First Name<i style="color:red;">*</i></label>
							<div class="col-sm-3">
								<input type="text" id="first_name"  name="first_name"  data-rule-required="true" data-rule-lettersonly=”true” class="form-control "value="{{ $arr_data['first_name'] or 'NA' }}">
								<span class="error">{{ $errors->first('first_name') }} </span>
							</div>
							<label class="col-sm-2 col-sm-2 control-label">Last Name<i style="color:red;">*</i></label>
							<div class="col-sm-3">
								<input type="text" id="last_name"  name="last_name"  data-rule-required="true"  class="form-control "  data-rule-lettersonly=”true” value="{{ $arr_data['last_name'] or 'NA' }}">
								<span class="error">{{ $errors->first('last_name') }} </span>
							</div>
						</div>
-->						
<!--
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Father/Husband Name<i style="color:red;">*</i></label>
							<div class="col-sm-3">
								<input type="text" id="father_full_name"  name="father_full_name"  data-rule-required="true"  class="form-control " value="{{ $arr_data['father_full_name'] or 'NA' }}">
								<span class="error">{{ $errors->first('father_full_name') }} </span>
							</div>
							<label class="col-sm-2 col-sm-2 control-label">Email</label>
							<div class="col-sm-3">
								<input type="text" id="email"  name="email"  {{-- data-rule-email="true" --}}  class="form-control " value="{{ $arr_data['email'] or 'NA' }}">
								<span class="error">{{ $errors->first('email') }} </span>
							</div>
						</div>
-->					
<!--
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Mobile Number(add +91)<i style="color:red;">*</i></label>
							<div class="col-sm-3">
								<input type="text" id="mobile_number"  name="mobile_number"  data-rule-required="true" data-rule-pattern="^(?:(?:\+|0{0,2})91(\s*[\ -]\s*)?|[0]?)?[789]\d{9}|(\d[ -]?){10}\d$" data-msg-pattern="Please enter valid mobile no"  class=" form-control" value="{{ $arr_data['mobile_number'] or 'NA' }}">
								<span class="error">{{ $errors->first('mobile_number') }} </span>
							</div>

							<label class="col-sm-2 col-sm-2 control-label">Date of Birth<i style="color:red;">*</i></label>
						<div class="col-sm-3">
								<input type="text" id="datepicker" readonly="" name="date_of_birth" data-rule-required="true" class="form-control" value="{{ $arr_data['date_of_birth'] or 'NA' }}">
							</div>
						</div>
-->
<!--
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Gender<i style="color:red;">*</i></label>
							<div class="col-sm-6">
						<div class="radio-btns">
                        <div class="row">
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 right-redio-mrg">
                                <div class="radio-btn ">
                                    <input type="radio" id="s-option1" name="gender" value="male" @if($arr_data['gender']=='male')
                                    checked='checked'
                                    @endif />
                                    <label for="s-option1"><span class="user-login-icon"></span>Male</label>
                                    
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 left-redio-mrg">
                                <div class="radio-btn radio-mrgin-right">
                                    <input type="radio" id="s-option2" name="gender" value="female"@if($arr_data['gender']=='female')
                                    checked='checked'
                                    @endif />
                                    <label for="s-option2"><span class="user-login-icon"></span>Female</label>
                                    
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 left-redio-mrg">
                                <div class="radio-btn radio-mrgin-right">
                                    <input type="radio" id="s-option3" name="gender" value="other"@if($arr_data['gender']=='other')
                                    checked='checked'
                                    @endif />
                                    <label for="s-option3"><span class="user-login-icon"></span>Other</label>
                                    
                                </div>
                            </div>
                            <span class="error">{{ $errors->first('gender') }} </span>
                        </div>
                        </div>
						</div>
						</div>
-->
<!--
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Religion<i style="color:red;">*</i></label>
							<div class="col-sm-3">
								<select name="religion" data-rule-required="true" id="religion" class="form-control">
                                        <option value="">Select Religion </option>
                        				@if(isset($arr_religion) && count($arr_religion)>0)
                                        @foreach($arr_religion as $religions)
                                        	<option @if($religions['id']==$arr_data['religion']) selected="true" @endif value="{{$religions['id']}}">{{$religions['religion_name'] or ''}}</option>
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
                                        	<option @if($castes['id']==$arr_data['caste']) selected="true" @endif value="{{$castes['id']}}">{{$castes['caste_name'] or ''}}</option>
                                        @endforeach
                                        @endif
                                    </select>
								<span class="error" style="color: red;">{{ $errors->first('caste') }} </span>
							</div>
					</div>
-->
				    </section>
					<section class="panel">
						<header class="panel-heading">
                            Address Details
                        </header>
                        <br>
                        {{-- <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-4">   
                                <div class="form-group">
                                    <label class="control-label">House No<i style="color:red;">*</i></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-home"></i></span>
                                        <input type="text" id="house_no"  name="house_no"  data-rule-required="true"  class="form-control "value="{{ $arr_data['house_no'] or 'NA' }}">
                                        <span class="error">{{ $errors->first('house_no') }} </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-4">   
                                <div class="form-group">
                                    <label class="control-label">Street/Area/Locality<i style="color:red;">*</i></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-location-pin"></i></span>
                                        <input type="text" id="street"  name="street"  data-rule-required="true"  class="form-control " value="{{ $arr_data['street'] or 'NA' }}">
                                        <span class="error">{{ $errors->first('street') }} </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-4">   
                                <div class="form-group">
                                    <label class="control-label">Pincode<i style="color:red;">*</i></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-location-pin"></i></span>
                                        <input type="text" id="pincode"  name="pincode"  data-rule-required="true"  class="form-control " value="{{ $arr_data['pincode'] or 'NA' }}">
                                        <span class="error">{{ $errors->first('pincode') }} </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-4">   
                                <div class="form-group">
                                    <label class="control-label">District<i style="color:red;">*</i></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-location-pin"></i></span>
                                        <select name="district" data-rule-required="true" id="district"  class="form-control ">
                                            <option value="" >Select district </option>
                                            @if(isset($arr_districts) && count($arr_districts)>0)
                                            @foreach($arr_districts as $districts)
                                            <option @if($districts['id']==$arr_data['district']) selected="true" @endif value="{{$districts['id']}}">{{$districts['district_name'] or ''}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span class="error" style="color: red;">{{ $errors->first('district') }} </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-4">   
                                <div class="form-group">
                                    <label class="control-label">City<i style="color:red;">*</i></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-location-pin"></i></span>
                                        <select name="city" data-rule-required="true" id="city"class="form-control ">
                                            <option value="">Select City </option>
                                            @if(isset($arr_city) && count($arr_city)>0)
                                            @foreach($arr_city as $cities)
                                            <option @if($cities['id']==$arr_data['city']) selected="true" @endif value="{{$cities['id']}}">{{$cities['city_name'] or ''}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span class="error" style="color: red;">{{ $errors->first('city') }} </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Town/Village<i style="color:red;">*</i></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-location-pin"></i></span>
                                        <select name="village" data-rule-required="true" id="village" class="form-control">
                                            <option value="">Select Town/City </option>
                                            @if(isset($arr_village) && count($arr_village)>0)
                                            @foreach($arr_village as $villages)
                                            <option @if($villages['id']==$arr_data['village']) selected="true" @endif value="{{$villages['id']}}">{{$villages['village_name'] or ''}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span class="error">{{ $errors->first('village') }} </span>
                                    </div>
                                </div>
                            </div> --}}
                           
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                    <label class="control-label">Pick Voter Location<i style="color:red;">*</i></label>
                                    <div class="input-group">                                        
                                        <div id="map"></div>
                                        <input type="hidden" name="latitude" id="latitude" data-rule-required="true" value="{{ $arr_data['latitude'] or 'NA' }}"/>
                                        <input type="hidden" name="longitude"  id="longitude"  value="{{ $arr_data['longitude'] or 'NA' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                           <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-4">
                            <div class="form-group">
                                <label class="control-label">Enter Address<i style="color:red;">*</i></label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="ti-user"></i></span>
                                <input type="text" id="address"  name="address"  data-rule-required="true"  class="form-control " value="{{ $arr_data['address'] or 'NA' }}">
                                <span class="error">{{ $errors->first('address') }} </span>
                            </div>						
					</section>
					<section class="panel">
						<header class="panel-heading">
                            Other Details
                        </header>
                        <br>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Ward<i style="color:red;">*</i></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-menu-alt"></i></span>
                                        <select name="ward" data-rule-required="true" id="ward" class="form-control">
                                            <option value="">Select Ward </option>
                                            @if(isset($arr_ward) && count($arr_ward)>0)
                                            @foreach($arr_ward as $wards)
                                            <option @if($wards['id']==$arr_data['ward']) selected="true" @endif value="{{$wards['id']}}">{{$wards['ward_name'] or ''}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span class="error">{{ $errors->first('ward') }} </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Booth<i style="color:red;">*</i></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-menu-alt"></i></span>
                                        <select name="booth" data-rule-required="true" id="booth" class="form-control">
                                            <option value="">Select Booth </option>
                                            @if(isset($arr_booth) && count($arr_booth)>0)
                                            @foreach($arr_booth as $booths)
                                            <option @if($booths['id']==$arr_data['booth']) selected="true" @endif value="{{$booths['id']}}">{{$booths['booth_name'] or ''}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span class="error">{{ $errors->first('booth') }} </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">List No<i style="color:red;">*</i></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-menu-alt"></i></span>
                                        <select name="list" data-rule-required="true" id="list"class="form-control ">
                                            <option value="">Select List </option>
                                            @if(isset($arr_list) && count($arr_list)>0)
                                            @foreach($arr_list as $lists)
                                            <option @if($lists['id']==$arr_data['list']) selected="true" @endif value="{{$lists['id']}}">{{'('.$lists['list_no'].')'.' '.'('.$lists['list_name'].')' }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span class="error" style="color: red;">{{ $errors->first('list') }} </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Occupation<i style="color:red;">*</i></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-menu-alt"></i></span>
                                        <select name="occupation" data-rule-required="true" id="occupation" class="form-control">
                                                <option value="">Select occupation </option>
                                                @if(isset($arr_occupation) && count($arr_occupation)>0)
                                                @foreach($arr_occupation as $occupations)
                                                    <option @if($occupations['id']==$arr_data['occupation']) selected="true" @endif value="{{$occupations['id']}}">{{$occupations['occupation_name'] or ''}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        <span class="error" style="color: red;">{{ $errors->first('occupation') }} </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">Voting Surety<i style="color:red;">*</i></label>
                                    <div class="input-group">
                                        <div class="radio-btns">                                            
                                            <div class="radio-btn" style="float: left;margin-right: 30px">
                                                <input type="radio" id="s-option1" name="voting_surety" value="0" @if($arr_data['voting_surety']=='0')
                                                checked='checked'
                                                @endif />
                                                <label for="s-option1"><span class="user-login-icon"></span>Full Surety</label>
                                            </div>                                                                                                
                                            <div class="radio-btn radio-mrgin-right" style="float: left;margin-right: 30px">
                                                <input type="radio" id="s-option2" name="voting_surety" value="1"@if($arr_data['voting_surety']=='1')
                                                checked='checked'
                                                @endif />
                                                <label for="s-option2"><span class="user-login-icon"></span>Half Surety</label>
                                            </div>                                                
                                            <div class="radio-btn radio-mrgin-right" style="float: left;margin-right: 30px">
                                                <input type="radio" id="s-option3" name="voting_surety" value="2"@if($arr_data['voting_surety']=='2')
                                                checked='checked'
                                                @endif />
                                                <label for="s-option3"><span class="user-login-icon"></span>No Surety</label>
                                            </div>                                                
                                            <span class="error">{{ $errors->first('voting_surety') }} </span>                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>						
<!--
						{{-- <div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Face color<i style="color:red;">*</i></label>
							<div class="col-sm-6">
						<div class="radio-btns">
                        <div class="row">
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 right-redio-mrg">
                                <div class="radio-btn ">
                                    <input type="radio" id="s-option1" name="face_color" value="fair" @if($arr_data['face_color']=='Fair')
                                    checked='checked'
                                    @endif />
                                    <label for="s-option1"><span class="user-login-icon"></span>Fair</label>
                                    
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 left-redio-mrg">
                                <div class="radio-btn radio-mrgin-right">
                                    <input type="radio" id="s-option2" name="face_color" value="medium"@if($arr_data['face_color']=='Medium')
                                    checked='checked'
                                    @endif />
                                    <label for="s-option2"><span class="user-login-icon"></span>Medium</label>
                                    
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 left-redio-mrg">
                                <div class="radio-btn radio-mrgin-right">
                                    <input type="radio" id="s-option3" name="face_color" value="dark"@if($arr_data['face_color']=='Dark')
                                    checked='checked'
                                    @endif />
                                    <label for="s-option3"><span class="user-login-icon"></span>DarK</label>
                                    
                                </div>
                            </div>
                            <span class="error">{{ $errors->first('face_color') }} </span>
                        </div>
                        </div>
						</div>
						</div> --}}
-->
						<div class="form-group">							
                            <a href="{{ $module_url_path or 'NA' }}" class="btn btn-primary">Back</a>
                            <button class="btn btn-primary" type="submit"  id="btn_add_front_page">Update</button>							
						</div>
                    </section>
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

<script src="{{url('/')}}/assets/admin_assets/js/pickers-init.js"></script>
\
<script async defer
src="https://maps.googleapis.com/maps/api/js?key={{ config('app.project.google_map_api_key') }}&callback=initMap">
</script>
<Script>
var map, infoWindow;
var nashik   = { lat: 19.9975 ,lng: 73.7898 };
function initMap() {
  map = new google.maps.Map(document.getElementById('map'), {
    center: nashik,
    zoom: 15
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
                    console.log(results[0].formatted_address);
                    $("#address").val(results[0].formatted_address);
                          /*for (var i = 0; i < length; i++)
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
                                    $("#pincode").val(results[0].address_components[i].long_name);         //for city name
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
}

</Script>
<script>
    $(function() {
        $( "#datepicker" ).datepicker({
            dateFormat : 'dd/mm/yy',
            changeMonth : true,
            changeYear : true,
            yearRange: '-100y:c+nn',
            maxDate: '-1d'
        });
    });


    $.validator.addMethod('customphone', function (value, element) {
    return this.optional(element) || /(5|6|7|8|9)\d{9}/.test(value);
    }, "Please enter a valid phone number");

    $.validator.addClassRules('customphone', {
    customphone: true
    });

    $(document).ready(function(){
        // jQuery.validator.addMethod("lettersonly", function(value, element) {
        // return this.optional(element) || /^[a-z]+$/i.test(value);}, "Letters only please");
    $('#frm_edit_page').validate({
                                      rules: {
                                        gender: {
                                          required: true
                                        }
                                      }
                                    })

    });

    $('#btn_add_front_page').click(function(){
            tinyMCE.triggerSave();
        });

</script>
<!-- Script for Image validation -->
<script type="text/javascript">
    $(document).ready(function() 
    {
        var e = document.getElementById("profile_image"),
        r = $("#default-image").val();
        $(e).change(function() 
        {
            if (e.files && e.files[0]) 
            { 
                var a = e.files,
                t = a[0].name.substring(a[0].name.lastIndexOf(".") + 1),
                n = new FileReader;
                if ("JPEG" != t && "jpeg" != t && "jpg" != t && "JPG" != t && "png" != t && "PNG" != t) 
                    return showAlert("Sorry, " + a[0].name + " is invalid, allowed extensions are: jpeg , jpg , png", "error"),$("#profile_image").val(""), $(".fileupload-preview").attr("src", r), !1;
                if (a[0].size > 2e6) return showAlert("Sorry, " + a[0].name + " is invalid, Image size should be upto 2 MB only", "error"), $("#profile_image").val(""), $(".fileupload-preview").attr("src", r), !1;
                // n.onload = function(e) 
                // {
                //  var a = new Image;
                //  a.src = e.target.result, a.onload = function() 
                //  { 
                //      var e = this.height,
                //      a = this.width;
                //      if (e < 1000 || a < 1000) 
                //          return showAlert("Sorry,Please upload image with Height and Width greater than or equal to 1000 X 1000 for best result", "error"), $("#profile_image").val(""), $(".fileupload-preview").attr("src", r), !1
                //  }, $(".fileupload-preview").attr("src", e.target.result)
                // }, n.readAsDataURL(e.files[0])
            }
        })
    
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
            if(ward_id!='')
            { 

                var url = '{{$module_url_path}}/get_booths';
                var csrf_token      = '{{csrf_token()}}';

                $.ajax({
                    type:'POST',
                    url: url,
                    data:{ward_id:ward_id,_token:'{{csrf_token()}}'},
                    
                    success:function(resp){
                        $('#booth').html(resp);
                    }
                });
            }
        });

        $('#booth').change(function(){
            var booth_id = $('#booth').val();
            var ward_id = $('#ward').val();
            if(ward_id!='' && booth_id!='')
            { 
                var url = '{{$module_url_path}}/get_list';
                var csrf_token      = '{{csrf_token()}}';
                $.ajax({
                    type:'POST',
                    url: url,
                    data:{ward_id:ward_id,booth_id:booth_id,_token:'{{csrf_token()}}'},
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
    // $('[data-type="adhaar-number"]').keyup(function() {
    //   var value = $(this).val();
    //   value = value.replace(/\D/g, "").split(/(?:([\d]{4}))/g).filter(s => s.length > 0).join("-");
    //   $(this).val(value);
    // });

    // $('[data-type="adhaar-number"]').on("change, blur", function() {
    //   var value = $(this).val();
    //   var maxLength = $(this).attr("maxLength");
    //   if (value.length != maxLength) {
    //     $(this).addClass("highlight-error");
    //   } else {
    //     $(this).removeClass("highlight-error");
    //   }
    // });



</script>

@endsection


