@extends('front.layout.master')
@section('main_content')

<style type="text/css">
    textarea.form-control {height: auto;font-size: 15px;}
</style>

<?php 
$active_currency = Session::get('currency');
$updated_currency = currency_conversion_api($active_currency);
if($active_currency == 'EUR' ){
    $cny_price = $updated_currency->rates->EUR;
}else{
    $cny_price = $updated_currency->rates->USD;
}
?>


<style type="text/css">
   .titles-h3{padding: 10px;margin-left: 30px;margin-right: 30px;}
</style>
<section class="gray-bg-main-section">
    <div class="container">
        <div class="request-quote-filter-section">
            <div class="row m-l--5 m-r--5">
                <div class="col-sm-8 col-md-8 col-lg-8">
                    <div class="filter-search-main">
                        <div class="form-group">
                            <span><i class="fa fa-search"></i></span>
                            <input type="search" name="search" type="text" placeholder="Search Transactions by Id" value="{{isset($_GET['search'])?$_GET['search']:''}}"  id="search"> 
                        </div>
                    </div>
                </div>
                <div class="col-sm-2 col-md-2 col-lg-4">
                    <div class="search-filter-button">
                        <button class="filter-search-btn" id="button"><i class="fa fa-search"></i> {{ trans('general.search') }}</button>
                        <a class="filter-icon-btn reset-form" href="javascript:void(0)"><i class="fa fa-repeat"></i></a>&nbsp;&nbsp;&nbsp;
                        <button style="width: 160px;font-size: 14px;"  class="btn btn-primary" data-toggle="modal" data-target="#myModal" id="review_modal" ><i class="fa fa-money"></i>&nbsp;Send payment</button>
                    </div>
                </div>
            </div>

            <div class="col-sm-3 col-md-3 col-lg-12 ">                    
                <div class="search-filter-button">
                    <div class="jpgs-avrd">
                        <img src="{{ url('/') }}/front_assets/images/money-bill.jpg" height="80" width="80" alt="">
                    </div><br>
                    <div class="titles-h3">
                        &#36; <?php echo number_format($obj_final_amount,2); ?> &nbsp;
                        <p><strong>Total amount collection</strong></p>
                    </div>

                    <div class="jpgs-avrd">
                        <img src="{{ url('/') }}/front_assets/images/money-bill.jpg" height="80" width="80" alt="">
                    </div><br>
                    <div class="titles-h3">
                        &#36;<?php echo number_format($obj_paid_amount,2); ?> &nbsp;
                        <p><strong>Paid amount</strong></p>
                    </div>

                    <div class="jpgs-avrd">
                        <img src="{{ url('/') }}/front_assets/images/money-bill.jpg" height="80" width="80" alt="">
                    </div><br>
                    <div class="titles-h3">
                        &#36; <?php echo number_format($pending_amount,2); ?> &nbsp;
                        <p><strong>Pending amount</strong></p>
                    </div>
                </div>
            </div>
            @include('front.layout.operation_status')
            @if(isset($arr_transaction['data']) && !empty($arr_transaction['data']))    
            <div class="table-responsive">
                <table class="table table-striped table-content">
                    <thead>
                      <tr style="text-align: center;">
                        <th>Transaction Id</th>
                        <th>Reservation Id</th>
                        <th>Requested Amount</th>
                        <th>Paid Amount</th>
                        <th>Paid On</th>
                        <th>Payment Slip</th>
                        <th>Status</th>
                        <th>Pay</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach( $arr_transaction['data'] as $key => $value ) 
                    <tr style="text-align: center;">
                        <td>{{ $value['transaction_id']  or 'NA'}}</td>
                        <td>{{ $value['reservation_id']  or 'NA'}}</td>
                        <td>&#36; {{ $value['requested_amount']  or 'NA'}}</td>
                        <td>&#36; {{ $value['paid_amount']  or 'NA'}}</td>
                        <td><?php echo get_formated_date($value['paid_on']) ?></td>
                        <td style="padding-left: 40px;">
                            @if(isset($value['pay_slip']) && $value['pay_slip'] !='' &&file_exists($payment_receipt_to_admin_base_path.$value['pay_slip']) )
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
                            <p   style="color: #1197f5;">REQUESTED</p>
                            @endif
                        </td>
                        <td>
                            @if($value['status'] == 'REQUESTED')
                            <button style="font-size: 14px;" data-id="{{ $value['id'] }}" data-res-id="{{ $value['reservation_id'] }}" data-req-amount="{{ $value['requested_amount'] }}" class="btn btn-primary" data-toggle="modal" data-target="#myModal" id="send_modal" ><i class="fa fa-money"></i>&nbsp;Pay Now</button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination">
            <ul>
                {{$page_link}}
            </ul>
        </div>
        @else
        <div class="container">
            <div class="background-container">
                <div class="img-content">
                    <img src="{{ url('/') }}/front_assets/images/rocket.png" alt="">
                </div> 
                <div class="content-background">
                    <div class="result-not-found">Sorry there are no Transactions</div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

<div class="modal registration-modal give-feedback-form-main" id="myModal" data-backdrop="static">
    <div class="modal-dialog">
        <form class="form-horizontal" id="frm_payment" name="frm_payment" action="{{url('/')}}/user/send_payment" method="post" enctype="multipart/form-data">
            {{csrf_field()}}
            <input type="hidden" id="hidden" name="hidden" value="">
            <div class="modal-content">
                <div class="close-icon" data-dismiss="modal"><img src="{{ url('/') }}/front_assets/images/close-img.png" alt="" /></div>
                <div class="modal-body">
                    <div class="give-feedback-form request-quote-main booking-pending booking-completed">
                        <div class="row">
                            <div class="col-sm-6 col-md-6 col-lg-12">
                                <div class="date-time-search">
                                    <label>Amount <span style="color: red">*</span></label>
                                    <div class="form-group">
                                        <span><i class="far fa-calendar-alt"></i></span>
                                        <input type="text" name="amount" id="amount" class="form-control" placeholder="Enter amount" data-rule-maxlength="10" data-rule-required="true" data-rule-min="1" @if(isset($pending_amount)) max="{{ $pending_amount or '' }}" @endif data-rule-number="true" data-msg-max="Only $ {0} dollars amount is remaining to pay. " >
                                        <span class="error">{{ $errors->first('amount') }} </span>
                                    </div>
                                </div>
                            </div>
                          <!--   <div class="form-group">
                        <label class="control-label col-lg-2" for="amount">Amount<i class="red">*</i></label>
                        <div class="col-lg-5">
                            <input type="text" name="amount" id="amount" class="form-control" placeholder="Enter amount" data-rule-maxlength="10" data-rule-required="true" data-rule-min="1" data-rule-number="true" @if(isset($pending_amount)) max="{{ $pending_amount or '' }}" @endif  data-msg-max="Only $ {0} dollars amount is remaining to pay. " />
                            <span class="error"> </span>
                        </div>
                    </div> -->
                            <div class="col-sm-6 col-md-6 col-lg-12">
                                <div class="date-time-search">
                                    <label>Transaction ID <span style="color: red">*</span></label>
                                    <div class="form-group">
                                        <span><i class="far fa-calendar-alt"></i></span>
                                        <input type="text" name="transaction" id="transaction" class="form-control" placeholder="Enter transaction" data-rule-required="true" >
                                        <span class="error">{{ $errors->first('amount') }} </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-12">
                                <div class="date-time-search">
                                    <label>Reservation ID <span style="color: red">*</span></label>
                                    <div class="form-group">
                                        <span><i class="far fa-calendar-alt"></i></span>
                                        <select class="form-control" style="position: unset;" data-rule-required="true" id="reservation" name="reservation">
                                            <option value="">Select Reservation</option>
                                            @foreach( $arr_reservations as $value )
                                            <option  value="{{ $value['reservation_id']  }}">{{ $value['reservation_id'] }}</option>
                                            @endforeach
                                        </select>
                                        <span class="error">{{ $errors->first('reservation') }} </span>
                                    </div>

                                </div>
                            </div>
                        </div>
                      <!--   <div class="row">
                            <div class="col-sm-6 col-md-6 col-lg-12">
                                <div class="date-time-search">
                                    <label>Payment receipt <span style="color: red">*</span></label>
                                    <div class="form-group">
                                        <span><i class="far fa-calendar-alt"></i></span>
                                       <input type="file" name="receipt" id="image" class="form-control btn-padding validate-image" data-rule-required="true" autocomplete="off">
                                        <span class="error">{{ $errors->first('image') }} </span> 
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <div class="row">
                          <div class="col-sm-6 col-md-6 col-lg-12 upload-experience-certifcate">
                            <div class="form-group">
                                <label>Payment receipt <span style="color: red">*</span>    </label>
                                <div class="upload-block">
                                    <input type="file" id="image" style="visibility:hidden; height: 0;border: none" name="receipt" data-rule-required="true" autocomplete="on" onchange="Changefilename(this)">
                                    <div class="input-group">
                                        <input type="text" class="alias_file file-caption kv-fileinput-caption" placeholder="Payment receipt" id="image" readonly="" />
                                        <div class="btn btn-primary btn-file" onclick="$('#image').click();"><i class="fa fa-upload"></i> File</div>
                                        <span class="error" id="error-image">{{ $errors->first('image') }} </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-12">
                            <div class="date-time-search">
                                <label>Note </label>
                                <div class="form-group">
                                    <span><i class="far fa-calendar-alt"></i></span>
                                    <textarea type="text" name="note" id="note" class="form-control"  data-rule-maxlength="1000"></textarea>
                                    <span class="error">{{ $errors->first('note') }} </span> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="feedback-review">
                        <div class="button-quote request-button main-button1">
                            <div class="accept reject"><button class="full-orng-btn sim-button" data-dismiss="modal">Close</button></div>
                            <div class="accept reject"><button type="submit" class="full-orng-btn sim-button">Submit</button></div>
                        </div>                                                       
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
</div>

<script type="text/javascript">
    $('#search').on("keypress", function(e) {
        if (e.keyCode == 13) {
            var search = $(this).val();
            window.location.href = "{{ url('/') }}/user/transactions?search="+search
        }
    });
    $( "#button" ).click(function() {
        var search = $('#search').val();
        window.location.href = "{{ url('/') }}/user/transactions?search="+search
    });

    $(document).ready(function(){
        $('#frm_payment').validate();
    })

    function Changefilename(event){
        var file = event.files;
        name = file[0].name;
        var ret = validateDocument(file,'Doc',null);
        $(event).next().children('input').val(name);

        if(!ret){
            $("#image").val('');
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
                    if(ext=='jpg' || ext=='png' || ext=='jpeg')
                    {
                        blnValid = true;
                    }  
                }
                else
                {
                    if(ext=='jpg' || ext=='png' || ext=='jpeg')
                    {
                        blnValid = true;
                    }
                }

                if(blnValid ==false) 
                {
                    if(type=='Doc')
                    {
                        showAlert("Sorry, " + files[0]['name'] + " is invalid, allowed extensions are: jpg or png or jpeg","error");
                    }
                    else
                    {
                        showAlert("Sorry, " + files[0]['name'] + " is invalid, allowed extensions are: jpg or png or jpeg","error");
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
<script>
    $("body").on('click','#send_modal',function(){

        var trans_id    = $(this).attr('data-id');
        var req_amount  = $(this).attr('data-req-amount');
        var res_id      = $(this).attr('data-res-id');

        $('#hidden').val(trans_id);
        $('#reservation').val(res_id);
        $('#amount').val(req_amount);
        
    });
    $('.reset-form').click(function(){
        $("#search").val('');
        window.location.href = "{{ url('/') }}/user/transactions";
    });
</script>
@endsection