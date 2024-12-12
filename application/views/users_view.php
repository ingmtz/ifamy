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
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/notifications/pnotify.min.js"></script>

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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"></span><?php echo $this->lang->line('Users'); ?></h4>
						</div>

					</div>

				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<!-- Basic datatable -->
					<div class="panel panel-flat">
						<div class="panel-heading">
							<div class="row">
								<div class="col-md-6">
									<h5 class="panel-title"><a href="<?php echo base_url(); ?>users/newuser">New User</a></h5>
								</div>
								<div class="col-md-6">
									<button type="button" id="capture" class="btn btn-primary print" data-id="supervisor">Print supervisor code <i class="icon-printer position-right"></i></button>
								</div>
							</div>
						</div>
						<table class="table datatable-show-all">
							<thead>
								<tr>
									<th><?php echo $this->lang->line('Name'); ?></th>
									<th><?php echo $this->lang->line('Username'); ?></th>
									<th><?php echo $this->lang->line('Profile'); ?></th>
									<th class="text-center"><?php echo $this->lang->line('Options'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($users as $key => $value) { ?>
								<tr>
									<td><?php echo $value->name; ?></td>
									<td><?php echo $value->username; ?></td>
									<td><?php echo $value->profile; ?></td>
									<td class="text-center">
										<a href="#" class="print" title="Print" data-id="<?php echo $value->Id; ?>">
										<i class="icon-printer"></i>
										</a>
										<a href="<?php echo base_url();?>users/useredit/<?php echo $value->Id; ?>" title="Edit">
										<i class="icon-pencil"></i>
										</a>
										<a href="#" class="delete" title="Delete" data-id="<?php echo $value->Id; ?>">
										<i class="icon-cross"></i>
										</a>
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
	<!-- Modal -->
	<div class="modal fade" id="modalprinter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel"><?php echo $this->config->item('title'); ?></h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	      	<div class="row">
	      		<div class="col-md-3">
	      			Printer:
	      		</div>
	      		<div class="col-md-4">
	      			<input type="text" id="fprinter" class="form-control"/>
	      		</div>
	      	</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary" id="btnprint">Print</button>
	      </div>
	    </div>
	  </div>
	</div>

</body>
<script type="text/javascript">
	var selecteduserid=0;
	var datatable;
	$(document).ready(function() {
        datatable=$('.datatable-show-all').DataTable({
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
        	if(confirm('¿Are you sure?')){
        		$.post('<?php echo base_url();?>users/delete', {id: user.data('id')}, function(data) {
        			user.parents('tr').remove();
        		});
        	}
        });
        $(document).delegate('.print', 'click', function(event) {
        	event.preventDefault();
        	var user=$(this);
        	selecteduserid=user.data('id');
        	$('#modalprinter').modal('show');
        	$('#fprinter').val("MEXSAINTPD4255");
        	setTimeout(function(){
        		$('#fprinter').focus();
        	},1500);
        });
        $('#btnprint').click(function(event) {
        	$('#modalprinter').modal('hide');
        	$.post('<?php echo base_url();?>users/printusers', {printer: $('#fprinter').val(),id: selecteduserid}, function(data) {
        		if(data=='ok'){
					new PNotify({
					            title: 'Success',
					            text: 'Tags printed',
					            addclass: 'bg-success'
					        });
					//datatable.ajax.reload();
				}else{
					new PNotify({
			            title: 'Problem',
			            text: data,
			            addclass: 'bg-danger'
			        });
				}
    		});
        });
	});
</script>
</html>
