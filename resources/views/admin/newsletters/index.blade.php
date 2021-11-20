@extends('admin.layout.master')    
@section('main_content')
<!--body wrapper start-->
<style type="text/css">
    .form-inline .form-control {display: block;}
    .error{color: red;}
</style>
<div class="wrapper">
    <div class="row">
        <div class="col-sm-12">
            @include('admin.layout.breadcrumb')
            <section class="panel">
                <header class="panel-heading">
                    {{$module_title or '' }}
                </header>
                <div class="panel-body">
                    <div class="adv-table">
                        @include('admin.layout._operation_status')

                        <form class="form-horizontal adminex-form" method="post" id="frm_newsletter" action="{{ url($module_url_path) }}/send">
                            {{ csrf_field() }}
                            <input type="hidden" name="multi_action" value="" />
                            <div class="form-group">
                                <div class="col-lg-4">
                                    <select class="col-md-4 form-control" data-rule-required="true" name="news_letter" class="form-control" id="news_letter">
                                        <option value=""> -- Select Newsletter Template -- </option>    
                                        @if(isset($arr_newsletters) && sizeof($arr_newsletters)>0)
                                            @foreach($arr_newsletters as $newsletter)
                                                <option value="{{base64_encode($newsletter['id'])}}">{{isset($newsletter['title'])?$newsletter['title']:'-'}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <span class="error">{{ $errors->first('news_letter') }} </span>
                                </div>
                                <button class="btn btn-primary" type="submit">Send</button>
                                <div class="pull-right">
                                    <a style="margin-right: 15px;" href="{{ url('/') }}/admin/newsletters" class="btn btn-default btn-rounded show-tooltip" title="Refresh"><i class="fa fa-refresh"></i></a>
                                </div>    
                            </div>
                       
                            <div id="hidden-table-info_wrapper" class="dataTables_wrapper form-inline" role="grid">

                                <table class="display table table-bordered dataTable" id="hidden-table-info" >
                                    <thead>
                                        <tr>
                                            <th> <input type="checkbox" class="filled-in" name="selectall" id="select_all" onchange="chk_all(this)" /><label for="selectall"></label></th>
                                            <th>Full Name
                                                <input type="text" name="q_email" placeholder="Search" class="search-block-new-table form-control column_filter">
                                            </th>
                                            <th>
                                                Created At
                                            </th>
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
        
        $('#frm_newsletter').validate();
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
                e["column_filter[q_email]"]       = $("input[name='q_email']").val()
            }
        },
        columns: [
        {
            render : function(data, type, row, meta) 
            {
                return '<div class="check-box"><input type="checkbox" class="filled-in case" name="checked_record[]" d="mult_change_'+row.email+'" value="'+row.email+'" /><label for="mult_change_'+row.email+'></label></div>';

            },"orderable": false, "searchable":false           
        },
        {data : 'email',"orderable":false,"searchable":true,name:'email'},
        {data : 'created_at',"orderable":false,"searchable":false,name:'created_at'},
        ],
        //"order": [[ 3, "desc" ]],
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


});

</script>
@endsection


