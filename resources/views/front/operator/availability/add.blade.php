@extends('front.layout.master')
@section('main_content')      
<style type="text/css">
    .fc-header-right {display: none;}
    .add-aircraft .fc-header { display: block; }
    .fc-event-time {display: none;}
</style>
<div class="login-main-section add-aircraft-main">
    <div class="container">
        <div class="signup-block-wrapper">
            @include('front.layout.operation_status')
        </div>
        <div id="pageloader" style="display: none;"><img src="{{url('/')}}/front_assets/images/material.gif" alt="processing..." /></div>
        <div class="add-aircraft form-5 label-5">
            <div class="back-button-section" style="display: block;">
                <a class="back-txt-block" href="{{ url('/').'/operator/aircrafts/availability/'.$enc_id }}">
                    <img src="{{ url('/') }}/front_assets/images/add-aircraft-back-arrow.png" alt="" /> Back
                </a>
            </div>
            <div class="signup-block active">
                <h2>
                    <span class="page-head-form form-head-5">Add Availability</span>
                </h2>
            </div>
            <div id="operationStatus"></div>

            <form method="POST" action='{{url('/')}}/operator/aircrafts/availability/store/{{$enc_id}}' id='frm_aircraft_five' enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="signup-step-five">
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group datepicker-main">
                                <label>From Date</label>
                                <span class="input-right-icon"><i class="fa fa-calendar"></i></span>
                                <input type="text" id="from_date" placeholder="From" name="from_date" data-rule-required="true" data-rule-date="true" data-rule-pattern="(0?[1-9]|[12][0-9]|3[01])/(0?[1-9]|1[012])/((19|20)\\d\\d)">
                                <span class="error">{{ $errors->first('from_date') }} </span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group datepicker-main">
                                <label>To Date</label>
                                <span class="input-right-icon"><i class="fa fa-calendar"></i></span>
                                <input type="text" id="to_date" name="to_date" placeholder="To" data-rule-required="true" data-rule-date="true" data-rule-pattern="(0?[1-9]|[12][0-9]|3[01])/(0?[1-9]|1[012])/((19|20)\\d\\d)">
                                <span class="error">{{ $errors->first('to_date') }} </span>
                            </div>
                        </div>
                    </div>  
                    <div id="calendar1" class="has-toolbar"></div>
                    <button type='submit' class="full-orng-btn">Add Availability</button>
                </div>
            </form>
        </div>            
    </div>
</div>  

    
<link rel="stylesheet" type="text/css" href="{{url('/')}}/front_assets/css/bootstrap-datepicker.min.css"/>
<script src="{{url('/')}}/front_assets/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src='{{url('/')}}/front_assets/js/moment.min.js'></script> 
<link href="{{url('/')}}/front_assets/css/fullcalendar.css" rel="stylesheet">
<script type="text/javascript" src="{{url('/')}}/front_assets/js/fullcalendar.min.js"></script>
<script src="{{ url('/') }}/front_assets/js/jquery.form.min.js"></script>
<script type="text/javascript">
    $(document).ready(function()
    {
        $('#calendar1').fullCalendar({
            header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay',
          },
            defaultView: 'month',
            dayClick: function(date, jsEvent, view, event)
            {
                $('.start_date').val(''); 
                $('.client_name').val('');
                $('.description').val('');
                $("#tab-1").show();
                $("#tab-2").hide();
                $("#new_booking").show();
                $("#block_of_time").show();
                $('#myModal').modal('show');

                var type1 = $('#type1').val();
                $(".abc").hide();

                var start_date      = $('.start_date').val();
                var client_name     = $('.client_name').val();
                var description     = $('.description').val();

                $(".start_date").val(start_date);    
                $(".client_name").val(client_name);   
                $(".description").val(description);   
            },

            viewDisplay: function(view)
            {
              try {
                    setTimeline();
                } catch(err) {}
            },
            agenda: 'h:mm{ - h:mm}',
                    '': 'h(:mm)t',
            editable: false,
            defaultView: 'month',
            firstDay: 1,
            allDayDefault: false,
            events: [
                    <?php
                        if(isset($arr_availability) && !empty($arr_availability)) :
                            foreach( $arr_availability as $key => $row ) :
                    ?>
                        {
                            'booking_type':'booking',
                            'id':'{{ $key or '' }}',
                            'start':moment('{{ $row['from_date'] }}').toDate(),
                            'end':moment('{{ $row['to_date'] }}').add('d', 1).toDate()
                        },
                    <?php
                            endforeach;
                        endif;
                    ?>
                    ],

            selectable: true,
            selectHelper: true,
            select: function (start, end, jsEvent, view){
                var check = $.fullCalendar.formatDate(start,'yyyy-MM-dd');
                var today = $.fullCalendar.formatDate(new Date(),'yyyy-MM-dd');
                if(check < today)
                {
                    $('#from_date').val('');
                    $('#to_date').val('');
                    swal('Oops!', 'Past dates not allowed!','error');
                }
                else
                {
                    from    = moment(start).format('MM/DD/YYYY');
                    to      = moment(end).format('MM/DD/YYYY');
                    $('#from_date').val(from);
                    $('#to_date').val(to);
                }
            },
            selectOverlap: function(event) {
                return ! event.block;
            },
            eventClick: function(event, jsEvent, view)
            {
            $(".abc").show();
                var booking = [];
                var blocktime = [];
                var title = event.title;    
                var booking_type = event.booking_type;                
                if(booking_type == 'booking')
                {
                    $("#tab-1").show();
                    $("#tab-2").hide();
                    $("#new_booking").show();
                    $("#block_of_time").hide();
                }

                else if(booking_type == 'blocktime')
                {
                    $("#tab-1").hide();
                    $("#tab-2").show();
                    $("#new_booking").hide();
                    $("#block_of_time").show();
                }
                else
                {
                    $("#tab-1").show();
                    $("#tab-2").show();
                    $("#new_booking").show();
                    $("#block_of_time").show();
                }

                var token = $('#token').val();
                if(title)
                {
                    event.title = title;
                    event.booking_type = booking_type;
                    var data = 
                    {
                        'title'  : title,
                        'booking_type'  : booking_type,
                        '_token' : token,
                    };
                    $.ajax({
                        url: url+'/ajax/edit_booking/'+event.id+'/'+event.booking_type,
                        data: data,
                        type: 'POST',
                        dataType: 'json',
                        success: function(res)
                        {
                            $(".start_date").val(res.start_date);
                            $("#block_end_date").val(res.end_date);
                            $("#client_name").val(res.client_name);
                            $("#event_type").val(res.event_type);
                            $(".description").val(res.event_name);
                            $(".event_id").val(res.event_id);                            
                            $("#btn_save").attr('data-type','edit');
                            $("#btn_block_time").attr('data-type','edit');
                            $(".booking_type").val(res.booking_type);
                            $("#type,#type1").val('Edit');
                            $('#myModal').modal('show');

                            if(booking_type == 'booking')
                            {
                                $(".abc").attr('href', url+'/talent/book_delete/'+res.event_id);
                            }
                            else if(booking_type == 'blocktime')
                            {
                                $(".abc").attr('href', url+'/talent/block_delete/'+res.event_id);
                            }
                        }
                    });
                }
            }    
        });

    });                
</script>
    
<script>
    $(function() {
        $( "#from_date" ).datepicker({
            todayHighlight: true,
            autoclose: true,
            startDate:new Date(),
        });

        $( "#to_date" ).datepicker({
            //todayHighlight: true,
            autoclose: true,
            startDate:moment().add('d', 1).toDate(),
        });
    });
</script>

<script>

$(document).ready(function()
{
    $("#frm_aircraft_five").validate({
        ignore : []
    });

    $('#from_date, #to_date').change(function()
    {
        var to_date = $("#to_date").val();
        var from_date = $("#from_date").val();

        if(new Date(to_date) < new Date(from_date))
        {
            $("#to_date").val('');
            swal('Error!','Last date should be greater than Start date','error');
        }
    });

});
</script>
@endsection