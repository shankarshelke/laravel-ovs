<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	<meta name="keywords" content="admin, dashboard, bootstrap, template, flat, modern, theme, responsive, fluid, retina, backend, html5, css, css3">
	<meta name="description" content="">
	<meta name="author" content="ThemeBucket">
	<link rel="shortcut icon" href="#" type="image/png">

	<link href="{{url('/')}}/assets/admin_assets/css/bootstrap.min.css" rel="stylesheet">
	<title>Voter Management</title>

	<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
	<script src="{{url('/')}}/assets/admin_assets/js/jquery.validate.min.js"></script>
	<script src="{{url('/')}}/assets/admin_assets/js/jquery.form.min.js"></script>
{{-- 	<script src="{{url('/')}}/assets/admin_assets/js/bootstrap-datetimepicker.js"></script> --}}
	<script src="{{url('/')}}/assets/admin_assets/js/bootstrap-datepicker.js"></script>
  
	<link href="{{url('/')}}/assets/admin_assets/js/iCheck/skins/minimal/minimal.css" rel="stylesheet">
	<link href="{{url('/')}}/assets/admin_assets/js/iCheck/skins/square/square.css" rel="stylesheet">
	<link href="{{url('/')}}/assets/admin_assets/js/iCheck/skins/square/red.css" rel="stylesheet">
	<link href="{{url('/')}}/assets/admin_assets/js/iCheck/skins/square/blue.css" rel="stylesheet">
	<link href="{{url('/')}}/assets/admin_assets/js/iCheck/skins/square/green.css" rel="stylesheet">

	<link href="{{url('/')}}/assets/admin_assets/css/clndr.css" rel="stylesheet">
	<link href="{{url('/')}}/assets/admin_assets/css/bootstrap-fileupload.min.css" rel="stylesheet">
	<script src="{{url('/')}}/assets/admin_assets/js/additional-methods.js"></script>
	<link href="{{url('/')}}/assets/admin_assets/css/style.css" rel="stylesheet">
	<link href="{{url('/')}}/assets/admin_assets/css/style-responsive.css" rel="stylesheet">
	<link href="{{url('/')}}/assets/admin_assets/css/sweetalert.css" rel="stylesheet">
	<link href="{{url('/')}}/assets/admin_assets/css/DT_bootstrap.css" rel="stylesheet">
	<link href="{{url('/')}}/assets/admin_assets/css/demo_table.css" rel="stylesheet">

	
	<link href="{{url('/')}}/assets/admin_assets/css/datepicker-custom.css" rel="stylesheet">

	<script src="{{url('/')}}/assets/admin_assets/js/select2.min.js" type='text/javascript'></script>


	<!-- CSS -->
	<link href="{{url('/')}}/assets/admin_assets/css/select2.min.css" rel='stylesheet' type='text/css'>

	<style>
		.cursor-pointer{
			cursor: pointer;
		}
	</style>
	

</head>

<body class="sticky-header">

	<section>

		<!-- left side start-->
		@include('admin.layout._sidebar')
		<!-- left side end-->

		<!-- main content start-->
		<div class="main-content">

			@include('admin.layout._header')

			@yield('main_content')

			<!-- Footer -->
			@include('admin.layout._footer')
			<!--footer section end-->
			
		</div> <!-- END Main Content -->

	</section> <!-- END Section -->

</body> <!-- END Body -->
</html>