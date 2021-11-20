@extends('front.layout.master')
@section('main_content')
<section class="gray-bg-main-section">

    @if(isset($arr_availability['data']) && count($arr_availability['data']) > 0)

    <div class="container">

        @include('front.layout.operation_status')

        <div class="request-quote-filter-section">
            <div class="row m-l--5 m-r--5">
                <div class="col-sm-2 col-md-2 col-lg-8 p-l-5 p-r-5">
                    <div class="showing-txt-section">
                        Showing {{ $arr_availability['from'] }} – {{ $arr_availability['to']}} of {{$arr_availability['total']}} results
                    </div>
                </div>
                <div class="col-sm-2 col-md-2 col-lg-4 p-l-5 p-r-5 pull-right">
                    <div class="send-invitation-button">
                        <a href="{{ url('/').'/operator/aircrafts/availability/add/'.$enc_id }}">
                            <button><i class="fa fa-plus"></i> Add Availability</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-content">
                <thead>
                    <tr>
                        <th>Model</th>
                        <th>From Date</th>
                        <th>To Date</th>
                        <th>Default Location</th>
                        <th>Available</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($arr_availability['data'] as $row)
                    <tr>
                        <td>{{ $row['get_aircraft_details']['get_aircraft_type']['model_name'] }}</td>
                        <td>{{ date('d M Y', strtotime($row['from_date'])) }}</td>
                        <td>{{ date('d M Y', strtotime($row['to_date'])) }}</td>
                        <td>{{ $row['get_aircraft_details']['base_of_operation'] }}</td>
                        @if(isset($row['is_available']) && $row['is_available'] == 'NO' )
                            <td><a class="btn btn-xs btn-danger" title="Not Available" href="{{ url('/') }}/operator/aircrafts/availability/unblock/{{ base64_encode($row['id']) }}" onclick="return confirm_action(this,event,\'Do you really want to activate this record ?\')" >NO</a></td>
                        @else
                            <td><a class="btn btn-xs btn-success" title="Available" href="{{ url('/') }}/operator/aircrafts/availability/block/{{ base64_encode($row['id']) }}" onclick="return confirm_action(this,event,\'Do you really want to inactivate this record ?\')" >YES</a></td>
                        @endif

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    @else

    <div class="container">

        <div class="request-quote-filter-section">
            <div class="row m-l--5 m-r--5">
                <div class="col-sm-2 col-md-2 col-lg-12 p-l-5 p-r-5 pull-right">
                    <div class="send-invitation-button">
                        <a href="{{ url('/').'/operator/aircrafts/availability/add/'.$enc_id }}">
                            <button><i class="fa fa-plus"></i> Add Availability</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

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

</section>

<script>

    $(document).ready(function(){
       $("body").on("click",'.filter-icon-btn', function(){                
            $(".onclick-show").show();
            $(".onclick-hide").hide();
        }); 
    });        
    
</script>
@endsection