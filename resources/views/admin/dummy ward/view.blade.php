

@extends('admin.layout.master')    
@section('main_content')
<style type="text/css">
    .form-inline .form-control {display: block;}
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
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                    <header class="panel-heading">
                        Voting List Details      
                    </header>
                        <div class="table-responsive">
                             <div class="panel-body">
                            <table class="table table-bordered  table-hover">

                                <tbody>
                                    {{-- <tr>
                                        <th style="width: 40%;">Village Name</th>
                                        <td style="width: 60%;">{{ $arr_user['village_name'] or 'NA'}}</td>

                                    </tr> --}}
                                    <tr>
                                        <th style="width: 40%;">ward No</th>
                                        <td style="width: 60%;">{{ $arr_user['ward_no'] or 'NA'}}</td>
                                    </tr>
                                    <tr class="odd">
                                        <th style="width: 40%;">ward Name</th>
                                        <td style="width: 60%;">{{$arr_user['ward_name'] or 'NA'}}</td>
                                    </tr>
                                    <tr class="odd">
                                        <th style="width: 40%;">ward Address</th>
                                        <td style="width: 60%;">{{$arr_user['ward_address'] or 'NA'}}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="col-sm-8 text-right">
                                <a href="{{ $module_url_path or 'NA' }}" class="btn btn-primary">Back</a>
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

@endsection