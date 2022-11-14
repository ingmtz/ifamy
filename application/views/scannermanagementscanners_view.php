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
	<link href="<?php echo base_url(); ?>assets/validator/bootstrapValidator.min.css" rel="stylesheet" />
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
	
  <script src="<?php echo base_url(); ?>assets/validator/bootstrapValidator.min.js"></script>

  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/chosen/chosen.css" />
  <script src="<?php echo base_url(); ?>assets/chosen/chosen.jquery.js"></script>

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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"></span><?php echo $this->lang->line('Scanners'); ?></h4>
						</div>

					</div>

				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<!-- Basic datatable -->
					<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title"><a href="<?php echo base_url(); ?>scannermanagement/newscanner"><?php echo $this->lang->line('New scanner'); ?></a></h5>
						</div>
						<table id="scannermanagementtable" class="table">
							<thead>
								<tr>
									<th><?php echo $this->lang->line('Serial'); ?></th>
									<th><?php echo $this->lang->line('Brand'); ?></th>
									<th><?php echo $this->lang->line('Notes'); ?></th>
									<th><?php echo $this->lang->line('Area'); ?></th>
									<th><?php echo $this->lang->line('Assigned user'); ?></th>
									<th><?php echo $this->lang->line('Assignment time'); ?></th>
									<th class="text-center"><?php echo $this->lang->line('Options'); ?></th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
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

	<!-- modal -->
  <div class="modal fade" id="modalassignscanner">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title">Assign Scanner</h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form id="formassignscanner">
          <div class="form-group">
            <label>Assigned user:</label>
            <select class="form-control" id="assigneduser" name="assigneduser">
              <?php foreach ($users as $key => $value) { ?>
                <option value="<?php echo $value->Id; ?>"><?php echo $value->name; ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group">
            <label>Assigned area:</label>
            <select class="form-control" id="assignedarea" name="assignedarea">
              <?php foreach ($areas as $key => $value) { ?>
                <option value="<?php echo $value->plant.'|'.$value->area; ?>"><?php echo $value->plant.' - '.$value->area; ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group">
            <label>Notes:</label>
            <input type="text" class="form-control" name="txtnotes" id="txtnotes">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <input type="text" id="IdScanner" hidden>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" id="assignscanner" class="btn btn-primary float-right">Confirm</button>
      </div>
    </div>
    <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
<!-- modal -->
  <div class="modal fade" id="modalhistoryscanner">
    <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title">History</h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped table-history">
          <thead>
            <tr>
              <th>Assignment/Unassignment</th>
              <th>Assigned user</th>
              <th>Assignator/Unassignator</th>
              <th>Datetime</th>
              <th>Notes</th>
            </tr>
          </thead>
          <tbody id="scannerhistory">
          </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <input type="text" id="IdScanner" hidden>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

</body>
<script type="text/javascript">
	var datatable;
	$(document).ready(function() {
		var idscanner = 0;
    datatable=$('#scannermanagementtable').DataTable({
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
                url: "<?php echo base_url(); ?>scannermanagement/loadscanners",
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

    datatablehistory = $('.table-history').DataTable();


		$('#formassignscanner').bootstrapValidator({
          message: 'The value is invalid.',
          excluded: ':disabled',
          feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
          },
          fields: {
            assigneduser: {
              message: 'User is invalid.',
              validators: {
                notEmpty: {
                  message: 'User is required.'
                },
                          remote: {
                              url: "<?php echo base_url(); ?>scannermanagement/validateuser",
                              type: "post",
                              data: {
                                Serial: function() {
                                  return $('#assigneduser').val();
                                }
                              },
                              message: 'User is already asignated.'
                          }
              }
            }
          }
    });

    $('#scannermanagementtable').delegate('.remove-scanner', 'click', function(event) {
    	event.preventDefault();
    	var id=$(this).data('id');
    	if(confirm('Are you sure?')){
    		$.post('<?php echo base_url(); ?>scannermanagement/removescanner', {id: id}, function(data) {
    			datatable.ajax.reload();
    		});
    	}
    });

    $('#scannermanagementtable').delegate('.unassign-scanner', 'click', function(event) {
    	event.preventDefault();
    	var id=$(this).data('id');
    	if(confirm('Are you sure?')){
    		$.post('<?php echo base_url(); ?>scannermanagement/unassignscanner/'+id, {}, function(data) {
    			datatable.ajax.reload();
    		});
    	}
    });

    $(document).delegate('.assign-scanner', 'click', function(){
        idscanner = $(this).data("id");
        $("#modalassignscanner").modal("show");
        $("#assigneduser").change();
        $('#formassignscanner').data('bootstrapValidator').validate();
    });
    $('#modalassignscanner').on('shown.bs.modal', function () {
      $('#assigneduser').chosen('destroy').chosen();
      $('#assignedarea').chosen('destroy').chosen();
    });

		$("#assignscanner").click(function(){
		    if($('#formassignscanner').data('bootstrapValidator').isValid()){
		      $.post("<?php echo base_url();?>scannermanagement/assignscanner/"+idscanner,{assigneduser: $("#assigneduser").val(), area: $("#assignedarea").val(), notes:$("#txtnotes").val()},function(result){
		          datatable.ajax.reload();
		          $("#modalassignscanner").modal("hide");
		      });
		    }
		});

		$(document).delegate('.history-scanner', 'click', function(){
		    idscanner = $(this).data("id");
		    $.post("<?php echo base_url();?>scannermanagement/historyscanner/"+idscanner,{},function(result){
            datatablehistory.destroy();
		        $("#scannerhistory").html(result);
            datatablehistory = $('.table-history').DataTable({"order": [[ 3, "desc" ]]});
		        $("#modalhistoryscanner").modal("show");
		    });
		});
	});

		
</script>
</html>
