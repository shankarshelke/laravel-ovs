<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="" />
    <title>{{ config('app.project.name') }}</title>
    <!-- ======================================================================== -->
    <link rel="icon" type="{{url('/')}}/front_assets/image/png" sizes="16x16" href="{{url('/')}}/front_assets/images/favicon.ico">
    <!-- Bootstrap CSS -->
    <link href="{{url('/')}}/front_assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!--font-awesome-css-start-here-->
    <link href="{{url('/')}}/front_assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!--Custom Css-->
    <link href="{{url('/')}}/front_assets/css/aircraft.css" rel="stylesheet" type="text/css" />
    <link href="{{url('/')}}/front_assets/css/swiper.min.css" rel="stylesheet" type="text/css" />
    <link href="{{url('/')}}/front_assets/css/sweetalert.css" rel="stylesheet" type="text/css" />
    <link href="{{url('/')}}/front_assets/css/gallery.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="{{url('/')}}/front_assets/js/jquery-1.11.3.min.js"></script>    
     <script type="text/javascript" src="{{url('/')}}/front_assets/js/gallery.min.js"></script>    
    <script type="text/javascript" src="{{url('/')}}/front_assets/js/jquery.validate.min.js"></script>

    <script type="text/javascript">
        var allowSubmit = false;
        var captchaContainer = null;
        var loadCaptcha = function() {

            captchaContainer = grecaptcha.render('captcha_container', {
                'sitekey' : '6LfixpwUAAAAAGNiGCwdx0smalXedC2PGOrHakLz',
                'callback' : function(response) {
                    console.log(response);
                    allowSubmit = true;
                    console.log(allowSubmit);
                    $('#captcha_error_div').html('');
                }
            });
        };
    </script>

<?php
$full_name = '';
$arr_user = [];
if(Auth::guard('operator')->check()){
    $arr_user = Auth::guard('operator')->user()->toArray();

}elseif(Auth::guard('users')->check()){
    $arr_user = Auth::guard('users')->user()->toArray();
}

$full_name = isset($arr_user['first_name']) ? $arr_user['first_name'] : '' ;
$full_name .= ' ' ;
$full_name .= isset($arr_user['last_name']) ? $arr_user['last_name'] : '' ;

?>
<div id="main"></div>
<div id ="header_home" class="on-banner-header">
               
<div class="header header-home">    
    <div class="logo-block wow fadeInDown" data-wow-delay="0.2s">
        <a href="{{ url('/') }}">
            <img src="{{url('/')}}/front_assets/images/logo-footer.png" alt="" class="main-logo" />
        </a>
    </div>    
    <span class="menu-icon" onclick="openNav()">&#9776;</span>
    <!--Menu Start-->
    <div class="language-section-responsive">
        <ul>                
            <li class="menu-notification-icon">
                <a href="javascript:void(0)"><i class="fa fa-bell"></i><span class="notification-count-section">0</span></a>
            </li>
            <li class="currency-icon">
                <a href="javascript:void(0)">USD <i class="fa fa-angle-down"></i></a>
                <ul class="currency-menu">
                    <li><a href="#"> <i class="fa fa-usd"></i> USD</a></li>
                    <li><a href="#"> <i class="fa fa-eur"></i> Euro</a></li>
                </ul>
            </li>
          <!--   @if(\App::isLocale('cn'))
            <li class="language-section-main responsive-menu-hide">
                <a href="{{ url('/').'/lang/en' }}">
                    <img src="{{url('/')}}/front_assets/images/flag-eng.jpg" alt="" />EN
                </a>
            </li>
            @elseif(\App::isLocale('en'))
                <li class="language-section-main responsive-menu-hide">
                    <a href="{{ url('/').'/lang/cn' }}">
                        <img src="{{url('/')}}/front_assets/images/flag-cn.png" alt="" />CN
                    </a>
                </li>
            @endif -->
        </ul>
    </div>
    <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <div class="banner-img-block">
            <img src="{{url('/')}}/front_assets/images/logo-footer.png" alt="" />
        </div>
        <ul class="min-menu">
            <li class="after-login-hide">
                <a href="{{ url('/') }}">{{ trans('header.home') }}</a>
            </li>


            <li class="after-login-hide">
                <a href="{{url('/')}}/about_us">{{ trans('header.about_us') }}</a>
            </li>            

            @if(empty($arr_user))
            <li class="after-login-hide">
                <a href="{{url('/')}}/contact_us">{{ trans('header.contact_us') }}</a>
            </li> 
            @endif

            <li class="after-login-show get-app-btn-section">
                <a href="javascript:void(0)">{{ trans('header.get_the_app') }}</a>
            </li>

            <li class="after-login-show menu-notification-icon responsive-menu-hide">
                <a href="javascript:void(0)"><i class="fa fa-bell"></i><span class="notification-count-section">0</span></a>
            </li>

            <li class="after-login-show user-profile-name-menu">
                <a href="javascript:void(0)"><i class="far fa-user"></i> <span class="user-name">Emily Smabcd</span> <i class="fa fa-angle-down"></i></a>
            </li>

            <li class="responsive-menu-hide currency-icon"><a href="javascript:void(0)">{{ get_active_currency_title() }} <i class="fa fa-angle-down"></i></a>
                <ul class="currency-menu">
                    <li>
                        <a href="{{ url('/').'/change_currency/USD' }}"><i class="fa fa-usd"></i> USD</a>
                    </li>
                    <li>
                        <a href="{{ url('/').'/change_currency/EUR' }}"><i class="fa fa-eur"></i> Euro</a>
                    </li>
                </ul>
            </li>

   <!--          @if(\App::isLocale('cn'))
            <li class="language-section-main responsive-menu-hide">
                <a href="{{ url('/').'/lang/en' }}">
                    <img src="{{url('/')}}/front_assets/images/flag-eng.jpg" alt="" />EN
                </a>
            </li>
            @elseif(\App::isLocale('en'))
                <li class="language-section-main responsive-menu-hide">
                    <a href="{{ url('/').'/lang/cn' }}">
                        <img src="{{url('/')}}/front_assets/images/flag-cn.png" alt="" />CN
                    </a>
                </li>
            @endif -->
            
            @if(\Auth::guard('operator')->check())
            <li class="user-profile-name-menu">
                <a href="{{ url('/').'/operator/dashboard' }}">
                   <span class="user-icon-pro"><i class="fa fa-user-circle-o"></i></span>
                    <span class="user-name">{{ ucwords($full_name) }} </span>
                </a>
            </li>
            @endif

            @if(\Auth::guard('users')->check())
            <li class="user-profile-name-menu">
                <a href="{{ url('/').'/user/dashboard' }}">
                   <span class="user-icon-pro"><i class="fa fa-user-circle-o"></i></span>
                    <span class="user-name">{{ ucwords($full_name) }} </span>
                </a>
            </li>
            @endif

            @if(is_user_logged_in('users') || is_user_logged_in('operator'))
                <li class="after-login-hide  sign-in-button"><a href="{{url('/')}}/logout/">{{ trans('general.signout') }}</a></li>
            @else 
                <li class="after-login-hide sign-in-button"><a href="{{url('/')}}/sign_in">{{ trans('general.signin') }}</a></li>
            @endif
        </ul>
    </div>    
</div>
<div class="blank-div">
    
</div>
</div> 
    
{{-- </section> --}}
</head>
<!-- Min Top Menu Start Here  -->
<script type="text/javascript">    
    function openNav() {
        document.getElementById("mySidenav").style.width = "250px";
        $("body").css({
            "margin-left": "250px",
            "overflow-x": "hidden",
            "transition": "margin-left .5s",
            "position": "fixed"
        });
        $("#main").addClass("overlay");
    }
    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
        $("body").css({
            "margin-left": "0px",
            "transition": "margin-left .5s",
            "position": "relative"
        });
        $("#main").removeClass("overlay");
    }    
</script>
<!-- Min Top Menu Start End  -->
<script>
    $(document).ready(function() {
        var stickyNavTop = $('.header').offset().top;

        var stickyNav = function() {
            var scrollTop = $(window).scrollTop();

            if (scrollTop > stickyNavTop) {
                $('.header').addClass('sticky');
            } else {
                $('.header').removeClass('sticky');
            }
        };
        stickyNav();
        $(window).scroll(function() {
            stickyNav();
        });
    })
</script>