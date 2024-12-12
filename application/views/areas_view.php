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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"></span><?php echo $this->lang->line('Areas'); ?></h4>
						</div>

					</div>

				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<!-- Basic datatable -->
					<div class="panel panel-flat">
						<div class="panel-heading">
							<!--<h5 class="panel-title"><a href="<?php echo base_url(); ?>areas/newarea">New Area</a></h5>-->
						</div>
						<table class="table datatable-show-all">
							<thead>
								<tr>
									<th><?php echo $this->lang->line('Orden'); ?></th>
									<th><?php echo $this->lang->line('Plant'); ?></th>
									<th><?php echo $this->lang->line('Area'); ?></th>
									<!--<th>Warehouse</th>
									<th>Location</th>-->
									<th class="text-center"><?php echo $this->lang->line('Edit'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($areas as $key => $value) { ?>
								<tr>
									<td><?php echo $value->orden; ?></td>
									<td><?php echo $value->plant; ?></td>
									<td><?php echo $value->area; ?></td>
									<!--<td><?php echo $value->warehouse; ?></td>
									<td><?php echo $value->location; ?></td>-->
									<td class="text-center">
										<a href="<?php echo base_url();?>areas/areaedit/<?php echo $value->Id; ?>">
										<i class="icon-pencil"></i>
										</a>
										<!--<a href="#" class="delete" title="Delete" data-id="<?php echo $value->Id; ?>">
										<i class="icon-cross"></i></a>-->
									</td>
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
	$(document).ready(function() {
        var datatable=$('.datatable-show-all').DataTable({
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
        $(document).delegate('.delete', 'click', function(event) {
        	event.preventDefault();
        	var user=$(this);
        	alert('Intentionally disabled');
        	/*if(confirm('¿Are you sure?')){
        		$.post('<?php echo base_url();?>areas/delete', {id: user.data('id')}, function(data) {
        			user.parents('tr').remove();
        		});
        	}*/
        });
	});
</script>
</html>
