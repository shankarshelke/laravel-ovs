@extends('admin.layout.master')    
@section('main_content')
<style type="text/css">
    .form-inline .form-control {display: block;}
    section .panel-heading {padding: 0 0 10px;}
</style>
<!--body wrapper start-->

<div class="wrapper">
    <div class="row">
        <div class="col-sm-12">
            @include('admin.layout.breadcrumb')
            <section class="panel">
                <div class="panel-body">
                    <section>
                        <header class="panel-heading">
                            Voting List Details      
                        </header>
                        <br>
                        <div class="table-responsive">                             
                            <table class="table table-bordered  table-hover">
                                <tbody>
                                    <tr class="odd">
                                        <th style="width: 10%;">Booth </th>
                                        <td style="width: 60%;">({{$arr_user['get_booth_details']['booth_no']}}){{$arr_user['get_booth_details']['booth_name']}}</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 10%;">List No</th>
                                        <td style="width: 60%;">{{ $arr_user['list_no'] or 'NA'}}</td>
                                    </tr>
                                    <tr class="odd">
                                        <th style="width: 10%;">List Name</th>
                                        <td style="width: 60%;">{{$arr_user['list_name'] or 'NA'}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>
                    <div class="form-group">
                        <a href="{{ $module_url_path or 'NA' }}/manage_list" class="btn btn-primary">Back</a>
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

@endsection