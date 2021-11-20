@extends('admin.layout.master')    
@section('main_content')
<!--body wrapper start-->
<div class="wrapper">
	<div class="row">
		<div class="col-md-12">
			@include('admin.layout.breadcrumb')
			<script type="text/javascript" src="{{ url('/') }}/assets/admin_assets/js/bootstrap-datepicker.min.js"></script>
			<link rel="stylesheet" href="{{ url('/') }}/assets/admin_assets/css/bootstrap-datepicker3.css" />  
			<section class="panel">
				<header class="panel-heading">
					Create List
				</header>

				<div class="panel-body">
					@include('admin.layout._operation_status') 
					<form action="{{$module_url_path}}/store_list" id="frm_blogs_page" name="frm_blogs_page" class="form-horizontal cmxform" method="post">
						{{csrf_field()}}
					    {{-- <div class="form-group">
    						<label class="col-sm-2 col-sm-2 control-label">District<i style="color:red;">*</i></label>
						    <div class="col-sm-3">
    						    <select name="district" data-rule-required="true" id="district"  class="form-control ">
                                    <option value="" >Select district </option>
    					 			@if(isset($arr_districts) && count($arr_districts)>0)
                                    @foreach($arr_districts as $districts)
                                    	<option value="{{$districts['id']}}" @if(old('district') == $districts['id']) selected="selected" @endif>{{$districts['district_name'] or ''}}</option>
                                    @endforeach
                                    @endif
                                </select>
						        <span class="error" style="color: red;">{{ $errors->first('district') }} </span>
						    </div>		  
    						<label class="col-sm-2 col-sm-2 control-label">City<i style="color:red;">*</i></label>
							<div class="col-sm-3">

								 <select name="city" data-rule-required="true" id="city" class="form-control ">
                                        <option value="">Select City </option>
                                        
                                    </select>

								<span class="error" style="color: red;">{{ $errors->first('city') }} </span>
							</div>
						</div> --}}
						{{-- <div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Town/Village<i style="color:red;">*</i></label>
							<div class="col-sm-3">
								<select name="village" data-rule-required="true" id="village" class="form-control">
                                        <option value="">Select Town/Village </option>
           
                                    </select>
								<span class="error" style="color: red;">{{ $errors->first('village') }} </span>
							</div>
                        </div> --}}
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Ward<i style="color:red;">*</i></label>
                            <div class="col-sm-3">
                                <select name="ward" data-rule-required="true" id="ward"  class="form-control ">
                                        <option value="" >Select ward </option>
                                        @if(isset($arr_ward) && count($arr_ward)>0)
                                        @foreach($arr_ward as $wards)
                                            <option value="{{$wards['id']}}" @if(old('ward') == $wards['id']) selected="selected" @endif>{{$wards['ward_name'] or ''}}</option>
                                        @endforeach
                                        @endif
                                </select>
                                <span class="error" style="color: red;">{{ $errors->first('ward') }} </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Booth<i style="color:red;">*</i></label>
                            <div class="col-sm-3">
                                <select name="booth" data-rule-required="true" id="booth" class="form-control">
                                    <option value="">Select Booth </option>
                                </select>
                                <span class="error" style="color: red;">{{ $errors->first('booth') }} </span>
                            </div>
                        </div>

						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">List no<i style="color:red;">*</i></label>
							<div class="col-sm-3">
								<input type="text" id="list_no"  name="list_no"  data-rule-required="true" value="{{old('list_no')}}" class="form-control " maxlength="10" data-rule="true">
								<span class="error" style="color: red;">{{ $errors->first('list_no') }} </span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">List Name<i style="color:red;">*</i></label>
							<div class="col-sm-3">
								<input id="list_name"  name="list_name" value="{{old('list_name')}}"  data-rule-required="true"  class="form-control"  maxlength="100" >

								<span class="error" style="color: red;">{{ $errors->first('list_name') }} </span>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-5 text-right">
								<a href="{{ $module_url_path or 'NA' }}/manage_list" class="btn btn-primary">Back</a>
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

<script type="text/javascript">
	$(document).ready(function(){
		$('#frm_blogs_page').validate();
		});

</script>
<script type="text/javascript">
	$(document).ready(function() 
	{
        $('#ward').change(function()
        {
            var ward_id = $('#ward').val();
            if(ward_id!='')
            { 
                var url = '{{$module_url_path}}/get_booths';
                var csrf_token      = '{{csrf_token()}}';
                $.ajax({
                    type:'POST',
                    url: url,
                    data:{ward_id:ward_id,_token:'{{csrf_token()}}'},
                    success:function(resp)
                    {
                        $('#booth').html(resp);
                    }
                });
            }
        });
	});
</script>
@endsection
