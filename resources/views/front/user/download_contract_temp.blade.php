<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="" />
    <title>{{ config('app.project.name') }}</title>
    <!-- ======================================================================== -->
    <link rel="icon" type="{{url('/')}}/front_assets/image/png" sizes="16x16" href="{{url('/')}}/front_assets/images/favicon.ico">    
<style type="text/css">    
    /*bookings-complete-details css start here*/ 
    .col-lg-5 {width: 41.666667%;float: left}
    .col-lg-2 {width: 16.666667%;float: left}
    .clr{clear: both;}    
    .user-details{border-radius: 100px;background-color: #fcfcfc;border: 1px solid #efefef;margin: 0 auto;position: relative; margin-bottom: 20px;}
    .pro-img{height: 80px;width: 80px;display: inline-block;vertical-align: middle}
    .pro-img img{height: 100%;width: 100%;border-radius: 50%;}
    .admin-details.round-mobile-hide-on {display: none;}
    .operator-content-main-section {display: inline-block;vertical-align: middle;}
    .admin-content {text-align: left;}
    .admin-operator {display: inline-block;width: 25px;text-align: center;margin-right: 3px;vertical-align: middle;}
    .admin-text {color: #c7c7c7;display: inline-block;font-family: 'robotolight';white-space: normal;width: 300px;vertical-align: middle}
    .operator-content-main-right-section {display: inline-block;vertical-align: middle;}
    .center-plane-image{height: 50px;border-radius: 30px;border: 1px dashed #c7c7c7;margin-top: 25px;text-align: center;position: relative;left: 0;right: 0;}
/*    .round-mobile-hide-off{float: right}*/
    .green-plane-icon{margin-top: -11px;}
    .blue-plane-icon{margin-top: 30px;}
    
    
/*
    .contract-between h3{font-size: 20px;line-height: 60px;float: left;}
    .total-rent{width: 222px;height: 40px;border-radius: 3px;background-color: #f5f5f5;float: right;text-align: center;color:  #272b2c;padding: 10px;position: absolute;right: 0;top: 0;}
    
    .aircraft-operator-text{color: #c7c7c7;}            
    
    .operator-content-right-section-main{float: right}
    .aircraft-image {display: inline-block; vertical-align: middle;}
    .operator-content-main-section { display: inline-block; vertical-align: middle;}
    .admin-details { display: inline-block; vertical-align: middle;}
    .admin-details.round-mobile-hide-on{display: none;}
    .aircraft-details-heading{font-size: 18px;letter-spacing: 0px;line-height: 60px;color: #272b2c;margin-bottom: 10px;}
    .sign-bx { text-align: right;}
    .signature-title { font-size: 18px; font-family: 'robotomedium'; }
    .sign-img {  margin-top: 20px;}
    .sign-bx { border-bottom: 1px solid #e2e2e2;    padding-bottom: 30px;  margin-bottom: 30px;}
    .aircraft-details-heading li { font-size: 15px; padding: 0; line-height: 32px; font-family: 'robotolight';}
    .aircraft-details-heading li span{font-family: 'robotomedium'; }
    .aircraft-details-heading p{font-size: 15px;letter-spacing: 0px;line-height: 24px;color: #727272;font-weight: 300;margin-right: 15px;}
    .terms-conditions-heading{font-size: 18px;letter-spacing: 0px;line-height: 60px;color: #272b2c;}
    .terms-conditions-heading p{font-size: 15px;letter-spacing: 0px;line-height: 18px;color: #727272;font-weight: 300;margin-bottom: 40px;margin-right: 90px;}
    .paragraph-spacing p{margin-bottom: 20px;}
    .paragraph-spacing{margin-bottom: 10px;}
    .check-block label{font-size: 14px;letter-spacing: 1px;color: #3c484c;font-weight: 300;}
    .signature-field{font-size: 18px;letter-spacing: 0px;line-height: 60px;color: #272b2c;font-weight: 500;float: right;margin-top: -30px;}
    .signature-image{float: right;margin-top: 18px;margin-bottom: 20px;}
    .gray-line {background: #dddddd;height: 1px;width: 100%;margin-top: 142px;margin-bottom: 20px;}
    .view-transaction-btn a{width: 170px;height: 40px;border-radius: 3px;background-color: #0d95f4;color: white;font-size: 16px;text-align: center;float: left;padding: 7px;}
    .agree-btn a{width: 120px;height: 40px;border-radius: 3px;background-color: #4bbd58;float: right;margin-right: 30px;color: white;font-size: 16px;text-align: center;padding: 7px;}
    .agree-btn:hover a{box-shadow: 3.5px 6.062px 20px rgba(75,189,88,0.5);}
    .cancel-booking-btn a{width: 160px;height: 40px;border-radius: 3px;background-color: rgba(75,189,88,0);border: 1px solid #d9d9d9;margin-right: 20px;float: right;color: red;font-size: 16px;text-align: center;padding: 7px;}
    .cancel-booking-btn:hover a{background-color: red; color: white;}
*/
    /*bookings-complete-details css end here*/
</style>
</head>
<?php

$active_currency = Session::get('currency');
$updated_currency = currency_conversion_api($active_currency);
if($active_currency == 'EUR' ){
    $cny_price = $updated_currency->rates->EUR;
}else{
    $cny_price = $updated_currency->rates->USD;
}

$operator_name = $user_name = '';

$operator_name = isset($obj_booking->get_owner_details->first_name) ? $obj_booking->get_owner_details->first_name.'&nbsp;' : '';
$operator_name .= isset($obj_booking->get_owner_details->last_name) ? $obj_booking->get_owner_details->last_name : '';

$user_name = isset($obj_booking->get_user_details->first_name) ? $obj_booking->get_user_details->first_name.'&nbsp;' : '';
$user_name .= isset($obj_booking->get_user_details->last_name) ? $obj_booking->get_user_details->last_name : '';

?>
<body>
<div class="header-index white-bg-header after-login-header" id="header-home"></div>
    <section class="gray-bg-main-section widt-chg">
        <div class="container">
            <div class="bookings-details booking-completed-details-main">
<!--
                <div class="main-booking-details">
                    <div class="contract-between">
                        <h3>Contract Between</h3>
                        <div class="clearfix"></div>
                    </div>
                    <div class="total-rent">
                        <h4>Total Rent <span style="color: #1a9ffc;"> {{ get_formatted_price($obj_booking->final_amount, $cny_price) }} </span></h4>
                    </div>
                </div>
-->                           
                <div class="user-details">
                    <div class="row">
                        <div class="col-sm-12 col-md-5 col-lg-5">
                            <div class="operator-content-section-main">
                                <div class="aircraft-image pro-img">
                                    <?php
                                        $img = '';
                                        if(isset($obj_booking->get_owner_details->profile_image) && file_exists($operator_profile_base_img_path.$obj_booking->get_owner_details->profile_image)){
                                            $img = $operator_profile_public_img_path.$obj_booking->get_owner_details->profile_image;
                                        }else{
                                            $img = url('/').'/front_assets/images/admin-aircraft-image.jpg';
                                        }
                                    ?>
                                    <img src="{{ $img }}" class="img-circle" alt="" />
                                </div>
                                <div class="operator-content-main-section">
                                    <div class="admin-content">
                                        <div class="admin-operator">
                                            <img src="{{ url('/') }}/front_assets/images/aircraft-operator-image.png" alt="" />
                                        </div>
                                        <div class="admin-text">
                                            Operator
                                            <span style="color: black;">{{ $operator_name or '' }}</span>
                                        </div>
                                    </div>
                                    <!-- <div class="admin-content">
                                        <div class="admin-operator">
                                            <i class="fa fa-mobile" aria-hidden="true"></i>
                                        </div>
                                        <div class="admin-text">
                                            Mobile
                                            <span style="color: black;">{{ $obj_booking->get_owner_details->contact or 'N/A' }}</span>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-2 col-lg-2">
                            <div class="center-plane-image">
                                <div class="green-plane-icon">
                                    <img src="{{ url('/') }}/front_assets/images/green-plane-image.png" alt="" />
                                </div>
                                <div class="blue-plane-icon">
                                    <img src="{{ url('/') }}/front_assets/images/blue-plane-image.png" alt="" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-5 col-lg-5">
                            <div class="operator-content-right-section-main">                                
                                <div class="admin-details round-mobile-hide-off pro-img">
                                    <?php
                                        $img = '';
                                        if(isset($obj_booking->get_user_details->profile_image) && file_exists($user_profile_base_img_path.$obj_booking->get_user_details->profile_image)){
                                            $img = $user_profile_public_img_path.$obj_booking->get_user_details->profile_image;
                                        }else{
                                            $img = url('/').'/front_assets/images/booking-aircraft-image.png';
                                        }
                                    ?>
                                    <img src="{{ $img }}" alt="" />
                                </div>
                                <div class="operator-content-main-right-section">
                                    <div class="admin-content">
                                        <div class="admin-operator">
                                            <img src="{{ url('/') }}/front_assets/images/admin-image-icon.png" alt="" />
                                        </div>
                                        <div class="admin-text">
                                            Charter
                                            <span style="color: black;">{{ $user_name or '' }}</span>
                                        </div>
                                    </div>
                               <!--      <div class="admin-content">
                                        <div class="admin-operator">
                                            <i class="fa fa-mobile" aria-hidden="true"></i>
                                        </div>
                                        <div class="admin-text">
                                            Mobile
                                            <span style="color: black;">{{ $obj_booking->get_user_details->mobile_number or 'N/A' }}</span>
                                        </div>
                                    </div> -->
                                </div>                                
                            </div>
                        </div>
                        <div class="clr"></div>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="aircraft-details">

                    <div class="aircraft-details-heading">
                        <h2> Aircraft Details </h2>
                        <ul>
                            <li>
                                <span>Reservation ID : </span>{{ $obj_booking->reservation_id or '' }}
                            </li>
                            <li>
                                <span>Aircraft Mddel : </span>{{ $obj_booking->get_aircraft_details->get_aircraft_type->model_name or '' }}
                            </li>
                            <li>
                                <span>Aircraft Type : </span>{{ ucwords(str_replace('_', ' ', $obj_booking->get_aircraft_details->type_name)) }}
                            </li>
                            <li>
                                <span>Pickup Date : </span>{{ date('d M Y', strtotime($obj_booking->pickup_date)) }}
                            </li>
                            <li>
                                <span>Return Date : </span>{{ date('d M Y', strtotime($obj_booking->return_date)) }}
                            </li>
                            <li>
                                <span>Pickup Location : </span>{{ $obj_booking->pickup_location or '' }}
                            </li>
                           <!--  <li>
                                <span>Return Location : </span>{{ $obj_booking->return_location or '' }}
                            </li> -->
                        </ul>
                    </div>

                    <div class="aircraft-details-heading">
                        <h2>Terms And Conditions</h2>
                        <p> {!! $obj_booking->get_contract->content or '' !!} </p>
                    </div>

                    @if($obj_booking->is_signed == '1')
                    <div class="sign-bx">
                        <div class="signature-title">Signature</div>
                        <div class="sign-img">
                            @if(file_exists($contract_signature_base_path.$obj_booking->signature))
                                <img src="{{ $contract_signature_public_path.$obj_booking->signature }}" alt="sign" />
                            @endif
                        </div>
                    </div>
                    @endif
                   
                </div>
            </div>
        </div>
   
</section>
</body>
</html>