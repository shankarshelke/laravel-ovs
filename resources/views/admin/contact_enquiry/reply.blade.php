
@extends('admin.layout.master')    
@section('main_content')
<!-- Page header -->
<!-- /page header -->


<style type="text/css">
.text-data {
	position: relative;
	top: 9px;
}
</style>
<div class="wrapper">
	<div class="row">
		<div class="col-sm-12">
@include('admin.layout.breadcrumb')  
			<div class="panel panel-flat">
				@include('admin.layout._operation_status')
				<div class="panel-heading page-name">
					<h5 class="panel-title">{{$page_title or ''}}</h5>
				</div>

				<div class="panel-body">
					<form class="form-horizontal" id="frm_contact_enquiry" name="frm_contact_enquiry" action="{{$module_url_path}}/send_reply/{{ $enc_id}}" method="post" enctype="multipart/form-data">
						{{csrf_field()}}
						<fieldset class="content-group">	

							<div class="row">
								<div class="col-lg-8">
									<div class="form-group">
										<label class="control-label col-sm-4 col-md-4 col-lg-3" for="name">First Name :<i class="red"></i></label>
										<div class="col-sm-8 col-md-8 col-lg-9">
											<div class="text-data">{{ $arr_data['first_name'] or 'NA' }}</div>							
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-8">
									<div class="form-group">
										<label class="control-label col-sm-4 col-md-4 col-lg-3" for="url">Email Id :</label>
										<div class="col-sm-8 col-md-8 col-lg-9">
											<div class="text-data">{{ $arr_data['email'] or 'NA' }}</div>
										</div>
									</div>
								</div>
							</div>
						
						{{-- 	<div class="row">
								<div class="col-lg-8">
									<div class="form-group">
										<label class="control-label col-sm-4 col-md-4 col-lg-3" for="url">Category :</label>
										<div class="col-sm-8 col-md-8 col-lg-9">
											<div class="text-data">{{ $arr_data['category']['name'] or 'NA' }}</div>
										</div>
									</div>
								</div>
							</div> --}}
							

							<div class="row">
								<div class="col-lg-8">
									<div class="form-group">
										<label class="control-label col-sm-4 col-md-4 col-lg-3" for="plan_description">Message :<i class="red"></i></label>
										<div class="col-sm-8 col-md-8 col-lg-9">
											<div class="text-data">{{ $arr_data['message'] or 'NA' }}</div>
										</div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-lg-8">
									<div class="form-group">
										<label class="control-label col-sm-4 col-md-4 col-lg-3" for="page_description">Reply :<i class="red">*</i></label>
										<div class="col-sm-8 col-md-8 col-lg-9">
											<textarea  name="page_description" id="page_description" class="form-control" data-rule-required="true" rows="15" tabindex="5"></textarea>
											<span class="error">{{ $errors->first('page_description') }} </span>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group text-right">
								<div class="col-lg-8">
									<button type="submit" class="btn btn-primary" id="btn_add_front_page" tabindex="6">Reply</button>
									<a href="{{ $module_url_path or '' }}" class="btn btn-primary">Back</a>
								</div>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>	
</div>
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>


<script>tinymce.init({ selector:'#page_description',valid_elements: "*[*]",force_p_newlines : false,forced_root_block : '', });</script>
<script type="text/javascript">

	$(document).ready(function()
	{
		$('#frm_contact_enquiry').validate({
			highlight: function(element) { },
			ignore: [] 
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

		$('#frm_contact_enquiry').click(function(){
			tinyMCE.triggerSave();
		});
	});
</script>
@endsection


