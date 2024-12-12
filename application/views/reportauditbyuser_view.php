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
	<script src="<?php echo base_url(); ?>assets/jquery.redirect.js"></script>

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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"></span>Audit by user report</h4>
						</div>

					</div>

				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<!-- Basic datatable -->
					<div class="panel panel-flat">
						<table id="scannedbyusertable" class="table">
							<thead>
								<tr>
									<th>#</th>
									<th>User</th>
									<th>User profile</th>
									<th>Audit tags</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($users as $key => $value) { ?>
									<tr>
										<td><?php echo $key+1; ?></td>
										<td><?php echo $value->name; ?></td>
										<td><?php echo $value->profile; ?></td>
										<td><?php echo $value->cuantos; ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
					<!-- /basic datatable -->
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
	var datatable, detailtable;
	$(document).ready(function() {
		datatable=$('#scannedbyusertable').DataTable( {
          "order": [[ 3, "desc" ]],
          "dom": 'T<"clear">lfrtip',
          "tableTools": {
              "sSwfPath": "<?php echo base_url(); ?>assets/datatable/swf/copy_csv_xls_pdf.swf"
          }
        });
        /*detailtable=$('#detaildata').DataTable( {
		          "order": [[ 0, "desc" ]],
		          "dom": 'T<"clear">lfrtip',
		          "tableTools": {
		              "sSwfPath": "<?php echo base_url(); ?>assets/datatable/swf/copy_csv_xls_pdf.swf"
		          }
		        });*/

        //Add button to download
		$('.DTTT_container').append('<a class="DTTT_button DTTT_button_print" id="downloadexcel" title="Download Excel" tabindex="0" aria-controls="history"><span>Export</span></a>');
		$('#downloadexcel').click(function(event) {
			var tabledata=[];
			$('#scannedbyusertable tr').each(function(i, el) {
				var row=[];
				$(this).find('th').each(function(i, el) {
					row.push($(el).html());
					//Mark Colspan
					if($(el).prop('colspan')>1){
						for (var i = 1; i <$(el).prop('colspan'); i++) {
							row.push('-colspan-');
						}
					}
				});
				$(this).find('td').each(function(i, el) {
					$cell=$(el).html();
					if($cell.indexOf('<a href')>=0){
						$cell=$(el).find('a').html();
					}
					row.push($cell);
				});
				tabledata.push(row);
			});
			tabledata=JSON.stringify(tabledata);
			$.redirect('<?php echo base_url();?>reports/exportexcel', {tabledata:tabledata, title:'Scanned by user report'},'POST');
		});
		//End button export--------------->

        $('#scannedbyusertable').delegate('.detail', 'click', function(event) {
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
											Number(val.qty).formatMoney(),
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
