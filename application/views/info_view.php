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
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dropzone/dropzone.css" />
	<link href="<?php echo base_url(); ?>assets/css/components.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url(); ?>assets/css/colors.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url(); ?>assets/css/icons/fontawesome/styles.min.css" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/loaders/pace.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/libraries/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/loaders/blockui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/forms/wizards/steps.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/forms/styling/uniform.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/libraries/jasny_bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/forms/validation/validate.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/extensions/cookie.js"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	

	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/app.js"></script>
	<!-- /theme JS files -->

	<!--<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/tables/datatables/datatables.min.js"></script>-->
	<link href="<?php echo base_url(); ?>assets/datatable/css/bootstrap.datatable.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/datatable/css/dataTables.tableTools.min.css" rel="stylesheet">
    <script src="<?php echo base_url(); ?>assets/datatable/js/jquery.dataTables.js"></script>
	<script src="<?php echo base_url(); ?>assets/datatable/js/dataTables.tableTools.js"></script>
	<!-- Dropzone -->
	<script src="<?php echo base_url(); ?>assets/dropzone/dropzone.js"></script>


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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"></span><?php echo $this->lang->line('how does it work?'); ?></h4>
						</div>

					</div>

				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<div class="panel panel-white">
						<div class="panel panel-default">
						  <div class="panel-body" style="font-size:18px;">
						  	Hola, gracias por usar nuestro sistema. Para comenzar necesitamos alimentarlo con 3 archivos:<br><br>
						  	<b>1.- Importar archivos</b> <br>
						  	<b>* "Item master"</b> Es el listado de productos involucrados en el conteo. Todo el sistema estará basado en esta información.<br>
						  	<b>* "Perpetuo"</b> Son las existencias que tienen en su sistema. Se usarán para desplegar un reporte de variación contra el conteo.<br>
						  	<b>* "Locaciones"</b> Son las ubicaciones en donde se realizarán los conteos.<br><br>

						  	<b>2.- Ajustes</b> <br>
						  	En la sección de ajustes, elige los prefijos que tendrán los códigos de marbete y producto. Así como el número del primer marbete a generar.<br><br>

						  	<b>3.- Crear y asignar marbetes</b> <br>
						  	En el menú -> Marbetes. Crea rangos de marbetes asignados a locaciones específicas.<br>
						  	Opcionalmente, puedes imprimir las etiquetas desde este mismo módulo.<br><br>

						  	<b>4.- Crear usuarios</b> <br>
						  	En el menú -> Usuarios. Puedes administrar los usuarios que tendrán acceso al sistema y a realizar los conteos en la aplicación, existen 3 tipos de usuarios:<br>
						  	<b>Data capture</b> Solo tienen acceso al módulo de captura de marbetes.<br>
						  	<b>Analyst</b> Acceso a captura y reportes.
						  	<b>Administrator</b> Acceso total al sistema. Se recomienda que solo existan pocos usuarios administradores.<br><br>

						  	<b>5.- Capturar </b> <br>
						  	Desde la aplicación Android o el mismo sistema, puedes comenzar a capturar la información de los marbetes.
						  	


						  </div>
						</div>
		            </div>
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
