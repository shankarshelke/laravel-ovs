@extends('front.layout.master')
@section('main_content')
<style type="text/css">
    #profile_image-error{
        color:red;
        width: 100%;
    }
</style>
<div class="header-index white-bg-header after-login-header" id="header-home"></div>
<section class="middle-section-main">
    <div class="clearfix"></div>
    <div class="container">
    <div class="admin-bank-box" >
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12" >      
            <div class="account-status-blog bank-off">   
                    <div class="account-status-txt">Subscribe for Newsletter </div>
                     <div class="add-activ-butto">
                    <label class="switch">
                        <input type="checkbox" id="checkbox" name="checkbox"  value="yes" @if($obj_newsletter > 0) checked @endif>
                        <span id="slider" class="slider round">
                        </span>
                    </label>
                </div>
            </div>
            </div>
        </div>
    </div>
        <form class="form-horizontal" id="frm_aircraft_owner" name="frm_aircraft_owner" action="{{url('/')}}/user/update_user" method="post" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="form-content">
                @include('front.layout.operation_status')

             <!--    <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12">
                        
                        <div class="form-group" style="float: right;">
                        <label>Subscribe to newsletter</label>
                            <label class="switch">
                                <input type="checkbox" id="checkbox" name="checkbox"  value="yes" @if($obj_newsletter > 0) checked @endif>
                                <span id="slider" class="slider round">
                                </span>
                            </label>
                        </div>
                    </div>
                </div> -->
                <div class="fileupload fileupload-new" data-provides="fileupload">
                    @php $is_profile_image_required = $prev_image_url = ""; @endphp
                    <div class="pro-img">
                        @if(isset($obj_data['profile_image']) && !empty($obj_data['profile_image']) && File::exists($user_profile_base_img_path.$obj_data['profile_image']))
                        <img src="{{$user_profile_public_img_path.$obj_data['profile_image']}}" id ='img-preview'  class="fileupload-preview  img-preview" alt=""/>
                        @php 
                        $prev_image_url = $user_profile_public_img_path.$obj_data['profile_image']; 
                        $is_profile_image_required = false; 
                        @endphp
                        @else
                        <img src="{{url('/').'/uploads/admin/default_image/default-profile.png' }}"  style="max-width: 100%; line-height: 20px;" class="fileupload-preview">
                        @php 
                        $is_profile_image_required = true;
                        $prev_image_url = url('/').'/uploads/admin/default_image/default-profile.png';
                        @endphp
                        @endif

                    </div>

                    <div class="change-profile-pic-btn">
                        <input style="height: 100%; width: 100%; z-index: 99;" data-validation-allowing="jpg, png, gif" id="profile_image" name="profile_image"  type="file" class="attachment_upload validate-image"  onchange="Changefilename(this)">
                        <input type="hidden" name="oldimage" id="oldimage" 
                        value="{{ $obj_data['profile_image']  or ''}}"/>
                        <input type="hidden" name="prev_image_url" id="prev_image_url"  
                        value="{{$prev_image_url or ''}}"/>
                        Change Profile Photo
                    </div>
                    <span class="error">{{ $errors->first('profile_image') }} </span>
                    
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>{{ trans('general.first_name') }} <span style="color: red">*</span></label>
                            <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Enter your First Name" data-rule-required="true" value="{{$obj_data['first_name'] or ''}}" data-rule-maxlength="200" tabindex="1">
                            <span class="error">{{ $errors->first('first_name') }} </span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>{{ trans('general.last_name') }} <span style="color: red">*</span></label>
                            <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Enter your Last Name" data-rule-required="true" data-rule-maxlength="200" tabindex="1" value="{{$obj_data['last_name'] or ''}}" >
                            <span class="error">{{ $errors->first('last_name') }} </span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>{{ trans('general.email') }} </label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Enter your Email"  data-rule-required="true" data-rule-maxlength="200" tabindex="1" onchange="" readonly="true" value="{{$obj_data['email'] or ''}}">
                            <span id="message" class="error">{{ $errors->first('email') }} </span>
                        </div>                    
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>{{ trans('general.phone_number') }} <span style="color: red">*</span></label>
                            <input type="text" name="contact" id="contact" class="form-control" placeholder="Enter your Contact" data-rule-required="true" data-rule-maxlength="200" data-rule-number="true" tabindex="1" value="{{$obj_data['mobile_number'] or ''}}" >
                            <span class="error">{{ $errors->first('mobile_number') }} </span>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <label>{{ trans('general.address') }} <span style="color: red">*</span></label>
                            <input type="text" name="address" id="address" class="form-control" placeholder="Enter your Address" data-rule-required="true" data-rule-maxlength="200" tabindex="1" value="{{$obj_data['address'] or ''}}" >
                            <span class="error">{{ $errors->first('address') }} </span>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>{{ trans('general.latitude') }} <span style="color: red">*</span></label>
                            <input type="text" name="latitude" id="latitude" class="form-control" placeholder="Latitude" data-rule-required="true" data-rule-maxlength="150" tabindex="1" data-rule-number="true" value="{{$obj_data['latitude'] or ''}}" >
                            <span class="error">{{ $errors->first('latitude') }} </span>
                        </div>
                    </div> 

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>{{ trans('general.longitude') }} <span style="color: red">*</span></label>
                            <input type="text" name="longitude" id="longitude" class="form-control" data-rule-number="true" placeholder="Longitude" data-rule-required="true" data-rule-maxlength="150" tabindex="1" value="{{$obj_data['longitude'] or ''}}">
                            <span class="error">{{ $errors->first('longitude') }} </span>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>{{ trans('general.company_name') }} </span></label>
                            <input type="text" name="company_details" id="company_details" class="form-control" rows="15" tabindex="1" value="{{ $obj_data['company_details'] or '' }}">
                            <span class="error">{{ $errors->first('company_details') }} </span>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>User ID </span></label>
                            <input type="text" name="user_id" id="user_id" class="form-control" rows="15" tabindex="1" value="{{ $obj_data['user_id'] or '' }}" readonly="">
                            <span class="error">{{ $errors->first('user_id') }} </span>
                        </div>
                    </div>

                   <!--  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <a class="update-profile-button" data-toggle="modal" data-target="#myModal" id="review_modal" ><div class="full-orng-btn sim-button" style="float:left;margin: 0px; padding: 7px;width: 182px; height: 40px;font-family: sans-serif">Request for change</div></a>
                    </div> -->

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12">
                        <div class="update-profile-button">
                            <button>{{ trans('general.update_profile') }}</button>
                        </div>                 
                    </div>
                </div>
            </form>                
            <form class="form-horizontal" id="set_password" name="set_password" action="{{url('/')}}/user/update_password" method="post" enctype="multipart/form-data">  
                {{csrf_field()}}
                <div class="change-password-label-section">
                    {{ trans('general.change') }} {{ trans('general.password') }}
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>{{ trans('general.current') }} {{ trans('general.password') }} <span style="color: red">*</span></label>
                            <input type="password" name="old_password" id="old_password" class="form-control" placeholder="Password" data-rule-required="true" data-rule-maxlength="200" >
                        </div>
                        <span class="error">{{ $errors->first('old_password') }} </span>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>{{ trans('general.new') }} {{ trans('general.password') }} <span style="color: red">*</span></label>
                            <input name="new_password" id="new_password" type="password" placeholder="Password" class="form-control" data-rule-required="true"  />
                        </div>
                        <span class="error">{{ $errors->first('new_password') }} </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-12 col-lg-6">
                        <div class="form-group">
                            <label>{{ trans('general.confirm') }} {{ trans('general.password') }} <span style="color: red">*</span></label>
                            <input name="confirm_password" id="confirm_password" type="password" placeholder="Password" class="form-control" data-rule-required="true"  />                            
                        </div>
                        <span id="message_password" class="error">{{ $errors->first('confirm_password') }} </span>
                    </div>
                </div>
                <div class="update-profile-button">
                    <button>{{ trans('general.change') }} {{ trans('general.password') }}</button>
                </div>
                <div class="clearfix"></div>
            </div>
        </form>
    </div>    
</section>
<div class="modal registration-modal give-feedback-form-main" id="myModal" data-backdrop="static">
    <div class="modal-dialog">
        <form class="form-horizontal" id="frm_request" name="frm_request" action="{{url('/')}}/user/send_request" method="post" enctype="multipart/form-data">
            {{csrf_field()}}
          
            <div class="modal-content">
                <div class="close-icon" data-dismiss="modal"><img src="{{ url('/') }}/front_assets/images/close-img.png" alt="" /></div>
                <div class="modal-body">
                    <div class="give-feedback-form request-quote-main booking-pending booking-completed">
                        
                        <div class="row">
                            <div class="col-sm-6 col-md-6 col-lg-12">
                                    
                                    <div class="form-group">
                                        <label>{{ trans('general.first_name') }} <span style="color: red">*</span> </label>
                                        <input type="text" name="req_first_name" id="req_first_name" class="form-control" placeholder="Enter your First Name" data-rule-required="true" data-rule-pattern="^[a-z A-Z]+$" value="{{$obj_data['first_name'] or ''}}" data-rule-maxlength="200" tabindex="1"  >
                                        <span class="error">{{ $errors->first('req_first_name') }} </span>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ trans('general.last_name') }}<span style="color: red">*</span> </label>
                                        <input type="text" name="req_last_name" id="req_last_name" class="form-control" placeholder="Enter your Last Name" data-rule-required="true" data-rule-maxlength="200" tabindex="1" value="{{$obj_data['last_name'] or ''}}" >
                                        <span class="error">{{ $errors->first('req_last_name') }} </span>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ trans('general.email') }} <span style="color: red">*</span></label>
                                        <input type="email" name="req_email" id="req_email" class="form-control" placeholder="Enter your Email"  data-rule-required="true" data-rule-maxlength="200" tabindex="1"  value="{{$obj_data['email'] or ''}}">
                                        <span id="message" class="error">{{ $errors->first('req_email') }} </span>
                                    </div>   
                            </div>
                        </div>
                        <div class="feedback-review">
                            <div class="button-quote request-button main-button1">
                                <div class="accept reject"><button class="full-orng-btn sim-button" data-dismiss="modal">Close</button></div>
                                <div class="accept reject"><button id="submit" type="submit" class="full-orng-btn sim-button">Submit</button></div>
                            </div>                                                       
                        </div>
                        
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="{{ url('/') }}/web_admin/assets/js/pages/jquery.geocomplete.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3&key={{ get_google_map_api_key() }}&libraries=places"></script>
<script type="text/javascript">


    $('#slider').click(function(){

        var val = $('#checkbox:checked').val();
        $.ajax({
            url : "{{ url('/').'/user/newsletter' }}",
            type : "POST",
            headers : {'X-CSRF-Token': "{{ csrf_token() }}" },
            data: { checkbox: val}, 
            success : function(resp)
            {
                if(resp.status == 'fail'){
                    swal({
                        //type: 'error',
                        title: 'Oops...',
                        text: resp.msg,
                    });
                }
                if(resp.status == 'success'){
                    swal({
                        //type: 'error',
                        title: 'Success',
                        text: resp.msg,
                    });    
                }
            }
        });
    });

    $(function () 
    {  
        $("#address").geocomplete({
            details: ".geo-details",
            detailsAttribute: "data-geo"
        }).bind("geocode:result", function (event, result){                       
            $("#latitude").val(result.geometry.location.lat());
            $("#longitude").val(result.geometry.location.lng());
            /*$("#city").val(result.geometry.location.city());*/
            var searchAddressComponents = result.address_components,
            searchPostalCode="";
        });
    });
    $(document).ready(function()
    {
        $('#frm_aircraft_owner').validate();
        $('#frm_request').validate();
    });
        
   
    $(document).ready(function()
    {
        $('#set_password').validate({
            rules : {
                new_password : {
                    minlength : 5,
                    required: true,
                },
                confirm_password : {
                    minlength : 5,
                    required: true,
                    equalTo : "#new_password"
                }
            }
        });
    });

    function Changefilename(event){
        var file = event.files;
        name = file[0].name;
        var ret = validateDocument(file,'Doc',null);
        $(event).next().children('input').val(name);

        if(!ret){
            $("#profile_image").val('');
        }
    }

    function validateDocument(files,type,element_id) 
    {
        if (typeof files !== "undefined") 
        {
            for (var i=0, l=files.length; i<l; i++) 
            {
                var blnValid = false;
                var ext = files[i]['name'].substring(files[i]['name'].lastIndexOf('.') + 1);
                if(type=='Doc')
                {
                    if(ext=='jpg' || ext=='png' || ext=='jpeg')
                    {
                        blnValid = true;
                    }  
                }
                else
                {
                    if(ext=='jpg' || ext=='png' || ext=='jpeg')
                    {
                        blnValid = true;
                    }
                }

                if(blnValid ==false) 
                {
                    if(type=='Doc')
                    {
                        showAlert("Sorry, " + files[0]['name'] + " is invalid, allowed extensions are: jpg or png or jpeg","error");
                    }
                    else
                    {
                        showAlert("Sorry, " + files[0]['name'] + " is invalid, allowed extensions are: jpg or png or jpeg","error");
                    }
                    return false;
                }
                else
                {              
                    if(type=='Doc')
                    {
                        if(files[0].size>10485760)
                        {
                            showAlert("File size should be less than 10 MB","error");
                        }
                    }       
                }                
            }
        }
        else
        {
            showAlert("No support for the File API in this web browser" ,"error");
        }
        return true;
    }
    $(document).ready(function() {
        var brand = document.getElementById('profile_image');
        brand.className = 'attachment_upload';
        brand.onchange = function() {
               /// document.getElementById('fakeUploadLogo').value = this.value.substring(12);
           };

            // Source: http://stackoverflow.com/a/4459419/6396981
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                          $('.fileupload-preview').attr('src', e.target.result);
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }
            $("#profile_image").change(function() {
                readURL(this);
            });
        });

    </script>
    @endsection