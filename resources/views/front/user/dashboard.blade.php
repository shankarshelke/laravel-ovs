@extends('front.layout.master')
@section('main_content')
<style type="text/css">
    .notify{text-align: center; margin: 95px;font-size: 25px;}
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
    <section class="middle-section-main">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3">
                    <div class="profile-box">
                        <div class="green-background">
                            <a href="{{ url('/') }}/user/profile" title="Edit Profile">
                                <i class="fa fa-edit"></i>
                            </a>
                        </div>
                        <div class="profile-image">
                             @if(isset($arr_data['profile_image']) && !empty($arr_data['profile_image']) && File::exists($user_profile_base_img_path.$arr_data['profile_image']))
                            <img src="{{$user_profile_public_img_path.$arr_data['profile_image']}}"  alt="" style="height: 100px" />
                        @php 
                        $prev_image_url = $user_profile_public_img_path.$arr_data['profile_image']; 
                        $is_profile_image_required = false; 
                        @endphp
                        @else
                        <img src="{{url('/').'/uploads/admin/default_image/default-profile.png' }}"  style="max-width: 100%; line-height: 20px;">
                        @php 
                        $is_profile_image_required = true;
                        $prev_image_url = url('/').'/uploads/admin/default_image/default-profile.png';
                        @endphp
                        @endif                            
                        </div>
                        <div class="profile-content">
                            <h2>{{ucwords($arr_data['first_name'])}} {{ucwords($arr_data['last_name'])}} </h2>
                            <h3>Aircraft Charter</h3>
                            <h4>{{$arr_data['address']}}</h4>
                        </div>
                        <div class="line-block"></div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-8 col-md-8 col-lg-9">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                        <a href="{{ url('/') }}/user/completed_bookings">
                            <div class="completed-booking">
                                <div class="plane-image">
                                    <img src="{{ url('/') }}/front_assets/images/triangle.png" alt="" />
                                </div>
                                <div class="booking-content">
                                    <h2>{{$arr_completed_booking or '-'}}</h2>
                                    <h4>{{ trans('general.completed_bookings') }}</h4>
                                    <div class="blue-line-block"></div>
                                </div>      
                            </div>
                        </a>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                        <a href="{{ url('/') }}/user/pending_bookings">
                            <div class="completed-booking">
                                <div class="plane-image">
                                    <img src="{{ url('/') }}/front_assets/images/yellow-plane.png" alt="" />
                                </div>
                                <div class="booking-content">
                                    <h2>{{$arr_pending_booking or '-'}}</h2>
                                    <h4>{{ trans('general.pending_bookings') }}</h4>
                                    <div class="yellow-line-block"></div>
                                </div>      
                            </div>
                        </a>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                        <a href="{{ url('/') }}/user/reviews_and_ratings">       
                            <div class="completed-booking">
                                <div class="plane-image">
                                    <img src="{{ url('/') }}/front_assets/images/violet-plane.png" alt="" />
                                </div>
                                <div class="booking-content">
                                    <h2>{{$arr_review_data or '-'}}</h2>
                                    <h4>{{ trans('general.review') }} &amp; {{ trans('general.rating') }}</h4>
                                    <div class="violet-line-block"></div>
                                </div>      
                            </div>
                        </a>
                        </div>   
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 mobile-show">
                        <a href="{{ url('/') }}/user/transactions">
                            <div class="completed-booking">
                                <div class="plane-image">
                                    <img src="{{ url('/') }}/front_assets/images/red-plane.png" alt="" />
                                </div>
                                <div class="booking-content">
                                    <h2>{{ $obj_transaction or 'NA' }}</h2>
                                    <h4>My Transactions</h4>
                                    <div class="red-line-block"></div>
                                </div>      
                            </div>
                        </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                            <div class="notification-box">
                                <div class="notification-heading">  
                                    <h3>{{ trans('general.notifications') }}</h3>
                                    <div class="view-all">
                                        <a href="{{ url('/') }}/user/notifications">{{ trans('general.view_all') }}</a>
                                    </div>
                                    <div class="clearfix"></div>                
                                </div>
                                <?php $i = 0;?>
                            @if( isset($arr_notification) && $arr_notification_count > 0 )
                                @foreach( $arr_notification as $key => $value )
                                <div class="notification-heading">  
                                    <div class="notification-profile-image">
                                    @if(isset($value['sender_type']) && $value['sender_type'] == 'admin')
                                        @if(isset($value['get_admin_details']['profile_image']) && !empty($value['get_admin_details']['profile_image']) && File::exists($admin_base_img_path.$value['get_admin_details']['profile_image']))
                                            <img src="{{$admin_public_img_path.$value['get_admin_details']['profile_image']}}" style="height: 35px;" />
                                        @else
                                            <img src="{{ url('/') }}/uploads/admin/default_image/default-profile.png'" style="height: 35px;" />
                                        @endif  
                                    @elseif(isset($value['sender_type']) && $value['sender_type'] == 'aircraft_owner') 
                                        @if(isset($value['get_owner_details']['profile_image']) && !empty($value['get_owner_details']['profile_image']) && File::exists($operator_profile_base_img_path.$value['get_owner_details']['profile_image']))
                                            <img src="{{$operator_profile_public_img_path.$value['get_owner_details']['profile_image']}}" style="height: 35px;" />
                                        @else
                                            <img src="{{ url('/') }}/uploads/admin/default_image/default-profile.png'" style="height: 35px;" />
                                        @endif  
                                    @else
                                            <img src="{{ url('/') }}/uploads/admin/default_image/default-profile.png'" style="height: 35px;" />
                                    @endif
                                    </div>
                                    <div class="notification-profile-name">
                                        <h4>{{ $value['title'] }}</h4>
                                        <h5>
                                        @if(strlen($value['description']) >= 60 )
                                        {{ substr($value['description'], 0,60).'...' }}
                                        @else
                                        {{ $value['description'] }}
                                        @endif
                                        </h5>
                                    </div> 
                                    <div class="clearfix"></div>                
                                </div>
                                <?php
                                    $i++;
                                    if($i>=5) :
                                        break;
                                    endif;
                                ?>
                                @endforeach
                            @else
                                <div class="notify" >You have no Notifications
                                </div>
                            @endif    

                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-4 col-md-6 col-lg-4 mobile-hide">
                        <a href="{{ url('/') }}/user/transactions">
                            <div class="completed-booking">
                                <div class="plane-image">
                                    <img src="{{ url('/') }}/front_assets/images/red-plane.png" alt="" />
                                </div>
                                <div class="booking-content">
                                    <h2 style="font-size: 30px">{{ $obj_transaction or 'NA' }}</h2>
                                    <h4>My Transactions</h4>
                                    <div class="red-line-block"></div>
                                </div>      
                            </div>
                        </a>
                        </div>
                    </div>
                </div>

            </div>                
        </div>


    </section>
  @endsection