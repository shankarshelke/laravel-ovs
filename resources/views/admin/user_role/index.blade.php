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
                <div class="panel-body" style="position: relative">
                    @include('admin.layout._operation_status')
                    <div class="table-action-buttons-top">
                        <a href="{{ url($module_url_path) }}" class="btn btn-default btn-rounded show-tooltip" title="refresh" >{{ trans('user_role.refresh') }}</a>
                        @if(get_admin_access('user_role','create'))
                        <a href="javascript:void(0)" class="btn btn-default btn-rounded show-tooltip add-form-btn-section" title="Add New">{{ trans('user_role.add') }}</a>
                        @endif
                        @if(get_admin_access('user_role','approve'))
                        <a href="javascript:void(0)" class="btn btn-default btn-rounded show-tooltip" title="Deactivate Multiple" onclick="check_multi_action('frm_manage','deactivate')">{{ trans('user_role.deactivate') }}</a>                        
                        <a href="javascript:void(0)" class="btn btn-default btn-rounded show-tooltip" title="Activate Multiple" onclick="check_multi_action('frm_manage','activate')">{{ trans('user_role.activate') }}</a>
                        @endif
                        @if(get_admin_access('user_role','delete'))
                        <a href="javascript:void(0)" class="btn btn-default btn-rounded show-tooltip" title="Delete Multiple" onclick="check_multi_action('frm_manage','delete')">{{ trans('user_role.delete') }}</a>
                        @endif
                    </div>           
                    <div class="adv-table">
                        <div id="hidden-table-info_wrapper" class="dataTables_wrapper form-inline table-responsive" role="grid">
                            <form name="frm_manage" id="frm_manage" method="post" class="form-horizontal" action="{{url($module_url_path)}}/multi_action">
                                    {{ csrf_field() }}
                                <input type="hidden" name="multi_action" value="" />
                                <table class="display table table-bordered dataTable" id="hidden-table-info" id="tbl_activitylog_listing">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" class="filled-in" name="selectall" id="select_all" onchange="chk_all(this)" />
                                                <label for="selectall"></label>
                                            </th>
                                            <th>{{ trans('user_role.role') }}</th>
                                            <th>{{ trans('user_role.description') }}</th>
                                            <th>{{ trans('user_role.status') }}</th>
                                            <th style="width: 50px;">{{ trans('user_role.action') }}</th>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                    <div class="add-form-section-main section-add-form">
                        <form action="{{$module_url_path}}/store" id="frm_blogs_page" name="frm_blogs_page" class="cmxform" method="post">
                            {{csrf_field()}}
                            <div class="form-head-section">
                               {{ trans('user_role.add user role') }} <span class="add-form-close-btn"><i class="ti-close"></i></span>
                                <div class="clearfix"></div>
                            </div>
                            <div class="scroll-main-section">
                                <div class="form-group">
                                    <label class="control-label">{{ trans('user_role.role') }}.<i style="color:red;">*</i></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-user"></i></span>
                                        <input type="text" id="role" name="role"  data-rule-required="true" 
                                         class="form-control" >
                                        <span class="error" style="color: red;">{{ $errors->first('role') }} </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">{{ trans('user_role.description') }}<i style="color:red;">*</i></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-user"></i></span>
                                        <input type="text" id="description"  name="description"  data-rule-required="true"  class="form-control "  >
                                        <span class="error" style="color: red;">{{ $errors->first('description') }} </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" id="btn_add_front_page" class="fcbtn btn btn-danger btn-1g"> {{ trans('user_role.submit') }}</button>
                                <a href="javascript:void(0);" class="btn btn-primary add-page-back-btn">{{ trans('user_role.back') }}</a>
                            </div>						
                        </form>                        
                    </div>
                    <div class="left-menu-black-bg"></div>
                    <div class="add-form-section-main edit-form-close-btn">
                        <form action="{{url($module_url_path)}}/update" id="frm_edit" name="frm_edit" method="post"   onsubmit='addLoader()';enctype="multipart/form-data" onsubmit='addLoader()';>
                            {{csrf_field()}}
                              <input type="hidden" name="enc_id" id="enc_id" value="">                      
                            <div class="form-head-section">
                                {{ trans('user_role.edit user role') }}<span class="add-form-close-btn"><i class="ti-close"></i></span>
                                <div class="clearfix"></div>
                            </div>
                            <div class="scroll-main-section">
                                <div class="form-group">
                                    <label class="control-label" for="role">{{ trans('user_role.role') }}<i class="red">*</i></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-user"></i></span>
                                        <input type="text" id="edit_role"  name="role"  data-rule-required="true"  class="form-control " value="" >
                                        {{-- <span class="error" style="color: red;">{{ $errors->first('role') }} </span> --}}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="description">{{ trans('user_role.description') }}<i class="red">*</i></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-user"></i></span>
                                        <input type="text" id="edit_description"  name="description"  data-rule-required="true"  class="form-control " value="">
                                     {{-- <span class="error">{{ $errors->first('description') }} </span> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" id="proceed_edit" class="fcbtn btn btn-danger btn-1g"> {{ trans('user_role.update') }}</button>
                                <a href="javascript:void(0);" class="btn btn-primary add-page-back-btn">{{ trans('user_role.back') }}</a>
<!--                                <button class="btn btn-primary" type="submit"  id="btn_add_front_page">Create</button>-->
                            </div>
<!--
                            <div class="form-group text-right">
                                <div class="col-lg-8">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
-->
                        </form>
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
          $("#role").val("");
        $("#description").val("");
        $("#edit_role").val("");
        $("#edit_description").val("");
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



    var oTable = $('#hidden-table-info').DataTable({
        processing: false,
        serverSide: true,
        searchDelay: 350,
        autoWidth: !1,
        bFilter: !1,
        ajax: {
            url: "{{ $module_url_path}}/load_data",
            data: function(d) {
                        d['column_filter[role]']          = $("input[name='role']").val();
                        d['column_filter[description]']   = $("input[name='description']").val();
                        d['column_filter[status]']        = $( "#status option:selected" ).val()
              
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
                     return '<div class="check-box sorting_disabled"><input type="checkbox" class="filled-in case" name="checked_record[]" d="mult_change_'+row.id+'" value="'+row.id+'" /><label for="mult_change_'+row.id+'></label></div>';
                },"orderable": false, "searchable":false
            },
                {data : 'role',"orderable":false,"searchable":true,name:'role'},
                {data : 'description',"orderable":false,"searchable":true,name:'description'},
                
                // {data : 'created_at',"orderable":true,"searchable":true,name:'created_at'},

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

    /* Add event listener for opening and closing details
    * Note that the indicator for showing which row is open is not controlled by DataTables,
    * rather it is done here
    */

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
                       // console.log(resp.data.enc_id);
                        $('#edit_role').val(resp.data.role);
                        $('#edit_description').val(resp.data.description);
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
<script src="{{ url('/') }}/assets/admin_assets/js/jQuery.style.switcher.js"></script>

@endsection


