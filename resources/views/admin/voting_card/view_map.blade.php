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
             {{--    <header class="panel-heading">
                    {{$sub_module_title or ''}}
                </header> --}}

                <div class="panel-body " >
                    @include('admin.layout._operation_status') 
                    <form action="{{$module_url_path}}/store" id="frm_create_page" name="frm_create_page" class="form-horizontal cmxform" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                       
                        
                    <section class="panel">
                        {{-- <header class="panel-heading">
                     
                            <div class="form-group">
                            <div class="col-sm-8 text-right">
                                <a href="{{ $module_url_path or 'NA' }}" class="btn btn-primary">Back</a>
                                
                            </div>
                        </div>
                        </header> --}}
                   
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"><i style="color:red;"></i></label>
                            <div class="col-sm-8">
                            {{-- <div id="floating-panel">
                            <b>Mode of Travel: </b>
                                <select id="mode">
                                  <option value="DRIVING">Driving</option>
                                  <option value="WALKING">Walking</option>
                                  <option value="BICYCLING">Bicycling</option>
                                  <option value="TRANSIT">Transit</option>
                                </select>
                            </div> --}}
                            <div id="map"></div>
                            <div id="right-panel">
                              <p>Total Distance: <span id="total"></span></p>
                            </div>
                        </div>
                    </section>
                    
                        
                    </form>
                </div>
            </section>
        </div>
    </div>
</div>
<!--body wrapper end-->


<script async defer
src="https://maps.googleapis.com/maps/api/js?key={{ config('app.project.google_map_api_key') }}&callback=initMap">
</script>
{{-- <script>
      function initMap() {
        var directionsRenderer = new google.maps.DirectionsRenderer;
        var directionsService = new google.maps.DirectionsService;
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 14,
          center: {lat: 19.9974533, lng: 73.7898023}
        });
        directionsRenderer.setMap(map);

        calculateAndDisplayRoute(directionsService, directionsRenderer);
        document.getElementById('mode').addEventListener('change', function() {
          calculateAndDisplayRoute(directionsService, directionsRenderer);
        });
      }

      function calculateAndDisplayRoute(directionsService, directionsRenderer) {
        var selectedMode = document.getElementById('mode').value;
        directionsService.route({
          origin: {lat: 19.9974533, lng: 73.7898023},  // Haight.
          destination: {lat:{{$data_arr[0]}}, lng:{{$data_arr[1]}}},  // Ocean Beach.
          // Note that Javascript allows us to access the constant
          // using square brackets and a string value as its
          // "property."
          travelMode: google.maps.TravelMode[selectedMode]
        }, function(response, status) {
          if (status == 'OK') {
            directionsRenderer.setDirections(response);
          } else {
            window.alert('Directions request failed due to ' + status);
          }
        });
      }
    </script> --}}

      <script>
      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 30,
          // center: {lat: 19.9974533, lng: 73.7898023}//Nashik
        });

        var directionsService = new google.maps.DirectionsService;
        var directionsRenderer = new google.maps.DirectionsRenderer({
          draggable: true,
          map: map,
          panel: document.getElementById('right-panel')
        });

        directionsRenderer.addListener('directions_changed', function() {
          computeTotalDistance(directionsRenderer.getDirections());
        });

        displayRoute({lat: 19.9974533, lng: 73.7898023}, {lat:{{$data_arr[0]}}, lng:{{$data_arr[1]}}}, directionsService,
            directionsRenderer);
      }

      function displayRoute(origin, destination, service, display) {
        service.route({
          origin: {lat:{{$admin_latitude}}, lng:{{$admin_longitude}} },  // Haight.
          destination: {lat:{{$data_arr[0]}}, lng:{{$data_arr[1]}}}, 
          //waypoints: [{location: 'Adelaide, SA'}, {location: 'Broken Hill, NSW'}],
          travelMode: 'DRIVING',
          avoidTolls: true
        }, function(response, status) {
          if (status === 'OK') {
            display.setDirections(response);
          } else {
            alert('Could not display directions due to: ' + status);
          }
        });
      }

      function computeTotalDistance(result) {
        var total = 0;
        var myroute = result.routes[0];
        for (var i = 0; i < myroute.legs.length; i++) {
          total += myroute.legs[i].distance.value;
        }
        total = total / 1000;
        document.getElementById('total').innerHTML = total + ' km';
      }
    </script>


@endsection


