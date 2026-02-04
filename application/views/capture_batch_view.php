<!DOCTYPE html>
<!--
============================================
CAPTURE BY BATCH VIEW
============================================
Flow: Location → Batch → OpenBox → Quantity
Updates: hus table only (finds HU by batch number)

This view allows users to:
1. Scan the warehouse location first
2. Scan the Batch number (looks up HU by batch field)
3. Confirm if the box is open
4. Enter/confirm quantity (if open box)

Note: Batch numbers are stored in the 'batch' field of the hus table.
This mode is useful when the HU label is not available but the batch
number from product packaging can be scanned.
============================================
-->
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo $this->config->item('title'); ?> - Capture by Batch</title>

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

				<!-- Content area -->
				<div class="content">

					<!-- Status alert - Shows current step and scanned data -->
					<div class="alert alert-info alert-styled-left alert-arrow-left alert-component">
						<span class="text-semibold" id="alert-body"><?php echo $this->lang->line('Scan the Location'); ?></span>
						<br/>
						<span class="text-semibold" id="last-scanned"></span>
					</div>

					<!-- Manual input field for scanner/keyboard -->
					<input type="text" id="scanner" class="form-control" placeholder="<?php echo $this->lang->line('Capture code here'); ?>" autofocus>
					
					<!-- QR/Barcode camera reader container -->
					<div id="reader" width="50%"></div>

					<!-- Footer -->
					<div class="footer text-muted">
						&copy; <?php echo date('Y'); ?>. <a href="http://novarumsystems.com" target="_blank">NovarumSystems.com</a>
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

	<!-- Audio feedback for scan results -->
	<audio id="correct" src="<?= base_url(); ?>assets/beep.mp3" autostart="false"></audio>
	<audio id="wrong" src="<?= base_url(); ?>assets/wrong.mp3" autostart="false"></audio>

	<!-- ============================================
	     OPEN BOX CONFIRMATION MODAL
	     Custom modal to avoid native confirm() which freezes camera on iPhone
	============================================ -->
	<div class="modal fade" id="confirmOpenBoxModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="confirmOpenBoxLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content" style="border-radius: 8px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
				<div class="modal-header" style="background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%); border: none; padding: 15px 20px;">
					<h4 class="modal-title" id="confirmOpenBoxLabel" style="color: #fff; font-weight: 600; margin: 0;">
						<i class="icon-question3" style="margin-right: 8px;"></i><?php echo $this->lang->line('Confirm'); ?>
					</h4>
				</div>
				<div class="modal-body" style="padding: 30px 20px; text-align: center;">
					<i class="icon-box" style="font-size: 48px; color: #9b59b6; margin-bottom: 15px; display: block;"></i>
					<p style="font-size: 20px; font-weight: 500; color: #333; margin: 0;"><?php echo $this->lang->line('Is the box open?'); ?></p>
				</div>
				<div class="modal-footer" style="border: none; padding: 15px 20px; justify-content: center; display: flex; gap: 15px;">
					<button type="button" class="btn btn-lg" id="confirmOpenBoxNo" style="background-color: #e74c3c; color: #fff; min-width: 100px; border-radius: 5px; font-weight: 600;">
						<i class="icon-cross2" style="margin-right: 5px;"></i><?php echo $this->lang->line('No'); ?>
					</button>
					<button type="button" class="btn btn-lg" id="confirmOpenBoxYes" style="background-color: #27ae60; color: #fff; min-width: 100px; border-radius: 5px; font-weight: 600;">
						<i class="icon-checkmark3" style="margin-right: 5px;"></i><?php echo $this->lang->line('Yes'); ?>
					</button>
				</div>
			</div>
		</div>
	</div>

</body>

<!-- QR Code Scanner Library -->
<script src="<?= base_url(); ?>assets/html5-qrcode.min.js" type="text/javascript"></script>

<script type="text/javascript">
/**
 * ============================================
 * CAPTURE BY BATCH - JavaScript Logic
 * ============================================
 * 
 * Capture Flow:
 * 1. Scan Location (validates against locations table)
 * 2. Scan Batch number (looks up HU by batch field in hus table)
 * 3. Open Box confirmation (modal popup)
 * 4. Quantity input (only if open box, otherwise use HU's original qty)
 * 
 * On completion: Updates hus table with captured data
 * 
 * Note: This mode finds the HU record by searching the 'batch' field
 * instead of the 'tag' field, useful when scanning product batch labels.
 */
$(document).ready(function($) {
	
	// ============================================
	// AUDIO ELEMENTS FOR FEEDBACK
	// ============================================
	var correct = document.getElementById("correct");  // Success beep
	var wrong = document.getElementById("wrong");      // Error sound

	// ============================================
	// CAPTURE STATE VARIABLES
	// These track the current capture session data
	// ============================================
	var location = '';    // Warehouse location (scanned first)
	var batch = '';       // Batch number (from product packaging)
	var hu = '';          // HU number (retrieved from batch lookup)
	var openbox = '';     // '1' = open, '0' = closed
	var openbox_flag = false;  // Flag to track if openbox modal was shown
	var qty = '';         // Quantity (only if open box)

	// ============================================
	// OPEN BOX CONFIRMATION MODAL
	// Uses Bootstrap modal instead of native confirm()
	// to avoid camera freeze issues on iPhone
	// ============================================
	function showOpenBoxConfirm(callback) {
		$('#confirmOpenBoxModal').modal('show');
		
		// Remove previous handlers to avoid duplicates
		$('#confirmOpenBoxYes').off('click').on('click', function() {
			$('#confirmOpenBoxModal').modal('hide');
			callback(true);
		});
		
		$('#confirmOpenBoxNo').off('click').on('click', function() {
			$('#confirmOpenBoxModal').modal('hide');
			callback(false);
		});
	}

	// ============================================
	// QR CODE SCANNER CALLBACKS
	// ============================================
	var lastScanned = '';  // Prevent duplicate scans
	
	function onScanSuccess(decodedText, decodedResult) {
		// Prevent processing the same code twice in a row
		if (decodedText === lastScanned) {
			return;
		}
		lastScanned = decodedText;
		
		console.log('Code matched:', decodedText, decodedResult);
		new PNotify({
			title: 'Success',
			text: 'Code matched: ' + decodedText,
			type: 'success'
		});
		
		checkCode(decodedText);
	}

	function onScanFailure(error) {
		// Silently ignore scan failures - keep scanning
	}

	// ============================================
	// MANUAL INPUT HANDLER
	// Processes codes entered via keyboard/scanner
	// ============================================
	$('#scanner').on('keypress', function(e) {
		if(e.which == 13) {  // Enter key
			var decodedText = $(this).val();
			$(this).val('');
			checkCode(decodedText);
		}
	});

	// ============================================
	// MAIN CODE PROCESSING FUNCTION
	// Parses scanned/entered codes and sends to server
	// ============================================
	function checkCode(decodedText) {
		if(decodedText.trim() == '') {
			return;
		}

		// Reset alert styling
		$('.alert-component').removeClass('alert-danger alert-info alert-success alert-warning');

		// ============================================
		// PARSE SPECIAL BATCH CODE FORMATS
		// Handle different barcode/QR formats from various systems
		// ============================================
		const arrCode = decodedText.split('|');
		
		if(arrCode.length == 6) {
			// Format: WEIDMANN|PRODL|MATNR{partnumber}|PAQTY{qty}|AUFNR{order}|CHARG{batch}
			// Example: WEIDMANN|PRODL|MATNRE3CSW30.0376R0830|PAQTY31.751|AUFNR4500984497-30**|CHARGP00910000
			// Extract batch from CHARG field
			decodedText = arrCode[5].replace('CHARG', '');
		}
		// For single-part codes, no transformation needed

		// ============================================
		// ASSIGN CODE TO APPROPRIATE FIELD
		// Flow: Location → Batch → OpenBox → Quantity
		// ============================================
		if(location == '') {
			location = decodedText;
		} else if(batch == '') {
			batch = decodedText;
		} else if(openbox == '') {
			openbox = decodedText;
		} else if(qty == '' && openbox_flag) {
			qty = decodedText;
		}

		console.log('Checking:', {location, batch, hu, openbox, qty});

		// ============================================
		// AJAX REQUEST TO SERVER
		// Validates and processes the capture step
		// ============================================
		$.ajax({
			url: '<?= base_url(); ?>capture/checkCodeBatch',
			type: 'POST',
			dataType: 'json',
			data: {
				location: location, 
				batch: batch, 
				hu: hu,
				openbox: openbox, 
				quantity: qty
			},
		})
		.done(function(data) {
			try {
				if(data.status) {
					// ============================================
					// SUCCESS RESPONSE HANDLING
					// ============================================
					correct.play();
					$('.alert-component').addClass('alert-success');

					// Update local state with server response
					location = data.location;
					batch = data.batch;
					hu = data.hu || '';  // HU retrieved from batch lookup
					openbox = data.openbox;
					qty = data.quantity;

					// Display current capture progress
					var current_captured = 
						'Location: ' + location + '<br/>' +
						'Batch: ' + batch + '<br/>' +
						(hu ? 'HU: ' + hu + '<br/>' : '') +
						'Open box: ' + (openbox == '1' ? 'Yes' : 'No') + '<br/>' +
						'Quantity: ' + qty;
					$('#last-scanned').html(current_captured);
					$('#alert-body').html(data.message);

					// Check if we need to show open box confirmation
					if(data.message.includes('capture open box option')) {
						showOpenBoxConfirm(function(isOpen) {
							openbox = isOpen ? '1' : '0';
							openbox_flag = true;
							checkCode(openbox);
						});
						return;
					}

					// Check if capture is complete
					if(data.isComplete) {
						// Reset all state for next capture
						location = '';
						batch = '';
						hu = '';
						qty = '';
						openbox = '';
						openbox_flag = false;
						
						new PNotify({
							title: 'Success',
							text: 'Batch captured successfully. You can start a new capture.',
							type: 'success'
						});
						$('.alert-component').addClass('alert-info');
						$('#alert-body').html('<?php echo $this->lang->line('Scan the Location'); ?>');
						$('#last-scanned').html('');
					}
				} else {
					// ============================================
					// ERROR RESPONSE HANDLING
					// Reset appropriate field based on error
					// ============================================
					wrong.play();
					$('#last-scanned').html(data.message);

					// Reset the field that caused the error
					if(data.message == 'Location not found') {
						location = '';
						$('.alert-component').addClass('alert-danger');
					} else if(data.message == 'Batch not found') {
						batch = '';
						$('.alert-component').addClass('alert-warning');
					} else if(data.message == 'Batch already scanned') {
						batch = '';
						$('.alert-component').addClass('alert-warning');
					} else if(data.message == 'Quantity must be a positive number') {
						qty = '';
						$('.alert-component').addClass('alert-warning');
					} else {
						$('.alert-component').addClass('alert-warning');
					}
				}
			} catch(e) {
				console.error('Error processing response:', e);
				wrong.play();
				$('.alert-component').addClass('alert-danger');
				$('#last-scanned').html('There was an error processing the response');
			}
		})
		.fail(function() {
			$('.alert-component').addClass('alert-danger');
			new PNotify({
				title: 'Error',
				text: 'An error occurred communicating with the server',
				type: 'error'
			});
		});
	}

	// ============================================
	// INITIALIZE QR CODE SCANNER
	// ============================================
	let html5QrcodeScanner = new Html5QrcodeScanner(
		"reader",
		{ fps: 10, qrbox: {width: 250, height: 250} },
		/* verbose= */ false
	);
	html5QrcodeScanner.render(onScanSuccess, onScanFailure);

	// Remove the info icon from the scanner UI
	$('img[alt="Info icon"]').remove();

});
</script>
</html>
