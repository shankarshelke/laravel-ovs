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
                            voting Booth Details      
                        </header>
                        <br>
                        <div class="table-responsive">                             
                            <table class="table table-bordered  table-hover">
                                <tbody>
                                    <tr class="odd">
                                        <th style="width: 10%;">Booth No</th>
                                        <td style="width: 60%;">{{$arr_user['booth_no'] or 'NA'}}</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 10%;">Booth Name</th>
                                        <td style="width: 60%;">{{ $arr_user['booth_name'] or 'NA'}}</td>
                                    </tr>                                   
                                </tbody>
                            </table>                        
                        </div>                    
                    </section>
                </div>                                        
                <div class="panel-body">
                    <section>
                        <header class="panel-heading">
                            List Details      
                        </header>
                        <br>
                        <div class="table-responsive">
                            <table class="table table-bordered  table-hover">
                                <tbody>
                                    <tr class="odd">
                                        <th style="width: 10%;">List No</th>
                                        <th style="width: 40%;">List Name</th>
                                    </tr>
                                    <tr>
                                     @if(isset($arr_list) && count($arr_list)>0)
                                    <!-- {{$i=1}} -->
                                    @foreach($arr_list as $data)

                                    <td style="width: 10%;">{{$data['list_no'] or 'NA'}}</td>
                                    <td style="width: 60%;">{{$data['list_name'] or 'NA'}}</td>
                                    </tr>
                                     @php $i++; @endphp
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </section>
                    <div class="form-group">
                         <a href="{{ $module_url_path or 'NA' }}" class="btn btn-primary">Back</a>
                    </div>                    
                </div>
            </section>
        </div>
    </div>
</div>
@endsection