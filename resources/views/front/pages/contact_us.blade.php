@extends('front.layout.master')
@section('main_content')
<style type="text/css">
    .parsley-errors-list.filled {
        color: red;
    }

#pageloader
{
  background: rgba( 255, 255, 255, 0.8 );
  display: none;
  top: 0;
  left: 0;
  height: 100%;
  position: fixed;
  width: 100%;
  z-index: 9999;
}

#pageloader img
{
  left: 50%;
  margin-left: -32px;
  margin-top: -32px;
  position: absolute;
  top: 50%;
}
</style>
     <div class="page-head-section-main">
        <div class="container">
            <div class="term-content">
                Contact Us
            </div>
            <div class="condition-content">
                <a href="{{url('/')}}/">Home ></a> <span class="inline-content-color">Contact Us</span>
            </div>    
            <div class="clearfix"></div>
        </div>
    </div>  


    <!--Section start here-->
     <div class="container">
        <div class="contact-info-bx">
        @include('front.layout.operation_status')
            <div class="get-in-title"> {{ trans('general.get_in_touch') }}</div>
            <div class="touch-line"></div>
            <div class="contact-four-bx">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-3">
                        <div class="cont-mob-bx">
                            <div class="add-img-bx">
                                <span> <img src="{{url('/')}}/front_assets/images/cont-1.png" alt="" /> </span>
                                <p> {{ trans('general.address') }}</p>
                            </div>
                            <div class="add-cont-txt">{{isset($arr_global_site_setting['site_address']) ? $arr_global_site_setting['site_address'] :'N/A'}}</div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-6 col-lg-3">
                        <div class="cont-mob-bx">
                            <div class="add-img-bx">
                                <span> <img src="{{url('/')}}/front_assets/images/cont-2.png" alt="" /> </span>
                                <p>{{ trans('general.phone_number') }}</p>
                            </div>
                            <div class="add-cont-txt">{{isset($arr_global_site_setting['site_contact_number']) ? $arr_global_site_setting['site_contact_number'] :'N/A'}}</div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-6 col-lg-3">
                        <div class="cont-mob-bx">
                            <div class="add-img-bx">
                                <span> <img src="{{url('/')}}/front_assets/images/cont-3.png" alt="" /> </span>
                                <p>{{ trans('general.email') }}</p>
                            </div>
                            <div class="add-cont-txt">{{isset($arr_global_site_setting['site_email_address']) ? $arr_global_site_setting['site_email_address'] :'N/A'}}</div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-3">
                        <div class="cont-mob-bx">
                            <div class="add-img-bx">
                                <span> <img src="{{url('/')}}/front_assets/images/cont-4.png" alt="" /> </span>
                                <p>{{ trans('general.website') }}</p>
                            </div>
                            <div class="add-cont-txt"> {{url('/')}} </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        
    <div class="container">
        <div class="display-map">
           <div class="map-sectionmain">
    <?php
      
        $site_address = isset($arr_global_site_setting['site_address']) ? $arr_global_site_setting['site_address'] :'';
        $site_address = urlencode($site_address);
        $api_key = get_google_map_api_key();    
                
        $map_url = "https://www.google.com/maps/embed/v1/place?q=".$site_address."&amp;key=".$api_key."";
    ?>
        <iframe src="{{$map_url}}" width="100%" height="100%" frameborder="0" style="border:0" allowfullscreen></iframe>

        </div>
    </div>
    </div>
    <div id="pageloader">
        <img src="{{url('/')}}/front_assets/images/material.gif" alt="processing..." />
    </div>
    <div class="container">
        <div class="form-content margin-map">
            <div class="heading-img">
                <h1>{{ trans('general.say_hello_to_us') }}</h1>
                <div class="say-hello-img">
                    <img src="{{url('/')}}/front_assets/images/form-img.png" alt="plane-image">
                </div>
                <div class="clearfix"></div>
                <div class="green-line-block"></div>
            </div>    
            <form action="{{url('/')}}/process_contact_us" id='contact_form' name='contact_form'  method="post" data-rule-required>
                {{csrf_field()}}
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>{{ trans('general.first_name') }}</label>
                            <input type="text" id='first_name' name='first_name' placeholder="Enter Your First Name" data-rule-required="true">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>{{ trans('general.last_name') }}</label>
                            <input type="text" id='last_name' name='last_name' placeholder="Enter Your Last Name" data-rule-required="true">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>{{ trans('general.email') }}</label>
                            <input type="text" id='email' name='email' placeholder="Enter Your Email" data-rule-required data-rule-email="true">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>{{ trans('general.phone_number') }}</label>
                            <input type="text" id="contact_no" name='contact_no' data-rule-digits='true' placeholder="Enter Your Phone Number" maxlength="14" minlength="8" data-rule-required="true">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                       <div class="form-group">
                            <label>{{ trans('general.description') }}</label>
                            <textarea id="description" name="description" placeholder="Description" data-rule-required="true"></textarea>
                        </div>
                    </div>
                   <button type="submit" class="full-orng-btn sim-button cont-sub">{{ trans('general.submit') }}</button>
                </div> 
            </form>          
         </div>
    </div>

        <div class="banner-bg contact-banner">
            <div class="container">
            <h1>Fly anytime, anywhere with <span>Aircraft Rental</span></h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam varius quis metus rhoncus porttitor. Vivamus a pulvinar nisi. Aliquam gravida blandit ex, vel convallis felis placerat non. Duis semper ex purus, vitae luctus libero pulvinar id. Suspendisse dui risus, rhoncus eget dignissim
. vitae, finibus non nunc.</p>
        </div>
            </div>
 <script type="text/javascript" src="{{url('/')}}/front_assets/js/jquery.validate.min.js"></script>            
<script type="text/javascript">
     $('#contact_form').validate({
                submitHandler: function(form) { 
                    $("#pageloader").fadeIn();
                    return true; 
                }
        });
    $(document).ready(function(){
        $('#header_home').removeClass('on-banner-header').addClass('white-bg-header');
});
</script>
    
@endsection