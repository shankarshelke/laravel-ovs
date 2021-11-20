@extends('admin.layout.master')    
@section('main_content')
<style>
    section .panel-heading {padding: 0 0 10px;}
</style>
<div class="wrapper">
    <div class="row">
        <div class="col-md-12">
            @include('admin.layout.breadcrumb')  
	        <div class="panel ">
		        @include('admin.layout._operation_status')
		        <div class="panel-body">		            
				    <section>
                        <header class="panel-heading">
                           {{ trans('myteam.Myteam Details') }}       
                        </header>
                        <br>
						<div class="table-responsive">							 
							<table class="table table-bordered table-striped table-hover">
								<tbody>									
									<tr>
										<th style="width: 10%;">{{ trans('myteam.First Name') }}</th>
										<td style="width: 60%;">{{$arr_user['first_name'] or 'NA'}} </td>
									</tr>
									<tr>
										<th style="width: 10%;">{{ trans('myteam.Last Name') }}</th>
										<td style="width: 60%;">{{$arr_user['last_name'] or 'NA'}}</td>
									</tr>
									<tr>
										<th style="width: 10%;">{{ trans('myteam.Email') }}</th>
										<td style="width: 60%;">{{ $arr_user['email'] or 'NA'}}</td>
									</tr>
									<tr>
										<th style="width: 10%;">{{ trans('myteam.Mobile No') }}</th>
										<td style="width: 60%;">{{$arr_user['contact'] or 'NA'}}</td>
									</tr>
									<tr>
										<th style="width: 10%;">{{ trans('myteam.Address') }}</th>
										<td style="width: 60%;">{{$arr_user['address'] or 'NA'}}</td>
									</tr>
									
									<tr>
										<th style="width: 10%;">{{ trans('myteam.Role') }}</th>
										<td style="width: 60%;">{{$arr_user['role'] or 'NA'}}</td>
									</tr>
									<tr>
										<th style="width: 10%;">{{ trans('myteam.status') }}</th>
										<td style="width: 60%;">
											@if(isset($arr_user['status']) && $arr_user['status']=='1')
												<a class='text-success'>{{ trans('myteam.active') }}</a>	
											@else
													<a class='text-danger'>{{ trans('myteam.inactive') }}</a>	
											@endif
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</section>
                    <div class="form-group">							                    
                        <a class="btn btn-primary back-btn" title="Back" href="{{$module_url_path}}">{{ trans('myteam.back') }}</a>
                    </div>                 
                </div>                
            </div>		
		</div>
	</div>
</div>

	@endsection


