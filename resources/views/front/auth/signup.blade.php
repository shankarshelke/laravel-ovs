@extends('front.layout.master')
@section('main_content')
     <div class="login-main-section signup-main-section">
        <div class="container">
            <div class="signup-block-wrapper">

                @include('front.layouts.user_dashboard_menus')

                <div class="signup-block">
                    <div class="login-form-logo-img">
                        <img src="{{url('/')}}/front_assets/images/login-form-logo-img.png" alt="" />
                    </div>
                    <h2>Signup</h2>   
                    <h1 class="what-type-user-head">What Type of User You Are?</h1>                 
                    <form>     
                        <div class="type-of-user-button">
                            <a class="signup-user-btn" href="signup-user.html"><img src="images/signup-user-icon.png" alt="" /> User</a>
                            <a class="signup-user-btn signup-aircraft-operater-btn active" href="{{url('/')}}/signup_operator"><img src="images/signup-aircraft-operator-icon-img.png" alt="" /> Aircraft Operater</a>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input type="text" placeholder="Enter your First Name" />
                                     <div class="error">This field is required</div>
                                </div>    
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input type="text" placeholder="Enter your Last Name" />
                                    <!--<div class="error">this field is required</div>-->
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" placeholder="Enter your Email" />
                                     <!--<div class="error">This field is required</div>-->
                                </div>    
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="text" placeholder="Enter your Phone Number" />
                                    <!--<div class="error">this field is required</div>-->
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-12 upload-experience-certifcate">
                                <div class="form-group">
                                    <label>Aircraft Driving Experience Certifcate</label>
                                    <div class="upload-block">
                                        <input type="file" id="pdffile" style="visibility:hidden; height: 0;border: none" name="file">
                                        <div class="input-group ">
                                            <input type="text" class="file-caption  kv-fileinput-caption" placeholder="Aircraft Driving Experience Certifcate" id="subfile" />
                                            <div class="btn btn-primary btn-file"><a class="file" onclick="$('#pdffile').click();"><i class="fa fa-upload"></i> File</a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="capcha-section-main">
                            <img src="{{url('/')}}/front_assets/images/capcha-img.png" alt="" />
                        </div>                       
                        <div class="terms-block text-left signup-terms-section">
                            <div class="check-block">
                                <input id="filled-in-box" class="filled-in" checked="checked" type="checkbox">
                                <label for="filled-in-box">Please confirm that you are agree our <a href="javascript:void(0)">terms and conditions</a></label>
                            </div>                            
                            <div class="clearfix"></div>
                        </div>
<!--                        <button type="submit" class="full-orng-btn sim-button">Sign In</button>-->
                            <a href="teacher-my-classes.html" class="full-orng-btn sim-button">Signup</a>
                            <div class="join-block">                            
                                <h5>Have an Account ? <a href="{{url('/')}}/sign_in">Sign In</a></h5>
                            </div>
                     </form>
                </div>
            </div>
        </div>
    </div>      


  <script type="text/javascript">
        $(document).ready(function(){
            // This is the simple bit of jquery to duplicate the hidden field to subfile
            $('#pdffile').change(function(){
                $('#subfile').val($(this).val());
            });

            // This bit of jquery will show the actual file input box
            $('#showHidden').click(function(){
                $('#pdffile').css('visibilty','visible');
            });

            // This is the simple bit of jquery to duplicate the hidden field to subfile
            $('#pdffile1').change(function(){
                $('#subfile1').val($(this).val());
            });

            // This bit of jquery will show the actual file input box
            $('#showHidden1').click(function(){
                $('#pdffile1').css('visibilty','visible');
            });
        });
    </script>
@endsection