@extends('front.layout.master')
@section('main_content')
<style type="text/css">
    .aircraft-details-heading li {display: block;}
    .error {color: red;}
    .aircraft-details ul {padding: 25px 0px;}
</style>
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

$today = Date('Y-m-d');
$pickup_date = $obj_booking->pickup_date;
//$pickup_date = '2019-04-30';

$day_remain = get_days_difference($today, $pickup_date);

?>
<div class="header-index white-bg-header after-login-header" id="header-home"></div>
    <section class="gray-bg-main-section widt-chg">
        <div class="container" id="printableDiv">
            @include('front.layout.operation_status')
            <div class="bookings-details booking-completed-details-main">
                <div class="main-booking-details">
                    <a class="print-btn" href="{{ url('/').'/user/download_contract/'.$enc_id }}" id="PrintContract" target="_blank"><i class="fa fa-print" ></i></a>
                    <div class="contract-between">
                        <h3>Contract Between</h3>
                        <div class="clearfix"></div>
                    </div>

                    <div class="total-rent">
                        <h4>Total Rent <span style="color: #1a9ffc;"> {{ get_formatted_price($obj_booking->final_amount, $cny_price) }} </span></h4>
                    </div>
                </div>

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
                                   <!--  <div class="admin-content">
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
                                <div class="admin-details round-mobile-hide-on">
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
                                   <!--  <div class="admin-content">
                                        <div class="admin-operator">
                                            <i class="fa fa-mobile" aria-hidden="true"></i>
                                        </div>
                                        <div class="admin-text">
                                            Mobile
                                            <span style="color: black;">{{ $obj_booking->get_user_details->mobile_number or 'N/A' }}</span>
                                        </div>
                                    </div> -->
                                </div>
                                <div class="admin-details round-mobile-hide-off pro-img">
                                    <img src="{{ $img }}" alt="" />
                                </div>
                            </div>
                        </div>
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
                                <span>Aircraft Model : </span>{{ $obj_booking->get_aircraft_details->get_aircraft_type->model_name or '' }}
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
                    @if($obj_booking->is_signed == '0' && $obj_booking->cancellation_status != 'APPROVED')
                    <div class="check-block">
                        <input id="filled-in-box" class="filled-in" checked="checked" type="checkbox">
                        <label for="filled-in-box">I Agree Terms and Conditions</label>
                    </div>
                    @endif

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

                    @if($obj_booking->is_signed == '1')
                    <div class="view-transaction-btn">
                        <a href="javascript:void(0);" id="view_trans">View Transaction</a>
                    </div>
                    @endif

                    <div class="agree-two-btn">
                        @if($obj_booking->is_signed == '0' && $obj_booking->cancellation_status != 'APPROVED')
                        <div class="agree-btn">
                            <a href="javascript:void(0)" id="aggreBtn">Sign Contract</a>
                        </div>
                        @endif

                        @if($obj_booking->status != 'COMPLETED' )
                        <div class="cancel-booking-btn">
                            @if($obj_booking->status != 'CANCELLED' && $obj_booking->cancellation_status == '' && $day_remain > 0)
                                <a href="{{ url('/').'/user/cancel_book_req/'.$enc_id }}" onclick="return confirm_action(this,event,'Do you really want to cancel this booking ?')">Cancel Booking</a>
                            {{-- @else
                                <a href="javascript:void(0);" onclick="swal('Oops!', 'Cannot request to cancel before 1 day and after pickup date.','error');">Cancel Booking</a> --}}
                            @endif
                        </div>
                        @endif
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <!-- Old transaction Listing -->
<!--             <div class="table-responsive table-transactions" style="display: none;">
                <table class="table table-striped table-content">
                    <thead>
                        <tr>
                            <th>Reservation Id</th>
                            <th>Pickup Date</th>
                            <th>Return Date</th>
                            <th>Final Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $obj_booking->reservation_id }}</td>
                            <td>{{ date('d M Y', strtotime($obj_booking->pickup_date)) }}</td>
                            <td>{{ date('d M Y', strtotime($obj_booking->return_date)) }}</td>
                            <td>{{ get_formatted_price($obj_booking->final_amount, $cny_price) }}</td>
                            <td>
                                @if($obj_booking->payment_status == 'PENDING')
                                <span style="color: #d5c51b;">{{$obj_booking->payment_status}}</span>
                                @elseif($obj_booking->payment_status == 'SUBMITTED')
                                <span style="color: #d1d100;">{{$obj_booking->payment_status}}</span>
                                @elseif($obj_booking->payment_status == 'REJECTED')
                                <span style="color: #fc3737;">{{$obj_booking->payment_status}}</span>
                                @elseif($obj_booking->payment_status == 'APPROVED')
                                <span style="color: #4bbd58;">{{$obj_booking->payment_status}}</span>
                                @endif
                            </td>
                            <td>
                                @if($obj_booking->payment_status == 'REJECTED')
                                <form method="POST" action="{{ url('/').'/user/submit_new_paysleep/'.$enc_id }}" id="reupload_paysleep_form" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <button type="submit" class="btn btn-info btn-lg" {{-- onclick="return confirm_action(this,event,'Do you really want to upload this file ?')" --}}>Upload</button>
                                    <input type="file" name="pay_sleep" id="reupload_paysleep" data-rule-required="true" onchange="Changefilename(this)" accept=".xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf" data-msg-required="Please choose payment sleep." />
                                    <br>
                                    <label id="reupload_paysleep-error" class="error" for="reupload_paysleep"></label>
                                </form>
                                @endif
                                @if(isset($obj_booking->payment_sleep) && file_exists($payment_receipt_to_admin_base_path.$obj_booking->payment_sleep))
                                    <a class="extend-req power-off" title="Download Receipt" href="{{ $payment_receipt_to_admin_public_path.$obj_booking->payment_sleep }}" download style="margin: unset;"><i class="fa fa-download"></i></a>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div> -->
            <!-- End of old transaction Listing -->
                        <div class="table-responsive table-transactions" style="display: none;">
                    @if(!empty($arr_transaction))
                <table class="table table-striped table-content">
                    <thead>
                        <tr>
                            <th>Transaction Id</th>
                            <th>Reservation Id</th>
                            <th>Paid Amount</th>
                            <th>Paid On</th>
                            <th>Pay slip</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach( $arr_transaction as $value )
                        <tr>
                            <td>{{ $value['transaction_id'] or 'NA' }}</td>
                            <td>{{ $value['reservation_id'] or 'NA' }}</td>
                            <td >&#36; {{ $value['paid_amount'] or 'NA' }}</td>
                            <td>
                                <?php echo get_formated_date($value['paid_on'])?>
                            </td>
                            <td>
                                 @if(isset($value['pay_slip']) && $value['pay_slip'] != '' && file_exists($payment_receipt_to_admin_base_path.$value['pay_slip']))
                                    <a class="extend-req power-off" title="Download Payment Slip" href="{{ $payment_receipt_to_admin_public_path.$value['pay_slip'] }}" download style="margin: unset;"><i class="fa fa-download"></i></a>
                                 @endif
                            </td>
                            <td>
                                @if($value['status'] == 'PENDING')
                                    <p style="color: orange;">PENDING</p>
                                @elseif($value['status'] == 'REJECTED')
                                    <p style="color: red;">REJECTED</p>
                                @elseif($value['status'] == 'APPROVED')
                                    <p   style="color: green;">APPROVED</p>
                                @elseif($value['status'] == 'REQUESTED')
                                    <p   style="color: #007bff;">REQUESTED</p>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                    @else
                        <tr>
                            <h3 style="text-align: center;color: red">No transactions to show</h3>
                        </tr>
                    @endif
            </div>
        </div>
   
</section>

<!-- The Modal -->
<div class="modal forgot-pwd-modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="close-icon" data-dismiss="modal"><img src="{{ url('/') }}/front_assets/images/close-img.png" alt="" /></div>
            <!-- Modal body -->
            <div class="modal-body">
                <form action="{{ url('/').'/user/submit_contract/'.$enc_id}}" method="POST" id="contractForm" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="image-icon">
                        <img src="{{ url('/') }}/front_assets/images/icon.png" alt="" />
                    </div>
                    <div class="forgot-pwd">Signature File</div>
                    <div class="new-sign-bx">
                    <div class="form-control" style="border: unset; padding: unset;">
                        <canvas id="signature" width="448" height="148" style="border: 1px solid #ddd;"></canvas>
                        <input type="text" name="signature_file" id="signature_file" data-rule-required="true" data-msg-required="Please draw your signature in the box." value="" style="display: none;">
                        <label id="signature_file-error" class="error" for="signature_file"></label>
                        <span class="error">{{ $errors->first('signature_file') }} </span>
                    </div>
                    {{-- <div class="forget-pwd-line"></div> --}}
                    </div>
                    {{-- <div class="upload-experience-certifcate">
                        <div class="form-group">
                            <label>Upload Payment Slip</label>
                            <div class="upload-block">
                                <input type="file" id="pdffile" style="visibility: hidden; height: 0;border: none" data-rule-required="true" name="payment_sleep" onchange="Changefilename(this)" accept=".xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf" />
                                <div class="input-group">
                                    <input type="text" class="alias_file file-caption kv-fileinput-caption" placeholder="Upload Payment Sleep" readonly="" />
                                    <span class="error" id="error-pdffile">{{ $errors->first('payment_sleep') }} </span>
                                    <span class="error">{{ $errors->first('signature_file') }} </span>
                                    <div class="btn btn-primary btn-file" onclick="$('#pdffile').click();"><i class="fa fa-upload"></i> File</div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div class="retrieve-pwd-btn">
                        <button class="button-retrieve-pwd" id="clear-signature">Clear</button>
                        <button class="button-retrieve-pwd" onclick="getData();"> Submit </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- The End of Modal -->
<script type="text/javascript" src="{{ url('/') }}/front_assets/js/signature_pad.min.js"></script>
<script type="text/javascript">

    $(document).ready(function()
    {
        <?php if(\Session::has('contract_modal')) : Session::forget('contract_modal'); ?>
            $('#myModal').modal('show');
        <?php endif; ?>
        $("#pdffile").val('');
        $("#contractForm").validate({
            ignore : [],
            rules : {
                required : true
            }
        });

        $("#reupload_paysleep_form").validate();

        $('#aggreBtn').click(function(){
            if($("#filled-in-box").prop("checked") == true){
                $('#myModal').modal('show');
            }else{
                swal("", "Please check the checkbox of Terms and Conditions", "error");
            }
        });

        var canvas = document.getElementById("signature");
        var signaturePad = new SignaturePad(canvas);

        $('#clear-signature').on('click', function(){
            signaturePad.clear();
            $("#signature_file").val('');
        });

        $("#contractForm").submit(function()
        {
            if(signaturePad.isEmpty()){
                $("#signature_file-error").html("Please draw your signature in the box.");
                $("#signature_file-error").show();
                return false;
            }
        });

        $("#view_trans").click(function(){
            $(".table-transactions").fadeToggle();
        });

        /*$("#PrintContract").click(function()
        {
            window.print();
        });*/

    });

    function getData()
    {
        var canvas  = document.getElementById("signature");
        var dataURL = canvas.toDataURL("image/png");
        $("#signature_file").val(dataURL);
    }

    function Changefilename(event){
        var file = event.files;
        name = file[0].name;
        var ret = validateDocument(file,'Doc',null);
        if(ret){
            //$(event).next().children('input').val(name);
            $('.alias_file').val(name);
        }else{
            $('#pdffile').val('');
            $('#reupload_paysleep').val('');
            $('.alias_file').val('');
        }
    }

    function validateDocument(files,type,element_id) 
    {
        if (typeof files !== "undefined") 
        {
            for (var i=0, l=files.length; i<l; i++) 
            {
                var blnValid = false;
                var ext = files[i]['name'].substring(files[i]['name'].lastIndexOf('.') + 1);
                if(type=='Doc')
                {
                    if(ext=='pdf' || ext=='jpg'|| ext=='png' || ext=='jpeg')
                    {
                        blnValid = true;
                    }  
                }
                else
                {
                    if(ext=='pdf' || ext=='jpg'|| ext=='png' || ext=='jpeg')
                    {
                        blnValid = true;
                    }
                }

                if(blnValid ==false) 
                {
                    if(type=='Doc')
                    {
                        showAlert("Sorry, " + files[0]['name'] + " is invalid, allowed extensions are: pdf or images","error");
                    }
                    else
                    {
                        showAlert("Sorry, " + files[0]['name'] + " is invalid, allowed extensions are: pdf or images","error");
                    }
                    return false;
                }
                else
                {              
                    if(type=='Doc')
                    {
                        if(files[0].size>10485760)
                        {
                            showAlert("File size should be less than 10 MB","error");
                        }
                    }       
                }                
            }
        }
        else
        {
            showAlert("No support for the File API in this web browser" ,"error");
        }
        return true;
    }
</script>
<!--new profile image upload demo script end-->

<script type="text/javascript">

    $(".inner-pages-menu-icon").on("click", function() {
        $(this).parent(".inner-pages-menu-head").siblings(".inner-page-menu-ul").slideToggle("slow");
    });

    $('#search').on("keypress", function(e) {
        if (e.keyCode == 13) {
            var search = $(this).val();
            window.location.href = "{{ url('/') }}/user/pending_bookings?search="+search
        }
    });
    $( "#button" ).click(function() {
        var search = $('#search').val();
        window.location.href = "{{ url('/') }}/user/pending_bookings?search="+search
    });

    function confirm_action(ref,evt,msg)
    {
       var msg = msg || false;
      
        evt.preventDefault();  
        swal({
              title: "Are you sure ?",
              text: msg,
              type: "warning",
              showCancelButton: true,
              confirmButtonColor: "#DD6B55",
              confirmButtonText: "Yes",
              cancelButtonText: "No",
              closeOnConfirm: false,
              closeOnCancel: true
            },
            function(isConfirm)
            {
              if(isConfirm==true)
              {
                // swal("Performed!", "Your Action has been performed on that file.", "success");
                window.location = $(ref).attr('href');
              }
            });
    }

</script>

@endsection