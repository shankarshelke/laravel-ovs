@extends('admin.layout.master')    
@section('main_content')

<style type="text/css">
    #map {
  height: 30%;
  /*width: 100%;*/
}
html, body {
  height: 100%;
  width: 100%;
  margin: 0;
  padding: 0;
}
<style type="text/css">
    .form-inline .form-control {display: block;}
    .form_txt{color:#65cea7}
</style>
<!--body wrapper start-->
<div class="wrapper">
    <div class="row">
        <div class="col-sm-12">
            @include('admin.layout.breadcrumb')
            <section class="panel">
                <header class="panel-heading">
                    {{$module_title or  '' }}
                </header>
                <div class="panel-body">
                    @include('admin.layout._operation_status')
                   
                <div class="panel-body">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        
                    <header class="panel-heading">
                        Personal Details      
                    </header>
                        <div class="table-responsive">
                             <div class="panel-body">
                            <table class="table table-bordered  table-hover">

                                <tbody>
                                    
                                    
                                    {{-- <tr class="odd">
                                        <th style="width: 40%;">Aadhar</th>
                                        <td style="width: 60%;">{{ $arr_user['aadhar_id'] or 'NA'}}</td>
                                    </tr> --}}
                                    <tr>
                                        <th style="width: 40%;">First Name</th>
                                        <td style="width: 60%;">{{$arr_user['first_name'] or 'NA'}}</td>
                                    </tr>
                                    <tr class="odd">
                                        <th style="width: 40%;">Last Name</th>
                                        <td style="width: 60%;">{{$arr_user['last_name'] or 'NA'}}</td>
                                    </tr>
                                    
                                    <tr>
                                        <th style="width: 40%;">Father/Husband Name</th>
                                        <td style="width: 60%;">{{$arr_user['father_full_name'] or 'NA'}}</td>
                                    </tr>
                                    <tr class="odd">
                                        <th style="width: 40%;">Email</th>
                                        <td style="width: 60%;">{{$arr_user['email'] or 'NA'}}</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%;">Mobile No</th>
                                        <td style="width: 60%;">{{$arr_user['mobile_number'] or 'NA'}}</td>
                                    </tr>
                                    <tr class="odd">
                                        <th style="width: 40%;">Date Of Birth</th>
                                        <td style="width: 60%;">{{$arr_user['date_of_birth'] or 'NA'}}</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%;">Gender</th>
                                        <td style="width: 60%;">{{$arr_user['gender'] or 'NA'}}</td>
                                    </tr>

                                    <tr class="odd">
                                        <th style="width: 40%;">Religion</th>
                                        <td style="width: 60%;">{{$arr_user['get_religion_details']['religion_name'] or 'NA'}}</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%;">Caste</th>
                                        <td style="width: 60%;">{{$arr_user['get_caste_details']['caste_name'] or 'NA'}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    </div>

                    <div class="panel-body">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        
                    <header class="panel-heading">
                        Address Details      
                    </header>
                        <div class="table-responsive">
                             <div class="panel-body">
                            <table class="table table-bordered  table-hover">

                                <tbody>
                                    <tr class="odd">
                                        <th style="width: 40%;">Address</th>
                                        <td style="width: 60%;">{{$arr_user['house_no'] or 'NA'}}</td>
                                    </tr>
                                    {{-- <tr>
                                        <th style="width: 40%;">Street/Area/Locality</th>
                                        <td style="width: 60%;">{{ $arr_user['street'] or 'NA'}}</td>
                                    </tr>
                                    <tr class="odd">
                                        <th style="width: 40%;">Pincode</th>
                                        <td style="width: 60%;">{{$arr_user['pincode'] or 'NA'}}</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%;">District</th>
                                        <td style="width: 60%;">{{$arr_user['get_district_details']['district_name'] or 'NA'}}</td>
                                    </tr>
                                    
                                    <tr class="odd">
                                        <th style="width: 40%;">City</th>
                                        <td style="width: 60%;">{{$arr_user['get_cities_details']['city_name'] or 'NA'}}</td>
                                    </tr>
                                        <tr>
                                            <th style="width: 40%;">Town/village</th>
                                            <td style="width: 60%;">{{$arr_user['get_village_details']['village_name'] or 'NA'}}</td>
                                        </tr> --}}
                                    <tr>
                             
                                    </tr>
                                     
                                </tbody>
                            </table>
                            <div class="form-group">
                                        <label class="col-sm-2 col-sm-2 control-label" style="width: 40%;"> <b>Voter Location</b></label>
                                       <a href="{{$module_url_path}}/get_location/{{base64_encode($arr_user['id'])}}"><button class="btn btn-link">Get Direction</button></a>
                                    </div> 

                        </div>
                    </div>

                    </div>
                            

                    <div class="panel-body">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        
                    <header class="panel-heading">
                        Other Details      
                    </header>
                        <div class="table-responsive">
                             <div class="panel-body">
                            <table class="table table-bordered  table-hover">

                                <tbody>
                                    <tr class="odd">
                                        <th style="width: 40%;">Ward no</th>
                                        <td style="width: 60%;">{{$arr_user['get_ward_details']['ward_no'] or 'NA'}}</td>
                                    </tr>
                                     <tr>
                                        <th style="width: 40%;">Ward Name</th>
                                        <td style="width: 60%;">{{$arr_user['get_ward_details']['ward_name'] or 'NA'}}</td>
                                    </tr>
                                    <tr class="odd">
                                        <th style="width: 40%;">Booth no</th>
                                        <td style="width: 60%;">{{$arr_user['get_booth_details']['booth_no'] or 'NA'}}</td>
                                    </tr>
                                     <tr>
                                        <th style="width: 40%;">Booth Name</th>
                                        <td style="width: 60%;">{{$arr_user['get_booth_details']['booth_name'] or 'NA'}}</td>
                                    </tr>
                                    {{-- <tr class="odd">
                                        <th style="width: 40%;">Booth Address</th>
                                        <td style="width: 60%;">{{$arr_user['get_booth_details']['booth_address'] or 'NA'}}</td>
                                    </tr> --}}
                                    <tr >
                                        <th style="width: 40%;">List No</th>
                                        <td style="width: 60%;">{{$arr_user['get_list_details']['list_no'] or 'NA'}}</td>
                                    </tr>
                                    <tr class="odd">
                                        <th style="width: 40%;">List Name</th>
                                        <td style="width: 60%;">{{$arr_user['get_list_details']['list_name'] or 'NA'}}</td>
                                    </tr>
                                    <tr >
                                        <th style="width: 40%;">Occupation</th>
                                        <td style="width: 60%;">{{$arr_user['get_occupation_details']['occupation_name'] or 'NA'}}</td>
                                    </tr>
                                    {{-- <tr class="odd">
                                        <th style="width: 40%;">Face Color</th>
                                        <td style="width: 60%;">{{$arr_user['face_color'] or 'NA'}}</td>
                                    </tr>
                                     --}}
                                    <tr >
                                        <th style="width: 40%;">Voting Surety</th>
                                        <td style="width: 60%;">@if(isset($arr_user['voting_surety']) && $arr_user['voting_surety']=='0')
                                                <a class='text-success'>Full Surety</a>  
                                                @endif
                                                @if(isset($arr_user['voting_surety']) && $arr_user['voting_surety']=='1')
                                                <a class='text-warning'>Half Surety</a>  
                                                @endif
                                                @if(isset($arr_user['voting_surety']) && $arr_user['voting_surety']=='2')
                                                <a class='text-danger'>No Surety</a>  
                                                @endif
                                                  </td>
                                    </tr>
                                    <tr class="odd">
                                        <th style="width: 40%;">Status</th>
                                        <td style="width: 60%;">
                                            @if(isset($arr_user['status']) && $arr_user['status']=='1')
                                                <a class='text-success'>Active</a>  
                                            @else
                                                    <a class='text-danger'>InActive</a> 
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    </div>

                    <div class="row">
                        <div class="col-md-9">
                                <a class="btn back-btn" style='float: right; margin-right: 20px;' title="Back" href="{{$module_url_path}}">Back</a>
                        </div>
                    </div>
        </div>
    </div>
                   

                 
                </div>
            </section>
        </div>
    </div>
</div>
<!--body wrapper end-->
<script type="text/javascript">

$(document).ready(function() {

});

</script>

<script async defer
src="https://maps.googleapis.com/maps/api/js?key={{ config('app.project.google_map_api_key') }}&callback=initMap">
</script>
<script>
    // Note: This example requires that you consent to location sharing when
// prompted by your browser. If you see the error "The Geolocation service
// failed.", it means you probably did not give permission for the browser to
// locate you.
var map, infoWindow;
var longitude = parseFloat(document.getElementById('longitude').value);
var latitude = parseFloat(document.getElementById('latitude').value);

  
  var nashik   = { lat: latitude ,
                        lng: longitude };

function initMap() {
  map = new google.maps.Map(document.getElementById('map'), {
    center: nashik,
    zoom: 8
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
                });
  infoWindow = new google.maps.InfoWindow;
}

</Script>

@endsection