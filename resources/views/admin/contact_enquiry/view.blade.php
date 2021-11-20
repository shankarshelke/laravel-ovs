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
                    <ul class="p-info">
                        <li>
                            <div class="title">First Name</div>
                            <div class="desk">{{ $arr_contact_enquiry['first_name'] or 'NA' }}</div>
                        </li>
                        <li>
                            <div class="title">Last Name</div>
                            <div class="desk">{{ $arr_contact_enquiry['last_name'] or 'NA' }}</div>
                        </li>
                        <li>
                            <div class="title">Enquiry Message</div>
                            <div class="desk">{{ $arr_contact_enquiry['message'] or 'NA' }}</div>
                        </li>
                        <li>
                            <div class="title">Reply</div>
                            <div class="desk"><? echo html_entity_decode($arr_contact_enquiry['admin_reply']) ?></div>
                        </li>
                    </ul> 
                    <hr>
                    <ul class="pager">
                        <li class="previous"><a href="{{ $module_url or '' }}"><i class="fa fa-arrow-left"></i> Back</a></li>
                    </ul>
                </div>
            </section>
        </div>
    </div>
</div>
<!--body wrapper end-->
<script type="text/javascript">

$(document).ready(function() {

});

</script>

@endsection