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

					<div class="alert alert-info alert-styled-left alert-arrow-left alert-component">
						<!-- <h6 class="alert-heading" id="alert-title">Scan storage bin</h6> -->
						<span class="text-semibold" id="alert-body">Scan the tag or workorder.</span>
						<br/>
						<span class="text-semibold" id="last-scanned"></span>
					</div>

					<input type="text" id="scanner" class="form-control" placeholder="Capture code here" autofocus>
					
					<div id="reader" width="50%"></div>

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
	<audio id="wrong" src="<?= base_url(); ?>assets/wrong.mp3" autostart="false" ></audio>

</body>
<script src="<?= base_url(); ?>assets/html5-qrcode.min.js" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function($) {
		var correct = document.getElementById("correct");
		var wrong = document.getElementById("wrong");		

		var tag_or_wo = '';
		var last_tag = '';
		var partnumber = '';
		var qty = '';
		var openbox = '';
		var location = '';

		// function checkScanStatus(){
		// 	location = localStorage.getItem('location');
		// 	if(location){
		// 		$('#alert-title').html('Current storage location: '+wh + '<br/>Current location: '+location + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-xs btn-primary" onclick="localStorage.removeItem(\'location\');location.reload();">Change storage bin</button>');
		// 		$('#alert-body').html('');
		// 	}else{
		// 		$('#alert-title').html('Current storage location: '+wh + '<br/>Scan storage bin');
		// 		$('#alert-body').html('Scan the storage bin code to start the process.');
		// 	}
		// }
		// checkScanStatus();

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
			$('#last-scanned').html('Last scanned: '+decodedText);
			
			checkCode(decodedText);
		}

		$('#scanner').on('keypress', function(e) {
			if(e.which == 13) {
				var decodedText = $(this).val();
				$(this).val('');
				checkCode(decodedText);
			}
		});

		/*
		function checkLocation(decodedText){
			$('.alert-component').removeClass('alert-danger')
					.removeClass('alert-info').removeClass('alert-success');
			$.ajax({
				url: '<?= base_url(); ?>capture/checklocation',
				type: 'POST',
				dataType: 'json',
				data: {location: decodedText, wh: wh},
			})
			.done(function(data) {
				try{
					if(data.status){
						localStorage.setItem('location', decodedText);
						correct.play();
						$('.alert-component').addClass('alert-success');
						checkScanStatus();
					}else{
						wrong.play();
						$('.alert-component').addClass('alert-danger');
						$('#last-scanned').html(data.message);
						// new PNotify({
						// 	title: 'Error',
						// 	text: data.message,
						// 	type: 'error'
						// });
					}
				}catch(e){
					wrong.play();
					$('.alert-component').addClass('alert-danger');
					$('#last-scanned').html('There was an error');
				}
			})
			.fail(function() {
				new PNotify({
					title: 'Error',
					text: 'An error occurred',
					type: 'error'
				});
			});
		}
		*/

		function checkCode(decodedText){
			if(decodedText.trim() == ''){
				return;
			}
			$('.alert-component').removeClass('alert-danger')
					.removeClass('alert-info').removeClass('alert-success')
					.removeClass('alert-warning');

			const arrHu = decodedText.split('|');
			if(arrHu.length == 6){ // Code like: WEIDMANN|PRODL|MATNRE3CSW30.0376R0830|PAQTY31.751|AUFNR4500984497-30**|CHARGP00910000
				decodedText = arrHu[2]; // Just preserve partnumber
				// Remove 'MATNR' prefix if exists
				decodedText = decodedText.replace('MATNR', '');
				qty = arrHu[3];
				qty = qty.replace('PAQTY', '');
				if(isNaN(qty)){
					qty = '';
					$('.alert-component').addClass('alert-danger');
					$('#last-scanned').html('Invalid quantity in HU');
					return;
				}
			}else if(arrHu.length == 1){
				// Just preserve numbers
				if(last_tag == ''){ // Allow alphanumeric for partnumber
					decodedText = decodedText.replace(/[^0-9]/g, '');
				}
			}else{
				wrong.play();
				$('.alert-component').addClass('alert-danger');
				$('#last-scanned').html('Invalid HU');
				return;
			}

			// let current_tag = decodedText;
			// if(tag_or_wo == 'tag' && partnumber == ''){
			// 	console.log('Setting partnumber to', decodedText);
			// 	partnumber = decodedText;
			// 	current_tag = last_tag;
			// }else if(tag_or_wo == 'tag' && partnumber != '' && qty == ''){
			// 	console.log('Setting quantity to', decodedText);
			// 	qty = decodedText;
			// 	current_tag = last_tag;
			// }
			if(last_tag == '') {
				last_tag = decodedText;
			} else if(partnumber == '') {
				partnumber = decodedText;
			} else if(qty == '') {
				qty = decodedText;
			} else if(location == '') {
				location = decodedText;
			}

			console.log('Checking code', last_tag, partnumber, qty, location);

			var openbox = false; //confirm("Is openbox?") ? 1 : 0;

			$.ajax({
				url: '<?= base_url(); ?>capture/checkCode',
				type: 'POST',
				dataType: 'json',
				data: {tag: last_tag, location: location, openbox: openbox, partnumber: partnumber, quantity: qty},
			})
			.done(function(data) {
				try{
					if(data.status){
						correct.play();
						$('.alert-component').addClass('alert-success');
						let current_captured = '';
						if(data.isTag){
							current_captured = 'Tag: '+last_tag+'<br/>Part number: '+partnumber+'<br/>Quantity: '+qty+'<br/>Location: '+location;
						}else{
							//TODO: workorder captured message
						}
						$('#last-scanned').html(current_captured); //'Last scanned: '+decodedText

						if(data.isTag){
							tag_or_wo = 'tag';
							last_tag = data.tag;
							partnumber = data.partnumber;
							qty = data.quantity;
							openbox = data.openbox;
							location = data.location;
						}

						$('#alert-body').html(data.message);

						if(data.isComplete){
							// Reset all
							tag_or_wo = '';
							last_tag = '';
							partnumber = '';
							qty = '';
							openbox = '';
							location = '';
							new PNotify({
								title: 'Success',
								text: 'Capture complete. You can start a new capture.',
								type: 'success'
							});
							$('.alert-component').addClass('alert-info');
							$('#alert-body').html('Scan the tag or workorder.');
							$('#last-scanned').html('');
						}
					}else{
						wrong.play();
						$('#last-scanned').html(data.message);

						if(data.message == 'Part number not found'){
							partnumber = '';
						}

						if(data.message == 'Part number not found'){
							partnumber = '';
						}

						if(data.message == 'Quantity must be a positive number'){
							qty = '';
						}

						if(data.message == 'Location not found'){
							location = '';
						}

						if(data.message == 'HU already scanned'){
							$('.alert-component').addClass('alert-danger');
						}else{
							$('.alert-component').addClass('alert-warning');
						}
						
					}
				}catch(e){
					console.error(e);
					wrong.play();
					$('.alert-component').addClass('alert-danger');
					$('#last-scanned').html('There was an error');
				}
			})
			.fail(function() {
				$('.alert-component').addClass('alert-danger');
				new PNotify({
					title: 'Error',
					text: 'An error occurred',
					type: 'error'
				});
			});
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
