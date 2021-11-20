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
                        <div class="pull-right">
                            <a href="{{ $module_url_path }}" class="btn btn-default btn-rounded show-tooltip" title="Refresh"><i class="fa fa-refresh"></i>
                            </a>
                            <a href="{{ $module_url_path }}/create" class="btn btn-default btn-rounded show-tooltip" title="Add New template"><i class="fa fa-plus"></i>
                            </a>
                        </div>
                        <form name="frm_manage" id="frm_manage" method="POST" class="form-horizontal" action="{{url($module_url_path)}}/multi_action">
                            {{ csrf_field() }}
                            <input type="hidden" name="multi_action" value="" />
                            <div id="hidden-table-info_wrapper" class="dataTables_wrapper form-inline" role="grid">

                                <table class="display table table-bordered dataTable" id="hidden-table-info" >
                                    <thead>
                                        <tr>
                                            <th>Title
                                                <input type="text" name="q_title" placeholder="Search" class="search-block-new-table form-control column_filter">
                                            </th>
                                            <th>Subject</th>
                                            <th>Created At</th>
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
            url: "{{ $module_url_path}}/load_template_data",
            data: function(e) {
                e["column_filter[q_title]"]       = $("input[name='q_title']").val();
            }
        },
        columns: [
        
        {data : 'title',"orderable":false,"searchable":true,name:'title'},
        {data : 'subject',"orderable":false,"searchable":true,name:'subject'},
        {data : 'created_at',"orderable":false,"searchable":true,name:'created_at'},
        {
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


