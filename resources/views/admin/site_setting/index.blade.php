<!--body wrapper start-->
<style>
	.error{color: red;}
	.red{color: red;}
</style>
<div class="wrapper">
	<div class="row">
		<div class="col-md-12">
			@include('admin.layout.breadcrumb')  
			<section class="panel">
				@include('admin.layout._operation_status') 
				<header class="panel-heading custom-tab dark-tab">
					<ul class="nav nav-tabs">
						<li class="active">
							<a href="#site_setting" data-toggle="tab" aria-expanded="true">{{ trans('sitesetting.site settings') }}</a>
						</li>
						<li class="">
							<a href="#bank_details" data-toggle="tab" aria-expanded="false">{{ trans('sitesetting.bank details') }}</a>
						</li>
						<li class="">
							<a href="#social_links" data-toggle="tab">{{ trans('sitesetting.social links') }}</a>
						</li>
					</ul>
				</header>
				<div class="panel-body">
					<div class="tab-content">
						<div class="tab-pane active" id="site_setting">
							<form action="{{$module_url_path}}/update" id="frm_gener_setting" name="frm_gener_setting" class="cmxform" method="post" enctype="multipart/form-data">
				                {{csrf_field()}}
								<div class="row">
									<div class="col-sm-12 col-md-12 col-lg-6">
										<div class="form-group">
											<label class="control-label">{{ trans('sitesetting.site name') }}<i class="red">*</i></label>
											<div class="input-group">
											    <span class="input-group-addon"><i class="ti-user"></i></span>
												<input type="text" name="site_name" id="site_name" onkeyup="chk_validation(this)" class="form-control" placeholder="Site Name" data-rule-required="true" data-rule-maxlength="100" value="{{$arr_site_settings['site_name'] or ''}}">
												<span class="error">{{ $errors->first('site_name') }} </span>
											</div>
										</div>
									</div>	
									<div class="col-sm-12 col-md-12 col-lg-6">
										<div class="form-group">
											<label class="control-label">{{ trans('sitesetting.address') }}<i class="red">*</i></label>
											<div class="input-group">
											    <span class="input-group-addon"><i class="ti-location-pin"></i></span>
												<input type="text" name="site_address" id="site_address" class="form-control" placeholder="Address" data-rule-required="true" data-rule-maxlength="500" value="{{$arr_site_settings['site_address'] or ''}}">
												<span class="error">{{ $errors->first('site_address') }} </span>
											</div>
										</div>
									</div>	
								</div>

								<div class="row">
									<div class="col-sm-12 col-md-12 col-lg-6">
										<div class="form-group">
											<label class="control-label">{{ trans('sitesetting.latitude') }}<i class="red">*</i></label>
											<div class="input-group">
											    <span class="input-group-addon"><i class="ti-location-pin"></i></span>
												<input type="text" name="latitude" id="latitude"  class="form-control" placeholder="Latitude" data-rule-number="true" data-rule-required="true" value="{{$arr_site_settings['lat'] or ''}}">
												<span class="error">{{ $errors->first('latitude') }} </span>
											</div>
										</div>
									</div>	
									<div class="col-sm-12 col-md-12 col-lg-6">
										<div class="form-group">
											<label class="control-label">{{ trans('sitesetting.longitude') }}<i class="red">*</i></label>
											<div class="input-group">
											    <span class="input-group-addon"><i class="ti-location-pin"></i></span>
												<input type="text" name="longitude" id="longitude"  class="form-control" placeholder="Longitude" data-rule-number="true" data-rule-required="true" value="{{$arr_site_settings['lon'] or ''}}">
												<span class="error">{{ $errors->first('longitude') }} </span>
											</div>
										</div>
									</div>	
								</div>
								<div class="row">
									<div class="col-sm-12 col-md-12 col-lg-6">
										<div class="form-group">
											<label class="control-label">{{ trans('sitesetting.email') }}<i class="red">*</i></label>
											<div class="input-group">
								                <span class="input-group-addon"><i class="ti-email"></i></span>
												<input type="email" name="site_email" id="site_email" class="form-control" placeholder="Site Email" data-rule-required="true" data-rule-email="true" value="{{$arr_site_settings['site_email_address'] or ''}}">
												<span class="error">{{ $errors->first('site_email') }} </span>
											</div>
										</div>
									</div>
									<div class="col-sm-12 col-md-12 col-lg-6">
										<div class="form-group">
											<label class="control-label">{{ trans('sitesetting.contact number') }}<i class="red">*</i></label>
											<div class="input-group">                                                
                                                <span class="input-group-addon"><i class="icon-phone"></i></span>
                                                <input type="text" name="site_contact_number" id="site_contact_number" class="form-control" placeholder="Site Contact Number" data-rule-number="true" data-rule-required="true" value="{{$arr_site_settings['site_contact_number'] or ''}}">
                                                <span class="error">{{ $errors->first('site_contact_number') }} </span>                                                
                                            </div>
										</div>
									</div>	
								</div>
								<div class="row">
									<div class="col-sm-12 col-md-12 col-lg-6">
										<div class="form-group">
											<label class="control-label">{{ trans('sitesetting.site status') }} <i class="red">*</i></label>
											<div class="input-group">
												<label class="radio-inline">
													<input name="site_status" class="styled" required="" value="1" type="radio" @if(isset($arr_site_settings['site_status']) && $arr_site_settings['site_status']==1) checked="" @endif>{{ trans('sitesetting.online') }} 
												</label>
												<label class="radio-inline">
													<input name="site_status" class="styled" value="0" type="radio" @if(isset($arr_site_settings['site_status']) && $arr_site_settings['site_status']==0) checked="" @endif> {{ trans('sitesetting.offline') }}
												</label>
												<div class="error err_site_status" id="siteStatusErrorDiv"></div>
											</div>
										</div>
									</div>
									<div class="col-sm-12 col-md-12 col-lg-6">
										<div class="form-group">
											<label class="control-label">{{ trans('sitesetting.meta title') }}<i class="red">*</i></label>
											<div class="input-group">
								                <span class="input-group-addon"><i class="ti-user"></i></span>
												<input type="text" name="meta_title" id="meta_title" class="form-control" placeholder="Meta Title" class="form-control" data-rule-required="true" data-rule-maxlength="100"  value="{{$arr_site_settings['meta_title'] or ''}}">
												<span class="error">{{ $errors->first('meta_title') }} </span>
											</div>
                                            <span><small>{{ trans('sitesetting.maximum 100 characters allowed') }}</small></span>
										</div>
									</div>	
								</div>
								<div class="row">
									<div class="col-sm-12 col-md-12 col-lg-6">
										<div class="form-group">
											<label class="control-label">{{ trans('sitesetting.meta keyword') }}<i class="red">*</i></label>
											<div class="input-group">
								                <span class="input-group-addon"><i class="ti-user"></i></span>
												<input type="text" name="meta_keyword" id="meta_keyword" class="form-control" placeholder="Meta Keyword" class="form-control" data-rule-required="true" data-rule-maxlength="255"  value="{{$arr_site_settings['meta_keyword'] or ''}}">
												<span class="error">{{ $errors->first('meta_keyword') }} </span>
											</div>
                                            <span><small>{{ trans('sitesetting.maximum 255 characters allowed') }}.</small></span>
										</div>
									</div>	
									<div class="col-sm-12 col-md-12 col-lg-6">
										<div class="form-group">
											<label class="control-label">{{ trans('sitesetting.meta description') }}<i class="red">*</i></label>
											<div class="input-group">
											    <span class="input-group-addon"><i class="ti-user"></i></span>
												<input type="text" name="meta_description" id="meta_description" class="form-control" placeholder="Meta Description" data-rule-required="true" data-rule-maxlength="500" value="{{$arr_site_settings['meta_desc'] or ''}}">
												<span class="error">{{ $errors->first('meta_description') }} </span>
											</div>
                                            <span><small>{{ trans('sitesetting.maximum 500 characters allowed') }}</small></span>
										</div>
									</div>	
									<div class="col-sm-12 col-md-12 col-lg-6">
										<div class="form-group">
											<label class="control-label">{{ trans('sitesetting.commission rate') }}<i class="red">*</i></label>
											<div class="input-group">
											    <span class="input-group-addon"><i class="ti-user"></i></span>
												<input type="text" name="commission_rate" id="commission_rate" class="form-control" placeholder="Commission rate" data-rule-required="true" data-rule-maxlength="3" data-rule-number="true" value="{{$arr_site_settings['commission_rate'] or ''}}">
												<span class="error">{{ $errors->first('commission_rate') }} </span>
											</div>
										</div>
									</div>	
								</div>
								@if(get_admin_access('site_setting','edit'))
								<div class="form-group">
									<div class="col-sm-12 text-right">
										<button class="btn btn-primary" type="submit"  id="btn_add_front_page" >{{ trans('sitesetting.update') }}</button>
									</div>
								</div>
								@endif
							</form>
						</div>
						<div class="tab-pane " id="bank_details">
							<form class="cmxform" id="frm_bank_details" name="frm_bank_details" action="{{url($admin_panel_slug.'/site_setting/update_bank_details')}}" method="post">
								{{csrf_field()}}
								<div class="row">
									<div class="col-sm-12 col-md-12 col-lg-6">
										<div class="form-group">
											<label class="control-label" for="bank_name">{{ trans('sitesetting.bank name') }}<i class="red">*</i></label>
											<div class="input-group">
											    <span class="input-group-addon"><i class="icon-credit-card"></i></span>
												<input type="text" name="bank_name" id="bank_name" class="form-control" data-rule-required="true" data-rule-maxlength="255" placeholder="" value="{{$arr_site_settings['bank_name'] or ''}}">
												<span class="error">{{ $errors->first('bank_name') }} </span>
											</div>
										</div>
									</div>
									<div class="col-sm-12 col-md-12 col-lg-6">
										<div class="form-group">
											<label class="control-label" for="branch_name">{{ trans('sitesetting.branch name') }}<i class="red">*</i></label>
											<div class="input-group">
											    <span class="input-group-addon"><i class="icon-credit-card"></i></span>
												<input type="text" name="branch_name" id="branch_name" class="form-control" data-rule-required="true" data-rule-maxlength="255" placeholder="" value="{{$arr_site_settings['branch_name'] or ''}}">
												<span class="error">{{ $errors->first('branch_name') }} </span>
											</div>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-12 col-md-12 col-lg-6">
										<div class="form-group">
											<label class="control-label" for="swift_code">{{ trans('sitesetting.swift code') }}<i class="red">*</i></label>
											<div class="input-group">
											    <span class="input-group-addon"><i class="icon-credit-card"></i></span>
												<input type="text" name="swift_code" id="swift_code" class="form-control" data-rule-required="true" data-rule-maxlength="15"  placeholder="" value="{{$arr_site_settings['swift_code'] or ''}}">
												<span class="error">{{ $errors->first('swift_code') }} </span>
											</div>
										</div>
									</div>
									<div class="col-sm-12 col-md-12 col-lg-6">
										<div class="form-group">
											<label class="control-label" for="bank_address">{{ trans('sitesetting.bank address') }}<i class="red">*</i></label>
											<div class="input-group">
											    <span class="input-group-addon"><i class="icon-credit-card"></i></span>
												<input type="text" name="bank_address" id="bank_address" class="form-control" data-rule-required="true" data-rule-maxlength="255" placeholder="" value="{{$arr_site_settings['bank_address'] or ''}}">
												<span class="error">{{ $errors->first('bank_address') }} </span>
											</div>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-12 col-md-12 col-lg-6">
										<div class="form-group">
											<label class="control-label" for="account_number">{{ trans('sitesetting.bank account number') }}<i class="red">*</i></label>
											<div class="input-group">
											    <span class="input-group-addon"><i class="icon-credit-card"></i></span>
												<input type="text" name="account_number" id="account_number" class="form-control" data-rule-required="true" data-rule-number="true" data-rule-maxlength="18" data-rule-minlength="8"placeholder="" value="{{$arr_site_settings['account_number'] or ''}}">
												<span class="error">{{ $errors->first('account_number') }}</span>
											</div>
										</div>
									</div>
								</div>
								@if(get_admin_access('site_setting','edit'))
								<div class="form-group">
									<div class="col-sm-12 text-right">
										<button class="btn btn-primary" type="submit"  id="btn_add_front_page" >{{ trans('sitesetting.update') }}</button>
									</div>
								</div>
								@endif
							</form>
						</div>
						<div class="tab-pane" id="social_links">
							<form class="cmxform" id="frm_social_links" name="frm_social_links" action="{{url($admin_panel_slug.'/site_setting/update_social_links')}}" method="post">
								{{csrf_field()}}

								<div class="row">
									<div class="col-sm-12 col-md-12 col-lg-6">
										<div class="form-group">
											<label class="control-label" for="facebook_url">{{ trans('sitesetting.facebook url') }}<i class="red">*</i></label>
											<div class="input-group">
											    <span class="input-group-addon"><i class="icon-social-facebook"></i></span>
												<input type="text" name="facebook_url" id="facebook_url" class="form-control" data-rule-required="true" data-rule-url="true" data-rule-maxlength="500" placeholder="https://facebook.com/" value="{{$arr_site_settings['fb_url'] or ''}}">
												<span class="error">{{ $errors->first('facebook_url') }} </span>
											</div>
										</div>
									</div>
									<div class="col-sm-12 col-md-12 col-lg-6">
										<div class="form-group">
											<label class="control-label" for="twitter_url">{{ trans('sitesetting.twitter url') }}<i class="red">*</i></label>
											<div class="input-group">
											    <span class="input-group-addon"><i class="icon-social-twitter"></i></span>
												<input type="text" name="twitter_url" id="twitter_url" class="form-control" data-rule-required="true" data-rule-url="true" data-rule-maxlength="500" placeholder="https://twitter.com/" value="{{$arr_site_settings['twitter_url'] or ''}}">
												<span class="error">{{ $errors->first('twitter_url') }} </span>
											</div>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-12 col-md-12 col-lg-6">	
										<div class="form-group">
											<label class="control-label" for="gmail_url">{{ trans('sitesetting.gmail url') }}<i class="red">*</i></label>
											<div class="input-group">
											    <span class="input-group-addon"><i class="ti-email"></i></span>
												<input type="text" name="gmail_url" id="gmail_url" class="form-control" data-rule-required="true" data-rule-url="true" data-rule-maxlength="500" placeholder="https://www.google.com" value="{{$arr_site_settings['gmail_url'] or ''}}">
												<span class="error">{{ $errors->first('gmail_url') }} </span>
											</div>
										</div>
									</div>
									<div class="col-sm-12 col-md-12 col-lg-6">	
										<div class="form-group">
											<label class="control-label" for="youtube_url">{{ trans('sitesetting.youtube url') }}<i class="red">*</i></label>
											<div class="input-group">
											    <span class="input-group-addon"><i class="icon-social-youtube"></i></span>
												<input type="text" name="youtube_url" id="youtube_url" class="form-control" data-rule-required="true" data-rule-url="true" data-rule-maxlength="500" placeholder="" value="{{$arr_site_settings['youtube_url'] or ''}}">
												<span class="error">{{ $errors->first('youtube_url') }} </span>
											</div>
										</div>
									</div>
								</div>
								@if(get_admin_access('site_setting','edit'))
								<div class="form-group">
									<div class="col-sm-12 text-right">
										<button class="btn btn-primary" type="submit"  id="btn_add_front_page" >{{ trans('sitesetting.update') }}</button>
									</div>
								</div>
								@endif
							</form>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?v=3&key={{ config('app.project.google_map_api_key') }}&libraries=places"></script>
<script>
	$(document).ready(function()
	{
		$("#frm_gener_setting").validate();
		$("#frm_bank_details").validate();
		$("#frm_social_links").validate();

		$("#site_address").geocomplete({
			details: ".geo-details",
			detailsAttribute: "data-geo"
		}).bind("geocode:result", function (event, result){                       
			$("#latitude").val(result.geometry.location.lat());
			$("#longitude").val(result.geometry.location.lng());
			var searchAddressComponents = result.address_components,
			searchPostalCode="";
		});

		$("#bank_address").geocomplete({
			details: ".geo-details",
			detailsAttribute: "data-geo"
		}).bind("geocode:result", function (event, result){                       
			$("#latitude").val(result.geometry.location.lat());
			$("#longitude").val(result.geometry.location.lng());
			var searchAddressComponents = result.address_components,
			searchPostalCode="";
		});

	});
	function chk_validation(ref)
	{
		var yourInput = $(ref).val();
		re = /[0-9`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
		var isSplChar = re.test(yourInput);
		if(isSplChar)
		{
			var no_spl_char = yourInput.replace(/[0-9`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
			$(ref).val(no_spl_char);
		}
	}

</script>



