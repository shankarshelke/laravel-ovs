<!-- header section start-->
<style type="text/css">
    .notification-image-section{float:left;}
    .notification-name{text-overflow: ellipsis;overflow: hidden;white-space: nowrap;display: block;margin-left: 40px;margin-top: 3px;}
    .dropdown-list.normal-list{width: 300px; }
    .msg{text-overflow: ellipsis;overflow: hidden;white-space: nowrap;display: block;margin-top: 3px;}
</style>
    <div class="header-section">
    {{csrf_field()}}
        <!--toggle button start-->
        <a class="toggle-btn menu-btn-section"><i class="icon-menu"></i></a>
        <!--toggle button end-->
        <!--notification menu start -->
        <div class="menu-right">
            <ul class="notification-menu">
                <li>
                    @if(\App::isLocale('mr'))
                        <li class="language-section-main responsive-menu-hide">
                            <a class="btn btn-default info-number" href="{{ url('/').'/lang/en' }}">
                                <img src="{{url('/')}}/assets/admin_assets/flags/flag-eng.jpg" alt="" />EN
                            </a>
                        </li>
                    @elseif(\App::isLocale('en'))
                        <li class="language-section-main responsive-menu-hide" style="color: white";>
                            <a class="btn btn-default info-number" href="{{ url('/').'/lang/mr' }}">
                                <img src="{{url('/')}}/assets/admin_assets/flags/india-flag.jpg" alt="" />MR
                            </a>
                        </li>
                    @endif
                    
                 </li>
                <li>
                    <a href="#" class="btn btn-default dropdown-toggle info-number" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                        <span class="badge">{{ $contact_enquiries_count or '0' }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-head pull-right">
                        <h5 class="title">{{ trans('header.you have') }} {{ $contact_enquiries_count or '0' }}{{ trans('header.enquiries') }}  </h5>
                        <ul class="dropdown-list normal-list">
                        @if(isset($contact_enquiries) && $contact_enquiries != '' )   
                            @if( $contact_enquiries_count >0 )   
                                @foreach( $contact_enquiries as $enquiry )
                                    <li class="new">
                                        <a href="{{ url('/') }}/admin/contact_enquiry/reply/{{ base64_encode($enquiry['id']) }}">
                                            <span class="desc">
                                              <span class="name">{{ $enquiry['first_name'] or '' }} {{ $enquiry['last_name'] or '' }} <span class="badge badge-success"><?php if($enquiry['status'] == 0){
                                                                        echo "Not replied";
                                                                    }
                                                                    else
                                                                    {
                                                                        echo "replied";
                                                                    }
                                              ?></span></span>
                                              <span class="msg">{{ $enquiry['message'] or '' }}</span>
                                            </span>
                                        </a>
                                    </li>
                                @endforeach
                            @else
                                <p class="text-info" style="text-align: center;margin-top: 15px">{{ trans('header.no new enquiries') }}</p>
                            @endif
                        @endif
                            <li class="new"><a href="{{ url('/') }}/admin/contact_enquiry">{{ trans('header.see all enquiries') }}</a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#" class="btn btn-default dropdown-toggle info-number" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="badge">{{ $notification_count or '0' }}</span>
                    </a>
                        <div class="dropdown-menu dropdown-menu-head pull-right">
                            <h5 class="title">{{ trans('header.notifications') }}</h5>
                            <ul class="dropdown-list normal-list">
                        @if(isset($notifications) && $notifications!='' )
                            @if( $notification_count >0 )    
                                @foreach( $notifications as $key => $value )
                                <li class="new">
                                    <a href="{{ url('/') }}/admin/notification">
                                        <span class="notification-image-section">
                                            @if(isset($value['get_user_details']['profile_image']) && !empty($value['get_user_details']['profile_image']) && File::exists($user_profile_image_base_path.$value['get_user_details']['profile_image']))
                                                <img style="width: 30px;height: 30px;border-radius: 15px;    " src="{{ $user_profile_image_public_path.$value['get_user_details']['profile_image'] }}">
                                            @else
                                                <img style="width: 30px;height: 30px;border-radius: 15px;" src="{{url('/')}}/uploads/default/no-img-user-profile.jpg" alt="" />
                                            @endif
                                        </span>
                                        <span class="notification-name">{{ $value['title'] or '' }}</span>
                                    </a>
                                </li>
                                @endforeach
                            @else
                                <p class="text-info" style="text-align: center;margin-top: 15px">{{ trans('header.see all notifications') }}</p>
                            @endif
                        @endif

                                <li class="new"><a href="{{url('/')}}/admin/notification">{{ trans('header.no new notifications') }} </a></li>

                            </ul>
                        </div>
                </li>
                <li>
                    <a href="#" class="btn btn-default dropdown-toggle info-number" data-toggle="dropdown">
                    @if(isset($shared_admin_details) && !empty($shared_admin_details['profile_image']) && File::exists($profile_image_base_img_path.$shared_admin_details['profile_image']))
                        <img src="{{$profile_image_public_img_path.$shared_admin_details['profile_image']}}" alt="" />
                    @else
                        <img src="{{url('/')}}/uploads/default/no-img-user-profile.jpg" alt="" />
                    @endif
                        {{$shared_admin_details['first_name'] or 'NA'}}
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                        <li><a href="{{url('/')}}/admin/account_setting"><i class="icon-user"></i>{{ trans('header.profile') }} </a></li>
                        <li><a href="{{url('/')}}/admin/password/change"><i class="icon-settings"></i> {{ trans('header.change password') }}</a></li>
                    @if( is_user_logged_in('admin') )
                        <li><a href="{{url('/')}}/admin/logout"><i class="icon-logout"></i>{{ trans('header.log out') }}</a></li>
                    @else
                        <li><a href="{{url('/')}}/admin/sign_in"><i class="icon-login"></i>{{ trans('header.sign in') }}</a></li>
                    @endif    
                    </ul>
                </li>

            </ul>
        </div>
        <!--notification menu end -->
    </div>
<!-- header section end-->