@extends('front.layout.master')
@section('main_content')

<div class="login-main-section">
    <div class="container">
        <div class="signup-block-wrapper">
            @include('front.layout.operation_status')
            <div class="signup-block">
                <div class="login-form-logo-img">
                    <img src="{{url('/')}}/front_assets/images/login-form-logo-img.png" alt="" />
                </div>

                <h2>{{ trans('general.signin') }}</h2>

                <?php
                    $arr_user = [];
                    $full_name = $profile_url = '';
                    $logged_in_user = "";

                    if(Auth::guard('operator')->check()){
                        $logged_in_user = 'operator';
                        //$arr_user = $operator_details;
                        $profile_url = url('/').'/operator/profile';
                    }elseif(Auth::guard('users')->check()){
                        $logged_in_user = 'user';
                        //$arr_user = $user_details;
                        $profile_url = url('/').'/user/profile';
                    }
                ?>

                @if($logged_in_user != '')
                    <h3>You are already logged in</h3>
                    <a class="full-orng-btn sim-button" href="{{ $profile_url or ''}}">Go to Profile</a>
                @else
                <form class="form-horizontal" id="frm_aircraft_owner" name="frm_aircraft_owner" action="{{url('/')}}/validate_login" method="post" enctype="multipart/form-data">     {{csrf_field()}}
                        <input type="hidden" name="redirect_to" value="{{ (isset($_GET['redirect_to']) && $_GET['redirect_to'] != '') ? $_GET['redirect_to'] : '' }}">        
                    <div class="form-group">
                        <label>{{ trans('general.user_id') }}</label>
                        <input type="text" name="user_id" id="user_id" class="form-control" placeholder="{{ trans('general.enter_your') }} {{ trans('general.user_id') }}" data-rule-required="true" data-rule-maxlength="200" tabindex="1" value="{{isset($_COOKIE['remember_me_id'])?$_COOKIE['remember_me_id']:''}}" >
                    </div>
                    <span class="error">{{ $errors->first('user_id') }} </span>
                    <div class="form-group">
                        <label>{{ trans('general.password') }}</label>
                        <input name="password" id="password" type="password" placeholder="{{ trans('general.enter_your') }} {{ trans('general.password') }}" class="form-control" data-rule-required="true"  value="{{isset($_COOKIE['remember_me_password'])?$_COOKIE['remember_me_password']:''}}" tabindex="1" minlength="6" />                            
                        <!--<div class="error">this field is required</div>-->
                    </div>
                    <span class="error">{{ $errors->first('password') }} </span>
                    <div class="terms-block text-left">
                        <div class="check-block">
                            <input id="filled-in-box" name="remember_me" class="filled-in" type="checkbox" tabindex="1">
                            <label for="filled-in-box">{{ trans('general.remember_me') }}</label>
                        </div>
                        <!-- <div class="check-box login-remember-pass">
                         <p>
                            <input id="product-register-check-bx" class="filled-in" checked="checked" type="checkbox" name="remember_me">
                            <label for="product-register-check-bx">Remember Password</label>
                            </p>
                        </div> -->

                    <!-- <div id="captcha_container"></div>
                    <div class="error-red error" id="captcha_error_div" style="color: #ff1717; font-size: 11px; float: left;"></div> -->
                    <div class="clearfix"></div> 
                        <a class="forget-pwd" data-toggle="modal" data-target="#myModal">{{ trans('general.forgot_password') }} ?</a>
                        <div class="clearfix"></div>
                    </div>
                    <button id="button" type="submit" class="full-orng-btn sim-button">{{ trans('general.signin') }}</button>
                    <div class="join-block">                            
                        <h5>{{ trans('general.dont_have_an_account') }} ? <a href="{{url('/')}}/signup_user">{{ trans('general.signup') }}</a></h5>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- The Modal -->
<div class="modal forgot-pwd-modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="close-icon" data-dismiss="modal">
                <img src="{{url('/')}}/front_assets/images/close-img.png" alt="" />
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="image-icon">
                    <img src="{{url('/')}}/front_assets/images/icon.png" alt="" />
                </div>
                <div class="forgot-pwd">Forgot Password</div>
                <div class="forget-pwd-line"></div>
                <div class="forgot-pwd-content">Forgot Your Password?</div>
                <div class="forgot-pwd-content-email">Please enter your User Id to get reset link on your Email</div>
                <form class="form-horizontal" id="forget_password" name="forget_password" action="{{url('/')}}/reset_password" method="post" >   
                    {{csrf_field()}}       
                    <div class="form-group">
                        <label>User ID</label>
                        <input type="text" id="email" name="email" placeholder="User ID" data-rule-required="true"  />
                        <span class="error">{{ $errors->first('email') }} </span> 
                    </div>
                    <div class="retrieve-pwd-btn">
                        <button type="submit" class="button-retrieve-pwd"> Get Reset Link</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script src="https://www.google.com/recaptcha/api.js?onload=loadCaptcha&render=explicit" async defer></script> 

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

    $("#button").click(function(){
        /*if(!allowSubmit){
            $('#captcha_error_div').html("Fill in the capcha!");
            return false;
        }*/
    });

    $(document).ready(function(){
        $('#frm_aircraft_owner').validate()
    });
    $(document).ready(function(){
        $('#forget_password').validate();
    });
</script>

@endsection