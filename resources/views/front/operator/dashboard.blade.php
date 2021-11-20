@extends('front.layout.master')
@section('main_content')
<div class="header-index white-bg-header after-login-header" id="header-home"></div>
<style type="text/css">
    .notify
    {
        text-align: center; margin: 95px;font-size: 25px;
    }
</style>

<section class="middle-section-main">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3">
                <div class="profile-box">
                    <div class="green-background">
                        <a href="{{ url('/') }}/operator/profile" title="Edit Profile">
                            <i class="fa fa-edit"></i>
                        </a>
                    </div>
                    <div class="profile-image">
                     @if(isset($arr_data['profile_image']) && !empty($arr_data['profile_image']) && File::exists($operator_profile_base_img_path.$arr_data['profile_image']))
                     <img src="{{$operator_profile_public_img_path.$arr_data['profile_image']}}"  alt="" style="height: 100px" />
                     @php 
                     $prev_image_url = $operator_profile_public_img_path.$arr_data['profile_image']; 
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
                    <h3>Aircraft Operator</h3>
                    <h4>{{$arr_data['address']}}</h4>
                </div>
                <div class="line-block"></div>
          <!--       <div class="social-icons">
                    <a href="{{isset( $arr_settings_data['fb_url'] ) && !empty( $arr_settings_data['fb_url'] ) ? $arr_settings_data['fb_url'] : ''}}"><i class="fa fa-facebook"></i></a>
                    <a href="{{isset( $arr_settings_data['twitter_url'] ) && !empty( $arr_settings_data['twitter_url'] ) ? $arr_settings_data['twitter_url'] : ''}}"><i class="fa fa-twitter"></i></a>
                    <a href="{{isset( $arr_settings_data['linkedin_url'] ) && !empty( $arr_settings_data['linkedin_url'] ) ? $arr_settings_data['linkedin_url'] : ''}}"><i class="fa fa-linkedin"></i></a>                            
                    <a href="{{isset( $arr_settings_data['gmail_url'] ) && !empty( $arr_settings_data['gmail_url'] ) ? $arr_settings_data['gmail_url'] : 'javascript:void(0)'}}"><i class="fa fa-google"></i></a>                            
                </div>  -->                       
            </div>
        </div>

        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-9">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                <a href="{{ url('/') }}/operator/completed_bookings">
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
                <a href="{{ url('/') }}/operator/pending_bookings">
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
                <a href="{{ url('/') }}/operator/reviews_and_ratings">
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
                <a href="{{ url('/') }}/operator/transactions">
                    <div class="completed-booking">
                        <div class="plane-image">
                            <img src="{{ url('/') }}/front_assets/images/red-plane.png" alt="" />
                        </div>
                        <div class="booking-content">
                            <h2>95</h2>
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
                                <a href="{{ url('/') }}/operator/notifications">{{ trans('general.view_all') }}</a>
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
                                            <img src="{{$admin_public_img_path.$value['get_admin_details']['profile_image']}}" style="height: 35px;width: 40px" />
                                        @else
                                            <img src="{{ url('/') }}/uploads/admin/default_image/default-profile.png'" style="height: 35px;width: 40px" />
                                        @endif  
                                    @elseif(isset($value['sender_type']) && $value['sender_type'] == 'user') 
                                        @if(isset($value['get_user_details']['profile_image']) && !empty($value['get_user_details']['profile_image']) && File::exists($user_profile_base_img_path.$value['get_user_details']['profile_image']))
                                            <img src="{{$user_profile_public_img_path.$value['get_user_details']['profile_image']}}" style="height: 35px;width: 40px" />
                                        @else
                                            <img src="{{ url('/') }}/uploads/admin/default_image/default-profile.png'" style="height: 35px;width: 40px" />
                                        @endif  
                                    @else
                                            <img src="{{ url('/') }}/uploads/admin/default_image/default-profile.png'" style="height: 35px;width: 40px" />
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
                            <?php $i++;
                            if($i>=5){
                                break; 
                            }   ?>
                            @endforeach
                        @else
                            <div class="notify" >You have no Notifications
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-xs-12 col-sm-4 col-md-6 col-lg-4 mobile-hide">
                <a href="{{ url('/') }}/operator/transactions">
                    <div class="completed-booking">
                        <div class="plane-image">
                            <img src="{{ url('/') }}/front_assets/images/red-plane.png" alt="" />
                        </div>
                        <div class="booking-content">
                            <h2>{{ $obj_transactions or 'NA' }}</h2>
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