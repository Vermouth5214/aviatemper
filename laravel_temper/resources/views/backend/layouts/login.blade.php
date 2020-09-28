<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, height=device-height,  initial-scale=1.0, user-scalable=no;user-scalable=0;"/>

		<title><?=getData('web_title');?> | @yield('title')</title>
		<link rel="apple-touch-icon" href="<?=url('img/avian.png');?>">
		<link rel="shortcut icon" href="<?=url('img/avian.png');?>">

		<!-- Ladda -->
		<link rel="stylesheet" href="<?=url('vendors/ladda/ladda.min.css');?>">
        <!-- Bootstrap -->
        <link href="<?=url('vendors/bootstrap/dist/css/bootstrap.min.css');?>" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="<?=url('vendors/font-awesome/css/font-awesome.min.css');?>" rel="stylesheet">
        <!-- NProgress -->
        <link href="<?=url('vendors/nprogress/nprogress.css');?>" rel="stylesheet">
        <!-- Animate.css -->
        <link href="<?=url('vendors/animate.css/animate.min.css');?>" rel="stylesheet">

        <!-- Custom Theme Style -->
        <link href="<?=url('build/css/custom.min.css');?>" rel="stylesheet">
    </head>

	<body class="login">
		@yield('content')
		<!-- jQuery -->
		<script src="<?=url('vendors/jquery/dist/jquery.min.js');?>"></script>
		<!-- Bootstrap -->
		<script src="<?=url('vendors/bootstrap/dist/js/bootstrap.min.js');?>"></script>
		<!-- Ladda -->
		<script src="<?=url('vendors/ladda/spin.min.js');?>"></script>
		<script src="<?=url('vendors/ladda/ladda.min.js');?>"></script>
		<script type="text/javascript">
			Ladda.bind('.btn-submit');
			$("#formLogin").submit(function() {
				var url = "<?php echo url('/'); ?>/backend/login";
				var frm_data = $("#formLogin").serialize();
				$.ajax({
					type: "POST",
					url: url,
					data: frm_data,
					success: function(response){ 
						Ladda.stopAll();
						if (response.status) {
							window.location.href = "<?php echo url('/'); ?>/backend/dashboard";
						}
						else {
							$('.error-alert').html("");
							$.each( response.message, function(key, value ) {
								$('.error-alert').append('<div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert">&times;</button>'+value+'</div>');
							});
						}
					}, 
					error: function(response){
						// console.log(response);
						alert('Login Error. Please try again.');
						location.reload();
					}
				});
				return false;
			});
		</script>		
	</body>
</html>
