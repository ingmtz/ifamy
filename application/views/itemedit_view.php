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
							<h4><i class="icon-arrow-left52 position-left"></i> <?php echo $this->lang->line('Edit Item'); ?></h4>
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
							<form id="itemsform" action="<?php echo base_url(); ?>itemcodes/saveedit/<?php echo $item->Id; ?>" method="POST">
								<div class="panel panel-flat">

									<div class="panel-body">
										<div class="form-group">
											<label><?php echo $this->lang->line('Item code'); ?>:</label>
											<input type="text" value="<?php echo $item->itemcode; ?>" class="form-control" id="itemcode" name="itemcode" placeholder="<?php echo $this->lang->line('Item code'); ?>">
										</div>

										<div class="form-group">
											<label><?php echo $this->lang->line('Description'); ?>:</label>
											<input type="text" value="<?php echo $item->description?>" class="form-control" id="description" name="description" placeholder="<?php echo $this->lang->line('Description'); ?>">
										</div>

										<div class="form-group">
											<label><?php echo $this->lang->line('UOM'); ?>:</label>
											<input type="text" value="<?php echo $item->uom?>" class="form-control" id="uom" name="uom" placeholder="<?php echo $this->lang->line('UOM'); ?>">
										</div>

										<div class="form-group">
											<label><?php echo $this->lang->line('Unit price'); ?>:</label>
											<input type="text" value="<?php echo $item->cost_price?>" class="form-control" id="cost_price" name="cost_price" placeholder="<?php echo $this->lang->line('Unit price'); ?>">
										</div>

										<div class="form-group">
											<label><?php echo $this->lang->line('Group'); ?>:</label>
											<input type="text" value="<?php echo $item->item_group?>" class="form-control" id="item_group" name="item_group" placeholder="<?php echo $this->lang->line('Group'); ?>">
										</div>
										<div class="form-group">
											<label><?php echo $this->lang->line('Type'); ?>:</label>
											<input type="text" value="<?php echo $item->type?>"  class="form-control" id="type" name="type" placeholder="<?php echo $this->lang->line('Type'); ?>">
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
		$('#itemsform').bootstrapValidator({
				message: 'El valor no es válido',
				excluded: ':disabled',
				feedbackIcons: {
					valid: 'glyphicon glyphicon-ok',
					invalid: 'glyphicon glyphicon-remove',
					validating: 'glyphicon glyphicon-refresh'
				},
				fields: {
					description: {
						message: 'The description is invalid.',
						validators: {
							notEmpty: {
								message: 'The description is requerid.'
							}
						}
					},
					cost_price: {
						message: 'The cost price is invalid.',
						validators: {
							notEmpty: {
								message: 'The cost price is requerid.'
							},
							callback: {
		                        message: 'A valid amount is required.',
		                        callback: function(value, validator, $field) {
		                        	if(value.length>0){
		                        		if(isNaN(value)){
		                        			return false;
		                        		}else{
		                        			return true;
		                        		}
		                        	}else{
		                        		return false;
		                        	}
		                        }
		                    }
						}
					},
					item_group: {
						message: 'The item group is invalid.',
						validators: {
							notEmpty: {
								message: 'The item group is requerid.'
							}
						}
					},
					itemcode: {
						message: 'The item code is invalid.',
						validators: {
							notEmpty: {
								message: 'The item code is requerid.'
							},
		                    remote: {
		                        url: "<?php echo base_url(); ?>itemcodes/validateitemcode",
		                        type: "post",
		                        data: {
		                          itemcode: function() {
		                            return $('#itemcode').val();
		                          },
		                          id: <?php echo $item->Id; ?>
		                        },
		                        message: 'This item is already registered.'
		                    }
						}
					},
				}
		});
	});
</script>

</html>
