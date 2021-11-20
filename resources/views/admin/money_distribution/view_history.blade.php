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
                   <!-- {{$i=0}} -->
                   <!--{{$total_amount=0}}-->
                <div class="panel-body">
                <div class="row">
                    <div class="col-md-9"> 
                         <header class="panel-heading" align="center">
                            Transaction History
                         {{-- Name : {{ $arr_history[0]['get_admin_details']['first_name'] ?? 'NA' }} {{ $arr_history[0]['get_admin_details']['last_name'] ?? 'NA' }}--}}
                        </header> 
                        <div class="table-responsive">
                            <div class="panel-body">
                        <h4>Name : {{ $arr_history[0]['get_admin_details']['first_name'] ?? 'NA' }} {{ $arr_history[0]['get_admin_details']['last_name'] ?? 'NA' }}</h4>
                       {{--  <h4>Total Amount : </h4> --}}
                                             
                                       
                                <table class="table table-bordered  table-hover">
                    
                                    <thead>
                    

                                         <tr align="center">
                                            <th style="width: 20%;">Transaction No.</th>
                                            <th style="width: 20%;">Amount</th>
                                            <th style="width: 20%;">Transcation Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($arr_history as $key =>$value)
                                        
                                        <tr>
                                        
                                            <td align="center">@if (@isset ($value['id'])){{$value['id']}} @endif</td>
                                            
                                            <td>
                                                @if (@isset ($value['amount']))Rs.{{$value['amount']}} @endif
                                            </td>
                                            <td>
                                                @if (@isset ($value['created_at'])){{$value['created_at']=date("d-m-Y")}} @endif
                                            </td>
                                        
                                
                                        </tr>
                                       {{--   {{dd($value)}} --}}
                                         
                                        <!--{{$total_amount=$total_amount+$value['amount']}}-->
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                             <div class="form-group">
                            <div class="col-sm-4 text-left">
                               <label> <h4><b>Total Amount</b>=Rs.{{$total_amount}}</h4></label>
                               

                            </div>
                        </div> 

                        <div class="form-group">
                            <div class="col-sm-3 text-right">
                                <a href="{{url('/')}}/admin/finance_team" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                        </div>
                    </div>

            </section>
        </div>
    </div>
</div>


@endsection