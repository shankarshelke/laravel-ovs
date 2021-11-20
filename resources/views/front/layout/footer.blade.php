<?php
    $arr_user = [];
if(Auth::guard('operator')->check()){
    $arr_user = Auth::guard('operator')->user()->toArray();

}elseif(Auth::guard('users')->check()){
    $arr_user = Auth::guard('users')->user()->toArray();
}

?>
<div class="footer-section">
    @if( Request::segment(1) != 'operator' && Request::segment(1) != 'user' )
    <div class="container footer-menu-section-main">
        <div class="row">
            <div class="col-sm-12 col-md-3 col-lg-3">
                <div class="footer-logo-social-section">
                    <div class="footer-logo-section">
                        <a href="index.html"><img src="{{url('/')}}/front_assets/images/logo-footer.png" alt="footer-logo" /></a>
                        <div class="footer-logo-content">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam ultricies blandit magna, at finibus Aliquam ultricies blandit magna,bus blandit magna... 
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-5 col-lg-5">
                <div class="footer-menu">
                   {{ trans('general.quick_link') }} <span><i class="fa fa-angle-down"></i> </span>
                </div>
                <div class="menu_name points-footer">
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <ul>
                                <li><a href="{{ url('/').'/privacy_policy' }}">{{ trans('general.privacy_policy') }} </a></li>
                                <li><a href="{{ url('/').'/terms_conditions' }}">{{ trans('general.terms_and_conditions') }}</a></li>
                                <li><a href="{{ url('/').'/guidelines' }}">{{ trans('general.guidelines') }}</a></li>
                                <li><a href="{{ url('/').'/blogs' }}">{{ trans('general.blogs') }}</a></li>                                              
                            </ul>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <ul>
                                <li><a href="{{ url('/').'/investor_information' }}">{{ trans('general.investor_information') }}</a></li>
                                <li><a href="{{ url('/').'/legal_terms' }}">{{ trans('general.legal_terms') }}</a></li>
                                <li><a href="{{ url('/').'/site_map' }}">{{ trans('general.site_map') }}</a></li>
                                @if(empty($arr_user))
                                <li><a href="{{ url('/').'/contact_us' }}">{{ trans('general.contact_us') }}</a></li>      
                                @endif                    
                            </ul>
                        </div>
                    </div>                    
                </div>                            
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4">
                <div class="footer-menu">
                    {{ trans('general.newsletter') }} <span><i class="fa fa-angle-down"></i></span>
                </div>
                <div class="menu_name points-footer">
                    <div class="newsletter-input">
                     <!-- <input type="text" name="newsletter" placeholder="Subscribe"> -->
                      <input type="email" placeholder="Enter your email address" name="subscription_email"  id="subscription_email">
                     <div class="form-group"><span class="error" id="error_subscription_email"></span></div>
                      <button id="subscription"  class="newsletter-submit-btn"><i class="fa fa-paper-plane"></i></button>
                    </div>
                </div>
                <div class="footer-menu">
                    {{ trans('general.join_us') }} <span><i class="fa fa-angle-down"></i></span>
                </div>
                <div class="menu_name points-footer">
                    <div class="social-links-section">
                        <ul>
                            <li class="facebook-link-section">
                                <a href="{{ $arr_global_site_setting['fb_url'] or '' }}" target="_blank"><i class="fa fa-facebook"></i></a>
                            </li>

                            <li class="twitter-link-section">
                                <a href="{{ $arr_global_site_setting['twitter_url'] or ''}}" target="_blank"><i class="fa fa-twitter"></i></a>
                            </li>


                            <li class="pinterest-link-section">
                                <a href="{{ $arr_global_site_setting['linkedin_url'] or '' }}" target="_blank"><i class="fa fa-pinterest-p"></i>
                                </a>
                            </li>

                            <li class="google-link-section">
                                <a href="{{ $arr_global_site_setting['gmail_url'] or '' }}" target="_blank"><i class="fa fa-google-plus"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="footer-main">
        <div class="container">            
            <div class="copyright-txt">
                &copy; <span class="bold">Copyright 2019</span> <a href="{{url('/')}}/">AirCraft</a>. All rights reserved.
            </div>                
        </div>
    </div>
    <div style="position: fixed;top: 0;right: 0;text-indent: 0px;width:0;">
        Website Design and Developed By <a target="blank" href="http://www.webwingtechnologies.com"> Webwing Technologies </a>
    </div>
    <a class="cd-top hidden-xs hidden-sm" href="#0"><i class="fa fa-angle-up"></i> </a>
</div>

<script type="text/javascript" src="{{url('/')}}/front_assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="{{url('/')}}/front_assets/js/swiper.min.js"></script>
<script type="text/javascript" src="{{url('/')}}/front_assets/js/common.js"></script>
<script type="text/javascript" language="javascript" src="{{url('/')}}/front_assets/js/backtotop.js"></script>
<script type="text/javascript" language="javascript" src="{{url('/')}}/front_assets/js/sweetalert.min.js"></script>
<script type="text/javascript" language="javascript" src="{{url('/')}}/front_assets/js/sweetalert_msg.js"></script>
<script type="text/javascript">

    $('#subscription').on('click',function()
    {
        $('#error_subscription_email').html("");
        $('#error_aggrement').html("");
        var flag = 0;
        var subscription_email=($('#subscription_email').val()).trim();
        if(subscription_email=="")
        {
            $('#error_subscription_email').text("Please enter email!");
            flag = 1;
        } 
        else
        {
            var check_input=/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

            if(!subscription_email.match(check_input))
            {
                $('#error_subscription_email').html("Please enter valid email!");
                flag = 1;
            }
        }
       
     
        if(flag==0)
        {
            var token = "{{csrf_token()}}";
            var subscription_email=$('#subscription_email').val();
            $.ajax({
                headers:{'X-CSRF-Token': token},
                type:'POST',
                url:'{{ url('/') }}/newsletter',
                data:{subscription_email:subscription_email},
                success:function(resp){
                    if(resp.status == 'success'){
                        swal('',resp.message,'success');
                        $('#subscription_email').val('');
                    }else if(resp.status == 'error'){
                        $('#error_subscription_email').html(resp.message);
                        //swal('',email,'success');
                    }
                }
            })
        }

    });
</script>