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

	<!-- Datatables -->
	<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>assets/datatables2/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>assets/datatables2/dataTables.buttons.min.js"></script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>assets/datatables2/jszip.min.js"></script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>assets/datatables2/pdfmake.min.js"></script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>assets/datatables2/vfs_fonts.js"></script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>assets/datatables2/buttons.html5.min.js"></script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>assets/datatables2/buttons.print.min.js"></script>

	<link href="<?php echo base_url(); ?>assets/datatables2/responsive.bootstrap.min.css" rel="stylesheet">
	<script src="<?php echo base_url(); ?>assets/datatables2/dataTables.responsive.min.js"></script>
	<!-- Fin de datatables -->
	<script src="<?php echo base_url(); ?>assets/jquery.redirect.js"></script>
	<style>
	.page-container{
		width:auto!important;
	}
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"></span><?php echo $this->lang->line('Variation report by item'); ?></h4>
						</div>

					</div>

				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">
					<?php
					$moneysign='$';
					$decimals=2;
					if($this->config->item('client')=='AtlanticPrime'){
						$moneysign='';
						$decimals=0;
					}
					?>
					<!-- Basic datatable -->
					<div class="panel panel-flat">
						<?php if($nimd>0){ ?>
						<div id="msgpanel" class="alert alert-danger">
							<strong>Existen <?= $nimd; ?> items sin considerar por no encontrarse en MasterData. <a href="<?= base_url(); ?>reports/noInMasterData" target="_blank">Ver</a></strong>
						</div>
						<?php }?>
						<?php if($this->config->item('client')!='AtlanticPrime'){ ?>
						<table id="variationtable" class="table">
							<thead>
								<tr>
									<th></th>
									<th></th>
									<th colspan="3" style="background-color:rgba(59, 27, 103, 0.13);"><?php echo $this->lang->line('On hand'); ?></th>
									<th style="text-align:right;background-color:rgba(59, 27, 103, 0.13);"><b><?php echo $moneysign; ?><?php echo number_format($totals->extonhand, $decimals, '.', ','); ?></b></th>
									<th colspan="2" style="background-color:beige;"><?php echo $this->lang->line('Counted'); ?></th>
									<th style="text-align:right;background-color:beige;"><b><?php echo $moneysign; ?><?php echo number_format($totals->extcounted, $decimals, '.', ','); ?></b></th>
									<th colspan="2" style="background-color:aliceblue;"><?php echo $this->lang->line('Variation'); ?></th>
									<th style="text-align:right;background-color:<?php if($totals->extvariation<0){ ?>red<?php }else{ ?>aliceblue;<?php } ?>"><b><?php echo ($totals->extvariation<0 ? '-' : '').$moneysign.number_format(abs($totals->extvariation), $decimals, '.', ','); ?></b></th>
								</tr>
								<tr>
									<th><?php echo $this->lang->line('Item code'); ?></th>
									<th><?php echo $this->lang->line('Type'); ?></th>
									<th><?php echo $this->lang->line('Description'); ?></th>
									<th><?php echo $this->lang->line('Quantity'); ?></th>
									<th><?php echo $this->lang->line('Unit price'); ?> <?php if($this->config->item('client')=='CVG'){ ?>USD<?php }else{ ?>MXN<?php } ?></th>
									<th><?php echo $this->lang->line('Extended'); ?> <?php if($this->config->item('client')=='CVG'){ ?>USD<?php }else{ ?>MXN<?php } ?></th>
									<th><?php echo $this->lang->line('Quantity'); ?></th>
									<th><?php echo $this->lang->line('Unit price'); ?> <?php if($this->config->item('client')=='CVG'){ ?>USD<?php }else{ ?>MXN<?php } ?></th>
									<th><?php echo $this->lang->line('Extended'); ?> <?php if($this->config->item('client')=='CVG'){ ?>USD<?php }else{ ?>MXN<?php } ?></th>
									<th><?php echo $this->lang->line('Quantity'); ?></th>
									<th><?php echo $this->lang->line('Unit price'); ?> <?php if($this->config->item('client')=='CVG'){ ?>USD<?php }else{ ?>MXN<?php } ?></th>
									<th><?php echo $this->lang->line('Extended'); ?> <?php if($this->config->item('client')=='CVG'){ ?>USD<?php }else{ ?>MXN<?php } ?></th>
								</tr>
							</thead>
							<tbody></tbody>
							<tfoot>
								<tr>
									<th colspan="3"><?php echo $this->lang->line('Totals'); ?></th>
									<th style="background-color:aliceblue;text-align:right;"><b><?php echo number_format($totals->onhand, $decimals,'.',','); ?></b></th>
									<th></th>
									<th style="background-color: #EAFF5F;text-align:right;"><b><?php echo $moneysign; ?><?php echo number_format($totals->extonhand, $decimals, '.', ','); ?></b></th>
									<th style="background-color:aliceblue;text-align:right;"><b><?php echo number_format($totals->counted,$decimals,'.',','); ?></b></th>
									<th></th>
									<th style="background-color: #EAFF5F;text-align:right;"><b><?php echo $moneysign; ?><?php echo number_format($totals->extcounted, $decimals, '.', ','); ?></b></th>
									<th></th>
									<th></th>
									<th style="text-align:right;background-color:<?php if($totals->extvariation<0){ ?>red<?php }else{ ?>aliceblue;<?php } ?>"><b><?php echo ($totals->extvariation<0 ? '-' : '').$moneysign.number_format(abs($totals->extvariation), $decimals, '.', ','); ?></b></th>
								</tr>
							</tfoot>
						</table>
						<?php }else{ ?>
							<table id="variationtable" class="table">
								<thead>
									<tr>
										<th></th>
										<th></th>
										<th></th>
										<th style="background-color:rgba(59, 27, 103, 0.13);"><?php echo $this->lang->line('On hand'); ?></th>
										<th style="text-align:right;background-color:rgba(59, 27, 103, 0.13);"><b><?php echo $moneysign; ?><?php echo number_format($totals->extonhand, $decimals, '.', ','); ?></b></th>
										<th style="background-color:beige;"><?php echo $this->lang->line('Counted'); ?></th>
										<th style="text-align:right;background-color:beige;"><b><?php echo $moneysign; ?><?php echo number_format($totals->extcounted, $decimals, '.', ','); ?></b></th>
										<th style="background-color:aliceblue;"><?php echo $this->lang->line('Variation'); ?></th>
										<th style="background-color: <?php if($totals->extvariation<0){ ?>red<?php }else{ ?>#EAFF5F;<?php } ?>;text-align:right;"><b><?php echo (($totals->extvariation)<0 ? '-' : '').$moneysign.number_format(abs($totals->extvariation), $decimals, '.', ','); ?></b></th>
										<th style="background-color:aliceblue;"><b><?php echo number_format($totals->variationqtyabs, $decimals, '.', ','); ?></b></th>
										<th style="background-color:aliceblue;"><b><?php echo number_format($totals->extvariationabs, $decimals, '.', ','); ?></b></th>
									</tr>
									<tr>
										<th><?php echo $this->lang->line('Item code'); ?></th>
										<th><?php echo $this->lang->line('Description'); ?></th>
										<th><?php echo $this->lang->line('Unit price'); ?> <?php if($this->config->item('client')=='CVG'){ ?>USD<?php }else{ ?>MXN<?php } ?></th>
										<th><?php echo $this->lang->line('Quantity'); ?></th>
										<th><?php echo $this->lang->line('Extended'); ?> <?php if($this->config->item('client')=='CVG'){ ?>USD<?php }else{ ?>MXN<?php } ?></th>
										<th><?php echo $this->lang->line('Quantity'); ?></th>
										<th><?php echo $this->lang->line('Extended'); ?> <?php if($this->config->item('client')=='CVG'){ ?>USD<?php }else{ ?>MXN<?php } ?></th>
										<th><?php echo $this->lang->line('Quantity'); ?></th>
										<th><?php echo $this->lang->line('Extended'); ?> <?php if($this->config->item('client')=='CVG'){ ?>USD<?php }else{ ?>MXN<?php } ?></th>
										<th>ABS Qty</th>
										<th>ABS Extended</th>
									</tr>
								</thead>
								<tbody></tbody>
								<tfoot>
									<tr>
										<th colspan="3"><?php echo $this->lang->line('Totals'); ?></th>
										<th style="background-color:aliceblue;text-align:right;"><b><?php echo number_format($totals->onhand, 2,'.',','); ?></b></th>
										<th style="background-color: #EAFF5F;text-align:right;"><b><?php echo $moneysign; ?><?php echo number_format($totals->extonhand, $decimals, '.', ','); ?></b></th>
										<th style="background-color:aliceblue;text-align:right;"><b><?php echo number_format($totals->counted,2,'.',','); ?></b></th>
										<th style="background-color: #EAFF5F;text-align:right;"><b><?php echo $moneysign; ?><?php echo number_format($totals->extcounted, $decimals, '.', ','); ?></b></th>
										<th style="background-color:aliceblue;text-align:right;">
											<b><?php echo ($totals->variationqty<0 ? '-' : '').$moneysign.number_format(abs($totals->variationqty), 2, '.', ','); ?></b>
										</th>
										<th style="background-color: <?php if($totals->extvariation<0){ ?>red<?php }else{ ?>#EAFF5F;<?php } ?>;text-align:right;"><b><?php echo (($totals->extvariation)<0 ? '-' : '').$moneysign.number_format(abs($totals->extvariation), $decimals, '.', ','); ?></b></th>
										<th style="background-color:aliceblue;"><b><?php echo number_format($totals->variationqtyabs, $decimals, '.', ','); ?></b></th>
										<th style="background-color:aliceblue;"><b><?php echo number_format($totals->extvariationabs, $decimals, '.', ','); ?></b></th>
									</tr>
								</tfoot>
							</table>
						<?php } ?>
					</div>
					<div>
						<div id="paneldetail" class="panel panel-flat" style="display:none;">
							<div class="panel-body">
								<div class="row" style="margin-bottom:15px;">
									<span><b><?php echo $this->lang->line('Detail data'); ?>:</b></span>
								</div>
							</div>

							<table class="table datatable-show-all" id="detaildata">
								<thead>
									<tr>
										<th><?php echo $this->lang->line('Date/time'); ?></th>
										<th><?php echo $this->lang->line('Tag'); ?></th>
										<th><?php echo $this->lang->line('Area'); ?></th>
										<th><?php echo $this->lang->line('Rack'); ?></th>
										<th><?php echo $this->lang->line('Location'); ?></th>
										<th><?php echo $this->lang->line('Item code'); ?></th>
										<th><?php echo $this->lang->line('Status'); ?></th>
										<th><?php echo $this->lang->line('Quantity'); ?></th>
										<th><?php echo $this->lang->line('Item description'); ?></th>
										<th><?php echo $this->lang->line('UOM'); ?></th>
										<th><?php echo $this->lang->line('Type'); ?></th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
					<!-- /basic datatable -->
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
	var datatable, detailtable;
	$(document).ready(function() {
        datatable=$('#variationtable').DataTable({
			"language": {
                "url": "<?php echo base_url(); ?>assets/datatables2/<?php echo $this->session->userdata('site_lang'); ?>.json",
                buttons: {
		            pageLength: {
		                _: "<?php echo $this->lang->line('Showing'); ?> %d <?php echo $this->lang->line('rows'); ?>",
		                '-1': "<?php echo $this->lang->line('Showing all'); ?>"
		            }
		        }
			},
			createdRow: function( row, data, dataIndex ) {
				// Set the data-status attribute, and add a class
				//$( row ).find('td:eq(2)').prop('align', 'right');
				$(row).find('td').slice(3, 12).prop('align', 'right');
			},
			responsive: false,//true
			"processing": true,
        	"serverSide": true,
			"ajax": {
          			url: "<?php echo base_url(); ?>reports/loadvariationdata",
          			type: "GET"
          		  },
			dom: 'Bfrtip',
			lengthMenu: [
	            [ 10, 25, 50, -1 ],
	            [ '10 <?php echo $this->lang->line('rows'); ?>', '25 <?php echo $this->lang->line('rows'); ?>', '50 <?php echo $this->lang->line('rows'); ?>', '<?php echo $this->lang->line('Show all'); ?>' ]
	        ],
	        buttons: [
	            /*'copyHtml5',*/
	            'excelHtml5',
	            /*'csvHtml5',*/
	            //'print',
	            {
		            extend: 'print',
		            text: '<?php echo $this->lang->line('Print'); ?>'
		        },
	            'pageLength'
	        ]
		});

		detailtable=$('#detaildata').DataTable({
			"language": {
                "url": "<?php echo base_url(); ?>assets/datatables2/<?php echo $this->session->userdata('site_lang'); ?>.json",
                buttons: {
		            pageLength: {
		                _: "<?php echo $this->lang->line('Showing'); ?> %d <?php echo $this->lang->line('rows'); ?>",
		                '-1': "<?php echo $this->lang->line('Showing all'); ?>"
		            }
		        }
			},
			createdRow: function( row, data, dataIndex ) {
				$(row).find('td:eq(7)').prop('align', 'right');
			},
			responsive: true,
			"processing": true,
			dom: 'Bfrtip',
			lengthMenu: [
	            [ 10, 25, 50, -1 ],
	            [ '10 <?php echo $this->lang->line('rows'); ?>', '25 <?php echo $this->lang->line('rows'); ?>', '50 <?php echo $this->lang->line('rows'); ?>', '<?php echo $this->lang->line('Show all'); ?>' ]
	        ],
	        buttons: [
	            /*'copyHtml5',*/
	            'excelHtml5',
	            /*'csvHtml5',*/
	            //'print',
	            {
		            extend: 'print',
		            text: '<?php echo $this->lang->line('Print'); ?>'
		        },
	            'pageLength'
	        ]
		});

        $('#variationtable').delegate('.detail', 'click', function(event) {
        	event.preventDefault();
        	waitingDialog.show('Wait a moment...');
        	$('#paneldetail').hide('slow');
        	$.post('<?php echo base_url(); ?>reports/getdetailvariation', {itemcode: $(this).data('item')}, function(data) {
        		$('html, body').animate({
                    scrollTop: $("#paneldetail").parent().offset().top
	            }, 2000);
	        	$('#paneldetail').show('slow');
        		var obj=JSON.parse(data);
        		detailtable.clear().draw();
        		$.each(obj, function(index, val) {
        			detailtable.row.add( [
								            val.datetimecaptured,
								            val.tag,
								            val.areadesc,
								            val.warehouse,
								            val.location,
											val.itemcode,
											val.status,
											Number(val.qty).formatMoney(<?php echo $decimals; ?>),
											val.description,
											val.uom,
											val.type
								        ] ).draw().node();
        		});
        		waitingDialog.hide();
        	});
        });
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
	});
</script>
</html>
