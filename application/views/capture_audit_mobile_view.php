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

				<!-- Content area -->
				<div class="content">

					<div class="alert alert-info alert-styled-left alert-arrow-left alert-component">
						<span class="text-semibold" id="alert-body">Scan the Tag to Audit</span>
						<br/>
						<span class="text-semibold" id="last-scanned"></span>
					</div>

					<!-- Tag Information Card (hidden initially) -->
					<div id="tag-info-card" class="panel panel-flat" style="display: none; margin-bottom: 20px;">
						<div class="panel-heading">
							<h5 class="panel-title">Tag Information</h5>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-6">
									<p><strong>Tag:</strong> <span id="info-tag"></span></p>
									<p><strong>Part Number:</strong> <span id="info-itemcode"></span></p>
									<p><strong>Quantity:</strong> <span id="info-qty"></span></p>
								</div>
								<div class="col-md-6">
									<p><strong>Location:</strong> <span id="info-location"></span></p>
									<p><strong>Warehouse:</strong> <span id="info-warehouse"></span></p>
									<p><strong>Open Box:</strong> <span id="info-openbox"></span></p>
								</div>
							</div>
						</div>
					</div>

					<input type="text" id="scanner" class="form-control" placeholder="Capture code here" autofocus>
					
					<div id="reader" width="50%"></div>

				</div>
				<!-- /content area -->

			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->

	<!-- Custom Confirm Modal for Quantity Verification -->
	<div class="modal fade" id="confirmQuantityModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="confirmQuantityLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content" style="border-radius: 8px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
				<div class="modal-header" style="background: linear-gradient(135deg, #2980b9 0%, #3498db 100%); border: none; padding: 15px 20px;">
					<h4 class="modal-title" id="confirmQuantityLabel" style="color: #fff; font-weight: 600; margin: 0;">
						<i class="icon-question3" style="margin-right: 8px;"></i>Verify Quantity
					</h4>
				</div>
				<div class="modal-body" style="padding: 20px;">
					<!-- Tag Information in Modal -->
					<div class="panel panel-flat" style="margin-bottom: 15px;">
						<div class="panel-body" style="padding: 10px;">
							<div class="row">
								<div class="col-xs-6">
									<p style="margin: 5px 0; font-size: 12px;"><strong>Tag:</strong> <span id="modal-info-tag"></span></p>
									<p style="margin: 5px 0; font-size: 12px;"><strong>Part Number:</strong> <span id="modal-info-itemcode"></span></p>
									<p style="margin: 5px 0; font-size: 12px;"><strong>Location:</strong> <span id="modal-info-location"></span></p>
								</div>
								<div class="col-xs-6">
									<p style="margin: 5px 0; font-size: 12px;"><strong>Warehouse:</strong> <span id="modal-info-warehouse"></span></p>
									<p style="margin: 5px 0; font-size: 12px;"><strong>Open Box:</strong> <span id="modal-info-openbox"></span></p>
								</div>
							</div>
						</div>
					</div>
					<!-- Quantity Question -->
					<div style="text-align: center;">
						<i class="icon-calculator" style="font-size: 48px; color: #2980b9; margin-bottom: 15px; display: block;"></i>
						<p style="font-size: 20px; font-weight: 500; color: #333; margin: 0;">Is the quantity correct?</p>
						<p style="font-size: 24px; font-weight: 700; color: #2980b9; margin-top: 10px;" id="modal-current-qty"></p>
					</div>
				</div>
				<div class="modal-footer" style="border: none; padding: 15px 20px; justify-content: center; display: flex; gap: 15px;">
					<button type="button" class="btn btn-lg" id="confirmQuantityNo" style="background-color: #e74c3c; color: #fff; min-width: 100px; border-radius: 5px; font-weight: 600;">
						<i class="icon-cross2" style="margin-right: 5px;"></i>No
					</button>
					<button type="button" class="btn btn-lg" id="confirmQuantityYes" style="background-color: #27ae60; color: #fff; min-width: 100px; border-radius: 5px; font-weight: 600;">
						<i class="icon-checkmark3" style="margin-right: 5px;"></i>Yes
					</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Input New Quantity Modal -->
	<div class="modal fade" id="inputQuantityModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="inputQuantityLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content" style="border-radius: 8px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
				<div class="modal-header" style="background: linear-gradient(135deg, #e67e22 0%, #f39c12 100%); border: none; padding: 15px 20px;">
					<h4 class="modal-title" id="inputQuantityLabel" style="color: #fff; font-weight: 600; margin: 0;">
						<i class="icon-pencil" style="margin-right: 8px;"></i>Enter Correct Quantity
					</h4>
				</div>
				<div class="modal-body" style="padding: 30px 20px;">
					<div class="form-group">
						<label for="new-quantity" style="font-weight: 600; color: #333;">New Quantity:</label>
						<input type="number" class="form-control" id="new-quantity" placeholder="Enter the correct quantity" step="0.01" min="0" style="font-size: 18px; padding: 10px;">
					</div>
				</div>
				<div class="modal-footer" style="border: none; padding: 15px 20px; justify-content: center; display: flex; gap: 15px;">
					<button type="button" class="btn btn-lg" id="cancelNewQuantity" style="background-color: #95a5a6; color: #fff; min-width: 100px; border-radius: 5px; font-weight: 600;">
						<i class="icon-cross2" style="margin-right: 5px;"></i>Cancel
					</button>
					<button type="button" class="btn btn-lg" id="saveNewQuantity" style="background-color: #27ae60; color: #fff; min-width: 100px; border-radius: 5px; font-weight: 600;">
						<i class="icon-checkmark3" style="margin-right: 5px;"></i>Save
					</button>
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

		var currentTag = null;
		var currentTagData = null;

		// Custom confirm function for quantity verification
		function showQuantityConfirm(qty, callback) {
			$('#modal-current-qty').html(qty);
			$('#confirmQuantityModal').modal('show');
			
			// Remove previous event handlers to avoid duplicates
			$('#confirmQuantityYes').off('click').on('click', function() {
				$('#confirmQuantityModal').modal('hide');
				callback(true);
			});
			
			$('#confirmQuantityNo').off('click').on('click', function() {
				$('#confirmQuantityModal').modal('hide');
				callback(false);
			});
		}

		// Show input quantity modal
		function showInputQuantityModal(callback) {
			$('#new-quantity').val('');
			$('#inputQuantityModal').modal('show');
			$('#new-quantity').focus();
			
			// Remove previous event handlers
			$('#saveNewQuantity').off('click').on('click', function() {
				var newQty = $('#new-quantity').val();
				if(newQty == '' || isNaN(newQty) || parseFloat(newQty) <= 0) {
					new PNotify({
						title: 'Error',
						text: 'Please enter a valid positive quantity',
						type: 'error'
					});
					return;
				}
				$('#inputQuantityModal').modal('hide');
				callback(parseFloat(newQty));
			});
			
			$('#cancelNewQuantity').off('click').on('click', function() {
				$('#inputQuantityModal').modal('hide');
				resetAudit();
			});

			// Allow Enter key to save
			$('#new-quantity').off('keypress').on('keypress', function(e) {
				if(e.which == 13) {
					$('#saveNewQuantity').click();
				}
			});
		}

		var lastScanned = '';
		function onScanSuccess(decodedText, decodedResult) {
			if (decodedText === lastScanned) {
				return;
			}
			lastScanned = decodedText;
			console.log(`Code matched = ${decodedText}`, decodedResult);
			$('#last-scanned').html('Last scanned: '+decodedText);
			
			checkTag(decodedText);
		}

		$('#scanner').on('keypress', function(e) {
			if(e.which == 13) {
				var decodedText = $(this).val();
				$(this).val('');
				checkTag(decodedText);
			}
		});

		function checkTag(tagCode){
			if(tagCode.trim() == ''){
				return;
			}

			// Remove only numbers from the tag
			tagCode = tagCode.replace(/\D/g, '');

			$('.alert-component').removeClass('alert-danger')
					.removeClass('alert-info').removeClass('alert-success')
					.removeClass('alert-warning');

			$.ajax({
				url: '<?= base_url(); ?>capture/checkTagForAudit',
				type: 'POST',
				dataType: 'json',
				data: {tag: tagCode},
			})
			.done(function(data) {
				try{
					if(data.status){
						correct.play();
						$('.alert-component').addClass('alert-success');
						$('#alert-body').html('Tag found, verifying quantity...');

						currentTag = tagCode;
						currentTagData = data.data;

						// Display tag information
						$('#info-tag').html(currentTagData.tag);
						$('#info-itemcode').html(currentTagData.itemcode);
						$('#info-qty').html(currentTagData.qty);
						$('#info-location').html(currentTagData.location);
						$('#info-warehouse').html(currentTagData.warehouse);
						$('#info-openbox').html(currentTagData.openbox == '1' ? 'Yes' : 'No');
						$('#tag-info-card').show();
					// Display tag information in modal
					$('#modal-info-tag').html(currentTagData.tag);
					$('#modal-info-itemcode').html(currentTagData.itemcode);
					$('#modal-info-location').html(currentTagData.location);
					$('#modal-info-warehouse').html(currentTagData.warehouse);
					$('#modal-info-openbox').html(currentTagData.openbox == '1' ? 'Yes' : 'No');
						// Ask if quantity is correct
						setTimeout(function() {
							showQuantityConfirm(currentTagData.qty, function(isCorrect) {
								if(isCorrect) {
									// Save audit with same quantity
									saveAudit(currentTagData.qty);
								} else {
									// Ask for new quantity
									showInputQuantityModal(function(newQty) {
										saveAudit(newQty);
									});
								}
							});
						}, 500);
					} else {
						wrong.play();
						$('.alert-component').addClass('alert-warning');
						$('#alert-body').html(data.message);
						$('#last-scanned').html('');
						$('#tag-info-card').hide();
					}
				}catch(e){
					console.error(e);
					wrong.play();
					$('.alert-component').addClass('alert-danger');
					$('#alert-body').html('There was an error');
					$('#tag-info-card').hide();
				}
			})
			.fail(function() {
				wrong.play();
				$('.alert-component').addClass('alert-danger');
				$('#alert-body').html('An error occurred');
				$('#tag-info-card').hide();
				new PNotify({
					title: 'Error',
					text: 'An error occurred',
					type: 'error'
				});
			});
		}

		function saveAudit(qty) {
			$.ajax({
				url: '<?= base_url(); ?>capture/saveAuditMobile',
				type: 'POST',
				dataType: 'json',
				data: {
					tag: currentTag,
					qty: qty,
					originalQty: currentTagData.qty
				},
			})
			.done(function(data) {
				if(data.status){
					correct.play();
					$('.alert-component').addClass('alert-success');
					
					var resultText = qty == currentTagData.qty ? 'Quantity verified as correct' : 'Quantity updated to ' + qty;
					$('#alert-body').html('Audit saved successfully! ' + resultText);
					
					new PNotify({
						title: 'Success',
						text: 'Audit completed successfully',
						type: 'success'
					});

					// Reset after 2 seconds
					setTimeout(function() {
						resetAudit();
					}, 2000);
				} else {
					wrong.play();
					$('.alert-component').addClass('alert-danger');
					$('#alert-body').html(data.message);
					new PNotify({
						title: 'Error',
						text: data.message,
						type: 'error'
					});
				}
			})
			.fail(function() {
				wrong.play();
				$('.alert-component').addClass('alert-danger');
				$('#alert-body').html('An error occurred while saving audit');
				new PNotify({
					title: 'Error',
					text: 'An error occurred while saving audit',
					type: 'error'
				});
			});
		}

		function resetAudit() {
			currentTag = null;
			currentTagData = null;
			$('#tag-info-card').hide();
			$('.alert-component').removeClass('alert-danger')
					.removeClass('alert-success').removeClass('alert-warning')
					.addClass('alert-info');
			$('#alert-body').html('Scan the Tag to Audit');
			$('#last-scanned').html('');
			$('#scanner').val('').focus();
		}

		function onScanFailure(error) {
			// handle scan failure, usually better to ignore and keep scanning.
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
