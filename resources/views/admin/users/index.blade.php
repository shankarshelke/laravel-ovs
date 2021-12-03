
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
<!--                         <a href="{{ url($module_url_path) }}" class="btn btn-default btn-rounded show-tooltip" title="Refresh" >Refresh</a>
                        <a href="#myModal" class="btn btn-default btn-rounded show-tooltip" title="Notification" onclick="check_empty()">Notification</a> -->
                        {{--  <a href="#myModal" class="btn btn-default btn-rounded show-tooltip" title="Notification" onclick="check_empty()">Send Msg</a> --}}
                        @if(get_admin_access('voters','create'))
                        <a class="loadPage" data-url="{{ url($module_url_path) }}/create">Add</a>
                        @endif
                        @if(get_admin_access('voters','create'))
                        <a href="{{ url($module_url_path) }}/import">Import</a>
                        @endif   
                        @if(get_admin_access('voters','create'))
                        <a href="{{ $module_url_path }}/export_voters">Export</a>
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
                    </div>
                    <div class="adv-table">
                        <div id="hidden-table-info_wrapper" class="dataTables_wrapper form-inline table-responsive" role="grid">
                            <form name="frm_manage" id="frm_manage" method="POST" class="form-horizontal" action="{{url($module_url_path)}}/multi_action">
                                    {{ csrf_field() }}

                                <input type="hidden" name="multi_action" value="" />
                                <!--<table class="display table table-bordered dataTable" id="hidden-table-info" id="tbl_activitylog_listing">-->
                                <table class="display table table-bordered dataTable myTable" id="myTable" id="tbl_activitylog_listing">

                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" class="filled-in" name="selectall" id="select_all" onchange="chk_all(this)" /><label for="selectall"></label>
                                            </th>
                                            <th>#</th>
                                            <th>Family Id</th>
                                            <th>Full Name</th>                                        
                                            <th>Voter ID</th>
                                           <th>Contact No.</th>
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
                                            <select class="search-block-new-table column_filter form-control" id="voting_surety" name="q_voting_surety">
                                                    <option value="">Select</option>
                                                    <option value="0">Full surety</option>
                                                    <option value="1">Half surety</option>
                                                    <option value="2">Not sured</option>
                                                </select>
                                            </th>
                                                {{-- <th>Is Verified</th> --}}
                                            <th style="width: 50px">Action</th>
                                        </tr>


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
                        <div class="add-form-section-main section-add-form">
                            <form id="frm_create_page" name="frm_create_page" class="form-horizontal cmxform" method="post" enctype="multipart/form-data">
                                {{csrf_field()}}
                                <div class="form-head-section">
                                    Select Form <span class="add-form-close-btn"><i class="ti-close"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="scroll-main-section">
                                    <a href="{{url('/')}}/admin/voters/create">Voter Registration</a><br>
                                    <a href="{{url('/')}}/admin/voters/aadhar">Aadhar Card</a><br>
                                    <a href="{{url('/')}}/admin/voters/voter">Voter Id</a><br>
                                    <a href="{{url('/')}}/admin/voters/aadhar_voter">Voter Id & Aadhar Card</a> 
                                </div>
                                <div class="form-actions">
                                    <a href="javascript:void(0);" class="btn btn-primary add-page-back-btn">Back</a>    
                                </div>
                            </form>                   
                        </div>
                     {{--    <div class="left-menu-black-bg"></div>
                        <div class="add-form-section-main edit-form-close-btn">
                            <form action="{{$module_url_path}}/store" id="frm_create_page" name="frm_create_page" class="form-horizontal cmxform" method="post" enctype="multipart/form-data">
                                {{csrf_field()}}
                                <div class="form-head-section">
                                    Edit <span class="add-form-close-btn"><i class="ti-close"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="scroll-main-section">
                                    
                                </div>
                                <div class="form-actions">
                                    <button type="submit" id="btn_add_front_page" class="fcbtn btn btn-danger btn-1g"> Submit</button>
                                    <a href="javascript:void(0);" class="btn btn-primary add-page-back-btn">Back</a>    
                                </div>
                            </form>      
                        </div>  --}}                         
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="{{url('/')}}/assets/admin_assets/js/table2excel.js" type="text/javascript"></script>
<script type="text/javascript">
        $(function () {
            $("#open_add_staff_modal").click(function () {
               
                $("#myTable").table2excel({
                    filename: "Table.xls"
                });
            });
        });
    </script>
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

$(document).ready(function() {

    // $("#open_add_staff_modal").click(function(e) 
    // {
    // var table = $('.myTable').DataTable();
    //     table.page.len( -1 ).draw();
    //     window.open('data:application/vnd.ms-excel,' + 
    //         encodeURIComponent($('.myTable').parent().html()));
    //   setTimeout(function(){
    //     table.page.len(10).draw();
    //   }, 1000)

    // });


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

    /*var oTable = $('#hidden-table-info').DataTable({
        processing: false,
        serverSide: true,
        searchDelay: 350,
        autoWidth: !1,
        bFilter: !1,
        ajax: {*/
            var oTable = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searchDelay: 350,
                // autoWidth: !1,
                // bFilter: !1,                
        ajax: {
            url: "{{ $module_url_path}}/load_data",
            data: function(d) {

                d['column_filter[q_full_name]']    = $("input[name='q_full_name']").val();
               // d['column_filter[q_last_name]']    = $("input[name='q_last_name']").val();
                d['column_filter[q_voter_id]']     = $("input[name='q_voter_id']").val();
               // d['column_filter[q_aadhar_id]']    = $("input[name='q_aadhar_id']").val();
                d['column_filter[q_address]']      = $("input[name='q_address']").val();
               // d['column_filter[q_city]']         = $("input[name='q_city']").val();
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

            {"data": "id",render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            },"orderable": true, "searchable":false
          },
           {data : 'family_id',"orderable":false,"searchable":true,name:'family_id'},
            {data : 'full_name',"orderable":false,"searchable":true,name:'full_name'},
           // {data : 'last_name',"orderable":false,"searchable":true,name:'last_name'},
           /* {data : 'email',"orderable":false,"searchable":true,name:'email'},*/
            {data : 'voter_id',"orderable":false,"searchable":true,name:'voter_id'},
            {data : 'mobile_number',"orderable":false,"searchable":true,name:'mobile_number'},
            {data : 'address',"orderable":false,"searchable":true,name:'address'},
           // {data : 'city_name',"orderable":false,"searchable":true,name:'city_name'},
          //  {data : 'face_color',"orderable":false,"searchable":true,name:'face_color'},

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
        "aaSorting": [[2, 'desc']],
    });

    $('.dataTables_filter input,.dataTables_length select').addClass('form-control');

    /* Add event listener for opening and closing details
    * Note that the indicator for showing which row is open is not controlled by DataTables,
    * rather it is done here
    */

    /*$('#hidden-table-info tbody td img').live('click', function () {
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
    });*/

    $('input.column_filter, select.column_filter').on( 'keyup change', function (){
        filterData();
    });

    function filterData(){
        //oTable.ajax.reload();
        oTable.draw();
    }

    // $(document).on('click','#hidden-table-info tbody td img',function () {
    //     var nTr = $(this).parents('tr')[0];
    //     if ( oTable.fnIsOpen(nTr) )
    //     {
    //         /* This row is already open - close it */
    //         this.src = detail_open_img_path;
    //         oTable.fnClose( nTr );
    //     }
    //     else
    //     {
    //         /* Open this row */
    //         this.src = detail_close_img_path;
    //         oTable.fnOpen( nTr, fnFormatDetails(oTable, nTr), 'details' );
    //     }
    // });

    /*function fnFormatDetails ( oTable, nTr )
    {
        var aData = oTable.fnGetData( nTr );
        var sOut = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">';
        sOut += '<tr><td>Rendering engine:</td><td>'+aData[1]+' '+aData[4]+'</td></tr>';
        sOut += '<tr><td>Link to source:</td><td>Could provide a link here</td></tr>';
        sOut += '<tr><td>Extra info:</td><td>And any further details here (images etc)</td></tr>';
        sOut += '</table>';

        return sOut;
    }*/
});
   
</script>   