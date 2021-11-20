
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
					<form action="{{$module_url_path}}/update_permissions/{{ $id or '' }}" id="frm_blogs_page" name="frm_blogs_page" class="form-horizontal cmxform" method="post" enctype="multipart/form-data">
						{{csrf_field()}}
						{{-- {{dd($arr_data)}} --}}
						<fieldset class="content-group">    
					@if(isset($arr_modules) && is_array($arr_modules) && sizeof($arr_modules)>0)
					<div class="row">
						<div class="col-lg-12">
						<div class="form-group">
							<label class="col-sm-4 col-md-4 col-lg-2 control-label" for="page_title">{{ trans('myteam.Permissions') }} <i class="red">*</i></label>
							<div class="col-sm-8 col-md-8 col-lg-9 controls">
								<div class="box-content table-responsive">
									<table class="table table-bordered custome-table">
										<thead>
											<tr>
												<th>{{ trans('myteam.Module Name') }}</th>
												<th>{{ trans('myteam.Module View') }}</th>
												<th>{{ trans('myteam.List/View') }}</th>
												<th>{{ trans('myteam.Create/Add') }}</th>
												<th>{{ trans('myteam.Edit/Update') }}</th>
												<th>{{ trans('myteam.Delete/Remove') }}</th>
												<th>{{ trans('myteam.Active/Inactive') }}</th>
												<th>{{ trans('myteam.Permissions') }}</th>

											</tr>
										</thead>
										<tbody>
											<tr>
												<td>All</td>
												<td><input type="checkbox" name="" class="all_module_view"></td>
												<td><input type="checkbox" name="" class="all_view"></td>
												<td><input type="checkbox" name="" class="all_create"></td>
												<td><input type="checkbox" name="" class="all_edit"></td>
												<td><input type="checkbox" name="" class="all_delete"></td>
												<td><input type="checkbox" name="" class="all_approve"></td>
												<td><input type="checkbox" name="" class="all_permission"></td>
											</tr>
											@foreach($arr_modules as $module)
											<tr>
												<td>{{$module['module_name'] or ''}}</td>
												<td>
													@if($module['is_module_view'] == 'YES')
													<?php
														$slug = $module['module_slug'];
														$checked = '';
														if(array_key_exists($module['module_slug'], $arr_abilities) && in_array('module_view', $arr_abilities[$slug])){
															$checked = 'checked';
														}
													?>
													<label>
													<input type="checkbox" value="module_view" name="permissions[{{$module['module_slug']}}][]" {{$checked}} class="module_view">
													</label>
													@endif
												</td>
												<td>
													@if($module['is_view'] == 'YES')
													<?php
														$slug = $module['module_slug'];
														$checked = '';
														if(array_key_exists($module['module_slug'], $arr_abilities) && in_array('view', $arr_abilities[$slug])){
															$checked = 'checked';
														}
													?>
													<label>
													<input type="checkbox" value="view" name="permissions[{{$module['module_slug']}}][]" {{$checked}} class="view">
													</label>
													@endif
												</td>
												<td>
													@if($module['is_create'] == 'YES')
													<?php
														$slug = $module['module_slug'];
														$checked = '';
														if(array_key_exists($module['module_slug'], $arr_abilities) && in_array('create', $arr_abilities[$slug])){
															$checked = 'checked';
														}
													?>
													<input type="checkbox" value="create" name="permissions[{{$module['module_slug']}}][]" {{$checked}} class="create">
													@endif
												</td>
												<td>
													@if($module['is_edit'] == 'YES')
													<?php
														$slug = $module['module_slug'];
														$checked = '';
														if(array_key_exists($module['module_slug'], $arr_abilities) && in_array('edit', $arr_abilities[$slug])){
															$checked = 'checked';
														}
													?>
													<input type="checkbox" value="edit" name="permissions[{{$module['module_slug']}}][]" {{$checked}} class="edit">
													@endif
												</td>
												<td>
													@if($module['is_delete'] == 'YES')
													<?php
														$slug = $module['module_slug'];
														$checked = '';
														if(array_key_exists($module['module_slug'], $arr_abilities) && in_array('delete', $arr_abilities[$slug])){
															$checked = 'checked';
														}
													?>
													<input type="checkbox" value="delete" name="permissions[{{$module['module_slug']}}][]" {{$checked}} class="delete">
													@endif
												</td>
												<td>
													@if($module['is_approved'] == 'YES')
												    <?php
														$slug = $module['module_slug'];
														$checked = '';
														if(array_key_exists($module['module_slug'], $arr_abilities) && in_array('approve', $arr_abilities[$slug])){
															$checked = 'checked';
														}
													?>
													<input type="checkbox" value="approve" name="permissions[{{$module['module_slug']}}][]" {{$checked}} class="approve">
													@endif
												</td>
												<td>
													@if($module['permissions'] == 'YES')
												    <?php
														$slug = $module['module_slug'];
														$checked = '';
														if(array_key_exists($module['module_slug'], $arr_abilities) && in_array('permission', $arr_abilities[$slug])){
															$checked = 'checked';
														}
													?>
													<input type="checkbox" value="permission" name="permissions[{{$module['module_slug']}}][]" {{$checked}} class="permissions">
													@endif
												</td>                                                    
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
						@endif
						</div>
					</div>

					<div class="cleafix"></div>
					<div class="form-group text-right">
						<div class="col-lg-11">
							<button type="submit" class="btn btn-primary">Update</button>
						</div>
					</div>
				</fieldset>
			
					</form>	
				</div>
			</section>
		</div>				
	</div>
</div>					
<!--body wrapper end-->

<script src="{{url('/')}}/assets/admin_assets/js/bootstrap-inputmask.min.js"></script>
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>

 <script type="text/javascript">

    $(document).ready(function() 
    { 
        jQuery('#form-roles').validate({
            ignore: [],
        });

        $('.all_module_view').click(function()
        {
        	if($(this).is(':checked'))
        	{
        		$('.module_view').prop('checked', true);
        	}else{
				$('.module_view').prop('checked', false);
        	}
        });

        $('.all_view').click(function()
        {
        	if($(this).is(':checked'))
        	{
        		$('.view').prop('checked', true);
        	}else{
				$('.view').prop('checked', false);
        	}
        });

        $('.all_create').click(function()
        {
        	if($(this).is(':checked'))
        	{
        		$('.create').prop('checked', true);
        	}else{
				$('.create').prop('checked', false);
        	}
        });

        $('.all_edit').click(function()
        {
        	if($(this).is(':checked'))
        	{
        		$('.edit').prop('checked', true);
        	}else{
				$('.edit').prop('checked', false);
        	}
        });

        $('.all_delete').click(function()
        {
        	if($(this).is(':checked'))
        	{
        		$('.delete').prop('checked', true);
        	}else{
				$('.delete').prop('checked', false);
        	}
        });

        $('.all_approve').click(function()
        {
        	if($(this).is(':checked'))
        	{
        		$('.approve').prop('checked', true);
        	}else{
				$('.approve').prop('checked', false);
        	}
        });

        $('.all_permission').click(function()
        {
        	if($(this).is(':checked'))
        	{
        		$('.permissions').prop('checked', true);
        	}else{
				$('.permissions').prop('checked', false);
        	}
        });
		// $('.module_view').click(function(e)
  //       {
  //       	if($(this).is(':checked'))
  //       	{
  //       		$('.view').show();
  //       		$('.create').show();
  //       		$('.edit').show();
  //       		$('.delete').show();
  //       		$('.approve').show();
  //       	}else{
  //       		$('.view').hide();
  //       		$('.create').hide();
  //       		$('.edit').hide();
  //       		$('.delete').hide();
  //       		$('.approve').hide();
  //       	}
  //       });

		// $(document).ready(function(){
		//   $("#hide").click(function(){
		//     $("#paragraph").hide();
		//   });
		//   $("#show").click(function(){
		//     $("#paragraph").show();
		//   });
		// });

    });

</script>
@endsection


