@extends('front.layout.master')
@section('main_content')
<section class="gray-bg-main-section">

    @if(isset($arr_aircraft['data']) && !empty($arr_aircraft['data']))

    <div class="container">
        @include('front.layout.operation_status')
       <div class="request-quote-filter-section">
                <div class="row m-l--5 m-r--5">
                    <div class="col-sm-8 col-md-8 col-lg-10">
                        <div class="filter-search-main">                            
                            <div class="form-group">
                                <span><i class="fa fa-search"></i></span>
                                <input type="search" name="search" type="text" placeholder="Search Aircraft by Model" value="{{isset($_GET['search'])?$_GET['search']:''}}" id="search">
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2 col-md-2 col-lg-2 p-l-5 p-r-5">                    
                        <div class="search-filter-button">
                             <button id="button" class="filter-search-btn search-btn" id='button' ><i class="fa fa-search"></i> {{ trans('general.search') }}</button>
                              <a class="filter-icon-btn reset-form" href="javascript:void(0)"><i class="fa fa-repeat"></i></a>
                            <!-- <a class="filter-icon-btn" href="javascript:void(0)"><img src="{{ url('/') }}/front_assets/images/filter-icon-img.png" alt="" /></a> -->
                        </div>
                        
                    </div>
                </div>
            </div>
        <div class="showing-txt-section">
            Showing {{ $arr_aircraft['from'] }} – {{ $arr_aircraft['to']}} of {{$arr_aircraft['total']}} results
        </div>
        <div class="row">
            @foreach($arr_aircraft['data'] as $aircraft)
            <div class="col-sm-4 col-md-4 col-lg-4">
                <div class="main-quote">
                    <div class="img-quote">
                        <div class="listing-persent">
                            <a href="{{ url('/').'/operator/aircrafts/edit/'.base64_encode($aircraft['id']) }}" title="Edit Aircraft" class="edit-icon"><i class="fa fa-pencil"></i></a>
                            <a href="{{ url('/').'/operator/aircrafts/availability/'.base64_encode($aircraft['id']) }}" title="Availability" class="delete-icon"><i class="fa fa-calendar"></i>
                            </a>
                        </div>
                        @if(isset($aircraft['get_image']) && isset($aircraft['get_image']['images']) && file_exists($aircraft_images_base_img_path.'thumb_320x205/'.$aircraft['get_image']['images']))
                            <img src="{{$aircraft_images_public_img_path}}thumb_320x205/{{$aircraft['get_image']['images'] or ''}}" alt="" class="img-responsive" />
                        @else
                            <img src="{{url('/')}}/front_assets/images/default-img-200.png" alt=" "  class="img-responsive" />
                        @endif
                        <div class="id-quote-page1">{{isset($aircraft['type_name'])? ucwords(str_replace('_',' ',$aircraft['type_name'])): '' }}</div>
                    </div>
                    <div class="content-quote">
                    <a href="{{ url('/') }}/details/{{ base64_encode($aircraft['id']) }}">
                        <h2>{{ $aircraft['get_aircraft_type']['model_name'] or '' }}</h2>
                    </a>    
                    </div>
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
                    <div class="price-quote2">pricing:<span class="price-content">{{get_currency()}} {{isset($aircraft['price_per_hour'])? $aircraft['price_per_hour']:''}}</span></div>
                </div>
            </div>
            @endforeach

        </div>

        <div class="clearfix"></div>

        <div class="pagination">
            <ul>{{ $page_link }}</ul>
        </div>

    </div>

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

</section>

<script>

    $(document).ready(function(){
       $("body").on("click",'.filter-icon-btn', function(){                
            $(".onclick-show").show();
            $(".onclick-hide").hide();
        }); 
    });        
     
    $('#search').on("keypress", function(e) {
        if (e.keyCode == 13) {
            var search = $(this).val();
            window.location.href = "{{ url('/') }}/operator/aircrafts?search="+search
        }
    });

    $( "#button" ).click(function() {
      var search = $('#search').val();
      window.location.href = "{{ url('/') }}/operator/aircrafts?search="+search
    });
    $('.reset-form').click(function(){
            $("#search").val('');
            window.location.href = "{{ url('/') }}/operator/aircrafts";
        });

</script>
@endsection