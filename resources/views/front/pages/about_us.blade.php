@extends('front.layout.master')
@section('main_content')
<div class="page-head-section-main">
        <div class="container">
            <div class="term-content">
                About Us
            </div>
            <div class="condition-content">
                <a href="{{url('/')}}">Home ></a> <span class="inline-content-color">About Us</span>
            </div>    
            <div class="clearfix"></div>
        </div>
    </div>  
    <!--Header section end here-->
    
<section>
 <div class="container-fluid">
        <div class="background-plane">
            <div class="plane-image-main">               
                    <div class="row">
                         <div class="col-sm-12 col-md-12 col-lg-6 plane-imge-bx">
                             <div class="about-plain">
                                 <img src="{{url('/')}}/front_assets/images/plane.png" alt="plan"/>    
                              </div>
                              </div>                       
                          <div class="col-sm-12 col-md-12 col-lg-6 plane-imge-bx">
                            <div class="plane-image-content-main">
                                <div class="plane-image-content">Air Charter <span >Safety</span></div>
                                <div class="green-line"></div>
                                <div class="plane-image-content-lorem"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p></div>
                                <div class="read-more-button">
                                    <button>Read More</button>
                                </div>
                            </div>
                        </div>
                         </div>  
                    </div>
                </div>
            </div>
      

         
            <div class="container">
        <div class="our-features">Our <span>Features</span></div>
        <div class="green-line2"></div>
        <div class="our-features-content">Cabore et dolore magna aliqua uat enim ad minim veniama qnostrud</div>

     
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-3 ">
                    <div class="our-image-main">
                        <div class="our1"><img src="{{url('/')}}/front_assets/images/our1.png" alt=""></div>
                        <div class="our1-selective">Selective Charter</div>
                        <div class="our1-selective-last">Cabore et dolore magna aliqua uat enim ad minim veniama qnostrud</div>
                    </div>
                </div>
                
                  <div class="col-sm-12 col-md-6 col-lg-3 ">
                    <div class="our-image-main">
                        <div class="our1"><img src="{{url('/')}}/front_assets/images/our2.png" alt=""></div>
                        <div class="our1-selective">Track Record</div>
                        <div class="our1-selective-last">Cabore et dolore magna aliqua uat enim ad minim veniama qnostrud</div>
                    </div>
                </div>
                
                   <div class="col-sm-12 col-md-6 col-lg-3 ">
                    <div class="our-image-main">
                        <div class="our1"><img src="{{url('/')}}/front_assets/images/our3.png" alt=""></div>
                        <div class="our1-selective">Safety</div>
                        <div class="our1-selective-last">Cabore et dolore magna aliqua uat enim ad minim veniama qnostrud</div>
                    </div>
                </div>
                
                  <div class="col-sm-12 col-md-6 col-lg-3 ">
                    <div class="our-image-main">
                        <div class="our1"><img src="{{url('/')}}/front_assets/images/our4.png" alt=""></div>
                        <div class="our1-selective">24/7 Service</div>
                        <div class="our1-selective-last">Cabore et dolore magna aliqua uat enim ad minim veniama qnostrud</div>
                    </div>
                </div>


            </div>
        </div>
   
        <div class="container-fluid">
            <div class="background-plane private-jet-section">
                <div class="plane-image-main">               
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-6 mobile-plain-block">
                            <div class="about-plain">
                                <img src="{{url('/')}}/front_assets/images/plan-2.png" alt="plan"/>    
                            </div>
                        </div>
                       
                        <div class="col-sm-12 col-md-12 col-lg-6">
                            <div class="plane-image-content-main">
                                <div class="plane-image-content">Air Charter <span >Safety</span></div>
                                <div class="green-line"></div>
                                <div class="plane-image-content-lorem"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p></div>
                                <div class="read-more-button">
                                    <button>Read More</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-12 col-md-12 col-lg-6 mobile-plain-none">
                            <div class="about-plain">
                                <img src="{{url('/')}}/front_assets/images/plan-2.png" alt="plan"/>    
                            </div>
                        </div>                       
                    </div>  
                </div>
            </div>
        </div>
    </section>

    <script type="text/javascript">
        $(document).ready(function(){
            $('#header_home').removeClass('on-banner-header').addClass('white-bg-header');
        });
    </script>
@endsection