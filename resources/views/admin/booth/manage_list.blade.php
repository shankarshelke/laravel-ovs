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
                        @if(get_admin_access('voting_booth','create'))
                        <a href="javascript:void(0);" class="btn btn-default btn-rounded show-tooltip add-form-btn-section" title="Add List">Add</a>
                        @endif
                        @if(get_admin_access('voting_booth','approve'))
                        <a href="javascript:void(0)" class="btn btn-default btn-rounded show-tooltip" title="Deactivate Multiple" onclick="check_multi_action('frm_manage','deactivate')">Deactivate</a>
                        <a href="javascript:void(0)" class="btn btn-default btn-rounded show-tooltip" title="Activate Multiple" onclick="check_multi_action('frm_manage','activate')">Activate</a>
                        @endif
                        @if(get_admin_access('voting_booth','delete'))
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
                                                {{-- <div class="icheck ">
                                                    <div class="square-blue single-row">
                                                        <div class="checkbox">
                                                            <input type="checkbox" class="filled-in" name="selectall" id="select_all" onchange="chk_all(this)" /><label for="selectall"></label>
                                                        </div>
                                                    </div>
                                                </div> --}}
                                                <input type="checkbox" class="filled-in" name="selectall" id="select_all" onchange="chk_all(this)" /><label for="selectall"></label>
                                            </th>
                                            <th >Booth 
                                                {{-- <input type="text" name="booth" placeholder="Search" class="search-block-new-table form-control column_filter"> --}}
                                            </th>
                                            <th >List No
                                                {{-- <input type="text" name="list_no" placeholder="Search" class="search-block-new-table form-control column_filter"style="width: 106px;"> --}}
                                            </th>
                                            <th>List Name
                                                {{-- <input type="text" name="list_name" placeholder="Search" class="search-block-new-table form-control column_filter"></th>
                                     --}}
                                            {{-- <th>Added On</th> --}}

                                            <th>Status
                                                {{-- <select class="search-block-new-table column_filter form-control" id="status" name="status">
                                                    <option value="">Select</option>
                                                    <option value="1">Active</option>
                                                    <option value="0">InActive</option>
                                                </select> --}}
                                            </th>
                                            {{-- <th>Voting Surety
                                                <select class="search-block-new-table column_filter form-control" id="q_voting_surety" name="q_voting_surety">
                                                    <option value="">Select</option>
                                                    <option value="0">Full surety</option>
                                                    <option value="1">Half surety</option>
                                                    <option value="2">Not surety</option>
                                                </select>
                                            </th> --}}
                                                {{-- <th>Is Verified</th> --}}
                                            <th style="width: 50px;">Action</th>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </form>
                        </div>
                        <div class="add-form-section-main section-add-form">
                             <form action="{{$module_url_path}}/store_list" id="frm_blogs_page" name="frm_blogs_page" class="cmxform" method="post">
                                {{csrf_field()}}
                                <div class="form-head-section">
                                    Add List <span class="add-form-close-btn"><i class="ti-close"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="scroll-main-section">
                                    <div class="form-group">
                            <label class=" control-label">Ward<i style="color:red;">*</i></label>
                            <div class="input-group">
                             <span class="input-group-addon"><i class="ti-menu-alt"></i></span> 
                                <select name="ward" data-rule-required="true" id="ward"  class="form-control ">
                                        <option value="" >Select ward </option>
                                        @if(isset($arr_ward) && count($arr_ward)>0)
                                        @foreach($arr_ward as $wards)
                                            <option value="{{$wards['id']}}" @if(old('ward') == $wards['id']) selected="selected" @endif>{{$wards['ward_name'] or ''}}</option>
                                        @endforeach
                                        @endif
                                </select>
                                <span class="error" style="color: red;">{{ $errors->first('ward') }} </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class=" control-label">Booth<i style="color:red;">*</i></label>
                            <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-menu-alt"></i></span> 
                                <select name="booth" data-rule-required="true" id="booth" class="form-control">
                                    <option value="">Select Booth </option>
                                </select>
                                <span class="error" style="color: red;">{{ $errors->first('booth') }} </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class=" control-label">List no<i style="color:red;">*</i></label>
                            <div class="input-group">
                            <span class="input-group-addon"><i class="ti-menu-alt"></i></span> 
                                <input type="text" id="list_no"  name="list_no"  data-rule-required="true" value="{{old('list_no')}}" class="form-control " maxlength="10" data-rule="true">
                                <span class="error" style="color: red;">{{ $errors->first('list_no') }} </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class=" control-label">List Name<i style="color:red;">*</i></label>
                            <div class="input-group">
                             <span class="input-group-addon"><i class="ti-menu-alt"></i></span> 
                                <input id="list_name"  name="list_name" value="{{old('list_name')}}"  data-rule-required="true"  class="form-control"  maxlength="100" >

                                <span class="error" style="color: red;">{{ $errors->first('list_name') }} </span>
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
                            <form action="{{url($module_url_path)}}/update_list/{{ $id or '' }}" id="frm_edit" name="frm_edit" method="post"   onsubmit='addLoader()';enctype="multipart/form-data" onsubmit='addLoader()';>
                               {{csrf_field()}}
                              <input type="hidden" name="enc_id" id="enc_id" value="">
                                <div class="form-head-section">
                                    Edit List <span class="add-form-close-btn"><i class="ti-close"></i></span>
                                    <div class="clearfix"></div>
                                </div>                              
                                <div class="scroll-main-section">
                                    <div class="form-group">
                                    <label class=" control-label">Booth Detail<i style="color:red;">*</i></label>
                                        <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-menu-alt"></i></span> 
                                            <select name="booth" readonly="" data-rule-required="true" id="selected_booth_id" class="form-control">
                                                <option value="">Select Booth </option>
                                                @if(isset($arr_booth) && count($arr_booth)>0)
                                                @foreach($arr_booth as $booth)
                                                <option value="{{$booth['id']}}" @if(old('booth') == $wards['id']) selected="selected" @endif>{{$booth['booth_name'] or ''}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        <span class="error">{{ $errors->first('wards') }} </span>
                                        </div>
                                    </div>
                        
                                    <div class="form-group">
                                        <label class="control-label">List No<i style="color:red;">*</i></label>
                                         <div class="input-group">
                                            <span class="input-group-addon"><i class="ti-menu-alt"></i></span> 
                                            <input type="text" id="edit_list_no"  name="list_no"  data-rule-required="true"  class="form-control "value="{{ $arr_data['list_no'] or 'NA' }}">
                                            <span class="error">{{ $errors->first('list_no') }} </span>
                                        </div>
                                    </div>
                        
                                    <div class="form-group">
                                        <label class="control-label">List Name<i style="color:red;">*</i></label>
                                         <div class="input-group">
                                            <span class="input-group-addon"><i class="ti-menu-alt"></i></span> 
                                            <input type="text" id="edit_list_name"  name="list_name"  data-rule-required="true"  class="form-control "value="{{ $arr_data['list_name'] or 'NA' }}">
                                            <span class="error">{{ $errors->first('list_name') }} </span>
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
        $("#ward").val("");
        $("#booth").val("");
        $("#list_no").val("");
        $("#list_name").val("");
        $("#edit_booth_no").val("");
        $("#edit_list_no").val("");
         $("#edit_list_name").val("");
    }); 
</script>
<script>
    $('body').on('click','.edit_button',function(){        
        $("body").addClass("edit-form-open");        
    });
</script>

<script type="text/javascript">

$(document).ready(function() {

  $('#ward').change(function()
        {
            var ward_id = $('#ward').val();
            if(ward_id!='')
            { 
                var url = '{{$module_url_path}}/get_booths';
                var csrf_token      = '{{csrf_token()}}';
                $.ajax({
                    type:'POST',
                    url: url,
                    data:{ward_id:ward_id,_token:'{{csrf_token()}}'},
                    success:function(resp)
                    {
                        $('#booth').html(resp);
                    }
                });
            }
        });
    




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

        var oTable = $('#myTable').DataTable({
        ajax:{
        url: "{{ $module_url_path}}/load_listdata",
        data: function(d) {
            d['column_filter[booth]']        = $("input[name='booth']").val()
            d['column_filter[list_no]']      = $("input[name='list_no']").val()
            d['column_filter[list_name]']    = $( "input[name='list_name']" ).val()
            d['column_filter[status]']       = $( "#status option:selected" ).val()
          
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
            {data : 'booth',"orderable":false,"searchable":true,name:'booth'},
            {data : 'list_no',"orderable":false,"searchable":true,name:'list_no'},
           /* {data : 'email',"orderable":false,"searchable":true,name:'email'},*/
         
            {data : 'list_name',"orderable":false,"searchable":true,name:'list_name'},
           

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
                url : '{{ $module_url_path }}/edit_list/'+enc_id,
                type : "GET",
                dataType: 'JSON',
                success:function(resp){

                    if(resp.status=='success')
                    {
                        console.log(resp.data.get_booth_details);
                      //alert(hiii);
                        $('#selected_booth_id').val(resp.data.get_booth_details.id);
                        $('#edit_booth_no').val(resp.data.booth_name);
                        $('#edit_list_no').val(resp.data.list_no);
                        $('#edit_list_name').val(resp.data.list_name);

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


