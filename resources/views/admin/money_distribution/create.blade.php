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
					  	<label class="col-sm-2 col-sm-2 control-label">Select Distributor<i style="color:red;">*</i></label>
						<div class="col-sm-3">
						<select name="subadmin_id" data-rule-required="true" id="subadmin_id"  class="form-control ">
                      		<option value="{{old('finance_team_id')}}" >Select Distributor </option>
			 				@if(isset($arr_finance_team) && count($arr_finance_team)>0)
                        		@foreach($arr_finance_team as $finance_team)
                        		<option value="{{$finance_team['subadmin_id']}}">{{$finance_team['get_admin_details']['first_name'] or ''}} {{$finance_team['get_admin_details']['last_name'] or ''}}</option>
                        		@endforeach
                        	@endif
      					</select>
      					</div>
					</div>
					<div class="form-group">
					  	<label class="col-sm-2 col-sm-2 control-label">Enter Amount<i style="color:red;">*</i></label>
						<div class="col-sm-3">
							<input type="integer" id="amount" name="amount" value="{{old('amount')}}" data-rule-required="true"  
							 class="form-control"maxlength="14">
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


