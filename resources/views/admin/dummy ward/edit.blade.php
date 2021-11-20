
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
					<form action="{{$module_url_path}}/update/{{ $enc_id or '' }}" id="frm_blogs_page" name="frm_blogs_page" class="form-horizontal cmxform" method="post" enctype="multipart/form-data">
						{{csrf_field()}}
						{{-- {{dd($arr_data)}} --}}

						<div class="form-group">
						  <label class="col-sm-2 col-sm-2 control-label">District<i style="color:red;">*</i></label>
							<div class="col-sm-3">
							  <select name="district" data-rule-required="true" id="district"  class="form-control ">
                                        <option value="" >Select district </option>
							 			@if(isset($arr_districts) && count($arr_districts)>0)
                                        @foreach($arr_districts as $districts)
                                        	<option @if($districts['id']==$arr_data['district']) selected="true" @endif value="{{$districts['id']}}">{{$districts['district_name'] or ''}}</option>
                                        @endforeach
                                        @endif
                              </select>
							 <span class="error" style="color: red;">{{ $errors->first('district') }} </span>
							</div>
						  
						  <label class="col-sm-2 col-sm-2 control-label">City<i style="color:red;">*</i></label>
							<div class="col-sm-3">

								 <select name="city" data-rule-required="true" id="city"class="form-control ">
                                        <option value="">Select City </option>
                                        @if(isset($arr_city) && count($arr_city)>0)
                                        @foreach($arr_city as $cities)
                                        	<option @if($cities['id']==$arr_data['city']) selected="true" @endif value="{{$cities['id']}}">{{$cities['city_name'] or ''}}</option>
                                        @endforeach
                                        @endif
                                    </select>
								<span class="error" style="color: red;">{{ $errors->first('city') }} </span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Town/Village<i style="color:red;">*</i></label>
							<div class="col-sm-3">
								<select name="village" data-rule-required="true" id="village" class="form-control">
                                        <option value="">Select Town/City </option>
                                    @if(isset($arr_village) && count($arr_village)>0)
                                        @foreach($arr_village as $villages)
                                        	<option @if($villages['id']==$arr_data['village_id']) selected="true" @endif value="{{$villages['id']}}">{{$villages['village_name'] or ''}}</option>
                                        @endforeach
                                        @endif</select>
								<span class="error">{{ $errors->first('village') }} </span>
							</div>
					
				
					<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Ward No<i style="color:red;">*</i></label>
							<div class="col-sm-2">
								<input type="text" id="ward_no" name="ward_no"  data-rule-required="true" data-rule-number=”true”  class="form-control" value="{{ $arr_data['ward_no'] or 'NA' }}" >
								<span class="error">{{ $errors->first('ward_no') }} </span>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Ward Name<i style="color:red;">*</i></label>
							<div class="col-sm-2">
								<input type="text" id="ward_name"  name="ward_name"  data-rule-required="true"  class="form-control "value="{{ $arr_data['ward_name'] or 'NA' }}">
								<span class="error">{{ $errors->first('ward_name') }} </span>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">Address<i style="color:red;">*</i></label>
							<div class="col-sm-6">
								<textarea id="ward_address"  name="ward_address"  data-rule-required="true"  class="form-control" rows="4">{{ $arr_data['ward_address'] or 'NA' }}</textarea>
								<span class="error">{{ $errors->first('ward_address') }} </span>
							</div>
							
						</div>
					
					

						<div class="form-group">
							<div class="col-sm-8 text-right">
								<a href="{{ $module_url_path or 'NA' }}" class="btn btn-primary">Back</a>
								<button class="btn btn-primary" type="submit"  id="btn_add_front_page">Update</button>
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

       





</script>

<!-- Script for Image validation -->

@endsection



	


