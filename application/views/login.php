<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<!-- Meta, title, CSS, favicons, etc. -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>Login</title>

		<!-- Bootstrap -->
		<link href="<?php echo base_url();?>assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
		<!-- Font Awesome -->
		<link href="<?php echo base_url();?>assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		<!-- NProgress -->
		<link href="<?php echo base_url();?>assets/vendors/nprogress/nprogress.css" rel="stylesheet">
		<!-- Animate.css -->
		<link href="<?php echo base_url();?>assets/vendors/animate.css/animate.min.css" rel="stylesheet">

		<!-- Custom Theme Style -->
		<link href="<?php echo base_url();?>assets/build/css/custom.min.css" rel="stylesheet">
	</head>
	<body class="login">
		<div>
			<div class="login_wrapper">
				<div class="animate form login_form">
					<section class="login_content">
						<form  action="<?php echo $form_action; ?>" method="post">
							<h1>MOS SEAS</h1>
							<div>
								<input type="text" class="form-control" placeholder="Username" name="username" required="" />
							</div>
							<div>
								<input type="password" class="form-control" placeholder="Password" name="Password" required="" />
							</div>
							<div>
								<button type="submit" class="btn btn-default submit" >Login</button>
							</div>
							<div class="clearfix"></div>
							<div class="separator">
								<div class="clearfix"></div>
								<br />
								<div>
									<p>©2019 IT PT.SIANTAR TOP, T.BK</p>
								</div>
							</div>
						</form>
					</section>
				</div>
			</div>
		</div>
	</body>
</html>
