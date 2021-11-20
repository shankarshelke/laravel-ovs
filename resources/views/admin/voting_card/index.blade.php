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
                        <a href="#myModal" class="btn btn-default btn-rounded show-tooltip" title="Notification" onclick="check_empty()">Notification</a>
                        @if(get_admin_access('voting_card','create'))
                        <a href="{{url('/')}}/admin/voting_card/create" class="btn btn-default btn-rounded show-tooltip" title="Create">Add</a>
                        @endif
                        @if(get_admin_access('voting_card','approve'))
                        <a href="javascript:void(0)" class="btn btn-default btn-rounded show-tooltip" title="Deactivate Multiple" onclick="check_multi_action('frm_manage','deactivate')">Deactivate</a>
                        @endif
                        @if(get_admin_access('voting_card','approve'))
                        <a href="javascript:void(0)" class="btn btn-default btn-rounded show-tooltip" title="Activate Multiple" onclick="check_multi_action('frm_manage','activate')">Activate</a>
                        @endif
                        @if(get_admin_access('voting_card','delete'))    
                        <a href="javascript:void(0)" class="btn btn-default btn-rounded show-tooltip" title="Delete Multiple" onclick="
                            check_multi_action('frm_manage','delete')">Delete</a>
                        @endif
                    </div>
                    <div class="adv-table">
                        <div id="hidden-table-info_wrapper" class="dataTables_wrapper form-inline table-responsive" role="grid">
                            <form name="frm_manage" id="frm_manage" method="POST" class="form-horizontal" action="{{url($module_url_path)}}/multi_action">
                                    {{ csrf_field() }}
<!--                                {{-- <input type="hidden" name="multi_action" value="" /> --}}-->
                                <!--<table class="display table table-bordered dataTable" id="hidden-table-info" id="tbl_activitylog_listing">-->
<!--                                {{-- <table class="display table table-bordered dataTable" id="hidden-table-info" id="tbl_activitylog_listing">  --}}-->
                                    <input type="hidden" name="multi_action" value="" />                                
                                <table class="display table table-bordered dataTable" id="myTable" id="tbl_activitylog_listing"> 
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" class="filled-in" name="selectall" id="select_all" onchange="chk_all(this)" /><label for="selectall"></label>
                                            </th>
                                            <th>Full Name</th>                                        
                                            {{-- <th>Aadhar ID</th> --}}
                                            <th>Address</th>
                                            {{-- <th>City</th>     --}}
                                            {{-- <th>Face color</th>                                             --}}
                                            <th>Status
                                            <!--<select class="search-block-new-table column_filter form-control" id="status" name="q_status"style="width: 70px;padding-left: 3px;padding-right: 1px;">
                                                    <option value="">Select</option>
                                                    <option value="1">Active</option>
                                                    <option value="0">InActive</option>
                                                </select>-->
                                            </th>
                                            <th>Surety
                                            <!--<select class="search-block-new-table column_filter form-control" id="voting_surety" name="q_voting_surety"style="width: 70px;padding-left: 5px;padding-right: 5px;">
                                                    <option value="">Select</option>
                                                    <option value="0">Full surety</option>
                                                    <option value="1">Half surety</option>
                                                    <option value="2">Not sured</option>
                                                </select>-->
                                            </th>
                                                {{-- <th>Is Verified</th> --}}
                                            <th style="width: 50px">Action</th>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                                <h4 class="modal-title">Send Message</h4>
                                            </div>
                                            <div class="modal-body">
                                                <form role="form">
                                                    <div class="form-group">
                                                    <label for="exampleInputEmail1" class="col-lg-2 col-sm-2 control-label">Enter Message</label>
                                                    <div class="col-sm-10">
                                                     <textarea rows="6" cols="45" name="message" class="form-control"></textarea>
                                                    </div>
                                                    <div class="col-lg-offset-2 col-lg-10"><br>
                                                    <button type="button" class="btn btn-primary send_newsletter_btn">Submit</button>
                                                    </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="left-menu-black-bg"></div>
                        <div class="add-form-section-main edit-form-close-btn">
                           <form action="{{$module_url_path}}/add_voting_card" id="frm_create_page" name="frm_create_page" class="form-horizontal cmxform" method="post" enctype="multipart/form-data">
                                {{csrf_field()}}
                                <div class="form-head-section">
                                    <input type="hidden" id="user_id" name="user_id">
                                    Add Voter Id<span class="add-form-close-btn"><i class="ti-close"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="scroll-main-section">
                                    <div class="form-group">
                                    <label class="col-sm-7 col-sm-2 control-label">Voter ID<i style="color:red;">*</i></label>
                                        <div class="col-sm-8">
                                            <input type="text" id="voter_id" name="voter_id" value="{{old('voter_id')}}" data-rule-required="true"  
                                                class="form-control" maxlength="14">
                                            <span class="error" style="color:red;">{{ $errors->first('voter_id') }} </span>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="form-actions">
                                    <button type="submit" id="btn_add_front_page" class="fcbtn btn btn-danger btn-1g"> Submit</button>
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

$(document).ready(function() {

    $('body').on('click','#add_voting_card',function(){
        var user_id = $(this).attr('data-id');
        $('#user_id').val(user_id);
    });

    $('.send_newsletter_btn').click(function(){
        $('#frm_manage').attr('action', "{{ url($module_url_path) }}/send_newsletter");
        $('#frm_manage').submit();
    });
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

    /*$('#hidden-table-info thead tr').each( function () {
        this.insertBefore( nCloneTh, this.childNodes[0] );
    });*/

    /*$('#hidden-table-info tbody tr').each( function () {
        this.insertBefore( nCloneTd.cloneNode( true ), this.childNodes[0] );
    });*/

    /*
    * Initialse DataTables, with no sorting on the 'details' column
    */

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
                d['column_filter[q_full_name]']    = $("input[name='q_full_name']").val();
               // d['column_filter[q_last_name]']    = $("input[name='q_last_name']").val();
               // d['column_filter[q_aadhar_id]']     = $("input[name='q_aadhar_id']").val();
                d['column_filter[q_address]']      = $("input[name='q_address']").val();
                //d['column_filter[q_city]']         = $("input[name='q_city']").val();
               // d['column_filter[q_face_color]']   = $("input[name='q_face_color']").val();
                d['column_filter[q_status]']       = $( "#status option:selected" ).val();
                d['column_filter[q_voting_surety]']= $( "#voting_surety option:selected" ).val();
             }
        },
        columns: [
            {
                render : function(data, type, row, meta)
                {
                    return '<div class="check-box"><input type="checkbox" class="filled-in case" name="checked_record[]" d="mult_change_'+row.id+'" value="'+row.id+'" /><label for="mult_change_'+row.id+'></label></div>';

                },"orderable": false, "searchable":false
            },
            {data : 'full_name',"orderable":false,"searchable":true,name:'full_name'},
           // {data : 'last_name',"orderable":false,"searchable":true,name:'last_name'},
           /* {data : 'email',"orderable":false,"searchable":true,name:'email'},*/
           // {data : 'aadhar_id',"orderable":false,"searchable":true,name:'aadhar_id'},
            {data : 'address',"orderable":false,"searchable":true,name:'address'},
           // {data : 'city_name',"orderable":false,"searchable":true,name:'city_name'},
            //{data : 'face_color',"orderable":false,"searchable":true,name:'face_color'},

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
                    return row.build_voting_surety_btn;
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

 

    $('input.column_filter, select.column_filter').on( 'keyup change', function (){
        filterData();
    });

    function filterData(){
        oTable.draw();
    }

    // $('[data-type="adhaar-number"]').keyup(function() {
    //   var value = $(this).val();
    //   value = value.replace(/\D/g, "").split(/(?:([\d]{4}))/g).filter(s => s.length > 0).join("-");
    //   $(this).val(value);
    // });

    // $('[data-type="adhaar-number"]').on("change, blur", function() {
    //   var value = $(this).val();
    //   var maxLength = $(this).attr("maxLength");
    //   if (value.length != maxLength) {
    //     $(this).addClass("highlight-error");
    //   } else {
    //     $(this).removeClass("highlight-error");
    //   }
    // });

});
   
</script>   
@endsection