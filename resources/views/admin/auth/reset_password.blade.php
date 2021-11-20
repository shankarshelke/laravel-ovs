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
	<!-- Password recovery -->
	<form action="{{url('/')}}/admin/forgot_password/postReset" method="post" id="frm_forgot" class="form-signin cmxform">
		<div class="form-signin-heading text-center">
            <h1 class="sign-title">Reset Password</h1>
            @include('admin.layout._operation_status') 
            <img src="{{url('/')}}/assets/admin_assets/images/login-logo.png" alt=""/>
        </div>

		{{csrf_field()}}
		<div class="panel panel-body login-form">
			@include('admin.layout._operation_status') 
			<div class="text-center">
				<div class="icon-object border-success text-success"><i class="icon-lock2"></i></div>
				<h5 class="content-group">Password Reset <small class="display-block">Add Your New Password</small></h5>
			</div>

			<div class="form-group has-feedback">
				<input type="password" name="password" id="password" class="form-control" placeholder="Your New Password">
				<div class="form-control-feedback">
					<i class="icon-lock2 text-muted"></i>
				</div>
			</div>

			<div class="form-group has-feedback">
				<input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm Password" data-rule-required="true">
				<div class="form-control-feedback">
					<i class="icon-lock2 text-muted"></i>
				</div>
			</div>

			<input type="hidden" name="token" value="{{ $token or ''}}" />
			<input type="hidden" name="email" value="{{ $password_reset['email'] or ''}}" />

			<button type="submit" class="btn btn-lg btn-login btn-block"><i class="fa fa-check"></i> <i class="icon-arrow-right14 position-right"></i></button>

			<div class="registration">
                <i class="fa fa-arrow-left"></i>&nbsp; Back To <a href="{{url('/')}}/admin">Login</a>
            </div>
		</div>

	</form>
	<!-- /password recovery -->
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

</html>