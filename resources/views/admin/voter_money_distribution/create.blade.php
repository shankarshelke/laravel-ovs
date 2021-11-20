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
						<label class="col-sm-2 col-sm-2 control-label" for="user_id">Select Voter<i class="red">*</i></label>
							<div class="col-sm-3">
								<select name="user_id" data-rule-required="true" id="user_id"  class="form-control ">
                                  	<option value="{{old('user_id')}}" >Select Voter </option>
					 				@if(isset($arr_voter_team) && count($arr_voter_team)>0)
                                		@foreach($arr_voter_team as $users)
                                		<option value="{{$users['id']}}">{{$users['first_name'] or ''}}  {{$users['last_name'] or ''}}{{-- @if($users['recieved_count']!==0)<b style="color: red">{{'('.$users['recieved_count'].')'}}</b>@endif --}}</option>
                                		@endforeach
                                	@endif
              					</select>
              				</div>
              				<input type="hidden" name="remaining_balance" value="{{$remaining_balance=$admin_money-$voter_money}}">	
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


