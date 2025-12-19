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
						<span class="text-semibold" id="alert-body">Scan the location</span>
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

	<!-- Custom Confirm Modal for Open Box -->
	<div class="modal fade" id="confirmOpenBoxModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="confirmOpenBoxLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content" style="border-radius: 8px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
				<div class="modal-header" style="background: linear-gradient(135deg, #16a085 0%, #1abc9c 100%); border: none; padding: 15px 20px;">
					<h4 class="modal-title" id="confirmOpenBoxLabel" style="color: #fff; font-weight: 600; margin: 0;">
						<i class="icon-question3" style="margin-right: 8px;"></i>Confirm
					</h4>
				</div>
				<div class="modal-body" style="padding: 30px 20px; text-align: center;">
					<i class="icon-box" style="font-size: 48px; color: #16a085; margin-bottom: 15px; display: block;"></i>
					<p style="font-size: 20px; font-weight: 500; color: #333; margin: 0;">Is the box open?</p>
				</div>
				<div class="modal-footer" style="border: none; padding: 15px 20px; justify-content: center; display: flex; gap: 15px;">
					<button type="button" class="btn btn-lg" id="confirmOpenBoxNo" style="background-color: #e74c3c; color: #fff; min-width: 100px; border-radius: 5px; font-weight: 600;">
						<i class="icon-cross2" style="margin-right: 5px;"></i>No
					</button>
					<button type="button" class="btn btn-lg" id="confirmOpenBoxYes" style="background-color: #27ae60; color: #fff; min-width: 100px; border-radius: 5px; font-weight: 600;">
						<i class="icon-checkmark3" style="margin-right: 5px;"></i>Yes
					</button>
				</div>
			</div>
		</div>
	</div>

</body>
<script src="<?= base_url(); ?>assets/html5-qrcode.min.js" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function($) {
		var correct = document.getElementById("correct");
		var wrong = document.getElementById("wrong");		

		var last_hu = '';
		var qty = '';
		var openbox = '';
		var openbox_flag = false;
		var location = '';

		// Custom confirm function using Bootstrap modal (avoids camera freeze on iPhone)
		function showOpenBoxConfirm(callback) {
			$('#confirmOpenBoxModal').modal('show');
			
			// Remove previous event handlers to avoid duplicates
			$('#confirmOpenBoxYes').off('click').on('click', function() {
				$('#confirmOpenBoxModal').modal('hide');
				callback(true);
			});
			
			$('#confirmOpenBoxNo').off('click').on('click', function() {
				$('#confirmOpenBoxModal').modal('hide');
				callback(false);
			});
		}

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

		function checkCode(decodedText){
			if(decodedText.trim() == ''){
				return;
			}
			$('.alert-component').removeClass('alert-danger')
					.removeClass('alert-info').removeClass('alert-success')
					.removeClass('alert-warning');

			// const arrHu = decodedText.split('/');
			// if(arrHu.length == 2){ // Code like: 400127347724040514-1011025174993112511201727103124105694302001/00243237298467371698M
			// 	decodedText = arrHu[1]; // Just preserve HU
			// 	// Ignore first 5 chars and take next 9 chars
			// 	if(decodedText.length >= 14){
			// 		decodedText = decodedText.substring(5, 14);
			// 	}
			// }else 
			if(decodedText.length >= 50){ //arrHu.length == 1 && 
				// Code like: 400127343224040514-1011025170993112511171727103124105694302001/00243237293594371698M
				// Remove non-printable characters
				decodedText = decodedText.replace(/[\x00-\x1F\x7F-\x9F]/g, '');
				console.log('Cleaned code:', decodedText);
				// Remove last 7 chars and then take last 9 chars
				decodedText = decodedText.slice(0, -7);
				if(decodedText.length >= 9){
					decodedText = decodedText.slice(-9);
				}
				console.log('Processed code:', decodedText);
			} 
			
			// else if(arrHu.length == 1){
			// 	// nothing to do
			// }else{
			// 	wrong.play();
			// 	$('.alert-component').addClass('alert-danger');
			// 	$('#last-scanned').html('Invalid HU');
			// 	return;
			// }

			if(location == '') {
				location = decodedText;
			} else if(last_hu == '') {
				last_hu = decodedText;
			} else if(openbox == '') {
				openbox = decodedText;
			} else if(qty == '' && !openbox_flag) {
				qty = decodedText;
			} 

			console.log('Checking code', last_hu, qty, location);

			$.ajax({
				url: '<?= base_url(); ?>capture/checkCodeMedica',
				type: 'POST',
				dataType: 'json',
				data: {location: location, hu: last_hu, openbox: openbox, quantity: qty},
			})
			.done(function(data) {
				try{
					if(data.status){
						correct.play();
						$('.alert-component').addClass('alert-success');
						let current_captured = '';
						current_captured = 'Location: '+location+'<br/>Hu: '+last_hu+'<br/>Open box: '+(openbox == '1' ? 'Yes' : 'No')+'<br/>Quantity: '+qty;
						$('#last-scanned').html(current_captured); //'Last scanned: '+decodedText

						last_hu = data.hu;
						qty = data.quantity;
						openbox = data.openbox;
						location = data.location;

						$('#alert-body').html(data.message);

						if(data.message == 'HU correct, capture open box option'){
							// Show custom modal instead of native confirm (fixes camera freeze on iPhone)
							showOpenBoxConfirm(function(isOpen) {
								openbox = isOpen ? '1' : '0';
								openbox_flag = true;
								checkCode(openbox);
							});
							return;
						}
						openbox_flag = false;

						if(data.isComplete){
							last_hu = '';
							qty = '';
							openbox = '';
							location = '';
							new PNotify({
								title: 'Success',
								text: 'Capture complete. You can start a new capture.',
								type: 'success'
							});
							$('.alert-component').addClass('alert-info');
							$('#alert-body').html('Scan the location');
							$('#last-scanned').html('');
						}
					}else{
						wrong.play();
						$('#last-scanned').html(data.message);

						if(data.message == 'HU not found'){
							last_hu = '';
						}

						if(data.message == 'HU already scanned'){
							last_hu = '';
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
