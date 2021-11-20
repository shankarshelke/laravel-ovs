@extends('admin.layout.master')    
@section('main_content')
<!--body wrapper start-->
<style type="text/css">
    .form-inline .form-control {display: block;}
</style>
<div class="wrapper">
    <div class="row">
        <div class="col-sm-12">
            @include('admin.layout.breadcrumb')
            <section class="panel">
                <header class="panel-heading">
                    {{$module_title or  '' }}
                </header>
                <div class="panel-body">
                    <div class="adv-table">
                        @include('admin.layout._operation_status')
                        <div class="clearfix">
                            <div class="pull-right">
                                <a href="{{url($module_url_path)}}" class="btn btn-default btn-rounded show-tooltip" title="Refresh"><i class="fa fa-refresh"></i></a>
                                <a href="{{ url('/') }}/admin/blogs/create" class="btn btn-default btn-rounded show-tooltip" title="Add New Blog"><i class="fa fa-plus"></i>
                                </a>
                                <a href="javascript:void(0)" class="btn btn-default btn-rounded show-tooltip" title="Deactivate Multiple" onclick="check_multi_action('frm_manage','deactivate')"><i class="fa fa-lock"></i></a>
                                <a href="javascript:void(0)" class="btn btn-default btn-rounded show-tooltip" title="Activate Multiple" onclick="check_multi_action('frm_manage','activate')"><i class="fa fa-unlock"></i></a>
                                <a href="javascript:void(0)" class="btn btn-default btn-rounded show-tooltip" title="Delete Multiple" onclick="check_multi_action('frm_manage','delete')"><i class="fa fa-trash-o"></i></a>

                            </div>
                        </div>
                        <form name="frm_manage" id="frm_manage" method="POST" class="form-horizontal" action="{{url($module_url_path)}}/multi_action">
                            {{ csrf_field() }}
                            <input type="hidden" name="multi_action" value="" />
                            <div id="hidden-table-info_wrapper" class="dataTables_wrapper form-inline" role="grid">

                                <table class="display table table-bordered dataTable" id="hidden-table-info" >
                                    <thead>
                                        <tr>
                                            <th> <input type="checkbox" class="filled-in" name="selectall" id="select_all" onchange="chk_all(this)" /><label for="selectall"></label></th>
                                            <th>Title
                                                <input type="text" name="q_title" placeholder="Search" class="search-block-new-table form-control column_filter">
                                            </th>
                                            <th>Short Description</th>
                                            <th>Created At</th>
                                            <th>Status
                                                <select class="search-block-new-table column_filter form-control" id="q_status" name="q_status">
                                                    <<option value="">Select Status</option>
                                                    <option value="1">Active</option>
                                                    <option value="0">InActive</option>
                                                </select>
                                            </th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<!--body wrapper end-->
<script type="text/javascript">

    $(document).ready(function() {
        
    /*
    * Initialse DataTables, with no sorting on the 'details' column
    */
    var oTable = $('#hidden-table-info').DataTable({
        processing: true,
        serverSide: true,
        searchDelay: 350,
        autoWidth: !1,
        bFilter: !1,
        ajax: {
            url: "{{ $module_url_path}}/load_data",
            data: function(e) {
                e['column_filter[q_status]']      = $("select[name='q_status']").val();
                e["column_filter[q_title]"]       = $("input[name='q_title']").val()
            }
        },
        columns: [
        {
            render : function(data, type, row, meta) 
            {
                return '<div class="check-box"><input type="checkbox" class="filled-in case" name="checked_record[]"  d="mult_change_'+row.id+'" value="'+row.id+'" /><label for="mult_change_'+row.id+'"></label></div>';

            },"orderable": false, "searchable":false           
        },
        {data : 'title',"orderable":false,"searchable":true,name:'title'},
        {data : 'short_description',"orderable":false,"searchable":true,name:'short_description'},
        {data : 'created_at',"orderable":false,"searchable":true,name:'created_at'},
        {
            data: "build_status_btn",
            orderable: !1,
            searchable: !0,
            name: "build_status_btn"
        }, {
            data: "build_action_btn",
            orderable: !1,
            searchable: !0,
            name: "build_action_btn"
        }
        ],
        "order": [[ 3, "desc" ]]
    });

    $('.dataTables_filter input,.dataTables_length select').addClass('form-control');

    $('input.column_filter, select.column_filter').on( 'keyup change', function (){
        filterData();
    });

    function filterData(){
        oTable.draw();
    }


});

</script>
@endsection


