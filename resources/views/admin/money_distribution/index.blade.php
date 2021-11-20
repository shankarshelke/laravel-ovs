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
                <header class="panel-heading">
                    {{$module_title or  '' }}
                </header>
                <div class="panel-body">
                    @include('admin.layout._operation_status')
                    <div>
                        <div class="pull-left">
                           Start Date
                         <input type="text" id="datepicker" readonly="" value="{{old('date')}}"  name="start_date" placeholder="Start Date" class="search-block-new-table form-control column_filter"> 
                      </div>
                       <div class="pull-left">
                            End Date
                           <input type="text" id="datepicker1" readonly="" value="{{old('date')}}"  name="end_date" placeholder="End Date" class="search-block-new-table form-control column_filter">

                      </div>
                        </div> 
                        <div class="pull-right">
                            <a href="{{ url($module_url_path) }}" class="btn btn-default btn-rounded show-tooltip" title="Page Refresh" ><i class="fa fa-refresh"></i></a>
                        @if(get_admin_access('finance_team'))
                        <a href="{{ url('/') }}/admin/finance_team" class="btn btn-default btn-rounded show-tooltip" title="Finance Team"><i class='fa fa-arrow-left'></i></a>
                        @endif
 
                      @if(get_admin_access('money_distribution','create'))
                        <a href="{{ url('/') }}/admin/money_distribution/create" class="btn btn-default btn-rounded show-tooltip" title="Add New"><i class="fa fa-plus"></i></a>
                        @endif
                            @if(get_admin_access('money_distribution','approve'))
                            <a href="javascript:void(0)" class="btn btn-default btn-rounded show-tooltip" title="Deactivate Multiple" onclick="check_multi_action('frm_manage','deactivate')"><i class="fa fa-lock"></i></a>
                            <a href="javascript:void(0)" class="btn btn-default btn-rounded show-tooltip" title="Activate Multiple" onclick="check_multi_action('frm_manage','activate')"><i class="fa fa-unlock"></i></a>
                            @endif
                            @if(get_admin_access('money_distribution','delete'))
                            <a href="javascript:void(0)" class="btn btn-default btn-rounded show-tooltip" title="Delete Multiple" onclick="check_multi_action('frm_manage','delete')"><i class="fa fa-trash-o"></i></a>
                            @endif
                        </div>
                    </div>


                    <div class="adv-table">
                        <div id="hidden-table-info_wrapper" class="dataTables_wrapper form-inline table-responsive" role="grid">
                            <form name="frm_manage" id="frm_manage" method="POST" class="form-horizontal" action="{{url($module_url_path)}}/multi_action">
                                    {{ csrf_field() }}
                                <input type="hidden" name="multi_action" value="" />
                                 
                                 <table class="display table table-bordered dataTable" id="hidden-table-info" id="tbl_activitylog_listing"> 
                                    {{-- <table id="myTable" class="table table-striped"> --}}
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" class="filled-in" name="selectall" id="select_all" onchange="chk_all(this)" /><label for="selectall"></label>
                                            </th>
                                             <th>Accountant Name
                                                <input type="text" name="full_name" placeholder="Search" class="search-block-new-table form-control column_filter">
                                            </th>
                                            <th>Mobile No
                                                <input type="text" name="contact" placeholder="Search" class="search-block-new-table form-control column_filter">
                                            </th> 
                                            <th>Date
                                                <input type="text" name="d_date" placeholder="Search" class="search-block-new-table form-control column_filter">
                                            </th>
                                            <th>Amount
                                                <input type="integer"  name="amount" placeholder="Search" class="search-block-new-table form-control column_filter">
                                            </th>
                                            


                                            <th width="10%">Action</th>
                                    </thead>
                                      
                                    <tbody></tbody>
                                </table>
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

    var oTable = $('#hidden-table-info').DataTable({
        processing: false,
        serverSide: true,
        searchDelay: 350,
        autoWidth: !1,
        bFilter: !1,
       // var oTable =  $('#myTable').DataTable({
        ajax: {
            url: "{{ $module_url_path}}/load_data",
            data: function(d) {
                d['column_filter[start_date]']            = $("input[name='start_date']").val()
                d['column_filter[end_date]']              = $("input[name='end_date']").val()
                d['column_filter[contact]']               = $("input[name='contact']").val()
                d['column_filter[amount]']                = $("input[name='amount']").val()
                d['column_filter[d_date]']                = $("input[name='d_date']").val()
                d['column_filter[full_name]']             = $("input[name='full_name']").val()
               // d['column_filter[status]']                = $( "#status option:selected" ).val()
              
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
            {data : 'contact',"orderable":false,"searchable":true,name:'contact'},
            {data : 'd_date',"orderable":false,"searchable":true,name:'d_date'},
            {data : 'amount',"orderable":false,"searchable":true,name:'amount'},
            

            
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
   
</script>

@endsection


