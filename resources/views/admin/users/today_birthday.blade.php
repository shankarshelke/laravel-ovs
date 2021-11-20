@extends('admin.layout.master')    
@section('main_content')
<?php 
use App\Models\UsersModel;
use App\Models\SentSmsModel;

 ?>
<style type="text/css">
    .form-inline .form-control {display: block;}
</style>
<!--body wrapper start-->
<div class="wrapper">
    <div class="row">
        <div class="col-sm-12">
            @include('admin.layout.breadcrumb')
            <section class="panel">
                <div class="panel-body" style="position: relative">
                    @include('admin.layout._operation_status')
<!--                     <div class="table-action-buttons-top">
                        <a href="{{ url($module_url_path) }}" class="btn btn-default btn-rounded show-tooltip" title="Refresh" >Refresh</a>
                        <a href="#myModal" class="btn btn-default btn-rounded show-tooltip" title="Notification" onclick="check_empty()">Notification</a>
                        {{--  <a href="#myModal" class="btn btn-default btn-rounded show-tooltip" title="Notification" onclick="check_empty()">Send Msg</a> --}}
                        @if(get_admin_access('voters','create'))
                        <a href="{{ url($module_url_path) }}/create">Add</a>
                        @endif
                        @if(get_admin_access('voters','create'))
                        <a href="{{ url($module_url_path) }}/import">Import</a>
                        @endif                        
                        @if(get_admin_access('voters','approve'))
                        <a href="javascript:void(0)" class="btn btn-default btn-rounded show-tooltip" title="Deactivate Multiple" onclick="check_multi_action('frm_manage','deactivate')">Deactivate</a>
                        @endif
                        @if(get_admin_access('voters','approve'))
                        <a href="javascript:void(0)" class="btn btn-default btn-rounded show-tooltip" title="Activate Multiple" onclick="check_multi_action('frm_manage','activate')">Activate</a>
                        @endif
                        @if(get_admin_access('voters','delete'))    
                        <a href="javascript:void(0)" class="btn btn-default btn-rounded show-tooltip" title="Delete Multiple" onclick="
                            check_multi_action('frm_manage','delete')">Delete</a>
                        @endif
                    </div> -->
                    <div class="adv-table">
                        <div id="hidden-table-info_wrapper" class="dataTables_wrapper form-inline table-responsive" role="grid">
                            <form name="frm_manage" id="frm_manage" method="POST" class="form-horizontal" action="{{url($module_url_path)}}/multi_action">
                                    {{ csrf_field() }}
                                <input type="hidden" name="multi_action" value="" />
                                <!--<table class="display table table-bordered dataTable" id="hidden-table-info" id="tbl_activitylog_listing">-->
                                <table class="display table table-bordered dataTable" id="myTable" id="tbl_activitylog_listing"> 
                                    <thead>
                                        <tr>
                                           <!--  <th>
                                                <input type="checkbox" class="filled-in" name="selectall" id="select_all" onchange="chk_all(this)" /><label for="selectall"></label>
                                            </th> -->
                                            <th>#</th>
                                            
                                            <th>Full Name</th>
                                            <th>Family Member</th>
                                            <th>Contact</th>
                                            <th>Date Of Birth</th>
                                            <th>SMS Status</th>
                                              
                                            
                                    </thead>
                                    <tbody>
                                        <?php 
                                         $i =0;
                                          ?> 
                                        @foreach($arr_data as $val)
                                        <?php 
                                        $i++;
                                        $date = date('Y-m-d');
                                         $get_family_name = UsersModel::where('family_id',$val['family_id'])
                                                            ->whereNotNull('mobile_number')
                                                            ->whereRaw('LENGTH(mobile_number) = 10')
                                                            ->first();
                                         $get_sms_status = SentSmsModel::where('user_id',$val['id'])
                                                            ->where('flag_id','1')
                                                            ->where('created_at',$date)
                                                            ->first();                                                            
                                        ?>
                                        <tr>
                                            <td>{{$i}}</td>
                                           
                                            <td><a href="{{url('/')}}/admin/voters/view/{{base64_encode($val['id'])}}">
                                                {{$val ? $val['first_name']:''}}&nbsp; {{$val ? $val['father_full_name']:''}}&nbsp;  {{$val ? $val['last_name']:''}}
                                                    </a>
                                            </td>
                                            <td>
                                                <?php 
                                                    if($val['mobile_number'] ==null || strlen((string)$val['mobile_number']) < 10){
                                                        if(isset($get_family_name)){
                                                            echo $get_family_name['full_name'];
                                                        }
                                                        
                                                    }        
                                                ?>

                                            </td>
                                            <td>
                                               <?php 
                                              $contact = (empty($val['mobile_number']) || strlen($val['mobile_number']) < 10) ? ((empty($get_family_name['mobile_number']) || strlen($get_family_name['mobile_number']) < 10) ? null : $get_family_name['mobile_number']) :$val['mobile_number'];
                                              echo $contact;
                                               ?>
                                           
                                            </td>
                                            <td>@if($val['date_of_birth'] !=null)
                                                {{date("d-m-Y ",strtotime($val ? $val['date_of_birth']:'' ))}}
                                                @endif</td>
                                 
                                            <td>
                                            <?php 
                                                if(isset($get_sms_status))
                                                {
                                                    echo '<span class="label label-success label-mini">Sent</span>';
                                                }
                                     
                                                else
                                                {                   
                                                    echo '<span class="label label-danger label-mini">Not Sent</span>';   
                                                }
                                                ?>
                                               
                                            </td>
                                           
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>

                            </form>
                        </div>
                     
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<script>
    $(".add-form-btn-section").on("click",function() {
        $("body").addClass("add-form-open");
    });
    $(".add-form-close-btn,.add-page-back-btn").on("click",function() {
        $("body").removeClass("add-form-open");
        $("body").removeClass("edit-form-open");
    }); 
</script>
<script>
    $('body').on('click','.edit_button',function(){        
        $("body").addClass("edit-form-open");        
    });
</script>

<script type="text/javascript">
    function check_empty(frm_id,action)
  {
    // var len = $('input[name="'+checked_record+'"]:checked').length;

    var len = $('input[name="checked_record[]"]:checked').length;
    var flag=1;
    var frm_ref = $("#"+frm_id);
    if(len<=0)
    {
      swal("Oops..","Please select the record to perform this Action.");
      return false;
    }
    else
    {
      $('#myModal').modal('show');
    }

}

</script>   
@endsection