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
                        <a href="{{ url($module_url_path) }}" class="btn btn-default btn-rounded show-tooltip" title="Refresh" >Refresh</a>
                        @if(get_admin_access('finance_team','create'))
                        <a href="javascript:void(0);" class="btn btn-default btn-rounded show-tooltip add-form-btn-section" title="Add New">Add</a>
                        @endif
<!--
                         @if(get_admin_access('money_distribution','create'))
                        <a href="{{ url($module_url_path) }}/create" class="btn btn-default btn-rounded show-tooltip" title="Add" >Add</a>
                        @endif
-->
                         @if(get_admin_access('money_distribution'))
                        <a href="{{ url('/') }}/admin/money_distribution" class="btn btn-default btn-rounded show-tooltip" title="Money Distribution">Money Distribution</a>
                        @endif
                        @if(get_admin_access('finance_team','approve'))
                        <a href="javascript:void(0)" class="btn btn-default btn-rounded show-tooltip" title="Deactivate Multiple" onclick="check_multi_action('frm_manage','deactivate')">Deactivate</a>
                        
                        <a href="javascript:void(0)" class="btn btn-default btn-rounded show-tooltip" title="Activate Multiple" onclick="check_multi_action('frm_manage','activate')">Activate</a>
                        @endif
                        @if(get_admin_access('finance_team','delete'))
                        <a href="javascript:void(0)" class="btn btn-default btn-rounded show-tooltip" title="Delete Multiple" onclick="check_multi_action('frm_manage','delete')">Delete</a>
                        @endif
                    </div>

                    <div class="adv-table">
                        <div id="hidden-table-info_wrapper" class="dataTables_wrapper form-inline table-responsive" role="grid">
                            <form name="frm_manage" id="frm_manage" method="POST" class="form-horizontal" action="{{url($module_url_path)}}/multi_action">
                                {{ csrf_field() }}
                                <input type="hidden" name="multi_action" value="" />                                
                                <table class="display table table-bordered dataTable" id="myTable" id="tbl_activitylog_listing"> 
<!--                                {{--<table id="myTable" class="table table-striped"> --}}-->
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" class="filled-in" name="selectall" id="select_all" onchange="chk_all(this)" /><label for="selectall"></label>
                                            </th>
                                             <th>Finance Member Name
                                                {{-- <input type="text" name="full_name" placeholder="Search" class="search-block-new-table form-control column_filter"style="width: 170px;"> --}}
                                            </th> 
                                            {{-- <th>Village --}}
                                                {{-- <input type="text" name="village" placeholder="Search" class="search-block-new-table form-control column_filter"style="width: 130px;"> --}}
                                            {{-- </th> --}}
                                            {{-- <th>City --}}
                                                {{-- <input type="text" name="city" placeholder="Search" class="search-block-new-table form-control column_filter"style="width: 130px;"> --}}
                                            {{-- </th> --}}
                                            <th>Ward
                                                {{-- <input type="text" name="district" placeholder="Search" class="search-block-new-table form-control column_filter"style="width: 122px;"> --}}
                                            </th>                                    
                                            {{-- <th>Added On</th> --}}
                                            <th>Status
<!--
                                                <select class="search-block-new-table column_filter form-control" id="status" name="status">
                                                    <option value="">Select</option>
                                                    <option value="1">Active</option>
                                                    <option value="0">InActive</option>
                                                </select>
-->
                                            </th>                                               
                                             <th style="width: 50px;">Action</th>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </form>
                        </div>
                        <div class="add-form-section-main section-add-form">
                            <form action="{{$module_url_path}}/store" id="frm_blogs_page" name="frm_blogs_page" class="cmxform" method="post">
                                {{csrf_field()}}
                                <div class="form-head-section">
                                    Add Finance Member <span class="add-form-close-btn"><i class="ti-close"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="scroll-main-section">

                                <div class="form-group">
                                    <label class="control-label">Ward<i style="color:red;">*</i></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-user"></i></span> 
                                        <select name="ward" data-rule-required="true" id="ward" class="form-control">
                                            <option value="">Select Ward </option>
                                            @if(isset($arr_wards) && count($arr_wards)>0)
                                            @foreach($arr_wards as $wards)
                                                <option value="{{$wards['id']}}" @if(old('ward') == $wards['id']) selected="selected" @endif>{{$wards['ward_name'] or ''}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span class="error" style="color: red;">{{ $errors->first('ward') }} </span>
                                    </div>
                                </div>

                                    <div class="form-group">
                                        <label class="control-label">Select Distributor<i style="color:red;">*</i></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="ti-user"></i></span>
                                            <select name="sub_admin_id" data-rule-required="true" id="sub_admin_id"  class="form-control ">
                                                <option value="{{old('sub_admin_id')}}" >Select Member Name </option>
                                                @if(isset($arr_teams) && count($arr_teams)>0)
                                                    @foreach($arr_teams as $webadmin)
                                                    <option value="{{$webadmin['id']}}">{{$webadmin['first_name'] or ''}} {{$webadmin['last_name'] or ''}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
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
                                    Edit Finance Member <span class="add-form-close-btn"><i class="ti-close"></i></span>
                                    <div class="clearfix"></div>
                                </div>                              
                                <div class="scroll-main-section">
                                  
                                    <div class="form-group">
                                    <label class="control-label">Ward<i style="color:red;">*</i></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-menu-alt"></i></span> 
                                        <select name="ward" data-rule-required="true" id="edit_ward" class="form-control">
                                            <option value="">Select Ward </option>
                                            @if(isset($arr_wards) && count($arr_wards)>0)
                                            @foreach($arr_wards as $wards)
                                            <option value="{{$wards['id']}}" @if(old('ward') == $wards['id']) selected="selected" @endif>{{$wards['ward_name'] or ''}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span class="error">{{ $errors->first('ward') }} </span>
                                    </div>
                                </div>

                                    <div class="form-group">
                                        <label class="control-label">Select Distributor<i style="color:red;">*</i></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="ti-user"></i></span>
                                            <select name="sub_admin_id" data-rule-required="true" id="edit_sub_admin_id"  class="form-control ">
                                                <option value="{{old('sub_admin_id')}}" >Select Member Name </option>
                                                @if(isset($arr_teams) && count($arr_teams)>0)
                                                    @foreach($arr_teams as $webadmin)
                                                    <option value="{{$webadmin['id']}}"@if(old('sub_admin_id')==$webadmin['id'])selected="selected"@endif>{{$webadmin['first_name'] or ''}} {{$webadmin['last_name'] or ''}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <button type="submit" id="proceed_edit" class="fcbtn btn btn-danger btn-1g"> Update</button>
                                        <a href="javascript:void(0);" class="btn btn-primary add-page-back-btn">Back</a>
        <!--                                <button class="btn btn-primary" type="submit"  id="btn_add_front_page">Create</button>-->
                                    </div>
    <!--
                                    <div class="form-group">
                                        <div class="col-sm-4 text-right">
                                            <a href="{{ $module_url_path or 'NA' }}" class="btn btn-primary">Back</a>
                                            <button class="btn btn-primary" type="submit"  >Add</button>
                                        </div>
                                    </div>
    -->
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
        $("#full_name").val("");
        $("#ward").val("");
        $("#edit_ward").val("");
        $("#edit_sub_admin_id").val("");
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
 

    /*var oTable = $('#hidden-table-info').DataTable({
        processing: false,
        serverSide: true,
        searchDelay: 350,
        autoWidth: !1,
        bFilter: !1,*/
        var oTable = $('#myTable').DataTable({
        ajax: {
            url: "{{ $module_url_path}}/load_data",
            data: function(d) {
              //  d['column_filter[distributor_no]']        = $("input[name='distributor_no']").val()
                d['column_filter[full_name]']             = $("input[name='full_name']").val()
                //d['column_filter[village]']               = $( "input[name='village']" ).val()
                //d['column_filter[city]']                  = $( "input[name='city']" ).val()
                d['column_filter[ward]']                  = $( "input[name='ward']" ).val()
                d['column_filter[status]']                = $( "#status option:selected" ).val()
              
            }
        },
        columns: [
            
            {
                render : function(data, type, row, meta)
                {
                    return '<div class="check-box"><input type="checkbox" class="filled-in case" name="checked_record[]" d="mult_change_'+row.id+'" value="'+row.id+'" /><label for="mult_change_'+row.id+'></label></div>';

                },"orderable": false, "searchable":false
            },
            //{data : 'distributor_no',"orderable":false,"searchable":true,name:'distributor_no'},
            {data : 'full_name',"orderable":false,"searchable":true,name:'full_name'},
            {data : 'ward',"orderable":false,"searchable":true,name:'ward'},
            //{data : 'city',"orderable":false,"searchable":true,name:'city'},
          //  {data : 'district',"orderable":false,"searchable":true,name:'district'},
            

         
           
           

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
                        $('#edit_ward').val(resp.data.ward);
                        //$('#select_subadmin_id').val(resp.data.full_name);
                         $('#edit_sub_admin_id').val(resp.data.get_admin_details.id);
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


