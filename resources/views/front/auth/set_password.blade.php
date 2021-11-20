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

             @if($obj_data['set_password_link_expiry'] > $new_date)
                    <h2>Reset Password</h2>     
                  <?php if($obj_data['owner_id'])
                        {
                            $id = $obj_data['owner_id'];
                        }
                        else
                        {
                            $id = $obj_data['user_id'];
                        }    
                  ?>
                @if($obj_data['is_set_password'] == 0)
                    <form class="form-horizontal" id="set_password" name="set_password" action="{{url('/')}}/save_password/{{base64_encode($id)}}" method="post">     
                        {{csrf_field()}}                       
                        <div class="form-group">
                            <label>New Password</label>

                            <input type="password" name="new_password" id="new_password" placeholder=" New Password" class="form-control" data-rule-maxlength="50" data-rule-required="true" tabindex="1" minlength="6" />                     
                            <!--<div class="error">this field is required</div>-->
                        </div>
                        <span class="error">{{ $errors->first('new_password') }} </span>
                        <div class="form-group">
                            <label>Confirm New Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" class="form-control" data-rule-equalto = "#new_password" minlength="6" data-rule-required="true" tabindex="1" />                            
                        </div>
                        <span class="error">{{ $errors->first('confirm_password') }} </span>
                        
                        <button type="submit" class="full-orng-btn sim-button">Reset</button>
                    </form>
                @else
                    <div class="form-horizontal">
                        <h1 style="padding: 50px 50px;">You have already set your password </h1>
                    </div>  
                @endif
            @else
                <div class="form-horizontal">
                    <h1 style="padding: 50px 50px;">Your reset password link has Expired</h1>
                </div>  
            @endif     
                </div>
            </div>
        </div>
    </div>   
    <!-- The Modal -->
   
<script type="text/javascript">
        $('#set_password').validate();
    </script>
@endsection