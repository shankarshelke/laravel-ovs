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
                            Transaction Detail      
                        </header>
                        <div class="table-responsive">
                            <div class="panel-body">
                                <table class="table table-bordered  table-hover">

                                    <tbody>
                                         
                                        <tr>
                                            <th style="width: 40%;">Transaction ID</th>
                                            <td style="width: 60%;">{{ $arr_amount['id'] or 'NA'}}</td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%;">Member Name</th>
                                            <td style="width: 60%;">{{ $arr_amount['get_admin_details']['first_name'] or 'NA'}} {{ $arr_amount['get_admin_details']['last_name'] or 'NA'}}</td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%;">Amount</th>
                                            <td style="width: 60%;">Rs.{{ $arr_amount['amount'] or 'NA'}}</td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%;">Transcation Date</th>
                                            <td style="width: 60%;">{{ $arr_amount['created_at'] or 'NA'}}</td>
                                        </tr>
                                        {{-- <tr>
                                            <th style="width: 40%;">Village</th>
                                            <td style="width: 60%;">{{ $arr_amount['get_village_details']['village_name'] or 'NA'}}</td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%;">City</th>
                                            <td style="width: 60%;">{{ $arr_amount['get_city_details']['city_name'] or 'NA'}}</td>
                                        </tr> --}}

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3 text-right">
                                <a href="{{ $module_url_path or 'NA' }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>

                    </div>             
            </section>
        </div>
    </div>
</div>


@endsection