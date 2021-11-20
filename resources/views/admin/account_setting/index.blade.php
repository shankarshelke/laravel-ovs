@extends('admin.layout.master')    
@section('main_content')
<style>
    .fileupload .thumbnail{line-height: 0 !important;}
</style>
<!--body wrapper start-->
<div class="wrapper">
    <div class="row">
        <div class="col-md-12">
            @include('admin.layout.breadcrumb')  
            <div class="panel">                
                <div class="panel-body">
                    @include('admin.layout._operation_status') 
                    <form action="{{url('/')}}/admin/account_setting/update" id="frm_admin" name="frm_admin" class="cmxform" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-6">
                                <div class="form-group last">
                                    <label class="control-label">{{ trans('accountsetting.image upload') }}</label>
                                    <div class="input-group">
                                        <div class="fileupload fileupload-new" data-provides="fileupload">
                                        @php $is_profile_image_required = $prev_image_url = ""; @endphp
                                            <div class="fileupload-new thumbnail " style="width: 150px; height: 150px;">
                                            @if(isset($arr_admin_details['profile_image']) && !empty($arr_admin_details['profile_image']) && File::exists($profile_image_base_img_path.$arr_admin_details['profile_image']))
                                                    <img src="{{$profile_image_public_img_path.$arr_admin_details['profile_image']}}">
                                                 @php 
                                                    $prev_image_url = $profile_image_public_img_path.$arr_admin_details['profile_image']; 
                                                    $is_profile_image_required = false; 
                                                @endphp
                                                @else
                                                    <img src="http://www.placehold.it/500x500/EFEFEF/AAAAAA&text=no+image">
                                                    @php 
                                                    $is_profile_image_required = true;
                                                    $prev_image_url = url('/').'/uploads/admin/default_image/default-profile.png';
                                                @endphp
                                            @endif
                                            </div>
                                            <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 150px; max-height: 150px; line-height: 0px !important;">
                                            </div>
                                            <div>
                                                   <span class="btn btn-default btn-file">
                                                   <span class="fileupload-new"><i class="fa fa-paper-clip"></i>{{ trans('accountsetting.select image') }} </span>
                                                   <span class="fileupload-exists"><i class="fa fa-undo"></i>{{ trans('accountsetting.change') }} </span>
                                                   <input type="file" data-validation-allowing="jpg, png, gif" class="default file-input news-image validate-image" name="profile_image" id="image"  class="" accept="image/*">
                                                   <input type="hidden" name="oldimage" id="oldimage" value="{{ $arr_admin_details['profile_image']  or ''}}"/>
                                                   <input type="hidden" name="prev_image_url" id="prev_image_url"  value="{{$prev_image_url or ''}}"/>
                                                   </span>
                                                <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> {{ trans('accountsetting.remove') }}</a>
                                            </div>
                                        </div>
                                        <br>
                                        <span class="label label-danger ">{{ trans('accountsetting.note!!') }}</span>
                                         <span>{{ trans('accountsetting.Only jpg, png, jpeg file are allowed.!! Image size should be less than 2 mb.!!') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-sm-12 col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('accountsetting.first name') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-user"></i></span>
                                        <input type="text" id="first_name" name="first_name"   data-rule-lettersonly="true" data-rule-required="true" class="form-control round-" value="{{$arr_admin_details['first_name'] or 'NA'}}" maxlength="40">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('accountsetting.last name') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-user"></i></span>
                                        <input type="text" id="last_name"  name="last_name"  data-rule-required="true"  data-rule-lettersonly=”true” class="form-control " value="{{$arr_admin_details['last_name'] or 'NA'}}" maxlength="40">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('accountsetting.email') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-email"></i></span>
                                        <input type="text" id="email" name="email" data-rule-email="true"  data-rule-required="true" class="form-control " value="{{$arr_admin_details['email'] or 'NA'}}" maxlength="40">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('accountsetting.contact') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="icon-phone"></i></span>
                                        <input type="text" id="contact" name="contact" data-rule-required="true" data-rule-number="true" class="form-control " value="{{$arr_admin_details['contact'] or 'NA'}}" maxlength="10" minlength="10" >
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('accountsetting.address') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-location-pin"></i></span>
                                        <input type="text" id="address" name="address"  data-rule-required="true" class="form-control " value="{{$arr_admin_details['address'] or 'NA'}}" maxlength="60">
                                    </div>
                                </div>
                            </div>
                        </div>                                                                                                
                        <div class="form-group">                            
                            <button class="btn btn-primary" type="submit" >{{ trans('accountsetting.update') }}</button>                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--body wrapper end-->
<script src="https://maps.googleapis.com/maps/api/js?v=3&key={{ config('app.project.google_map_api_key') }}&libraries=places"></script>

<script src="{{url('/')}}/assets/admin_assets/js/bootstrap-inputmask.min.js"></script>

<script type="text/javascript">

    $(document).ready(function(){
        $('#frm_admin').validate();
    });

    $.validator.addMethod('customphone', function (value, element) {
    return this.optional(element) || /(5|6|7|8|9)\d{9}/.test(value);
    }, "Please enter a valid phone number");

    $.validator.addClassRules('customphone', {
    customphone: true
    });

    $(function () 
    {
        $("#address").geocomplete({
            types: ['(cities)'],
            details: ".geo-details",
            detailsAttribute: "data-geo"
        }).bind("geocode:result", function (event, result){ /* Retrun Lat Long*/                      
            $("#latitude").val(result.geometry.location.lat());
            $("#longitude").val(result.geometry.location.lng());
            var searchAddressComponents = result.address_components,
            searchPostalCode="";
        });
    });

    $(document).ready(function() 
    {
        var e = document.getElementById("image"),
            r = $("#default-image").val();
        $(e).change(function() 
        {
            if (e.files && e.files[0]) 
            { 
                var a = e.files,
                    t = a[0].name.substring(a[0].name.lastIndexOf(".") + 1),
                    n = new FileReader;
                if ("JPEG" != t && "jpeg" != t && "jpg" != t && "JPG" != t && "png" != t && "PNG" != t) 
                    return showAlert("Sorry, " + a[0].name + " is invalid, allowed extensions are: jpeg , jpg , png", "error"),$("#image").val(""), $(".fileupload-preview").attr("src", r), !1;
                if (a[0].size > 2e6) return showAlert("Sorry, " + a[0].name + " is invalid, Image size should be upto 2 MB only", "error"), $("#image").val(""), $(".fileupload-preview").attr("src", r), !1;
                n.onload = function(e) 
                {
                    var a = new Image;
                    a.src = e.target.result, a.onload = function() 
                    { 
                        var e = this.height,
                            a = this.width;
                        if (e < 500 || a < 500) 
                            return showAlert("Sorry,Please upload image with Height and Width greater than or equal to 1500 X 1500 for best result", "error"), $("#image").val(""), $(".fileupload-preview").attr("src", r), !1
                    }, $(".fileupload-preview").attr("src", e.target.result)
                }, n.readAsDataURL(e.files[0])
            }
        })
    });
</script>

@endsection


			