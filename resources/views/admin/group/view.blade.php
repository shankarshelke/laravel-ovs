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
                        Contact Details      
                    </header>
                        <div class="table-responsive">
                             <div class="panel-body">
                            <table class="table table-bordered  table-hover">

                                <tbody>
                                    

                                     <tr class="odd">

                                        <th style="width: 40%;" >{{$arr_data ? $arr_data[0]['group_name']: ''}}</th>
                                        <th></th>
                                    </tr>
                                  <tr>
                                        <th style="width: 40%;">Contact Person Name</th>
                                        <th style="width: 40%;">Contact</th>
                                       
                                    </tr>
                                    @foreach($arr_data as $row)
                                    <tr>
                                        <td style="width: 40%;">{{$row ? $row->contact_person_name:''}}</td>
                                        <td style="width: 40%;">{{$row ? $row->contact_no:''}}</td>
                                       
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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