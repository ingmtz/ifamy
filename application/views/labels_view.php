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
	<!-- Datatables -->
	<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>assets/datatables2/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>assets/datatables2/dataTables.buttons.min.js"></script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>assets/datatables2/jszip.min.js"></script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>assets/datatables2/pdfmake.min.js"></script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>assets/datatables2/vfs_fonts.js"></script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>assets/datatables2/buttons.html5.min.js"></script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>assets/datatables2/buttons.print.min.js"></script>

	<link href="<?php echo base_url(); ?>assets/datatables2/responsive.bootstrap.min.css" rel="stylesheet">
	<script src="<?php echo base_url(); ?>assets/datatables2/dataTables.responsive.min.js"></script>

	<script src="<?php echo base_url(); ?>assets/jquery.redirect.js"></script>

	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/app.js"></script>
	<!-- /theme JS files -->

	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/chosen/chosen.css" />
	<script src="<?php echo base_url(); ?>assets/chosen/chosen.jquery.js"></script>
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"></span>Labels<?php //echo $this->lang->line('Tags'); ?></h4>
						</div>

					</div>

				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<!-- Basic datatable -->
					<div class="panel panel-flat">
						<div class="panel-body">
							<h5 class="panel-title" style="margin-bottom:15px;">New label<?php //echo $this->lang->line('New tag assignation'); ?></a></h5>
							<div class="row" style="margin-bottom:15px;">
									<span><b>
										Capture the follow information to create label:
										<?php //echo $this->lang->line('Capture the follow information to create new tags or reassign them'); ?>:</b></span>
							</div>
							<div class="row" style="margin-bottom:15px;">
								<div class="col-md-2 form-inline">
									<b>Item code:<?php //echo $this->lang->line('Start tag number'); ?></b>
								</div>
							</div>
							<div class="row" style="margin-bottom:35px;">
							<form id="frmnewlabel">
								<div class="col-md-2 form-inline">
									<select class="form-control chosen-select" id="itemcode" name="itemcode">
										<?php foreach ($itemcodes as $key => $value) { ?>
										<option value="<?php echo $value->itemcode; ?>"><?php echo $value->itemcode; ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="col-md-1 form-inline">
									<button type="button" id="create" class="btn btn-primary"><?php echo $this->lang->line('Create'); ?> <i class="icon-arrow-right14 position-right"></i></button>
								</div>
							</form>
							</div>
						</div>
					</div>
					<!-- /basic datatable -->
					<!-- Footer -->
					<div class="footer text-muted">
						&copy; 2020. <a href="http://novarumsystems.com" target="_blank">NovarumSystems.com</a>
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
	$(document).ready(function() {
		$('.chosen-select').chosen();
		$('.chosen-single').height(36);
		$('.chosen-container').width('90%');
		$('.chosen-single span').css('margin-top', '5px');

		$('#create').click(function(event) {
			$.redirect('<?php echo base_url();?>tags/createlabels', {data: $('#frmnewlabel').serialize()},'POST', '_blank');

			new PNotify({
	            title: 'Success',
	            text: 'Labels created',
	            addclass: 'bg-success'
	        });
		});
	});
</script>
</html>
