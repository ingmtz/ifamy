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

	<script src="<?php echo base_url(); ?>assets/highcharts/highcharts.js"></script>
	<script src="<?php echo base_url(); ?>assets/highcharts/exporting.js"></script>

	<script src="<?php echo base_url(); ?>assets/jquery.redirect.js"></script>
	
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
							<div class="row">
								<div class="col-md-6">
									<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Audit progress report</span></h4>
								</div>
								<div class="col-md-6 text-right">
									<button class="btn btn-info" id="downloaddetail">Download detail</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<!-- Page length options -->
					<div class="panel panel-flat">
						<table id="report" class="table datatable-show-all">
							<thead>
								<tr>
									<th style="text-align:left;padding:0px;">
										<button type="button" id="btnchange" class="btn btn-primary btn-xs">Change schedule</button>
									</th>
									<?php foreach ($range as $key => $value) { ?>
									<th style="text-align:right;padding:0px;"><?php echo $value; ?>hrs</th>
									<?php } ?>
								</tr>
								<tr>
									<!--<th></th>-->
									<th colspan="2" style="text-align:right;">Current time</th>
									<th colspan="<?php echo sizeof($range)-1; ?>" style="text-align:right;padding: 12px 0px;position:relative;"><!-- position:relative; -->
										<div id="time" class="bartime" style="float:left;"></div>
										<div class="redlinetop" style="position: absolute; left: 40%; background-color:red;  height:20px;width:3px;"></div>
									</th>
								</tr>
								<tr>
									<th colspan="2" id="thportion" style="width:20%">
									<select class="form-control" id="plant">
										<option value="0">All plants</option>
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
									<th style="text-align:right;background-color:beige;border: 1px solid black;width:<?php echo $percentbycell; ?>%; padding: 0px;"><?php echo $percentbycellrealaccumulative; ?>%</th>
									<?php } ?>
								</tr>
							</thead>
							<tbody></tbody>
							<tfoot>
								<tr>
									<th><b>Totals</b></th>
									<th style="background-color: #EAFF5F;"><b id="lbltotals">Totals</b></th>
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
					<h5 class="modal-title">Change schedule</h5>
				</div>

				<form action="#" id="frmschedule">
					<div class="modal-body">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6">
									<label>Start:</label>
									<!--<input id="starttime" class="form-control" type="time" name="starttime" value="09:00">-->
									<select class="form-control" id="starttime" name="starttime">
										<?php for ($i=0; $i <24 ; $i++) { 
											$j=($i<10 ? '0'.$i : $i);?>
										<option value="<?php echo $j;?>:00"><?php echo $j;?> hrs</option>
										<?php } ?>
									</select>
								</div>

								<div class="col-sm-6">
									<label>End:</label>
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
									<label>Break time?</label>
									<input type="checkbox" id="break" value="1" name="break">
								</div>

								<div class="col-sm-4">
									<label>Start break:</label>
									<select class="form-control" id="startbreak" name="startbreak">
										<?php for ($i=0; $i <24 ; $i++) { 
											$j=($i<10 ? '0'.$i : $i);?>
										<option value="<?php echo $j;?>:00"><?php echo $j;?> hrs</option>
										<?php } ?>
									</select>
								</div>

								<div class="col-sm-4">
									<label>End breake</label>
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
						<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Save</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- /modal change schedule -->

</body>
<script type="text/javascript">
	$(document).ready(function($) {
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
				window.location.href='<?php echo base_url(); ?>reports/auditprogress';
			});
		});
		$('#downloaddetail').click(function(event) {
			$.redirect('<?php echo base_url();?>reports/exportauditdetail', {},'POST');
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
		var percent=hoursdecimal-<?php echo $range[0]; ?>;//actual hour - 9am
		<?php if($break=='1'){ ?>
		if(hoursdecimal>=<?php echo $startbreak; ?> && hoursdecimal<=<?php echo $endbreak; ?>){
			percent=<?php echo ($startbreak-$range[0])*$percentbycellreal; ?>;//3 normal hours X percent by hour
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
		$.post('<?php echo base_url();?>reports/getauditprogressdata', {plant:$('#plant').val()}, function(data) {
			var obj=JSON.parse(data);
			$('#report tbody').html('');
			var maxarea='';
			var maxpercent=0;
			var position=0;
			var maxpositions=obj.length;
			var tdheight=Number($('#thportion').height())+9;//12 padding top and bottom//42.5;//43.75; //26
			var totalused=0;
			var totaltags=0;
			$.each(obj, function(index, val) {
				var counted=Number(val.audited);
				var total=Number(val.total);
				var percent=counted*100/total;
				totalused+=val.audited;
				totaltags+=val.total;
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
				var html='<tr>';
				html+='	<td class="area">'+val.orden+' - '+val.area+'</td><td title="Tags captured / Total tags">'+Number(val.audited).formatMoney().replace('.00','')+'/'+val.total.formatMoney().replace('.00','')+'</td>';
				html+='	<td colspan="<?php echo sizeof($range)-1; ?>" style="padding: 12px 0px;   position:relative;">';//display:block;
				html+='		<div class="bar" style="width:'+percent.formatMoney()+'%">'+percent.formatMoney()+'%</div>';
				html+='	</td>';
				html+='</tr>';
				$('#report tbody').append(html);
			});
			$('#lbltotals').html(totalused.formatMoney().replace('.00','')+'/'+totaltags.formatMoney().replace('.00',''));
			//position+=1;
			var redlinetop=position*tdheight;
			//maxpositions+=1;
			var redlineheight=maxpositions*tdheight;
			// Cycle to add red marker
			$('#report tbody tr').each(function(index, el) {
				//if($(this).find('.area').html().replace('&nbsp;', ' ')==maxarea){
				if(position==index){
					var html='	<div style="float:left;position:absolute;left:'+maxpercent.formatMoney()+'%; background-color:red; height:'+redlineheight+'px;width:3px;top:-'+redlinetop+'px"></div>';
					//$(this).find('.area').next().append(html);
				}
			});
		});
	}
</script>
</html>
