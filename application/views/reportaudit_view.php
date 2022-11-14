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
	<!--<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/tables/datatables/datatables.min.js"></script>-->
	<link href="<?php echo base_url(); ?>assets/datatable/css/bootstrap.datatable.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/datatable/css/dataTables.tableTools.min.css" rel="stylesheet">
    <script src="<?php echo base_url(); ?>assets/datatable/js/jquery.dataTables.js"></script>
	<script src="<?php echo base_url(); ?>assets/datatable/js/dataTables.tableTools.js"></script>

	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/chosen/chosen.css" />
	<script src="<?php echo base_url(); ?>assets/chosen/chosen.jquery.js"></script>
	<script src="<?php echo base_url(); ?>assets/jquery.redirect.js"></script>
	
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/app.js"></script>
	<!-- /theme JS files -->
	<style type="text/css">
        input{
            text-transform: uppercase;
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Audit report</span></h4>
						</div>
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<!-- Page length options -->
					<div class="panel panel-flat">
						<table class="table datatable-show-all" id="history">
							<thead>
								<tr>
									<th>Tag</th>
									<th>Area</th>
									<th>Rack</th>
									<th>Location</th>
									<th>Item code</th>
									<th>Status</th>
									<th>Qty</th>
									<th>Open box</th>
									<th>Unit price</th>
									<th>Unit extended</th>
									<th>To audit</th>
									<th>Audit counter</th>
									<th>Item description</th>
									<th>UOM</th>
									<!--<th>Type</th>-->
									<th>User</th>
									<th>Team Leader</th>
									<th>Auditor</th>
									<th>Comments</th>
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

</body>
<script type="text/javascript">
	var erroritemcode='Type an item code', errortag='Type a tag';
	var datatable;
	var editmode=false;
	var openbox='';
	$(document).ready(function($) {
		datatable=$('#history').DataTable({
          "order": [[ 0, "desc" ]],
          "dom": 'T<"clear">lfrtip',
          "tableTools": {
              "sSwfPath": "<?php echo base_url(); ?>assets/datatable/swf/copy_csv_xls_pdf.swf",//"aButtons": [ "copy", "print" ]
          },
          "processing": true,
          "serverSide": true,
          "ajax": {
          			url: "<?php echo base_url(); ?>reports/loaddataauditreport",
          			type: "GET",
          			data: function ( d ) {
          				if($('#mintag').val()!='' && $('#maxtag').val()!=''){
					        d.maxtag = $('#maxtag').val();
					        d.mintag = $('#mintag').val();
					    }
				    }
          		  }
        });

        $('#ToolTables_history_1').click(function(event){
        	datatable.columns([15/*,2*/]).visible(false);
        });
        $(document).on('keyup',function(evt) {
		    if (evt.keyCode == 27) {
		    	datatable.columns([15/*,2*/]).visible(true);
		    }
		});

		//Add button to download
		$('.DTTT_container').append('<a class="DTTT_button DTTT_button_print" id="downloadexcel" title="Download Excel" tabindex="0" aria-controls="history"><span>Export</span></a>');
		$('#downloadexcel').click(function(event) {
			var tabledata=[];
			$('#history tr').each(function(i, el) {
				var row=[];
				$(this).find('th').each(function(i, el) {
					if(i<18) // ommit actions
					row.push($(el).html());
				});
				$(this).find('td').each(function(i, el) {
					if(i<18) // ommit actions
					row.push($(el).html());
				});
				tabledata.push(row);
			});
			tabledata=JSON.stringify(tabledata);
			$.redirect('<?php echo base_url();?>capture/exportexcel', {tabledata:tabledata, title:'AuditedTags'},'POST');
		});
		//End button export--------------->

        var html='Min tag: <input type="number" class="form-control" id="mintag">&nbsp;&nbsp;&nbsp;';
		html+='Max tag: <input type="number" class="form-control" id="maxtag">';
		$('#history_filter').addClass('form-inline').width(500).html(html);

        $('#mintag, #maxtag').keyup( function() {
        	//if($('#mintag').val()!='' && $('#maxtag').val()!=''){
        		datatable.settings()[0].jqXHR.abort()
	        	datatable.draw();
	        //}
	    });
	});
</script>
</html>
