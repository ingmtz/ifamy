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

	<!-- Theme JS files -->
	<!--<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/tables/datatables/datatables.min.js"></script>-->
	<link href="<?php echo base_url(); ?>assets/datatable/css/bootstrap.datatable.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/datatable/css/dataTables.tableTools.min.css" rel="stylesheet">
    <script src="<?php echo base_url(); ?>assets/datatable/js/jquery.dataTables.js"></script>
	<script src="<?php echo base_url(); ?>assets/datatable/js/dataTables.tableTools.js"></script>

	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/chosen/chosen.css" />
	<script src="<?php echo base_url(); ?>assets/chosen/chosen.jquery.js"></script>
	<script src="<?php echo base_url(); ?>assets/jquery.redirect.js"></script>
	
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/app.js"></script>
	<!-- /theme JS files -->
	<style type="text/css">
        /*input{
            text-transform: uppercase;
        }*/
    </style>

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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"><?php echo $this->lang->line('Settings'); ?></span></h4>
						</div>
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<!-- Page length options -->
					<div class="panel panel-flat">
						<div class="panel-body">
							<form id="frmsettings">
							<?php if($this->config->item('client')=='AtlanticPrime'){ ?>
							<div class="row" style="margin-bottom:15px;">
								<span><b><?php echo $this->lang->line('Field validations'); ?>:</b></span>
							</div>
							<div class="row">
								<div class="col-md-2 form-inline">
									"<?php echo $this->lang->line('Packing date'); ?>" <?php echo $this->lang->line('minimum value'); ?>
								</div>
								<div class="col-md-2 form-inline">
									"<?php echo $this->lang->line('Packing date'); ?>" <?php echo $this->lang->line('maximum value'); ?>
								</div>
							</div>
							<div class="row" style="margin-bottom:15px;">
								<div class="col-md-2 form-inline">
									<input type="date" class="form-control" name="fechaempaquemin" value="<?php echo $settings->fechaempaquemin ?>" />
								</div>
								<div class="col-md-2 form-inline">
									<input type="date" class="form-control" name="fechaempaquemax" value="<?php echo $settings->fechaempaquemax ?>" />
								</div>
							</div>
							<div class="row">
								<div class="col-md-2 form-inline">
									"<?php echo $this->lang->line('Receipt date'); ?>" <?php echo $this->lang->line('minimum value'); ?>
								</div>
								<div class="col-md-2 form-inline">
									"<?php echo $this->lang->line('Receipt date'); ?>" <?php echo $this->lang->line('maximum value'); ?>
								</div>
							</div>
							<div class="row" style="margin-bottom:15px;">
								<div class="col-md-2 form-inline">
									<input type="date" class="form-control" name="fecharecibomin" value="<?php echo $settings->fecharecibomin ?>" />
								</div>
								<div class="col-md-2 form-inline">
									<input type="date" class="form-control" name="fecharecibomax" value="<?php echo $settings->fecharecibomax ?>" />
								</div>
							</div>
							<div class="row">
								<div class="col-md-2 form-inline">
									"<?php echo $this->lang->line('Expiry date'); ?>" <?php echo $this->lang->line('minimum value'); ?>
								</div>
								<div class="col-md-2 form-inline">
									"<?php echo $this->lang->line('Expiry date'); ?>" <?php echo $this->lang->line('maximum value'); ?>
								</div>
							</div>
							<div class="row" style="margin-bottom:15px;">
								<div class="col-md-2 form-inline">
									<input type="date" class="form-control" name="fechacaducidadmin" value="<?php echo $settings->fechacaducidadmin ?>" />
								</div>
								<div class="col-md-2 form-inline">
									<input type="date" class="form-control" name="fechacaducidadmax" value="<?php echo $settings->fechacaducidadmax ?>" />
								</div>
							</div>
							<?php } ?>



							<div class="row" style="margin-bottom:15px;">
								<span><b>Prefixes:</b></span>
							</div>
							<div class="row">
								<div class="col-md-2 form-inline">
									Tag prefix
								</div>
								<div class="col-md-2 form-inline">
									Item prefix
								</div>
							</div>
							<div class="row" style="margin-bottom:15px;">
								<div class="col-md-2 form-inline">
									<input type="text" class="form-control" name="tagprefix" value="<?php echo $settings->tagprefix; ?>" maxlength="10" />
								</div>
								<div class="col-md-2 form-inline">
									<input type="text" class="form-control" name="itemprefix" value="<?php echo $settings->itemprefix; ?>" maxlength="1" />
								</div>
							</div>
							<div class="row" style="margin-bottom:15px;">
								<span><b>Others:</b></span>
							</div>
							<div class="row">
								<div class="col-md-2 form-inline">
									Start Tag
								</div>
							</div>
							<div class="row" style="margin-bottom:15px;">
								<div class="col-md-2 form-inline">
									<input type="number" class="form-control" name="starttag" value="<?php echo $settings->starttag; ?>" />
								</div>
							</div>
							<?php if($this->config->item('client')=='CVG'){ ?>
							<div class="row" style="margin-bottom:15px;">
								<span><b>Inventory Type:</b></span>
							</div>
							<div class="row">
								<div class="col-md-6 form-inline">
									<div class="btn-group btn-group-toggle" data-toggle="buttons">
									  <label class="btn btn-secondary <?php if($settings->inventorytype=='' or $settings->inventorytype=='TRIM'){ ?>active<?php } ?>">
									    <input type="radio" name="inventorytype" value="TRIM" <?php if($settings->inventorytype=='' or $settings->inventorytype=='TRIM'){ ?>checked<?php } ?>> TRIM
									  </label>
									  <label class="btn btn-secondary <?php if($settings->inventorytype=='SEATS'){ ?>active<?php } ?>">
									    <input type="radio" name="inventorytype" value="SEATS" <?php if($settings->inventorytype=='SEATS'){ ?>checked<?php } ?>> SEATS
									  </label>
									</div>
								</div>
							</div>
							<div class="row" style="margin-bottom:15px;">
								<span><b>Bartender print labels:</b></span>
							</div>
							<div class="row">
								<div class="col-md-6 form-inline">
									BTW Tag path
								</div>
								<div class="col-md-6 form-inline">
									Tag files print path
								</div>
							</div>
							<div class="row" style="margin-bottom:15px;">
								<div class="col-md-6 form-inline">
									<input type="text" readonly class="form-control" name="btwtagfile" value="<?php echo $settings->btwtagfile; ?>" style="width:100%;" />
								</div>
								<div class="col-md-6 form-inline">
									<input type="text" readonly class="form-control" name="tagfilesprintpath" value="<?php echo $settings->tagfilesprintpath; ?>" style="width:100%;" />
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 form-inline">
									BTW location path
								</div>
								<div class="col-md-6 form-inline">
									Location files print path
								</div>
							</div>
							<div class="row" style="margin-bottom:15px;">
								<div class="col-md-6 form-inline">
									<input type="text" readonly class="form-control" name="btwlocationfile" value="<?php echo $settings->btwlocationfile; ?>" style="width:100%;" />
								</div>
								<div class="col-md-6 form-inline">
									<input type="text" readonly class="form-control" name="locationfilesprintpath" value="<?php echo $settings->locationfilesprintpath; ?>" style="width:100%;" />
								</div>
							</div>

							<div class="row">
								<div class="col-md-6 form-inline">
									BTW user path
								</div>
								<div class="col-md-6 form-inline">
									User's print path file
								</div>
							</div>
							<div class="row" style="margin-bottom:15px;">
								<div class="col-md-6 form-inline">
									<input type="text" readonly class="form-control" name="btwuserfile" value="<?php echo $settings->btwuserfile; ?>" style="width:100%;" />
								</div>
								<div class="col-md-6 form-inline">
									<input type="text" readonly class="form-control" name="userfilesprintpath" value="<?php echo $settings->userfilesprintpath; ?>" style="width:100%;" />
								</div>
							</div>
							<?php } ?>
							<div class="row" style="margin-bottom:15px;">
								<div class="col-md-2 form-inline">
									<button type="button" id="save" class="btn btn-primary"><?php echo $this->lang->line('Save'); ?> <i class="icon-arrow-right14 position-right"></i></button>
								</div>
							</div>
							</form>
						</div>
					</div>
					<!-- /page length options -->
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
	$(document).ready(function($) {
		$('[name="inventorytype"]').change(function(event){
			console.log($(this).val(), $(this).is(':checked'));
			if($(this).is(':checked')){
				if($(this).val()=='TRIM'){
					$('input[name="btwtagfile"]').val('\\\\newalpwbarten03\\Sites\\Saltillo_Trim\\barcode_labels\\ifa_tag.btw');
					$('input[name="btwlocationfile"]').val('\\\\newalpwbarten03\\Sites\\Saltillo_Trim\\barcode_labels\\ifa_location.btw');
					$('input[name="btwuserfile"]').val('\\\\newalpwbarten03\\Sites\\Saltillo_Trim\\barcode_labels\\ifa_user.btw');

					$('input[name="tagfilesprintpath"]').val('\\\\newalpwbarten03\\Sites\\Saltillo_Trim\\barcode_labels\\SerialLabel_Seq\\_Labels\\');
					$('input[name="locationfilesprintpath"]').val('\\\\newalpwbarten03\\Sites\\Saltillo_Trim\\barcode_labels\\SerialLabel_Seq\\_Labels\\');
					$('input[name="userfilesprintpath"]').val('\\\\newalpwbarten03\\Sites\\Saltillo_Trim\\barcode_labels\\SerialLabel_Seq\\_Labels\\');
				}else{
					$('input[name="btwtagfile"]').val('\\\\newalpwbarten02\\Sites\\Saltillo_056\\barcode_labels\\ifa_tag.btw');
					$('input[name="btwlocationfile"]').val('\\\\newalpwbarten02\\Sites\\Saltillo_056\\barcode_labels\\ifa_location.btw');
					$('input[name="btwuserfile"]').val('\\\\newalpwbarten02\\Sites\\Saltillo_056\\barcode_labels\\ifa_user.btw');

					$('input[name="tagfilesprintpath"]').val('\\\\newalpwbarten02\\Sites\\Saltillo_056\\barcode_labels\\SerialLabel_Seq\\_Labels\\');
					$('input[name="locationfilesprintpath"]').val('\\\\newalpwbarten02\\Sites\\Saltillo_056\\barcode_labels\\SerialLabel_Seq\\_Labels\\');
					$('input[name="userfilesprintpath"]').val('\\\\newalpwbarten02\\Sites\\Saltillo_056\\barcode_labels\\SerialLabel_Seq\\_Labels\\');
				}
			}
		});
		$('[name="inventorytype"]').change();
		$('#save').click(function(event) {
			$.post('<?php echo base_url();?>settings/updatesettings', {data: $('#frmsettings').serialize()}, function(data) {
					new PNotify({
					            title: 'Success',
					            text: 'Settings updated',
					            addclass: 'bg-success'
					        });
					<?php if($this->config->item('client')=='CVG'){ ?>
						$('#lblinventorytype').html('<?php echo $this->config->item('title'); ?> '+$('[name="inventorytype"]:checked').val());
					<?php } ?>
				}).fail(function(xhr, status, error) {
					alert('Something was wrong');
			    });
		});
	});
</script>
</html>
