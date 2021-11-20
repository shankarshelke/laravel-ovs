
@extends('admin.layout.master')    
@section('main_content')
<style type="text/css">
    .form-inline .form-control {display: block;}
    section .panel-heading {padding: 0 0 10px;}
</style>
<div class="wrapper">
    <div class="row">
        <div class="col-sm-12">
            @include('admin.layout.breadcrumb')
            <section class="panel">
                <div class="panel-body">                
                    <section>
                        <header class="panel-heading">
                            Ward  Details      
                        </header>
                        <br>
                        <div class="table-responsive">                            
                            <table class="table table-bordered  table-hover">
                                <tbody>
                                    <tr class="odd">
                                        <th style="width: 10%;">Ward No</th>
                                        <td style="width: 60%;">{{ $arr_ward['ward_no'] or 'NA'}}</td>
                                    </tr>
                                    <tr class="">
                                        <th style="width: 10%;">Ward Name</th>
                                        <td style="width: 60%;">{{$arr_ward['ward_name'] or 'NA'}}</td>
                                    </tr>
                                    <tr class="odd">
                                        <th style="width: 10%;">Ward Address</th>
                                        <td style="width: 60%;">{{$arr_ward['ward_address'] or 'NA'}}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="form-group">
                                <a href="{{ $module_url_path or 'NA' }}" class="btn btn-primary">Back</a>
                            </div>                            
                        </div>                    
                    </section>
                </div>
            </section>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
    });
</script>
@endsection