@extends('front.layout.master')
@section('main_content')
<style type="text/css">
    .form-group i
    {
        position: unset;
        font-size: 11px !important;  
    }

</style>
<div class="login-main-section signup-main-section">

    <div class="container">
          <div id="pageloader"><img src="{{url('/')}}/front_assets/images/material.gif" alt="processing..." /></div>
        <div class="signup-block-wrapper">
            <div id="operationStatus"></div>
            @include('front.layout.operation_status')
            <div class="signup-block">
                <div class="login-form-logo-img">
                    <img src="{{url('/')}}/front_assets/images/login-form-logo-img.png" alt="" />
                </div>
                <h2>{{ trans('general.signup') }}</h2>   
                <h1 class="what-type-user-head">{{ trans('general.what_type_of_user_you_are') }}?</h1>                 
                <form class="form-horizontal" id="frm_aircraft_owner" name="frm_aircraft_owner" action="{{url('/')}}/process_signup_operator" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="type-of-user-button">
                        <a class="signup-user-btn" href="{{url('/')}}/signup_user"><img src="{{url('/')}}/front_assets/images/signup-user-icon.png" alt="" /> {{ trans('general.user') }}</a>
                        <a class="signup-user-btn signup-aircraft-operater-btn active" href="{{url('/')}}/signup_operator"><img src="{{url('/')}}/front_assets/images/signup-aircraft-operator-icon-img.png" alt="" /> {{ trans('general.aircraft_operator') }}</a>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>{{ trans('general.first_name') }}</label>
                                <input type="text" name="first_name" id="first_name" class="form-control" placeholder="{{ trans('general.enter_your') }} {{ trans('general.first_name') }}" data-rule-required="true" data-rule-maxlength="200" tabindex="1" >
                                <span class="error" id="error-first_name">{{ $errors->first('first_name') }} </span>
                            </div>    
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>{{ trans('general.last_name') }}</label>
                                <input type="text" name="last_name" id="last_name" class="form-control" placeholder="{{ trans('general.enter_your') }} {{ trans('general.last_name') }}" data-rule-required="true" data-rule-maxlength="200" tabindex="1" >
                                <span class="error" id="error-last_name">{{ $errors->first('last_name') }} </span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>{{ trans('general.email') }}</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="{{ trans('general.enter_your') }} {{ trans('general.email') }}"  data-rule-required="true" data-rule-maxlength="200" tabindex="1" onchange="">
                                <span id="error-email" class="error">{{ $errors->first('email') }} </span>
                            </div>    
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>{{ trans('general.company_name') }}</label>
                                <input type="text" name="company_name" id="company_name" class="form-control" placeholder="{{ trans('general.enter_your') }} {{ trans('general.company_name') }}"  data-rule-required="true" data-rule-maxlength="200" tabindex="1" onchange="">
                                <span id="error-company_name" class="error">{{ $errors->first('company_name') }} </span>
                            </div>    
                        </div>
                    </div>
                    
                    <div id="captcha_container"></div>
                    <div class="error-red error" id="captcha_error_div" style="color: #ff1717; font-size: 11px; float: left;"></div>
                    <div class="clearfix"></div>

                    <div class="terms-block text-left signup-terms-section">
                        <div class="check-block">
                            <input id="filled-in-box" name="filled-in-box" class="filled-in" data-rule-required="true" type="checkbox">
                            <label for="filled-in-box">{{ trans('general.please_confirm_that_you_agree_our') }} <a href="{{url('/')}}/terms_conditions">{{ trans('general.terms_and_conditions') }}</a></label>
                        </div>
                        <div class="clearfix"></div>
                        <label class="error" style="color: #ff0000 !important; font-size: 11px;" for="filled-in-box"></label>
                        <span class="error">{{ $errors->first('filled-in-box') }} </span>
                    </div>
                    <button type="submit" class="full-orng-btn sim-button">{{ trans('general.signup') }}</button>
                    <div class="join-block">                            
                        <h5>{{ trans('general.have_an_account') }} ? <a href="{{url('/')}}/sign_in">{{ trans('general.signin') }}</a></h5>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>  

<!-- The Modal -->
    <div class="modal registration_success" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="close-icon">
                    <img src="{{url('/')}}/front_assets/images/close-img.png" class="close" alt="" data-dismiss="modal" />
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="image-icon">
                        <img src="{{url('/')}}/front_assets/images/tick-registration.png" alt="" />
                    </div>
                    <div class="forgot-pwd">Thanks For Registration</div>
                    <br>
                    <div class="forgot-pwd-content" style="padding: 0 30px 0 30px;">Please check your email to set the account password and to verify your email.</div>
                    <br>
                    <br>
                    <div class="forgot-pwd-content">Didn't Received the Email?</div>
                    <button type="button" id="resend_registration_email" class="full-orng-btn sim-button">Resend Email</button>

                </div>
            </div>
        </div>
    </div>


<script src="https://www.google.com/recaptcha/api.js?onload=loadCaptcha&render=explicit" async defer></script>
<script src="{{ url('/') }}/front_assets/js/jquery.form.min.js"></script>
<script type="text/javascript">

    var onloadCallback = function() {
        //alert("grecaptcha is ready!");
    };

    var allowSubmit = false;
    function capcha_filled () {
        allowSubmit = true;
    }
    function capcha_expired () {
        allowSubmit = false;
    }

    /*function check_if_capcha_is_filled (e)
    {
        if(allowSubmit) return true;
        e.preventDefault();
        $('#captcha_error_div').html("Fill in the capcha!")
    }*/

</script>
<script type="text/javascript">      

    /*$(document).ready(function()
    {
        $('#frm_aircraft_owner').validate();
    });*/

    $(document).ready(function()
    {
        $('#pdffile').change(function(){
            $('#subfile').val($(this).val());
        });

     /*   $('#frm_aircraft_owner').submit(function(e)
        {
            if(!allowSubmit){
                $('#captcha_error_div').html("Fill in the capcha!");
                return false;
            }
        });*/

        $('#pdffile').change(function(){
            $('#subfile').val($(this).val());
        });

        $('#showHidden').click(function(){
            $('#pdffile').css('visibilty','visible');
        });

        $('#pdffile1').change(function(){
            $('#subfile1').val($(this).val());
        });

        $('#showHidden1').click(function(){
            $('#pdffile1').css('visibilty','visible');
        });

    });

    function Changefilename(event){
        var file = event.files;
        name = file[0].name;
        var ret = validateDocument(file,'Doc',null);
        if(ret){
            $(event).next().children('input').val(name);
        }else{
            $('#pdffile').val('');
            $('.alias_file').val('');
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
                    if(ext=='pdf' || ext=='docx')
                    {
                        blnValid = true;
                    }  
                }
                else
                {
                    if(ext=='pdf' || ext=='docx')
                    {
                        blnValid = true;
                    }
                }

                if(blnValid ==false) 
                {
                    if(type=='Doc')
                    {
                        showAlert("Sorry, " + files[0]['name'] + " is invalid, allowed extensions are: pdf or docx","error");
                    }
                    else
                    {
                        showAlert("Sorry, " + files[0]['name'] + " is invalid, allowed extensions are: pdf or docx","error");
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

    $(document).ready(function(){

        $.validator.addMethod('nofreeemail', function (value) { 
            return /^([\w-.]+@(?!gmail\.com)(?!yahoo\.com)(?!hotmail\.com)([\w-]+.)+[\w-]{2,4})?$/.test(value); 
        }, 'Free email addresses are not allowed.');

        $('#frm_aircraft_owner').validate({
            rules: {
                email: {
                    required: true,
                    email: true,
                    nofreeemail: true,
                    remote: {
                        url:'{{url('/')}}/check_email',
                        type : "POST",
                        headers     : {'X-CSRF-Token': $('input[name="_token"]').val()},
                    }
                },
            },
            messages : 
            {
                email : {
                    nofreeemail: "Please use your business email only.",
                    remote: "This email has already been taken.",
                },
            },
        });

    });
    
    $('#frm_aircraft_owner').submit(function(e)
    {
      e.preventDefault();
         /* if(!allowSubmit){
            $('#captcha_error_div').html("Fill in the capcha!");
            return false;
        }else */if($('#frm_aircraft_owner').valid())
        {
            $("#pageloader").fadeIn();
            $("#frm_aircraft_owner").ajaxSubmit({
                dataType  : 'json',
                success :function(data, statusText, xhr, wrapper)
                {   
                    $("#pageloader").fadeOut();
                    $('.error').html('');
    
                    if(data.status == 'fail')
                    {
                        var errorsHtml = '';
                        if(Object.entries(data.errors).length > 0){
                            $.each(data.errors, function( key, value ) {
                                errorsHtml = $('#error-'+key).html(value[0]);
                            });
                        }
                        if(data.customMsg != '' && data.customMsg != undefined ){
                            $("#operationStatus").html('<div class="alert alert-danger no-border"><span class="text-semibold">Error!</span> '+data.customMsg+'<a href="#" class="alert-link"></a></div>');
                                swal('Oops!',data.customMsg,'error');
                        }
                    }
                    if(data.status == 'success')
                    {
                        if(data.customMsg!='' && data.customMsg != undefined ){
                            $("#operationStatus").html('<div class="alert alert-success no-border"><span class="text-semibold">Success!</span> '+data.customMsg+'<a href="#" class="alert-link"></a></div>');
                        }
                        $('#frm_aircraft_owner').trigger("reset");
                        $("#pageloader").fadeOut();
                        $("#myModal").modal('show');
                    }
                },
                error  : function(data, statusText, xhr, wrapper)
                {
                    $("#pageloader").fadeOut();
                    if(data.customMsg!='' && data.customMsg != undefined ){
                        $("#operationStatus").html('<div class="alert alert-danger no-border"><span class="text-semibold">Error!</span> '+data.customMsg+'<a href="#" class="alert-link"></a></div>'); 
                    }else{
                        $("#operationStatus").html('<div class="alert alert-danger no-border"><span class="text-semibold">Error!</span> Something went Wrong.<a href="#" class="alert-link"></a></div>');
                    }
                }
            });
        }

    });

    /* Resend Registration Email Code */

    $('#resend_registration_email').click(function()
    {
        $.ajax({
            url : "{{ url('/').'/operator_resend_resgistraion_mail' }}",
            type : "POST",
            headers : {'X-CSRF-Token': "{{ csrf_token() }}" },
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
                        title: 'Success...',
                        text: resp.msg,
                    });    
                }
            },
            error : function(){
                swal({
                    //type: 'error',
                    title: 'Oops...',
                    text: 'Error occured while sending email.',
                });
            }
        });
    });

</script>
@endsection