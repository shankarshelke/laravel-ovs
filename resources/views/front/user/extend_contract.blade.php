@extends('front.layout.master')
@section('main_content')
<style type="text/css">
    .listing-time-table-section.table-mange {max-width: 100%;width: 100%;margin: 0 auto;}
    .datepicker .day {color: #4bbd58 !important;}
    .datepicker table tr td.disabled, .datepicker table tr td.disabled:hover {color: #999 !important;}
</style>

<script src="{{url('/')}}/front_assets/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src='{{url('/')}}/front_assets/js/moment.min.js'></script>

<section class="gray-bg-main-section user-setting-page"> 

    <div class="container">

        <div class="request-quote-filter-section">
            <div class="row m-l--5 m-r--5">
                <div class="col-sm-8 col-md-8 col-lg-10">
                    <div class="filter-search-main">                            
                        <div class="form-group">
                            <span><i class="fa fa-search"></i></span>
                            <input type="search" name="search" type="text" placeholder="Search bookings by Id"  id="search" value="{{isset($_GET['search'])?$_GET['search']:''}}">
                        </div>
                    </div>
                </div>
                <div class="col-sm-2 col-md-2 col-lg-2 p-l-5 p-r-5">                    
                    <div class="search-filter-button">
                        <button id="button" class="filter-search-btn search-btn" id='button' ><i class="fa fa-search"></i> Search</button>
                        <a class="filter-icon-btn reset-form" href="javascript:void(0)"><i class="fa fa-repeat"></i></a>
                    </div>                        
                </div>
            </div>
        </div>

        <div class="listing-time-table-section table-mange">
            <div class="public-courses-table-block">
                <div class="transactions-table table-responsive">
                    @include('front.layout.operation_status')

                    @if(isset($arr_bookings['data']) && sizeof($arr_bookings['data'])>0)
                    @foreach($arr_bookings['data'] as $key=>$data)

                    @php
                        $arr_availability = $arr_dates = [];

                        if((isset($data['get_aircraft_details']) && $data['get_aircraft_details']['get_availablity1']) && !empty($data['get_aircraft_details']['get_availablity1'])){
                            $arr_availability = $data['get_aircraft_details']['get_availablity1'];
                        }

                        //To find dates between two dates as an array
                        foreach($arr_availability as $row)
                        {
                            $from = $row['from_date'];
                            $to = $row['to_date'];
                            $to = date('Y-m-d', strtotime($to . ' +1 day'));

                            $dates = new DatePeriod( new DateTime($from), new DateInterval('P1D'), new DateTime($to) );
                            foreach ($dates as $value) {
                                $arr_dates[] = $value->format('Y-m-d');
                            }
                        }
                    @endphp

                    <div class="table">

                        <div class="table-row">
                            <div class="table-cell">
                                <div class="operator-content-main-section">
                                    <div class="admin-content">
                                        <div class="admin-operator">
                                            <img src="{{url('/')}}/front_assets/images/plan.png" alt="">
                                        </div>
                                        <div class="admin-text">Booking Id :<span style="color: black;">
                                        {{isset($data['reservation_id']) ? ($data['reservation_id']):''}}</span>
                                        </div>
                                    </div>
                                <!--     <div class="admin-content">
                                        <div class="admin-operator">
                                            <img src="{{url('/')}}/front_assets/images/plan.png" alt="">
                                        </div>
                                        <div class="admin-text">Aircraft Operator : <span style="color: black;">{{isset($data['get_aircraft_details']['get_aircraft_owner']['first_name']) ? ucfirst($data['get_aircraft_details']['get_aircraft_owner']['first_name']):''}} {{isset($data['get_aircraft_details']['get_aircraft_owner']['last_name']) ? ucfirst($data['get_aircraft_details']['get_aircraft_owner']['last_name']):''}}</span>
                                        </div>
                                    </div> -->
                                </div>
                             

                                <div class="admin-content">
                                    <div class="admin-operator">
                                        <img src="{{url('/')}}/front_assets/images/calendar-img.png" alt="">
                                    </div>
                                    <div class="admin-text">From Date :<span style="color: black;"> {{isset($data['pickup_date'])? $data['pickup_date']:''}}</span></div>
                                </div>
                            </div>

                            <div class="table-cell">
                                <div class="seeting-chg">
                                    <img src="{{url('/')}}/front_assets/images/setting-chg.png" alt="chg" />
                                </div>
                            </div>

                            <div class="table-cell">
                                <div class="operator-content-main-section">
                                   <div class="admin-content">
                                    <div class="admin-operator">
                                        <img src="{{url('/')}}/front_assets/images/map.png" alt="">
                                    </div>
                                    <div class="admin-text">Location : <span style="color: black;">{{isset($data['pickup_location'])? $data['pickup_location']:''}}</span></div>
                                </div>
                                    <!-- <div class="admin-content">
                                        <div class="admin-operator">
                                            <img src="{{url('/')}}/front_assets/images/user-green.png" alt="">
                                        </div>
                                        <div class="admin-text">User<span style="color: black;"> {{isset($data['get_user_details']['first_name']) ? ucfirst($data['get_user_details']['first_name']):''}} {{isset($data['get_user_details']['last_name']) ? ucfirst($data['get_user_details']['last_name']):''}}</span>
                                        </div>
                                    </div> -->
                                </div>
                                <!-- <div class="admin-content">
                                    <div class="admin-operator">
                                        <img src="{{url('/')}}/front_assets/images/map.png" alt="">
                                    </div>
                                    <div class="admin-text">To : <span style="color: black;">{{isset($data['return_location'])? $data['return_location']:''}}</span></div>
                                </div> -->

                                <div class="admin-content">
                                    <div class="admin-operator">
                                        <img src="{{url('/')}}/front_assets/images/calendar-img.png" alt="">
                                    </div>
                                    <div class="admin-text">To Date : <span style="color: black;">{{isset($data['return_date'])? $data['return_date']:''}}</span></div>
                                </div>
                            </div>

                            <div class="table-cell">
                        <?php $result = get_extended_data($data['id']);?>
                                @if(isset($data['extend_requests']) && $data['extend_requests'][
                                    'status']=='PENDING')
                                    <button class="extend-req" >Pending</button>

                                @elseif(isset($data['extend_requests']) && $data['extend_requests'][
                                        'status']=='ALLOWED')
                                    <button class="extend-req" >Allowed</button>
                                        @if(isset($data['extend_requests']) && $data['extend_requests']['payment_status']=='PENDING')
                                        <button class="extend-req openModel" data-toggle="modal" data-target="#myModal1" id="review_modal" data-id="{{$data['id']}}" data-result="{{$result}}" onclick=" return ChkExetendPayment(this);">Pay</button>
                                        @elseif(isset($data['extend_requests']) && $data['extend_requests']['payment_status']=='SUBMITTED')

                                        <button class="extend-req">Wait for approval</button>
                                    @elseif(isset($data['extend_requests']) && $data['extend_requests']['payment_status']=='REJECTED')
                                        <button class="extend-req openModel" data-toggle="modal" data-target="#myModal1" id="review_modal" data-id="{{$data['id']}}" data-result="{{$result}}" onclick=" return ChkExetendPayment(this);">Pay Again</button>

                                        <p>Last Payment Rejected By admin</p>

                                    @endif

                                @elseif(isset($data['extend_requests']) && $data['extend_requests'][
                                            'status']=='REJECTED')
                                    <button class="extend-req" >Rejected</button>

                                @elseif(isset($data['extend_requests']) && $data['extend_requests'][
                                                'status']=='APPROVED')
                                    <button class="extend-req" >APPROVED</button>

                                @elseif( $data['is_signed'] != '1')
                                    <button class="extend-req" onclick="swal('Oops!', 'Please Complete your contract sign process first.','error')">Request to Extend</button>
                                @else
                                    <button class="extend-req" data-toggle="modal" data-target="#myModal_{{$key}}" id="review_modal" data-id="{{$data['id']}}" data-reservation-id="{{$data['reservation_id']}}" onclick="return ChkExetend(this);">Request to Extend</button>
                                @endif
                            </div>

                            <div class="table-cell">
                                <a class="extend-req power-off" href="{{url('/')}}/user/view_contract/{{base64_encode($data['id'])}}"><i class="fa fa-eye"></i>
                                </a>
                            </div>

                        </div>
                        <br>
                        <div class="clearfix"></div>
                    </div>

                    <!-- The Modal for Extend request-->
                    <div class="modal registration-modal give-feedback-form-main" id="myModal_{{$key}}" data-backdrop="static">
                        <div class="modal-dialog">
                            <form class="form-horizontal" id="frm_quotation" name="frm_quotation" action="{{url('/')}}/user/request_extend_contract/{{ base64_encode($data['id'])}}" method="post" enctype="multipart/form-data" >
                                {{csrf_field()}}
                                <input type="hidden" name="reservation_id" id="reservation_id">
                                <input type="hidden" name="reservation_enc_id" id="reservation_enc_id">
                                <div class="modal-content">
                                    <div class="close-icon" data-dismiss="modal"><img src="{{ url('/') }}/front_assets/images/close-img.png" alt="" /></div>
                                    <div class="modal-body">
                                        <div class="give-feedback-form request-quote-main booking-pending booking-completed">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-12 col-lg-12">
                                                    <div class="date-time-search">
                                                        <label>Extended Date <span style="color: red">*</span></label>
                                                        <div class="form-group">
                                                            <span><i class="far fa-calendar-alt"></i></span>
                                                            <input type="text" data-date-format='yyyy-mm-dd' placeholder="Date" id="extended_date_{{$key}}" name="extended_date" class="form-control" data-rule-required="true" autocomplete="off" readonly oninput="return false" />
                                                            <span class="error">{{ $errors->first('extended_date') }} </span>
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

                        var availableDates = [];

                        console.log(availableDates);

                        <?php
                        if(isset($arr_dates) && !empty($arr_dates)){
                            foreach($arr_dates as $row){
                                ?>
                                availableDates.push("{{ $row }}");
                                <?php
                            }
                        }
                        ?>
                        //console.log(availableDates);
                        $( "#extended_date_{{$key}}" ).datepicker({
                            todayHighlight: true,
                            autoclose: true,
                            beforeShowDay : function(dt) {
                                return available(dt);
                                //return false;
                            },
                            startDate:new Date("{{$data['pickup_date']}}"),
                        }).on('show', function(ev) {
                            $( "#extended_date_{{$key}}" ).attr('disabled', 'true');
                            //alert("show");
                        }).on('hide', function(ev) {
                            $( "#extended_date_{{$key}}" ).removeAttr('disabled');
                        });

                        function available (date) {
                            dmy = moment(date).format('YYYY-MM-DD');

                            console.log(($.inArray(dmy, availableDates) != -1));
                            console.log(availableDates);

                            if ($.inArray(dmy, availableDates) != -1) {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    </script>

                    @endforeach
                    @else
                    <div class="container">
                        <div class="background-container">
                            <div class="img-content">
                                <img src="{{ url('/') }}/front_assets/images/rocket.png" alt="">
                            </div> 
                            <div class="content-background">
                                <div class="result-not-found">No reservations to extend.</div>
                                <div class="please-try-again">Please try again later</div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="pagination">
                    <ul>
                        {!! $obj_bookings->appends(request()->except(['page','_token'])) !!}
                    </ul>
                </div>

            </div>
        </div>

    </div>
</section>

<!-- The Modal for Upload payment receipt-->
<div class="modal forgot-pwd-modal" id="myModal1" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="close-icon" data-dismiss="modal"><img src="{{ url('/') }}/front_assets/images/close-img.png" alt="" /></div>
            <!-- Modal body -->
            <div class="modal-body">
                <form action="{{ url('/').'/user/extend_contract_payment'}}" method="POST" id="contractForm" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="reservation_id_for_extend_payment" id="reservation_id_for_extend_payment">
                    <div class="image-icon">
                        <img src="{{ url('/') }}/front_assets/images/icon.png" alt="" />
                    </div>
                    <div class="upload-experience-certifcate">
                        <div class="form-group">
                            <label>Upload Payment Slip</label>
                            <div class="upload-block">
                                <input type="file" id="pdffile" style="visibility: hidden; height: 0;border: none" data-rule-required="true" name="payment_sleep" onchange="Changefilename(this)" accept=".xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf" />
                                <div class="input-group">
                                    <input type="text" class="alias_file file-caption kv-fileinput-caption" placeholder="Upload Payment Slip" readonly="" />
                                    <span class="error" id="error-pdffile">{{ $errors->first('pdffile') }} </span>
                                    <div class="btn btn-primary btn-file" onclick="$('#pdffile').click();"><i class="fa fa-upload"></i> File</div>
                                </div>
                            </div>
                        </div>
                            <!-- <label> Amount <span style="color: red">*</span></label>
                            <div class="upload-block">
                                  <div class="input-group">
                                    <input type="number" name="amount" id="amount" class="form-control" placeholder="Final amount" data-rule-required="true" data-rule-number="true" min="0"  onkeypress="return isNumberOnlyKey(event);" >
                                <span class="error">{{ $errors->first('amount') }} </span>
                                </div>
                            </div> -->
                        <div class="form-group">
                            <label> Amount <span style="color: red">*</span></label>
                            <div class="upload-block">
                                  <div class="input-group">
                                    <input type="number" name="amount" id="amount" class="form-control formdata" value="" placeholder="Final amount" data-rule-required="true" readonly="">
                                <span class="error">{{ $errors->first('amount') }} </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label> Transaction ID <span style="color: red">*</span></label>
                            <div class="upload-block">
                                  <div class="input-group">
                                    <input type="text" name="transaction" id="transaction" class="form-control" placeholder="Transaction ID" data-rule-required="true" >
                                <span class="error">{{ $errors->first('transaction') }} </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="retrieve-pwd-btn">
                        <button class="button-retrieve-pwd" data-dismiss="modal">Close</button>
                        <button class="button-retrieve-pwd" onclick="getData();"> Submit </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- The End of Modal -->

<script src="{{url('/')}}/front_assets/js/responsivetabs.js"></script>
<script src="{{url('/')}}/front_assets/js/gallery.min.js"></script>
<link rel="stylesheet" type="text/css" href="{{url('/')}}/front_assets/css/bootstrap-datepicker.min.css"/>
<script src="{{ url('/') }}/web_admin/assets/js/pages/jquery.geocomplete.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3&key={{ get_google_map_api_key() }}&libraries=places"></script>

<script type="text/javascript">
    
    function ChkExetend(ref)
    {
        var id = $(ref).attr('data-id');
        var reservation_enc_id = $(ref).attr('data-reservation-id');
        $('#reservation_id').val(id);
        $('#reservation_enc_id').val(reservation_enc_id);
    }

    function ChkExetendPayment(ref)
    {
        var id = $(ref).attr('data-id');
       
        $('#reservation_id_for_extend_payment').val(id);
    }

    function Changefilename(event){
        var file = event.files;
        name = file[0].name;
        var ret = validateDocument(file,'Doc',null);
        if(ret){
            $(event).next().children('input').val(name);
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

<script type="text/javascript">

$(document).ready(function() {
    $('#frm_quotation').validate();
    $("#contractForm").validate();
});

$(function()
{
    $('#pickup_date').datepicker({
        todayHighlight: true,
        autoclose: true,
        beforeShowDay: function(dt)
        {
            // return available(dt);
            return true;
        },
        startDate:new Date(),
    });

});

// function available(date) {
//     dmy = moment(date).format('YYYY-MM-DD');
//     if ($.inArray(dmy, availableDates) != -1) {
//         return true;
//     } else {
//         return false;
//     }
// }


/*$( "#extended_date" ).datepicker({
    todayHighlight: true,
    autoclose: true,
    beforeShowDay: function(dt)
    {
        // return available(dt);
        return true;
    },
    //startDate:moment().add('d', 1).toDate(),
    startDate:new Date(),
});*/

</script>

<script type="text/javascript">
    $('#search').on("keypress", function(e) {
        if (e.keyCode == 13) {
            var search = $(this).val();
            window.location.href = "{{ url('/') }}/user/extend_contract?search="+search
        }
    });

    $('input[name=extended_date]').on("focus", function(e) {
        return false;
    });
    
    $( "#button" ).click(function() {
        var search = $('#search').val();
        window.location.href = "{{ url('/') }}/user/extend_contract?search="+search
    });

    function isNumberOnlyKey(evt)
    {
    
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 46 || charCode > 57)) {
            return false;
        }
        return true;
    }
$('.reset-form').click(function(){
        $("#search").val('');
        window.location.href = "{{ url('/') }}/user/extend_contract";
    });
  $('.openModel').on('click',function(){
        var amount= $(this).attr('data-result');
        $('.formdata').val(amount);
    });


    //document.querSelector('bs-datepicker-container').addEventListener('click', evt => evt.stopPropagation());
</script>

@endsection