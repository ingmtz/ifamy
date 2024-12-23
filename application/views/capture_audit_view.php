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
						<h6 class="alert-heading" id="alert-title">Scan HU</h6>
						<span class="text-semibold" id="alert-body">Scan the HU code to start the process.</span>
						<br/>
						<span class="text-semibold" id="info-scanned"></span>
					</div>

					<div class="row border-bottom-3" id="detail-scanned">

					</div>
					<div class="row border-bottom-3" id="verify-scanned" style="display: none">
						<br/>
						<div class="col-md-12 text-center form-group">
							Is the above information correct?
						</div>
						<div class="col-md-6 text-center form-group">
							<button class="btn btn-xs btn-success" id="auditCorrectData">Correct</button>
						</div>
						<div class="col-md-6 text-center form-group">
							<button class="btn btn-xs btn-danger" id="auditIncorrectData">Incorrect</button>
						</div>
					</div>
					<div class="row border-bottom-3" id="fix-scanned" style="display: none">
						<br/>
						<div class="col-md-6">
							Select a reason
							<select id="incorrectDataReason" class="form-control chosen-select">
								<option value="1">Quantity Error</option>
								<option value="2">Missing transaction</option>
								<option value="3">Wrong Location</option>
								<option value="4">Others</option>
							</select>
						</div>
						<div class="col-md-6">
							Capture the right data
							<input type="text" id="fixedData" class="form-control" placeholder="">
						</div>
						<div class="col-md-12">
							<br/>
						</div>
						<div class="col-md-6">
							<button class="btn btn-xs btn-success" id="auditSaveFixedData">Save</button>
						</div>
					</div>
					<br/>

					<div id="scann-area">

						<input type="text" id="scanner" class="form-control" placeholder="Capture code here" autofocus>
						
						<div id="reader" width="50%"></div>
					</div>

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
		var wh = '<?= $wh; ?>';


		var lastScanned = '';
		function onScanSuccess(decodedText, decodedResult) {
			if (decodedText === lastScanned) {
				return;
			}
			lastScanned = decodedText;
			// console.log(`Code matched = ${decodedText}`, decodedResult);
			// new PNotify({
			// 	title: 'Success',
			// 	text: `Code matched = ${decodedText}`,
			// 	type: 'success'
			// });
			
			if($("#fix-scanned").is(":visible")){
				checkLocation(decodedText);
			}else{
				checkHu(decodedText);
			}
			
		}

		$('#scanner').on('keypress', function(e) {
			if(e.which == 13) {
				
				var decodedText = $(this).val();
				$(this).val('');
				if($("#fix-scanned").is(":visible")){
					checkLocation(decodedText);
				}else{
					checkHu(decodedText);
				}
			}
		});

		$(document).delegate('#auditCorrectData', 'click', function(){
			$('.alert-component').removeClass('alert-danger')
				.removeClass('alert-info').removeClass('alert-success');

			saveAudit(true);
		});

		$(document).delegate('#auditSaveFixedData', 'click', function(){
			$('.alert-component').removeClass('alert-danger')
				.removeClass('alert-info').removeClass('alert-success');

			if($("#fixedData").val() == ""){

				wrong.play();
				$('.alert-component').addClass('alert-danger');
				$('#info-scanned').html("Missing right data");

			}

			if($('#incorrectDataReason').val() == 1 && isNaN($("#fixedData").val())){
				wrong.play();
				$('.alert-component').addClass('alert-danger');
				$('#info-scanned').html("Invalid quantity");
			}

			saveAudit(false);
			// if($('#incorrectDataReason').val() == 1){
			// 	checkItemCode($("#fixedData").val());
			// }else{
			// 	saveAudit(false);
			// }
		});		

		
		$(document).delegate('#auditIncorrectData', 'click', function(){
			$('#fix-scanned').css("display","");
			$('#verify-scanned').css("display","none");
		});

		$('#incorrectDataReason').click(function(){
			if($(this).val() == 3){
				$("#fixedData").val("");
				$("#fixedData").prop("disabled",true);
				$("#fixedData").prop("placeholder","Scan the right location");
				$('#scann-area').css("display","");
			}else{
				$("#fixedData").prop("disabled",false);
				$("#fixedData").prop("placeholder","");
				$('#scann-area').css("display","none");

			}
		});

		function checkLocation(decodedText){
			$("#fixedData").val("");
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
						correct.play();
						$("#fixedData").val(decodedText);
						$('.alert-component').addClass('alert-success');
						$('#info-scanned').html("");
						
					}else{
						wrong.play();
						$('.alert-component').addClass('alert-danger');
						$('#info-scanned').html(data.message);
						// new PNotify({
						// 	title: 'Error',
						// 	text: data.message,
						// 	type: 'error'
						// });
					}
				}catch(e){
					wrong.play();
					$('.alert-component').addClass('alert-danger');
					$('#info-scanned').html('There was an error');
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

		let hu;
		function checkHu(decodedText){
			$('.alert-component').removeClass('alert-danger')
					.removeClass('alert-info').removeClass('alert-success')
					.removeClass('alert-warning');

			const arrHu = decodedText.split('|');
			if(arrHu.length == 4){
				decodedText = arrHu[2];
				// Just preserve numbers
				decodedText = decodedText.replace(/[^0-9]/g, '');
			}else if(arrHu.length == 1){
				// Just preserve numbers
				decodedText = decodedText.replace(/[^0-9]/g, '');
			}else{
				wrong.play();
				$('.alert-component').addClass('alert-danger');
				$('#info-scanned').html('Invalid HU');
				return;
			}

			$.ajax({
				url: '<?= base_url(); ?>capture/checkhuaudit',
				type: 'POST',
				dataType: 'json',
				data: {tag: decodedText},
			})
			.done(function(response) {
				try{
					if(response.status){
						correct.play();
						$('.alert-component').addClass('alert-success');
						hu = response.data;
						let htmlinfo = "";
						htmlinfo += '<div class="col-md-6">Location: '+hu.location+'</div>';
						htmlinfo += '<div class="col-md-6">HU: '+hu.tag+'</div>';
						htmlinfo += '<div class="col-md-6">Item: '+hu.itemcode+'</div>';
						htmlinfo += '<div class="col-md-6">Description: '+hu.description+'</div>';
						//htmlinfo += '<div class="col-md-6">OM: '+hu.uom+'</div>';
						htmlinfo += '<div class="col-md-6">Qty: '+hu.qty+'</div>';
						htmlinfo += '<div class="col-md-6">Open box: '+(hu.openbox == "1" ? "Yes" : "No")+'</div>';
						$('#detail-scanned').html(htmlinfo);
						$('#verify-scanned').css("display","");
						$('#scann-area').css("display","none");
					}else{
						wrong.play();
						$('#info-scanned').html(response.message);
						if(response.message == 'HU has not been scanned yet'){
							$('.alert-component').addClass('alert-danger');
						}else{
							$('.alert-component').addClass('alert-warning');
						}
						
					}
				}catch(e){
					wrong.play();
					$('.alert-component').addClass('alert-danger');
					$('#info-scanned').html('There was an error');
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


		function checkItemCode(decodedText){
			$("#fixedData").val("");
			
			$.ajax({
				url: '<?= base_url(); ?>capture/checkitemcode',
				type: 'POST',
				dataType: 'json',
				data: {itemcode: decodedText},
			})
			.done(function(data) {
				try{
					if(data.status){
						$("#fixedData").val(decodedText);
						
						$('#info-scanned').html("");
						
						saveAudit(false);
					}else{
						wrong.play();
						$('.alert-component').addClass('alert-danger');
						$('#info-scanned').html(data.message);
						// new PNotify({
						// 	title: 'Error',
						// 	text: data.message,
						// 	type: 'error'
						// });
					}
				}catch(e){
					wrong.play();
					$('.alert-component').addClass('alert-danger');
					$('#info-scanned').html('There was an error');
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

		function saveAudit(correctData){
			if(!correctData){
				let reason = $('#incorrectDataReason').val();
				switch(Number(reason)){
					case 1:
						hu.qty = $("#fixedData").val();
						break;
					case 2: // Missing transaction
						hu.itemcode = $("#fixedData").val();
						break;
					case 3:
							hu.location = $("#fixedData").val();
							break;
					case 4:
						hu.uom = $("#fixedData").val();
						break;						
				}
			}
			
			$.ajax({
				url: '<?= base_url(); ?>capture/saveAudit',
				type: 'POST',
				dataType: 'json',
				data: {hu: hu, correct: correctData, reason: $('#incorrectDataReason option:selected').text()},
			})
			.done(function(data) {
				
				correct.play();
				$('#verify-scanned').css("display","none");
				$('#fix-scanned').css("display","none");
				$('#scann-area').css("display","");
				$('#detail-scanned').html("");
				$("#fixedData").val("");
				$('.alert-component').addClass('alert-success');
					
			})
			.fail(function() {
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
