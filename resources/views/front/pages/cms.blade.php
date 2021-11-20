@extends('front.layout.master')
@section('main_content')
<div class="page-head-section-main">
    <div class="container">
        <div class="term-content">
          {{ $module_title }}
        </div>
        <div class="condition-content">
            <a href="{{url('/')}}">Home ></a> <span class="inline-content-color">{{ $module_title }}</span>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<!--Header section end here-->


    <div class="terms-conditions-main">
        <div class="container">
            <div class="my-account-main-white">
               
            @if(isset($arr_data) && $arr_data !='' )
                <div class="terms-bx">
                    <div class="terms-sub no-margi"> <?php echo $arr_data['page_description']; ?> </div>
                </div>
            @else
            <div class="terms-bx">
                <div class="terms-sub no-margi">
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
            </div>
            @endif
               
            </div>
        </div>
    </div>


<script type="text/javascript">
    $(document).ready(function() {
        $('#header_home').removeClass('on-banner-header').addClass('white-bg-header');
    });
</script>
@endsection