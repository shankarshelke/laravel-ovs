@extends('front.layout.master')
@section('main_content')
<div class="page-head-section-main">
    <div class="container">
        <div class="term-content">
            Blog Details
        </div>
        <div class="condition-content">
            <a href="{{url('/')}}">Home ></a> <a href="{{url('/')}}/blogs"><span>Blogs ></span></a> <a><span class="inline-content-color">Blog-details </span></a>
        </div> 
        <div class="clearfix"></div>
    </div>
</div>    
<!--Section start here-->
<section class="middle-section-main aircraft-blog">
    @if(isset($arr_blogs) && sizeof($arr_blogs)>0)
    <div class="container">
        <div class="row">
           <div class="col-sm-12 col-md-12 col-lg-9">
            @if(isset($arr_blogs) && sizeof($arr_blogs)>0)
            <div class="blog-left-section detail-blog-section">
              
                <div class="blog-details-img">
                    @if(isset($arr_blogs['image']) && isset($arr_blogs['image']) && file_exists($blogs_base_img_path.'/'.$arr_blogs['image']))
                    <img src="{{$blogs_public_img_path}}/{{$arr_blogs['image'] or ''}}" alt="" class="img-responsive" />
                    @else
                    <img src="{{url('/')}}/front_assets/images/default-img.png" alt=" "  class="img-responsive" />
                    @endif
                </div>
                <div class="detail-description-bx">

                    @if(isset($arr_blogs['type']) && $arr_blogs['type'] == 'fixed_wings' )
                    <div class="deatil-category" style="margin-top: 15px;">FIXED WINGS</div>
                    @elseif(isset($arr_blogs['type']) && $arr_blogs['type'] == 'rotating_wings' )
                    <div class="deatil-category" style="margin-top: 15px;">ROTATING WINGS</div>
                    @endif
                    <div class="blog-title"><a href="">{{ $arr_blogs['title'] or 'N/A' }}</a></div>
                    <div class="title-line"></div>
                    

                    <div class="description-point-bx">

                        <div class="des-point-txt"><?php echo html_entity_decode($arr_blogs['description']) ?></div>
                    </div>
                </div>
               <!--  <div class="comments">
                    <h2>Comments</h2>
                    <div class="comment-box">
                        <div class="comment-profile-img">
                            <img src="images/review-1.png" alt="profile image">
                        </div>
                        <div class="comment-text">
                            <h3>Riva Collins</h3>
                            <div class="time-review">December 25, 2018 - 3:33 pm</div>
                            <p class="profile-comment">Phasellus quis lectus metus, at posuere neque. Sed pharetra nibh eget orci convallis at posuere leo convallis. </p>
                        </div>
                    </div>
                    <div class="comment-box">
                        <div class="comment-profile-img">
                            <img src="images/review-2.png" alt="profile image">
                        </div>
                        <div class="comment-text">
                            <h3>Finn Balor</h3>

                            <div class="time-review">December 25, 2018 - 3:33 pm</div>
                            <p class="profile-comment">Phasellus quis lectus metus, at posuere neque. Sed pharetra nibh eget orci convallis at posuere leo convallis. </p>
                        </div>
                    </div>
                    <div class="comment-box">
                        <div class="comment-profile-img">
                            <img src="images/review-3.png" alt="profile image">
                        </div>
                        <div class="comment-text">
                            <h3>Peter Mckinnon</h3>
                            <div class="time-review">December 25, 2018 - 3:33 pm</div>
                            <p class="profile-comment">Phasellus quis lectus metus, at posuere neque. Sed pharetra nibh eget orci convallis at posuere leo convallis. </p>
                        </div>
                    </div>
                </div>
                <div class="comments write-comment-section">
                    <h2>Write a Comment</h2>
                    <div class="write-comment-bx-blog">
                        <div class="row">
                            <div class="col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <input type="text" placeholder="Name" />
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <input type="email" placeholder="Email" />
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group coment-height">
                                    <textarea placeholder="Comments" rows="3"></textarea>
                                </div>
                            </div>
                            
                            
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="coment-send-btn">
                                   <button class="full-orng-btn sim-button cont-sub">Submit</button>
                                   <div class="clearfix"></div>
                               </div>
                               
                           </div>
                       </div>
                   </div>
               </div> -->
           </div>
           @endif
       </div>
       <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
     <!--    <div class="categories-list">
            <h3>categories</h3>
            <ul>
                <li><i class="fa fa-circle-o" aria-hidden="true"></i>
                    <span class="category-circle"><a href="#">cargo(40)</a></span>
                </li>
                <li><i class="fa fa-circle-o" aria-hidden="true"></i>
                    <span class="category-circle"><a href="#">passenger(15)</a></span>
                </li>
                <li><i class="fa fa-circle-o" aria-hidden="true"></i>
                    <span class="category-circle"><a href="#">medical evaculation(30)</a></span>
                </li>
                <li><i class="fa fa-circle-o" aria-hidden="true"></i>
                    <span class="category-circle"><a href="#">all(60)</a></span>
                </li>
                <li><i class="fa fa-circle-o" aria-hidden="true"></i>
                    <span class="category-circle"><a href="#">fire fighting(25)</a></span>
                </li>
            </ul>
        </div> -->
        
        <div class="categories-list">
            <h3>recent posts</h3>
            <div class="recent-post-main">
                @if(isset($arr_recent_blogs) && sizeof($arr_recent_blogs)>0)
                @foreach($arr_recent_blogs as $recent_blog)
                <a href="{{ url('/') }}/blog_details/{{ base64_encode($recent_blog['id']) }}">
                    <div class="recent-post-content">                                    
                        <div class="recent-post-img-section">
                            @if(isset($recent_blog['image']) && isset($recent_blog['image']) && file_exists($blogs_base_img_path.'/'.$recent_blog['image']))
                            <img src="{{$blogs_public_img_path}}/{{$recent_blog['image'] or ''}}" alt="" class="img-responsive" />
                            @else
                            <img src="{{url('/')}}/front_assets/images/default-img.png" alt=" "  class="img-responsive" />
                            @endif
                        </div>
                        <div class="recent-post-text">
                            <h4>{{isset($recent_blog['title'])?$recent_blog['title'] :'' }}</h4>
                            <h5>{{isset($recent_blog['created_at'])?get_month_formated_created_date($recent_blog['created_at']) :'N/A' }}</h5>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </a>       
                @endforeach
            </div>
            @endif

        </div>
    </div>
</div>
</div>
@else
<div class="container">
    <div class="background-container">
        <div class="img-content">
            <img src="{{ url('/') }}/front_assets/images/rocket.png" alt="">
        </div> 
        <div class="content-background">
            <div class="result-not-found">Oops</div>
            <div class="please-try-again">Please try again</div>
        </div>
    </div>
</div>
@endif
</section>
<script type="text/javascript">
    $(function(){
        $('#header_home').removeClass('on-banner-header').addClass('white-bg-header');
    });
</script>
@endsection