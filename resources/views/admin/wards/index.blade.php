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
               {{--  <header class="panel-heading">
                    {{$module_title or  '' }}
                </header> --}}
                <div class="panel-body" style="position: relative">
                    @include('admin.layout._operation_status')
                    <div class="table-action-buttons-top">
                        <a href="{{ url($module_url_path) }}" class="btn btn-default btn-rounded show-tooltip" title="Refresh" >Refresh</a>
                         @if(get_admin_access('wards','create'))
                        <a href="javascript:void(0);" class="btn btn-default btn-rounded show-tooltip add-form-btn-section" title="Add New Wards">Add</a>
                        @endif
<!--                        {{-- <a href="{{ url($module_url_path) }}/create" class="btn btn-default btn-rounded show-tooltip" title="Add" >Add</a>   --}}-->
                        @if(get_admin_access('wards','approve'))
                        <a href="javascript:void(0)" class="btn btn-default btn-rounded show-tooltip" title="Deactivate Multiple" onclick="check_multi_action('frm_manage','deactivate')">Deactivate</a>
                        <a href="javascript:void(0)" class="btn btn-default btn-rounded show-tooltip" title="Activate Multiple" onclick="check_multi_action('frm_manage','activate')">Activate</a>
                        @endif
                        @if(get_admin_access('wards','delete'))
                        <a href="javascript:void(0)" class="btn btn-default btn-rounded show-tooltip" title="Delete Multiple" onclick="check_multi_action('frm_manage','delete')">Delete</a>
                        @endif
                    </div>


                    <div class="adv-table">
                        <div id="hidden-table-info_wrapper" class="dataTables_wrapper form-inline table-responsive" role="grid">
                            <form name="frm_manage" id="frm_manage" method="POST" class="form-horizontal" action="{{url($module_url_path)}}/multi_action">
                                    {{ csrf_field() }}
                                <input type="hidden" name="multi_action" value="" />
                                <table class="display table table-bordered dataTable" id="myTable" id="tbl_activitylog_listing">
                                    <thead>
                                        <tr>
                                            <th>                                           
                                                <input type="checkbox" class="filled-in" name="selectall" id="select_all" onchange="chk_all(this)" /><label for="selectall"></label>
                                            </th>
                                            <th style="width: 100px;">Ward No
                                            </th>
                                            <th>Ward Name
                                            </th>
                                            <th>Ward Address

                                            <th  style="width: 100px;">Status
                                            <!--<select class="search-block-new-table column_filter form-control" id="status" name="status" style="width:86px;">
                                                    <option value="">Select</option>
                                                    <option value="1">Active</option>
                                                    <option value="0">InActive</option>
                                                </select>-->
                                            </th>                                               
                                            <th style="width: 50px">Action</th>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </form>
                        </div>
                       <div class="add-form-section-main section-add-form">
                            <form action="{{$module_url_path}}/store" id="frm_blogs_page" name="frm_blogs_page" class="cmxform" method="post">
                                {{csrf_field()}}
                                <div class="form-head-section">
                                    Add Wards <span class="add-form-close-btn"><i class="ti-close"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="scroll-main-section">



                            
                        <div class="form-group">
                            <label class="control-label">Wards No.<i style="color:red;">*</i></label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="ti-user"></i></span>  
                                <input type="text" id="ward_no" name="ward_no"  data-rule-required="true" data-rule-number=”true” 
                                 class="form-control" maxlength="10">
                                <span class="error" style="color: red;">{{ $errors->first('ward_no') }} </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label"> Wards Name<i style="color:red;">*</i></label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="ti-user"></i></span>
                                <input type="text" id="ward_name"  name="ward_name"  data-rule-required="true"  class="form-control " maxlength="40" >
                                <span class="error" style="color: red;">{{ $errors->first('ward_name') }} </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Address<i style="color:red;">*</i></label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icon-location-pin"></i></span>
                                <input id="ward_address"  name="ward_address"  data-rule-required="true"  class="form-control " maxlength="255" rows="6"></input>
                                <span class="error" style="color: red;">{{ $errors->first('ward_address') }} </span>
                            </div>
                        </div>    
                         </div>
                                <div class="form-actions">
                                    <button type="submit" id="btn_add_front_page" class="fcbtn btn btn-danger btn-1g"> Submit</button>
                                    <a href="javascript:void(0);" class="btn btn-primary add-page-back-btn">Back</a>    
                                </div>
                            </form>                   
                        </div>
                        <div class="left-menu-black-bg"></div>
                        <div class="add-form-section-main edit-form-close-btn">
                            <form action="{{url($module_url_path)}}/update" id="frm_edit" name="frm_edit" method="post"   onsubmit='addLoader()';enctype="multipart/form-data" onsubmit='addLoader()';>
                               {{csrf_field()}}
                              <input type="hidden" name="enc_id" id="enc_id" value="">
                                <div class="form-head-section">
                                    Edit Wards <span class="add-form-close-btn"><i class="ti-close"></i></span>
                                    <div class="clearfix"></div>
                                </div>                              
                                <div class="scroll-main-section">

                            <div class="form-group">
                            <label class=" control-label">Wards No.<i style="color:red;">*</i></label>
                           <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-menu-alt"></i></span> 
                                <input type="text" id="edit_ward_no" name="ward_no"  data-rule-required="true" data-rule-number=”true”  class="form-control" value="{{ $arr_data['ward_no'] or 'NA' }}" >
                                <span class="error">{{ $errors->first('ward_no') }} </span>
                            </div>
                        </div>                  
                        <div class="form-group">
                            <label class="control-label"> Wards Name<i style="color:red;">*</i></label>
                            <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-menu-alt"></i></span> 
                                <input type="text" id="edit_ward_name"  name="ward_name"  data-rule-required="true"  class="form-control "value="{{ $arr_data['ward_name'] or 'NA' }}">
                                <span class="error">{{ $errors->first('ward_name') }} </span>
                            </div>
                        </div>                      
                        <div class="form-group">
                            <label class=" control-label">Address<i style="color:red;">*</i></label>
                            <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-menu-alt"></i></span> 
                                <input id="edit_ward_address"  name="ward_address"  data-rule-required="true"  class="form-control" rows="4" value="{{ $arr_data['ward_address'] or 'NA' }}">
                                <span class="error">{{ $errors->first('ward_address') }} </span>
                            </div>
                            
                        </div>
                                    
                                </div>
                                <div class="form-actions">
                                   <button type="submit" id="proceed_edit" class="fcbtn btn btn-danger btn-1g"> Update</button>
                                    <a href="javascript:void(0);" class="btn btn-primary add-page-back-btn">Back</a>    
                                </div>
                            </form>
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
        $("#ward_no").val("");
        $("#ward_name").val("");
        $("#ward_address").val("");
        $("#edit_ward_no").val("");
        $("#edit_ward_name").val("");
        $("#edit_ward_address").val("");



    }); 
</script>
<script>
    $('body').on('click','.edit_button',function(){        
        $("body").addClass("edit-form-open");        
    });
</script>

<script type="text/javascript">

$(document).ready(function() {
     $('#frm_edit').validate();


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
        ajax: {*/
            var oTable = $('#myTable').DataTable({
        ajax: {
            url: "{{ $module_url_path}}/load_data",
            data: function(d) {
                d['column_filter[ward_no]']        = $("input[name='ward_no']").val()
                // d['column_filter[village_name]']   = $("input[name='village_name']").val()
                d['column_filter[ward_name]']      = $("input[name='ward_name']").val()
                d['column_filter[ward_address]']   = $( "input[name='ward_address']" ).val()
                d['column_filter[status]']         = $( "#status option:selected" ).val()
              
            }
        },
        columns: [
            
            {
                render : function(data, type, row, meta)
                {
                    return '<div class="check-box"><input type="checkbox" class="filled-in case" name="checked_record[]" d="mult_change_'+row.id+'" value="'+row.id+'" /><label for="mult_change_'+row.id+'></label></div>';

                },"orderable": false, "searchable":false
            },
            {data : 'ward_no',"orderable":false,"searchable":true,name:'ward_no'},
            // {data : 'village_name',"orderable":false,"searchable":true,name:'village_name'},
            {data : 'ward_name',"orderable":false,"searchable":true,name:'ward_name'},
         
            {data : 'ward_address',"orderable":false,"searchable":true,name:'ward_address'},
           

           /* {data : 'created_at',"orderable":false,"searchable":true,name:'created_at'},*/

            {
                render : function(data, type, row, meta) 
                {
                    return row.build_status_btn;
                },
                "orderable": false, "searchable":false
            },
            
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

    
    $('#hidden-table-info tbody td img').live('click', function () {
        var nTr = $(this).parents('tr')[0];
        oTable = $('#hidden-table-info').dataTable();

        if ( $(this).attr("isOpen") == "true" )
        {
            this.src = detail_open_img_path;
            oTable.fnClose( nTr );
            $(this).attr("isOpen","false");
        }
        else
        {
            this.src = detail_open_img_path;
            oTable.fnOpen( nTr, fnFormatDetails(oTable, nTr), 'details' );
            $(this).attr("isOpen","true");
        }
    });

    $('input.column_filter, select.column_filter').on( 'keyup change', function (){
        filterData();
    });

    function filterData(){
        //oTable.ajax.reload();
        oTable.draw();
    }

   

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
function addLoader() {
            $('#frm_add,#frm_edit').submit(function(event) {
                if($("#frm_add").valid()==true)
                {
                    $("#proceed").html("<b><i class='fa fa-spinner fa-spin'></i></b> Processing...");
                    $("#proceed").attr('disabled', true);
                }
                else if($("#frm_edit").valid()==true)
                {
                    $("#proceed_edit").html("<b><i class='fa fa-spinner fa-spin'></i></b> Processing...");
                    $("#proceed_edit").attr('disabled', true);
                } 
                else
                {
                    event.preventDefault();
                }
            });
        } 
     $('body').on('click','.edit_button',function(){
            var data_id = $(this).attr('data-id');
            var enc_id  = data_id;
 // console.log(data_id);
 //alert("hello");
            $.ajax({
                url : '{{ $module_url_path }}/edit/'+enc_id,
                type : "GET",
                dataType: 'JSON',
                success:function(resp){

                    if(resp.status=='success')
                    {
                        //console.log(resp.data.full_name);
                      //alert(hiii);
                        $('#edit_ward_no').val(resp.data.ward_no);
                        $('#edit_ward_name').val(resp.data.ward_name);
                        $('#edit_ward_address').val(resp.data.ward_address);
                        $('#enc_id').val(resp.data.id);
                        $("body").addClass("edit-form-open");
                        return true;
                // console.log("edit-form-open");
                    }
                    else if(resp.status=='error')
                    {
                        $('#error').html(' This Role already exists');
                        document.getElementById("submit").disabled = true;

                        return false;
                    }
                    else
                    {
                        $('#error').html('');
                        document.getElementById("submit").disabled = false;
                        return true;
                    }
                }
            })
        });
   
</script>
@endsection


