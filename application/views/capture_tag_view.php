<!DOCTYPE html>
<!--
============================================
CAPTURE BY TAG VIEW
============================================
Flow: Tag → HU/PartNumber/Batch → OpenBox → Quantity → Location
Updates: tags table (links physical tag to inventory item)

This view allows users to:
1. Scan a physical inventory tag (from pre-printed labels)
2. Scan the HU, Part Number, or Batch code
3. Confirm if the box is open
4. Enter/confirm quantity
5. Scan the location
============================================
-->
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo $this->config->item('title'); ?> - Capture by Tag</title>

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
						<span class="text-semibold" id="alert-body"><?php echo $this->lang->line('Scan the Tag'); ?></span>
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
				<div class="modal-header" style="background: linear-gradient(135deg, #16a085 0%, #1abc9c 100%); border: none; padding: 15px 20px;">
					<h4 class="modal-title" id="confirmOpenBoxLabel" style="color: #fff; font-weight: 600; margin: 0;">
						<i class="icon-question3" style="margin-right: 8px;"></i><?php echo $this->lang->line('Confirm'); ?>
					</h4>
				</div>
				<div class="modal-body" style="padding: 30px 20px; text-align: center;">
					<i class="icon-box" style="font-size: 48px; color: #16a085; margin-bottom: 15px; display: block;"></i>
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
 * CAPTURE BY TAG - JavaScript Logic
 * ============================================
 * 
 * Capture Flow:
 * 1. Scan Tag (validates against tags table)
 * 2. Scan HU/PartNumber/Batch (validates and retrieves item info)
 * 3. Open Box confirmation (modal popup)
 * 4. Quantity input (auto-filled if HU has quantity)
 * 5. Scan Location (validates against locations table)
 * 
 * On completion: Updates tags table with captured data
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
	var tag = '';         // Physical tag number (from tags table)
	var itemcode = '';    // Part number / Item code
	var hu = '';          // Handling Unit number (if scanned)
	var openbox = '';     // '1' = open, '0' = closed
	var openbox_flag = false;  // Flag to track if openbox modal was shown
	var qty = '';         // Quantity
	var location = '';    // Warehouse location

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
		// PARSE SPECIAL CODE FORMATS
		// Handle different barcode/QR formats from various systems
		// ============================================
		const arrHu = decodedText.split('|');
		
		if(arrHu.length == 6) {
			// Format: WEIDMANN|PRODL|MATNR{partnumber}|PAQTY{qty}|AUFNR{order}|CHARG{batch}
			// Example: WEIDMANN|PRODL|MATNRE3CSW30.0376R0830|PAQTY31.751|AUFNR4500984497-30**|CHARGP00910000
			decodedText = arrHu[2].replace('MATNR', '');  // Extract part number
			qty = arrHu[3].replace('PAQTY', '');          // Extract quantity
			if(isNaN(qty)) {
				qty = '';
				$('.alert-component').addClass('alert-danger');
				$('#last-scanned').html('Invalid quantity in HU');
				return;
			}
		} else if(arrHu.length == 4) {
			// Format: WEIDMANN|HULAB|EXIDV{hu_number}|VENUM{venum}
			// Example: WEIDMANN|HULAB|EXIDV237235599|VENUM0037466482
			decodedText = arrHu[2].replace(/\D/g, '');  // Extract HU number (digits only)
		} else if(arrHu.length == 1) {
			// Simple code - no transformation needed
		} else {
			wrong.play();
			$('.alert-component').addClass('alert-danger');
			$('#last-scanned').html('Invalid barcode format');
			return;
		}

		// ============================================
		// ASSIGN CODE TO APPROPRIATE FIELD
		// Based on what's already captured
		// ============================================
		if(tag == '') {
			tag = decodedText;
		} else if(itemcode == '') {
			itemcode = decodedText;
		} else if(openbox == '') {
			openbox = decodedText;
		} else if(qty == '' && !openbox_flag) {
			qty = decodedText;
		} else if(location == '' && !openbox_flag) {
			location = decodedText;
		}

		console.log('Checking:', {tag, itemcode, openbox, qty, location, hu});

		// ============================================
		// AJAX REQUEST TO SERVER
		// Validates and processes the capture step
		// ============================================
		$.ajax({
			url: '<?= base_url(); ?>capture/checkCodeTag',
			type: 'POST',
			dataType: 'json',
			data: {
				tag: tag, 
				itemcode: itemcode, 
				openbox: openbox, 
				quantity: qty, 
				location: location, 
				hu: hu
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
					tag = data.tag;
					itemcode = data.itemcode;
					openbox = data.openbox;
					qty = data.quantity;
					location = data.location;
					hu = data.hu;

					// Display current capture progress
					var current_captured = 
						'Tag: ' + tag + '<br/>' +
						'Part number: ' + itemcode + '<br/>' +
						'Open box: ' + (openbox == '1' ? 'Yes' : 'No') + '<br/>' +
						'Quantity: ' + qty + '<br/>' +
						'Location: ' + location;
					$('#last-scanned').html(current_captured);
					$('#alert-body').html(data.message);

					// Check if we need to show open box confirmation
					if(data.message.includes('capture open box option')) {
						showOpenBoxConfirm(function(isOpen) {
							openbox = isOpen ? '1' : '0';
							if(openbox == '1') qty = '';  // Clear qty for open boxes
							openbox_flag = true;
							checkCode(openbox);
						});
						return;
					}
					openbox_flag = false;

					// Check if capture is complete
					if(data.isComplete) {
						// Reset all state for next capture
						tag = '';
						itemcode = '';
						qty = '';
						openbox = '';
						location = '';
						hu = '';
						
						new PNotify({
							title: 'Success',
							text: 'Capture complete. You can start a new capture.',
							type: 'success'
						});
						$('.alert-component').addClass('alert-info');
						$('#alert-body').html('<?php echo $this->lang->line('Scan the Tag'); ?>');
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
					if(data.message == 'Tag not found' || data.message == 'Tag already scanned') {
						tag = '';
						$('.alert-component').addClass('alert-danger');
					} else if(data.message == 'Quantity must be a positive number') {
						qty = '';
						$('.alert-component').addClass('alert-warning');
					} else if(data.message == 'Location not found') {
						location = '';
						$('.alert-component').addClass('alert-warning');
					} else if(data.message == 'HU already scanned') {
						itemcode = '';
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
