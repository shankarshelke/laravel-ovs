@extends('admin.layout.master')    
@section('main_content')
<!--body wrapper start-->
<div class="wrapper">
	<div class="row">
		<div class="col-md-12">
			@include('admin.layout.breadcrumb')  
			<section class="panel">
				<header class="panel-heading">
					{{$sub_module_title or ''}}
				</header>

				<div class="panel-body">
					@include('admin.layout._operation_status') 
					<form action="{{$module_url_path}}/store" id="frm_blogs_page" name="frm_blogs_page" class="form-horizontal cmxform" method="post" enctype="multipart/form-data">
						{{csrf_field()}}
						<div class="form-group last">
							<label class="col-sm-2 col-sm-2 control-label">Image Upload</label>
							<div class="col-md-9">
								<div class="fileupload fileupload-new" data-provides="fileupload">
									<div class="fileupload-new thumbnail " style="width: 200px; height: 150px;">
										<img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image">
									</div>
									<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 10px;">
									</div>
									<div>
										<span class="btn btn-default btn-file">
											<span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select image</span>
											<span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
											<input type="file" data-validation-allowing="jpg, png, gif" class="default file-input news-image validate-image" data-rule-required="true" name="image" id="image"  class="">
										</span>
										<a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> Remove</a>
									</div>
								</div>
								<label id="image-error" class="error" for="image"></label>
								<br>
								<span class="label label-danger ">NOTE!</span>
								<span>
									Only jpg, png, jpeg file are allowed.!!
									Image size should be less than 2 mb.!!
								</span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Title</label>
							<div class="col-sm-6">
								<input type="text" id="title" name="title"  data-rule-required="true" class="form-control" >
								<span class="error">{{ $errors->first('title') }} </span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Short Description</label>
							<div class="col-sm-6">
								<input type="text" id="short_description"  name="short_description"  data-rule-required="true"  class="form-control ">
								<span class="error">{{ $errors->first('short_description') }} </span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Blog Content</label>
							<div class="col-sm-6">
								<textarea  name="description" id="description" class="form-control" data-rule-required="true" rows="15" tabindex="1"></textarea>
								<span class="error">{{ $errors->first('description') }} </span>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-8 text-right">
								<a href="{{ $module_url_path or 'NA' }}" class="btn btn-primary">Back</a>
								<button class="btn btn-primary" type="submit"  id="btn_add_front_page">Create</button>
							</div>
						</div>
					</form>
				</div>
			</section>
		</div>
	</div>
</div>
<!--body wrapper end-->

<script src="{{url('/')}}/assets/admin_assets/js/bootstrap-inputmask.min.js"></script>
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>


	$(document).ready(function()
	{
		$('#frm_blogs_page').validate({
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

		$('#btn_add_front_page').click(function(){
			tinyMCE.triggerSave();
		});
	});
</script>
<!-- Script for Image validation -->
<script type="text/javascript">
	$(document).ready(function() 
	{
		var e = document.getElementById("image"),
		r = $("#default-image").val();
		$(e).change(function() 
		{
			if (e.files && e.files[0]) 
			{ 
				var a = e.files,
				t = a[0].name.substring(a[0].name.lastIndexOf(".") + 1),
				n = new FileReader;
				if ("JPEG" != t && "jpeg" != t && "jpg" != t && "JPG" != t && "png" != t && "PNG" != t) 
					return showAlert("Sorry, " + a[0].name + " is invalid, allowed extensions are: jpeg , jpg , png", "error"),$("#image").val(""), $(".fileupload-preview").attr("src", r), !1;
				if (a[0].size > 2e6) return showAlert("Sorry, " + a[0].name + " is invalid, Image size should be upto 2 MB only", "error"), $("#image").val(""), $(".fileupload-preview").attr("src", r), !1;
				n.onload = function(e) 
				{
					var a = new Image;
					a.src = e.target.result, a.onload = function() 
					{ 
						var e = this.height,
						a = this.width;
						if (e < 1000 || a < 1000) 
							return showAlert("Sorry,Please upload image with Height and Width greater than or equal to 1000 X 1000 for best result", "error"), $("#image").val(""), $(".fileupload-preview").attr("src", r), !1
					}, $(".fileupload-preview").attr("src", e.target.result)
				}, n.readAsDataURL(e.files[0])
			}
		})
	});
</script>

@endsection


