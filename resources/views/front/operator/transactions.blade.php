@extends('front.layout.master')
@section('main_content')

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
    .titles-h3{padding: 10px;margin-left: 10px;margin-right: 10px;}
</style>
<section class="gray-bg-main-section">
        <div class="container">
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
                            <button class="filter-search-btn" id="button"><i class="fa fa-search"></i> {{ trans('general.search') }}</button>&nbsp;&nbsp;&nbsp;
                            <button style="width: 175px;font-size: 14px;" class="btn btn-primary" data-toggle="modal" data-target="#myModalrequest" id="review_modal" ><i class="fa fa-money"></i> Request payment</button>
                            <a class="filter-icon-btn reset-form" href="javascript:void(0)"><i class="fa fa-repeat"></i></a>
                        </div>
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
                    &#36; <?php echo number_format($obj_paid_amount,2); ?> &nbsp;
                    <p><strong>Recevied amount</strong></p>
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
              
        </div>
        <br>    
         @include('front.layout.operation_status')
         @if(isset($arr_transaction['data']) && !empty($arr_transaction['data']))    
            <div class="table-responsive">
                <table class="table table-striped table-content">
                    <thead>
                      <tr style="text-align: center;">
                        <th>Transaction Id</th>
                        <th>Reservation Id</th>
                        <th>Requested Amount</th>
                        <th>Received Amount</th>
                        <th>Paid On</th>
                        <th>Payment Slip</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                       @foreach( $arr_transaction['data'] as $key => $value ) 
                      <tr style="text-align: -moz-center;" >
                        <td >{{ $value['transaction_id']  or 'NA'}}</td>
                        <td>{{ $value['reservation_id']  or 'NA'}}</td>
                        <td>{{ $value['requested_amount']  or 'NA'}}</td>
                        <td>&#36; {{ $value['paid_amount']  or 'NA'}}</td>
                        <td><?php echo get_formated_date($value['paid_on']) ?></td>
                        <td>
                            @if(isset($value['pay_slip']) && $value['pay_slip'] != '' &&file_exists($payment_receipt_to_admin_base_path.$value['pay_slip']))
                                    <a class="extend-req power-off" title="Download Payment Slip" href="{{ $payment_receipt_to_admin_public_path.$value['pay_slip'] }}" download style="margin: unset;"><i class="fa fa-download"></i></a>
                            @endif
                        </td>
                        <td>
                        @if($value['status'] == 'PENDING')
                            <p style="color: orange;">PENDING</p>
                        @elseif($value['status'] == 'APPROVED') 
                            <p style="color: green;">APPROVED</p>
                        @elseif($value['status'] == 'REJECTED')
                            <p style="color: red;">REJECTED</p>
                        @elseif($value['status'] == 'REQUESTED')
                            <p style="color: #3ca7f1;">REQUESTED</p>
                        @endif
                        </td>
                        <td style="width: 10%;">
                            @if($value['status'] == 'PENDING')
                                <a class="btn btn-xs btn-success" title="Approve" href="{{ url('/') }}/operator/approve/{{ base64_encode($value['id']) }}" onclick="return confirm_action(this,event,\'Do you really want to Approve this Payment ?\')" >Approve</a>

                                <a class="btn btn-xs btn-danger" title="Reject" href="{{ url('/') }}/operator/reject/{{ base64_encode($value['id']) }}" onclick="return confirm_action(this,event,\'Do you really want to Reject this payment ?\')" >Reject</a>
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
        <div class="modal registration-modal give-feedback-form-main" id="myModalrequest" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="close-icon" data-dismiss="modal" ><img src="{{ url('/') }}/front_assets/images/close-img.png" alt="" /></div>
                <div class="modal-body">
                    <div class="give-feedback-form request-quote-main booking-pending booking-completed">

                        <h1 style="text-align: center;">Request payment</h1>
                        <div class="panel-body ulter-colo">
                            <form class="form-horizontal" id="frm_payment" name="frm_payment" action="{{url('/')}}/operator/request_payment" method="post" enctype="multipart/form-data">
                             {{csrf_field()}}
                                    <label>Amount <span style="color: red">*</span></label>
                                    <div class="form-group">
                                        <span><i class="far fa-calendar-alt"></i></span>
                                        <input type="text" name="amount" id="amount" class="form-control" placeholder="Enter amount"  data-rule-required="true" data-rule-min="1" data-rule-number="true" value="" @if(isset($pending_amount)) max="{{ $pending_amount or '' }}" @endif  data-msg-max="Only $ {0} dollars amount is remaining to pay. ">
                                        <span class="error">{{ $errors->first('amount') }} </span>
                                    </div>
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
                                    <div class="accept reject" style="text-align: center;text-align: center;"><button type="submit" class="filter-search-btn" style="width: 180px;margin-left: 125px;">Request for payment</button></div>

                             </form>
                        </div>
                        </div>
                </div>
            </div>
        </div>
        </div>
<script type="text/javascript">
    $('#search').on("keypress", function(e) {
        if (e.keyCode == 13) {
            var search = $(this).val();
            window.location.href = "{{ url('/') }}/operator/transactions?search="+search
        }
    });
    $( "#button" ).click(function() {
        var search = $('#search').val();
        window.location.href = "{{ url('/') }}/operator/transactions?search="+search
    });
    $(document).ready(function(){
        $('#frm_payment').validate();
    });
    $('.reset-form').click(function(){
            $("#search").val('');
            window.location.href = "{{ url('/') }}/operator/transactions";
        });

   
</script>
@endsection