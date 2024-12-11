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
	<link href="<?php echo base_url(); ?>assets/css/icons/fontawesome/styles.min.css" rel="stylesheet" type="text/css">
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
	
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/app.js"></script>
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
				<!-- <div class="page-header page-header-default">
					<div class="page-header-content">
						<div class="page-title">
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"><?php echo $title; ?></span></h4>
						</div>
					</div>
				</div> -->
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">
					
					TODO: Capture mobile view
					<div id="reader" width="50%"></div>

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
	<div class="modal fade" id="modalmessage" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-body alert-danger">
	        <div class="alert alert-danger" role="alert">
	          <h4 class="alert-heading">ALERT</h4>
	          <p>Validation is not over. <?php if($this->session->userdata('ifatrimidprofile')=='3'){ ?><a href="<?php echo base_url(); ?>reports/validations">Go to validation</a><?php } ?></p>
	          <br/>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>

	<div class="modal fade" id="modalaudits" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
	  <div class="modal-dialog modal-xl" role="document">
	    <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="modalauditslabel">Audits for:</h4>
			</div><!-- /modal-header -->
			<div class="modal-body">
				<table class="table datatable" id="audits">
					<thead>
						<tr>
							<th><?php echo $this->lang->line('Audit counter'); ?></th>
							<th><?php echo $this->lang->line('Quantity'); ?></th>
							<th><?php echo $this->lang->line('Location'); ?></th>
							<th><?php echo $this->lang->line('Reason for modification'); ?></th>
							<th><?php echo $this->lang->line('Audit result'); ?></th>
							<th><?php echo $this->lang->line('Auditor'); ?></th>
							<th><?php echo $this->lang->line('Audit datetime'); ?></th>
							<th><?php echo $this->lang->line('Open box'); ?></th>
							<th><?php echo $this->lang->line('UOM'); ?></th>

							
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
	    </div>
	  </div>
	</div>

	<audio id="correct" src="<?= base_url(); ?>assets/beep.mp3" autostart="false" ></audio>

</body>
<script src="<?= base_url(); ?>assets/html5-qrcode.min.js" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function($) {
		var correct = document.getElementById("correct");

		var lastScanned = '';
		function onScanSuccess(decodedText, decodedResult) {
			if (decodedText === lastScanned) {
				return;
			}
			lastScanned = decodedText;
			console.log(`Code matched = ${decodedText}`, decodedResult);
			new PNotify({
				title: 'Success',
				text: `Code matched = ${decodedText}`,
				type: 'success'
			});
			correct.play();
		}

		function onScanFailure(error) {
			// handle scan failure, usually better to ignore and keep scanning.
			// for example:
			//console.warn(`Code scan error = ${error}`);
		}

		let html5QrcodeScanner = new Html5QrcodeScanner(
		"reader",
		{ fps: 10, qrbox: {width: 250, height: 250} },
		/* verbose= */ false);
		html5QrcodeScanner.render(onScanSuccess, onScanFailure);

		$('img[alt="Info icon"]').remove();

	});
</script>
</html>
