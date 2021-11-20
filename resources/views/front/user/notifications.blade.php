@extends('front.layout.master')
@section('main_content')

<section class="gray-bg-main-section">
    <div class="container">
        @if(count($arr_notification['data']) > 0)
            <div class="notification-content">
            @foreach( $arr_notification['data'] as $key => $value )
                <div class="notification-main">
                    <div class="notification-img-section">

                    @if(isset($value['sender_type']) && $value['sender_type'] == 'admin')
                            @if(isset($value['get_admin_details']['profile_image']) && !empty($value['get_admin_details']['profile_image']) && File::exists($admin_base_img_path.$value['get_admin_details']['profile_image']))
                                 <img src="{{get_resized_image($value['get_admin_details']['profile_image'] ,$admin_base_img_path,40,40)}}">
                            @else
                                <img src="{{ url('/') }}/uploads/admin/default_image/default-profile.png'" style="height: 35px;" />
                            @endif  
                    @elseif(isset($value['sender_type']) && $value['sender_type'] == 'aircraft_owner') 
                            @if(isset($value['get_owner_details']['profile_image']) && !empty($value['get_owner_details']['profile_image']) && File::exists($operator_profile_base_img_path.$value['get_owner_details']['profile_image']))
                                <img src="{{get_resized_image($value['get_owner_details']['profile_image'] ,$operator_profile_base_img_path,40,40)}}">
                            @else
                                <img src="{{ url('/') }}/uploads/admin/default_image/default-profile.png'" style="height: 35px;" />
                            @endif  
                    @else
                             <img src="{{ url('/') }}/uploads/admin/default_image/default-profile.png'" style="height: 35px;" />
                    @endif
                    </div>
                    <div class="notification-text">
                        <h4 style="font-weight: bold;">{{ $value['title'] }}</h4>

                        <a @if($value['redirect_url'] != '') href="{{ url('/').'/'.$value['redirect_url'] }}" @endif><h4 style="color: #3ca7f1">{{ $value['description'] or 'N/A'  }}</h4></a>
                        <h5><?php echo get_formated_date($value['created_at']) ?></h5>
                    </div>
                    <div class="clearfix"></div>
                </div>
            @endforeach
            </div>
        @else
            <div class="background-container">
                <div class="img-content">
                    <img src="{{ url('/') }}/front_assets/images/rocket.png" alt="">
                </div> 
                <div class="content-background">
                    <div class="result-not-found">You have no notifications</div>
                    <div class="please-try-again">Please check Later</div>
                </div>
            </div>
        @endif
    </div>
	<br>
    <div class="pagination">
        <ul>
            {{$page_link}}
        </ul>
    </div>
</section>

@endsection