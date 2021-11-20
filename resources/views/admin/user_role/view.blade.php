@extends('admin.layout.master')    
@section('main_content')
<style>
    section .panel-heading {padding: 0 0 10px;}
</style>
<div class="wrapper">
    <div class="row">
        <div class="col-md-12">
            @include('admin.layout.breadcrumb')                          
            <div class="panel">
                @include('admin.layout._operation_status')
                <div class="panel-body">
                    <section>
                        <header class="panel-heading">{{ trans('user_role.role details') }}</header>
                        <br>
                        <div class="table-responsive">                         
                            <table class="table table-bordered table-striped table-hover">
                                <tbody>
                                    <tr>
                                        <th style="width: 10%;">{{ trans('user_role.role') }}</th>
                                        <td style="width: 60%;">{{$arr_user['role'] or 'NA'}} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 10%;">{{ trans('user_role.description') }}</th>
                                        <td style="width: 60%;">{{$arr_user['description'] or 'NA'}}</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 10%;">{{ trans('user_role.status') }}</th>
                                        <td style="width: 60%;">
                                            @if(isset($arr_user['status']) && $arr_user['status']=='1')
                                                <a class='text-success'>{{ trans('user_role.active') }}</a>	
                                            @else
                                                    <a class='text-danger'>{{ trans('user_role.inactive') }}</a>	
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>                        
                        </div>
                        <div class="form-group">							
                            <a class="btn btn-primary back-btn" title="Back" href="{{$module_url_path}}">{{ trans('user_role.back') }}</a>
                        </div>                
                    </section>
                </div>                
            </div>            
        </div>
    </div>
</div>
@endsection