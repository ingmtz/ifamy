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
	<link href="<?php echo base_url(); ?>assets/validator/bootstrapValidator.min.css" rel="stylesheet" />
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/loaders/pace.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/libraries/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/loaders/blockui.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/validator/bootstrapValidator.min.js"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/forms/styling/uniform.min.js"></script>

	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/app.js"></script>
	<!-- /theme JS files -->

</head>

<body>

	<!-- Main navbar -->
	<?php echo $this->header; ?>
	<!-- /main navbar -->


	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main sidebar -->
			<?php echo $this->sidebar; ?>
			<!-- /main sidebar -->


			<!-- Main content -->
			<div class="content-wrapper">

				<!-- Page header -->
				<div class="page-header page-header-default">
					<div class="page-header-content">
						<div class="page-title">
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"></span><?php echo $this->lang->line('Edit User'); ?></h4>
						</div>

					</div>

				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<!-- Vertical form options -->
					<div class="row">
						<div class="col-md-12">

							<!-- Basic layout-->
							<form id="usersform" action="<?php echo base_url(); ?>users/saveedit/<?php echo $user->Id; ?>" method="POST">
								<div class="panel panel-flat">

									<div class="panel-body">
										<div class="form-group">
											<label><?php echo $this->lang->line('Name'); ?>:</label>
											<input type="text" value="<?php echo $user->name; ?>" class="form-control" id="name" name="name" placeholder="<?php echo $this->lang->line('Name'); ?>">
										</div>

										<div class="form-group">
											<label><?php echo $this->lang->line('Username'); ?>:</label>
											<input type="text" value="<?php echo $user->username?>" class="form-control" id="username" name="username" placeholder="<?php echo $this->lang->line('Username'); ?>">
										</div>

										<div class="form-group">
											<label><?php echo $this->lang->line('Password'); ?>:</label>
											<input type="password" value="<?php echo $user->password?>" class="form-control" id="password" name="password" placeholder="<?php echo $this->lang->line('Password'); ?>">
										</div>

										<div class="form-group">
											<label><?php echo $this->lang->line('Profile'); ?>:</label>
											<select name="idprofile" class="form-control">
												<optgroup label="Tipe of user">
													<?php foreach ($profiles as $key => $value) { ?>
													<option value="<?php echo $value->Id; ?>" <?php if($value->Id==$user->idprofile){ ?>selected=""<?php } ?>><?php echo $value->profile; ?></option>
													<?php } ?>
												</optgroup>
											</select>
										</div>

										<div class="text-right">
											<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('Save'); ?> <i class="icon-arrow-right14 position-right"></i></button>
										</div>
									</div>
								</div>
							</form>
							<!-- /basic layout -->

						</div>
					</div>
					<!-- /vertical form options -->
					<!-- Footer -->
					<div class="footer text-muted">
						&copy; 2024. <a href="http://novarumsystems.com" target="_blank">NovarumSystems.com</a>
					</div>
					<!-- /footer -->

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
	$(document).ready(function($) {
		$('#usersform').bootstrapValidator({
			message: 'El valor no es válido',
			excluded: ':disabled',
			feedbackIcons: {
				valid: 'glyphicon glyphicon-ok',
				invalid: 'glyphicon glyphicon-remove',
				validating: 'glyphicon glyphicon-refresh'
			},
			fields: {
				name: {
					message: 'The name is invalid.',
					validators: {
						notEmpty: {
							message: 'The name is requerid.'
						}
					}
				},
				password: {
					message: 'The password is invalid.',
					validators: {
						notEmpty: {
							message: 'The password is requerid.'
						}
					}
				},
				username: {
					message: 'The username is invalid.',
					validators: {
						notEmpty: {
							message: 'The username is requerid.'
						},
	                    remote: {
	                        url: "<?php echo base_url(); ?>users/validateusername",
	                        type: "post",
	                        data: {
	                          username: function() {
	                            return $('#username').val();
	                          },
	                          id: <?php echo $user->Id; ?>
	                        },
	                        message: 'This user is already registered.'
	                    }
					}
				},
			}
		});
	});
</script>
</html>
