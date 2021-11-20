<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    <link rel="shortcut icon" href="#" type="image/png">

    <title>Login</title>

    <link href="{{url('/')}}/assets/admin_assets/css/style.css" rel="stylesheet">
    <link href="{{url('/')}}/assets/admin_assets/css/style-responsive.css" rel="stylesheet">
</head>
<body class="login-body">

<div class="container">

    <form class="form-signin cmxform" id="frm_login" name="frm_login" action="{{url($admin_panel_slug.'/validate_login')}}" method="post" enctype="multipart/form-data">
            {{csrf_field()}}
        <div class="form-signin-heading text-center">
            <h1 class="sign-title">Sign In</h1>
               @include('admin.layout._operation_status') 
            <img src="{{url('/')}}/assets/admin_assets/images/login-logo.jpg" height="113px;" alt=""/>
        </div>
        <div class="login-wrap form-group">
            <input type="text" id="email" name="email" class="form-control" data-rule-email="true" data-rule-required="true" placeholder="Email ID" autofocus value="{{$_COOKIE['remember_me_email'] or ''}}">
            <label class="error">{{ $errors->first('email') }} </label>

            <input type="password" class="form-control" id="password" name="password" data-rule-required="true" placeholder="Password">
            <label class="error">{{ $errors->first('password') }} </label>
            
            <button class="btn btn-lg btn-login btn-block" type="submit">
                Login
            </button>
            <label class="checkbox">
                <input type="checkbox" value="on" name="remember_me" id="remember_me" @if(!empty($_COOKIE['remember_me_email'])) ? checked : '' @endif> Remember me
                <span class="pull-right">
                    <a data-toggle="modal" href="#myModal"> Forgot Password?</a>
                </span>
            </label>
        </div>
    </form>

    <!-- Fogot Password Modal -->
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Forgot Password ?</h4>
                </div>
                <form method="post" id="frm_forgot" action="{{url('/')}}/admin/forgot_password/post_email">
                    {{csrf_field()}}
                    <div class="modal-body">
                        <p>Enter your e-mail address below to reset your password.</p>
                        <input type="text" name="email" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix">
                        <label class="error">{{ $errors->first('email') }} </label>
                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- modal -->
</div>



<!-- Placed js at the end of the document so the pages load faster -->

<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{url('/')}}/assets/admin_assets/js/jquery-1.10.2.min.js"></script>
<script src="{{url('/')}}/assets/admin_assets/js/bootstrap.min.js"></script>
<script src="{{url('/')}}/assets/admin_assets/js/modernizr.min.js"></script>
<script src="{{url('/')}}/assets/admin_assets/js/jquery.validate.min.js"></script>

</body>
<script type="text/javascript">
    $(document).ready(function(){
        $('#frm_login').validate();

        <?php if($errors->has('email')) : ?>
            $("#myModal").modal('show');
        <?php endif; ?>

    });
</script>


<!-- Mirrored from adminex.themebucket.net/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 18 Jun 2019 12:10:19 GMT -->
</html>