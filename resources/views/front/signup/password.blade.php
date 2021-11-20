@extends('front.layout.master')
@section('main_content')
    <div class="login-main-section">
        <div class="container">
            <div class="signup-block-wrapper">
            	@include('front.layout.operation_status')
                <div class="signup-block">
                    <div class="login-form-logo-img">
                        <img src="{{url('/')}}/front_assets/images/login-form-logo-img.png" alt="" />
                    </div>
                    @if($arr_data['set_password_link_expiry'] > $new_date)

                        <h2>Set Password</h2>     
                        <?php if(isset($arr_data['owner_id']))
                            {
                                $id = $arr_data['owner_id'];
                            }
                            else
                            {
                                $id = $arr_data['user_id'];
                            }    
                        ?>
                        @if($arr_data['is_set_password'] == 0)
                        <form class="form-horizontal" id="set_password" name="set_password" action="{{url('/')}}/email_save_password/{{base64_encode($id)}}" method="post" enctype="multipart/form-data">     {{csrf_field()}}                       
                            
                            <div class="form-group">
                                <label>Password</label>
                                <input name="password" id="password" type="password" placeholder=" Password" class="form-control" data-rule-required="true" minlength="6" />
                            </div>
                            <span class="error">{{ $errors->first('password') }} </span>
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input name="confirm_password" id="confirm_password" type="password" placeholder="Confirm Password" class="form-control" data-rule-required="true" minlength="6" />
                            </div>
                            <span class="error">{{ $errors->first('confirm_password') }} </span>
                            <span id="message"></span>
                            <button type="submit" onClick="validatePassword();" class="full-orng-btn sim-button">Submit</button>
                            
                         </form>
                         @else
                             <div class="form-horizontal">
                                <h1 style="padding: 50px 50px;">You have already set your password</h1>
                             </div>   
                         @endif
                    @else
                        <div class="form-horizontal">
                            <h1 style="padding: 50px 50px;">Your set password link has Expired</h1>
                        </div>  
                    @endif 
                </div>
            </div>
        </div>
    </div>   
    <!-- The Modal -->
   
<script type="text/javascript">

    /*$('#password, #confirm_password').on('keyup', function ()
    {
        if ($('#password').val() == $('#confirm_password').val()) 
        {
            $('#message').html('').css('color', 'green');
        }
        else 
        {
            $('#message').html('Please enter the same password as above').css('color', 'red');
        }
    });*/
    $(document).ready(function()
    {
        $('#set_password').validate({
            rules : {
                password : {
                    minlength : 5,
                    required: true,
                },
                confirm_password : {
                    minlength : 5,
                    required: true,
                    equalTo : "#password"
                }
            }
        });
    });
    </script>
@endsection