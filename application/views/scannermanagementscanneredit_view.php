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
							<h4><i class="icon-arrow-left52 position-left"></i> <?php echo $this->lang->line('Edit scanner'); ?></h4>
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
							<form id="scannerform" action="<?php echo base_url(); ?>scannermanagement/saveeditscanner/<?php echo $scanner->Id; ?>" method="POST">
								<div class="panel panel-flat">

									<div class="panel-body">
										<div class="form-group">
											<label><?php echo $this->lang->line('Serial'); ?>:</label>
											<input type="text" value="<?php echo $scanner->serial; ?>" required="" class="form-control" id="serial" name="serial" placeholder="<?php echo $this->lang->line('Serial'); ?>">
										</div>

										<div class="form-group">
											<label><?php echo $this->lang->line('Brand'); ?>:</label>
											<input type="text" value="<?php echo $scanner->brand; ?>" required="" class="form-control" id="brand" name="brand" placeholder="<?php echo $this->lang->line('Brand'); ?>">
										</div>

										<div class="form-group">
											<label><?php echo $this->lang->line('Notes'); ?>:</label>
											<input type="text" value="<?php echo $scanner->notes; ?>" required="" class="form-control" id="notes" name="notes" placeholder="<?php echo $this->lang->line('Notes'); ?>">
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
		$('#scannerform').bootstrapValidator({
				message: 'El valor no es válido',
				excluded: ':disabled',
				feedbackIcons: {
					valid: 'glyphicon glyphicon-ok',
					invalid: 'glyphicon glyphicon-remove',
					validating: 'glyphicon glyphicon-refresh'
				},
				fields: {
					serial: {
						message: 'Serial is invalid.',
						validators: {
							notEmpty: {
								message: 'Serial is requerid.'
							},
		                    remote: {
		                        url: "<?php echo base_url(); ?>scannermanagement/validatescanner",
		                        type: "post",
		                        data: {
		                          serial: function() {
		                            return $('#serial').val();
		                          },
		                          id: <?php echo $scanner->Id; ?>
		                        },
		                        message: 'This serial is already registered.'
		                    }
						}
					},
					assigneduser: {
						message: 'User is invalid.',
						validators: {
							notEmpty: {
								message: 'User is required.'
							},
		                    remote: {
		                        url: "<?php echo base_url(); ?>scannermanagement/validateuser",
		                        type: "post",
		                        data: {
		                          assigneduser: function() {
		                            return $('#assigneduser').val();
		                          },
		                          id: <?php echo $scanner->Id; ?>
		                        },
		                        message: 'User is already asignated.'
		                    }
						}
					}
				}
		});
	});
</script>

</html>
