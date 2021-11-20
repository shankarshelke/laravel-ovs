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
                {{-- <header class="panel-heading">
                    {{$module_title or  '' }}

                </header> --}}
                <div class="panel-body" style="position: relative">
                    @include('admin.layout._operation_status')
                    <div class="table-action-buttons-top">
                        <a href="{{ url($module_url_path) }}" class="btn btn-default btn-rounded show-tooltip" title="Refresh" >Refresh</a>
                        @if(get_admin_access('voter_money_distribution','create'))
                        <a href="javascript:void(0);" class="btn btn-default btn-rounded show-tooltip add-form-btn-section" title="Add New">Add</a>
                        @endif
<!--                        <a href="{{ url($module_url_path) }}/create" class="btn btn-default btn-rounded show-tooltip" title="Add" >Add</a>-->
                        @if(get_admin_access('voter_money_distribution','approve'))
                        <a href="javascript:void(0)" class="btn btn-default btn-rounded show-tooltip" title="Deactivate Multiple" onclick="check_multi_action('frm_manage','deactivate')">Deactivate</a>                        
                        <a href="javascript:void(0)" class="btn btn-default btn-rounded show-tooltip" title="Activate Multiple" onclick="check_multi_action('frm_manage','activate')">Activate</a>
                        @endif
                        @if(get_admin_access('voter_money_distribution','delete'))
                        <a href="javascript:void(0)" class="btn btn-default btn-rounded show-tooltip" title="Delete Multiple" onclick="check_multi_action('frm_manage','delete')">Delete</a>
                        @endif
                    </div>

                    <div class="adv-table">
                        <div id="hidden-table-info_wrapper" class="dataTables_wrapper form-inline table-responsive" role="grid">
                            <form name="frm_manage" id="frm_manage" method="POST" class="form-horizontal" action="{{url($module_url_path)}}/multi_action">
                                {{ csrf_field() }}
                                <input type="hidden" name="multi_action" value="" />
                                <table class="display table table-bordered dataTable" id="myTable" id="tbl_activitylog_listing"> 
<!--                                 {{-- <table id="myTable" class="table table-striped"> --}}-->                                       
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" class="filled-in" name="selectall" id="select_all" onchange="chk_all(this)" /><label for="selectall"></label>
                                                <th>Voter Name
                                                     {{-- <input type="text" name="voter_name" placeholder="Search" class="search-block-new-table form-control column_filter">  --}}
                                                </th>
                                            {{-- @if($type=='SUPERADMIN') --}}
                                               <th>Accountant Name
                                                 {{-- <input type="text" name="full_name" placeholder="Search" class="search-block-new-table form-control column_filter">  --}}
                                            </th>
                                         {{--    @endif --}}
                                            <th>Amount
                                               {{-- <input type="text" name="amount" placeholder="Search" class="search-block-new-table form-control column_filter">  --}}
                                            </th>
                                            <th>Date
                                          {{-- <input type="text" id="datepicker" name="d_date" placeholder="Search" class="search-block-new-table form-control column_filter"> --}}
                                            </th>

                                          {{-- <th>Status --}}
                                                {{-- <select class="search-block-new-table column_filter form-control" id="status" name="status">
                                                    <option value="">Select</option>
                                                    <option value="1">Active</option>
                                                    <option value="0">InActive</option>
                                                </select> --}}
                                            {{-- </th> --}}
                                            <th width="50px">Action</th>
                                      </tr>
                                    </thead>
                                    <tbody></tbody>
<!--                                </table>-->
                             </table>
                            </form>
                        </div>                       
                        <div class="add-form-section-main section-add-form">
                            <form action="{{$module_url_path}}/store" id="frm_blogs_page" name="frm_blogs_page" class="cmxform" method="post">
                                {{csrf_field()}}
                                <div class="form-head-section">
                                    Handover Money <span class="add-form-close-btn"><i class="ti-close"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="scroll-main-section">
                                    <div class="form-group">
                                        <label class="control-label" for="user_id">Select Voter<i class="red">*</i></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="ti-user"></i></span>
                                            <select name="user_id" data-rule-required="true" id="user_id"  class="form-control ">
                                                <option value="" >Select Voter </option>
                                                @if(isset($arr_voter_team) && count($arr_voter_team)>0)
                                                    @foreach($arr_voter_team as $users)
                                                    <option value="{{$users['id']}}">{{$users['first_name'] or ''}}  {{$users['last_name'] or ''}}
                                                    </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            
                                        </div>
                                        <input type="hidden" name="remaining_balance" value="{{$remaining_balance=$admin_money-$voter_money}}"> 
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Enter Amount<i style="color:red;">*</i></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="ti-user"></i></span>
                                            <input type="integer" id="amount" name="amount" value="" data-rule-required="true" class="form-control"maxlength="14">
                                        </div>
                                    </div>                                
                                </div>
                                <div class="form-actions">
                                    <button type="submit" id="btn_add_front_page" class="fcbtn btn btn-danger btn-1g"> Add</button>
                                    <a href="javascript:void(0);" class="btn btn-primary add-page-back-btn">Back</a>
                                </div>                                	                                    
                            </form>                                            
                        </div>
                        <div class="left-menu-black-bg"></div>
                         <div class="add-form-section-main edit-form-close-btn">
                            <form action="" id="frm_blogs_page" name="frm_blogs_page" class="cmxform" method="POST">
                                {{csrf_field()}}
                                <div class="form-head-section">
                                    Add Team <span class="add-form-close-btn"><i class="ti-close"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="scroll-main-section">
                                    <div class="form-group">
                                        <label class="control-label" for="user_id">Select Voter<i class="red">*</i></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="ti-user"></i></span>
                                            <select name="user_id" data-rule-required="true" id="user_id"  class="form-control ">
                                                <option value="" >Select Voter </option>
                                                @if(isset($arr_voter_team) && count($arr_voter_team)>0)
                                                    @foreach($arr_voter_team as $users)
                                                    <option value="">{{$users['first_name'] or ''}}  {{$users['last_name'] or ''}}@if($users['recieved_count']!==0)<b style="color: red">{{'('.$users['recieved_count'].')'}}</b>@endif</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <input type="hidden" name="remaining_balance" value="">	
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Enter Amount<i style="color:red;">*</i></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="ti-user"></i></span>
                                            <input type="integer" id="amount" name="amount" value="" data-rule-required="true" class="form-control"maxlength="14">
                                        </div>
                                    </div>   
                                </div>                             
                                <div class="form-actions">
                                     <button type="submit" id="btn_add_front_page" class="fcbtn btn btn-danger btn-1g">Add</button>
                                    <a href="javascript:void(0);" class="btn btn-primary add-page-back-btn">Back</a>
                                </div>	                           
                     / </form> 
                       </div>                       
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<!--body wrapper end-->

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

<script>
    $(function() {
        $( "#datepicker" ).datepicker({
            dateFormat : 'yy/mm/dd',
            changeMonth : true,
            changeYear : true,
            yearRange: '-100y:c+nn',
            maxDate: '365d',
            closeOnDateSelect : true,
            scrollMonth : false,
            scrollInput : false,
            
        });
           $( "#datepicker1" ).datepicker({
            dateFormat : 'yy/mm/dd',
            changeMonth : true,
            changeYear : true,
            yearRange: '-100y:c+nn',
            maxDate: '365d',
            closeOnDateSelect : true,
            scrollMonth : false,
            scrollInput : false,
            
        });
    });
 </script>
<script type="text/javascript">

$(document).ready(function() {

    /*
    * Insert a 'details' column to the table
    */
    var nCloneTh = document.createElement( 'th' );
    var nCloneTd = document.createElement( 'td' );
    detail_open_img_path = "{{url('/')}}/assets/admin_assets/images/details_open.png";
    detail_close_img_path = "{{url('/')}}/assets/admin_assets/images/details_close.png";
    nCloneTd.innerHTML = '<img src="'+detail_open_img_path+'">';
    collapse = '<img src="'+detail_open_img_path+'">';
    nCloneTd.className = "center";


   /* var oTable = $('#hidden-table-info').DataTable({
        processing: false,
        serverSide: true,
        searchDelay: 350,
        autoWidth: !1,
        bFilter: !1,
*/
       var oTable = $('#myTable').DataTable({

        ajax: {
            url: "{{ $module_url_path}}/load_data",
            data: function(d) {
                        d['column_filter[start_date]']        = $("input[name='start_date']").val();
                        d['column_filter[end_date]']          = $("input[name='end_date']").val();
                        d['column_filter[voter_name]']        = $("input[name='voter_name']").val();
                        d['column_filter[full_name]']         = $("input[name='full_name']").val();
                        d['column_filter[amount]']            = $("input[name='amount']").val();
                        d['column_filter[d_date]']            = $("input[name='d_date']").val();
                        //d['column_filter[q_status]']          = $("#status option:selected" ).val();
                        
            }
        },
        columns: [
            /*{
                render : function(data, type, row, meta) 
                {
                    return collapse
          },"orderable": false, "searchable":false
            },*/
            {
                render : function(data, type, row, meta)
                {
                    return '<div class="check-box"><input type="checkbox" class="filled-in case" name="checked_record[]" d="mult_change_'+row.id+'" value="'+row.id+'" /><label for="mult_change_'+row.id+'></label></div>';

                },"orderable": false, "searchable":false
            },
             
                {data : 'voter_name',"orderable":false,"searchable":true,name:'voter_name'},
              
                    {data : 'full_name',"orderable":false,"searchable":true,name:'full_name'},
          
                {data : 'amount',"orderable":false,"searchable":true,name:'amount'},
                {data : 'd_date',"orderable":false,"searchable":true,name:'d_date'},
                //{data : 'webadmin_id',"orderable":false,"searchable":true,name:'webadmin_id'},


            // {
            //     render : function(data, type, row, meta) 
            //     {
            //         return row.build_status_btn;
            //     },
            //     "orderable": false, "searchable":false
            // },
              
            {
                render : function(data, type, row, meta) 
                {
                    return row.built_action_button;
                },
                "orderable": false, "searchable":false

            }
        ],
        "aoColumnDefs": [
            { "bSortable": false, "aTargets": [ 1 ] }
        ],
        "aaSorting": [[2, 'asc']],
    });

    $('.dataTables_filter input,.dataTables_length select').addClass('form-control');


    $('input.column_filter, select.column_filter').on( 'keyup change', function (){
        filterData();
    });

    function filterData(){
        //oTable.ajax.reload();
        oTable.draw();
    }

   ;

    function fnFormatDetails ( oTable, nTr )
    {
        var aData = oTable.fnGetData( nTr );
        var sOut = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">';
        sOut += '<tr><td>Rendering engine:</td><td>'+aData[1]+' '+aData[4]+'</td></tr>';
        sOut += '<tr><td>Link to source:</td><td>Could provide a link here</td></tr>';
        sOut += '<tr><td>Extra info:</td><td>And any further details here (images etc)</td></tr>';
        sOut += '</table>';

        return sOut;
    }
});
   
</script>

@endsection


