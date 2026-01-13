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
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/app.js"></script>
	<!-- /theme JS files -->
	<style type="text/css">
		@media (min-width: 1000px) {
			.modal-xl {
				width:1000px
			}
		}
		.variance-positive {
			color: #4CAF50;
			font-weight: bold;
		}
		.variance-negative {
			color: #F44336;
			font-weight: bold;
		}
		#typerawvariationtable tfoot {
			font-weight: bold;
			background-color: #f9f9f9;
			border-top: 2px solid #ddd;
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"><?php echo $title; ?></span></h4>
						</div>
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<!-- Page length options -->
					<div class="panel panel-flat">
						<div class="panel-body">
							<table class="table table-striped table-bordered" id="typerawvariationtable">
								<thead>
									<tr>
										<th>Type Raw</th>
										<th>Value</th>
										<th>Counted</th>
										<th>Variance</th>
									</tr>
								</thead>
								<tbody>
									<?php 
										$totalValue = 0;
										$totalCounted = 0;
										foreach($typerawdata as $row): 
											$totalValue += $row->value;
											$totalCounted += $row->counted;
									?>
									<tr>
										<td><?php echo $row->type_raw; ?></td>
										<td>$<?php echo number_format($row->value, 2, '.', ','); ?></td>
										<td>$<?php echo number_format($row->counted, 2, '.', ','); ?></td>
										<?php 
											$variance = $row->counted - $row->value;
											$class = $variance >= 0 ? 'variance-positive' : 'variance-negative';
										?>
										<td class="<?php echo $class; ?>">$<?php echo number_format($variance, 2, '.', ','); ?></td>
									</tr>
									<?php endforeach; 
										$totalVariance = $totalCounted - $totalValue;
										$totalClass = $totalVariance >= 0 ? 'variance-positive' : 'variance-negative';
									?>
								</tbody>
								<tfoot>
									<tr>
										<td>TOTAL</td>
										<td>$<?php echo number_format($totalValue, 2, '.', ','); ?></td>
										<td>$<?php echo number_format($totalCounted, 2, '.', ','); ?></td>
										<td class="<?php echo $totalClass; ?>">$<?php echo number_format($totalVariance, 2, '.', ','); ?></td>
									</tr>
								</tfoot>
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
</html>
