@extends('admin.layout.master')    
@section('main_content')
<style type="text/css">
    .form-inline .form-control {display: block;}
    .form_txt{color:#65cea7}
    section .panel-heading {padding: 0 0 10px;}
</style>
<!--body wrapper start-->
<div class="wrapper">
    <div class="row">
        <div class="col-sm-12">
            @include('admin.layout.breadcrumb')
            <section class="panel">                                
                @include('admin.layout._operation_status')                   
                <div class="panel-body">                    
                    <section>
                        <header class="panel-heading">
                            Transaction Detail      
                        </header>
                        <br>
                        <div class="table-responsive">                            
                            <table class="table table-bordered  table-hover">
                                <tbody>
                                    <tr>
                                        <th style="width: 10%;">Transaction ID</th>
                                        <td style="width: 60%;">{{ $arr_amount['id'] or 'NA'}}</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 10%;">Voter Name</th>
                                        <td style="width: 60%;">{{ $arr_amount['get_user_details']['first_name'] or 'NA'}} {{ $arr_amount['get_user_details']['last_name'] or 'NA'}}</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 10%;">Accountant Name</th>
                                        <td style="width: 60%;">{{ $arr_amount['get_admin_details']['first_name'] or 'NA'}} {{ $arr_amount['get_admin_details']['last_name'] or 'NA'}}</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 10%;">Amount</th>
                                        <td style="width: 60%;">Rs.{{ $arr_amount['amount'] or 'NA'}}</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 10%;">Transcation Date</th>
                                        <td style="width: 60%;">{{ $arr_amount['created_at'] or 'NA'}}</td>
                                    </tr>
                                 {{--    <tr>
                                        <th style="width: 10%;">Village name</th>
                                        <td style="width: 60%;">{{$arr_amount['get_user_details']['village_name'] or 'NA'}}</td>
                                    </tr>
                                 {{dd($arr_amount['get_user_details']['village_name'])}} --}}
                                </tbody>
                            </table>                            
                        </div>
                        <div class="form-group">                        
                            <a href="{{ $module_url_path or 'NA' }}" class="btn btn-primary">Back</a>                        
                        </div>
                    </section>
                </div>             
            </section>
        </div>
    </div>
</div>
@endsection