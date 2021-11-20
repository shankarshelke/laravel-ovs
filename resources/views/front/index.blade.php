@extends('front.layout.master')
@section('main_content')
<body>   
   {{--  {{dd(check_user_login('users'))}} --}}
    <div class="banner">                        
        <div class="banner-content-section">
            <div class="container">
                <div class="banner-content-head">
                    {{ trans('landing_page.banner_title') }}
                </div>
                <div class="banner-content-semihead">
                    {{ trans('landing_page.banner_sub_title') }}
                </div>
            </div>
        </div>
        <div class="down-arrow-section">
            <a href="#home" class="slide-to">
                <img src="{{url('/')}}/front_assets/images/banner-go-down-icon.png" alt="" />
            </a>
        </div>
    </div>
    <div class="instantaneously-section-main" id="home">
        <div class="container">
            <div class="instantaneously-head-section-main">
                 “Old Way” of Matching You with the Right Aircraft… <span>Instantaneously !</span>
            </div>
        </div> 
        <div class="instantaneously-slider-main">
            <div class="swiper-container slider-1">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="instantaneously-content-secton" style="background-image: url('front_assets/images/category-img-1.jpg');">
                            <div class="instantaneously-content-secton-txt">
                                <div class="instantaneously-content-block">
                                    <div class="green-three-dots">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                    <div class="instantaneously-content-head">
                                        Which Aircraft Type <br>&amp; Model?
                                    </div>
                                    <div class="instantaneously-content-text">
                                        Tell us what type of aircraft and if there is a specific model you need. We will match make you with an aircraft that is exactly what you ask for. No blind dates from us.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="instantaneously-content-secton" style="background-image: url('front_assets/images/category-img-2.jpg');">
                            <div class="instantaneously-content-secton-txt">
                                <div class="instantaneously-content-block">
                                    <div class="green-three-dots">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                    <div class="instantaneously-content-head">
                                        When do you need it?
                                    </div>
                                    <div class="instantaneously-content-text">
                                        It’s like asking someone for a date but in your case, we make sure your selected aircraft is there as expected. On time, all the time.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="instantaneously-content-secton" style="background-image: url('front_assets/images/category-img-3.jpg');">
                            <div class="instantaneously-content-secton-txt">
                                <div class="instantaneously-content-block">
                                    <div class="green-three-dots">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                    <div class="instantaneously-content-head">
                                        Where shall we meet?
                                    </div>
                                    <div class="instantaneously-content-text">
                                        Similarly, it is important we know where exactly to send your aircraft to meet you. Not only that, we will match make you with one that is nearest to you. No delay, no excuses!
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="instantaneously-content-secton" style="background-image: url('front_assets/images/category-img-4.jpg');">
                            <div class="instantaneously-content-secton-txt">
                                <div class="instantaneously-content-block">
                                    <div class="green-three-dots">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                    <div class="instantaneously-content-head">
                                        Are we all set?
                                    </div>
                                    <div class="instantaneously-content-text">
                                        Before you get ready to hop on, we want to make sure that everything is perfect for you. The right aircraft, date &amp; time, location and any other details are correct. Our aircraft match makers will write, call and list down everything clearly for your confirmation.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                
                </div>
                <!-- Add Pagination -->
                <div class="swiper-pagination"></div>            
            </div>              
        </div>
    </div>    
    <div class="jet-charter-main-section3">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-md-6 col-lg-6 section3-img-main div-hide-mobile">
                    <div class="section3-image">
                        <img src="{{url('/')}}/front_assets/images/section3-img.jpg" alt="" />
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6 section3-txt-main">
                    <div class="section3-content-main">
                        <div class="section3-content-head">
                            Jet Charter Costs &amp; Pricing Basics
                        </div>
                        <div class="section3-content-semihead">
                            How much does a private jet charter cost?
                        </div>
                        <div class="section3-content-text">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam varius quis metus rhoncus porttitor. Vivamus a pulvinar nisi. Aliquam gravida blandit ex, vel convallis felis placerat non. Duis semper ex purus, vitae luctus libero pulvinar id. Suspendisse dui risus, rhoncus eget dignissim vitae, finibus non nunc.
                        </div>
                        <div class="get-started-now-btn">
                            <a href="{{ url('/') }}/listing" class="btn-get-started-now"> {{ trans('landing_page.get_started_now') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="become-owner-main-section">
        <div class="container">
            <div class="become-owner-head-section">
                Become the <span>Owner</span> of your Jet
            </div>
            <div class="become-owner-content-section">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam varius quis metus rhoncus porttitor. Vivamus a pulvinar nisi. Aliquam gravida blandit ex, vel convallis felis placerat non. Duis semper ex purus, vitae luctus libero pulvinar id. Suspendisse dui risus, rhoncus eget dignissim vitae, finibus non nunc. Mauris vitae egestas ante, at malesuada eros.
            </div>
            <div class="get-started-now-btn">
                @if(\Auth::guard('operator')->check())
                    <a href="{{ url('/') }}/operator/dashboard" class="btn-get-started-now mt30"> {{ trans('landing_page.become_an_owner') }}</a>
                @elseif(\Auth::guard('users')->check())
                @else
                    <a href="{{ url('/') }}/signup_operator" class="btn-get-started-now mt30">{{ trans('landing_page.become_an_owner') }}</a>
                @endif
            </div>
        </div>
        <div class="become-owner-img-section">
            <img src="{{url('/')}}/front_assets/images/become-owner-img.png" alt="" />
        </div>
    </div>    
    <div class="container">
        <div class="section5-main">
            <img src="{{url('/')}}/front_assets/images/section5-img-main.jpg" alt="" />
            <div class="section5-main-content-main">
                <div class="section5-main-content-head">
                    Are you ready to book your best charter flight yet?
                </div>
                <div class="section5-main-content-text">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam varius quis metus rhoncus porttitor. Vivamus a pulvinar nisi. Aliquam gravida blandit ex, vel convallis felis placerat non. Duis semper ex purus, vitae luctus libero pulvinar id. Suspendisse dui risus, rhoncus eget dignissim vitae, finibus non nunc. Mauris vitae egestas ante, at malesuada eros.
                </div>
            </div>
        </div>
    </div>    
    <div class="section6-main">
        <div class="container">
            <div class="video-play-btn-section">
                <a href="javascript:void(0)"><img src="{{url('/')}}/front_assets/images/video-play-button-img.png" alt="" /> </a>
            </div>
            <div class="find-out-way-head">
                Find out why we’re experts in air charter
            </div>
            <div class="find-out-way-content">
                Watch our corporate video to discover how Aircraft Rental can help you by providing a full range of aircraft charters 
            </div>
        </div>
    </div>    
</body>

@endsection