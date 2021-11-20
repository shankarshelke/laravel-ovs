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
                        
                    
                        <div class="table-responsive">
                             <div class="panel-body">
                           
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
                                        <th style="width: 40%;">House No</th>
                                        <td style="width: 60%;">{{$arr_user['house_no'] or 'NA'}}</td>
                                    </tr>
                                    <tr>
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
                                        </tr>
                                    <tr>
                             
                                    </tr>
                                     
                                </tbody>
                            </table>
                            <div class="form-group">
                                        <label class="col-sm-2 col-sm-2 control-label" style="width: 40%;"> <b>Voter Location</b></label>
                                        <a href="{{$module_url_path}}/get_location/{{$arr_user['address']}}"><button class="btn btn-link">Get Direction</button></a>
                                    </div> 

                        </div>
                    </div>

                    </div>
                            

                    <div class="panel-body">
                <div class="row">
                    

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

@endsection