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
	<link href="<?php echo base_url(); ?>assets/css/icons/fontawesome/styles.min.css" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/loaders/pace.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/libraries/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/loaders/blockui.min.js"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	

	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/app.js"></script>
	<!-- /theme JS files -->

	<!--<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/tables/datatables/datatables.min.js"></script>-->
	<link href="<?php echo base_url(); ?>assets/datatable/css/bootstrap.datatable.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/datatable/css/dataTables.tableTools.min.css" rel="stylesheet">
    <script src="<?php echo base_url(); ?>assets/datatable/js/jquery.dataTables.js"></script>
	<script src="<?php echo base_url(); ?>assets/datatable/js/dataTables.tableTools.js"></script>

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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Import Files</span></h4>
						</div>

					</div>

				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<div class="row">
						<div class="col-xs-12">
							<section class="panel form-wizard">
								<!--<header class="panel-heading">
									<h2 class="panel-title">Import excel</h2>
								</header>-->
								<div class="panel-body">
										<div class="alert alert-danger" id="alert">
											Stop! You are about to delete all data. Are you sure?
										</div>
										
										<!-- Inventory File Section -->
										<div class="row">
											<div class="col-md-6">
												<div class="panel panel-flat">
													<div class="panel-heading">
														<h5 class="panel-title">Inventory File</h5>
													</div>
													<div class="panel-body">
														<div id="msgpanel-inventory" class="alert alert-info">
															<strong id="msgresult-inventory">
																[C] -> "HU", [D] -> "Material", [E] -> "Description", [G] -> "Qty", [H] -> "UM", [Q] -> "Storage Location", [R] -> "Location", [T] -> "Cost Price" columns are expected. Previous data will be truncated.
															</strong>
														</div>
														<div class="form-group">
															<label for="file-upload-inventory" class="control-label">Select inventory file to upload</label>
															<input type="file" class="form-control" id="file-upload-inventory" accept=".xls,.xlsx,.csv">
														</div>
													</div>
												</div>
											</div>
											
											<!-- Locations File Section -->
											<div class="col-md-6">
												<div class="panel panel-flat">
													<div class="panel-heading">
														<h5 class="panel-title">Locations File</h5>
													</div>
													<div class="panel-body">
														<div id="msgpanel-locations" class="alert alert-info">
															<strong id="msgresult-locations">
																[A] -> "PLANT", [B] -> "AREA", [C] -> "WAREHOUSE", [D] -> "LOCATION", [E] -> "AbsEntry" columns are expected. Previous location data will be truncated.
															</strong>
														</div>
														<div class="form-group">
															<label for="file-upload-locations" class="control-label">Select locations file to upload</label>
															<input type="file" class="form-control" id="file-upload-locations" accept=".xls,.xlsx,.csv">
														</div>
													</div>
												</div>
											</div>
										</div>
								</div>
							</section>
						</div>
					</div>
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
	$(function() {
	  // Inventory file upload
	  $('#file-upload-inventory').on('change', function(e) {
	    var file = e.target.files[0];
	    if (file) {
	      uploadInventoryFile(file);
	    }
	  });
	  
	  // Locations file upload
	  $('#file-upload-locations').on('change', function(e) {
	    var file = e.target.files[0];
	    if (file) {
	      uploadLocationsFile(file);
	    }
	  });
	});
	
	function uploadInventoryFile(file) {
		var formData = new FormData();
		formData.append('file', file);
		
		waitingDialog.show('Uploading '+file.name+'...');
		
		$.ajax({
			url: '<?php echo base_url();?>reports/fileupload',
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			success: function(response) {
				processInventory(file.name, file.type);
			},
			error: function(xhr, status, error) {
				waitingDialog.hide();
				$('#msgpanel-inventory').addClass('alert-danger').removeClass('alert-success');
				$('#msgresult-inventory').html('Upload failed for '+file.name+'<br/>');
			}
		});
	}
	
	function uploadLocationsFile(file) {
		var formData = new FormData();
		formData.append('file', file);
		
		waitingDialog.show('Uploading '+file.name+'...');
		
		$.ajax({
			url: '<?php echo base_url();?>reports/fileuploadlocations',
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			success: function(response) {
				processLocations(file.name, file.type);
			},
			error: function(xhr, status, error) {
				waitingDialog.hide();
				$('#msgpanel-locations').addClass('alert-danger').removeClass('alert-success');
				$('#msgresult-locations').html('Upload failed for '+file.name+'<br/>');
			}
		});
	}
	
	function processInventory(filename,tipo){
		waitingDialog.show('Importing '+filename+'...');
		$.post("<?php echo base_url(); ?>reports/import",{filename:filename,tipo:tipo},function(result){
			waitingDialog.hide();
			$('#msgpanel-inventory').removeClass('alert-success').removeClass('alert-danger');
			if(result.includes('Unknown') || result.includes('skipped') || result.includes('Please check') || result.includes('not imported')){
				$('#msgpanel-inventory').addClass('alert-danger').removeClass('alert-success');
			}else{
				$('#msgpanel-inventory').addClass('alert-success').removeClass('alert-danger');
			}
			$('#msgpanel-inventory').show('slow', function() {
				$('#msgresult-inventory').html('<br/>'+filename+' '+result+'<br/>');
			});
		}).fail(function(){
			waitingDialog.hide();
			$('#msgpanel-inventory').addClass('alert-danger').removeClass('alert-success');
			$('#msgresult-inventory').html('Problem with '+filename+'<br/>');
			new PNotify({
					title: '<?php echo $this->config->item('title'); ?>',
					text: 'An error has ocurred.',
					type: 'error'
				});
		});
	}
	
	function processLocations(filename,tipo){
		waitingDialog.show('Importing '+filename+'...');
		$.post("<?php echo base_url(); ?>reports/importlocationsfile",{filename:filename,tipo:tipo},function(result){
			waitingDialog.hide();
			$('#msgpanel-locations').removeClass('alert-success').removeClass('alert-danger');
			if(result.includes('Unknown') || result.includes('skipped') || result.includes('Please check') || result.includes('not imported')){
				$('#msgpanel-locations').addClass('alert-danger').removeClass('alert-success');
			}else{
				$('#msgpanel-locations').addClass('alert-success').removeClass('alert-danger');
			}
			$('#msgpanel-locations').show('slow', function() {
				$('#msgresult-locations').html('<br/>'+filename+' '+result+'<br/>');
			});
		}).fail(function(){
			waitingDialog.hide();
			$('#msgpanel-locations').addClass('alert-danger').removeClass('alert-success');
			$('#msgresult-locations').html('Problem with '+filename+'<br/>');
			new PNotify({
					title: '<?php echo $this->config->item('title'); ?>',
					text: 'An error has ocurred.',
					type: 'error'
				});
		});
	}
	var waitingDialog = (function ($) {

		    // Creating modal dialog's DOM
			var $dialog = $(
				'<div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">' +
				'<div class="modal-dialog modal-m">' +
				'<div class="modal-content">' +
					'<div class="modal-header"><h3 style="margin:0;"></h3></div>' +
					'<div class="modal-body">' +
						'<div class="progress progress-striped active" style="margin-bottom:0;"><div class="progress-bar" style="width: 100%"></div></div>' +
					'</div>' +
				'</div></div></div>');
				return {
					/**
					 * Opens our dialog
					 * @param message Custom message
					 * @param options Custom options:
					 * 				  options.dialogSize - bootstrap postfix for dialog size, e.g. "sm", "m";
					 * 				  options.progressType - bootstrap postfix for progress bar type, e.g. "success", "warning".
					 */
					show: function (message, options) {
						// Assigning defaults
						var settings = $.extend({
							dialogSize: 'm',
							progressType: ''
						}, options);
						if (typeof message === 'undefined') {
							message = 'Loading';
						}
						if (typeof options === 'undefined') {
							options = {};
						}
						// Configuring dialog
						$dialog.find('.modal-dialog').attr('class', 'modal-dialog').addClass('modal-' + settings.dialogSize);
						$dialog.find('.progress-bar').attr('class', 'progress-bar');
						if (settings.progressType) {
							$dialog.find('.progress-bar').addClass('progress-bar-' + settings.progressType);
						}
						$dialog.find('h3').text(message);
						// Opening dialog
						$dialog.modal();
					},
					/**
					 * Closes dialog
					 */
					hide: function () {
						$dialog.modal('hide');
					}
				}
			})(jQuery);
</script>
</html>
