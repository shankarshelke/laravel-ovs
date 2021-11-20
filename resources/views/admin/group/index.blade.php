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
                <div class="panel-body" style="position: relative">
                    <div class="adv-table">
                        @include('admin.layout._operation_status')
                        <div class="clearfix">
                            <div class="table-action-buttons-top">
                                <a href="{{url($module_url_path)}}" class="btn btn-default btn-rounded show-tooltip" title="Refresh">Refresh</a>
                                <a href="{{url($module_url_path)}}/import" class="btn btn-default btn-rounded show-tooltip" title="Add">Import</a>
                                @if(get_admin_access('sms_template','delete'))
                                <a href="javascript:void(0)" class="btn btn-default btn-rounded show-tooltip" title="Delete Multiple" onclick="check_multi_action('frm_manage','delete')">Delete</a>
                                @endif
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
                                            <th>Group Name</th>
                                            <th>Added On</th>
                                            <th>Actions</th>
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
                e['column_filter[group_name]']      = $("input[name='group_name']").val();
            }
        },
        columns: [
        {
            render : function(data, type, row, meta) 
            {
                return '<div class="check-box"><input type="checkbox" class="filled-in case" name="checked_record[]"  d="mult_change_'+row.id+'" value="'+row.id+'" /><label for="mult_change_'+row.id+'"></label></div>';

            },"orderable": false, "searchable":false           
        },
        {data : 'group_name',"orderable":false,"searchable":true,name:'group_name'},
        // {data : 'template_subject',"orderable":false,"searchable":false,name:'template_subject'},
        // {data : 'template_from',"orderable":false,"searchable":true,name:'template_from'},
        // {data : 'template_from_mail',"orderable":false,"searchable":true,name:'template_from_mail'},
        {data : 'created_at',"orderable":false,"searchable":true,name:'created_at'},
        {
            data: "built_action_button",
            orderable: !1,
            searchable: !0,
            name: "built_action_button"
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


