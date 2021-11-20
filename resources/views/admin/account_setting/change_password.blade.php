@extends('admin.layout.master')    
@section('main_content')
        <!--body wrapper start-->
        <div class="wrapper">
            <div class="row">
                <div class="col-md-12">
                @include('admin.layout.breadcrumb') 
                        <section class="panel">                            
                            @include('admin.layout._operation_status') 
                            <div class="panel-body">
                                <form action="{{url('/')}}/admin/password/update" id="frm_admin" name="frm_admin" class="cmxform" method="post" enctype="multipart/form-data"> 
                                {{csrf_field()}}
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 col-lg-6">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-12 col-lg-12">
                                                    <div class="form-group">
                                                        <label class="control-label">{{ trans('accountsetting.current password') }}</label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="ti-more-alt"></i></span>
                                                            <input type="password" id="current_password" name="current_password"  data-rule-required="true" class="form-control ">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-12 col-lg-12">
                                                    <div class="form-group">
                                                        <label class="control-label">{{ trans('accountsetting.new password') }}</label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="ti-more-alt"></i></span>
                                                            <input type="password" id="new_password"  name="new_password"  data-rule-required="true"  class="form-control " minlength="6">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-12 col-lg-12">
                                                    <div class="form-group">
                                                        <label class="control-label">{{ trans('accountsetting.confirm password') }}</label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="ti-more-alt"></i></span>
                                                            <input type="password" id="confirm_password" name="confirm_password" data-rule-required="true"  data-rule-equalto = "#new_password" minlength="6" class="form-control ">
                                                        </div>
                                                    </div> 
                                                </div>
                                            </div> 
                                        </div>
                                    </div>                                                                                                           
                                    <div class="form-group">                                        
                                        <button class="btn btn-primary" type="submit" >{{ trans('accountsetting.update') }}</button>                                        
                                    </div>
                                </form>
                            </div>
                        </section>
                </div>
            </div>
        </div>
        <!--body wrapper end-->

<script type="text/javascript">
    $(document).ready(function(){
        $('#frm_admin').validate();
    });
    
</script>
@endsection


			