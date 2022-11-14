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
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/loaders/pace.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/libraries/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/loaders/blockui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/notifications/pnotify.min.js"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"></span><?php echo $this->lang->line('Audit'); ?></h4>
						</div>

					</div>

				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<!-- Basic datatable -->
					<div class="panel panel-flat">
						<div class="panel-body">
							<h5 class="panel-title" style="margin-bottom:15px;">
								All tags are auditable</h5>
							<!--
							<h5 class="panel-title" style="margin-bottom:15px;">Select tags for audit</h5>
							<div class="row" style="margin-bottom:15px;">
									<span><b>Capture the follow information to mark tags for audit:</b></span>
							</div>
							<div class="row" style="margin-bottom:15px;">
								<div class="col-md-2 form-inline">
									<b>Start tag number</b>
								</div>
								<div class="col-md-2 form-inline">
									<b>Final tag number</b>
								</div>
							</div>
							<div class="row" style="margin-bottom:35px;">
							<form id="frmtoaudit">
								<div class="col-md-2 form-inline">
									<input type="text" class="form-control" id="starttag" maxlength="5" name="starttag" placeholder="Start tag"/>
								</div>
								<div class="col-md-2 form-inline">
									<input type="text" class="form-control" id="finaltag" maxlength="5" name="finaltag" placeholder="Final tag" />
								</div>
								<div class="col-md-2 form-inline">
									<button type="button" id="toaudit" class="btn btn-primary">To audit <i class="icon-arrow-right14 position-right"></i></button>
								</div>
							</form>
							</div>
							<div class="row"><hr /></div>
							<div class="row" style="margin-bottom:15px;">
									<span><b>You can add tags separated by comma, example: 00001,00002</b></span>
							</div>
							<div class="row" style="margin-bottom:15px;">
								<div class="col-md-10 form-inline">
									<input type="text" class="form-control" name="bycoma" id="bycoma" style="width: 100%">
								</div>
								<div class="col-md-2 form-inline">
									<button type="button" id="btnbycoma" class="btn btn-primary">To audit <i class="icon-arrow-right14 position-right"></i></button>
								</div>
							</div>
							
							<div class="row"><hr /></div>
							<div class="row" style="margin-bottom:35px;">
								<div class="col-md-2 form-inline">
									<button type="button" id="clearaudit" class="btn btn-primary">Clear all audit tags <i class="icon-arrow-right14 position-right"></i></button>
								</div>
							</div>
						-->

							<div class="row">
								<hr />
							</div>
							<div class="row" style="margin-bottom:15px;">
								<span><b><?php echo $this->lang->line('Routines'); ?></b></span>
							</div>
							<div class="row" style="margin-bottom:15px;">
								<div class="col-md-2 form-inline">
									<button type="button" id="routinesopenbox" class="btn btn-primary routines"><?php echo $this->lang->line('Open box tags'); ?> <i class="glyphicon glyphicon-download-alt position-right"></i></button>
								</div>
								<?php if ($this->config->item('client') == 'AtlanticPrime') { ?>
									<div class="col-md-2 form-inline">
										<button type="button" id="routinesprecounted" class="btn btn-primary routines"><?php echo $this->lang->line('Precounted tags'); ?> <i class="glyphicon glyphicon-download-alt position-right"></i></button>
									</div>
								<?php } ?>
								<div class="col-md-3 form-inline">
									<?php echo $this->lang->line('Less than'); ?> $ <?php if ($this->config->item('client') == 'CVG') { ?>USD <?php } else { ?>MXN<?php } ?>:
									<input type="number" class="form-control" id="lessthan" value="-5000" style="width: 80px;">
									<button type="button" id="routineslessthan" class="btn btn-primary routines">
										< <?php echo $this->lang->line('Download'); ?> <i class="glyphicon glyphicon-download-alt position-right"></i>
									</button>
								</div>
								<div class="col-md-4 form-inline">
									<?php echo $this->lang->line('Greater than'); ?> $ <?php if ($this->config->item('client') == 'CVG') { ?>USD <?php } else { ?>MXN<?php } ?>:
									<input type="number" class="form-control" id="greaterthan" value="5000" style="width: 80px;">
									<button type="button" id="routinesgreaterthan" class="btn btn-primary routines">> <?php echo $this->lang->line('Download'); ?> <i class="glyphicon glyphicon-download-alt position-right"></i></button>
								</div>
								<?php //if($this->config->item('client')=='CVG'){ 
								?>
								<div class="col-md-3 form-inline">
									<button type="button" id="routinesall" class="btn btn-primary routines">Download all <i class="glyphicon glyphicon-download-alt position-right"></i></button>
								</div>
								<?php //} 
								?>
							</div>

							<div class="row">
								<hr />
							</div>
							<table class="table datatable-show-all" id="tagstable">
								<thead>
									<tr>
										<th><?php echo $this->lang->line('Tag'); ?></th>
										<th><?php echo $this->lang->line('Area'); ?></th>
										<th><?php echo $this->lang->line('Plant'); ?></th>
										<th><?php echo $this->lang->line('Print counter'); ?></th>
										<th><?php echo $this->lang->line('Open box'); ?></th>
										<th><?php echo $this->lang->line('Quantity'); ?></th>
										<th><?php echo $this->lang->line('Unit extended'); ?></th>
										<th><?php echo $this->lang->line('Audit result'); ?></th>
										<th><?php echo $this->lang->line('Audit user'); ?></th>
										<th><?php echo $this->lang->line('Audit datetime'); ?></th>
										<th><?php echo $this->lang->line('Audit counter'); ?></th>
										<th><?php echo $this->lang->line('Precounted'); ?></th>
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
	$(document).ready(function() {
		$('#toaudit').click(function(event) {
			if ($('#starttag').val().length != 5 || $('#finaltag').val().length != 5 || isNaN($('#starttag').val()) || isNaN($('#finaltag').val()) || $('#finaltag').val() == '' || $('#starttag').val() == '') {
				new PNotify({
					title: 'Problem',
					text: 'Invalid tags range',
					addclass: 'bg-danger'
				});
				return;
			}
			$.post('<?php echo base_url(); ?>tags/marktagstoaudit', {
				data: $('#frmtoaudit').serialize()
			}, function(data) {
				if (data == 'ok') {
					new PNotify({
						title: 'Success',
						text: 'Tags added',
						addclass: 'bg-success'
					});
					datatable.ajax.reload();
				} else {
					new PNotify({
						title: 'Problem',
						text: data,
						addclass: 'bg-danger'
					});
				}
			});
		});
		$('#clearaudit').click(function(event) {
			if (confirm('Are you sure?')) {
				$.post('<?php echo base_url(); ?>tags/clearaudit', {}, function(data) {
					new PNotify({
						title: 'Success',
						text: 'Audit tags cleared',
						addclass: 'bg-success'
					});
					datatable.ajax.reload();
				});
			}
		});

		datatable = $('#tagstable').DataTable({
			"language": {
				"url": "<?php echo base_url(); ?>assets/datatables2/<?php echo $this->session->userdata('site_lang'); ?>.json",
				buttons: {
					pageLength: {
						_: "<?php echo $this->lang->line('Showing'); ?> %d <?php echo $this->lang->line('rows'); ?>",
						'-1': "<?php echo $this->lang->line('Showing all'); ?>"
					}
				}
			},
			responsive: true,
			"processing": true,
			"serverSide": true,
			"ajax": {
				url: "<?php echo base_url(); ?>tags/loadtagstoaudit",
				type: "GET"
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
		//End button export--------------->
		$('#bycoma').keydown(function(e) {
			if (e.keyCode == 13 || e.keyCode == 9) {
				$('#btnbycoma').click();
			}
		});
		$('#btnbycoma').click(function(event) {
			/*var arrtemp=$('#bycoma').val().split(',');
			$.each(arrtemp, function(index, val) {
				alert(val);
			});*/
			$.post('<?php echo base_url(); ?>tags/bycoma', {
				text: $('#bycoma').val()
			}, function(data) {
				$('#bycoma').val('');
				datatable.ajax.reload();
			});
		});
		//Routines
		$('.routines').click(function(event) {
			var type = $(this).prop('id');
			var lessthan = $('#lessthan').val();
			var greaterthan = $('#greaterthan').val();

			if ((type == 'routinesgreaterthan' || type == 'routinesall') && $('#greaterthan').val().length == 0) {
				new PNotify({
					title: 'Problem',
					text: 'Type a valid quantity',
					addclass: 'bg-danger'
				});
				return;
			}
			if ((type == 'routineslessthan' || type == 'routinesall') && $('#lessthan').val().length == 0) {
				new PNotify({
					title: 'Problem',
					text: 'Type a valid quantity',
					addclass: 'bg-danger'
				});
				return;
			}
			$.redirect('<?php echo base_url(); ?>tags/routines', {
				type: type,
				lessthan: lessthan,
				greaterthan: greaterthan
			}, 'POST');
		});
	});
</script>

</html>