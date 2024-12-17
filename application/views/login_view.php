<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo $this->config->item('title'); ?></title>

	<!-- favicon -->
    <link rel="icon" type="image/vnd.microsoft.icon" href="<?php echo base_url(); ?>clients/<?php echo $this->config->item('client'); ?>/favicon.ico">

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url(); ?>assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url(); ?>assets/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url(); ?>assets/css/core.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url(); ?>assets/css/components.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url(); ?>assets/css/colors.css" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/loaders/pace.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/libraries/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/loaders/blockui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/notifications/pnotify.min.js"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/forms/styling/uniform.min.js"></script>

	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/app.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/pages/login.js"></script>
	<!-- /theme JS files -->

</head>

<body class="login-container login-cover">

	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main content -->
			<div class="content-wrapper">

				<!-- Content area -->
				<div class="content">

					<!-- Advanced login -->
					<?php
			            $username = array('name' => 'username', 'placeholder' => 'Username', 'class' => 'form-control');
			            $password = array('name' => 'password',    'placeholder' => 'Password', 'class' => 'form-control');
			            $submit = array('name' => 'submit', 'value' => 'Access', 'class' => 'btn bg-blue btn-block');
			            ?>
					<?php //echo form_open(base_url().'login/new_user')?>
					<form id="frmlogin" action="<?php echo base_url(); ?>login/new_user" method="post" accept-charset="utf-8">
						<div class="panel panel-body login-form">
							<div class="text-center">
								<img src="<?php echo base_url(); ?>clients/<?php echo $this->config->item('client'); ?>/logo.png" style="width: 250px;">
								<h5 class="content-group-lg"><?php echo $this->config->item('title'); ?> <small class="display-block"><?php echo $this->lang->line('welcome_login'); ?></small></h5>
							</div>

							<div class="form-group has-feedback has-feedback-left">
								<?php echo form_input($username)?><p><?php echo form_error('username')?>
								<div class="form-control-feedback">
									<i class="icon-user text-muted"></i>
								</div>
							</div>

							<div class="form-group has-feedback has-feedback-left">
								<?php echo form_password($password)?><p><?php echo form_error('password')?>
								<div class="form-control-feedback">
									<i class="icon-lock2 text-muted"></i>
								</div>
							</div>
							 <div class="form-group has-feedback has-feedback-left">
								<select class="form-control" name="area" id="area">
									<option value="0">Select a storage Bin</option>
									<?php foreach ($areas as $key => $value) { ?>
									<option value="<?php echo $value->wh; ?>"><?php echo $value->wh; ?></option>
									<?php } ?>
								</select>
								<div class="form-control-feedback">
									<i class="icon-stack text-muted"></i>
								</div>
							</div> 

							<div class="row">
								<div class="col-sm-8">
									<!--<div class="checkbox-custom checkbox-default">
										<input id="RememberMe" name="rememberme" type="checkbox"/>
										<label for="RememberMe">Remember Me</label>
									</div>-->
								</div>
								<div class="col-sm-4 text-right">
									<?php echo form_hidden('token',$token)?>
                					<?php echo form_submit($submit)?>
								</div>
							</div>
						</div>
					</form>
					<!-- /advanced login -->

				</div>
				<!-- /content area -->

			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->

</body>
<script type="text/javascript">
	$(document).ready(function() {
		$('#frmlogin').submit(function(event) {
			if($('#area').val()=='0' && $('#area').children('option').length>1){
				new PNotify({
		            title: 'Problem',
		            text: "Select a storage bin.",
		            addclass: 'bg-danger'
		        });
		        event.preventDefault();
			}
		});
	});
</script>
</html>
