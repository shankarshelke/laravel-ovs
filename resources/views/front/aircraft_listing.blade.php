@extends('front.layout.master')
@section('main_content')
@include('front.layout.breadcrumb')

<style type="text/css">
    .form-group{width: 100%}
</style>

<section class="gray-bg-main-section">
    <div class="container">
        @include('front.layout.operation_status')
        <form action="{{ url('/') }}/listing" id="filter-form">
            <div class="row search-filter-main-section">
                <div class="col-sm-4 col-md-4 col-lg-4 onclick-show">
                    <div class="filter-search-main">

                        <?php
                        $keyword         = isset($_GET['key'])? $_GET['key']:'';
                        $type            = isset($_GET['aircraft_type'])? $_GET['aircraft_type']:'';
                        $selected_model  = isset($_GET['model'])? $_GET['model']:'';
                        $base_operation  = isset($_GET['base_operation'])? $_GET['base_operation']:'';
                        $no_aircraft     = isset($_GET['no_of_aircraft'])? $_GET['no_of_aircraft']:'';
                        $op_capabilities = isset($_GET['op_capability'])? $_GET['op_capability']:'';
                        $pickup_date     = isset($_GET['pickup_date'])? $_GET['pickup_date']:'';
                        $return_date     = isset($_GET['return_date'])? $_GET['return_date']:'';
                        $location        = isset($_GET['location'])? $_GET['location']:'';
                        $lat             = isset($_GET['lat'])? $_GET['lat']:'';
                        $lng             = isset($_GET['lng'])? $_GET['lng']:'';
                        ?>

                        <label>Aircraft Type</label>
                        <div class="form-group">
                            <i class="fa fa-angle-down"></i>
                            <select id='aircraft_type' name='aircraft_type' data-rule-required="true">
                                <option value=''> -- Select Aircraft Type -- </option>
                                <option value='fixed_wings' {{ $type == 'fixed_wings' ? 'selected' : '' }} >Fixed Wings</option>
                                <option value='rotary_wings' {{ $type == 'rotary_wings' ? 'selected' : '' }} >Rotary Wings</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-md-4 col-lg-4 onclick-show">
                    <div class="filter-search-main">
                        <label>
                            Aircraft Model 
                            <a class="tooltips" href="#"><i class="fa fa-info-circle"></i><span class="tool-main">Drop down menu of ICAO aircraft type list</span></a>
                        </label>
                        <div class="form-group">
                            <i class="fa fa-angle-down"></i>
                            <select name='model' data-rule-requird="true" id="aircraft_models">
                                <option value="">-- Select Model --</option>
                                @if(isset($arr_aircraft_type) && !empty($arr_aircraft_type))
                                @foreach($arr_aircraft_type as $model)
                                <option value="{{ $model['id'] or '' }}" {{ $model['id'] == $selected_model ? 'selected' : '' }}>{{ $model['model_name'] }}</option>
                                @endforeach
                                @endif
                            </select>                        
                        </div>   
                    </div>
                </div>
                <div class="col-sm-4 col-md-4 col-lg-4 onclick-show">
                    <div class="filter-search-main">
                        <label>Type of Operation</label>
                        <div class="form-group">
                            <i class="fa fa-angle-down"></i>
                            <?php $arr_operations = !empty(config('app.project.operational_capabilities')) ? config('app.project.operational_capabilities') : []; ?>
                            <select id='op_capability' name='op_capability'>
                                <option value="">-- Select --</option>          
                                @if(!empty($arr_operations))
                                @foreach($arr_operations as $key => $option)
                                <option value="{{ $key }}" {{ $op_capabilities == $key ? 'selected' : '' }} > {{ $option }} </option>                            
                                @endforeach
                                @endif
                            </select>                        
                        </div>
                    </div>
                </div>
          <!--       <div class="col-sm-4 col-md-4 col-lg-4 onclick-show">
                    <div class="filter-search-main">
                        <label>
                            Base of Operations
                            <a class="tooltips base-operations-tooltip" href="#"><i class="fa fa-info-circle"></i><span class="tool-main">Select country list between a-z</span></a>
                        </label>
                        <div class="form-group">
                            <i class="fa fa-angle-down"></i>                                         
                            <select class="form-control" name="base_operation" id="base_of_operation" data-rule-requird="true">
                                <option value =''> -- Select -- </option>
                                @if(isset($arr_countries) && !empty($arr_countries))
                                @foreach($arr_countries as $country)
                                <option value="{{ isset($country['short_name'])?$country['short_name']:'' }}" {{ $base_operation == $country['short_name'] ? 'selected' : '' }} >{{ $country['name'] }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div> -->
                <div class="col-sm-4 col-md-4 col-lg-4 onclick-show">
                    <div class="filter-search-main">
                        <label>Location</label>
                        <div class="form-group">
                            <input type="text" placeholder="Location of Aircraft" id ="pickup_location" name="location" autocomplete="off" value="{{ $location }}" />
                            <input type="hidden" name="lat" id="default_lat" value="{{ $lat }}">
                            <input type="hidden" name="lng" id="default_lng" value="{{ $lng }}">
                        </div>
                    </div>
                </div>
                {{-- <div class="col-sm-4 col-md-4 col-lg-4 onclick-show">
                    <div class="filter-search-main">
                        <label>Drop Location</label>
                        <div class="form-group">
                            <input type="text" placeholder="Drop Location of Aircraft" id ="drop_location" name="drop_location" autocomplete="off">
                        </div>   
                    </div>
                </div> --}}
                <div class="col-sm-4 col-md-4 col-lg-4 onclick-show">
                    <div class="filter-search-main">
                        <label>
                            Number of Aircraft Required
                            <a class="tooltips base-operations-tooltip" href="#"><i class="fa fa-info-circle"></i><span class="tool-main">Numerical value between 1-10</span></a>
                        </label>
                        <div class="form-group">
                            <i class="fa fa-angle-down"></i>
                            <select id ='no_of_aircraft' name="no_of_aircraft"  class="no_of_aircraft">
                               <option value = ''>-- Select --</option>                            
                               @for($i = 1 ; $i <=10 ; $i++)
                               <option value='{{$i}}' @if($i == $no_aircraft) selected="" @endif >{{$i}}</option>
                               @endfor
                           </select>
                       </div>
                   </div>
                </div>
                <div class="col-sm-3 col-md-3 col-lg-3" id="class-4">
                    <div class="filter-search-main">
                        <label>Keyword</label>
                        <div class="form-group">
                            <span><i class="fa fa-search"></i></span>
                            <input type="text" placeholder="Search" name="key" id='main_search' autocomplete="off" value="{{ $keyword or '' }}" />
                        </div>
                    </div>
                </div>
                <div class="col-sm-7 col-md-7 col-lg-6" id="class-8">
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <div class="filter-search-main date-time-search">
                                <label>Start Date</label>
                                <div class="row m-l--5 m-r--5">
                                    <div class="col-sm-12 col-md-12 col-lg-12 p-l-5 p-r-5">
                                        <div class="form-group">
                                            <span><i class="fa fa-calendar"></i></span>
                                            <input type="text" placeholder="Date" id='pickup_date' class="form-control date_picker" name="pickup_date" value="{{$pickup_date}}" autocomplete="off"/>    
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <div class="filter-search-main date-time-search">
                                <label>Return Date</label>
                                <div class="row m-l--5 m-r--5">
                                    <div class="col-sm-12 col-md-12 col-lg-12 p-l-5 p-r-5">
                                        <div class="form-group">
                                            <span><i class="fa fa-calendar"></i></span>
                                            <input type="text" placeholder="Date" id='return_date' class="form-control date_picker" name="return_date" value="{{$return_date}}" autocomplete="off"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                    
                </div>
                {{-- <div class="col-sm-10 col-md-10 col-lg-10 onclick-show">
                    <div class="filter-search-main estimated-flight-hours-main">
                        <label>Estimated Flight Hours</label>
                        <div class="check-block">
                            <input id="filled-in-box" class="filled-in" name ='hours[]' type="checkbox">
                            <label for="filled-in-box"> > 50</label>
                        </div>                                                        
                        <div class="check-block">
                            <input id="filled-in-box2" class="filled-in" name ='hours[]' type="checkbox">
                            <label for="filled-in-box2"> <i class="fa fa-angle-left"></i> 50</label>
                        </div>
                    </div>
                </div> --}}
                <div class="col-sm-2 col-md-2 col-lg-3">
                    <label>&nbsp;</label>
                    <div class="search-filter-button">
                        <button type="submit" class="filter-search-btn"><i class="fa fa-search"></i> Search</button>
                        <a class="filter-icon-btn" href="javascript:void(0)"><img src="{{url('/')}}/front_assets/images/filter-icon-img.png" alt="" /></a>
                        <a class="filter-icon-btn reset-form" href="javascript:void(0)"><i class="fa fa-repeat"></i></a>
                    </div>
                </div>
            </div>
        </form>

        <div class="showing-txt-section">
            Showing {{ $arr_aircraft['from'] }} – {{ $arr_aircraft['to']}} of {{$arr_aircraft['total']}} results
        </div>
        <div class="row">
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
            @if(isset($arr_aircraft['data']) && sizeof($arr_aircraft['data']) > 0 )
            @foreach($arr_aircraft['data'] as $aircraft)
            <div class="col-sm-4 col-md-4 col-lg-4">
                <a href="{{ url('/').'/details/'.base64_encode($aircraft['id']) }}" class="main-quote similar-air-box">
                    <div class="img-quote">
                        @if(isset($aircraft['get_image']) && isset($aircraft['get_image']['images']) && file_exists($aircraft_images_base_img_path.'thumb_320x205/'.$aircraft['get_image']['images']))
                        <img src="{{$aircraft_images_public_img_path}}thumb_320x205/{{$aircraft['get_image']['images'] or ''}}" alt="" class="img-responsive" />
                        @else
                        <img src="{{url('/')}}/front_assets/images/default-img-200.png" alt=" "  class="img-responsive" />
                        @endif
                        <div class="id-quote-page1">{{isset($aircraft['type_name'])? ucwords(str_replace('_',' ',$aircraft['type_name'])): '' }}</div>
                    </div>
                    <!-- <div class="content-quote"><h2>{{isset($aircraft['name'])? $aircraft['name']:''}}</h2></div> -->
                    <div class="content-quote"><h2>{{isset($aircraft['get_aircraft_type']['model_name'])? $aircraft['get_aircraft_type']['model_name']:''}}</h2></div>
                    <div class="ratings">
                    <?php
                    $tot_reviews = $reviews_cnt = $avg_reviews = 0;

                    if(isset($aircraft['get_reviews']) && count($aircraft['get_reviews']) > 0){
                        $reviews_cnt = count($aircraft['get_reviews']);
                        foreach($aircraft['get_reviews'] as $row){
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
                <div class="content-date-quote">
                    <p>
                        @if(isset($aircraft['description']) && $aircraft['description'] != '')
                        @if(strlen($aircraft['description']) > 150)
                        {{ substr($aircraft['description'], 0,100).'...' }}
                        @else
                        {{ $aircraft['description'] }}
                        @endif
                        @endif
                    </p> 
                </div>
                <div class="price-quote2">
                    {{ $aircraft['distance'] or '' }}
                    <span class="pricing" style="color: black;">Pricing: </span> {{isset($aircraft['price_per_hour'])? get_formatted_price($aircraft['price_per_hour'],$cny_price) : ''}}<span class="price-content"> {{-- Approx based on the select --}}</span>
                </div>
            </a>
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
    {{ $page_link }} 
</div>
</div>
</section>

<link rel="stylesheet" type="text/css" href="{{url('/')}}/front_assets/css/bootstrap-datepicker.min.css"/>
<script src="{{url('/')}}/front_assets/js/bootstrap-datepicker.min.js" type="text/javascript"></script> 
<link href="{{url('/')}}/front_assets/css/fullcalendar.css" rel="stylesheet">
<script type="text/javascript" src="{{url('/')}}/front_assets/js/fullcalendar.min.js"></script>
<!-- <script src="{{url('/')}}/front_assets/js/jquery.countryselector.js"></script> -->
<script src="{{ url('/') }}/web_admin/assets/js/pages/jquery.geocomplete.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3&key={{ get_google_map_api_key() }}&libraries=places"></script>
<script type="text/javascript">

    $(document).ready(function(){
        $("#base_of_operation").change(function(){

            var country_name = $("#base_of_operation").val();

            var input1 = document.getElementById('pickup_location');
            var options = {
                types: ['(regions)'],
                componentRestrictions: {country: "{{$country_name}}"}
            };

            autocomplete = new google.maps.places.Autocomplete(input1, options);

            var input = document.getElementById('drop_location');
            var options1 = {
                types: ['(regions)'],
                componentRestrictions: {country: "{{$country_name}}"}
            };

            autocomplete1 = new google.maps.places.Autocomplete(input, options1);

            if($('#base_of_operation').val() ==''){
                var input = document.getElementById('drop_location');
                var options1 = {
                    types: ['(regions)'],
                };
                autocomplete1 = new google.maps.places.Autocomplete(input, options1);

                var input1 = document.getElementById('pickup_location');
                var options = {
                    types: ['(regions)'],
                };

                autocomplete = new google.maps.places.Autocomplete(input1, options);
            }
        });

        var input1 = document.getElementById('pickup_location');
        autocomplete = new google.maps.places.Autocomplete(input1);
        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();
            lat = place.geometry.location.lat(),
            lng = place.geometry.location.lng();
            $("#default_lat").val(lat);
            $("#default_lng").val(lng);
        });
    });

</script>
<script>
    $( "#pickup_date" ).datepicker({
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        autoclose: true,
    });

    $( "#return_date" ).datepicker({
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        autoclose: true,
    });


    $(document).ready(function()
    {
        classStr4 = ['col-sm-3 col-md-3 col-lg-3', 'col-sm-4 col-md-4 col-lg-4'];
        classStr8 = ['col-sm-7 col-md-7 col-lg-6', 'col-sm-8 col-md-8 col-lg-8'];
        
        $('#header_home').removeClass('on-banner-header').addClass('white-bg-header');

        $("body").on("click",'.filter-icon-btn', function()
        {

            if(!$(this).hasClass('reset-form')){
                if($('.onclick-show:visible').length > 0)
                {
                    $("#main_search").html('');
                    $(".onclick-show").hide();
                    $(".onclick-hide").show();
                    $('#class-4').removeAttr('class');
                    $('#class-4').addClass(classStr4[0]);
                    $('#class-8').removeAttr('class');
                    $('#class-8').addClass(classStr8[0]);
                }
                else
                {
                    $(".onclick-show").show();
                    $(".onclick-hide").hide();
                    $('#class-4').removeAttr('class');
                    $('#class-4').addClass(classStr4[1]);
                    $('#class-8').removeAttr('class');
                    $('#class-8').addClass(classStr8[1]);
                }
            }
            window.location.href = "{{ url('/') }}/listing";
        });
    });

    $('.reset-form').click(function(){
        $("#filter-form").trigger("reset");
    });
    

    $('#aircraft_type').change(function(){
        var selectedType = $(this).children("option:selected").val();
        $.ajax({
            url : '{{ url('/') }}/get_models_by_type/'+selectedType,
            data: { _token : "{{ csrf_token() }}", },
            success : function(resp) {
                if(resp.status == 'success'){
                    if(resp.html != undefined && resp.html != ''){
                        options = '<option value=""> -- Select Model -- </option>'+resp.html;
                        $("#aircraft_models").html(options);
                    }
                }
            }
        })
    });

    $('#return_date,#pickup_date').change(function(){
        var pickup_date    = $("#pickup_date").val(); 
        var return_date    = $("#return_date").val();
        
        if(new Date(pickup_date) > new Date(return_date))
        {
            swal('Error!','Return date should be greater than pickup date','error');
        }
    });

</script>
@endsection