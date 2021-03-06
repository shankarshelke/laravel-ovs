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
					{{$sub_module_title or ''}}
				</header>

				<div class="panel-body">
					@include('admin.layout._operation_status') 
					<form action="{{$module_url_path}}/store" id="frm_blogs_page" name="frm_blogs_page" class="form-horizontal cmxform" method="post">
						{{csrf_field()}}
						<div class="form-group">
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
						</div>
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Town/Village<i style="color:red;">*</i></label>
							<div class="col-sm-3">
								<select name="village" data-rule-required="true" id="village" class="form-control">
                                        <option value="">Select Town/Village </option>
           
                                    </select>
								<span class="error" style="color: red;">{{ $errors->first('village') }} </span>
							</div>

						</div>
					
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Wards No.<i style="color:red;">*</i></label>
							<div class="col-sm-3">
								<input type="text" id="ward_no" name="ward_no"  data-rule-required="true" data-rule-number=???true??? 
								 class="form-control" maxlength="10">
								<span class="error" style="color: red;">{{ $errors->first('ward_no') }} </span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label"> Wards Name<i style="color:red;">*</i></label>
							<div class="col-sm-3">
								<input type="text" id="ward_name"  name="ward_name"  data-rule-required="true" data-rule-lettersonly="true" class="form-control " maxlength="40" >
								<span class="error" style="color: red;">{{ $errors->first('ward_name') }} </span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Address<i style="color:red;">*</i></label>
							<div class="col-sm-6">
								<textarea id="ward_address"  name="ward_address"  data-rule-required="true"  class="form-control " maxlength="255" rows="6"></textarea>

								<span class="error" style="color: red;">{{ $errors->first('ward_address') }} </span>
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

<script type="text/javascript">
	$(document).ready(function(){
		$('#frm_blogs_page').validate();
		});
</script>
<script type="text/javascript">
	$(document).ready(function() 
	{
	
		    $('#district').change(function(){

            var district_id = $('#district').val();
            
            if(district_id!='')
            {
                var url = '{{$module_url_path}}/get_cities';
                var csrf_token      = '{{csrf_token()}}';

                $.ajax({
                    type:'POST',
                    url: url,
                    data:{district_id:district_id,_token:'{{csrf_token()}}'},

                    success:function(resp){
                        $('#city').html(resp);
                    }
                });
            }
        });

        $('#city').change(function(){

            
            var district_id = $('#district').val();
            var city_id = $('#city').val();
            if(district_id!='' && city_id!='')
            {
                var url = '{{$module_url_path}}/get_villages';
                var csrf_token      = '{{csrf_token()}}';

                $.ajax({
                    type:'POST',
                    url: url,
                    data:{district_id:district_id,city_id:city_id,_token:'{{csrf_token()}}'},
                    
                    success:function(resp){
                        $('#village').html(resp);
                    }
                });
            }
        });
	});
	



</script>

<!-- Script for Image validation -->

@endsection


