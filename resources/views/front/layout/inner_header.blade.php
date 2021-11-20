{{-- {{ dd(Auth::guard('operator')->user()->first_name) }} --}}
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
    <script type="text/javascript" src="{{url('/')}}/front_assets/js/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="{{url('/')}}/front_assets/js/jquery.validate.min.js"></script>
    <!--common header footer script end-->
</head>

<body>
    <?php
        $arr_user = [];
        $full_name = $profile_url = $profile_path = '';
        if(Auth::guard('operator')->check()){
            $arr_user = $operator_details;
            $profile_url = url('/').'/operator/profile';
            $profile_path = url('/').'/operator/';
            $dashboard_url = url('/').'/operator/dashboard';
        }elseif(Auth::guard('users')->check()){
            $arr_user = $user_details;
            $profile_url = url('/').'/user/profile';
            $profile_path = url('/').'/user/';
            $dashboard_url = url('/').'/user/dashboard';
        }

        $full_name = isset($arr_user['first_name']) ? $arr_user['first_name'] : '' ;
        $full_name .= ' ' ;
        $full_name .= isset($arr_user['last_name']) ? $arr_user['last_name'] : '' ;
    ?>
    <div class="header-index white-bg-header after-login-header" id="header-home">
        <div id="main"></div>
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
                    <li class="menu-notification-icon currency-icon">
                        <a href="javascript:void(0)"><i class="fa fa-bell"></i><span class="notification-count-section">0</span></a>
                        <ul class="currency-menu">
                            
                            <li><a href="javascript:void(0)">
                              <p><i class="fa fa-bell"></i></p>
                              <div class="text-ntictn">Reservation</div> 
                              <div class="count-lis notifyCountAdmin">6</div>
                            </a></li>
                           <li><a href="javascript:void(0)">
                              <p><i class="fa fa-bell"></i></p>
                              <div class="text-ntictn">Transaction</div> 
                              <div class="count-lis notifyCountAdmin">8</div>
                            </a></li>
                            <li><a href="javascript:void(0)">
                              <p><i class="fa fa-bell"></i></p>
                              <div class="text-ntictn">General</div> 
                              <div class="count-lis notifyCountAdmin">10</div>
                            </a></li>
                        </ul>
                        
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
                   <!--  @if(\App::isLocale('cn'))
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
                    <li class="after-login-hide"><a href="{{ url('/') }}">Home</a></li>
                    <li class="after-login-hide"><a href="javascript:void(0)">Charterer <i class="fa fa-angle-down"></i></a></li>
                    <li class="after-login-hide"><a href="javascript:void(0)">Aircraft Operator <i class="fa fa-angle-down"></i></a></li>
                    <li class="after-login-hide"><a href="javascript:void(0)">About Us</a></li>
                    <li class="after-login-hide"><a href="javascript:void(0)">Contact Us</a></li>
                    <li class="after-login-show get-app-btn-section"><a href="https://play.google.com/store">Get the App</a></li>
                    @if(Auth::guard('operator')->check())
                    <li class="after-login-show get-app-btn-section">
                        <a href="{{ $profile_path.'aircrafts/add' }}">Add Aircraft</a>
                    </li>
                    @endif
                    @if(Auth::guard('users')->check())
                    <li class="after-login-show menu-notification-icon responsive-menu-hide currency-icon">
                        <a href="{{ $profile_path.'notifications' }}"><i class="fa fa-bell"></i><span class="notification-count-section"><?php echo getUserNotificationsCount() ?></span></a>
                        <ul class="currency-menu">
                        	
                            <li><a href="{{ url('/') }}/user/notifications?type=reservation">
                              <p><i class="fa fa-bell"></i></p>
                              <div class="text-ntictn">Reservation</div> 
                              <div class="count-lis notifyCountAdmin"><?php echo getreservationNotificationsCount('user') ?></div>
                            </a></li>
                           <li><a href="{{ url('/') }}/user/notifications?type=transaction">
                              <p><i class="fa fa-bell"></i></p>
                              <div class="text-ntictn">Transaction</div> 
                              <div class="count-lis notifyCountAdmin"><?php echo gettransactionNotificationsCount('user') ?></div>
                            </a></li>
                            <li><a href="{{ url('/') }}/user/notifications?type=general">
                              <p><i class="fa fa-bell"></i></p>
                              <div class="text-ntictn">General</div> 
                              <div class="count-lis notifyCountAdmin"><?php echo getgeneralNotificationsCount('user') ?></div>
                            </a></li>
                        </ul>
                    </li>
                    @endif
                    @if(Auth::guard('operator')->check())
                    <li class="after-login-show menu-notification-icon responsive-menu-hide currency-icon">
                        <a href="{{ $profile_path.'notifications' }}"><i class="fa fa-bell"></i><span class="notification-count-section"><?php echo getOperatorNotificationsCount() ?></span></a>
                        <ul class="currency-menu">
                            
                            <li><a href="{{ url('/') }}/operator/notifications?type=reservation">
                              <p><i class="fa fa-bell"></i></p>
                              <div class="text-ntictn">Reservation</div> 
                              <div class="count-lis notifyCountAdmin"><?php echo getreservationNotificationsCount('operator') ?></div>
                            </a></li>
                           <li><a href="{{ url('/') }}/operator/notifications?type=transaction">
                              <p><i class="fa fa-bell"></i></p>
                              <div class="text-ntictn">Transaction</div> 
                              <div class="count-lis notifyCountAdmin"><?php echo gettransactionNotificationsCount('operator') ?></div>
                            </a></li>
                            <li><a href="{{ url('/') }}/operator/notifications?type=general">
                              <p><i class="fa fa-bell"></i></p>
                              <div class="text-ntictn">General</div> 
                              <div class="count-lis notifyCountAdmin"><?php echo getgeneralNotificationsCount('operator') ?></div>
                            </a></li>
                        </ul>

                    </li>
                    @endif
                    <li class="after-login-show user-profile-name-menu responsive-menu-hide currency-icon">
                        <a href="{{ $profile_url }}">
                            <span class="user-icon-pro"><i class="fa fa-user-circle-o"></i></span> <span class="user-name">{{ ucwords($full_name) }}</span> <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="currency-menu">
                            <li><a href="{{ $dashboard_url or '' }}"> <i class="fa fa-home"></i> {{ trans('general.dashboard') }}</a></li>
                            <li><a href="{{ $profile_url or '' }}"> <i class="fa fa-pencil-square-o"></i> {{ trans('general.edit_profile') }}</a></li>
                            <li><a href="{{ url('/').'/logout' }}"> <i class="fa fa-sign-out"></i> {{ trans('general.sign_out') }}</a></li>
                        </ul>
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
               <!--      @if(\App::isLocale('cn'))
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
                    <li class="after-login-hide sign-in-button"><a href="login.html">{{ trans('general.signin') }}</a></li>
                </ul>
            </div>
        </div>
        <div class="blank-div"></div>
    </div>
    <div class="page-title-breadcurm-section">
        <div class="container">
            <div class="page-head-title-section">
               {{$module_title or ''}}
            </div>
            <div class="page-breadcurm-section">
                <a href="{{ url('/') }}">Home</a> &nbsp; > &nbsp;
                <a href="{{ $module_url_path or '' }}">
                @if(isset($sub_module_title) && !empty($sub_module_title))
                    {{$module_title or ''}}
                @else
                    <span>{{$module_title or ''}}</span>
                @endif
                </a>
                @if(isset($sub_module_title) && !empty($sub_module_title))
                 &nbsp; > &nbsp;
                <span>{{$sub_module_title or ''}}</span>
                @endif
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="inner-pages-menu-section">
        <div class="container">
            <div class="inner-pages-menu-head">
                <div class="inner-pages-menu-head-txt">Menu</div>
                <div class="inner-pages-menu-icon">&#9776;</div>
                <div class="clearfix"></div>
            </div>
            <ul class="inner-page-menu-ul">
                <li>
                    <a href="{{ $profile_url or ''}}" class="{{ Request::segment(2) == 'profile' ? 'active' : '' }}">{{ trans('general.manage_account') }}</a>
                </li>
                @if(Auth::guard('users')->check())
                <li>
                    <a href="{{ $profile_path.'requested_quotations/' }}" class="{{ Request::segment(2) == 'requested_quotations' ? 'active' : '' }}">Quotes under review</a>
                </li>
                @endif
                @if(Auth::guard('operator')->check())
                <li>
                    <a href="{{ $profile_path.'requested_quotations/' }}" class="{{ Request::segment(2) == 'requested_quotations' ? 'active' : '' }}">Quotes under review</a>
                </li>
                @endif

                <?php
                    $active = '';
                    if(Request::segment(2) == 'pending_bookings' || Request::segment(2) == 'completed_bookings' || Request::segment(2) == 'cancelled_bookings' || Request::segment(2)=='extend_contract'){
                        $active = 'active';
                    }
                ?>
                @if(Auth::guard('users')->check())
                <li class="dropdown-menu-main">
                    <a href="javascript:void(0)" class="{{ $active or '' }}">{{ trans('general.my_bookings') }} <i class="fa fa-angle-down"></i></a>
                    <ul class="dropdown-menus-ul">
                        <li><a href="{{ $profile_path.'pending_bookings' }}">{{ trans('general.pending_bookings') }}</a></li>
                        <li><a href="{{ $profile_path.'completed_bookings' }}">{{ trans('general.completed_bookings') }}</a></li>
                        <li><a href="{{ $profile_path.'cancelled_bookings' }}">{{ trans('general.cancelled_bookings') }}</a></li>
                        <li><a href="{{ $profile_path.'extend_contract' }}">{{ trans('general.extend_contract') }}</a></li>
                    </ul>
                </li>
                @endif
                @if(Auth::guard('operator')->check())
                <li class="dropdown-menu-main">
                    <a href="javascript:void(0)" class="{{ $active or '' }}">{{ trans('general.my_bookings') }} <i class="fa fa-angle-down"></i></a>
                    <ul class="dropdown-menus-ul">
                        <li>
                            <a href="{{ $profile_path.'pending_bookings' }}" class="{{ Request::segment(2) == 'pending_bookings' ? 'active' : '' }}">{{ trans('general.pending_bookings') }}</a>
                        </li>
                        <li>
                            <a href="{{ $profile_path.'completed_bookings' }}" class="{{ Request::segment(2) == 'completed_bookings' ? 'active' : '' }}">{{ trans('general.completed_bookings') }}</a>
                        </li>
                        <li>
                            <a href="{{ $profile_path.'cancelled_bookings' }}" class="{{ Request::segment(2) == 'cancelled_bookings' ? 'active' : '' }}">{{ trans('general.cancelled_bookings') }}</a>
                        </li>


                    </ul>
                </li>
                @endif
                @if(Auth::guard('operator')->check())
                <li>
                	<a class="{{ Request::segment(2) == 'aircrafts' ? 'active' : '' }}" href="{{ url('/').'/operator/aircrafts' }}">{{ trans('general.my_aircrafts') }}</a>
                </li>
                @endif
                <li><a href="javascript:void(0)">{{ trans('general.invite_a_friend') }}</a></li>

                 <?php
                    $active = '';
                    if(Request::segment(2) == 'bank_details' || Request::segment(2) == 'transactions' ){
                        $active = 'active';
                    }
                ?>
                 @if(Auth::guard('users')->check())
                <li class="dropdown-menu-main">
                    <a href="javascript:void(0)" class="{{ $active or '' }}">Transactions<i class="fa fa-angle-down"></i></a>
                    <ul class="dropdown-menus-ul">
                        <li>
                            <a href="{{ $profile_path.'bank_details' }} " class="{{ Request::segment(2) == 'bank_details' ? 'active' : '' }}">{{ trans('general.bank') }} {{ trans('general.details') }}</a>
                        </li>
                        <li>
                            <a href="{{ $profile_path.'transactions' }} " class="{{ Request::segment(2) == 'transactions' ? 'active' : '' }}">Transactions</a>
                        </li>
                    </ul>
                </li>
                @endif



                @if(Auth::guard('operator')->check())
                <li class="dropdown-menu-main">
                    <a href="javascript:void(0)" class="{{ $active or '' }}">Transactions<i class="fa fa-angle-down"></i></a>
                    <ul class="dropdown-menus-ul">
                        <li>
                            <a href="{{ $profile_path.'bank_details' }} " class="{{ Request::segment(2) == 'bank_details' ? 'active' : '' }}">{{ trans('general.bank') }} {{ trans('general.details') }}</a>
                        </li>
                        <li>
                            <a href="{{ $profile_path.'transactions' }} " class="{{ Request::segment(2) == 'transactions' ? 'active' : '' }}">Transactions</a>
                        </li>
                    </ul>
                </li>
                @endif


                            <!-- @if(Auth::guard('users')->check())
                                <li>
                                    <a href="{{ $profile_path.'bank_details' }} " class="{{ Request::segment(2) == 'bank_details' ? 'active' : '' }}">{{ trans('general.bank') }} {{ trans('general.details') }}</a>
                                </li>
                            @endif

                            @if(Auth::guard('operator')->check())
                                <li>
                                    <a href="{{ $profile_path.'bank_details' }} " class="{{ Request::segment(2) == 'bank_details' ? 'active' : '' }}">{{ trans('general.bank') }} {{ trans('general.details') }}</a>
                                </li>
                            @endif

                            @if(Auth::guard('users')->check())
                                <li>
                                    <a href="{{ $profile_path.'transactions' }} " class="{{ Request::segment(2) == 'transactions' ? 'active' : '' }}">Transactions</a>
                                </li>
                            @endif

                            @if(Auth::guard('operator')->check())
                                <li>
                                    <a href="{{ $profile_path.'transactions' }} " class="{{ Request::segment(2) == 'transactions' ? 'active' : '' }}">Transactions</a>
                                </li>
                            @endif -->


                @if(Auth::guard('users')->check())
                    <li>
                        <a href="{{ $profile_path.'reviews_and_ratings' }} " class="{{ Request::segment(2) == 'reviews_and_ratings' ? 'active' : '' }}">{{ trans('general.review') }} &amp; {{ trans('general.rating') }}</a>
                    </li>
                @endif

                @if(Auth::guard('operator')->check())
                    <li>
                        <a href="{{ $profile_path.'reviews_and_ratings' }}" class="{{ Request::segment(2) == 'reviews_and_ratings' ? 'active' : '' }}">{{ trans('general.review') }} &amp; {{ trans('general.rating') }}</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
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

            $(".inner-pages-menu-icon").on("click", function() {
                $(this).parent(".inner-pages-menu-head").siblings(".inner-page-menu-ul").slideToggle("slow");
            });
        })
    </script>