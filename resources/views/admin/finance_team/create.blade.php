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
						{{-- <div class="form-group">
						  <label class="col-sm-2 col-sm-2 control-label">District<i style="color:red;">*</i></label>
							<div class="col-sm-3">
							  <select name="district" data-rule-required="true" id="district"  class="form-control">
                                        <option value="" >Select district </option>
							 			@if(isset($arr_districts) && count($arr_districts)>0)
                                        @foreach($arr_districts as $districts)
                                        	<option value="{{$districts['id']}}" @if(old('district') == $districts['id']) selected="selected" @endif>{{$districts['district_name'] or ''}}</option>
                                        @endforeach
                                        @endif
                              </select>
							 <span class="error" style="color: red;">{{ $errors->first('district') }} </span>
							</div>
						</div>
						<div class="form-group">  
						  <label class="col-sm-2 col-sm-2 control-label">City<i style="color:red;">*</i></label>
							<div class="col-sm-3">

								 <select name="city" data-rule-required="true" id="city" class="form-control ">
	                                    <option value="">Select City </option>
	                                    
	                                </select>

								<span class="error" style="color: red;">{{ $errors->first('city') }} </span>
							</div>
						</div>
						<div class="form-group">  
						  <label class="col-sm-2 col-sm-2 control-label">Village<i style="color:red;">*</i></label>
							<div class="col-sm-3">

								 <select name="village" data-rule-required="true" id="village" class="form-control ">
	                                    <option value="">Select Village </option>
	                                    
	                                </select>

								<span class="error" style="color: red;">{{ $errors->first('village') }} </span>
							</div>
						</div> --}}
						
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">Ward<i style="color:red;">*</i></label>
                                    <div class="col-sm-3">
                                        {{-- <span class="input-group-addon"><i class="ti-user"></i></span> --}}
                                        <select name="ward" data-rule-required="true" id="ward" class="form-control">
                                            <option value="">Select Ward </option>
                                            @if(isset($arr_wards) && count($arr_wards)>0)
                                            @foreach($arr_wards as $wards)
                                                <option value="{{$wards['id']}}" @if(old('ward') == $wards['id']) selected="selected" @endif>{{$wards['ward_name'] or ''}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span class="error" style="color: red;">{{ $errors->first('ward') }} </span>
                                    </div>
                                </div>
                           
						  <div class="form-group">
						  <label class="col-sm-2 col-sm-2 control-label">Select Distributor<i style="color:red;">*</i></label>
							<div class="col-sm-3">
							<select name="sub_admin_id" data-rule-required="true" id="sub_admin_id"  class="form-control ">
                              	<option value="{{old('sub_admin_id')}}" >Select Member Name </option>
				 				@if(isset($arr_teams) && count($arr_teams)>0)
                            		@foreach($arr_teams as $webadmin)
                            		<option value="{{$webadmin['id']}}">{{$webadmin['first_name'] or ''}} {{$webadmin['last_name'] or ''}}</option>
                            		@endforeach
                            	@endif
          					</select>
          				</div>
						</div>
						
						
						<div class="form-group">
							<div class="col-sm-4 text-right">
								<a href="{{ $module_url_path or 'NA' }}" class="btn btn-primary">Back</a>
								<button class="btn btn-primary" type="submit"  id="btn_add_front_page">Add</button>
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

	});



</script>

@endsection


