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
	<!-- Fin de datatables -->

	<!--<script src="<?php echo base_url(); ?>assets/jquery.jqprint-0.3.js"></script>-->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/chosen/chosen.css" />
	<script src="<?php echo base_url(); ?>assets/chosen/chosen.jquery.js"></script>
	<script src="<?php echo base_url(); ?>assets/jquery.redirect.js"></script>
	<script src="<?php echo base_url(); ?>assets/moment.js"></script>
	
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/app.js"></script>
	<!-- /theme JS files -->
	<style type="text/css">
        /*input{
            text-transform: uppercase;
        }*/
		
		@media (min-width: 1000px) {
			.modal-xl {
				width:1000px
			}
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
							<div class="row" style="margin-bottom:15px;">
								<span><b><?php echo $this->lang->line('Capture the follow information'); ?>:</b></span>
							</div>
							<div class="row">
								<div class="col-md-1 form-inline">
									<b><?php echo $this->lang->line('Status'); ?></b>
								</div>
								<div class="col-md-2 form-inline">
									<b><?php echo $this->lang->line('Tag number'); ?></b>
								</div>
								<div class="col-md-2 form-inline">
									<b><?php echo $this->lang->line('Item code'); ?></b>
								</div>
								<div class="col-md-1 form-inline">
									<b><?php echo $this->lang->line('Quantity'); ?></b>
								</div>
								<div class="col-md-2 form-inline">
									<?php if($this->config->item('client')=='AtlanticPrime'){ ?>
									<b><?php echo $this->lang->line('Rack'); ?></b>
									<?php }else{ ?>
									<b>WH</b>
									<?php } ?>
								</div>
								<div class="col-md-2 form-inline">
									<b><?php echo $this->lang->line('Location'); ?></b>
								</div>
							</div>
							<div class="row" style="margin-bottom:15px;">
								<div class="col-md-1 form-inline">
									<select class="form-control" id="status">
										<option value="Used"><?php echo $this->lang->line('Used'); ?></option>
										<option value="Voided"><?php echo $this->lang->line('Voided'); ?></option>
										<option value="Blank"><?php echo $this->lang->line('Blank'); ?></option>
									</select>
								</div>
								<div class="col-md-2 form-inline">
									<input type="text" class="form-control" id="tag" placeholder="<?php echo $this->lang->line('Tag number'); ?>" />
								</div>
								<div class="col-md-2 form-inline">
									<input type="text" class="form-control" id="itemcode" placeholder="<?php echo $this->lang->line('Item code'); ?>" />
								</div>
								<div class="col-md-1 form-inline">
									<input type="number" style="width:100px" class="form-control" id="qty" placeholder="<?php echo $this->lang->line('Quantity'); ?>" />
								</div>
								<div class="col-md-2 form-inline">
									<select id="warehouse" class="form-control chosen-select">
										<option value="0"><?php echo $this->lang->line('Select'); ?></option>
										<?php foreach ($warehouse as $key => $value) { ?>
										<option value="<?php echo $value->wh; ?>"><?php echo $value->wh; ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="col-md-2 form-inline">
									<select id="location" class="form-control chosen-select" disabled="">
										<option value="0"><?php echo $this->lang->line('Select'); ?></option>
									</select>
								</div>
								<div class="col-md-2 form-inline">
									<button type="button" id="save" class="btn btn-primary"><?php echo $this->lang->line('Save'); ?> <i class="icon-arrow-right14 position-right"></i></button>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2 form-inline">
									<?php echo $this->lang->line('Description'); ?>
								</div>
								<div class="col-md-2 form-inline">
									<?php echo $this->lang->line('Type'); ?>
								</div>
								<div class="col-md-2 form-inline">
									<?php echo $this->lang->line('UOM'); ?>
								</div>
								<div class="col-md-2 form-inline">
									<?php echo $this->lang->line('Area'); ?>
								</div>
								<div class="col-md-2 form-inline">
									<?php echo $this->lang->line('Team Leader'); ?>
								</div>
								<div class="col-md-2 form-inline">
									<?php echo $this->lang->line('Auditor'); ?>
								</div>
							</div>
							<div class="row" style="margin-bottom:15px;">
								<div class="col-md-2 form-inline">
									<input type="text" class="form-control" id="description" readonly="" />
								</div>
								<div class="col-md-2 form-inline">
									<input type="text" class="form-control" id="type" readonly="" />
								</div>
								<div class="col-md-2 form-inline">
									<input type="text" class="form-control" id="uom" readonly="" />
								</div>
								<div class="col-md-2 form-inline">
									<input type="text" class="form-control" id="area" readonly="" />
								</div>
								<div class="col-md-2 form-inline">
									<select class="form-control" id="idcounter">
										<option value="0"><?php echo $this->lang->line('Select'); ?></option>
										<?php foreach ($counters as $key => $value) { ?>
										<option value="<?php echo $value->Id; ?>"><?php echo $value->name; ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="col-md-2 form-inline">
									<select class="form-control" id="idauditor">
										<option value="0"><?php echo $this->lang->line('Select'); ?></option>
										<?php foreach ($auditors as $key => $value) { ?>
										<option value="<?php echo $value->Id; ?>"><?php echo $value->name; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="row" style="margin-bottom:15px;">
								<div class="col-md-2 form-inline">
									<?php echo $this->lang->line('Open box'); ?>: &nbsp;&nbsp;
									<input type="checkbox" class="form-control" id="openbox" style="width: 30px; margin-top: -7px;" />
								</div>
								<?php if($this->config->item('client')=='CVG'){
									if($config->inventorytype=='SEATS'){ ?>
									<div class="col-md-3 form-inline">
										DIMM: &nbsp;&nbsp;
										<input type="text" class="form-control" id="dimm" readonly="" />
									</div>
								<?php }
								} ?>
								<?php if($this->config->item('client')=='AtlanticPrime'){ ?>
								<div class="col-md-2 form-inline">
									<?php echo $this->lang->line('Precounted'); ?>: &nbsp;&nbsp;
									<input type="checkbox" class="form-control" id="precounted" style="width: 30px; margin-top: -7px;" />
								</div>
								<div class="col-md-4 form-inline">
									<?php echo $this->lang->line('Packing date'); ?>: &nbsp;&nbsp;
									<input type="date" class="form-control" id="fechaempaque" style="width: 160px; margin-top: -7px;" />
								</div>
								<div class="col-md-4 form-inline">
									<?php echo $this->lang->line('Receipt date'); ?>: &nbsp;&nbsp;
									<input type="date" class="form-control" id="fecharecibo" style="width: 160px; margin-top: -7px;" />
								</div>
							</div>
							<div class="row">
								<div class="col-md-4 form-inline">
									<?php echo $this->lang->line('Expiry date'); ?>: &nbsp;&nbsp;
									<input type="date" class="form-control" id="fechacaducidad" style="width: 160px; margin-top: -7px;" />
								</div>
								<?php } ?>
							</div>
							<div class="row" style="margin-bottom:15px; display: none;">
								<div class="col-md-4 form-inline">
									<span class="badge bg-warning-400"><?php echo $this->lang->line('Reason for modification'); ?>:</span>
									<select class="form-control border-warning" id="reason">
										<option value="0"><?php echo $this->lang->line('Select'); ?></option>
										<option value="1. Part Number Error">1. Part Number Error</option>
										<option value="2. Quantity Error">2. Quantity Error</option>
										<option value="3. Unit Mesure Discrepancy">3. Unit Mesure Discrepancy</option>
										<option value="4. Wrong Location">4. Wrong Location</option>
										<option value="5. Auditado">5. Auditado</option>
										<option value="6">6. Others</option>
									</select>
								</div>
								<div class="col-md-6 form-inline">
									<input type="text" class="form-control border-warning" id="reasontxt" style="width: 600px; display: none;" maxlength="50" />
								</div>
							</div>
						</div>

						<table class="table datatable" id="history">
							<thead>
								<tr>
									<th class="actions"><?php echo $this->lang->line('Actions'); ?></th>
									<th><?php echo $this->lang->line('Tag'); ?></th>
									<th><?php echo $this->lang->line('Area'); ?></th>
									<th>
										<?php if($this->config->item('client')=='AtlanticPrime'){ ?>
										<?php echo $this->lang->line('Rack'); ?>
										<?php }else{ ?>
										<b>WH</b>
										<?php } ?>
									</th>
									<th><?php echo $this->lang->line('Location'); ?></th>
									<th><?php echo $this->lang->line('Item code'); ?></th>
									<th><?php echo $this->lang->line('Type'); ?></th>
									<th><?php echo $this->lang->line('Status'); ?></th>
									<th><?php echo $this->lang->line('Quantity'); ?></th>
									<th><?php echo $this->lang->line('Open box'); ?></th>
									<th><?php echo $this->lang->line('Unit price'); ?></th>
									<th><?php echo $this->lang->line('Unit extended'); ?></th>
									<th><?php echo $this->lang->line('Audit counter'); ?></th>
									<th><?php echo $this->lang->line('Item description'); ?></th>
									<th><?php echo $this->lang->line('UOM'); ?></th>
									<th><?php echo $this->lang->line('User'); ?></th>
									<th><?php echo $this->lang->line('Team Leader'); ?></th>
									<th><?php echo $this->lang->line('Auditor'); ?></th>
									<th><?php echo $this->lang->line('Comments'); ?></th>
									<?php if($this->config->item('client')=='AtlanticPrime'){ ?>
									<th>Lot<?php //echo $this->lang->line('Precounted'); ?></th>
									<th>Packing date<?php //echo $this->lang->line('Packing date'); ?></th>
									<th>UOM<?php //echo $this->lang->line('Receipt date'); ?></th>
									<!--<th><?php //echo $this->lang->line('Expiry date'); ?></th>-->
									<?php } ?>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
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
	<div class="modal fade" id="modalmessage" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-body alert-danger">
	        <div class="alert alert-danger" role="alert">
	          <h4 class="alert-heading">ALERT</h4>
	          <p>Validation is not over. <?php if($this->session->userdata('ifatrimidprofile')=='3'){ ?><a href="<?php echo base_url(); ?>reports/validations">Go to validation</a><?php } ?></p>
	          <br/>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>

	<div class="modal fade" id="modalaudits" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
	  <div class="modal-dialog modal-xl" role="document">
	    <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="modalauditslabel">Audits for:</h4>
			</div><!-- /modal-header -->
			<div class="modal-body">
				<table class="table datatable" id="audits">
					<thead>
						<tr>
							<th><?php echo $this->lang->line('Audit counter'); ?></th>
							<th><?php echo $this->lang->line('Quantity'); ?></th>
							<th><?php echo $this->lang->line('Location'); ?></th>
							<th><?php echo $this->lang->line('Reason for modification'); ?></th>
							<th><?php echo $this->lang->line('Audit result'); ?></th>
							<th><?php echo $this->lang->line('Auditor'); ?></th>
							<th><?php echo $this->lang->line('Audit datetime'); ?></th>
							<th><?php echo $this->lang->line('Open box'); ?></th>
							<th><?php echo $this->lang->line('UOM'); ?></th>

							
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
	    </div>
	  </div>
	</div>

</body>
<script type="text/javascript">
	var erroritemcode='Type an item code', errortag='Type a tag';
	var datatable;
	var datatableaudits;
	var tagaudits = '';
	var editmode=false;
	var openbox='No';
	var precounted='No';
	var locations=JSON.parse('<?php echo json_encode($locations); ?>');
	$(document).ready(function($) {
		$('.chosen-select').chosen();
		$('.chosen-single').height(36);
		$('.chosen-container').width('90%');
		$('.chosen-single span').css('margin-top', '5px');

		$('#warehouse').change(function(event) {
			$('#location').html('');
			$.each(locations, function(index, val) {
				if(val.wh==$('#warehouse').val())
					$('#location').append('<option data-dimm="'+val.plataforma+'" value="'+val.location+'">'+val.location+'</option>');
			});
			$('#location').prop('disabled', false).trigger("chosen:updated");
			$('#location').change();
			/*$.post('<?php echo base_url();?>capture/getlocations', {wh: $('#warehouse').val()}, function(data) {
				var obj=JSON.parse(data);
				$('#location').html('');
				$.each(obj, function(index, val) {
					$('#location').append('<option value="'+val.location+'">'+val.location+'</option>');
				});
				$('#location').prop('disabled', false).trigger("chosen:updated");
			});*/
		});
		$('#location').change(function(event) {
			$('#dimm').val($('#location option:selected').data('dimm'));
		});
		//================= Datatables =================>
		datatable=$('#history').DataTable({
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
          			url: "<?php echo base_url(); ?>reports/loaddatatotalcapture/<?php echo $mode; ?>",
          			type: "GET",
          			data: function ( d ) {
          				if($('#mintag').val()!='' && $('#maxtag').val()!=''){
					        d.maxtag = $('#maxtag').val();
					        d.mintag = $('#mintag').val();
					    }
				    }
          		  },
			dom: 'Bfrtip',
			lengthMenu: [
	            [ 10, 25, 50, -1 ],
	            [ '10 <?php echo $this->lang->line('rows'); ?>', '25 <?php echo $this->lang->line('rows'); ?>', '50 <?php echo $this->lang->line('rows'); ?>', '<?php echo $this->lang->line('Show all'); ?>' ]
	        ],
	        buttons: [
	            /*'copyHtml5',*/
	            //'excelHtml5',
	            /*'csvHtml5',*/
	            //'print',
	            {
	                text: 'Download Excel',
	                action: function ( e, dt, node, config ) {
	                	$.redirect('<?php echo base_url();?>reports/exporttotalcapture', {mode: '<?php echo $mode; ?>'},'POST', '_blank');
	                }
	            },
	            {
		            extend: 'print',
		            text: '<?php echo $this->lang->line('Print'); ?>'
		        },
	            'pageLength'
	        ]
		});

		setTimeout(function(){
			var html='Min tag: <input type="number" class="form-control" id="mintag">&nbsp;&nbsp;&nbsp;';
			html+='Max tag: <input type="number" class="form-control" id="maxtag">';
			$('#history_filter').addClass('form-inline').width(800).html(html);

	        $('#mintag, #maxtag').keyup( function() {
	        		datatable.settings()[0].jqXHR.abort()
		        	datatable.draw();
		    });
		}, 3000);


		datatableaudits=$('#audits').DataTable({
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
          			url: "<?php echo base_url(); ?>tags/loaddataaudits",
          			data: function ( d ) {
						return $.extend( {}, d, {
	               	 		"tag": tagaudits
	              		});
				    }
          		  },
			dom: 'Bfrtip',
			lengthMenu: [
	            [ 10, 25, 50, -1 ],
	            [ '10 <?php echo $this->lang->line('rows'); ?>', '25 <?php echo $this->lang->line('rows'); ?>', '50 <?php echo $this->lang->line('rows'); ?>', '<?php echo $this->lang->line('Show all'); ?>' ]
	        ],
	        buttons: [
	            /*'copyHtml5',*/
	            //'excelHtml5',
	            /*'csvHtml5',*/
	            //'print',
				
	            'excelHtml5',
	            'pageLength'
	        ]
		});
	    //======================================>

		$('#tag').change(function(event) {
			if(!$('#tag').val().startsWith('<?php echo $config->tagprefix; ?>')){
				errortag="It is not a valid tag, prefix required <?php echo $config->tagprefix; ?>. " + $('#tag').val();
				new PNotify({
		            title: 'Problem',
		            text: errortag,
		            addclass: 'bg-danger'
		        });
                $('#tag').val('');
                return;
            }
			$.post('<?php echo base_url(); ?>capture/gettag', {tag: $('#tag').val().replace('<?php echo $config->tagprefix; ?>','')}, function(data) {
				var obj=JSON.parse(data);
				if($.isEmptyObject(obj)){
					errortag="Tag doesn't exists.";
					new PNotify({
			            title: 'Problem',
			            text: errortag,
			            addclass: 'bg-danger'
			        });
				}else{
					$('#area').val(obj.areadesc);
					$('#idcounter').val(obj.idcounter);
					$('#idauditor').val(obj.idauditor);
					if(obj.status!=null && !editmode){
						errortag="This tag has been captured.";
						new PNotify({
				            title: 'Problem',
				            text: errortag,
				            addclass: 'bg-danger'
				        });
					}else{
						<?php if($mode=='1'){ ?>
						if(obj.areadesc!='<?php echo $this->session->userdata('ifatrimarea'); ?>'){
							errortag="This tag does not belong in your area.";
							new PNotify({
					            title: 'Problem',
					            text: errortag,
					            addclass: 'bg-danger'
					        });
						}else{
							errortag='';
						}
					<?php }else{ ?>
						errortag='';
					<?php } ?>
					}
				}
			});
		});
		$('#openbox').change(function(event) {
			openbox=$('#openbox').is(':checked') ? 'Ok' : 'No';
		});
		$('#precounted').change(function(event) {
			precounted=$('#precounted').is(':checked') ? 'Ok' : 'No';
		});
		$('#precounted').change(function(event) {
			precounted=$('#precounted').is(':checked') ? 'Ok' : 'No';
		});
		$('#status').change(function(event) {
			if($(this).val()=='Voided' || $(this).val()=='Blank'){
				$('#itemcode').val('').prop('disabled', true);
				$('#qty').val('').prop('disabled', true);
				$('#description').val('');
				$('#uom').val('');
				$('#type').val('')
				$('#save').focus();
			}else{
				$('#itemcode').val('').prop('disabled', false);
				$('#qty').val('').prop('disabled', false);
			}
		});
		$('#itemcode').change(function(event) {
			if(!$('#itemcode').val().startsWith('<?php echo $config->itemprefix; ?>')){
				erroritemcode="It is not a valid item, prefix required <?php echo $config->itemprefix; ?>" + $('#itemcode').val();
				new PNotify({
		            title: 'Problem',
		            text: erroritemcode,
		            addclass: 'bg-danger'
		        });
                $('#itemcode').val('');
                return;
            }
            if($('#itemcode').val().length>1){
                if($('#itemcode').val().charAt(0)=='<?php echo $config->itemprefix; ?>'){
                    $('#itemcode').val($('#itemcode').val().substring(1));
                }
                if($('#itemcode').val().includes('/')){
                    var temparr=$('#itemcode').val().split('/');
                    $('#itemcode').val(temparr[0]);
                }
            }
			//=====>
			$.post('<?php echo base_url(); ?>capture/getitemcode', {itemcode: $('#itemcode').val()}, function(data) {
				var obj=JSON.parse(data);
				if($.isEmptyObject(obj)){
					erroritemcode="Item code doesn't exists.";
					new PNotify({
			            title: 'Problem',
			            text: erroritemcode,
			            addclass: 'bg-danger'
			        });
				}else{
					erroritemcode='';
					$('#description').val(obj.description);
					$('#uom').val(obj.uom);
					$('#type').val(obj.type);
					
					/*if(obj.descriptionuom!=null){
						alert("Verifica que la unidad de medida de éste número de parte sea "+obj.descriptionuom);
					}*/
					//Warehouse and location fixed
					//$('#warehouse').val('<?php echo $this->session->userdata('ifawh'); ?>');
					//$('#location').val('<?php echo $this->session->userdata('ifalocation'); ?>');
				}
			});
		});
		$('#qty').keypress(function(event) {
			//$('#qty').val($('#qty').val().replace('Q','').replace('q',''));
			if(event.keyCode==13){
				savecapture();
			}
		});
		$('#qty').change(function(event) {
			openbox=confirm('Is the box open?') ? 'Ok' : 'No';
			$('#openbox').prop('checked', openbox=='Ok');
		});
		$('#history').delegate('.fa-edit', 'click', function(event) {
			var basetr=$(this).parents('tr');
			if(basetr.hasClass('child')){
				basetr=basetr.prev();
			}
			var tag=basetr.children().eq(1).html();
			var warehouse=basetr.children().eq(3).html();
			var location=basetr.children().eq(4).html();
			var itemcode=basetr.children().eq(5).html();
			var type=basetr.children().eq(6).html();
			var status=basetr.children().eq(7).html();
			var qty=basetr.children().eq(8).html();
			qty=qty.replace('$','');
			openbox=basetr.children().eq(9).html();
			precounted=basetr.children().eq(19).html();
			var fechaempaque=basetr.children().eq(20).html();
			var fecharecibo=basetr.children().eq(21).html();
			var fechacaducidad=basetr.children().eq(22).html();
			$('#reason').parent().parent().show('slow');
			
			erroritemcode='Type an item code';
			errortag='Type a tag';
			editmode=true;//To pass validation tag captured
			$('#tag').val('<?php echo $config->tagprefix; ?>'+tag).change();
			$('#itemcode').val('<?php echo $config->itemprefix; ?>'+itemcode).change();
			$('#status').val(status);
			$('#qty').val(qty);
			$('#warehouse').val(warehouse.toUpperCase()).trigger("chosen:updated").change();

			$('#openbox').prop('checked', openbox=='Ok');
			$('#precounted').prop('checked', precounted=='Ok');
			$('#fechaempaque').val(fechaempaque);
			$('#fecharecibo').val(fecharecibo);
			$('#fechacaducidad').val(fechacaducidad);
			setTimeout(function(){
				$('#location').val(location.toUpperCase()).trigger("chosen:updated").change();
			}, 2000);//4000
		});
		$('#history').delegate('.fa-eye', 'click', function(event) {
			event.preventDefault();

			var basetr=$(this).parents('tr');
			if(basetr.hasClass('child')){
				basetr=basetr.prev();
			}
			tagaudits=basetr.children().eq(1).html();

			$('#modalauditslabel').text('Audits for Tag: '+tagaudits);

			datatableaudits.ajax.reload();
			$('#modalaudits').modal('show');
			setTimeout(() => {
				datatableaudits.draw();
			}, 150);
			
		});
		$('#history').delegate('.fa-remove', 'click', function(event) {
			var basetr=$(this).parents('tr');
			if(basetr.hasClass('child')){
				basetr=basetr.prev();
			}
			var tag=basetr.children().eq(0).html();
			if(confirm('<?php echo $this->lang->line('Are you sure?'); ?>')){
				$.post('<?php echo base_url(); ?>capture/resetcapture', {id: tag}, function(data) {
					datatable.ajax.reload();
				});
			}
		});
		$('#save').click(function(event) {
			savecapture();
		});
		$('#reason').change(function(event) {
			if($(this).val()=='6'){
				$('#reasontxt').show('slow');
			}else{
				$('#reasontxt').hide('slow');
			}
		});
	});
	function savecapture(){
		/*if($('#fechaempaque').val()!=''){
			var limit1 = moment('<?php echo $config->fechaempaquemin; ?>');
			var limit2 = moment('<?php echo $config->fechaempaquemax; ?>');
			var fechaempaque=moment($('#fechaempaque').val());
			if(!(fechaempaque>limit1 && fechaempaque<limit2)){
				new PNotify({
		            title: 'Problem',
		            text: '"Fecha de empaque" must be greater than "'+limit1.format("MMMM Do YYYY")+'" and less than '+limit2.format("MMMM Do YYYY"),
		            addclass: 'bg-danger'
		        });
				return;
			}
		}
		if($('#fecharecibo').val()!=''){
			var limit1 = moment('<?php echo $config->fecharecibomin; ?>');
			var limit2 = moment('<?php echo $config->fecharecibomax; ?>');
			var fecharecibo=moment($('#fecharecibo').val());
			if(!(fecharecibo>limit1 && fecharecibo<limit2)){
				new PNotify({
		            title: 'Problem',
		            text: '"Fecha de recibo" must be greater than "'+limit1.format("MMMM Do YYYY")+'" and less than '+limit2.format("MMMM Do YYYY"),
		            addclass: 'bg-danger'
		        });
				return;
			}
		}
		if($('#fechacaducidad').val()!=''){
			var fechacaducidad=moment($('#fechacaducidad').val());
			if($('#fechaempaque').val()!=''){
				var fechaempaque=moment($('#fechaempaque').val());
				//var limit1 = moment(fechaempaque).add(6, 'M');
				//var limit2 = moment(fechaempaque).add(12, 'M');
				var limit1 = moment('<?php echo $config->fechacaducidadmin; ?>');
				var limit2 = moment('<?php echo $config->fechacaducidadmax; ?>');
				//console.log(limit1.format('DD-MM-YYYY'), limit2.format('DD-MM-YYYY'));
				if(!(fechacaducidad>limit1 && fechacaducidad<limit2)){
					new PNotify({
			            title: 'Problem',
			            text: '"Fecha de caducidad" must be greater than "'+limit1.format("MMMM Do YYYY")+'" and less than '+limit2.format("MMMM Do YYYY"),
			            addclass: 'bg-danger'
			        });
					return;
				}
			}else{
				new PNotify({
		            title: 'Problem',
		            text: 'You must capture "Fecha de empaque"',
		            addclass: 'bg-danger'
		        });
		        return;
			}			
		}*/
		//Other validations==>
		if($('#reason').is(':visible') && $('#reason').val()=='0'){
			new PNotify({
	            title: 'Problem',
	            text: "Select a reason to edit.",
	            addclass: 'bg-danger'
	        });
	        return;
		}
		if($('#reason').is(':visible') && $('#reason').val()=='6' && $('#reasontxt').val().length<10){
			new PNotify({
	            title: 'Problem',
	            text: "Type a reason, at least 10 characters.",
	            addclass: 'bg-danger'
	        });
	        return;
		}
		//Validate
		if($('#status').val()=='0' || $('#status').val()==null){
			new PNotify({
	            title: 'Problem',
	            text: "Select an status.",
	            addclass: 'bg-danger'
	        });
	        return;
		}
		if($('#idcounter').val()=='0' || $('#idcounter').val()==null){
			new PNotify({
	            title: 'Problem',
	            text: "Select a team leader.",
	            addclass: 'bg-danger'
	        });
	        return;
		}
		/*if($('#idauditor').val()=='0' || $('#idauditor').val()==null){
			new PNotify({
	            title: 'Problem',
	            text: "Select an auditor.",
	            addclass: 'bg-danger'
	        });
	        return;
		}*/
		if($('#status').val()=='Used'){
			//Qty
			var qty=$('#qty').val();
			qty=qty.replace(',','');
			if(erroritemcode!=''){
				new PNotify({
		            title: 'Problem',
		            text: erroritemcode,
		            addclass: 'bg-danger'
		        });
		        return;
			}
			if(isNaN(qty) || qty=='' || qty<=0){
				new PNotify({
		            title: 'Problem',
		            text: 'Invalid quantity',
		            addclass: 'bg-danger'
		        });
		        return;
			}
			if(qty!=Math.floor(qty) && $('#uom').val()=='EA'){
				new PNotify({
		            title: 'Problem',
		            text: 'Invalid quantity, only integers',
		            addclass: 'bg-danger'
		        });
		        return;
			}
			if($('#warehouse').val()=='0' || $('#location').val()==null){
				new PNotify({
		            title: 'Problem',
		            text: 'Select a warehouse and location',
		            addclass: 'bg-danger'
		        });
		        return;
			}
		}
		
		if(errortag!=''){
			new PNotify({
	            title: 'Problem',
	            text: errortag,
	            addclass: 'bg-danger'
	        });
	        return;
		}

		var reasontoedit='';
		if($('#reason').is(':visible')){
			if($('#reason').val()=='6'){
				reasontoedit=$('#reasontxt').val();
			}else{
				reasontoedit=$('#reason').val();
			}
		}
		$.post('<?php echo base_url();?>capture/updatecapture', {tag: $('#tag').val().replace('<?php echo $config->tagprefix; ?>',''), itemcode:$('#itemcode').val(), status:$('#status').val(), qty:qty, warehouse:$('#warehouse').val(), location:$('#location').val(), idcounter:$('#idcounter').val(), idauditor:$('#idauditor').val(), reasontoedit:reasontoedit, openbox:openbox, precounted:precounted, fechaempaque:$('#fechaempaque').val(), fecharecibo:$('#fecharecibo').val(), fechacaducidad: $('#fechacaducidad').val()}, function(data) {
			editmode=false;
			var obj=JSON.parse(data);
			datatable.ajax.reload();
			//Clean, initialize
			$('#precounted').prop('checked', false);
			$('#openbox').prop('checked', false);
			openbox='No';
			precounted='No';
			$('#fechaempaque').val('');
			$('#fecharecibo').val('');
			$('#fechacaducidad').val('');

			$('#reasontxt').val('');
			$('#reason option[value="0"]').prop("selected", "selected");
			$('#reason').parent().parent().hide('slow');
			$('input').each(function(index, el) {
				$(this).val('');
			});
			$('#itemcode').prop('disabled', false);
			$('#qty').prop('disabled', false);
			$('#status').val('Used');
			erroritemcode='Type an item code', errortag='Type a tag';
			$('#warehouse').val('0').trigger("chosen:updated").change();
			$('#location').val('0').trigger("chosen:updated");
			$('#idcounter').val('0').trigger("chosen:updated");
			$('#idauditor').val('0').trigger("chosen:updated");
			$('#tag').focus();
		});
	}
</script>
</html>
