
       <div class="left-side sticky-left-side">

        <!--logo and iconic logo start-->
        <div class="logo">
            <a href="#"><img src="{{url('/')}}/assets/admin_assets/images/logo.jpg" alt=""></a>
        </div>

        <div class="logo-icon text-center">
            <a href="#"><img src="{{url('/')}}/assets/admin_assets/images/logo_icon.jpg" alt=""></a>
        </div>
        <!--logo and iconic logo end-->

        <div class="left-side-inner">

            <!-- visible to small devices only -->
            <div class="visible-xs hidden-sm hidden-md hidden-lg">
                <div class="media logged-user">
                    <img alt="" src="{{url('/')}}/assets/admin_assets/images/photos/user-avatar.png" class="media-object">
                    <div class="media-body">
                        <h4><a href="#">John Doe</a></h4>
                        <span>"Hello There..."</span>
                    </div>
                </div>

                <h5 class="left-nav-title">Account Information</h5>
                <ul class="nav nav-pills nav-stacked custom-nav">
                  <li><a href="#"><i class="fa fa-user"></i> <span>Profile</span></a></li>
                  <li><a href="#"><i class="fa fa-cog"></i> <span>Settings</span></a></li>
                  <li><a href="#"><i class="fa fa-sign-out"></i> <span>Sign Out</span></a></li>
                </ul>
            </div>

            <!--sidebar nav start-->
            <ul class="nav nav-pills nav-stacked custom-nav">
                @if(get_admin_access('dashboard','module_view'))

                    <li class="@if(Request::segment(2) == 'dashboard') active @endif"><a href="{{url('/')}}/admin/dashboard"><i class="ti-home"></i> <span class="menu-label-span">{{ trans('sidebar.dashboard') }}</span></a>
                    </li>
                @endif
                 
                 @if(get_admin_access('site_setting','module_view'))

                     <li class="@if(Request::segment(2) == 'site_setting') active @endif"><a href="{{url('/')}}/admin/site_setting"><i class="icon-settings"></i> <span class="menu-label-span">{{ trans('sidebar.site setting') }}</span></a>
                     </li>
                 @endif

                @if(get_admin_access('user_role','module_view'))

                 <li class="@if(Request::segment(2) == 'user_role') active  @endif"><a href="{{url('/')}}/admin/user_role/"><i class="icon-user"></i> <span class="menu-label-span">{{ trans('sidebar.user role') }}</span></a>


                <ul class="sub-menu-list">
                @if(get_admin_access('user_role','create')) 
                <li class="@if(Request::segment(2) == 'create') active @endif"><a href="{{url('/')}}/admin/user_role/create"><span>Add</span></a></li>
                @endif

                <li class="@if(Request::segment(2) == 'index') active @endif"><a href="{{url('/')}}/admin/user_role/"><span>Manage </span></a></li>
                
                </ul>

                
                @endif
            </li>

                @if(get_admin_access('my_team','module_view'))

                     <li class="@if(Request::segment(2) == 'my_team') active @endif"><a href="{{url('/')}}/admin/my_team"><i class="icon-people"></i> <span class="menu-label-span">{{ trans('sidebar.my team') }}</span></a>
                    </li>
                @endif

                 @if(get_admin_access('finance_team','module_view'))

                 <li class="@if(Request::segment(2) == 'finance_team') active  @endif"><a href="{{url('/')}}/admin/finance_team/"><i class="icon-people"></i> <span class="menu-label-span">{{ trans('sidebar.finance team') }}</span></a>

                    <ul class="sub-menu-list">
                        @if(get_admin_access('finance_team','create')) 
                        <li class="@if(Request::segment(2) == 'create') active @endif"><a href="{{url('/')}}/admin/finance_team/create"><span>Add</span></a></li>
                        @endif
                        <li class="@if(Request::segment(2) == 'index') active @endif"><a href="{{url('/')}}/admin/finance_team/"><span>Manage</span></a></li>
                    </ul>                    

                @endif
                </li>
                @if(get_admin_access('voters','module_view'))
                    <li class="@if(Request::segment(2) == 'voters') active @endif"><a href="{{url('/')}}/admin/voters"><i class="ti-user"></i> <span class="menu-label-span">{{ trans('sidebar.voters') }}</span></a>
                    </li>
                @endif
               {{--  @if(get_admin_access('voters','module_view'))
                    <li class="@if(Request::segment(2) == 'aadhar') active @endif"><a href="{{url('/')}}/admin/aadhar"><i class="ti-user"></i> <span class="menu-label-span">Aadhar Card</span></a>
                    </li>
                @endif --}}
                @if(get_admin_access('voter_money_distribution','module_view'))
                 <li class="@if(Request::segment(2) == 'voter_money_distribution') active  @endif"><a href="{{url('/')}}/admin/voter_money_distribution/"><i class="ti-menu"></i> <span class="menu-label-span">{{ trans('sidebar.voter money distribution') }}</span></a>

                    <ul class="sub-menu-list">
                        @if(get_admin_access('voter_money_distribution','create')) 
                        <li class="@if(Request::segment(2) == 'create') active @endif"><a href="{{url('/')}}/admin/voter_money_distribution/create"><span>Transfer</span></a></li>
                        @endif
                        <li class="@if(Request::segment(2) == 'index') active @endif"><a href="{{url('/')}}/admin/voter_money_distribution/"><span>Manage</span></a></li>                         
                        {{-- <li class="@if(Request::segment(2) == 'create') active @endif"><a href="{{url('/')}}/admin/money_distribution/create_voter"><span>Voter Money Distribution</span></a></li> 
                        <li class="@if(Request::segment(2) == 'index') active @endif"><a href="{{url('/')}}/admin/money_distribution/index_history"><span>View Voter</span></a></li>  --}}                
                    </ul>                    

                @endif
                </li>
                @if(get_admin_access('group','module_view'))

                     <li class="@if(Request::segment(2) == 'group') active @endif"><a href="{{url('/')}}/admin/group"><i class="icon-people"></i> <span class="menu-label-span">Group</span></a>
                    </li>
                @endif
                @if(get_admin_access('voters','module_view'))
                    <li class="@if(Request::segment(2) == 'voting_card') active @endif"><a href="{{url('/')}}/admin/voting_card"><i class="ti-user"></i> <span class="menu-label-span">{{ trans('sidebar.voting card') }}</span></a>
                    </li>
                @endif
                @if(get_admin_access('wards','module_view'))
                 <li class="@if(Request::segment(2) == 'wards') active  @endif"><a href="{{url('/')}}/admin/wards/"><i class="ti-home"></i> <span class="menu-label-span">{{ trans('sidebar.voting wards') }}</span><!--<i class="icon-arrow-right"></i>--><!-- </a> -->

                <ul class="sub-menu-list">
                @if(get_admin_access('wards','create')) 
                <li class="@if(Request::segment(2) == 'create') active @endif"><a href="{{url('/')}}/admin/wards/create"><span>Add </span></a></li>
                @endif
                <li class="@if(Request::segment(2) == 'index') active @endif"><a href="{{url('/')}}/admin/wards/"><span>Manage </span></a></li>            

                </ul>                

                @endif
                </li>               
                @if(get_admin_access('voting_booth','module_view'))
                <li class="menu-list @if(Request::segment(2) == 'voting_booth') active  @endif"><a href="javascript:void(0)"><i class="ti-home"></i> <span class="menu-label-span">{{ trans('sidebar.voting booth and list') }}</span><i class="icon-arrow-right"></i></a>
                <ul class="sub-menu-list">

                @if(get_admin_access('voting_booth','create')) 
                <li class="@if(Request::segment(2) == 'create') active @endif"><a href="{{url('/')}}/admin/voting_booth/create"><span>Add</span></a></li>
                @endif

                <li class="@if(Request::segment(2) == 'index') active @endif"><a href="{{url('/')}}/admin/voting_booth/"><span>{{ trans('sidebar.manage booth') }} </span></a></li>

                <!-- @if(get_admin_access('voting_booth','create'))
                <li class="@if(Request::segment(2) == 'create_list')  active @endif"><a href="{{url('/')}}/admin/voting_booth/create_list"><span>Add List</span></a></li>
                @endif -->

                <li class="@if(Request::segment(2) == 'manage_list')  active @endif"><a href="{{url('/')}}/admin/voting_booth/manage_list"><span>{{ trans('sidebar.manage list') }}</span></a></li>            
                </ul>                
                @endif
                </li>
                @if(get_admin_access('email_template','module_view'))
                <li class="@if(Request::segment(2) == 'email_template') active @endif"><a href="{{url('/')}}/admin/email_template"><i class="ti-email"></i> <span class="menu-label-span">{{ trans('sidebar.email template') }}</span></a>
                </li>
                @endif  
                @if(get_admin_access('sms_template','module_view'))
                <li class="@if(Request::segment(2) == 'sms_template' && Request::segment(3) != 'send_sms') active @endif"><a href="{{url('/')}}/admin/sms_template"><i class="ti-email"></i> <span class="menu-label-span">SMS Template</span></a>
                </li>
                @endif 
                @if(get_admin_access('send_sms','module_view'))
                <li class="@if(Request::segment(2) == 'cron_schedule') active @endif"><a href="{{url('/')}}/admin/cron_schedule"><i class="ti-email"></i> <span class="menu-label-span">Send SMS</span></a>
                </li>
                @endif                                                 
                @if(get_admin_access('notification','module_view'))
                    <li class="@if(Request::segment(2) == 'notification') active @endif"><a href="{{url('/')}}/admin/notification"><i class="ti-bell"></i> <span class="menu-label-span">{{ trans('sidebar.notification') }}</span></a>
                    </li>
                @endif                
             
            </ul>
            <!--sidebar nav end-->

        </div>
    </div>