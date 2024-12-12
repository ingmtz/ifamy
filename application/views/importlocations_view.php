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
	<!-- Dropzone -->
	<script src="<?php echo base_url(); ?>assets/dropzone/dropzone.js"></script>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dropzone/dropzone.css" />

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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"></span>Import Locations file</h4>
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
										<div id="msgpanel" class="alert alert-info">
											<strong id="msgresult">
												<span>[A] -&gt; "WH", [B] -&gt; "Planta", [C] -&gt; "Area", [D] -&gt; "Location" columns are expected for locations file in sheet named "data" (without quotes).</span>
											</strong>
										</div>
										<div class="tab-content">
											<div id="w4-account" class="tab-pane active">
												<div class="panel-body">
													<form action="<?php echo base_url();?>reports/fileupload" class="dropzone dz-square" id="dropzone-file"></form>
												</div>
											</div>
										</div>
								</div>
							</section>
						</div>
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

</body>
<script type="text/javascript">
	Dropzone.autoDiscover = false;
	$(function() {
	  var myDropzone = new Dropzone("#dropzone-file");
	  myDropzone.on("success", function(file) {
	  	//console.log(file.type);
	  	process(file.name,file.type);
	  });
	});
	function process(filename,tipo){
		waitingDialog.show('Importing '+filename+'...');
		$.post("<?php echo base_url(); ?>reports/import/Locations",{filename:filename,tipo:'Locations'},function(result){
			waitingDialog.hide();
			$('#msgpanel').removeClass('alert-success').removeClass('alert-danger');
			if(result.includes('columns are expected')){
				$('#msgpanel').addClass('alert-danger');
			}else{
				$('#msgpanel').addClass('alert-success');
			}
			$('#msgpanel').show('slow', function() {
				$('#msgresult').html(filename+' '+result+'<br/>');
			});
		}).fail(function(){
			waitingDialog.hide();
			$('#msgresult').append('Problem with '+filename+'<br/>');
			new PNotify({
					title: 'IFA',
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
