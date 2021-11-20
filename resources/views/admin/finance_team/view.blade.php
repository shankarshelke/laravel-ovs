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
                            Finance Member Detail      
                        </header>
                        <div class="table-responsive">
                            <div class="panel-body">
                                <table class="table table-bordered  table-hover">

                                    <tbody>
                                        <tr>
                                            <th style="width: 40%;">Member ID</th>
                                            <td style="width: 60%;">{{ $arr_finance_team['id'] or 'NA'}}</td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%;">Member Name</th>
                                            <td style="width: 60%;">{{ $arr_finance_team['get_admin_details']['first_name'] or 'NA'}} {{ $arr_finance_team['get_admin_details']['last_name'] or 'NA'}}</td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%;">Ward Name</th>
                                            <td style="width: 60%;">{{ $arr_finance_team['get_ward_details']['ward_name'] or 'NA'}} </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-1 text-right">
                                <a href="{{ $module_url_path or 'NA' }}" class="btn btn-primary">Back</a>
                            </div>                      
                        </div>                    

                    </section>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection