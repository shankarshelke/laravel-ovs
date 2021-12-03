
<!--body wrapper start-->
<style>
a {
  color: black;
}
a:hover {
  color: white;
}
</style>
<body>
    <div class="wrapper dashboard-section-main">
        <div class="row states-info">
        @if(get_admin_access('my_team','module_view'))
            <div class="col-md-3">
                <div class="panel red-bg">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-4">                                
                                <i class="icon-people"></i>
                            </div>
                            <div class="col-xs-8">
                                @if(get_admin_access('my_team','module_view'))
                                    {{-- <span class="state-title"> Team Members </span> --}}
                                    <a href="{{url('/')}}/admin/my_team/"><span class="state-title"><h4>{{ trans('dashboard.team members') }}</h4></span></a>
                                    <h4>{{count($arr_admin)}}</h4>
                                @else
                                <a href="#"><span class="state-title"><h4>{{ trans('dashboard.team members') }}</h4></span></a>
                                    <h4>{{count($arr_admin)}}</h4>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if(get_admin_access('finance_team','module_view'))
            <div class="col-md-3">
                <div class="panel blue-bg">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-4">                                
                                <i class="icon-user"></i>
                            </div>
                            <div class="col-xs-8">
                                @if(get_admin_access('finance_team','module_view'))
                                    <a href="{{url('/')}}/admin/finance_team/"><span class="state-title"><h4>{{ trans('dashboard.finance member') }}</h4></span></a>
                                    <h4>{{count($arr_financeteam)}}</h4>
                                @else
                                    <a href="#"><span class="state-title"><h4>{{ trans('dashboard.finance member') }}</h4></span></a>
                                    <h4>{{count($arr_financeteam)}}</h4>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
<!--         @if(get_admin_access('user_role','module_view'))
            <div class="col-md-3">
                <div class="panel red-bg">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-4">
                                <i class="icon-tag"></i>
                            </div>
                            <div class="col-xs-8">
                                @if(get_admin_access('user_role','module_view'))
                                    <a href="{{url('/')}}/admin/user_role/"><span class="state-title"><h4>{{ trans('dashboard.total role') }}</h4></span></a>
                                    <h4>{{count($arr_roles)}}</h4>
                                @else
                                <a  href="#"><span class="state-title"><h4>{{ trans('dashboard.total role') }}</h4></span></a>
                                    <h4>{{count($arr_roles)}}</h4>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif -->
        @if(get_admin_access('voters','module_view'))
            <div class="col-md-3">
                <div class="panel green-bg">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-4">
                                <i class="fa fa-gavel"></i>
                            </div>
                            <div class="col-xs-8">
                                @if(get_admin_access('voters','module_view'))
                                    <a href="{{url('/')}}/admin/voters/"><span class="state-title"><h4>{{ trans('dashboard.registered voter') }}</h4></span></a>
                                    <h4>{{count($arr_users)}}</h4>
                                @else
                                    <a href="#"><span class="state-title"><h4> {{ trans('dashboard.registered voter') }} </h4></span></a>
                                    <h4>{{count($arr_users)}}</h4>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if(get_admin_access('voter_money_distribution','module_view'))
            <div class="col-md-3">
                <div class="panel blue-bg">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-4">
                                <i class="icon-eye"></i>
                            </div>
                            @if($ad_type=='SUBADMIN')
                                <div class="col-xs-8">
                                    <a href="{{url('/')}}/admin/voter_money_distribution/"><span class="state-title"><h4>{{ trans('dashboard.recieved money') }}</h4></span></a>
                                    <!--{{setlocale(LC_MONETARY, 'en_IN')}}-->

                                    <h4>{{ trans('dashboard.rs') }}.{{$amount = number_format($admin_money,2)}}</h4>
                                </div>
                            @else
                                <div class="col-xs-8">
                                    <a href="{{url('/')}}/admin/money_distribution/"><span class="state-title"><h4>{{ trans('dashboard.admin money distributed') }}</h4></span></a>
                                    <!--{{setlocale(LC_MONETARY, 'en_IN')}}-->
                                    <h4>{{ trans('dashboard.rs') }}.{{$amount = number_format($admin_money,2)}}</h4>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if($ad_type=='SUBADMIN' && get_admin_access('voter_money_distribution','module_view'))
            <div class="col-md-3">
                <div class="panel green-bg">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-4">
                                <i class="icon-eye"></i>
                            </div>
                            <div class="col-xs-8">
                                <a href="{{url('/')}}/admin/voter_money_distribution/"><span class="state-title"><h4>{{ trans('dashboard.wallet money') }}</h4></span></a>
                                <!--{{setlocale(LC_MONETARY, 'en_IN')}}-->
                                <h4>{{ trans('dashboard.rs') }}.{{$amount = number_format( $admin_money-$voter_money,2)}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if($ad_type=='SUBADMIN' &&get_admin_access('voter_money_distribution','module_view'))
            <div class="col-md-3">
                <div class="panel green-bg">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-4">
                                <i class="icon-eye"></i>
                            </div>
                            <div class="col-xs-8">
                                <a href="{{url('/')}}/admin/voter_money_distribution/"><span class="state-title"><h4>{{ trans('dashboard.voter money distributed') }}</h4></span></a>
                                <!--{{setlocale(LC_MONETARY, 'en_IN')}}-->
                                <h4>{{ trans('dashboard.rs') }}.{{$amount = number_format($voter_money,2)}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif(get_admin_access('voter_money_distribution','module_view'))
            <div class="col-md-3">
                <div class="panel green-bg">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-4">
                                <i class="icon-eye"></i>
                            </div>
                            <div class="col-xs-8">
                                <a href="{{url('/')}}/admin/voter_money_distribution/"><span class="state-title"><h4>{{ trans('dashboard.voter money distributed') }}</h4></span></a>
                                <!--{{setlocale(LC_MONETARY, 'en_IN')}}-->
                                <h4>{{ trans('dashboard.rs') }}.{{$amount = number_format($voter_money,2)}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
          @if(get_admin_access('voters','module_view'))
            <div class="col-md-3">
                <div class="panel green-bg">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-4">
                                <i class="icon-eye"></i>
                            </div>
                            <div class="col-xs-8">
                                <a href="{{url('/')}}/admin/voters/today_birthday">
                                    <span class="state-title"><h4>{{ trans('dashboard.todays birthday') }}</h4></span>
                                </a>
                                <!--{{setlocale(LC_MONETARY, 'en_IN')}}-->
                                <h4>{{$today_birthday}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
            @endif       
        </div>
    <div class="row"> 
        <div class="col-md-6">   
            <div id="piechart" style="height: 400px;"></div>
             {{-- <iframe src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDH-qeHRuYeaviYcdEvwggMbMIvUndf_vw&amp;q={{$arr_site_setting['lat'] or '-34.397'}},{{$arr_site_setting['lon'] or '150.644'}}" height="500" width="100%" frameborder="0" style="border: 0;" allowfullscreen=""></iframe> --}}


            </div>
        <div class="col-md-6">   
            <div id="piechart_2" style="height: 400px;"></div>
             {{-- <iframe src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDH-qeHRuYeaviYcdEvwggMbMIvUndf_vw&amp;q={{$arr_site_setting['lat'] or '-34.397'}},{{$arr_site_setting['lon'] or '150.644'}}" height="500" width="100%" frameborder="0" style="border: 0;" allowfullscreen=""></iframe> --}}
            

            </div>            
        </div>
    </div>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Task', 'Voters Surity'],
          ['None',<?= $none_surity;?>],
          ['Not Sure',<?=$not_surity;?>],
          ['Half Sure',<?= $half_surity;?>],
          ['Full Sure', <?= $full_surity;?>]

        ]);

        var options = {
          title: 'Voting Surity'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Task', 'Voters Contact Number Not Available'],
          ['Total Voters',<?= $total_user; ?>],
          ['Not Available',<?= $not_available; ?>],
         

        ]);

        var options = {
          title: 'Voters Contact Number Not Available'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_2'));

        chart.draw(data, options);
      }
    </script>    
<!--body wrapper end-->