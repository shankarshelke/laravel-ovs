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
                        <a href="{{ url($module_url_path) }}" class="btn btn-default btn-rounded show-tooltip" title="refresh">{{ trans('myteam.refresh') }}</a>
                        @if(get_admin_access('my_team','create'))
                        <!--<a href="{{ url($module_url_path) }}/create" class="btn btn-default btn-rounded show-tooltip add-form-btn-section" title="Add New">Add</a>-->
                        <a href="javascript:void(0);" class="btn btn-default btn-rounded show-tooltip add-form-btn-section" title="Add New">{{ trans('myteam.add') }}</a>
                        @endif
                        @if(get_admin_access('my_team','approve'))
                        <a href="javascript:void(0)" class="btn btn-default btn-rounded show-tooltip" title="Deactivate Multiple" onclick="check_multi_action('frm_manage','deactivate')">{{ trans('myteam.deactivate') }}</a>
                        
                        <a href="javascript:void(0)" class="btn btn-default btn-rounded show-tooltip" title="Activate Multiple" onclick="check_multi_action('frm_manage','activate')">{{ trans('myteam.activate') }}</a>
                        @endif
                        @if(get_admin_access('my_team','delete'))
                        <a href="javascript:void(0)" class="btn btn-default btn-rounded show-tooltip" title="Delete Multiple" onclick="check_multi_action('frm_manage','delete')">{{ trans('myteam.delete') }}</a>
                        @endif
                    </div>


                    <div class="adv-table">
                        <div id="hidden-table-info_wrapper" class="dataTables_wrapper form-inline table-responsive" role="grid">
                            <form name="frm_manage" id="frm_manage" method="POST" class="form-horizontal" action="{{url($module_url_path)}}/multi_action">
                                {{ csrf_field() }}
                                <input type="hidden" name="multi_action" value="" />
                                <table class="display table table-bordered dataTable" id="hidden-table-info" id="tbl_activitylog_listing">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" class="filled-in" name="selectall" id="select_all" onchange="chk_all(this)" /><label for="selectall"></label>
                                            </th>
                                            <th>{{ trans('myteam.Full Name') }}</th>
                                            <th>{{ trans('myteam.Email') }}</th>
                                            <th>{{ trans('myteam.Contact') }}</th>
                                            <th>{{ trans('myteam.Role') }}</th>
                                            <th>{{ trans('myteam.status') }}
                                            </th>
                                            <th style="width: 50px;">{{ trans('myteam.action') }}</th>
                                    </thead> 
                                    <tbody></tbody>
                                </table>
                            </form>
                        </div>
                        <div class="add-form-section-main section-add-form">
                            <form action="{{$module_url_path}}/store" id="frm_myteam_page" name="frm_myteam_page" class="cmxform" method="post">
                                {{csrf_field()}} 
                                <div class="form-head-section">
                                   {{ trans('myteam.Add Team Member') }}  <span class="add-form-close-btn"><i class="ti-close"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="scroll-main-section">                                                            
                                    <div class="form-group">
                                        <label class="control-label" for="first_name">{{ trans('myteam.First Name') }}<i class="red">*</i></label>
                                        <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-user"></i></span>
                                            <input type="text" value="{{old('first_name')}}" name="first_name" id="first_name" class="form-control" placeholder="First Name" data-rule-required="true" data-rule-pattern="^[a-z A-Z]+$" data-msg-pattern="Alphabetics only" data-rule-maxlength="40" value="">
                                            <span class="error" style = "color:red;">{{ $errors->first('first_name') }} </span>
                                        </div>
                                    </div>                                                                
                                    <div class="form-group">
                                        <label class="control-label" for="last_name">{{ trans('myteam.Last Name') }}<i class="red">*</i></label>
                                        <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-user"></i></span>
                                            <input type="text" value="{{old('last_name')}}" name="last_name" id="last_name" class="form-control" placeholder="Last Name" data-rule-required="true" data-rule-pattern="^[a-z A-Z]+$" data-msg-pattern="Alphabetics only" data-rule-maxlength="40" value="">
                                            <span class="error" style = "color:red;">{{ $errors->first('last_name') }} </span>
                                        </div>
                                    </div>                                                                
                                    <div class="form-group">
                                        <label class="control-label" for="role">{{ trans('myteam.Select Role') }}<i class="red">*</i></label>
                                        <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-user"></i></span>
                                            <select name="role" data-rule-required="true" id="role"  class="form-control ">
                                                <option value="{{old('role')}}" >{{ trans('myteam.Select Role') }} </option>
                                                @if(isset($arr_roles) && count($arr_roles)>0)
                                                    @foreach($arr_roles as $roles)
                                                    <option value="{{$roles['role']}}">{{$roles['role'] or ''}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>	
                                    </div>                                								
                                    <input type="hidden"  name="admin_type" id="admin_type" class="form-control" value="SUBADMIN">                                
                                    <div class="form-group">
                                        <label class="control-label" for="contact">{{ trans('myteam.Contact Number') }}<i class="red">*</i></label>
                                        <div class="input-group">
                                        <span class="input-group-addon"><i class="icon-phone"></i></span>
                                            <input class="form-control required valid"  value="{{old('contact')}}" data-rule-number="true" data-type="contact-number" name="contact" data-rule-number="true" type="text" placeholder="Contact Number"   id="contact" data-rule-required="true" maxlength="10">
                                            <span class="error" style = "color:red;">{{ $errors->first('contact') }} </span>
                                        </div>
                                    </div>                                                                
                                    <div class="form-group">
                                        <label class="control-label" for="email">{{ trans('myteam.Email') }}<i class="red">*</i></label>
                                        <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-email"></i></span>
                                            <input type="email" value="{{old('email')}}" name="email" id="email" class="form-control" placeholder="Email" data-rule-required="true"  value="">
                                            <span class="error" style = "color:red;">{{ $errors->first('email') }} </span>
                                        </div>
                                    </div>                                                                
                                    <div class="form-group">
                                        <label class="control-label" for="password">{{ trans('myteam.Password') }}<i class="red">*</i></label>
                                        <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-lock"></i></span>
                                            <input type="password" value="{{old('password')}}" name="password" id="password" class="form-control" placeholder="Password"  data-rule-required="true" data-rule-minlength="6" data-rule-maxlength="16" >
                                            <span class="error" style = "color:red;">{{ $errors->first('password') }} </span>
                                        </div>
                                    </div>                                
                                    <div class="form-group">
                                        <label class="control-label" for="conf_password">{{ trans('myteam.Confirm Password') }}<i class="red">*</i></label>
                                        <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-lock"></i></span>
                                            <input type="password" value="{{old('conf_password')}}" name="conf_password" id="conf_password" class="form-control" placeholder="Confirm Password" data-rule-required="true" data-rule-equalto="#password" data-rule-maxlength="16">
                                            <span class="error" style = "color:red;">{{ $errors->first('conf_password') }} </span>
                                        </div>
                                    </div>                                                                
                                    <div class="form-group">
                                        <label class="control-label" for="address">{{ trans('myteam.Address') }}<i class="red">*</i></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="icon-address"></i></span>
                                            <textarea  name="address" id="site_address" class="form-control" placeholder="Address" data-rule-required="true"  data-rule-maxlength="500">{{old('address')}}</textarea>
                                            <span class="error" style = "color:red;">{{ $errors->first('address') }} </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" id="btn_add_front_page" class="fcbtn btn btn-danger btn-1g">{{ trans('myteam.add') }}</button>
                                    <a href="javascript:void(0);" class="btn btn-primary add-page-back-btn">{{ trans('myteam.back') }}</a>
                                </div>	
                            </form>                       
                        </div>
                        <div class="left-menu-black-bg"></div>
                        <div class="add-form-section-main edit-form-close-btn">
                            <form action="{{url($module_url_path)}}/update" id="frm_edit" name="frm_edit" method="post"   onsubmit='addLoader()';enctype="multipart/form-data" onsubmit='addLoader()';>
                               {{csrf_field()}}
                              <input type="hidden" name="enc_id" id="enc_id" value="">
                                <div class="form-head-section">
                                    {{ trans('myteam.Edit Team Member') }} <span class="add-form-close-btn"><i class="ti-close"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="scroll-main-section">
                                    <div class="form-group">
                                        <label class="control-label" for="first_name">{{ trans('myteam.First Name') }}<i class="red">*</i></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="ti-user"></i></span>
                                            <input type="text" name="first_name" id="edit_first_name" class="form-control" placeholder="First Name" data-rule-required="true" data-rule-pattern="^[a-z A-Z]+$" data-msg-pattern="Alphabetics only" data-rule-maxlength="40" value="">
                                            <span class="error">{{ $errors->first('first_name') }} </span>
                                        </div>
                                    </div>                                                                
                                    <div class="form-group">
                                        <label class="control-label" for="last_name">{{ trans('myteam.Last Name') }}<i class="red">*</i></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="ti-user"></i></span>
                                            <input type="text" name="last_name" id="edit_last_name" class="form-control" placeholder="Last Name" data-rule-required="true" data-rule-pattern="^[a-z A-Z]+$" data-msg-pattern="Alphabetics only" data-rule-maxlength="40" value="">
                                            <span class="error">{{ $errors->first('last_name') }} </span>
                                        </div>
                                    </div>                                                                
                                    <div class="form-group">
                                        <label class="control-label" for="role">{{ trans('myteam.Select Role') }}<i class="red">*</i></label>
                                        <div class="input-group">
                                        <span class="input-group-addon"><i class="ti-user"></i></span>
                                            <select name="role" data-rule-required="true" id="edit_role"  class="form-control ">
                                                <option value="{{old('role')}}" >{{ trans('myteam.Select Role') }} </option>
                                                @if(isset($arr_roles) && count($arr_roles)>0)
                                                    @foreach($arr_roles as $roles)
                                                    <option value="{{$roles['role']}}">{{$roles['role'] or ''}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>  
                                    </div>                               
                                    <input type="hidden"  name="admin_type" id="admin_type" class="form-control" value="SUBADMIN">                                
                                    <div class="form-group">
                                        <label class="control-label" for="contact">{{ trans('myteam.Contact Number') }}<i class="red">*</i></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="icon-phone"></i></span>
                                            <input type="text" name="contact" id="edit_contact" class="form-control" placeholder="Contact Number" data-rule-required="true" data-rule-pattern="[- +()0-9]+" data-rule-minlength="10" data-rule-maxlength="10" data-msg-minlength="Contact no should be atleast 7 numbers" data-type="contact-number" data-msg-maxlength="Contact no should not be more than 10 numbers" value="">
                                            <span class="error">{{ $errors->first('contact') }} </span>
                                        </div>
                                    </div>                                                                
                                    <div class="form-group">
                                        <label class="control-label" for="email">{{ trans('myteam.Email') }}<i class="red">*</i></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="ti-email"></i></span>
                                            <input type="email" value="" name="email" id="edit_email" class="form-control" placeholder="Email" data-rule-required="true" >
                                            <span class="error">{{ $errors->first('email') }} </span>
                                        </div>
                                    </div>                                                                
                                    <div class="form-group">
                                        <label class="control-label" for="address">{{ trans('myteam.Address') }}<i class="red">*</i></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="icon-address"></i></span>
                                            <textarea  name="address" id="edit_address" class="form-control" placeholder="Address" data-rule-required="true"  data-rule-maxlength="500">{{old('address')}}</textarea>
                                            <span class="error" style = "color:red;">{{ $errors->first('address') }} </span>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <button type="submit" id="proceed_edit" class="fcbtn btn btn-danger btn-1g">{{ trans('myteam.update') }}</button>
                                        <a href="javascript:void(0);" class="btn btn-primary add-page-back-btn">{{ trans('myteam.back') }}</a>
        <!--                                <button class="btn btn-primary" type="submit"  id="btn_add_front_page">Create</button>-->
                                    </div>
    <!--
                                    <div class="form-group text-right">
                                        <div class="col-lg-8">
                                            <button type="submit" class="btn btn-primary">Update</button>
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
        $("#first_name").val("");
        $("#last_name").val("");
        $("#role").val("");
        $("#contact").val("");
        $("#email").val("");
        $("#address").val("");
        $("#edit_first_name").val("");
        $("#edit_last_name").val("");
        $("#edit_role").val("");
        $("#edit_contact").val("");
        $("#edit_email").val("");
        $("#edit_address").val("");
    
    }); 
</script>
<script>
    $('body').on('click','.edit_button',function(){        
        $("body").addClass("edit-form-open");        
    });
</script>
<script src="https://maps.googleapis.com/maps/api/js?v=3&key={{config('app.project.google_map_api_key')}}&libraries=places">
</script>        
<script>
    $(document).ready(function()
    {
        $('#frm_edit').validate();
        $("#site_address").geocomplete({
            alert('here');
            details: ".geo-details",
            detailsAttribute: "data-geo"
        }).bind("geocode:result", function (event, result){                       
            $("#latitude").val(result.geometry.location.lat());
            $("#longitude").val(result.geometry.location.lng());
            var searchAddressComponents = result.address_components,
            searchPostalCode="";
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
        ajax: {
            url: "{{ $module_url_path}}/load_data",
            data: function(d) {
                        d['column_filter[q_full_name]']   = $("input[name='q_full_name']").val();
                        d['column_filter[q_email]']       = $("input[name='q_email']").val();
                        d['column_filter[q_contact]']     = $("input[name='q_contact']").val();
                        d['column_filter[q_role]']        = $("input[name='q_role']").val();
                        d['column_filter[status]']        = $( "#status option:selected" ).val()          
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
                {data : 'email',"orderable":false,"searchable":true,name:'email'},
                {data : 'contact',"orderable":false,"searchable":true,name:'contact'},
                {data : 'role',"orderable":false,"searchable":true,name:'role'},
                // {data : 'created_at',"orderable":true,"searchable":true,name:'created_at'},q_role

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
</script>

<script>
  $('[data-type="contact-number"]').keyup(function() {
    var value = $(this).val();
    value = value.replace(/\D/g, "").split(/(?:([\d]{4}))/g).filter(s => s.length > 0).join("");
    $(this).val(value);
  });

  $('[data-type="contact-number"]').on("change, blur", function() {
    var value = $(this).val();
    var maxLength = $(this).attr("maxLength");
    if (value.length != maxLength) {
      $(this).addClass("highlight-error");
    } else {
      $(this).removeClass("highlight-error");
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
                        $('#edit_first_name').val(resp.data.first_name);
                        $('#edit_last_name').val(resp.data.last_name);
                        $('#edit_role').val(resp.data.role);
                        $('#edit_contact').val(resp.data.contact);
                        $('#edit_email').val(resp.data.email);
                        $('#edit_address').val(resp.data.address);
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


