@extends('front.layout.master')
@section('main_content')
    <div class="header-index white-bg-header after-login-header" id="header-home"></div>


    <section class="gray-bg-main-section">
        <div class="container">            
              <div class="admin-bank-box">
                    <div class="admin-bank">
                        <a href="javascript:void(0)"  data-toggle="modal" data-target="#myModal" id="review_modal">{{ trans('general.admin') }} {{ trans('general.bank') }} {{ trans('general.details') }}</a>
                    </div>

                    <div class="account-status-blog bank-off">
                        <!-- <div class="account-status-txt">Bank Details</div> -->
                        <div class="add-activ-butto">
                            <label class="switch">
                                <!-- <input type="checkbox">
                                <span class="slider rou -->
                                </span>
                            </label>
                        </div>
                    </div>
                  <div class="clearfix"></div>
                </div>            
        <form class="form-horizontal" id="frm_bank_details" name="frm_bank_details" action="{{url('/')}}/user/update_bank_details" method="post"  enctype="multipart/form-data">
            {{csrf_field()}}  
            <div class="form-content">              
             @include('front.layout.operation_status')  
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>{{ trans('general.bank_name') }}<span style="color: red">*</span></label>
                            <input type="text" name="bank_name" id="bank_name" class="form-control" placeholder="Enter your Bank Name" data-rule-required="true" @if(isset($arr_data['bank_name'])) value="{{$arr_data['bank_name']}}" @endif tabindex="1" >
                            <span class="error">{{ $errors->first('bank_name') }} </span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>{{ trans('general.branch_name') }} <span style="color: red">*</span></label>
                            <input type="text" name="branch_name" id="branch_name" class="form-control" placeholder="Enter your Branch Name" data-rule-required="true" @if(isset($arr_data['branch_name'])) value="{{$arr_data['branch_name']}}"  @endif tabindex="1" >
                            <span class="error">{{ $errors->first('branch_name') }} </span>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>{{ trans('general.swift') }} {{ trans('general.code') }} <span style="color: red">*</span></label>
                            <input type="text" name="swift_code" id="swift_code" class="form-control" placeholder="Enter Swift code" data-rule-required="true" @if(isset($arr_data['swift_code']))  value="{{$arr_data['swift_code']}}" @endif tabindex="1" >
                            <span class="error">{{ $errors->first('swift_code') }} </span>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>{{ trans('general.account_number') }}<span style="color: red">*</span></label>
                            <input type="text" name="account_number" id="account_number" class="form-control" placeholder="Enter your Account Number" @if(isset($arr_data['account_number'])) value="{{$arr_data['account_number']}}" @endif data-rule-number="true" data-rule-required="true"  tabindex="1" >
                            <span class="error">{{ $errors->first('account_number') }} </span>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>{{ trans('general.bank_address') }}<span style="color: red">*</span></label>
                            <input type="text" name="address" id="address" class="form-control" placeholder="Enter Bank Address" data-rule-required="true"  @if(isset($arr_data['bank_address'])) value="{{$arr_data['bank_address']}}" @endif tabindex="1" >
                            <span class="error">{{ $errors->first('address') }} </span>
                        </div>
                    </div>


                    <div class="update-profile-button col-sm-12">
                        <button>{{ trans('general.save') }}</button>
                    </div>
                </div>



                <div class="clearfix"></div>
            </div>
        </form>    
        </div>
    </section>
<!-- The Modal -->
<div class="modal registration-modal give-feedback-form-main" id="myModal" data-backdrop="static">
    <div class="modal-dialog">

    <div class="modal-content">                
        <!-- Modal body -->
    <div class="close-icon" data-dismiss="modal">
        <img src="{{ url('/') }}/front_assets/images/close-img.png" alt="" />
    </div>
        <div class="modal-body">
            <div class="give-feedback-form request-quote-main booking-pending booking-completed">

                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12">
                        <div class="form-group">
                            <h1 style="text-align: center;">{{ trans('general.admin') }} {{ trans('general.bank') }} {{ trans('general.details') }}</h1>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12">
                        <div class="form-group">
                            <label>{{ trans('general.bank_name') }} <span style="color: red">*</span></label>
                            <input type="text" name="bank_name" id="bank_name" class="form-control" placeholder="Enter your Bank Name" data-rule-required="true" @if(isset($arr_admin['bank_name'])) value="{{$arr_admin['bank_name']}}" @endif tabindex="1" readonly="">
                            <span class="error">{{ $errors->first('bank_name') }} </span>
                        </div>
                    </div>
                </div>
                <div class="row">    
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12">
                         <div class="form-group">
                            <label>{{ trans('general.branch_name') }}  <span style="color: red">*</span></label>
                            <input type="text" name="branch_name" id="branch_name" class="form-control" placeholder="Enter your Branch Name" data-rule-required="true" @if(isset($arr_admin['branch_name'])) value="{{$arr_admin['branch_name']}}"  @endif tabindex="1" readonly="" >
                            <span class="error">{{ $errors->first('branch_name') }} </span>
                        </div>
                    </div>
                </div>
                <div class="row">
                   <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12">
                        <div class="form-group">
                            <label>{{ trans('general.swift') }} {{ trans('general.code') }} <span style="color: red">*</span></label>
                            <input type="text" name="swift_code" id="swift_code" class="form-control" placeholder="Enter Swift code" data-rule-required="true" @if(isset($arr_admin['swift_code']))  value="{{$arr_admin['swift_code']}}" @endif tabindex="1" readonly="">
                            <span class="error">{{ $errors->first('swift_code') }} </span>
                        </div>
                    </div>
                </div>
                <div class="row">    
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12">
                       <div class="form-group">
                            <label>{{ trans('general.account_number') }}<span style="color: red">*</span></label>
                            <input type="text" name="account_number" id="account_number" class="form-control" placeholder="Enter your Account Number" @if(isset($arr_admin['account_number'])) value="{{$arr_admin['account_number']}}" @endif data-rule-number="true" data-rule-required="true"  tabindex="1" readonly="">
                            <span class="error">{{ $errors->first('account_number') }} </span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12">
                       <div class="form-group">
                            <label>{{ trans('general.bank_address') }} <span style="color: red">*</span></label>
                            <input type="text" name="address" id="address" class="form-control" placeholder="Enter Bank Address" data-rule-required="true"  @if(isset($arr_admin['bank_address'])) value="{{$arr_admin['bank_address']}}" @endif tabindex="1" readonly="">
                            <span class="error">{{ $errors->first('address') }} </span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12">
                       <div class="form-group">
                            <div class="accept" style="margin-left: 110px;"><a href="javascript:void(0)" data-dismiss="modal">{{ trans('general.close') }}</a></div>
                        </div>
                    </div>
                </div>
      
            </div>
        </div>
        <!-- Modal body end here -->
    </div>

</div>
</div>
    <script src="{{ url('/') }}/web_admin/assets/js/pages/jquery.geocomplete.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3&key={{ get_google_map_api_key() }}&libraries=places"></script>
    <script type="text/javascript">

        $(document).ready(function()
        {
            $('#frm_bank_details').validate();
        });
        $(function () 
        {  
            $("#address").geocomplete({
                details: ".geo-details",
                detailsAttribute: "data-geo"
            }).bind("geocode:result", function (event, result){                       
                //$("#latitude").val(result.geometry.location.lat());
                //$("#longitude").val(result.geometry.location.lng());
                /*$("#city").val(result.geometry.location.city());*/
                var searchAddressComponents = result.address_components,
                searchPostalCode="";
            });
        });
     </script>   
    @endsection