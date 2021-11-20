@extends('admin.layout.master')
<style type="text/css">
	#map {
  height: 30%;
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
</style>
    
@section('main_content')
<!--body wrapper start-->
<div class="wrapper">
	<div class="row">
		<div class="col-md-12">
			@include('admin.layout.breadcrumb')

			<section class="panel">
				<header class="panel-heading">
					{{$sub_module_title or ''}}
				</header>

				<div class="panel-body " >
					@include('admin.layout._operation_status') 
					<form action="{{$module_url_path}}/import_file" id="frm_create_page" name="frm_create_page" class="form-horizontal cmxform" method="post" enctype="multipart/form-data">
						{{csrf_field()}}
						<section class="panel">
                    <br>
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Import File<i style="color:red;">*</i></label>
							<div class="col-sm-3">
								<input type="file" id="file" name="file" value="{{old('file')}}" data-rule-required="true"  
								 class="form-control" data-type="adhaar-number" >
								<span class="error" style="color: red;">{{ $errors->first('file') }} </span>
							</div> 

						{{-- </div> --}}
							<div class="col-sm-8 col-md-8 col-lg-9">
		<div class="note"><b>Note : </b>Please Below Format.</div>
					<a href="{{url('/')}}/uploads/excel_format/user.xlsx" download="">Download</a>
						</div>
					</section>
						<div class="form-group">
							<div class="col-sm-8 text-right">
								<a href="{{ $module_url_path or 'NA' }}" class="btn btn-primary">Back</a>
								<button class="btn btn-primary" type="submit"  id="btn_add_front_page">Import</button>
							</div>
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
            maxDate: '-1d',
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
    zoom: 12
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
	                            if(results[0].address_components[i].types[0] == 'sublocality_level_1'){
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
            var city_id = $('#city').val();
            var village_id = $('#village').val();
            var district_id = $('#district').val();
            if(district_id!='' && city_id!='' && village_id!='' && ward_id!='')
            { 

                var url = '{{$module_url_path}}/get_booths';
                var csrf_token      = '{{csrf_token()}}';

                $.ajax({
                    type:'POST',
                    url: url,
                    data:{district_id:district_id,city_id:city_id,village_id:village_id,ward_id:ward_id,_token:'{{csrf_token()}}'},
                    
                    success:function(resp){
                        $('#booth').html(resp);
                    }
                });
            }
        });

        $('#booth').change(function(){

            var booth_id = $('#booth').val();
            var ward_id = $('#ward').val();
            var city_id = $('#city').val();
            var village_id = $('#village').val();
            var district_id = $('#district').val();
            if(district_id!='' && city_id!='' && village_id!='' && ward_id!='' && booth_id!='')
            { 

                var url = '{{$module_url_path}}/get_list';
                var csrf_token      = '{{csrf_token()}}';

                $.ajax({
                    type:'POST',
                    url: url,
                    data:{district_id:district_id,city_id:city_id,village_id:village_id,ward_id:ward_id,booth_id:booth_id,_token:'{{csrf_token()}}'},
                    
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

@endsection


