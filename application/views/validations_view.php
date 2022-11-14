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
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dropzone/dropzone.css" />
	<link href="<?php echo base_url(); ?>assets/css/components.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url(); ?>assets/css/colors.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url(); ?>assets/css/icons/fontawesome/styles.min.css" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/loaders/pace.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/libraries/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/loaders/blockui.min.js"></script>
	<script type="text/javascript">
		var indexvalidation=<?php echo $validationstep; ?>;
		var base_url='<?php echo base_url(); ?>';
	</script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/forms/wizards/steps.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/forms/styling/uniform.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/libraries/jasny_bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/forms/validation/validate.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/extensions/cookie.js"></script>
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"></span>IFA Validations</h4>
						</div>

					</div>

				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<div class="panel panel-white">
						<div class="panel-heading">
							<h6 class="panel-title">Complete all steps</h6>
						</div>

	                	<form class="steps-validation" action="#">
							<h6>1.- Import Item master</h6>
							<fieldset>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<input type="text" id="ItemMaster" name="ItemMaster" class="form-control required" readonly>
											<div id="msgpanel" class="alert alert-info">
												<strong id="msgresult">
													<span>[A] -> "Item", [E] -> "Description", [I] -> "Unit", [H] -> "Standard", [F] -> "Group", [J] -> "Type" columns are expected for MasterData file in sheet named "data" (without quotes).</span>
												</strong>
											</div>
											<div id="uploadItemmaster" class="dropzone dz-square">
											      <div class="dz-default dz-message">Drop file here to upload Item master</div>
											</div>											
										</div>
									</div>
								</div>
							</fieldset>

							<h6>2.- Capture</h6>
							<fieldset>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>Is the capture over?: <span class="text-danger">*</span></label>
			                                <input type="checkbox" name="capture" class="required" value="1">
		                                </div>
									</div>
								</div>
							</fieldset>

							<h6>3.- Import "Perpetuo"</h6>
							<fieldset>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<input type="text" id="Perpetuo" name="Perpetuo" class="form-control required" readonly>
											<div id="msgpanel2" class="alert alert-info">
												<strong id="msgresult2">
													<span>[A] -> "Item Code", [B] -> "Description", [D] -> "Type", [G] -> "On Hand" columns are expected for Perpetuo file in sheet named "data" (without quotes).</span>
												</strong>
											</div>
											<div id="uploadPerpetuo" class="dropzone dz-square">
											      <div class="dz-default dz-message">Drop file here to upload Perpetuo</div>
											</div>											
										</div>
									</div>
								</div>
							</fieldset>

							<h6>4.- Finalize</h6>
							<fieldset>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<div class="alert alert-success" role="alert" style="font-size: 30px;">
												Well done! <a target="_blank" href="<?php echo base_url();?>assets/ifachecklist.pdf">Print checklist</a> <i class="glyphicon glyphicon-check"></i>
											</div>
	                                    </div>
									</div>
								</div>
							</fieldset>
						</form>
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
	$(document).ready(function() {
		var base_url='<?php echo base_url(); ?>';
		var form = $(".steps-validation").show();
	    // Initialize wizard
	    $(".steps-validation").steps({
	        headerTag: "h6",
	        bodyTag: "fieldset",
	        saveState: true,
	        transitionEffect: "fade",
	        titleTemplate: '<span class="number">#index#</span> #title#',
	        autoFocus: true,
	        onStepChanging: function (event, currentIndex, newIndex) {
	            // Allways allow previous action even if the current form is not valid!
	            if (currentIndex > newIndex) {
	                return true;
	            }

	            // Forbid next action on "Warning" step if the user is to young
	            if (newIndex === 3 && Number($("#age-2").val()) < 18) {
	                return false;
	            }

	            // Needed in some cases if the user went back (clean up)
	            if (currentIndex < newIndex) {

	                // To remove error styles
	                form.find(".body:eq(" + newIndex + ") label.error").remove();
	                form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
	            }

	            form.validate().settings.ignore = ":disabled,:hidden";
	            return form.valid();
	        },

	        onStepChanged: function (event, currentIndex, priorIndex) {
	            // Used to skip the "Warning" step if the user is old enough.
	            if (currentIndex === 2 && Number($("#age-2").val()) >= 18) {
	                form.steps("next");
	            }

	            // Used to skip the "Warning" step if the user is old enough and wants to the previous step.
	            if (currentIndex === 2 && priorIndex === 3) {
	                form.steps("previous");
	            }
	        },

	        onFinishing: function (event, currentIndex) {
	            form.validate().settings.ignore = ":disabled";
	            return form.valid();
	        },

	        onFinished: function (event, currentIndex) {
	            alert("Submitted!");
	        }
	    });
	    // Initialize validation
	    //$.validator.setDefaults({ ignore: '' });
	    $(".steps-validation").validate({
	        ignore: ":hidden:not(#myitem)", // ignore hidden fields input[type=hidden], .select2-search__field
	        errorClass: 'validation-error-label',
	        successClass: 'validation-valid-label',
	        highlight: function(element, errorClass) {
	            $(element).removeClass(errorClass);
	        },
	        unhighlight: function(element, errorClass) {
	            $(element).removeClass(errorClass);
	        },

	        // Different components require proper error label placement
	        errorPlacement: function(error, element) {
	            // Styled checkboxes, radios, bootstrap switch
	            if (element.parents('div').hasClass("checker") || element.parents('div').hasClass("choice") || element.parent().hasClass('bootstrap-switch-container') ) {
	                if(element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
	                    error.appendTo( element.parent().parent().parent().parent() );
	                }
	                 else {
	                    error.appendTo( element.parent().parent().parent().parent().parent() );
	                }
	            }

	            // Unstyled checkboxes, radios
	            else if (element.parents('div').hasClass('checkbox') || element.parents('div').hasClass('radio')) {
	                error.appendTo( element.parent().parent().parent() );
	            }

	            // Input with icons and Select2
	            else if (element.parents('div').hasClass('has-feedback') || element.hasClass('select2-hidden-accessible')) {
	                error.appendTo( element.parent() );
	            }

	            // Inline checkboxes, radios
	            else if (element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
	                error.appendTo( element.parent().parent() );
	            }

	            // Input group, styled file input
	            else if (element.parent().hasClass('uploader') || element.parents().hasClass('input-group')) {
	                error.appendTo( element.parent().parent() );
	            }

	            else {
	                error.insertAfter(element);
	            }
	        },
	        rules: {
	            email: {
	                email: true
	            }
	        }
	    });

	    Dropzone.autoDiscover = false;
	    $("#uploadItemmaster").dropzone({
	        url: "<?php echo base_url();?>reports/fileupload",
	        addRemoveLinks: false,
	        success: function (file, response) {
	            file.previewElement.classList.add("dz-success");
	            process(file.name,'MasterData');//file.type
	        },
	        error: function (file, response) {
	            file.previewElement.classList.add("dz-error");
	        }
	    });
	    $("#uploadPerpetuo").dropzone({
	        url: "<?php echo base_url();?>reports/fileupload",
	        addRemoveLinks: false,
	        success: function (file, response) {
	            file.previewElement.classList.add("dz-success");
	            process(file.name,'Perpetuo');//file.type
	        },
	        error: function (file, response) {
	            file.previewElement.classList.add("dz-error");
	        }
	    });
	});
	function process(filename,tipo){
		var diferent='';
		if(tipo=='Perpetuo') diferent='2';
		waitingDialog.show('Importing '+filename+'...');
		$.post("<?php echo base_url(); ?>reports/import/"+tipo,{filename:filename},function(result){
			waitingDialog.hide();
			$('#msgpanel'+diferent).removeClass('alert-success').removeClass('alert-danger').removeClass('alert-info');
			if(tipo=='MasterData'){
				if(result.includes('columns are expected') || result.includes('price "zero"')){
					$('#msgpanel').addClass('alert-danger');
				}else{
					$('#msgpanel').addClass('alert-success');
					$('#ItemMaster').val('Success');
				}
			}else{
				if(result.includes('columns are expected') || result.includes('items that do not exist')){
					$('#msgpanel2').addClass('alert-danger');
				}else{
					$('#msgpanel2').addClass('alert-success');
					$('#Perpetuo').val('Success');
				}
			}

			$('#msgpanel'+diferent).show('slow', function() {
				$('#msgresult'+diferent).html(filename+' '+result+'<br/>');
			});
		}).fail(function(){
			waitingDialog.hide();
			$('#msgresult'+diferent).html('Problem with '+filename+'<br/>');
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
