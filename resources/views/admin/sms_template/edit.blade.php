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
					<form class="form-horizontal cmxform" id="frm_create_sms_template" name="frm_create_sms_template" action="{{$module_url_path}}/update/{{$id}}" method="post">
						{{csrf_field()}}
						<div class="row">
							<div class="col-lg-8">
								<div class="form-group">
									<label class="control-label col-sm-4 col-md-4 col-lg-3" for="template_name"> Template Name<i class="red">*</i></label>
									<div class="col-sm-8 col-md-8 col-lg-9">
										<input type="text" name="template_name" value="{{$arr_email_template['template_name']}}" id="template_name" class="form-control" placeholder="Template Name" data-rule-required="true"  data-rule-maxlength="60" value="">
										<span class="error">{{ $errors->first('template_name') }} </span>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-8">
								<div class="form-group">
									<label class="control-label col-sm-4 col-md-4 col-lg-3" for="template_html"> Template Body<i class="red">*</i></label>
									<div class="col-sm-8 col-md-8 col-lg-9">
										<textarea  name="template_html" id="template_html" class="form-control" data-rule-required="true" rows="8">{{$arr_email_template['template_html'] or ''}}</textarea>
										<span class="error err_email_content">{{ $errors->first('template_html') }} </span>

										@if(isset($arr_variables) && sizeof($arr_variables)>0 && !empty($arr_variables))
										<br>
										<div class="note"><b>Note : </b>Please don't change the following variables in the email template body.</div>
										<br>
										<span> Variables: </span>

										@foreach($arr_variables as $variable)
										<br> <label> {{ $variable }} </label> 
										@endforeach
										@endif
									</div>
								</div>
							</div>
						</div>	
						<div class="form-group text-right">
							<div class="col-lg-8">
								<button type="submit" class="btn btn-primary" id="btn_update_email_template">Update</button>
							<!-- 	<a href="javascript:void(0)" name="preview" id="preview" class="btn btn-primary"><i class="fa fa-eye"></i> Preview</a> -->
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
	<!-- <script src="//cdn.tinymce.com/4/tinymce.min.js"></script> -->

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

	@endsection


