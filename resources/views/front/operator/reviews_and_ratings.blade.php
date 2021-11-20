@extends('front.layout.master')
@section('main_content')
<div class="header-index white-bg-header after-login-header" id="header-home"></div>

<section class="gray-bg-main-section">
    <div class="container">
        @if(isset($arr_data['data']) && !empty($arr_data['data']))    
        @foreach( $arr_data['data'] as $key => $value ) 
        <div class="rating-review-box"> 
            @if(isset($value['get_reservation']['reservation_id']) && !empty($value['get_reservation']['reservation_id']))    
            <div class="rating-id" style="font-size: 11px;">{{ $value['get_reservation']['reservation_id'] or 'N/A'}}</div>
            @endif
            <div class="rating-main-title">{{ $value['aircraft']['get_aircraft_type']['model_name'] or 'N/A'}}</div>
            @if(isset($value['get_reservation']['reservation_id']) && !empty($value['get_reservation']['reservation_id']))
            <div class="rating-frm-date"><span><i class="fa fa-calendar"></i> From</span> {{ $value['get_reservation']['pickup_date'] or 'N/A' }} </div>
            <div class="rating-frm-date"><span> <i class="fa fa-calendar"></i> To</span> {{ $value['get_reservation']['return_date'] or 'N/A' }} </div>
            @endif
            <div class="equipment-content-main review-ratings-content-main review-top-border">
                
             
                <div class="review-profile-image">
                    @if(isset($value['user']['profile_image']) && $value['user']['profile_image']!=null && file_exists($user_profile_base_img_path.$value['user']['profile_image']))
                    <img src="{{get_resized_image($value['user']['profile_image'] ,$user_profile_base_img_path,70,70)}}">
                    @else
                    <img src="{{url('/')}}/uploads/default/no-img-user-profile-old.jpeg">
                    @endif
                    <!-- <img src="images/review-sender-img.jpg" alt="" /> -->
                </div>
                <div class="review-content-block">
                    <div class="review-send-head">
                       {{ $value['user']['first_name'] or 'N/A' }} {{ $value['user']['last_name'] or 'N/A'}}
                   </div>
                   <div class="rating-review-stars">
                    <span class="start-rate-count-blue">{{ $value['ratings'] }}</span>
                    <div class="redeem-star">
                        @php
                        $ratings = isset($value['ratings']) ? intval($value['ratings']) : 0;
                        @endphp
                        
                        @for($i=1;$i<=5;$i++)
                        @if($i<=$ratings)
                        <span title="{{$i}}" style="color:orange" class="fa fa-star"></span>
                        @else
                        <span title="{{$i}}" class="fa fa-star"></span>
                        @endif
                        @endfor
                    </div>
                    <div class="time-text"> {{ get_formated_date($value['created_at']) }} </div>
                </div>
                <div class="review-rating-message">
                    {{ $value['reviews'] or 'N/A' }}
                </div>
            </div>
        </div>
    </div>
    @endforeach
    
    
    <div class="pagination">
        <ul>
            {{$page_link}}
        </ul>
    </div>
    @else
    <div class="container">
        <div class="background-container">
            <div class="img-content">
                <img src="{{ url('/') }}/front_assets/images/rocket.png" alt="">
            </div> 
            <div class="content-background">
                <div class="result-not-found">Sorry you have no reviews</div>
            </div>
        </div>
    </div>    
    @endif          
</div>
</section>

@endsection