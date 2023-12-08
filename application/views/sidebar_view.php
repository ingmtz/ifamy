			<div class="sidebar sidebar-main">
				<div class="sidebar-content">

					<!-- User menu -->
					<div class="sidebar-user">
						<div class="category-content">
							<div class="media">
								<a href="#" class="media-left">
								<i class="glyphicon glyphicon-user" style="font-size:35px;"></i>
								</a>
								<div class="media-body">
									<span class="media-heading text-semibold"><?php echo $this->session->userdata('ifatrimname'); ?></span>
									<div class="text-size-mini text-muted">
										<?php echo $this->session->userdata('ifatrimprofile'); ?><br/>
										Area: <?php echo $this->session->userdata('ifatrimarea'); ?>
									</div>
								</div>
								<!--<div class="media-right media-middle">
									<ul class="icons-list">
										<li>
											<a href="#"><i class="icon-cog3"></i></a>
										</li>
									</ul>
								</div>-->
							</div>
						</div>
					</div>
					<!-- /user menu -->
					<!-- Main navigation -->
					<div class="sidebar-category sidebar-category-visible">
						<div class="category-content no-padding">
							<ul class="navigation navigation-main navigation-accordion">
								<li class="navigation-header"><span><?php echo $this->lang->line('Menu'); ?></span> <i class="icon-menu" title="<?php echo $this->lang->line('Menu'); ?>"></i></li>
								<li><a href="<?php echo base_url(); ?>capture"><i class="icon-stack2"></i> <span><?php echo $this->lang->line('Capture'); ?></span></a></li>
								<?php if($this->session->userdata('ifatrimidprofile')=='1' || $this->session->userdata('ifatrimidprofile')=='3'){ ?>
								<li><a href="<?php echo base_url(); ?>itemcodes"><i class="icon-stack"></i> <span><?php echo $this->lang->line('Item codes'); ?></span></a></li>
								<li><a href="<?php echo base_url(); ?>reports/totalcapture"><i class="glyphicon glyphicon-th"></i> <span><?php echo $this->lang->line('Total capture report'); ?></span></a></li>
								<li><a href="<?php echo base_url(); ?>reports/progress/capture"><i class="glyphicon glyphicon-align-left"></i> <span><?php echo $this->lang->line('Progress report'); ?></span></a></li>

								<?php if($this->config->item('client')=='CVG'){ ?>
								<li><a href="<?php echo base_url(); ?>reports/progress/audit"><i class="glyphicon glyphicon-align-left"></i> <span><?php echo $this->lang->line('Audit progress report'); ?></span></a></li>
								<?php } ?>


								<!--<li><a href="<?php echo base_url(); ?>reports/auditprogress"><i class="glyphicon glyphicon-list-alt"></i> <span>Audit progress report</span></a></li>-->
								
								<!--<li><a href="<?php echo base_url(); ?>reports/auditreport"><i class="glyphicon glyphicon-list-alt"></i> <span>Audit report</span></a></li>-->

								<li><a href="<?php echo base_url(); ?>reports/variation"><i class="glyphicon glyphicon-th-list"></i> <span><?php echo $this->lang->line('Variation report by item'); ?></span></a></li>
								<li><a href="<?php echo base_url(); ?>reports/variationarea"><i class="glyphicon glyphicon-th-list"></i> <span><?php echo $this->lang->line('Variation report by area'); ?></span></a></li>
								<!--<li><a href="<?php echo base_url(); ?>reports/variationbywarehouse"><i class="glyphicon glyphicon-th-list"></i> <span>Variation report by warehouse</span></a></li>-->
								<li><a href="<?php echo base_url(); ?>reports/locationreport"><i class="glyphicon glyphicon-th-list"></i> <span><?php echo $this->lang->line('Locations report'); ?></span></a></li>
								
								<li><a href="<?php echo base_url(); ?>reports/scannedbyuser"><i class="glyphicon glyphicon-user"></i> <span>Scanned by user</span></a></li>

								<li><a href="<?php echo base_url(); ?>reports/auditbyuser"><i class="glyphicon glyphicon-user"></i> <span>Audit by user</span></a></li>

								<!--<li><a href="<?php echo base_url(); ?>reports/unknownitems"><i class="glyphicon glyphicon-align-justify"></i> <span>Unknown items report</span></a></li>-->
								<!--<li><a href="<?php echo base_url(); ?>reports/importfiles"><i class="glyphicon glyphicon-cloud-upload"></i> <span>"Perpetuo" & "Item Master"</span></a></li>-->

								<!--<li><a href="<?php echo base_url(); ?>reports/devices"><i class="glyphicon glyphicon-barcode"></i> <span>Devices</span></a></li>-->

								<!--<li><a href="<?php echo base_url(); ?>reports/validations"><i class="glyphicon glyphicon-check"></i> <span>Validations</span></a></li>-->
								<li><a href="<?php echo base_url(); ?>reports/importfiles"><i class="glyphicon glyphicon-import"></i> <span><?php echo $this->lang->line('Import files'); ?></span></a></li>

								<?php if($this->session->userdata('ifatrimidprofile')=='3'){ ?>
								<li><a href="<?php echo base_url(); ?>databaseconfig"><i class="glyphicon glyphicon-hdd"></i> <span><?php echo $this->lang->line('Database config'); ?></span></a></li>
								<?php } ?>


								<li><a href="<?php echo base_url(); ?>reports/info"><i class="glyphicon glyphicon-question-sign"></i> <span><?php echo $this->lang->line('how does it work?'); ?></span></a></li>
								<?php } ?>

								<li><a href="<?php echo base_url(); ?>clients/<?php echo $this->config->item('client'); ?>/<?php echo $this->config->item('title'); ?>.apk"><i class="glyphicon glyphicon-download-alt"></i> <span>Android application</span></a></li>

								<?php if($this->config->item('client')!='CVG'){ ?>
								<li><a href="<?php echo base_url(); ?>tags/labels"><i class="glyphicon glyphicon-barcode"></i> <span>Print labels</span></a></li>
								<?php } ?>


								<?php if($this->session->userdata('ifatrimidprofile')=='3'){ ?>
								<li class="active">
									<a href="#"><i class="icon-puzzle2"></i> <span><?php echo $this->lang->line('Configuration'); ?></span></a>
									<ul>
										<li><a href="<?php echo base_url(); ?>tags/audit"><?php echo $this->lang->line('Audit'); ?></a></li>
										<li><a href="<?php echo base_url(); ?>tags"><?php echo $this->lang->line('Tags'); ?></a></li>
										<!--<li><a href="<?php echo base_url(); ?>areas"><?php echo $this->lang->line('Areas'); ?></a></li>-->
										<li><a href="<?php echo base_url(); ?>tags/locations"><?php echo $this->lang->line('Locations'); ?></a></li>
										<!-- <li><a href="<?php echo base_url(); ?>providers"><?php echo $this->lang->line('Providers'); ?></a></li> -->
										<li><a href="<?php echo base_url(); ?>settings"><?php echo $this->lang->line('Settings'); ?></a></li>
										<li><a href="<?php echo base_url(); ?>users"><?php echo $this->lang->line('Users'); ?></a></li>
										<!--<li><a href="<?php echo base_url(); ?>users/specialusers">Special Users</a></li>-->
										<li><a href="<?php echo base_url(); ?>scannermanagement/scanners"><?php echo $this->lang->line('Scanners'); ?></a></li>
										<?php if($this->config->item('client')=='CVG'){
														if($this->session->userdata('inventorytype')==$this->config->item('title').' SEATS'){ ?>
										<li><a href="<?php echo base_url(); ?>exportfile">Export AX file</a></li>
										<?php }
										} ?>
										<!--<li><a href="<?php echo base_url(); ?>reports/importlocations">Import locations</a></li>-->
									</ul>
								</li>
								<?php } ?>
							</ul>
						</div>
					</div>
					<!-- /main navigation -->
				</div>
			</div>