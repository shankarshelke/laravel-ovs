@extends('front.layout.master')
@section('main_content')
<div class="header-index white-bg-header after-login-header" id="header-home"></div>
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
<section class="gray-bg-main-section">
    <div class="booking-menu-section-main">

    </div>
    <div class="container">
        @if(isset($arr_booking['data']) && !empty($arr_booking['data']))
        <div class="request-quote-filter-section">
            <div class="row m-l--5 m-r--5">
                <div class="col-sm-8 col-md-8 col-lg-10">
                    <div class="filter-search-main">                            
                        <div class="form-group">
                            <span><i class="fa fa-search"></i></span>
                            <input type="search" name="search" type="text" placeholder="Search bookings by Id or Aircraft Model" value="{{isset($_GET['search'])?$_GET['search']:''}}"  id="search"> 
                        </div>
                        @include('front.layout.operation_status')
                    </div>
                </div>
                <div class="col-sm-2 col-md-2 col-lg-2 p-l-5 p-r-5">                    
                    <div class="search-filter-button">
                        <button class="filter-search-btn" id="button"><i class="fa fa-search"></i> {{ trans('general.search') }}</button>
                        <!-- <a class="filter-icon-btn" href="javascript:void(0)"><img src="{{ url('/') }}/front_assets/images/filter-share-icon-img.png" alt="" /></a> -->
                        <a class="filter-icon-btn reset-form" href="javascript:void(0)"><i class="fa fa-repeat"></i></a>    
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="row">
            @if(isset($arr_booking['data']) && !empty($arr_booking['data']))
            @foreach( $arr_booking['data'] as $key => $value )
            <?php 
            $header_img_pop = $header_image =  url('/')."/front_assets/images/default-img.png";
            if(isset($value['get_image']['images']) && $value['get_image']['images']!=null && file_exists($aircraft_image_base_img_path.$value['get_image']['images']))
            {
                $header_image = get_resized_image($value['get_image']['images'] ,$aircraft_image_base_img_path,200,320);

                $header_img_pop = get_resized_image($value['get_image']['images'] ,$aircraft_image_base_img_path,200,320);
            }else{
                $header_img_pop = $header_image =  url('/')."/front_assets/images/default-img-200.png";
            }


            ?>
            <?php 
            $profile_img_pop = $profile_image =  url('/')."/uploads/default/no-img-user-profile-old.jpeg";
            if(isset($value['get_user_details']['profile_image']) && $value['get_user_details']['profile_image']!=null && file_exists($user_profile_base_img_path.$value['get_user_details']['profile_image']))
            {
                $profile_image = get_resized_image($value['get_user_details']['profile_image'] ,$user_profile_base_img_path,40,40);

                $profile_img_pop = get_resized_image($value['get_user_details']['profile_image'] ,$user_profile_base_img_path,40,40);
            }else{
                $profile_img_pop = $profile_image =  url('/')."/uploads/default/no-img-user-profile-old.jpeg";
            }

            ?>
            <div class="col-sm-4 col-md-4 col-lg-4">
                <div class="main-quote request-quote-main booking-pending booking-completed">
                <a href="{{ url('/') }}/details/{{ base64_encode($value['get_aircraft_details']['id']) }}">
                    <div class="img-quote">
                        @if(isset($value['get_image']['images']) && $value['get_image']['images']!=null && file_exists($aircraft_image_base_img_path.$value['get_image']['images']))
                        <img src="{{get_resized_image($value['get_image']['images'] ,$aircraft_image_base_img_path,200,320)}}">
                        @else
                        <img  src="{{url('/')}}/front_assets/images/default-img.png">
                        @endif
                        <div class="id-quote-receive">{{isset($value['reservation_id']) ? $value['reservation_id'] : '' }}</div>
                        <div class="pending">{{isset($value['status']) ? $value['status'] : ''}}</div>
                        <!-- <div class="text-quote">
                            <div class="profile-quote">
                                @if(isset($value['get_user_details']['profile_image']) && $value['get_user_details']['profile_image']!=null && file_exists($user_profile_base_img_path.$value['get_user_details']['profile_image']))
                                <img src="{{get_resized_image($value['get_user_details']['profile_image'] ,$user_profile_base_img_path,40,40)}}">
                                @else
                                <img src="{{url('/')}}/uploads/default/no-img-user-profile-old.jpeg">
                                @endif
                            </div>
                            <div class="profile-text-quote">{{$value['get_user_details']['first_name'] or ''}} {{$value['get_user_details']['last_name'] or ''}}</div>
                        </div> -->
                        <div class="date-quote"> {{get_formated_date($value['created_at']) }}</div>
                    </div>
                </a>
                    <div class="content-quote"><a href="{{ url('/') }}/details/{{ base64_encode($value['get_aircraft_details']['id']) }}">{{$value['get_aircraft_details']['get_aircraft_type']['model_name'] or ''}}</a></div>
                    <div class="content-date-quote">
                        <span class="june">
                            <span class="from">
                                <i class="far fa-calendar-alt"></i> from
                            </span> 
                            {{isset($value['pickup_date']) ? get_formated_date($value['pickup_date']) : ''}}
                        </span> 
                        <span class="june to-date-section">
                            <span class="from">
                                <i class="far fa-calendar-alt"></i> To
                            </span> 
                            {{isset($value['return_date']) ? get_formated_date($value['return_date']) : ''}}
                        </span> 
                    </div>
                    <div class="price-quote">
                        <span class="price"><i class="fa fa-dollar-sign"></i> Price </span> {{isset($value['final_amount']) ? get_formatted_price($value['final_amount'],$cny_price) :''}}
                    </div>
                    <div class="button-quote request-button main-button1">
                    <?php $reviews =    get_user_reviews($value['reservation_id'],$value['owner_id']) ;
                    ?>
                    @if($reviews)
                    <div class="redeem-star" style="padding-left: 25px;padding-right: 25px;height: 10px">
                                           <?php $rating = $reviews;
                                           for($i=0;$i<=4;$i++)
                                           {
                                            if($i<$rating)
                                            {
                                                ?>
                                                <i class="fa fa-star active"></i>
                                                <?php }else{?>
                                                <i class="fa fa-star "></i>
                                                <?php }}?>

                                                <p>({{$rating}}) ratings</p>
                                            </div>

                    @else

                        <div class="accept"><a href="javascript:void(0)" id="review_modal" class="review_modal openModel"
                            data-id="{{ $value['reservation_id'] or 'N/A'   }}"
                            data-aircraft-id="{{ $value['get_aircraft_details']['id'] or 'N/A'   }}"
                            data-name="{{ $value['get_user_details']['first_name'] or 'N/A'  }}"
                            data-last-name="{{ $value['get_user_details']['last_name'] or 'N/A'  }}"
                            data-header-image="{{ $header_img_pop  }}"
                            data-profile-image="{{ $profile_img_pop  }}"
                            data-created-at = "<?php echo get_formated_date($value['created_at']); ?>"
                            ><span>{{ trans('general.give') }}</span> {{ trans('general.rating') }}</a></div>
                    @endif

                            <div class="accept reject"><a href="{{ url('/').'/operator/view_contract/'.base64_encode($value["id"]) }}">{{ trans('general.view_contract') }}</a></div>
                        </div>
                    </div>
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
                <ul>
                    {{$page_link}}
                </ul>
            </div>
        </div>
    </section>
    <!--Section end here-->

    <!-- The Modal -->

    <div class="modal registration-modal give-feedback-form-main" id="myModal" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">                
                <!-- Modal body -->
                <div class="close-icon" data-dismiss="modal"><img src="{{ url('/') }}/front_assets/images/close-img.png" alt="" /></div>
                <div class="modal-body">
                    <form class="form-horizontal" id="frm_reviews" name="frm_reviews"  method="post" enctype="multipart/form-data">
                      <div class="close-icon" data-dismiss="modal"><img src="{{ url('/') }}/front_assets/images/close-img.png" alt="" /></div>
                       {{csrf_field()}}
                       <div class="give-feedback-form request-quote-main booking-pending booking-completed">
                         <input type="hidden" name="reservation_id" id="reservation_id">
                         <div class="img-quote">
                             <img src="" class="header-img">
                             <div class="id-quote-receive reservation_id"></div>
                             <div class="pending">{{isset($value['status']) ? $value['status'] : ''}}</div>
                             <div class="text-quote profile-text">
                                <div class="profile-quote">
                                   <img src="" class="profile-img">
                               </div>
                               <div class="profile-text-quote user-name aircraft-owner-name "></div>
                               <div class="profile-text-quote user-name aircraft-owner-lastname "></div>
                           </div>
                           <div class="date-quote date data-created-at"> </div>
                           <div class="clearfix"></div> 
                       </div>

                       <div class="content-quote"><h2>How was your Experience ?</h2></div>
                       <div class="ratings feedback">
                        <div class="stars"> 
                            <input type="radio"  checked="checked" name="rating" id="star-5" class="star star-5" value="5">
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

                    </div>
                    <div class="feedback-review">
                        <div class="form-group">
                            <label>Enter Your Review <span style="color: red">*</span></label>
                            <textarea  name="reviews" id="reviews" class="form-control" data-rule-required="true" rows="15" tabindex="1"></textarea>
                            <span class="error">{{ $errors->first('reviews') }} </span>
                        </div>                            
                        <h3>Feedback Questions</h3>
                        @foreach( $arr_feedback as $key => $row )
                        <div class="form-group">
                            <label>{{$row['questions']}}<span style="color: red">*</span></label>
                            <input class="experience" type="text" id="experience[{{ $row['id'] }}]" name="experience[{{ $row['id'] }}]"  class="form-control"  placeholder="{{$row['questions']}}" data-rule-required="true" data-rule-maxlength="255" tabindex="1"   >
                            <!-- <label id="experience-error" class="error" for="experience[<?php //echo $row['id'] ?>]"></label> -->
                            <span class="error" for="experience[{{ $row['id'] }}]"></span>
                        </div>
                        @endforeach 
                        <div class="button-quote request-button main-button1">
                            <div class="accept"><a href="javascript:void(0)" data-dismiss="modal">{{ trans('general.close') }}</a></div>
                            <div class="accept reject"><button>{{ trans('general.submit') }}</button></a></div>
                        </div>                                                       
                    </div>
                </div>
            </form>

        </div>
        <!-- Modal body end here -->
    </div>
</div>
</div>  



<script type="text/javascript" src="{{ url('/') }}/front_assets/js/bootstrap.min.js"></script>

<!--new profile image upload demo script start-->
<script type="text/javascript">
    $(document).ready(function() {

        $(".review_modal").click(function(){
            mod_id = $(this).data('target');
            $(mod_id).modal('toggle');
        });

        $(document).ready(function()
        {
            $('#frm_reviews').validate();
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('.img-preview').attr('src', e.target.result);

                };

                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#logo-id").change(function() {
            readURL(this);
        });

    });
</script>
<!--new profile image upload demo script end-->

<script type="text/javascript">
 
    $(".inner-pages-menu-icon").on("click", function() {
        $(this).parent(".inner-pages-menu-head").siblings(".inner-page-menu-ul").slideToggle("slow");
    })
        // }
        $('#search').on("keypress", function(e) {
            if (e.keyCode == 13) {
                var search = $(this).val();
                window.location.href = "{{ url('/') }}/operator/completed_bookings?search="+search
            }
        });

        $( "#button" ).click(function() {
          var search = $('#search').val();
          window.location.href = "{{ url('/') }}/operator/completed_bookings?search="+search
      });

        $('.openModel').on('click',function(){
            var id              = $(this).attr('data-id');
            var aircraft_id     = $(this).attr('data-aircraft-id');
            var name            = $(this).attr('data-name');
            var last_name       = $(this).attr('data-last-name');
            var created_at      = $(this).attr('data-created-at');
            var header_name     = $(this).attr('data-header-image');
            var profile_name    = $(this).attr('data-profile-image');
            var url             = "{{url('/')}}/operator/reviews/"+btoa(id)+'/'+btoa(aircraft_id);


            $('#frm_reviews').attr('action',url);
            $('.reservation_id').html(id);
            $('.data-created-at').html(created_at);
            $('.aircraft-owner-name').html(name);
            $('.aircraft-owner-lastname').html(last_name);
            $('.header-img').attr("src",header_name);
            $('.profile-img').attr("src",profile_name);
            $('#myModal').modal();

        });

        $("#myModal").on("hidden.bs.modal", function () {
            // put your default event here
            $('#reviews').val(''); /* form fields blank*/
            $('.error').html(''); /* form fields blank*/
            $("#star-5").prop("checked", true);
            $('.experience').val('');
        });
        $('.reset-form').click(function(){
            $("#search").val('');
            window.location.href = "{{ url('/') }}/operator/completed_bookings";
        });
    </script>
    @endsection