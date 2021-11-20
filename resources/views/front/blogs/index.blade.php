@extends('front.layout.master')
@section('main_content')
<div class="page-head-section-main">
    <div class="container">
        <div class="term-content">
            Blogs 
        </div>
        <div class="condition-content">
            <a href="{{url('/')}}">Home ></a> <span class="inline-content-color">Blogs</span>
        </div> 
        <div class="clearfix"></div>
    </div>
</div>    
<!--Section start here-->
<section class="middle-section-main">
@if(isset($arr_blogs['data']) && sizeof($arr_blogs['data'])>0)    
    <div class="container">
        <div class="showing-txt-section">
            Showing {{ $arr_blogs['from'] }} – {{ $arr_blogs['to']}} of {{$arr_blogs['total']}} results
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-9">
                <div class="row">
                    @if(isset($arr_blogs['data']) && sizeof($arr_blogs['data'])>0)
                    @foreach($arr_blogs['data'] as $blog)
                    <a href="{{ url('/') }}/blog_details/{{ base64_encode($blog['id']) }}">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                        <div class="blog-flight-list">
                            <div class="flight-img">
                                @if(isset($blog['image']) && isset($blog['image']) && file_exists($blogs_base_img_path.'/'.$blog['image']))
                                <img src="{{$blogs_public_img_path}}/{{$blog['image'] or ''}}" alt="" class="img-responsive" />
                                @else
                                <img src="{{url('/')}}/front_assets/images/default-img.png" alt=" "  class="img-responsive" />
                                @endif
                            </div>
                            <div class="flight-content">
                               
                            <a href="{{ url('/') }}/blog_details/{{ base64_encode($blog['id']) }}">
                                    <h4>{{isset($blog['title'])?$blog['title'] :'' }}</h4>
                            </a>    
                                <div class="gray-line-block"></div>
                                {{ $blog['short_description'] }}
                            </div>
                        </div>
                    </div>
                    </a>
                    @endforeach
                    @endif
                </div>
                <div class="pagination">
                    {{ $page_link }} 
                </div>
            </div>
            
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
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
                <div class="result-not-found">Currently there are No Blogs</div>
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