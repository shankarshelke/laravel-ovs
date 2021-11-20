@extends('admin.layout.master')    
@section('main_content')
<div class="wrapper">
	<div class="row">
		<div class="col-md-12">
				@include('admin.layout.breadcrumb')
			<div class="panel ">
				<div class="panel-heading page-name">
					<h5 class="panel-title">{{$sub_module_title or ''}}</h5>
				</div>

				<div class="panel-body">
					@include('admin.layout._operation_status')
					<form class="form-horizontal cmxform" id="frm_create_sms_template" name="frm_create_sms_template" action="{{$module_url_path}}/send_sms_to_user" method="post">
						{{csrf_field()}}
						<div class="row">
							<div class="col-lg-8">
								<div class="form-group">
									<label class="control-label col-sm-4 col-md-4 col-lg-3" for="template_name"> Template Name<i class="red">*</i></label>
									<div class="col-sm-8 col-md-8 col-lg-9">
                                                                                <select class="form-control" id="template_id" name="template_id">
                                                                                <option value="none">Select</option>    
                                                                                @foreach($template as $key => $sms)
                                                                                    <option value='{{$key}}'>{{$sms}}</option>
                                                                                @endforeach

                                                                                </select>
									</div>
								</div>
							</div>
							<div class="col-lg-8">
								<div class="form-group">
									<label class="control-label col-sm-4 col-md-4 col-lg-3" for="template_name">Sent To<i class="red">*</i></label>
									<div class="col-sm-3 col-md-3 col-lg-3">
										<label>All
											<input type="radio" name="send_to" class="" value="all" id="send_to">
										</label>
									</div>
								<!-- </div> -->
								<!-- <div class="form-group"> -->
									<div class="col-sm-3 col-md-3 col-lg-3">
										<label>Group
											<input type="radio" name="send_to" class="" value="group" id="send_to">
										</label>
									</div>									
								</div>
							</div>
							<div class="col-lg-8" style="display: none;" id="group_ddl">
								<div class="form-group">
									<label class="control-label col-sm-4 col-md-4 col-lg-3" for="template_name"> Group <i class="red">*</i></label>
									<div class="col-sm-8 col-md-8 col-lg-9">
                                                                                <select class="form-control" id="group_id" name="group_id">
                                                                                <option value="none">Select</option>    
                                                                                @foreach($group as $key => $gp)
                                                                                    <option value='{{$gp["id"]}}'>{{$gp['group_name']}}</option>
                                                                                @endforeach

                                                                                </select>
									</div>
								</div>
							</div>							
						</div>

						<div class="form-group text-right">
							<div class="col-lg-8">
								<button type="submit" class="btn btn-primary" id="btn_update_email_template">Send</button>
								<a href="javascript:void(0)" name="preview" id="preview" class="btn btn-primary"><i class="fa fa-eye"></i> Preview</a>
							</div>
						</div>
						
					</form>
					<form id="preview_form"  method="POST" action="{{$module_url_path}}/preview" target="_blank">
						{{csrf_field()}}
						<input type="hidden" name="preview_html" id="preview_html" required=""> 
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
	<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>

	<script>tinymce.init({ selector:'#template_html' });</script>

	<script>

		$(document).ready(function(){
			$('#frm_edit_email_template').validate({
				highlight: function(element) { },
				ignore: [],
			});

			/*TINY Text */
			tinymce.init({
				selector: 'textarea',
				height:350,
				valid_elements: "*[*]",
				force_p_newlines : !1,
				forced_root_block : !1,

				plugins: ['code',
				'advlist autolink lists link charmap print preview anchor',
				'searchreplace visualblocks code fullscreen',
				'insertdatetime table contextmenu paste code'
				],
				toolbar: 
				'code | insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent',
				content_css: [

				],
			}); 

			$('#preview').click(function(){

				tinyMCE.triggerSave();

				$('#preview_html').val($('#template_html').val());

				$('#preview_form').submit();

			});

			var preview_rules = new Object();

			preview_rules['preview_html'] = { required: true };

			$('#preview_form').validate({
				ignore: [],
				rules : preview_rules,
				errorPlacement: function(error, element) 
				{
					$(".err_email_content").html("");

					var name = $(element).attr("name");
					if(name == 'template_html')
					{
						error.appendTo($(this).find(".err_email_content"));
					}
				}

			});

			$('#btn_update_email_template').click(function(){
				tinyMCE.triggerSave();
			});
		});
	</script>
	<script type="text/javascript">
$("form input:radio").change(function () {
// alert($(this).val())
    if ($(this).val() == "group") {

    	$('#group_ddl').show();
        // Disable your roomnumber element here
        //$('.roomNumber').attr('disabled', 'disabled');
    } else {
        // Re-enable here I guess
       $('#group_ddl').hide();
    }
});
	</script>
	@endsection


