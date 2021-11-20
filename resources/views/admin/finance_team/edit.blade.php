
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
						  {{-- <div class="form-group">
						  	<label class="col-sm-2 col-sm-2 control-label">District<i style="color:red;">*</i></label>
							<div class="col-sm-3">
						  		<select name="district" data-rule-required="true" id="district"  class="form-control ">
                                    <option value="" >Select district </option>
						 			@if(isset($arr_districts) && count($arr_districts)>0)
                                    	@foreach($arr_districts as $districts)
                                    		<option @if($districts['id']==$arr_data['district_id']) selected="true" @endif value="{{$districts['id']}}">{{$districts['district_name'] or ''}}</option>
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
	                                    @foreach($arr_city as $city)
                                    		<option @if($city['id']==$arr_data['city_id']) selected="true" @endif value="{{$city['id']}}">{{$city['city_name'] or ''}}</option>
                                    	@endforeach
	                                    
	                                </select>

								<span class="error" style="color: red;">{{ $errors->first('city') }} </span>
								</div>
						</div>

						<div class="form-group">  
						  	<label class="col-sm-2 col-sm-2 control-label">Village<i style="color:red;">*</i></label>
								<div class="col-sm-3">

								 	<select name="village" data-rule-required="true" id="village" class="form-control ">
	                                    @foreach($arr_village as $village)
                                    		<option @if($village['id']==$arr_data['village_id']) selected="true" @endif value="{{$village['id']}}">{{$village['village_name'] or ''}}</option>
                                    	@endforeach
	                                    
	                                </select>
	                                
								<span class="error" style="color: red;">{{ $errors->first('village') }} </span>
								</div>
						</div> --}}
						
                           
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">Ward<i style="color:red;">*</i></label>
                                    <div class="col-sm-3">
                                        {{-- <span class="input-group-addon"><i class="ti-menu-alt"></i></span> --}}
                                        <select name="ward" data-rule-required="true" id="ward" class="form-control">
                                            <option value="">Select Ward </option>
                                            @if(isset($arr_ward) && count($arr_ward)>0)
                                            @foreach($arr_ward as $wards)
                                            <option @if($wards['id']==$arr_data['ward']) selected="true" @endif value="{{$wards['id']}}">{{$wards['ward_name'] or ''}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span class="error">{{ $errors->first('ward') }} </span>
                                    </div>
                                </div>
                    
                     
						<div class="form-group">  
						  	<label class="col-sm-2 col-sm-2 control-label">Select Ditributor<i style="color:red;">*</i></label>
								<div class="col-sm-3">

								 	<select name="subadmin_id" data-rule-required="true" id="subadmin_id" class="form-control ">
	                                    @foreach($arr_accountant as $accountant)
                                    		<option @if($accountant['id']==$arr_data['subadmin_id']) selected="true" @endif value="{{$accountant['id']}}">{{$accountant['first_name'] or ''}} {{$accountant['last_name'] or ''}}</option>
                                    	@endforeach
	                                    
	                                </select>
	                                
								<span class="error" style="color: red;">{{ $errors->first('subadmin_id') }} </span>
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



	


