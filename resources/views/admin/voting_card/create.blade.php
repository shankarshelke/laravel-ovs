@extends('admin.layout.master')
<style type="text/css">
    #map {
  height: 300px;
  /*width: 100%;*/
}
/* Optional: Makes the sample page fill the window. */
html, body {
  height: 100%;
  width: 100%;
  margin: 0;
  padding: 0;
}
.highlight-error {
  border-color: red;
}
    body{overflow: unset !important}
    section .panel-heading{padding: 0 0 10px;}
    section.panel{-webkit-box-shadow: none;box-shadow: none;}
</style>
    
@section('main_content')
<!--body wrapper start-->
<div class="wrapper">
    <div class="row">
        <div class="col-md-12">
            @include('admin.layout.breadcrumb')
            <section class="panel">
                <div class="panel-body " >
                    @include('admin.layout._operation_status') 
                    <form action="{{$module_url_path}}/store" id="frm_create_page" name="frm_create_page" class="cmxform" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <section class="panel">
                            <header class="panel-heading">
                                Personal Details
                            </header>
                            <br>                       
                            <div class="col-sm-12 col-md-12 col-lg-3">
                                <div class="form-group">
                                    <label class="control-label">First Name<i class="red">*</i></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-user"></i></span>
                                        <input type="text" id="first_name"  name="first_name" value="{{old('first_name')}}" data-rule-required="true" data-rule-lettersonly=”true” class="form-control ">
                                        <span class="error" style="color: red;">{{ $errors->first('first_name') }} </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-3">
                                <div class="form-group">
                                    <label class="control-label">Last Name<i class="red">*</i></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-user"></i></span>
                                        <input type="text" id="last_name"  name="last_name" value="{{old('last_name')}}"  data-rule-required="true"  data-rule-lettersonly=”true” class="form-control ">
                                        <span class="error" style="color: red;">{{ $errors->first('last_name') }} </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-3">
                                <div class="form-group">
                                    <label class="control-label">Father/Husband Name<i class="red">*</i></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-user"></i></span>
                                        <input type="text" id="father_full_name"  name="father_full_name"  value="{{old('father_full_name')}}"data-rule-required="true"  class="form-control " data-rule-lettersonly=”true”>
                                        <span class="error" style="color: red;">{{ $errors->first('father_full_name') }} </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-3">
                                <div class="form-group">
                                    <label class="control-label">Email<i class="red">*</i></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-email"></i></span>
                                        <input type="text" id="email"  name="email"  value="{{old('email')}}" data-rule-required="true" data-rule-email="true"  class="form-control ">
                                        <span class="error" style="color: red;">{{ $errors->first('email') }} </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-3">
                                <div class="form-group">
                                    <label class="control-label">Mobile Number<i class="red">*</i></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="icon-phone"></i></span>
                                        <input class="form-control required valid"  value="{{old('contact')}}" data-rule-number="true" data-type="contact-number" name="mobile_number" data-rule-number="true" type="text" placeholder="Mobile Number"   id="contact" data-rule-required="true" maxlength="10">
                                        <span class="error" style="color: red;">{{ $errors->first('mobile_number') }} </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-3">
                                <div class="form-group">
                                    <label class="control-label">Date of Birth<i class="red">*</i></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-user"></i></span>
                                        <input type="text" id="datepicker" readonly="" value="{{old('date_of_birth')}}" name="date_of_birth" data-rule-required="true" class="form-control" placeholder="Date of Birth">
                                    </div>
                                </div>
                            </div>
                                <div class="col-sm-12 col-md-12 col-lg-3">
                            <div class="form-group">
                                <label class="control-label">Enter Address<i style="color:red;">*</i></label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="ti-user"></i></span>
                                    <input type="text" id="address"  name="address" value="{{old('address')}}" data-rule-required="true"  class="form-control ">
                                    <span class="error" style="color: red;">{{ $errors->first('address') }} </span>
                                </div>
                            </div>
                        </div>
                            <div class="col-sm-12 col-md-12 col-lg-3">
                                <div class="form-group">
                                    <label class="control-label">Gender<i class="red">*</i></label>
                                    <div class="input-group">                                                                                
                                        <div class="radio-btn" style="float: left;margin-right: 30px">
                                            <input type="radio" id="s-option1" name="gender" class = "gender" value="male"@if(old('gender')) checked @endif >
                                            <label for="s-option1"><span class="user-login-icon"></span>Male</label>                                    
                                        </div>                                        
                                        <div class="radio-btn" style="float: left;margin-right: 30px">
                                            <input type="radio" id="s-option2" name="gender" class = "gender" value="female" @if(old('gender')) checked @endif />
                                            <label for="s-option2"><span class="user-login-icon"></span>Female</label>                                    
                                        </div>                                        
                                        <div class="radio-btn" style="float: left;margin-right: 30px">
                                            <input type="radio" id="s-option3" name="gender" class = "gender" value="other" @if(old('gender')) checked @endif >
                                            <label for="s-option3"><span class="user-login-icon"></span>Other</label>                                    
                                        </div>                                     
                                        <div class="clearfix"></div>   
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-sm-12 col-md-12 col-lg-3">
                                <div class="form-group">
                                    <label class="control-label">Religion<i class="red">*</i></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-user"></i></span>
                                        <select name="religion" data-rule-required="true" id="religion" class="form-control">
                                            <option value="">Select Religion </option>
                                            @if(isset($arr_religion) && count($arr_religion)>0)
                                            @foreach($arr_religion as $religions)
                                                <option value="{{$religions['id']}}"@if(old('religion') == $religions['id']) selected="selected" @endif >{{$religions['religion_name'] or ''}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span class="error" style="color: red;">{{ $errors->first('religion') }} </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-3">
                                <div class="form-group">
                                    <label class="control-label">Caste Category<i class="red">*</i></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-user"></i></span>
                                        <select name="caste" data-rule-required="true" id="caste" class="form-control">
                                            <option value="">Select Caste Category </option>
                                            @if(isset($arr_caste) && count($arr_caste)>0)
                                            @foreach($arr_caste as $castes)
                                                <option value="{{$castes['id']}}" @if(old('caste') == $castes['id']) selected="selected" @endif>{{$castes['caste_name'] or ''}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span class="error" style="color: red;">{{ $errors->first('caste') }} </span>
                                    </div>
                                </div>
                            </div>                    
                        </section>
                        <div class="clearfix"></div>
                    
                    <section class="panel">
                        <header class="panel-heading">
                            Address Details
                        </header>
                    <br>

                    <div class="form-group">
                        <label class="control-label">Pick Voter Location<i style="color:red;">*</i></label>
                        <div class="input-group">
                            <div id="map"></div>
                            <input type="hidden" name="latitude" id="latitude" data-rule-required="true" value=""/>
                            <input type="hidden" name="longitude"id="longitude" data-rule-required="true" value=""/>
                        </div>
                    </div>
                    <div class="row">
                    
                        {{-- <div class="col-sm-12 col-md-12 col-lg-6">
                            <div class="form-group">
                                <label class="control-label">Address<i style="color:red;">*</i></label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="ti-user"></i></span>
                                    <input  name="address" id="address" class="form-control" readonly="address" placeholder="Address" data-rule-required="true" value="{{old('address')}}" >
                                    <span class="error" style="color: red;">{{ $errors->first('address') }} </span>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                        
                        </section>

                        <section class="panel">
                        <header class="panel-heading">
                            Other Details
                        </header>
                        <br>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-3">
                                <div class="form-group">
                                    <label class="control-label">Ward<i style="color:red;">*</i></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-menu"></i></span>
                                        <select name="ward" data-rule-required="true" id="ward" class="form-control">
                                            <option value="">Select Ward </option>
                                            @if(isset($arr_wards) && count($arr_wards)>0)
                                            @foreach($arr_wards as $wards)
                                                <option value="{{$wards['id']}}" @if(old('ward') == $wards['id']) selected="selected" @endif>{{$wards['ward_name'] or ''}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span class="error" style="color: red;">{{ $errors->first('ward') }} </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-3">
                                <div class="form-group">
                                    <label class="control-label">Booth<i style="color:red;">*</i></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-menu"></i></span>
                                        <select name="booth" data-rule-required="true" id="booth" class="form-control">
                                                <option value="">Select Booth </option>
                                            </select>
                                        <span class="error" style="color: red;">{{ $errors->first('booth') }} </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-3">
                                <div class="form-group">
                                    <label class="control-label">List<i style="color:red;">*</i></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-menu"></i></span>
                                        <select name="list" data-rule-required="true" id="list" class="form-control">
                                                <option value="">Select List </option>
                                            </select>
                                        <span class="error" style="color: red;">{{ $errors->first('list') }} </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-3">
                                <div class="form-group">
                                    <label class="control-label">Occupation<i style="color:red;">*</i></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-menu"></i></span>
                                        <select name="occupation" data-rule-required="true" id="occupation" class="form-control">
                                            <option value="">Select occupation </option>
                                            @if(isset($arr_occupation) && count($arr_occupation)>0)
                                            @foreach($arr_occupation as $occupations)
                                                <option value="{{$occupations['id']}}" @if(old('occupation') == $occupations['id']) selected="selected" @endif>{{$occupations['occupation_name'] or ''}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span class="error" style="color: red;">{{ $errors->first('occupation') }} </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-3">
                                <div class="form-group">
                                    <label class="control-label">Surety<i style="color:red;">*</i></label>
                                    <div class="input-group">
                                        <div class="radio-btns">                                            
                                            <div class="radio-btn" style="float: left;margin-right: 30px">
                                                <input type="radio" id="s-option7" name="voting_surety" value="0"@if(old('voting_surety')) checked @endif />
                                                <label for="s-option7"><span class="user-login-icon"></span>Full Surety
                                              </label>
                                            </div>                                            
                                            <div class="radio-btn" style="float: left;margin-right: 30px">
                                                <input type="radio" id="s-option8" name="voting_surety" value="1" @if(old('voting_surety')) checked @endif/>
                                                <label for="s-option8"><span class="user-login-icon"></span>Half Surety</label>
                                            </div>                                            
                                            <div class="radio-btn" style="float: left;margin-right: 30px">
                                                <input type="radio" id="s-option9" name="voting_surety" value="2" @if(old('voting_surety')) checked @endif/>
                                                <label for="s-option9"><span class="user-login-icon"></span>No Surety</label>
                                            </div>                                            
                                            <span class="error" style="color: red;">{{ $errors->first('voting_surety') }} </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                  
                    </section>                  
                        <div class="form-group">                            
                            <a href="{{ $module_url_path or 'NA' }}" class="btn btn-primary">Back</a>
                            <button class="btn btn-primary" type="submit"  id="btn_add_front_page">Create</button>
                        </div>                      
                    </form>
                </div>
            </section>
        </div>
    </div>
</div>
<!--body wrapper end-->

<script src="{{url('/')}}/assets/admin_assets/js/bootstrap-inputmask.min.js"></script>
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script type="text/javascript" src="{{url('/')}}/assets/admin_assets/js/bootstrap-datepicker.js"></script>
{{-- <link href="{{url('/')}}/assets/admin_assets/css/datetimepicker-custom.css" rel="stylesheet">
<script src="{{url('/')}}/assets/admin_assets/js/bootstrap-datetimepicker.js"></script> --}}
{{-- <script src="{{url('/')}}/assets/admin_assets/js/pickers-init.js"></script>
 --}}
<script async defer
src="https://maps.googleapis.com/maps/api/js?key={{ config('app.project.google_map_api_key') }}&callback=initMap">
</script>
<script>
    $(function() {
        $( "#datepicker" ).datepicker({
            dateFormat : 'dd/mm/yy',
            changeMonth : true,
            changeYear : true,
            yearRange: '-100y:c+nn',
            maxDate: '365d',
            closeOnDateSelect : true,
            scrollMonth : false,
            scrollInput : false,
            
        });
    });



    $.validator.addMethod('customphone', function (value, element) {
    return this.optional(element) || /(5|6|7|8|9)\d{9}/.test(value);
    }, "Please enter a valid phone number");

    $.validator.addClassRules('customphone', {
    customphone: true
    });


    $(document).ready(function(){
        /*$( "#datepicker" ).datepicker();*/
        jQuery.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /^[a-z]+$/i.test(value);}, "Letters only please");

    $('#frm_create_page').validate({
                                      rules: {
                                        gender: {
                                          required: true
                                        },
                                         voting_surety: {
                                          required: true
                                        },
                                         face_color: {
                                          required: true

                                      }
                                    }
                        })

    });

    
</script>
<Script>
var map, infoWindow;
var nashik   = { lat: 19.9975 ,lng: 73.7898 };
function initMap() {
  map = new google.maps.Map(document.getElementById('map'), {
    center: nashik,
    zoom: 15
  });
  var marker    = new google.maps.Marker({position: nashik, map: map,draggable:true});
    google.maps.event.addListener(marker, 'dragend',
                function(marker) {
                    var latLng          = marker.latLng;
                    currentLatitude     = latLng.lat();
                    currentLongitude    = latLng.lng();
                     map.setCenter(new google.maps.LatLng(currentLatitude, currentLongitude)); 
                    $("#latitude").val(currentLatitude);
                    $("#longitude").val(currentLongitude);
                       var geocoder= new google.maps.Geocoder();

                    var latlng = {lat: parseFloat(currentLatitude), lng: parseFloat(currentLongitude)};
                    geocoder.geocode({'location': latlng}, function(results, status) {
                    $("#state, #city").val();
                    var length = results[0].address_components.length;
                    console.log(results[0].address_components);
                    console.log(results[0].formatted_address);
                    $("#address").val(results[0].formatted_address); 
                          for (var i = 0; i < length; i++)
                            {
                                if(results[0].address_components[i].types[0] == 'administrative_area_level_1'){
                                    $("#state").val(results[0].address_components[i].long_name);        //for state name
                                }

                                if(results[0].address_components[i].types[0] == 'administrative_area_level_2'){
                                    $("#district1").val(results[0].address_components[i].long_name);         //for city name
                                }
                                // if(results[0].address_components[i].types[0] == 'locality'){
                                //     $("#village").val(results[0].address_components[i].long_name);//for city name
                                // }

                                if(results[0].address_components[i].types[0] == 'postal_code'){
                                    $("#postal_code").val(results[0].address_components[i].long_name);         //for city name
                                }

                                if(results[0].address_components[i].types[0] == 'route'){
                                    $("#street").val(results[0].address_components[i].long_name);         //for city name
                                }
                                if(results[0].address_components[i].types[0] == 'premise'){
                                    $("#house_no").val(results[0].address_components[i].long_name);         //for city name
                                }

                                if($("#street").val()=='')
                                    {$("#street").val('Unnamed Road');}
                                  /*if(result.address_components[i].types[0] == 'administrative_area_level_1'){
                                        $("#state").val(result.address_components[i].long_name);                //for state name
                                    }
                                    if(result.address_components[i].types[0] == 'locality'){
                                        $("#city").val(result.address_components[i].long_name);                 //for city name
                                    }
                                    if(result.address_components[i].types[0] == 'postal_code'){
                                        $("#postal_code").val(result.address_components[i].long_name);          //for city name
                                    }*/

                            }
                      if (status === 'OK') {

                        if (results[0]) {
                          map.setZoom(11);
                          map.setCenter(new google.maps.LatLng(currentLatitude, currentLongitude));  //set current location as center
                        /*  var marker = new google.maps.Marker({
                            position: latlng,
                            map: map,
                            draggable:true
                          });*/
                          $("#address").val(results[0].formatted_address);
                        } else {
                          window.alert('No results found');
                        }
                      } else {
                        window.alert('Geocoder failed due to: ' + status);
                      }
                    });
                });
  infoWindow = new google.maps.InfoWindow;
    //return map(latitude,longitude);
}
</Script>



<!-- Script for Image validation -->
<script type="text/javascript">
    $(document).ready(function() 
    {
    
        $('#district').change(function(){

        var district_id = $('#district').val();
        
        if(district_id!='')
        {
            var url = '{{$module_url_path}}/get_cities';
            var csrf_token      = '{{csrf_token()}}';

            $.ajax({
                type:'POST',
                url: url,
                data:{district_id:district_id,_token:'{{csrf_token()}}'},

                success:function(resp){
                    $('#city').html(resp);
                }
            });
        }
        });
        $('#city').change(function(){

            
            var district_id = $('#district').val();
            var city_id = $('#city').val();
            if(district_id!='' && city_id!='')
            {
                var url = '{{$module_url_path}}/get_villages';
                var csrf_token      = '{{csrf_token()}}';

                $.ajax({
                    type:'POST',
                    url: url,
                    data:{district_id:district_id,city_id:city_id,_token:'{{csrf_token()}}'},
                    
                    success:function(resp){
                        $('#village').html(resp);
                    }
                });
            }
        });


        $('#village').change(function(){

            
            var village_id = $('#village').val();
            var city_id = $('#city').val();
            var district_id = $('#district').val();
            if(district_id!='' && city_id!='' && village_id!='')
            { 

                var url = '{{$module_url_path}}/get_wards';
                var csrf_token      = '{{csrf_token()}}';

                $.ajax({
                    type:'POST',
                    url: url,
                    data:{district_id:district_id,city_id:city_id,village_id:village_id,_token:'{{csrf_token()}}'},
                    
                    success:function(resp){
                        $('#ward').html(resp);
                    }
                });
            }
        });

        $('#ward').change(function(){

            
            var ward_id = $('#ward').val();
            if(ward_id!='')
            { 

                var url = '{{$module_url_path}}/get_booths';
                var csrf_token      = '{{csrf_token()}}';

                $.ajax({
                    type:'POST',
                    url: url,
                    data:{ward_id:ward_id,_token:'{{csrf_token()}}'},
                    
                    success:function(resp){
                        $('#booth').html(resp);
                    }
                });
            }
        });

        $('#booth').change(function(){
            var booth_id = $('#booth').val();
            var ward_id = $('#ward').val();
            if(ward_id!='' && booth_id!='')
            { 
                var url = '{{$module_url_path}}/get_list';
                var csrf_token      = '{{csrf_token()}}';
                $.ajax({
                    type:'POST',
                    url: url,
                    data:{ward_id:ward_id,booth_id:booth_id,_token:'{{csrf_token()}}'},
                    success:function(resp){
                        $('#list').html(resp);
                    }
                });
            }
        });

        // Initialize select2
          // $("#selUser").select2();

          // // Read selected option
          // $('#but_read').click(function(){
          //   var username = $('#selUser option:selected').text();
          //   var userid = $('#selUser').val();

          //   $('#result').html("id : " + userid + ", name : " + username);

          // });


    });
    $('[data-type="adhaar-number"]').keyup(function() {
      var value = $(this).val();
      value = value.replace(/\D/g, "").split(/(?:([\d]{4}))/g).filter(s => s.length > 0).join("-");
      $(this).val(value);
    });

    $('[data-type="adhaar-number"]').on("change, blur", function() {
      var value = $(this).val();
      var maxLength = $(this).attr("maxLength");
      if (value.length != maxLength) {
        $(this).addClass("highlight-error");
      } else {
        $(this).removeClass("highlight-error");
      }
    });
</script>

<script>
  $('[data-type="contact-number"]').keyup(function() {
    var value = $(this).val();
    value = value.replace(/\D/g, "").split(/(?:([\d]{4}))/g).filter(s => s.length > 0).join("");
    $(this).val(value);
  });

  $('[data-type="contact-number"]').on("change, blur", function() {
    var value = $(this).val();
    var maxLength = $(this).attr("maxLength");
    if (value.length != maxLength) {
      $(this).addClass("highlight-error");
    } else {
      $(this).removeClass("highlight-error");
    }
  });
</script>

@endsection


