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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"></span><?php echo $this->lang->line('Tags'); ?></h4>
						</div>

					</div>

				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<!-- Basic datatable -->
					<div class="panel panel-flat">
						<div class="panel-body">
							<h5 class="panel-title" style="margin-bottom:15px;"><?php echo $this->lang->line('New tag assignation'); ?></a></h5>
							<div class="row" style="margin-bottom:15px;">
									<span><b><?php echo $this->lang->line('Capture the follow information to create new tags or reassign them'); ?>:</b></span>
							</div>
							<div class="row" style="margin-bottom:15px;">
								<div class="col-md-2 form-inline">
									<b><?php echo $this->lang->line('Start tag number'); ?></b>
								</div>
								<div class="col-md-2 form-inline">
									<b><?php echo $this->lang->line('Final tag number'); ?></b>
								</div>
								<div class="col-md-3 form-inline">
									<b><?php echo $this->lang->line('Area'); ?></b>
								</div>
								<div class="col-md-2 form-inline">
									<b><?php echo $this->config->item('client')=='CVG' ? 'WH': $this->lang->line('Rack'); ?></b>
								</div>
								<div class="col-md-2 form-inline">
									<b><?php echo $this->lang->line('Plant'); ?></b>
								</div>
							</div>
							<div class="row" style="margin-bottom:35px;">
							<form id="frmnewtag">
								<div class="col-md-2 form-inline">
									<input type="text" class="form-control" id="starttag" name="starttag" placeholder="<?php echo $this->lang->line('Start tag number'); ?>"/>
								</div>
								<div class="col-md-2 form-inline">
									<input type="text" class="form-control" id="finaltag" name="finaltag" placeholder="<?php echo $this->lang->line('Final tag number'); ?>" />
								</div>
								<div class="col-md-3 form-inline">
									<select class="form-control" id="area" name="area">
										<?php foreach ($areas as $key => $value) { ?>
										<option data-plant="<?php echo $value->plant; ?>" value="<?php echo $value->plant.'|'.$value->area; ?>"><?php echo $value->plant.' - '.$value->area; ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="col-md-2 form-inline">
									<select class="form-control" id="wh" name="wh">
										<option value="0"><?php echo $this->lang->line('Select'); ?></option>
									</select>
								</div>
								<div class="col-md-2 form-inline">
									<input type="text" class="form-control" id="plant" name="plant" placeholder="<?php echo $this->lang->line('Plant'); ?>" readonly="" />
								</div>
								<div class="col-md-1 form-inline">
									<button type="button" id="create" class="btn btn-primary"><?php echo $this->lang->line('Create'); ?> <i class="icon-arrow-right14 position-right"></i></button>
								</div>
							</form>
							</div>
							<div class="row"><hr /></div>
							<!-- -->
							<h5 class="panel-title" style="margin-bottom:15px;"><?php echo $this->lang->line('Print tags'); ?></a></h5>
							<div class="row" style="margin-bottom:15px;">
									<span><b><?php echo $this->lang->line('Capture the follow information to print tags'); ?>:</b></span>
							</div>
							<div class="row" style="margin-bottom:15px;">
								<div class="col-md-2 form-inline">
									<b><?php echo $this->lang->line('Start tag number'); ?></b>
								</div>
								<div class="col-md-2 form-inline">
									<b><?php echo $this->lang->line('Final tag number'); ?></b>
								</div>
								<?php if($this->config->item('client')=='CVG'){ ?>
								<div class="col-md-2 form-inline">
									<b><?php echo $this->lang->line('Printer'); ?></b>
								</div>
								<?php } ?>
							</div>
							<div class="row" style="margin-bottom:35px;">
							<form id="frmprint">
								<div class="col-md-2 form-inline">
									<input type="text" class="form-control" id="starttagprint" name="starttagprint" placeholder="<?php echo $this->lang->line('Start tag number'); ?>"/>
								</div>
								<div class="col-md-2 form-inline">
									<input type="text" class="form-control" id="finaltagprint" name="finaltagprint" placeholder="<?php echo $this->lang->line('Final tag number'); ?>" />
								</div>
								<?php if($this->config->item('client')=='CVG'){ ?>
								<div class="col-md-2 form-inline">
									<input type="text" class="form-control" id="printer" name="printer" placeholder="<?php echo $this->lang->line('Printer'); ?>" value="10.191.1.55" />
								</div>
								<?php } ?>
								<div class="col-md-2 form-inline">
									<button type="button" id="print" class="btn btn-primary"><?php echo $this->lang->line('Print'); ?> <i class="icon-arrow-right14 position-right"></i></button>
								</div>
							</form>
							</div>
							<div class="row"><hr /></div>
							<!-- -->


							<h5 class="panel-title" style="margin-bottom:15px;"><?php echo $this->lang->line('Capture tags by range'); ?></a></h5>
							<div class="row" style="margin-bottom:15px;">
									<span><b><?php echo $this->lang->line('Capture the follow information to capture status tags'); ?>:</b></span>
							</div>
							<div class="row" style="margin-bottom:15px;">
								<div class="col-md-2 form-inline">
									<b><?php echo $this->lang->line('Start tag number'); ?></b>
								</div>
								<div class="col-md-2 form-inline">
									<b><?php echo $this->lang->line('Final tag number'); ?></b>
								</div>
								<div class="col-md-2 form-inline">
									<b><?php echo $this->lang->line('Status'); ?></b>
								</div>
							</div>
							<div class="row" style="margin-bottom:35px;">
							<form id="frmcapture">
								<div class="col-md-2 form-inline">
									<input type="text" class="form-control" id="starttagc" name="starttagc" placeholder="<?php echo $this->lang->line('Start tag number'); ?>"/>
								</div>
								<div class="col-md-2 form-inline">
									<input type="text" class="form-control" id="finaltagc" name="finaltagc" placeholder="<?php echo $this->lang->line('Final tag number'); ?>" />
								</div>
								<div class="col-md-2 form-inline">
									<select class="form-control" id="status" name="status">
										<option value="Blank"><?php echo $this->lang->line('Blank'); ?></option>
										<option value="Voided"><?php echo $this->lang->line('Voided'); ?></option>
									</select>
								</div>
								<div class="col-md-2 form-inline">
									<button type="button" id="capture" class="btn btn-primary"><?php echo $this->lang->line('Capture'); ?> <i class="icon-arrow-right14 position-right"></i></button>
								</div>
							</form>
							</div>
							<div class="row"><hr /></div>
							<table class="table" id="tagstable">
								<thead>
									<tr>
										<th><?php echo $this->lang->line('Tag'); ?></th>
										<th><?php echo $this->lang->line('Plant'); ?></th>
										<th><?php echo $this->lang->line('Area'); ?></th>
										<th><?php echo $this->config->item('client')=='CVG' ? 'WH': $this->lang->line('Rack'); ?></th>
										<th><?php echo $this->lang->line('Print counter'); ?></th>
										<th><?php echo $this->lang->line('Status'); ?></th>
										<th><?php echo $this->lang->line('Open box'); ?></th>
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
		$('#area').change(function(event) {
			$('#plant').val($('#area option:selected').data('plant'));
			$.post('<?php echo base_url(); ?>tags/getwhbyarea', {area: $('#area').val()}, function(data) {
				var obj=JSON.parse(data);
				$('#wh').html('<option value="0">Select</option>');
				$.each(obj, function(index, val) {
					$('#wh').append('<option value="'+val.wh+'">'+val.wh+'</option>');
				});
			});
		});
		$('#area').change();

		$('#create').click(function(event) {
			if(isNaN($('#starttag').val()) || isNaN($('#finaltag').val()) || $('#finaltag').val()=='' || $('#starttag').val()=='' || $('#wh').val()=='0'){
				new PNotify({
		            title: 'Problem',
		            text: 'Invalid tags range or incorrect Rack.',
		            addclass: 'bg-danger'
		        });
				return;
			}
			$.post('<?php echo base_url(); ?>tags/checktags', {data: $('#frmnewtag').serialize()}, function(data) {
				if(data.includes('Next')){
					new PNotify({
			            title: 'Problem',
			            text: data,
			            addclass: 'bg-danger'
			        });
					return;
				}
				if(data=='nulls'){
					if(confirm('Some tags in range are asignated, do you want to reassign?')){
						$.post('<?php echo base_url(); ?>tags/reasignatetags', {data: $('#frmnewtag').serialize()}, function(data) {
							new PNotify({
					            title: 'Success',
					            text: 'Tags reasignated',
					            addclass: 'bg-success'
					        });
					        datatable.ajax.reload();
						});
					}
					return;
				}
				if(data=='ok'){
					new PNotify({
					            title: 'Success',
					            text: 'Tags created',
					            addclass: 'bg-success'
					        });
					datatable.ajax.reload();
				}else{
					new PNotify({
			            title: 'Problem',
			            text: 'Tags ocupated',
			            addclass: 'bg-danger'
			        });
				}
			});
		});
		$('#capture').click(function(event) {
			if(isNaN($('#starttagc').val()) || isNaN($('#finaltagc').val()) || $('#finaltagc').val()=='' || $('#starttagc').val()==''){
				new PNotify({
		            title: 'Problem',
		            text: 'Invalid tags range',
		            addclass: 'bg-danger'
		        });
				return;
			}
			
			$.post('<?php echo base_url(); ?>tags/capturetags', {data: $('#frmcapture').serialize()}, function(data) {
				if(data=='ok'){
					new PNotify({
					            title: 'Success',
					            text: 'Tags captured',
					            addclass: 'bg-success'
					        });
					datatable.ajax.reload();
				}else{
					new PNotify({
			            title: 'Problem',
			            text: data,
			            addclass: 'bg-danger'
			        });
				}
			});
		});
		$('#print').click(function(event) {
			if(isNaN($('#starttagprint').val()) || isNaN($('#finaltagprint').val()) || $('#finaltagprint').val()=='' || $('#starttagprint').val()==''){
				new PNotify({
		            title: 'Problem',
		            text: 'Invalid tags range',
		            addclass: 'bg-danger'
		        });
				return;
			}
			<?php if($this->config->item('client')=='CVG'){ ?>
			$.post('<?php echo base_url(); ?>tags/printtags', {data: $('#frmprint').serialize()}, function(data) {
				if(data=='ok'){
					new PNotify({
					            title: 'Success',
					            text: 'Tags printed',
					            addclass: 'bg-success'
					        });
					datatable.ajax.reload();
				}else{
					new PNotify({
			            title: 'Problem',
			            text: data,
			            addclass: 'bg-danger'
			        });
				}
			});
			<?php }else{ ?>
				$.redirect('<?php echo base_url();?>tags/pdftags', {data: $('#frmprint').serialize()},'POST', '_blank');
			<?php } ?>
		});

		datatable=$('#tagstable').DataTable({
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
          			url: "<?php echo base_url(); ?>tags/loaddata",
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
	});
</script>
</html>
