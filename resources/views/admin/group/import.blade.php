@extends('admin.layout.master')
<style type="text/css">
	#map {
  height: 30%;
  /*width: 100%;*/
}
/* Optional: Makes the sample page fill the window. */
html, body {
  height: 100%;
  width: 100%;
  margin: 0;
  padding: 0;
}
.highlight-error {
  border-color: red;
}
</style>
    
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

				<div class="panel-body " >
					@include('admin.layout._operation_status') 
					<form action="{{$module_url_path}}/import_file" id="frm_create_page" name="frm_create_page" class="form-horizontal cmxform" method="post" enctype="multipart/form-data">
						{{csrf_field()}}
						<section class="panel">
                    <br>
						<div class="form-group">
									<label class="col-sm-2 col-sm-2 control-label">Title<i style="color:red;">*</i></label>
									<div class="col-sm-3">
										<input type="text" id="title" name="title" value="{{old('title')}}" placeholder="Enter Title" data-rule-required="true"  
										 class="form-control" data-type="adhaar-number" >
										<span class="error" style="color: red;">{{ $errors->first('title') }} </span>
							</div>		
						</div>                    
						<div class="form-group">
									<label class="col-sm-2 col-sm-2 control-label">Import File<i style="color:red;">*</i></label>
									<div class="col-sm-3">
										<input type="file" id="file" name="file" value="{{old('file')}}" data-rule-required="true"  
										 class="form-control" data-type="adhaar-number" >
										<span class="error" style="color: red;">{{ $errors->first('file') }} </span>
							</div>		
						</div> 
				<div class="form-group">		
					<div class="col-sm-12 col-md-12 col-lg-12">
							<div class="note"><b>Note : </b>Please Below Format.</div>
							<a href="{{url('/')}}/uploads/excel_format/phone_book.xlsx" download="">Download</a>
					</div>
				</div>
					<!-- </div> -->

					</section>
						<div class="form-group">
							<div class="col-sm-8 text-right">
								<a href="{{ $module_url_path or 'NA' }}" class="btn btn-primary">Back</a>
								<button class="btn btn-primary" type="submit"  id="btn_add_front_page">Import</button>
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
<script type="text/javascript" src="{{url('/')}}/assets/admin_assets/js/bootstrap-datepicker.js"></script>
@endsection


