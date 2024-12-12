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

	<script src="<?php echo base_url(); ?>assets/highcharts/highcharts.js"></script>
	<script src="<?php echo base_url(); ?>assets/highcharts/exporting.js"></script>
	
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/app.js"></script>
	<!-- /theme JS files -->
	<style type="text/css">
		.bartime{
			background: rgb(191,210,85);
			background: -moz-linear-gradient(top,  rgba(191,210,85,1) 0%, rgba(142,185,42,1) 50%, rgba(114,170,0,1) 51%, rgba(158,203,45,1) 100%);
			background: -webkit-linear-gradient(top,  rgba(191,210,85,1) 0%,rgba(142,185,42,1) 50%,rgba(114,170,0,1) 51%,rgba(158,203,45,1) 100%);
			background: linear-gradient(to bottom,  rgba(191,210,85,1) 0%,rgba(142,185,42,1) 50%,rgba(114,170,0,1) 51%,rgba(158,203,45,1) 100%);
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#bfd255', endColorstr='#9ecb2d',GradientType=0 );
		}
		.bar{
			background: rgba(212,228,239,1);
			background: -moz-linear-gradient(left, rgba(212,228,239,1) 0%, rgba(134,174,204,1) 100%);
			background: -webkit-gradient(left top, right top, color-stop(0%, rgba(212,228,239,1)), color-stop(100%, rgba(134,174,204,1)));
			background: -webkit-linear-gradient(left, rgba(212,228,239,1) 0%, rgba(134,174,204,1) 100%);
			background: -o-linear-gradient(left, rgba(212,228,239,1) 0%, rgba(134,174,204,1) 100%);
			background: -ms-linear-gradient(left, rgba(212,228,239,1) 0%, rgba(134,174,204,1) 100%);
			background: linear-gradient(to right, rgba(212,228,239,1) 0%, rgba(134,174,204,1) 100%);
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#d4e4ef', endColorstr='#86aecc', GradientType=1 );

			-webkit-box-shadow: 1px 2px 5px 0px rgba(0,0,0,0.75);
			-moz-box-shadow: 1px 2px 5px 0px rgba(0,0,0,0.75);
			box-shadow: 1px 2px 5px 0px rgba(0,0,0,0.75);
		}
		.bar:hover{
			-webkit-box-shadow: 1px 2px 5px 0px rgba(0,0,0,2);
			-moz-box-shadow: 1px 2px 5px 0px rgba(0,0,0,2);
			box-shadow: 1px 2px 5px 0px rgba(0,0,0,2);
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"><?php echo $type=='capture' ? $this->lang->line('Progress report') : $this->lang->line('Audit progress report'); ?></span></h4>
						</div>
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<!-- Page length options -->
					<div class="panel panel-flat">
						<?php if($type=='audit'){ ?>
						<div class="panel-body">
							<div class="row" style="margin-bottom:15px;">
								<span><b>&nbsp;&nbsp;&nbsp;Auditable tags:</b></span>
							</div>
							<div class="row" style="margin-bottom:15px;">
								<div class="col-md-3 form-inline">
									Open box:
									<input type="checkbox" class="form-control" id="openbox" value="5000" style="width: 80px;" checked="">
								</div>
								<div class="col-md-3 form-inline">
									<?php echo $this->lang->line('Less than'); ?> $ USD:
									<input type="number" class="form-control" id="lessthan" value="-5000" style="width: 80px;">
								</div>
								<div class="col-md-3 form-inline">
									<?php echo $this->lang->line('Greater than'); ?> $ USD:
									<input type="number" class="form-control" id="greaterthan" value="5000" style="width: 80px;">
								</div>
								<div class="col-md-1 form-inline">
									<button type="button" id="setparameters" class="btn btn-primary">Set parameters</button>
								</div>
							</div>
						</div>
						<?php } ?>
						<table id="report" class="table datatable-show-all">
							<thead>
								<tr>
									<th style="text-align:left;padding:0px;">
										<button type="button" id="btnchange" class="btn btn-primary btn-xs"><?php echo $this->lang->line('Change schedule'); ?></button>
									</th>
									<?php foreach ($range as $key => $value) { ?>
									<th style="text-align:right;padding:0px;"><?php echo $value; ?>hrs</th>
									<?php } ?>
								</tr>
								<tr>
									<th id="lblprogress" style="font-size: 20px; font-weight:bold;"></th>
									<th style="text-align:right;"><?php echo $this->lang->line('Current time'); ?></th>
									<th colspan="<?php echo sizeof($range)-1; ?>" style="text-align:right;padding: 12px 0px;position:relative;"><!-- position:relative; -->
										<div id="time" class="bartime" style="float:left;"></div>
										<div class="redlinetop" style="position: absolute; left: 40%; background-color:red;  height:20px;width:3px;"></div>
									</th>
								</tr>
								<tr>
									<th colspan="2" id="thportion" style="width:20%">
									<select class="form-control" id="plant">
										<option value="0"><?php echo $this->lang->line('All racks'); ?></option>
										<?php foreach ($plants as $key => $value) { ?>
										<option value="<?php echo $value->plant; ?>"><?php echo $value->plant; ?></option>
										<?php } ?>
									</select>
									</th>
									<?php $percentbycell=80/(sizeof($range)-1);
										  $percentbycellreal=100/(sizeof($range)-1);
										  $percentbycellrealaccumulative=0;
									for ($i=1; $i<=(sizeof($range)-1); $i++) {
										$percentbycellrealaccumulative+=number_format($percentbycellreal,2); ?>
									<th style="text-align:right;background-color:beige;border: 1px solid black;width:<?php echo $percentbycell; ?>%; padding: 0px;"><?php echo number_format($percentbycellrealaccumulative,2,'.',','); ?>%</th>
									<?php } ?>
								</tr>
							</thead>
							<tbody></tbody>
							<tfoot>
								<tr>
									<th><b><?php echo $this->lang->line('Totals'); ?></b></th>
									<th style="background-color: #EAFF5F;"><b id="lbltotals"><?php echo $this->lang->line('Totals'); ?></b></th>
									<th colspan="7"></th>
								</tr>
							</tfoot>
						</table>
					</div>
					<!-- /page length options -->
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

	<!-- modal change schedule -->
	<div id="modalchange" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title"><?php echo $this->lang->line('Change schedule'); ?></h5>
				</div>

				<form action="#" id="frmschedule">
					<div class="modal-body">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6">
									<label><?php echo $this->lang->line('Start'); ?>:</label>
									<!--<input id="starttime" class="form-control" type="time" name="starttime" value="09:00">-->
									<select class="form-control" id="starttime" name="starttime">
										<?php for ($i=0; $i <24 ; $i++) { 
											$j=($i<10 ? '0'.$i : $i);?>
										<option value="<?php echo $j;?>:00"><?php echo $j;?> hrs</option>
										<?php } ?>
									</select>
								</div>

								<div class="col-sm-6">
									<label><?php echo $this->lang->line('End'); ?>:</label>
									<!--<input id="endtime" class="form-control" type="time" name="endtime" value="16:00">-->
									<select class="form-control" id="endtime" name="endtime">
										<?php for ($i=0; $i <24 ; $i++) { 
											$j=($i<10 ? '0'.$i : $i);?>
										<option value="<?php echo $j;?>:00"><?php echo $j;?> hrs</option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">
								<div class="col-sm-4">
									<label><?php echo $this->lang->line('Break time?'); ?></label>
									<input type="checkbox" id="break" value="1" name="break">
								</div>

								<div class="col-sm-4">
									<label><?php echo $this->lang->line('Start break'); ?>:</label>
									<select class="form-control" id="startbreak" name="startbreak">
										<?php for ($i=0; $i <24 ; $i++) { 
											$j=($i<10 ? '0'.$i : $i);?>
										<option value="<?php echo $j;?>:00"><?php echo $j;?> hrs</option>
										<?php } ?>
									</select>
								</div>

								<div class="col-sm-4">
									<label><?php echo $this->lang->line('End break'); ?></label>
									<select class="form-control" id="endbreak" name="endbreak">
										<?php for ($i=0; $i <24 ; $i++) { 
											$j=($i<10 ? '0'.$i : $i);?>
										<option value="<?php echo $j;?>:00"><?php echo $j;?> hrs</option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-link" data-dismiss="modal"><?php echo $this->lang->line('Close'); ?></button>
						<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('Save'); ?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- /modal change schedule -->

	<!-- modal details -->
	<div id="modaldetails" class="modal fade">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title">Details</h5>
				</div>

				<div class="modal-body"></div>
			</div>
		</div>
	</div>
	<!-- /modal details -->

</body>
<script type="text/javascript">
	$(document).ready(function($) {
		<?php if($type=='audit'){ ?>
			if(localStorage.progressopenbox==undefined){
				localStorage.progressopenbox=1;
				localStorage.progressgreaterthan=5000;
				localStorage.progresslessthan=-5000;
			}
			$('#openbox').prop("checked", localStorage.progressopenbox==1);
			$('#greaterthan').val(localStorage.progressgreaterthan);
			$('#lessthan').val(localStorage.progresslessthan);

			$('#setparameters').click(function(event) {
				localStorage.progressopenbox=$('#openbox').is(':checked') ? 1 : 0;
				localStorage.progressgreaterthan=$('#greaterthan').val();
				localStorage.progresslessthan=$('#lessthan').val();
				updatedata();
			});
		<?php } ?>
		updatedata();
		setInterval(function(){
			updatedata();
		}, 60000);
		gettime();
		$('#plant').change(function(event) {
			updatedata();
		});
		$('#btnchange').click(function(event) {
			$('#modalchange').modal('show');
			$.post('<?php echo base_url(); ?>reports/getschedule', {}, function(data) {
				var obj=JSON.parse(data);
				$('#starttime').val(obj.starttime);
				$('#endtime').val(obj.endtime);
				$('#startbreak').val(obj.startbreak);
				$('#endbreak').val(obj.endbreak);
				if(obj.break==1){
					$('#break').prop('checked', true);
					$('#startbreak').prop('disabled',false);
					$('#endbreak').prop('disabled',false);
				}else{
					$('#break').prop('checked', false);
					$('#startbreak').prop('disabled',true);
					$('#endbreak').prop('disabled',true);
				}
			});
		});
		$('#starttime').change(function(event) {
			//$('#endtime').prop('min',$('#starttime').val());
			console.log($('#starttime').val());
		});
		$('#break').change(function(event) {
			if($(this).is(':checked')){
				$('#startbreak').prop('disabled', false);
				$('#endbreak').prop('disabled', false);
			}else{
				$('#startbreak').prop('disabled', true);
				$('#endbreak').prop('disabled', true);
			}
		});
		$('#frmschedule').submit(function(event) {
			event.preventDefault();
			$.post('<?php echo base_url(); ?>reports/setSchedule', {data: $('#frmschedule').serialize()}, function(){
				window.location.href='<?php echo base_url(); ?>reports/progress';
			});
		});

		$('#report tbody').delegate('.progress-detail', 'click', function(event) {
			event.preventDefault();
			var area=$(this).closest('tr').find('.area').html();
			$.post('<?php echo base_url(); ?>reports/getprogressdetail/<?php echo $type; ?>', {plant:$('#plant').val(), area:area<?php if($type=='audit'){ ?>, openbox:localStorage.progressopenbox, greaterthan:localStorage.progressgreaterthan, lessthan:localStorage.progresslessthan<?php } ?>}, function(data) {
				var obj=JSON.parse(data);
				$('#modaldetails').modal('show');
				$('#modaldetails .modal-title').html(area);
				$('#table-details tbody').html('');

				let html = `<table class="table datatable-show-all" id="table-details">
								<thead>
									<tr>
										<th>Tag</th>
										<th>Plant</th>
										<th>Area</th>
										<th>Warehouse</th>
										<th>Status</th>
										<th>Audit Status</th>
									</tr>
								</thead>
								<tbody>`;
				$.each(obj, function(index, val) {
					html += `<tr>
									<td>${val.tag}</td>
									<td>${val.plant}</td>
									<td>${val.area}</td>
									<td>${val.warehouse}</td>
									<td>${val.status==null ? '' : val.status }</td>
									<td>${val.auditstatus}</td>
								</tr>`;
				});
				html += `</tbody>
						</table>`;
				$('#modaldetails .modal-body').html(html);

				$('#table-details').DataTable({
					"bDestroy": true,
					"aaSorting": [[ 5, "desc" ]],
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
			}).fail(function(err) {
				console.error(err);
				alert('There was an error, please try again later.')
			});
		});
	});
	function gettime(){ 
		var f=new Date();
		var hours=f.getHours();
		var minutes=f.getMinutes();
		//hours=13;
		//minutes=00;

		hours=(hours<10) ? '0'+hours : hours;
		minutes=(minutes<10) ? '0'+minutes : minutes;
		var seconds=f.getSeconds();
		seconds=(seconds<10) ? '0'+seconds : seconds;
		time=hours+":"+minutes+":"+seconds;
		var hoursdecimal=timeStringToFloat(time);
		var percent=hoursdecimal-<?php echo array_values($range)[0]; ?>;//actual hour - 9am
		<?php if($break=='1'){ ?>
		if(hoursdecimal>=<?php echo $startbreak; ?> && hoursdecimal<=<?php echo $endbreak; ?>){
			percent=<?php echo ($startbreak-array_values($range)[0])*$percentbycellreal; ?>;//3 normal hours X percent by hour
			var hoursdiference=hoursdecimal-<?php echo $startbreak; ?>;//12
			//console.log(hoursdiference);
			percent=percent+(hoursdiference*<?php echo $percentbycellreal/($endbreak-$startbreak+1) ?>);// this hours * half percent  Before: 7.14
			//console.log(percent);
		}else if(hoursdecimal><?php echo $endbreak; ?>){
			percent=(percent-<?php echo $endbreak-$startbreak; ?>)*<?php echo $percentbycellreal; ?>;//rest 1 hour
		}else{
			percent=percent*<?php echo $percentbycellreal; ?>;//each hour = 20% (100% / 5 hours)				Before = 100/7
		}
		<?php }else{ ?>
			percent=percent*<?php echo $percentbycellreal; ?>;//each hour = 20% (100% / 5 hours)				Before = 100/7
		<?php } ?>
		if(percent>100){
			percent=100;
		}
		$('#time').html(time);
		$('#time').width(percent+'%');
		//Red marker now on bartime
		//12 del padding+27 del height del timebar
		var heightb=12+27+$('#report tbody').height()+$('#thportion').height()*2;
		heightb-=18;
		$('.redlinetop').height(heightb).css('left', percent+'%');
		setTimeout("gettime()",1000); 
	}
	function timeStringToFloat(time) {
		var hoursMinutes = time.split(/[.:]/);
		var hours = parseInt(hoursMinutes[0], 10);
		var minutes = hoursMinutes[1] ? parseInt(hoursMinutes[1], 10) : 0;
		return hours + minutes / 60;
	}
	function updatedata(){
		$.post('<?php echo base_url();?>reports/getprogressdata/<?php echo $type; ?>', {plant:$('#plant').val()<?php if($type=='audit'){ ?>, openbox:localStorage.progressopenbox, greaterthan:localStorage.progressgreaterthan, lessthan:localStorage.progresslessthan<?php } ?>}, function(data) {
			var obj=JSON.parse(data);
			$('#report tbody').html('');
			var maxarea='';
			var maxpercent=0;
			var position=0;
			var maxpositions=obj.length;
			var tdheight=Number($('#thportion').height())+9;//12 padding top and bottom//42.5;//43.75; //26
			var totalused=0;
			var totaltags=0;

			obj.sort((a, b) => {
				<?php if($type=='capture'){ ?>
				var countedA=Number(a.used)+Number(a.voided)+Number(a.blank);
				var countedB=Number(b.used)+Number(b.voided)+Number(b.blank);
				<?php }else{ ?>
				var countedA=Number(a.audit);
				var countedB=Number(b.audit);
				<?php }?>
				return (countedA/a.total) - (countedB/b.total);
			});
			$.each(obj, function(index, val) {
				<?php if($type=='capture'){ ?>
				var counted=Number(val.used)+Number(val.voided)+Number(val.blank);
				<?php }else{ ?>
				var counted=Number(val.audit);
				<?php }?>
				var total=Number(val.total);
				var percent=counted*100/total;
				totalused+=counted;
				totaltags+=Number(val.total);
				//test with randoms
				/*var random= Math.random();
				random=random*100;
				random=parseInt(random);
				percent=random;*/
				//----->
				//Initialize with first record
				if(percent>maxpercent || index==0){
					maxpercent=percent;
					maxarea=val.area;
					position=index;
				}
				//---->
				<?php $title='Tags captured / Total tags';
				if($type=='audit'){
					$title='Tags audited / Total tags';
				} ?>
				var html='<tr>';//+val.orden+' - '
				html+='	<td class="area">'+val.area+'</td><td title="<?php echo $title; ?>">';
				html+=' <a class="progress-detail" href="#">'+counted+'/'+val.total+'</a>';
				html+='</td>'
				html+='	<td colspan="<?php echo sizeof($range)-1; ?>" style="padding: 12px 0px;   position:relative;">';//display:block;
				html+='		<div class="bar" style="width:'+percent+'%">'+Number(percent).formatMoney()+'%</div>';
				html+='	</td>';
				html+='</tr>';
				$('#report tbody').append(html);

			});
			//(Number(totalused).formatMoney()).replace('.00','')
			//(Number(totaltags).formatMoney()).replace('.00','')
			$('#lbltotals').html(totalused+'/'+totaltags);
			var percenttotal=totalused*100/totaltags;
			$('#lblprogress').html('Progress: '+percenttotal.formatMoney()+'%');
			//position+=1;
			var redlinetop=position*tdheight;
			//maxpositions+=1;
			var redlineheight=maxpositions*tdheight;
			// Cycle to add red marker
			$('#report tbody tr').each(function(index, el) {
				//if($(this).find('.area').html().replace('&nbsp;', ' ')==maxarea){
				if(position==index){
					var html='	<div style="float:left;position:absolute;left:'+Number(maxpercent).formatMoney()+'%; background-color:red; height:'+redlineheight+'px;width:3px;top:-'+redlinetop+'px"></div>';
					//$(this).find('.area').next().append(html);
				}
			});
		});
	}

	Number.prototype.formatMoney = function(c, d, t){
        var n = this, 
        c = 2,//isNaN(c = Math.abs(c)) ? 2 : c, 
        d = '.',//d == undefined ? "." : d, 
        t = ',',//t == undefined ? "," : t, 
        s = n < 0 ? "-" : "", 
        i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
        j = (j = i.length) > 3 ? j % 3 : 0;
       return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
    };
</script>
</html>
