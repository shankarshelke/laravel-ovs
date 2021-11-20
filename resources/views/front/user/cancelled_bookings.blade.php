@extends('front.layout.master')
@section('main_content')
<style type="text/css">
    .request-quote-main .accept {width: 100%;}
</style>

<?php 

    $active_currency  = Session::get('currency');
    $updated_currency = currency_conversion_api($active_currency);
    if($active_currency == 'EUR' )
    {
        $cny_price = $updated_currency->rates->EUR;     
    }else{
        $cny_price = $updated_currency->rates->USD;     
    }    
?>
    <section class="gray-bg-main-section">
        <div class="container">
            @include('front.layout.operation_status')
            @if(isset($arr_booking['data']) && !empty($arr_booking['data']))
            <div class="request-quote-filter-section">
                <div class="row m-l--5 m-r--5">
                    <div class="col-sm-8 col-md-8 col-lg-10">
                        <div class="filter-search-main">                            
                            <div class="form-group">
                                <span><i class="fa fa-search"></i></span>
                                <input type="search" name="search" type="text"  placeholder="Search bookings by Id or Aircraft Model" value="{{isset($_GET['search'])?$_GET['search']:''}}" id="search">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-2 col-md-2 col-lg-2 p-l-5 p-r-5">                    
                        <div class="search-filter-button">
                            <button class="filter-search-btn search-btn" id="button"><i class="fa fa-search"></i> Search</button>
                            <a class="filter-icon-btn reset-form" href="javascript:void(0)"><i class="fa fa-repeat"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <!-- </form> -->
            <div class="row">
                @if(isset($arr_booking['data']) && !empty($arr_booking['data']))
                @foreach( $arr_booking['data'] as $key => $value )
                <div class="col-sm-4 col-md-4 col-lg-4">
                    <div class="main-quote request-quote-main booking-pending">
                    <a href="{{ url('/') }}/details/{{ base64_encode($value['get_aircraft_details']['id']) }}">
                        <div class="img-quote">
                            @if(isset($value['get_image']['images']) && $value['get_image']['images']!=null && file_exists($aircraft_image_base_img_path.$value['get_image']['images']))
                                <img src="{{get_resized_image($value['get_image']['images'] ,$aircraft_image_base_img_path,200,320)}}">
                            @else
                                <img src="{{url('/')}}/front_assets/images/default-img-200.png">
                            @endif
                            <!-- <img src="{{ url('/') }}/front_assets/images/booking_completed1.png" alt="" /> -->
                            <div class="id-quote-receive">{{isset($value['reservation_id']) ? $value['reservation_id'] : '' }}</div>
                            <div class="pending" style="background-color: red">{{isset($value['status']) ? $value['status'] : ''}}</div>
                            <div class="text-quote">
                                <div class="profile-quote">
                                  @if(isset($value['get_aircraft_details']['get_aircraft_owner']['profile_image']) && $value['get_aircraft_details']['get_aircraft_owner']['profile_image']!=null && file_exists($operator_profile_base_img_path.$value['get_aircraft_details']['get_aircraft_owner']['profile_image']))
                                  <img src="{{get_resized_image($value['get_aircraft_details']['get_aircraft_owner']['profile_image'] ,$operator_profile_base_img_path,40,40)}}">
                                  @else
                                  <img src="{{url('/')}}/uploads/default/no-img-user-profile-old.jpeg">
                                  @endif
                                  <!-- <img src="{{ url('/') }}/front_assets/images/profile1.jpg" alt=""> -->
                              </div>
                              <div class="profile-text-quote">{{$value['get_aircraft_details']['get_aircraft_owner']['first_name'] or ''}} {{$value['get_aircraft_details']['get_aircraft_owner']['last_name'] or ''}}</div>
                          </div>
                          <div class="date-quote"> {{get_formated_date($value['created_at']) }}</div>
                      </div>
                    </a>  
                      <div class="content-quote"><a href="{{ url('/') }}/details/{{ base64_encode($value['get_aircraft_details']['id']) }}">{{$value['get_aircraft_details']['get_aircraft_type']['model_name'] or ''}}</a></div>
                      <div class="content-date-quote">
                        <span class="june">
                            <span class="from">
                                <i class="far fa-calendar-alt"></i> from
                            </span> 
                            {{isset($value['pickup_date']) ? get_formated_date($value['pickup_date']) : ''}}
                        </span> 
                        <span class="june to-date-section">
                            <span class="from">
                                <i class="far fa-calendar-alt"></i> To
                            </span> 
                            {{isset($value['return_date']) ? get_formated_date($value['return_date']) : ''}}
                        </span> 
                    </div>
                    <div class="price-quote">
                        <span class="price"><i class="fa fa-dollar-sign"></i> Price </span> {{isset($value['final_amount']) ? get_formatted_price($value['final_amount'],$cny_price) :''}}
                    </div>
                    <div class="button-quote request-button main-button1">
                        <div class="accept"><a href="{{ url('/').'/user/view_contract/'.base64_encode($value["id"]) }}">View Contract</a></div>
                        {{-- <div class="accept reject"><a href="#">Cancel Request</a></div> --}}
                    </div>   
                </div>
            </div>
            @endforeach
            @else
            <div class="container">
                    <div class="background-container">
                        <div class="img-content">
                            <img src="{{ url('/') }}/front_assets/images/rocket.png" alt="">
                        </div> 
                        <div class="content-background">
                            <div class="result-not-found">Result Not Found</div>
                            <div class="please-try-again">Please try again</div>
                        </div>
                    </div>
                </div>
            @endif        
        </div>
        <div class="pagination">
            <ul>
                {{$page_link}}                    
            </ul>
        </div>
    </div>
</section>
<script type="text/javascript">
    $(document).ready(function() {
        var brand = document.getElementById('logo-id');
        brand.className = 'attachment_upload';
        brand.onchange = function() {
            document.getElementById('fakeUploadLogo').value = this.value.substring(12);
        };

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('.img-preview').attr('src', e.target.result);

                };

                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#logo-id").change(function() {
            readURL(this);
        });

    });
</script>
<!--new profile image upload demo script end-->

<script type="text/javascript">

    $(".inner-pages-menu-icon").on("click", function() {
        $(this).parent(".inner-pages-menu-head").siblings(".inner-page-menu-ul").slideToggle("slow");
    });
    $('#search').on("keypress", function(e) {
        if (e.keyCode == 13) {
            var search = $(this).val();
            window.location.href = "{{ url('/') }}/user/cancelled_bookings?search="+search
        }
    });
    $( "#button" ).click(function() {
          var search = $('#search').val();
          window.location.href = "{{ url('/') }}/user/cancelled_bookings?search="+search
        });

     $('.reset-form').click(function(){
        $("#search").val('');
        window.location.href = "{{ url('/') }}/user/cancelled_bookings";
    });
        // }
    </script>

    @endsection