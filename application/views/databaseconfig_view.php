<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Database config</title>

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
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/notifications/bootbox.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/notifications/pnotify.min.js"></script>
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"></span><?php echo $this->lang->line('Database config'); ?></h4>
						</div>

					</div>

				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<!-- Basic datatable -->
					<div class="panel panel-flat">
						<div class="panel-heading">
							<!--<h5 class="panel-title"><a href="<?php echo base_url(); ?>databaseconfig/newdatabase">New Database</a></h5>-->
						</div>
						<div class="row">
							<div class="col-md-3"></div>
							<div class="col-md-3">
								<div class="panel panel-body border-top-primary text-center">
									<h6 class="no-margin text-semibold"><?php echo $this->lang->line('Backup current database'); ?></h6>
									<p class="text-muted content-group-sm"><?php echo $this->lang->line('Back up current information'); ?></p>

			                    	<button type="button" class="btn btn-success btn-rounded" id="createbackup"><i class="icon-database-add position-left"></i> <?php echo $this->lang->line('Backup database'); ?></button>
								</div>
							</div>
							<div class="col-md-3">
								<div class="panel panel-body border-top-primary text-center">
									<h6 class="no-margin text-semibold"><?php echo $this->lang->line('Clean main data'); ?></h6>
									<p class="text-muted content-group-sm"><?php echo $this->lang->line('Remove all except users and scanners'); ?></p>

			                    	<button type="button" class="btn btn-warning btn-rounded" id="cleanmaindata"><i class="icon-database-remove position-left"></i> <?php echo $this->lang->line('Clean main data'); ?></button>
								</div>
							</div>
							<div class="col-md-3">
								<div class="panel panel-body border-top-primary text-center">
									<h6 class="no-margin text-semibold"><?php echo $this->lang->line('Clean current database'); ?></h6>
									<p class="text-muted content-group-sm"><?php echo $this->lang->line('Caution, you will delete all data'); ?></p>

			                    	<button type="button" class="btn btn-danger btn-rounded" id="cleandatabase"><i class="icon-database-remove position-left"></i> <?php echo $this->lang->line('Clean current database'); ?></button>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
						<table id="databasetable" class="table">
							<thead>
								<tr>
									<th><?php echo $this->lang->line('Backup'); ?></th>
									<th><?php echo $this->lang->line('Created'); ?></th>
									<th><?php echo $this->lang->line('Size'); ?></th>
									<th><?php echo $this->lang->line('Restore'); ?></th>
									<th><?php echo $this->lang->line('Delete'); ?></th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
							</div>
						</div>

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
	var datatable;
	$(document).ready(function() {
        $('#createbackup').on('click', function() {
        	bootbox.prompt({
			    title: "<?php echo $this->lang->line('Please enter a name'); ?>",
			    maxlength: "20",
			    callback: function (result) {
			    	if(result === null){
		                //bootbox.alert("Prompt dismissed");
		            }else{
		            	$.post('<?php echo base_url(); ?>databaseconfig/createbackup', {name: result}, function(data) {
		            		new PNotify({
					            title: 'Success',
					            text: 'Correct',
					            addclass: 'bg-success'
					        });
					        listdatabases();
		            	}).fail(function(){
		            		new PNotify({
					            title: 'Problem',
					            text: 'Something was wrong',
					            addclass: 'bg-danger'
					        });
		            	});
		            }
			    }
			});
	    });

	    $('#databasetable').delegate('.restore', 'click', function(event) {
	    	event.preventDefault();
	    	var name=$(this).data('name');

	    	if(confirm('<?php echo $this->lang->line('Are you sure? You will not be able to retrieve the information.'); ?>')){
	    		$.post('<?php echo base_url(); ?>databaseconfig/restorebackup', {name: name}, function(data) {
	    			if(data==''){
		        		new PNotify({
				            title: 'Success',
				            text: 'Correct',
				            addclass: 'bg-success'
				        });
				        setTimeout(function(){
				        	window.location.href='<?php echo base_url(); ?>login/logout';
				        }, 1000);
		        	}else{
		        		new PNotify({
				            title: 'Problem',
				            text: 'Something was wrong',
				            addclass: 'bg-danger'
				        });
		        	}
	        	}).fail(function(){
	        		new PNotify({
			            title: 'Problem',
			            text: 'Something was wrong',
			            addclass: 'bg-danger'
			        });
	        	});
        	}
	    });

	    $('#databasetable').delegate('.remove', 'click', function(event) {
	    	event.preventDefault();
	    	var name=$(this).data('name');

	    	if(confirm('<?php echo $this->lang->line('Are you sure? You will not be able to retrieve the information.'); ?>')){
	    		$.post('<?php echo base_url(); ?>databaseconfig/removebackup', {name: name}, function(data) {
	    			if(data==''){
		        		new PNotify({
				            title: 'Success',
				            text: 'Correct',
				            addclass: 'bg-success'
				        });
				        listdatabases();
		        	}else{
		        		new PNotify({
				            title: 'Problem',
				            text: 'Something was wrong',
				            addclass: 'bg-danger'
				        });
		        	}
	        	}).fail(function(){
	        		new PNotify({
			            title: 'Problem',
			            text: 'Something was wrong',
			            addclass: 'bg-danger'
			        });
	        	});
        	}
	    });

        $('#cleandatabase').click(function(event) {
        	if(confirm('<?php echo $this->lang->line('Are you sure? You will not be able to retrieve the information.'); ?>')){
        		window.location.href='<?php echo base_url(); ?>databaseconfig/cleandatabase';
        	}
        });
        $('#cleanmaindata').click(function(event) {
        	if(confirm('<?php echo $this->lang->line('Are you sure? You will not be able to retrieve the information.'); ?>')){
        		window.location.href='<?php echo base_url(); ?>databaseconfig/cleanmaindata';
        	}
        });

        $(document).delegate('.bootbox-input', 'keyup blur', function(event) {
		    var node = $(this);
		    node.val(node.val().replace(/[^a-z ]/g,'') ); }
		);

		listdatabases();
	});
	function listdatabases(){
		$.post('<?php echo base_url(); ?>databaseconfig/listdatabases', {}, function(data) {
			$('#databasetable tbody').html(data);
    	}).fail(function(){
    		new PNotify({
	            title: 'Problem',
	            text: 'Something was wrong',
	            addclass: 'bg-danger'
	        });
    	});
	}
</script>
</html>
