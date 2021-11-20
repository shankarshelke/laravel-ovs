@extends('front.layout.master')
@section('main_content')
<style type="text/css">
/*.errors { color: red; float: left; display: none; }*/
.form-group .error { position: initial; }
.terms-block .check-block { display: block; }
.form-2.add-aircraft { min-height: 830px; }
.tag.label.label-info { color:red; }
.radio { display: inline-block; }
.bootstrap-tagsinput { display: block; width: 100%; text-align: left;}
</style>

<div class="login-main-section add-aircraft-main">
    <div class="container">          
        <div class="add-aircraft form-1">
            @include('front.layout.operation_status')
            <div class="back-button-section">
                <a class="back-txt-block" href="javascript:void(0)">
                    <img src="{{ url('/') }}/front_assets/images/add-aircraft-back-arrow.png" alt="" /> Back
                </a>
            </div>
            <div class="signup-block">
                <h2>
                    <span class="page-head-form form-head-1">Edit Aircraft</span>
                    <span class="page-head-form form-head-2">Add Aircraft Type</span>
                    <span class="page-head-form form-head-3">Add Aircraft Inventory</span>
                    <span class="page-head-form form-head-4">Specifications, Amenities &amp; Equipments</span>
                    <span class="page-head-form form-head-5">Add Availability</span>
                </h2>
            </div>
            <div class="step-dot-section">
                <span class="form-one active"></span>
                <span class="form-two"></span>
                <span class="form-three"></span>
                <span class="form-four"></span>
                {{-- <span class="form-five"></span> --}}
            </div>
            <?php
                $existing_images = $img_tmp = [];
                if(isset($arr_aircraft['images']) && $arr_aircraft['images'] != ''){
                    $img_tmp = unserialize($arr_aircraft['images']);
                }
                if(!empty($img_tmp)){
                    foreach($img_tmp as $key => $tmp_img){
                        if($tmp_img != '' && file_exists($tmp_img_base_path.$tmp_img)){
                            $existing_images[$key]['base'] = $tmp_img_base_path.$tmp_img;
                            $existing_images[$key]['public'] = $tmp_img_public_path.$tmp_img;
                        }
                    }
                }
            ?>
            <div id="operationStatus"></div>
            <form action = '{{url('/')}}/operator/aircrafts/store' method="POST" name='frm_aircraft_one' id='frm_aircraft_one' enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="page" value="first_page">
                <div class="signup-step-one">                        
                    <div class="form-group">
                        <label>Aircraft Name</label>
                        <input type="text" placeholder="Aircraft Name" name="aircraft_name" value="{{isset($arr_aircraft['name'])?$arr_aircraft['name']: ''}}" id ="aircraft_name" data-rule-required="true">
                        <label id="error-aircraft_name" class="error" for="aircraft_name"></label>
                    </div>
                    <div class="form-group">
                        <label>Aircraft Description</label>
                        <textarea placeholder="Aircraft Description" name="description" id="description" data-rule-required="true">{{isset($arr_aircraft['description'])?$arr_aircraft['description']: ''}}</textarea>
                        <label id="error-description" class="error" for="description"></label>
                    </div>
                    <div class="form-group">
                        <div class="multyselect-dropzone-section">
                            <label>Upload Images</label>
                            <span data-multiupload="3">
                                <span data-multiupload-holder></span>
                                <span class="upload-photo">
                                    <img src="{{ url('/') }}/front_assets/images/upload-defolt-img.png" alt="plus img">
                                    <?php
                                        if(isset($existing_images) && !empty($existing_images)) :
                                            foreach($existing_images as $tmp_img) : ?>
                                            {{-- <input name='image[]' type="file" value="{{ $tmp_img['public'] }}"> --}}
                                    <?php
                                            endforeach;
                                        endif;
                                    ?>
                                    <input data-multiupload-src class="upload_pic_btn" id='image' name='image[]' type="file" multiple accept="image/x-png, image/gif, image/jpeg, image/jpg" data-rule-required="true" data-msg-required="Please upload atleast one image.">
                                    <span data-multiupload-fileinputs></span>
                                </span>
                            </span>
                        </div>
                        <div class="clearfix"></div>
                        <label id="error-image" class="error" for="image"></label>
                    </div>
                    <button type="submit" class="full-orng-btn for-step-two" >Save and Next</button>
                </div>
            </form>
            <?php
                $exist_op_cap = [];
                if(isset($arr_aircraft['operational_capability']) && $arr_aircraft['operational_capability']!=''){
                    $exist_op_cap = json_decode($arr_aircraft['operational_capability']);
                }
            ?>
            {{-- {{ dd($arr_aircraft['less_mgh_cost']) }} --}}
            <form action = '{{url('/')}}/operator/aircrafts/store' method="POST" id='frm_aircraft_two' enctype="multipart/form-data">
                {{csrf_field()}}
                <input type="hidden" name="page" value="second_page">
                <div class="signup-step-two">
                    <div class="form-group">
                        <label>Aircraft Type <span style="color:red">*</span></label>
                        <i class="fa fa-angle-down"></i>
                        <select id='aircraft_type' name = 'aircraft_type' data-rule-required="true">
                            <option value=''> -- Select Aircraft Type -- </option>
                            <option value='fixed_wings' {{ (isset($arr_aircraft['type_name']) && $arr_aircraft['type_name'] == 'fixed_wings') ? 'selected' : '' }}>Fixed Wings</option>
                            <option value='rotating_wings' {{ (isset($arr_aircraft['type_name']) && $arr_aircraft['type_name'] == 'rotating_wings') ? 'selected' : '' }}>Rotating Wings</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Price per hour <span style="color:red">*</span></label>
                        <input type="text" placeholder="Price per hour" id='price' name='price' data-rule-digits="true" data-rule-required='true' value="{{ $arr_aircraft['price_per_hour'] or '' }}" >
                    </div>
                    <div class="form-group">
                        <label>Operational Capability </label>
                        <div class="checkbox check-block">
                            <input type="checkbox" class="filled-in" name='oper_capability[]' id='cargo' value="cargo" {{ in_array('cargo', $exist_op_cap) ? 'checked' : '' }} >
                            <label for='cargo'>Cargo</label>
                        </div>
                        <div class="checkbox check-block">
                            <input type="checkbox" class="filled-in" name='oper_capability[]' id='casualty' value="casualty_or_medical_evacuation" {{ in_array('casualty_or_medical_evacuation', $exist_op_cap) ? 'checked' : '' }}>
                            <label for='casualty'>Casualty or Medical Evacuation</label>
                        </div>
                        <div class="checkbox check-block">
                            <input type="checkbox" class="filled-in" name='oper_capability[]' id='sling_operations' value="external_sling_operations" {{ in_array('external_sling_operations', $exist_op_cap) ? 'checked' : '' }}>
                            <label for='sling_operations'>External Sling Operations</label>
                        </div>
                        <div class="checkbox check-block">
                            <input type="checkbox" class="filled-in" name='oper_capability[]' id='firefighting' value="firefighting" {{ in_array('firefighting', $exist_op_cap) ? 'checked' : '' }}>
                            <label for='firefighting'>firefighting</label>
                        </div>
                        <div class="checkbox check-block">
                            <input type="checkbox" class="filled-in"  name='oper_capability[]' id='para-jump' value="para_jump" {{ in_array('para_jump', $exist_op_cap) ? 'checked' : '' }}>
                            <label for='para-jump'>Para-jump</label>
                        </div>
                        <div class="checkbox check-block">
                            <input type="checkbox" class="filled-in" name='oper_capability[]' id='rescue' value="Search_rescue" {{ in_array('Search_rescue', $exist_op_cap) ? 'checked' : '' }}>
                            <label for='rescue'>Search & Rescue</label>
                        </div>
                        <div class="checkbox check-block">
                            <input type="checkbox" class="filled-in" name='oper_capability[]' id='observation' value="observation_reconnaissance" {{ in_array('observation_reconnaissance', $exist_op_cap) ? 'checked' : '' }}>
                            <label for='observation'>Observation & Reconnaissance</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Aircraft Capacity <span style="color:red">*</span></label>
                          <input type="text" placeholder="Aircraft Capacity" id='capacity' name = 'capacity' data-rule-required="true" data-rule-digits='true' value="{{isset($arr_aircraft['capacity'])?$arr_aircraft['capacity']: ''}}">
                       
                    </div>                                                     
                    <button type="submit" class="full-orng-btn for-step-three" >Save and Next</button>
                </div> 
            </form>
            <form  method="POST" id ='frm_aircraft_three' enctype="multipart/form-data">
                {{csrf_field()}}
                <meta name="csrf-token" content="{{ csrf_token() }}"> 
                <input type="hidden" name="page" value="third_page">
                <div class="signup-step-three">
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>Aircraft Model<span style="color: red">*</span></label>
                                <i class="fa fa-angle-down"></i>
                                <select name='aircraft_model' data-rule-requird="true" id="aircraft_models">
                                    <option> -- Select Model -- </option>
                                    @if(isset($aircraft_models) && !empty($aircraft_models))
                                    @foreach($aircraft_models as $model)
                                        <option value="{{ $model['id'] or '' }}" {{ ( isset($arr_aircraft['model_name']) && $arr_aircraft['model_name'] == $model['id']) ? 'selected' : '' }}>{{ $model['model_name'] }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div> 
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>Aircraft Quantity</label>
                                <input type="text" placeholder="Aircraft Quantity" id ="quantity" data-rule-digits="true" name="quantity" value="{{isset($arr_aircraft['quantity'])?$arr_aircraft['quantity']: ''}}" data-rule-required="true" >
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>Base of Operation<span style="color: red">*</span></label>
                                {{-- <input type="text" name="base_of_operation" id="base_of_operation"> --}}
                                <i class="fa fa-angle-down"></i>
                                <select name="base_of_operation" data-rule-requird="true">
                                    <option> -- Select -- </option>
                                    @if(isset($arr_countries) && !empty($arr_countries))
                                    @foreach($arr_countries as $country)
                                    <option value="{{ $country['name'] or '' }}" {{ ( isset($arr_aircraft['base_of_operation']) && $arr_aircraft['base_of_operation'] == $country['name']) ? 'selected' : '' }}>{{ $country['name'] }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>Minimum Gaurantee hours/month Required</label>
                                <input type="text" placeholder="Minimum Gaurantee hours/month Required" id ="min_gaurantee_hr" name="min_gaurantee_hr" value ="{{ $arr_aircraft['min_gaurantee_hours'] or '' }}" data-rule-digits=”true” data-rule-required="true">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group cost-hover-angle">
                                <label>Cost / hour <i class="fa fa-angle-right"></i> 50MGH </label>
                                <input type="text" placeholder="Cost / hour > 50MGH " id="cost_less_50" name="cost_less_50" data-rule-digits="true" data-rule-required="true" value="{{ $arr_aircraft['less_mgh_cost'] or '' }}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group cost-hover-angle">
                                <label>Cost / hour <i class="fa fa-angle-left"></i> 50MGH </label>
                                <input type="text" placeholder="Cost / hour < 50MGH " id="cost_greater_50" name="cost_greater_50" data-rule-digits="true" data-rule-required="true" value="{{ $arr_aircraft['more_mgh_cost'] or '' }}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>Other Charges</label>
                                <input type="text" placeholder="Other Charges" id ="other_charges" name="other_charges" value ="{{isset($arr_aircraft['other_charges'])?$arr_aircraft['other_charges']: ''}}" data-rule-digits=”true” data-rule-required="true" >
                            </div>
                        </div>                        
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>Default Location of Aircraft</label>
                                <input type="text" placeholder="Default Location of Aircraft" id ="default_location" name="default_location" value ="{{ $arr_aircraft['default_location'] or '' }}" data-rule-required="true">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>Registration Number</label>
                                <input type="text" placeholder="Registration Number"  id ="registration_no" name="registration_no" value ="{{$arr_aircraft['registration_no'] or ''}}" data-rule-required="true">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Positioning & Re-positioning</label>
                            <div class="radio">
                                <label for='positioning'>
                                    <input type="radio" class="filled-in" name='positioning' id='positioning' value="INCLUDED" {{ (isset($arr_aircraft['positioning']) && $arr_aircraft['positioning'] == 'included') ? 'checked' : '' }} data-rule-required="true" >
                                    Included
                                </label>
                            </div>
                            <div class="radio">
                                <label for='re-positioning'>
                                    <input type="radio" class="filled-in" name='positioning' id='re-positioning' value="NOT_INCLUDED" {{ ( isset($arr_aircraft['positioning']) && $arr_aircraft['positioning'] == 'NOT_INCLUDED') ? 'checked' : '' }} data-rule-required="true" >
                                    Not Included
                                </label>
                            </div>
                            <label id="positioning-error" class="error" for="positioning"></label>
                        </div>
                    </div> 
                    <button type="submit" class="full-orng-btn for-step-four" >Save and Next</button>
                </div>
            </form>
            <form action = '{{url('/')}}/operator/aircrafts/store' method="POST" id ='frm_aircraft_four' enctype="multipart/form-data">
                {{csrf_field()}}
                <meta name="csrf-token" content="{{ csrf_token() }}"> 
                <input type="hidden" name="page" value="fourth_page">
                <div class="signup-step-four">
                    <div class="specifications-head-seciton">
                        Specifications
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>Engines</label>
                                <input type="text" placeholder="Engines" id ="engine" name="engine" value ="{{isset($arr_aircraft['engine'])?$arr_aircraft['engine']: ''}}"  data-rule-required="true">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>Props</label>
                                <input type="text" placeholder="Props" id ="props" name="props" value ="{{isset($arr_aircraft['props'])?$arr_aircraft['props']: ''}}" data-rule-required="true">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <div class="specifications-head-seciton">
                                Amenities
                            </div>
                            <div class="form-group">
                                <label>Avionics</label>
                               {{--  <input type="text" value="Amsterdam,Washington,Sydney,Beijing,Cairo" data-role="tagsinput" /> --}}
                                <input type ='text' id="avionics" name="avionics" data-rule-required="true" value='' data-role="tagsinput"/>
                                <div class="clearfix"></div>
                                <label id="avionics-error" class="error" for="avionics"></label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <div class="specifications-head-seciton">
                                Equipment
                            </div>
                            <div class="form-group">
                                <label>Enter Equipment Details in Aircraft</label>
                                <input type ='text' {{-- placeholder="Enter Equipment Details in Aircraft" --}} id="equipment" name="equipment" data-rule-required="true" data-role="tagsinput"/>
                                <div class="clearfix"></div>
                                <label id="equipment-error" class="error" for="equipment"></label>
                            </div>
                        </div>
                    </div>
                    <button type ='submit' href="javascript:void(0)" class="full-orng-btn for-step-five"> Add Aircraft</button>
                </div>
            </form>
            <form action = '{{url('/')}}/operator/aircrafts/store' method="POST" id ='frm_aircraft_five' enctype="multipart/form-data">
                {{csrf_field()}}
                <input type="hidden" name="page" value="fifth_page">
                <div class="signup-step-five">
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group datepicker-main">
                                <label>From Date</label>
                                <span class="input-right-icon"><i class="fa fa-calendar"></i></span>
                                <input type="text" id="datepicker" placeholder="From">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group datepicker-main">
                                <label>To Date</label>
                                <span class="input-right-icon"><i class="fa fa-calendar"></i></span>
                                <input type="text" id="datepicker1" placeholder="To">
                            </div>
            
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>Aircraft Capacity <span style="color: red">*</span></label>
                                <i class="fa fa-angle-down"></i>
                                <select>
                                    <option>Aircraft Capacity</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>Aircraft Capacity <span style="color: red">*</span></label>
                                <i class="fa fa-angle-down"></i>
                                <select>
                                    <option>Aircraft Capacity</option>
                                </select>
                            </div>  
                        </div>
                    </div>  
                    <div id="calendar1" class="has-toolbar"></div>
                    <div class="color-what-we-uesed-main">
                        <div class="color-what-we-uesed">
                            <span class="color-circle-main"></span>
                            <span>Confirmed Reservation</span>
                        </div>
                        <div class="color-what-we-uesed pending-reservation-main">
                            <span class="color-circle-main"></span>
                            <span>Pending Reservation on hold</span>
                        </div>
                        <div class="color-what-we-uesed available-reservation-main">
                            <span class="color-circle-main"></span>
                            <span>All Available Reservations</span>
                        </div>
                    </div>
                    <button  type ='submit' class="full-orng-btn">Add Aircraft {{-- Availability --}}</button>
                </div>
            </form>
        </div>            
    </div>
</div>  
    
    
<link rel="stylesheet" type="text/css" href="{{url('/')}}/front_assets/css/bootstrap-datepicker.min.css"/>
<script src="{{url('/')}}/front_assets/js/bootstrap-datepicker.min.js" type="text/javascript"></script> 
<link href="{{url('/')}}/front_assets/css/fullcalendar.css" rel="stylesheet">
<script type="text/javascript" src="{{url('/')}}/front_assets/js/fullcalendar.min.js"></script>
<script src="{{url('/')}}/front_assets/js/bootstrap-tagsinput.js" type="text/javascript"></script> 
 <link href="{{url('/')}}/front_assets/css/bootstrap-tagsinput.css" rel="stylesheet"> 
{{-- <script src="{{url('/')}}/front_assets/js/bootstrap-tagsinput-angular.js" type="text/javascript"></script>    --}}
<script src="{{ url('/') }}/web_admin/assets/js/pages/jquery.geocomplete.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3&key={{ get_google_map_api_key() }}&libraries=places"></script>
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

                var start_date = $('.start_date').val();
                var client_name   = $('.client_name').val();
                var description   = $('.description').val();

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
            events: [{'booking_type':'booking','id':'22','title':'','name':'Tushar Ahire','start':'2017-03-17','end':'2017-03-31'},{'booking_type':'booking','id':'53','title':'Royal Weeding Events1','name':'Anna Adam','start':'2017-04-07','end':'2017-04-12'},{'booking_type':'booking','id':'54','title':'test','name':'Jai Thakare','start':'2017-04-19','end':'2017-04-20'}],

            selectable: true,
            selectHelper: true,

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

        $('#aircraft_type').change(function(){
            var selectedType = $(this).children("option:selected").val();
            $.ajax({
                url : '{{ url('/') }}/get_models_by_type/'+selectedType,
                data: { _token : "{{ csrf_token() }}", },
                success : function(resp) {
                    if(resp.status == 'success'){
                        console.log(resp.html);
                        if(resp.html != undefined && resp.html != ''){
                            html = '<option> -- Select Model -- </option>'+resp.html;
                            $("#aircraft_models").html(resp.html);
                        }
                    }
                }
            })
        });

        var input = document.getElementById('default_location');
        var options = {
            types: ['(regions)']
        };

        autocomplete = new google.maps.places.Autocomplete(input, options);

        /*google.maps.event.addListener(autocomplete, 'place_changed', function(data) {
            var place = autocomplete.getPlace();
            $.each(place.address_components, function(index, value){
                if(this.types[0] == "country")
                {
                    $('#base_of_operation').val(this.long_name);                  
                }
            });
        });*/
    });                
</script>
    
<script>
    $(function() {
        $( "#datepicker" ).datepicker({
            todayHighlight: true,
            autoclose: true,
        });
    });
    $(function() {
        $( "#datepicker1" ).datepicker({
            todayHighlight: true,
            autoclose: true,
        });
    });
</script>

<script>
    //dropzone script with multiple files
    (function($) {

        function readMultiUploadURL(input, callback) {

            if (input.files) {
                $.each(input.files, function(index, file)
                {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        callback(false, e.target.result);
                    }
                    reader.readAsDataURL(file);
                });
            }
            callback(true, false);
        }
     
        var arr_multiupload = $("span[data-multiupload]");

        if (arr_multiupload.length > 0) {
            $.each(arr_multiupload, function(index, elem) {
                var container_id = $(elem).attr("data-multiupload");
     
                var id_multiupload_img = "multiupload_img_" + container_id + "_";
                var id_multiupload_img_remove = "multiupload_img_remove" + container_id + "_";
                var id_multiupload_file = id_multiupload_img + "_file";
     
                var block_multiupload_src = "data-multiupload-src-" + container_id;
                var block_multiupload_holder = "data-multiupload-holder-" + container_id;
                var block_multiupload_fileinputs = "data-multiupload-fileinputs-" + container_id;
     
                var input_src = $(elem).find("input[data-multiupload-src]");
                $(input_src).removeAttr('data-multiupload-src').attr(block_multiupload_src, "");
     
                var block_img_holder = $(elem).find("span[data-multiupload-holder]");
                $(block_img_holder).removeAttr('data-multiupload-holder').attr(block_multiupload_holder,"");

                var block_fileinputs = $(elem).find("span[data-multiupload-fileinputs]");
                $(block_fileinputs).removeAttr('data-multiupload-fileinputs').attr(block_multiupload_fileinputs,"");

                $(input_src).on('change', function(event) {

                    readMultiUploadURL(event.target, function(has_error, img_src) {
                        if (has_error == false) {
                            addImgToMultiUpload(img_src);
                        }
                    })
                });

                <?php if(isset($existing_images) && !empty($existing_images)) : 
                        foreach($existing_images as $tmp_img) : ?>
                            addImgToMultiUpload("{{ $tmp_img['public'] }}");
                <?php   endforeach;
                    endif;
                ?>
     
                function addImgToMultiUpload(img_src) {
                    var id = Math.random().toString(36).substring(2, 10);

                    var html = '<div class="upload-photo" id="' + id_multiupload_img + id + '">' +
                    '<span class="upload-close">' +
                    '<a href="javascript:void(0)" id="' + id_multiupload_img_remove + id + '" ><i class="fa fa-trash-o"></i></a>' +
                    '</span>' +
                    '<img src="' + img_src + '" >' +
                    '<input name="image[]" type="file" value="'+img_src+'">'+
                    '</div>';

                    var file_input = '<input type="file" name="file[]" id="' + id_multiupload_file + id + '" style="display:none" />';
                    $(block_img_holder).append(html);
                    $(block_fileinputs).append(file_input);

                    bindRemoveMultiUpload(id);
                }
     
                function bindRemoveMultiUpload(id) {
                    $("#" + id_multiupload_img_remove + id).on('click', function() {
                        $("#" + id_multiupload_img + id).remove();
                        $("#" + id_multiupload_file + id).remove();
                    });
                }


            });
        }
    })(jQuery);
</script>

<script>
    //for next form
    $("body").on("click",'.back-form-1', function(){
        $(".add-aircraft").addClass("form-1");
        $(".add-aircraft").removeClass("form-2 label-2");
        $(".form-two").removeClass("active");
        $(".form-one").addClass("active");
    });
    $("body").on("click",'.back-form-2', function(){
        $(".add-aircraft").addClass("label-2");
        $(".add-aircraft").removeClass("form-3 label-3");
        $(".form-two").addClass("active");            
        $(".form-three").removeClass("active");
        $(this).addClass("back-form-1")
        $(this).removeClass("back-form-2")
    });
    $("body").on("click",'.back-form-3', function(){
        $(".add-aircraft").addClass("label-3");
        $(".add-aircraft").removeClass("form-4 label-4");            
        $(".form-three").addClass("active");
        $(".form-four").removeClass("active");
        $(this).addClass("back-form-2")
        $(this).removeClass("back-form-3")
    })
    $("body").on("click",'.back-form-4', function(){
        $(".add-aircraft").addClass("label-4");
        $(".add-aircraft").removeClass("form-5 label-5");            
        $(".form-four").addClass("active");
        $(".form-five").removeClass("active");
        $(this).addClass("back-form-3")
        $(this).removeClass("back-form-4")
    });

$(document).ready(function()
{
    //form -one
    $('#frm_aircraft_one').validate();

    $('#frm_aircraft_one').submit(function(e)
    {
        e.preventDefault();
        if($('#frm_aircraft_one').valid())
        {
            $('#frm_aircraft_one').ajaxSubmit({
                dataType  : 'json',
                success: function(data)
                {
                    if(data.status == 'fail')
                    {
                        var errorsHtml = '';
                        if(data.errors != undefined && Object.entries(data.errors).length > 0){
                            $('#frm_aircraft_one').find('.error').html('');
                            $.each(data.errors, function( key, value ) {
                                errorsHtml = $('#error-'+key).html(value[0]);
                            });
                        }
                        if(data.customMsg!=''){
                            $("#operationStatus").html('<div class="alert alert-danger no-border"><span class="text-semibold">Error!</span> '+data.customMsg+'<a href="#" class="alert-link"></a></div>');
                            $( "#operationStatus" ).scroll();
                        }
                    }
                    else if(data.status == 'success')
                    {
                        $(".add-aircraft").removeClass("form-1"); 
                        $(".add-aircraft").addClass("form-2 label-2"); 
                        $(".form-two").addClass("active");
                        $(".form-one").removeClass("active");
                        $(".back-txt-block").addClass("back-form-1");
                    }
                }
            });
        }
    });

    //form -two
    $('#frm_aircraft_two').validate();

    $('#frm_aircraft_two').submit(function(e)
    {
        e.preventDefault();

        if($('#frm_aircraft_two').valid())
        {
            var form_data = $('#frm_aircraft_two').serialize();
            var url = "{{ url('/') }}/operator/aircrafts/store";
            $.ajax({
                url: url,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                data: form_data,
                success: function(data){
                    if(data.status == 'fail')
                    {
                        var errorsHtml = '';
                        if(data.errors != undefined && Object.entries(data.errors).length > 0){
                            $('#frm_aircraft_one').find('.error').html('');
                            $.each(data.errors, function( key, value ) {
                                errorsHtml = $('#error-'+key).html(value[0]);
                            });
                        }
                        if(data.customMsg!=''){
                            $("#operationStatus").html('<div class="alert alert-danger no-border"><span class="text-semibold">Error!</span> '+data.customMsg+'<a href="#" class="alert-link"></a></div>');
                            $( "#operationStatus" ).scroll();
                        }
                    }
                    else if(data.status == 'success')
                    {
                        $(".add-aircraft").removeClass("label-2"); 
                        $(".add-aircraft").addClass("form-3 label-3"); 
                        $(".form-two").removeClass("active");            
                        $(".form-three").addClass("active");
                        $(".back-txt-block").addClass("back-form-2");
                        $(".back-txt-block").removeClass("back-form-1");
                    }
                }
            });
        }
    });

    //form-three
    $('#frm_aircraft_three').validate();

    $('#frm_aircraft_three').submit(function(e)
    {
         e.preventDefault();
        if($('#frm_aircraft_three').valid())
        {
            var form_data = $('#frm_aircraft_three').serialize();
            var url = "{{ url('/') }}/operator/aircrafts/store";
            $.ajax({
                url: url,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                data: form_data,
                success: function(data){
                    if(data.status == 'fail')
                    {
                        var errorsHtml = '';
                        if(data.errors != undefined && Object.entries(data.errors).length > 0){
                            $('#frm_aircraft_one').find('.error').html('');
                            $.each(data.errors, function( key, value ) {
                                errorsHtml = $('#error-'+key).html(value[0]);
                            });
                        }
                        if(data.customMsg!=''){
                            $("#operationStatus").html('<div class="alert alert-danger no-border"><span class="text-semibold">Error!</span> '+data.customMsg+'<a href="#" class="alert-link"></a></div>');
                            $( "#operationStatus" ).scroll();
                        }
                    }
                    else if(data.status == 'success')
                    {
                        $(".add-aircraft").removeClass("label-3"); 
                        $(".add-aircraft").addClass("form-4 label-4"); 
                        $(".form-three").removeClass("active");                        
                        $(".form-four").addClass("active");
                        $(".back-txt-block").addClass("back-form-3");
                        $(".back-txt-block").removeClass("back-form-2");
                    }
                }
            });
        }
    });

    $.validator.setDefaults({
        ignore: []
    });

    $('#frm_aircraft_four').validate();

    $('#frm_aircraft_four').submit(function(e)
    {
        e.preventDefault();
        if($('#frm_aircraft_four').valid())
        {
            var form_data = $('#frm_aircraft_four').serialize();
            var url = "{{ url('/') }}/operator/aircrafts/store";
            $.ajax({
                url: url,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                data: form_data,
                success: function(data){
                    if(data.status == 'fail')
                    {
                        var errorsHtml = '';
                        if(data.errors != undefined && Object.entries(data.errors).length > 0){
                            $('#frm_aircraft_one').find('.error').html('');
                            $.each(data.errors, function( key, value ) {
                                errorsHtml = $('#error-'+key).html(value[0]);
                            });
                        }
                        if(data.customMsg!=''){
                            $("#operationStatus").html('<div class="alert alert-danger no-border"><span class="text-semibold">Error!</span> '+data.customMsg+'<a href="#" class="alert-link"></a></div>');
                            $( "#operationStatus" ).scroll();
                        }
                    }else if(data.status == 'success')
                    {
                        swal('Done!','Aircraft Added succesfuly','success');
                        /*$(".add-aircraft").removeClass("label-4"); 
                        $(".add-aircraft").addClass("form-5 label-5");             
                        $(".form-four").removeClass("active");
                        $(".form-five").addClass("active");
                        $(".back-txt-block").addClass("back-form-4");
                        $(".back-txt-block").removeClass("back-form-3");*/
                    }
                }
            });
        }
    });
});


</script>   
@endsection