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

	<script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/treemap.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

	<script src="<?php echo base_url(); ?>assets/jquery.redirect.js"></script>
	
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/app.js"></script>
	<!-- /theme JS files -->

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
							<div class="row">
								<div class="col-md-6">
									<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"><?php echo $this->lang->line('Variation report by area'); ?></span> </h4>
								</div>
								<div class="col-md-6">
									<h4> <span id="totalVariation" class="text-semibold text-right">Total: </span></h4>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<!-- Page length options -->
					<div class="panel panel-flat ">
                        <div id="container"></div>
					</div>
					<div>
						<div id="paneldetailitems" class="panel panel-flat" style="display:none;">
							<?php if ($this->config->item('client') != 'AtlanticPrime') { ?>
								<table id="variationtable" class="table" style="width: 100% !important">
									<thead>
										<tr id="variationtableheader">
											
										</tr>
										<tr>
											<th><?php echo $this->lang->line('Item code'); ?></th>
											<th><?php echo $this->lang->line('Type'); ?></th>
											<th><?php echo $this->lang->line('Description'); ?></th>
											<th><?php echo $this->lang->line('Quantity'); ?></th>
											<th><?php echo $this->lang->line('Unit price'); ?> <?php if ($this->config->item('client') == 'CVG') { ?>USD<?php } else { ?>MXN<?php } ?></th>
											<th><?php echo $this->lang->line('Extended'); ?> <?php if ($this->config->item('client') == 'CVG') { ?>USD<?php } else { ?>MXN<?php } ?></th>
											<th><?php echo $this->lang->line('Quantity'); ?></th>
											<th><?php echo $this->lang->line('Unit price'); ?> <?php if ($this->config->item('client') == 'CVG') { ?>USD<?php } else { ?>MXN<?php } ?></th>
											<th><?php echo $this->lang->line('Extended'); ?> <?php if ($this->config->item('client') == 'CVG') { ?>USD<?php } else { ?>MXN<?php } ?></th>
											<th><?php echo $this->lang->line('Quantity'); ?></th>
											<th><?php echo $this->lang->line('Unit price'); ?> <?php if ($this->config->item('client') == 'CVG') { ?>USD<?php } else { ?>MXN<?php } ?></th>
											<th><?php echo $this->lang->line('Extended'); ?> <?php if ($this->config->item('client') == 'CVG') { ?>USD<?php } else { ?>MXN<?php } ?></th>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot>
										<tr id="variationtablefooter">
											
										</tr>
									</tfoot>
								</table>
							<?php } else { ?>
								<table id="variationtable" class="table"  style="width: 100% !important">
									<thead>
										<tr id="variationtableheader">
											
										</tr>
										<tr>
											<th><?php echo $this->lang->line('Item code'); ?></th>
											<th><?php echo $this->lang->line('Description'); ?></th>
											<th><?php echo $this->lang->line('Unit price'); ?> <?php if ($this->config->item('client') == 'CVG') { ?>USD<?php } else { ?>MXN<?php } ?></th>
											<th><?php echo $this->lang->line('Quantity'); ?></th>
											<th><?php echo $this->lang->line('Extended'); ?> <?php if ($this->config->item('client') == 'CVG') { ?>USD<?php } else { ?>MXN<?php } ?></th>
											<th><?php echo $this->lang->line('Quantity'); ?></th>
											<th><?php echo $this->lang->line('Extended'); ?> <?php if ($this->config->item('client') == 'CVG') { ?>USD<?php } else { ?>MXN<?php } ?></th>
											<th><?php echo $this->lang->line('Quantity'); ?></th>
											<th><?php echo $this->lang->line('Extended'); ?> <?php if ($this->config->item('client') == 'CVG') { ?>USD<?php } else { ?>MXN<?php } ?></th>
											<th>ABS Qty</th>
											<th>ABS Extended</th>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot>
										<tr id="variationtablefooter">
											
										</tr>
									</tfoot>
								</table>
							<?php } ?>
						</div>
					</div>
					<div>
						<div id="paneldetail" class="panel panel-flat" style="display:none;">
							<div class="panel-body">
								<div class="row" style="margin-bottom:15px;">
									<span><b><?php echo $this->lang->line('Detail data'); ?>:</b></span>
								</div>
							</div>

							<table class="table datatable-show-all" id="detaildata" style="width: 100% !important">
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
	let datatable = null;
	let plantselected = '';
	let areaselected = '';
	$(document).ready(function($) {
		let detailtable = $('#detaildata').DataTable({
			"language": {
				"url": "<?php echo base_url(); ?>assets/datatables2/<?php echo $this->session->userdata('site_lang'); ?>.json",
				buttons: {
					pageLength: {
						_: "<?php echo $this->lang->line('Showing'); ?> %d <?php echo $this->lang->line('rows'); ?>",
						'-1': "<?php echo $this->lang->line('Showing all'); ?>"
					}
				}
			},
			createdRow: function(row, data, dataIndex) {
				$(row).find('td:eq(7)').prop('align', 'right');
			},
			responsive: true,
			"processing": true,
			dom: 'Bfrtip',
			lengthMenu: [
				[10, 25, 50, -1],
				['10 <?php echo $this->lang->line('rows'); ?>', '25 <?php echo $this->lang->line('rows'); ?>', '50 <?php echo $this->lang->line('rows'); ?>', '<?php echo $this->lang->line('Show all'); ?>']
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

		$.post('<?php echo base_url();?>reports/getdatareportvariationarea', {plant:$('#plant').val()}, function(data) {
            let dataForChart = JSON.parse(data);
			let total = 0;
            $.each(dataForChart, function( index, value ) {
                if(value.value){
					total+= Number(value.value);
                    value.value = Math.abs(Number(value.value));
                    if(isNaN(value.value)) value.value = 0;
                }
            });
			setDataChart(dataForChart);
			
			$("#totalVariation").text($("#totalVariation").text()+total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
            $('#container').css('height', innerHeight * .8);
		}).fail(function (xhr, status, error) {
            alert("Something went wrong");
        });

        $(window).resize(function(){
            $('#container').css('height', innerHeight * .8);
        });

		$('#variationtable').delegate('.detail', 'click', function(event) {
			event.preventDefault();
			waitingDialog.show('Wait a moment...');
			$('#paneldetail').hide('slow');
			$.post('<?php echo base_url(); ?>reports/getdetailvariation', {
				itemcode: $(this).data('item')
			}, function(data) {
				$('html, body').animate({
					scrollTop: $("#paneldetail").parent().offset().top
				}, 2000);
				$('#paneldetail').show('slow');
				var obj = JSON.parse(data);
				detailtable.clear().draw();
				$.each(obj, function(index, val) {
					detailtable.row.add([
						val.datetimecaptured,
						val.tag,
						val.areadesc,
						val.warehouse,
						val.location,
						val.itemcode,
						val.status,
						Number(val.qty).formatMoney(2),
						val.description,
						val.uom,
						val.type
					]).draw().node();
				});
				waitingDialog.hide();
			});
		});

	});
	

	

    function setDataChart(data){
        Highcharts.chart('container', {
            series: [{
                type: 'treemap',
                layoutAlgorithm: 'stripes',
                alternateStartingDirection: true,
                borderColor: '#fff',
                borderRadius: 6,
                borderWidth: 2,
                dataLabels: {
                    style: {
                        textOutline: 'none',
                        
                        fontSize: '35px',
                    }
                },
                levels: [{
                    level: 1,
                    layoutAlgorithm: 'sliceAndDice',
                    dataLabels: {
                        enabled: true,
                        align: 'left',
                        verticalAlign: 'top',
                        style: {
                            fontSize: '25px',
                            fontWeight: 'bold'
                        }
                    }
                }],
				events: {
					click: function (event) {

						let selected = event.point.parent.split('|');

						waitingDialog.show('Wait a moment...');
						plantselected = selected[0];
						areaselected = selected[1];
						$('#paneldetail').hide('slow');
						$('#paneldetailitems').hide('slow');
						$.post('<?php echo base_url(); ?>reports/gettotalsvariation', {
							plant: plantselected,
							area: areaselected
						}, function(data) {
							let obj =  JSON.parse(data);
							$('#variationtableheader').html(obj.header);
							$('#variationtablefooter').html(obj.footer);
							loadDatatable();
							$('html, body').animate({
								scrollTop: $("#paneldetailitems").parent().offset().top
							}, 2000);
							$('#paneldetailitems').show('slow');
							waitingDialog.hide();
						});
						
					
					}
				},
                data: data
            }],
            title: {
                text: '',
                align: 'left'
            },
            tooltip: {
                useHTML: true,
                style: {
                            fontSize: '25px',
                            fontWeight: 'bold'
                        },
                pointFormat:
                    'The variation is <b>{point.name}</b>'
            }
        });
    }

	function loadDatatable(){
		if(datatable == null){

			datatable = $('#variationtable').DataTable({
				"language": {
					"url": "<?php echo base_url(); ?>assets/datatables2/<?php echo $this->session->userdata('site_lang'); ?>.json",
					buttons: {
						pageLength: {
							_: "<?php echo $this->lang->line('Showing'); ?> %d <?php echo $this->lang->line('rows'); ?>",
							'-1': "<?php echo $this->lang->line('Showing all'); ?>"
						}
					}
				},
				createdRow: function(row, data, dataIndex) {
					// Set the data-status attribute, and add a class
					//$( row ).find('td:eq(2)').prop('align', 'right');
					$(row).find('td').slice(3, 12).prop('align', 'right');
				},
				responsive: false, //true
				"processing": true,
				"serverSide": true,
				"ajax": {
					url: "<?php echo base_url(); ?>reports/loadvariationdata",
					type: "GET",
					data: function ( d ) {
						return $.extend( {}, d, {
							"plant": plantselected,
							"area": areaselected
						});
					}
				},
				dom: 'Bfrtip',
				lengthMenu: [
					[10, 25, 50, -1],
					['10 <?php echo $this->lang->line('rows'); ?>', '25 <?php echo $this->lang->line('rows'); ?>', '50 <?php echo $this->lang->line('rows'); ?>', '<?php echo $this->lang->line('Show all'); ?>']
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
		}else{
			datatable.ajax.reload();
		}
	}


	var waitingDialog = (function($) {
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
				show: function(message, options) {
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
				hide: function() {
					$dialog.modal('hide');
				}
			}
		})(jQuery);
</script>
</html>
