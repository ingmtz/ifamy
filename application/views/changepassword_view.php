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
	<!--<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/tables/datatables/datatables.min.js"></script>-->
	<link href="<?php echo base_url(); ?>assets/datatable/css/bootstrap.datatable.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/datatable/css/dataTables.tableTools.min.css" rel="stylesheet">
    <script src="<?php echo base_url(); ?>assets/datatable/js/jquery.dataTables.js"></script>
	<script src="<?php echo base_url(); ?>assets/datatable/js/dataTables.tableTools.js"></script>

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
				<!-- Content area -->
				<div class="content">

					<!-- Basic datatable -->
					<div class="panel panel-flat">
						<div class="row">
		                    <div class="col-md-6 col-md-offset-3">
		                        <h4 class="page-title">You need change your password</h4>
		                        <form id="frmpassword">
		                            <div class="row">
		                                <div class="col-xs-12 col-sm-6">
		                                    <div class="form-group">
		                                        <label>New password</label>
		                                        <input type="password" id="password" class="form-control">
		                                    </div>
		                                </div>
		                                <div class="col-xs-12  col-sm-6">
		                                    <div class="form-group">
		                                        <label>Confirm password</label>
		                                        <input type="password" id="confirmpassword" class="form-control">
		                                    </div>
		                                </div>
		                            </div>
		                            <div class="row" id="lblpassword" style="display: none;">
		                                <div class="col-md-12">
		                                    <div class="alert alert-danger">
		                                        <strong>Warning!</strong> Passwords do not match.
		                                    </div>
		                                </div>
		                            </div>
		                            <div class="row">
		                                <div class="col-sm-12 text-center m-t-20">
		                                    <button type="submit" class="btn btn-primary btn-lg">Update Password</button>
		                                </div>
		                            </div>
		                        </form>
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
    $(document).ready(function(){
        $('#password').focus();
        $('#frmpassword').submit(function(event) {
            event.preventDefault();
            if($('#password').val()=='' || $('#password').val()!=$('#confirmpassword').val()){
                $('#lblpassword').show();
            }else{
                $('#lblpassword').hide();
                $.post('<?php echo base_url(); ?>changepassword/update', {password: $('#password').val()}, function(data) {
                    window.location.href='<?php echo base_url();?>capture';
                });
            }
        })            
    });
</script>
</html>
