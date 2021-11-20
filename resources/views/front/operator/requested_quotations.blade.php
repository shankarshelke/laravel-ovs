@extends('front.layout.master')
@section('main_content')

<style type="text/css">
    .request-quote-main .accept {width: 100%;}
   .pending {
    position: absolute;
    color: #ffffff;
    background-color: #ffae00;
    right: 10px;
    top: 10px;
    font-size: 11px;
    border-radius: 3px;
    width: 70px;
    text-align: center;
}

</style>
    <div class="header-index white-bg-header after-login-header" id="header-home"></div>
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
            @if(isset($arr_quotations['data']) && !empty($arr_quotations['data']))
            <div class="request-quote-filter-section">
                <div class="row m-l--5 m-r--5">
                    <div class="col-sm-8 col-md-8 col-lg-10">
                        <div class="filter-search-main">                            
                            <div class="form-group">
                                <span><i class="fa fa-search"></i></span>
                                <input type="search" name="search" type="text" placeholder="Search Quotations by Id or Aircraft Model" id="search" value="{{isset($_GET['search'])?$_GET['search']:''}}">                            
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2 col-md-2 col-lg-2 p-l-5 p-r-5">                    
                        <div class="search-filter-button">
                          <button class="filter-search-btn" id='button'><i class="fa fa-search"></i> {{ trans('general.search') }}</button>
                           <a class="filter-icon-btn reset-form" href="javascript:void(0)"><i class="fa fa-repeat"></i></a>
                         <!-- <a class="filter-icon-btn" href="javascript:void(0)"><img src="images/filter-icon-img.png" alt="" /></a> -->
                     </div>
                 </div>
                </div>
            </div>
            @endif
            <div class="row">
            @if(isset($arr_quotations['data']) && !empty($arr_quotations['data']))
            @foreach( $arr_quotations['data'] as $key => $value )
                <a href=" {{url('/')}}/details/{{ base64_encode($value['aircraft']['id']) }} ">
                <div class="col-sm-4 col-md-4 col-lg-4">
                    <div class="main-quote request-quote-main booking-pending">
                        <div class="img-quote">
                            @if(isset($value['get_image']['images']) && $value['get_image']['images']!=null && file_exists($aircraft_image_base_img_path.$value['get_image']['images']))
                                <img src="{{ get_resized_image($value['get_image']['images'] ,$aircraft_image_base_img_path,200,320) }}">
                            @else
                                <img src="{{url('/')}}/front_assets/images/default-img-200.png">
                            @endif
                            
                            <div class="id-quote-receive">{{$value['rfq_id']}}</div>

                            @if($value['status'] == 'REQUESTED')
                                <div class="pending" >{{ trans('general.requested') }}</div>
                            @elseif($value['status'] == 'REPLIED')
                                 <div class="pending" style="background-color: #0d95f4">{{ trans('general.replied') }}</div>
                            @elseif($value['status'] == 'ACCEPTED')
                                <div class="pending" style="background-color: green">{{ trans('general.accepted') }}</div>
                            @elseif($value['status'] == 'REJECTED')
                                <div class="pending" style="background-color: red">{{ trans('general.rejected') }}</div>
                            @endif

                           <!--  <div class="text-quote">
                                <div class="profile-quote">
                                @if(isset($value['user']['profile_image']) && $value['user']['profile_image']!=null && file_exists($user_profile_base_img_path.$value['user']['profile_image']))
                                    <img src="{{get_resized_image($value['user']['profile_image'] ,$user_profile_base_img_path,40,40)}}">
                                @else
                                    <img src="{{url('/')}}/uploads/default/no-img-user-profile-old.jpeg">
                                @endif
                                </div>
                                <div class="profile-text-quote">{{$value['user']['first_name'] or ''}} {{$value['user']['last_name'] or ''}}</div>
                            </div> -->
                            <div class="date-quote">{{get_formated_date($value['created_at']) }}</div>
                        </div>
                        <div class="content-quote"><a href="{{ url('/') }}/details/{{ base64_encode($value['aircraft']['id']) }}">{{$value['aircraft']['get_aircraft_type']['model_name'] or ''}}</a></div>
                        <div class="content-date-quote">
                            <span class="june">
                                <span class="from">
                                    <i class="far fa-calendar-alt"></i> from
                                </span> 
                                {{ isset($value['from_date']) ? get_formated_date($value['from_date']) : '' }}
                            </span> 
                            <span class="june to-date-section">
                                <span class="from">
                                    <i class="far fa-calendar-alt"></i> To
                                </span> 
                                {{ isset($value['to_date']) ? get_formated_date($value['to_date']) : '' }}
                            </span> 
                        </div>
                        <div class="price-quote"><span class="price"><i class="fa fa-dollar-sign"></i> Price</span> {{isset($value['aircraft']['price_per_hour']) ? get_formatted_price($value['aircraft']['price_per_hour'],$cny_price) : ''}}</div>
               <!--          <div class="button-quote request-button">
                        @if($value['status'] == 'REQUESTED')
                            <div class="accept"><a href="javascript:void(0)">{{ trans('general.requested') }}</a></div>
                        @elseif($value['status'] == 'REPLIED')
                            <div class="accept"><a href="javascript:void(0)">{{ trans('general.replied') }}</a></div>
                        @elseif($value['status'] == 'ACCEPTED')
                            <div class="accept"><a href="javascript:void(0)">{{ trans('general.accepted') }}</a></div>    
                        @elseif($value['status'] == 'REJECTED')
                            <div class="accept"><a href="javascript:void(0)">{{ trans('general.rejected') }}</a></div>                
                        @endif
                        </div> -->
                    </div>
                </div>
                </a>
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


    <!--new profile image upload demo script end-->

    <script type="text/javascript">
        
        $(".inner-pages-menu-icon").on("click", function() {
            $(this).parent(".inner-pages-menu-head").siblings(".inner-page-menu-ul").slideToggle("slow");
        })
        $('#search').on("keypress", function(e) {
            if (e.keyCode == 13) {
                var search = $(this).val();
                window.location.href = "{{ url('/') }}/operator/requested_quotations?search="+search
            }
        });
        $( "#button" ).click(function() {
          var search = $('#search').val();
          window.location.href = "{{ url('/') }}/operator/requested_quotations?search="+search
        });
    $('.reset-form').click(function(){
        $("#search").val('');
        window.location.href = "{{ url('/') }}/operator/requested_quotations";
    });
    </script>

 @endsection