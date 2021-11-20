@extends('front.layout.master')
@section('main_content')
<style type="text/css">
    #review_description-error {font-size: 12px;color: red;}
    .pac-container { z-index: 100000; }
    .datepicker .day {color: #4bbd58 !important;}
    .datepicker table tr td.disabled, .datepicker table tr td.disabled:hover {color: #999 !important;}
</style>
<?php

$arr_user = [];
if(Auth::guard('operator')->check()){
    $arr_user = Auth::guard('operator')->user()->toArray();
}elseif(Auth::guard('users')->check()){
    $arr_user = Auth::guard('users')->user()->toArray();
}

$arr_availability = $arr_dates = [];

$arr_availability = (isset($arr_data['get_availablity1']) && !empty($arr_data['get_availablity1'])) ? $arr_data['get_availablity1'] : [] ;

//To find dates between two dates as an array
foreach($arr_availability as $row)
{
    $from = $row['from_date'];
    $to = $row['to_date'];
    $to = date('Y-m-d', strtotime($to . ' +1 day'));
    //$to = date('Y-m-d', strtotime($to));

    $dates = new DatePeriod( new DateTime($from), new DateInterval('P1D'), new DateTime($to) );
    foreach ($dates as $key => $value) {
        $arr_dates[] = $value->format('Y-m-d');
    }
}

foreach($arr_bookings as $booked)
{
    $dates = [];
    $booked_from = $booked['pickup_date'];

    $booked_to = date('Y-m-d', strtotime($booked['return_date']. ' +1 day'));

    $dates = new DatePeriod( new DateTime($booked_from), new DateInterval('P1D'), new DateTime($booked_to) );

    foreach ($dates as $key => $value) {
        if(in_array($value->format('Y-m-d'), $arr_dates)){
            $item = array_search($value->format('Y-m-d'),$arr_dates);
            unset($arr_dates[$item]);
        }
    }
}

?>
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
@include('front.layout.breadcrumb')
<section class="gray-bg-main-section">
    <div class="container">
        @include('front.layout.operation_status')
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-8">
                @if(isset($arr_aircraft_images) && sizeof($arr_aircraft_images)>0)
                <div class="buy-deal-box for-margin-10">
                    <div class="gallery-bx">
                        <div class="main-deatil-slider">
                            <div id="example1" class="webwing-gallery img300">
                                <div class="prod-carousel">
                                    <?php $default = url('/')."/front_assets/images/default-img.png"; ?>
                                    @foreach($arr_aircraft_images as $key => $value)
                                    @if(isset($value['images']) && $value['images']!=null && file_exists($aircraft_images_base_img_path.$value['images']))
                                    
                                    <?php
                                    $thumb1 = $thumb2 = $thumb3 =  '';
                                    if(file_exists($aircraft_images_base_img_path.'thumb_350x255/'.$value['images'])){
                                        $thumb1 = $aircraft_images_public_img_path.'/thumb_350x255/'.$value['images'];
                                    }else{
                                        $thumb1 = url('/')."/front_assets/images/default-img.png";
                                    }

                                    if(file_exists($aircraft_images_base_img_path.'thumb_690x345/'.$value['images'])){
                                        $thumb2 = $aircraft_images_public_img_path.'/thumb_690x345/'.$value['images'];
                                    }else{
                                        $thumb2 = url('/')."/front_assets/images/default-img.png";
                                    }

                                    if(file_exists($aircraft_images_base_img_path.$value['images'])){
                                        $thumb3 = $aircraft_images_public_img_path.$value['images'];
                                    }else{
                                        $thumb3 = url('/')."/front_assets/images/default-img.png";
                                    }
                                    ?>
                                    <img src="{{$thumb1}}" data-medium-img="{{$thumb2}}" data-big-img="{{$thumb3}}" data-title="{{-- Mustang Shelby GT500 - big black car with red lines is very beautiful and powerful --}}" alt="">
                                    @else
                                    <img src="{{url('/')}}/front_assets/images/default-img.png" class="img-responsive" data-medium-img="{{$default}}" data-big-img="{{$default}}">
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="product-details-tabbing-main">
                    <div data-responsive-tabs>
                        <nav>
                            <ul>
                                <li>
                                    <a href="#one">{{ trans('general.specifications_and_amenities') }} </a>
                                </li>
                                <li>
                                    <a href="#two"> {{ trans('general.equipment') }}</a>
                                </li>
                                <li>
                                    <a href="#three">{{ trans('general.review_and_ratings') }}</a>
                                </li>                                       
                            </ul>
                        </nav>
                        <div class="content">
                            <section id="one">
                                <div class="buy-deal-box tabbing-content-main-box">
                                    <div class="tabbing-content-head-section">
                                        {{ trans('general.specification') }}
                                    </div>
                                    <div class="engines-registretion-content-main">
                                        <div class="row">
                                            <div class="col-sm-6 col-md-6 col-lg-6">
                                                <div class="engines-registretion-content">
                                                    <div class="engines-registretion-content-head">
                                                        {{ trans('general.model') }}
                                                    </div>
                                                    <div class="engines-registretion-content-text">
                                                        {{ $arr_aircraft_type['model_name'] or 'N/A'}}&nbsp;
                                                        ( {{ $arr_aircraft_type['icao_code'] or ''}} )
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-6 col-lg-6">
                                                <div class="engines-registretion-content">
                                                    <div class="engines-registretion-content-head">
                                                        {{ trans('general.engines') }}
                                                    </div>
                                                    <div class="engines-registretion-content-text">
                                                        {{isset($arr_data['engine'])? $arr_data['engine'] :'N/A'}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-6 col-lg-6">
                                                <div class="engines-registretion-content">
                                                    <div class="engines-registretion-content-head">
                                                        {{ trans('general.registration_number') }}
                                                    </div>
                                                    <div class="engines-registretion-content-text">
                                                        {{isset($arr_data['registration_no'])? $arr_data['registration_no']:'N/A'}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-6 col-lg-6">
                                                <div class="engines-registretion-content">
                                                    <div class="engines-registretion-content-head">
                                                        {{ trans('general.props') }}
                                                    </div>
                                                    <div class="engines-registretion-content-text">
                                                        {{isset($arr_data['props']) ? $arr_data['props'] : 'N/A' }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-6 col-lg-6">
                                                <div class="engines-registretion-content">
                                                    <div class="engines-registretion-content-head">
                                                        {{ trans('general.minimum') }} {{ trans('general.guarantee') }} {{ trans('general.hours') }}/{{ trans('general.month_required') }}
                                                    </div>
                                                    <div class="engines-registretion-content-text">
                                                        {{ (isset($arr_data['min_gaurantee_hours']) && $arr_data['min_gaurantee_hours'] != '') ? $arr_data['min_gaurantee_hours'].' Hrs' : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                      <!--       <div class="col-sm-6 col-md-6 col-lg-6">
                                                <div class="engines-registretion-content">
                                                    <div class="engines-registretion-content-head">
                                                        {{ trans('general.capacity') }}
                                                    </div>
                                                    <div class="engines-registretion-content-text">
                                                        {{isset($arr_data['capacity']) ? $arr_data['capacity'] : 'N/A' }}
                                                    </div>
                                                </div>
                                            </div> -->
                                            <div class="col-sm-6 col-md-6 col-lg-6">
                                                <div class="engines-registretion-content">
                                                    <div class="engines-registretion-content-head">
                                                        {{ trans('general.other_charges') }}
                                                    </div>
                                                    <div class="engines-registretion-content-text">
                                                       {{ isset($arr_data['other_charges']) ? get_formatted_price($arr_data['other_charges'],$cny_price) : 'N/A' }}
                                                   </div>
                                               </div>
                                           </div>
                                           <div class="col-sm-6 col-md-6 col-lg-6">
                                            <div class="engines-registretion-content">
                                                <div class="engines-registretion-content-head">
                                                    {{ trans('general.cost_per_hour') }} > 50MGH
                                                </div>
                                                <div class="engines-registretion-content-text">
                                                    {{ isset($arr_data['less_mgh_cost']) ? get_formatted_price($arr_data['less_mgh_cost'],$cny_price) : 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6 col-lg-6">
                                            <div class="engines-registretion-content">
                                                <div class="engines-registretion-content-head">
                                                    {{ trans('general.cost_per_hour') }} < 50MGH
                                                </div>
                                                <div class="engines-registretion-content-text">
                                                    {{ isset($arr_data['more_mgh_cost']) ?  get_formatted_price($arr_data['more_mgh_cost'],$cny_price) :'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6 col-lg-6">
                                            <div class="engines-registretion-content">
                                                <div class="engines-registretion-content-head">
                                                    {{ trans('general.positioning') }} & {{ trans('general.repositioning') }}
                                                </div>
                                                <div class="engines-registretion-content-text">
                                                    {{isset($arr_data['positioning'])? ucwords(str_replace('_',' ',$arr_data['positioning'])): '' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6 col-lg-6">
                                            <div class="engines-registretion-content">
                                                <div class="engines-registretion-content-head">
                                                    Location
                                                </div>
                                                <div class="engines-registretion-content-text">
                                                   {{ $arr_data['base_of_operation'] or 'N/A' }}
                                               </div>
                                           </div>
                                       </div>
                                       <!-- <div class="col-sm-6 col-md-6 col-lg-6">
                                        <div class="engines-registretion-content">
                                            <div class="engines-registretion-content-head">
                                                {{ trans('general.default_location') }}
                                            </div>
                                            <div class="engines-registretion-content-text">
                                                {{ $arr_data['default_location'] or 'N/A' }}
                                            </div>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <div class="buy-deal-box amenities-content-main">
                            <div class="tabbing-content-head-section">
                                {{ trans('general.amenities') }}
                            </div>
                            @if(isset($arr_data_amenities) && sizeof($arr_data_amenities) > 0)
                            @foreach( $arr_data_amenities as $key => $value )
                            <div class="engines-registretion-content-text">
                                <div class="find-print-bullet-point">
                                    <i class="fa fa-angle-double-right"></i>
                                    <span><?php if($value['name']!='')
                                                    {
                                                        echo $value['name'];
                                                    }else{
                                                        echo 'No data Available';
                                                    }    
                                                        ?></span>
                                </div>
                            </div>
                            @endforeach
                            @else 
                            <div> No Amenities Found </div>
                            @endif
                        </div>
                    </section>
                    <section id="two" class="equipment-main-section">
                        <div class="buy-deal-box tabbing-content-main-box">
                            <div class="tabbing-content-head-section">
                                {{ trans('general.equipments') }}
                            </div>
                            <div class="equipment-content-main">
                                <div class="find-print-bx">  
                                    @if(isset($arr_data_equipments) && sizeof($arr_data_equipments)>0)   
                                    @foreach( $arr_data_equipments as $key => $value )                                        
                                    <div class="find-print-bullet-point"> 
                                        <i class="fa fa-angle-double-right"></i>
                                        <span><?php if($value['name']!='')
                                                    {
                                                        echo $value['name'];
                                                    }else{
                                                        echo 'No data Available';
                                                    }    
                                                        ?></span>
                                    </div>
                                    @endforeach  
                                    @else
                                    <div> No Equipments Found </div>
                                    @endif                     
                                </div>
                            </div>
                        </div>
                   
                    </section>
                    <section id="three">
                        <div class="buy-deal-box tabbing-content-main-box">
                            <div class="tabbing-content-head-section">
                                {{ trans('general.review_and_ratings') }}
                            </div>
                            @if( isset($arr_data_reviews['data']) && sizeof($arr_data_reviews['data']) > 0 )
                            <div id="rating_div">
                            @foreach( $arr_data_reviews['data'] as $key => $value )
                            <div  class="equipment-content-main review-ratings-content-main">
                                <div class="review-profile-image">

                                    @if(isset($value['user']['profile_image']) && $value['user']['profile_image']!=null && file_exists($user_profile_base_img_path.$value['user']['profile_image']))
                                    <!-- <img src="{{url('/')}}/front_assets/images/review-sender-img.jpg" alt="" /> -->
                                    <img src="{{get_resized_image($value['user']['profile_image'] ,$user_profile_base_img_path,100,100)}}">
                                    @else
                                    <img style="width:100px;height:100px" src="{{url('/')}}/uploads/default/no-img-user-profile-old.jpeg">
                                    @endif
                                </div>
                                <div class="review-content-block">
                                    <div class="review-send-head">
                                        {{$value['user']['first_name']}}  {{$value['user']['last_name']}}
                                    </div>
                                    <div class="rating-review-stars">
                                        <span class="start-rate-count-blue">{{$value['ratings']}}</span>
                                        <div class="redeem-star">
                                           <?php $rating = $value['ratings'];
                                           for($i=0;$i<=4;$i++)
                                           {
                                            if($i<$rating)
                                            {
                                                ?>
                                                <i class="fa fa-star active"></i>
                                                <?php }else{?>
                                                <i class="fa fa-star "></i>
                                                <?php }}?>
                                            </div>
                                            <div class="time-text"> {{ get_formated_date($value['created_at']) }}</div>
                                        </div>
                                        <div class="review-rating-message">
                                           {{$value['reviews']}}
                                       </div>
                                   </div>
                               </div>
                               @endforeach 
                            </div>
                               <div class="load-more-btn" >
                                <input type="hidden" id="reviewPerPage" name="reviewPerPage" value="1">
                                <input type="hidden" id="productId" name="productId" value="{{$arr_data['id']}}"> 
                                @if( ($arr_data_reviews['total']) > 5 )
                                <button id="_moreReviews"  class="load-more loadMoreReviews full-orng-btn sim-button">Load More</button>
                                @endif
                            </div> 
                            @else
                            <div> No Reviews Found </div>
                            @endif
                        </div>
                @if(!empty($arr_res) && $arr_res != 'null')       
                    <?php $reviews =    get_aircraft_reviews($arr_res['reservation_id'],$arr_res['user_id']);
                    ?>
                    @if($reviews == false)
                        @if( isset($count_review) && $count_review > 0 )
                        @if(is_user_logged_in('users')) 
                        <form action="{{url('/')}}/review/{{base64_encode($arr_data['id'])}}" method="POST" id='review_form'>
                            {{csrf_field()}}
                            <div class="buy-deal-box">
                                <div class="review-send-rating-star">
                                    <div class="stars"> 
                                        <input type="radio" checked="checked" name="rating" id="star-5" class="star star-5" value="5">
                                        <label class="star star-5" for="star-5"></label>
                                        <input class="star star-4" type="radio" name="rating" id="star-4" value="4"> 
                                        <label class="star star-4" for="star-4"></label>
                                        <input class="star star-3" type="radio" name="rating" id="star-3" value="3"> 
                                        <label class="star star-3" for="star-3"></label>
                                        <input class="star star-2" type="radio" name="rating" id="star-2" value="2"> 
                                        <label class="star star-2" for="star-2"></label>
                                        <input class="star star-1"  type="radio" name="rating" id="star-1" value="1"> 
                                        <label class="star star-1" for="star-1"></label>
                                    </div>  
                                    <label id="reviews-error" class="error" for="rating"></label>

                                    <div class="rating-title">{{ trans('general.rate_it_now') }}</div>
                                </div>
                                <div class="review-rating-form-section">
                                    <div class="form-group">
                                        <label>{{ trans('general.enter_your_review') }}</label>
                                        <textarea name='review_description' id='review_description' data-rule-required="true" placeholder="{{ trans('general.write_your_review') }}"></textarea>
                                    </div>
                                    <button type="submit" class="full-orng-btn sim-button">{{ trans('general.submit') }}</button>
                                </div>
                            </div>
                        </form>
                        @endif
                        @endif  
                     @endif

                @endif
                    </section>                              
                </div>
            </div>
        </div>                    
    </div>

    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-4">
        <div class="buy-deal-box">
            <div class="deatil-category">{{isset($arr_data['type_name'])? ucwords(str_replace('_',' ',$arr_data['type_name'])): '' }}</div>

            <div class="deatil-category-title">{{isset($arr_aircraft_type['model_name'])? $arr_aircraft_type['model_name']: ''}}</div>
            <div class="redeem-star">
                <?php
                $tot_reviews = $reviews_cnt = $avg_reviews = 0;

                if(isset($arr_data['get_reviews']) && count($arr_data['get_reviews']) > 0){
                    $reviews_cnt = count($arr_data['get_reviews']);
                    foreach($arr_data['get_reviews'] as $row){
                        $tot_reviews += isset($row['ratings']) ? $row['ratings'] : 0;
                    }
                }
                
                if($reviews_cnt > 0)
                {
                    $avg_reviews = ($tot_reviews / ($reviews_cnt));
                }
                
                for($i=0;$i<=4;$i++)
                {
                    if($i<$avg_reviews)
                    {
                        echo '<i class="fa fa-star active"></i>';
                    }else{
                        echo '<i class="fa fa-star "></i>';
                    }
                }
                ?>
                ({{ number_format($avg_reviews,1) }}) 
            </div>
            <div class="rating-title">{{isset($count_reviews)? $count_reviews : ''}} ratings</div>
            <div class="price-per-hrs-main">
                <div class="price-per-hrs-main-head">
                    {{ trans('general.Price (PER HOUR)') }}
                </div>
                <div class="price-per-hrs-main-value">
                   {{ isset($arr_data['price_per_hour'])? get_formatted_price($arr_data['price_per_hour'],$cny_price) : 'N/A' }}
               </div>
           </div>
           <div class="performance-main-section">
            <div class="price-per-hrs-main-head">
               {{ trans('general.capabilities') }}
           </div>
           <div class="nautical-miles">
            <?php
            $capabilities = 'N/A';
            if(isset($arr_data['operational_capability']) && $arr_data['operational_capability'] != '' && $arr_data['operational_capability'] != 'null'){
                $temp = json_decode($arr_data['operational_capability']);
                if(!empty($temp))
                {
                    $capabilities = implode(', ', $temp);
                    $capabilities = ucfirst(str_replace('_',' ',$capabilities));
                }
            }
            echo $capabilities;
            ?>
        </div>
    </div>
    <div style="width: 100%" class="performance-main-section">
        <!-- Request for quotation -->
        @if(\Auth::guard('operator')->check())

        @elseif(\Auth::guard('users')->check())
        {{-- @if( isset($quotation_count) && $quotation_count > 0 )
        <button class="full-orng-btn sim-button"  id="review_modal"  onclick="Changefilename(this)">{{ trans('general.quotation_requested') }}</button>
        @else --}}
        <button class="full-orng-btn sim-button" data-toggle="modal" data-target="#myModal" id="review_modal" >{{ trans('general.request_for_quotation') }}</button>
        {{-- @endif --}}
        @else
        <form class="form-horizontal" id="search" name="search" action="{{url('/')}}/sign_in" >
            <input type="hidden" name="redirect_to" id=redirect_to value="{{url('/')}}/details/{{base64_encode($arr_data['id'])}}">
            <button class="full-orng-btn sim-button" type="submit"  id="review_modal">{{ trans('general.request_for_quotation') }}</button>    
        </form>
        @endif        
        <!-- Request for quotation -->
    </div>
</div>
    @if(!empty($arr_data['description']))
    <div class="buy-deal-box">
        <div class="about-airCraft">
           {{ trans('general.about_aircraft') }}
       </div>
       <div class="about-airCraft-content">
           {{isset($arr_data['description']) ? $arr_data['description']:'N/A'}}
       </div>                        
    </div>
    @endif
</div>
</div>

@if(isset($arr_similar_aircraft) && sizeof($arr_similar_aircraft)>0)

<div class="similar-aircraft-main-section">
    <div class="similar-aircraft-head-section">
        {{ trans('general.similar_aircraft') }}
    </div>                
    <div class="swiper-container">
        <div class="swiper-wrapper">
            @foreach( $arr_similar_aircraft as $key => $value )
            <div class="swiper-slide">
                <a href="{{ url('/').'/details/'.base64_encode($value['id']) }}" class="main-quote">
                    @if(isset($value['get_image']['images']) && $value['get_image']['images']!='null' && file_exists($aircraft_images_base_img_path.$value['get_image']['images']))
                    <div class="img-quote">
                        <img src="{{$aircraft_images_public_img_path}}/thumb_255x170/{{$value['get_image']['images']}}" alt=" " />
                        <div class="id-quote-page1">{{ isset($value['type_name'])?  ucwords(str_replace('_',' ',$arr_data['type_name'])):''}}</div>
                    </div>
                    @else 
                    <div class="img-quote">
                        <img src="{{url('/')}}/front_assets/images/default-similar.png" class="img-responsive">
                        <div class="id-quote-page1">{{ isset($value['type_name'])?  ucwords(str_replace('_',' ',$arr_data['type_name'])):''}}</div>
                    </div>
                    @endif
                    <div class="content-quote">
                        <h2>{{ isset($value['get_aircraft_type']['model_name'])? $value['get_aircraft_type']['model_name'] : '' }}</h2>
                    </div>
                    <div class="ratings">
                        <?php
                        $tot_reviews = $reviews_cnt = $avg_reviews = 0;

                        if(isset($value['get_reviews']) && count($value['get_reviews']) > 0)
                        {
                            $reviews_cnt = count($value['get_reviews']);
                            foreach($value['get_reviews'] as $row)
                            {
                                $tot_reviews += isset($row['ratings']) ? $row['ratings'] : 0;
                            }
                        }
                        
                        if($reviews_cnt > 0)
                        {

                            $avg_reviews = ($tot_reviews / ($reviews_cnt));
                        }
                        
                        for($i=0;$i<=4;$i++)
                        {
                            if($i<$avg_reviews)
                            {
                                echo '<i class="fa fa-star active"></i>';
                            }else{
                                echo '<i class="fa fa-star "></i>';
                            }
                        }
                        ?>
                        ({{ number_format($avg_reviews,1) }})
                        <div class="content-date-quote">
                            <p>
                                @if(isset($value['description']) && $value['description'] != '')
                                @if(strlen($value['description']) > 100)
                                {{ substr($value['description'], 0,90).'...' }}
                                @else
                                {{ $value['description'] }}
                                @endif
                                @endif
                            </p>
                        </div>
                        <div class="price-quote2">pricing:<span class="price-content">{{ isset($value['price_per_hour']) ? get_formatted_price($value['price_per_hour'],$cny_price) : 'N/A' }}</span></div>
                    </div>
                </a>
            </div>                
            @endforeach    
        </div>     
        <!-- Add Arrows -->
    </div>       
    <div class="swiper-button-next"> <i class="fa fa-angle-right"></i> </div>
    <div class="swiper-button-prev"> <i class="fa fa-angle-left"></i> </div>
    @endif
</div>
</section>

<!-- The Modal -->
<div class="modal registration-modal give-feedback-form-main" id="myModal" data-backdrop="static">
    <div class="modal-dialog">
        <form class="form-horizontal" id="frm_quotation" name="frm_quotation" action="{{url('/')}}/request_quotation" method="post" enctype="multipart/form-data">
            {{csrf_field()}}
            <input type="hidden" name="aircraft_id" value="{{$arr_data['id']}}">
            
            <input type="hidden" name="user_id" value="{{ isset($arr_user['id']) ? $arr_user['id'] : '' }}">
            <div class="modal-content">
                <div class="close-icon" data-dismiss="modal"><img src="{{ url('/') }}/front_assets/images/close-img.png" alt="" /></div>
                <div class="modal-body">
                    <div class="give-feedback-form request-quote-main booking-pending booking-completed">
                        @if(!empty($arr_dates))
                        <div class="row">
                            <div class="col-sm-6 col-md-6 col-lg-6">
                                <div class="date-time-search">
                                    <label>Pickup Date  <span style="color: red">*</span></label>
                                    <div class="form-group">
                                        <span><i class="far fa-calendar-alt"></i></span>
                                        <input type="text" placeholder="Date" id="pickup_date" name="pickup_date" class="form-control" data-date-format='yyyy-mm-dd'  data-rule-required="true" autocomplete="off">    
                                        <span class="error">{{ $errors->first('pickup_date') }} </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6">
                                <div class="date-time-search">
                                    <label>Return Date <span style="color: red">*</span></label>
                                    <div class="form-group">
                                        <span><i class="far fa-calendar-alt"></i></span>
                                        <input type="text" data-date-format='yyyy-mm-dd' placeholder="Date" id="return_date" name="return_date" class="form-control" data-rule-required="true" autocomplete="off">
                                        <span class="error">{{ $errors->first('return_date') }} </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 col-md-6 col-lg-12">
                                <div class="date-time-search">
                                    <label>Pickup Location  <span style="color: red">*</span></label>
                                    <div class="form-group">
                                        <span><i class="far fa-calendar-alt"></i></span>
                                        <input type="text" placeholder="Pickup Location" id="pickup_loaction" 
                                        name="pickup_loaction" class="form-control " data-rule-required="true" autocomplete="off">    
                                        <span class="error">{{ $errors->first('pickup_loaction') }} </span> 
                                    </div>
                                </div>
                            </div>
                        </div>
                      <!--   <div class="row">
                            <div class="col-sm-6 col-md-6 col-lg-12">
                                <div class="date-time-search">
                                    <label>Return Location <span style="color: red">*</span></label>
                                    <div class="form-group">
                                        <span><i class="far fa-calendar-alt"></i></span>
                                        <input type="text" placeholder="Return Location" id="return_location" 
                                        name="return_location" class="form-control " data-rule-required="true" autocomplete="off">
                                        <span class="error">{{ $errors->first('return_location') }} </span> 
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <div class="feedback-review">
                            <div class="button-quote request-button main-button1">
                                <div class="accept reject"><button class="full-orng-btn sim-button" data-dismiss="modal">Close</button></div>
                                <div class="accept reject"><button type="submit" class="full-orng-btn sim-button">Submit</button></div>
                            </div>                                                       
                        </div>
                        @else
                            <div class="container">
                                <div class="background-container">
                                    <div class="img-content">
                                        <img src="{{ url('/') }}/front_assets/images/rocket.png" alt="" style="max-width: 50%" />
                                    </div> 
                                    <div class="content-background">
                                        <div class="result-not-found">Oops!</div>
                                        <div class="please-try-again">This Aircrafts availability not found, please try again later!</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="{{url('/')}}/front_assets/js/responsivetabs.js"></script>
<script src="{{url('/')}}/front_assets/js/gallery.min.js"></script>
<link rel="stylesheet" type="text/css" href="{{url('/')}}/front_assets/css/bootstrap-datepicker.min.css"/>
<script src="{{url('/')}}/front_assets/js/bootstrap-datepicker.min.js" type="text/javascript"></script> 
<script src='{{url('/')}}/front_assets/js/moment.min.js'></script> 
<script src="{{ url('/') }}/web_admin/assets/js/pages/jquery.geocomplete.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3&key={{ get_google_map_api_key() }}&libraries=places"></script>
<script>
    function Changefilename(event){
        swal("Oops!","You have already requested quotation for this aircraft. ","error");
    }

    $(function () 
    {  
        $("#pickup_loaction").geocomplete({
            details: ".geo-details",
            detailsAttribute: "data-geo"
        }).bind("geocode:result", function (event, result){                       
            $("#latitude").val(result.geometry.location.lat());
            $("#longitude").val(result.geometry.location.lng());

            var searchAddressComponents = result.address_components,
            searchPostalCode="";
        });
    });
    $(function () 
    {  
        $("#return_location").geocomplete({
            details: ".geo-details",
            detailsAttribute: "data-geo"
        }).bind("geocode:result", function (event, result){                       
            $("#latitude").val(result.geometry.location.lat());
            $("#longitude").val(result.geometry.location.lng());
            /*$("#city").val(result.geometry.location.city());*/
            var searchAddressComponents = result.address_components,
            searchPostalCode="";
        });
    });
    $(document).ready(function ()
    {   
        $('#header_home').removeClass('on-banner-header').addClass('white-bg-header');
        $(document).on('responsive-tabs.initialised', function (event, el)
        {
        });

        $(document).on('responsive-tabs.change', function (event, el, newPanel)
        {
        });

        $('[data-responsive-tabs]').responsivetabs(
        {
            initialised : function ()
            {
            },

            change : function (newPanel)
            {
            }
        });
    });

    $('#search').submit(function() {
        var redirect_to = $(this).val();
        window.location.href = "{{ url('/') }}/sign_in?redirect_to="+redirect_to
    });
</script>    
<script>

    $(document).ready(function() {

        $('#example1').webwingGallery({
            openGalleryStyle: 'transform',
            changeMediumStyle: true
        });

        $('#review_form').validate();
        $('#frm_quotation').validate();

        $('#pickup_date, #return_date').change(function()
        {
            var pickup_date = $("#pickup_date").val();
            var return_date = $("#return_date").val();

            if(new Date(return_date) < new Date(pickup_date))
            {
                $("#return_date").val('');
                swal('Error!','Return date should be greater than Pickup date','error');
            }
        });

    });

    var availableDates = [];

    <?php
    if(isset($arr_dates) && !empty($arr_dates)){
        foreach($arr_dates as $row){
            ?>
            availableDates.push("{{ $row }}");
            <?php
        }
    }
    ?>
    $(function()
    {
        $('#pickup_date').datepicker({
            todayHighlight: true,
            autoclose: true,
            beforeShowDay: function(dt)
            {
                return available(dt);
            },
            startDate:new Date(),
        });

    });

    function available(date) {
        dmy = moment(date).format('YYYY-MM-DD');
        if ($.inArray(dmy, availableDates) != -1) {
            return true;
        } else {
            return false;
        }
    }


    $( "#return_date" ).datepicker({
        todayHighlight: true,
        autoclose: true,
        beforeShowDay: function(dt)
        {
            return available(dt);
        },
    //startDate:moment().add('d', 1).toDate(),
    startDate:new Date(),
});

    /*START LOAD MORE*/
    $("#_moreReviews").click(function (e){
      if($(this).hasClass('loadMoreReviews'))
      {
        var track_page = $('#reviewPerPage').val();
        var product_id = $('#productId').val();

        track_page++;
        
        loadReview(track_page,product_id);
        $('#reviewPerPage').val(track_page);
    }
    
});

    function loadReview(pagNum,product_id)
    {
        $.ajax({
            headers:{'X-CSRF-Token': $('input[name="_token"]').val()},
            url : "{{ url('/').'/details/ajax_more_review' }}",
            type : "POST",
            dataType: 'JSON',
            data : {page: pagNum, product_id: product_id},
            beforeSend:function(data, statusText, xhr, wrapper){

                $("#_moreReviews").addClass('loadMoreReviews');
                $(".loadMoreReviews").html('<i style="color:#ffdc14;" class="fa fa-spinner fa-pulse"></i><span class="bigger-110"> loading...</span>');
            },
            success:function(data, statusText, xhr, wrapper){

              if(data.status == 'done')
              {
                allData = [''];
                var _output = data._tokenArrList;
                if(_output.length)
                {
                  $.each(_output, function(i, val)
                  {
                    var obj = _output[i];

                    console.log(obj);
                    var dynamic_html = '';
                    dynamic_html+='<div class="equipment-content-main review-ratings-content-main">';
                    dynamic_html+='<div class="review-profile-image">';
                    dynamic_html+='<img src="'+obj.profile_img+'">';
                    dynamic_html+='</div>';
                    dynamic_html+='<div class="review-content-block">';
                    dynamic_html+='<div class="review-send-head">'+obj.cname+'</div>';
                    dynamic_html+='<div class="rating-review-stars">';
                    dynamic_html+='<span class="start-rate-count-blue">'+obj.ratings+'</span>';
                    dynamic_html+='<div class="redeem-star">';


                    /*
                    dynamic_html+='<div class="title-user-name">'+obj.cname+'</div>';
                    dynamic_html+='<div class="lisitng-detls-rate">';*/
                    if(obj.ratings!=0)
                    {
                      for($i=0;$i<=4;$i++)
                      {
                        if($i<obj.ratings)
                        {
                            dynamic_html+='<i class="fa fa-star active"></i>';        
                        }
                        else
                        {       
                         dynamic_html+=' <i class="fa fa-star "></i>';
                     }
                 }     
             }
             else
             {
                dynamic_html+='<img src="{{ url('/') }}/images/front/star2.png" />';
            }
            dynamic_html+='</div>';
            dynamic_html+='<div class="time-text">'+obj.time+'</div>';
            dynamic_html+='</div>';
            dynamic_html+=' <div class="review-rating-message">'+obj.reviews+'</div>';
            dynamic_html+='</div></div>'; 

            allData.push(dynamic_html);
        });
                  $(".loadMoreReviews").html('Load More');

              }else{   
                $(".loadMoreReviews").html('No more Reviews to display.');
                
                $("#_moreReviews").removeClass('loadMoreReviews');
            }

            $("#rating_div").append(allData.join(''));

        }
        else{
          $(".loadMoreReviews").html('Load More');
      }
  },
  error:function(data, statusText, xhr, wrapper){

      $(".loadMoreReviews").html('Load More');
  }
});
    }
    /*End LOAD MORE*/
</script>

<!-- Swiper JS -->
<script src="{{url('/')}}/front_assets/js/swiper.min.js"></script>


<!-- Initialize Swiper -->
<script>
    var swiper = new Swiper('.swiper-container', {
        slidesPerView: 4,
        spaceBetween: 30,
        slidesPerGroup: 1,
        loop: true,
        loopFillGroupWithBlank: true,
        breakpoints: {
            1199: {
                slidesPerView: 3,                
            },
            767: {
                slidesPerView: 2,
            },
            567: {
                slidesPerView: 1,
            }
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });
</script>

@endsection