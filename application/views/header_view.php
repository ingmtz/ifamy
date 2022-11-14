	<div class="navbar navbar-inverse">
		<div class="navbar-header" <?php if($this->config->item('client')=='AtlanticPrime'){ ?>style="background-color: white;"<?php } ?>>
			<a class="navbar-brand" href="javascript:;"><img src="<?php echo base_url(); ?>clients/<?php echo $this->config->item('client'); ?>/logo.png" alt=""></a>
			<?php if($this->config->item('client')=='CVG'){ ?>
				<h3 id="lblinventorytype" style="margin-top: 10px;"><?php echo $this->session->userdata('inventorytype'); ?></h3>
			<?php }else if($this->config->item('client')=='EasyInventory'){ ?>
				<h3 id="lblinventorytype" style="margin-top: 10px;">Easy Inventory</h3>
			<?php } ?>
			<ul class="nav navbar-nav visible-xs-block">
				<li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
				<li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
			</ul>
		</div>

		<div class="navbar-collapse collapse" id="navbar-mobile">
			<ul class="nav navbar-nav">
				<li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a></li>
			</ul>

			<p class="navbar-text" style="font-size: 21px;">
				<?php if($this->config->item('client')=='AtlanticPrime'){ ?>
				<?php 
		        $tmparr=explode('/', $_SERVER['REQUEST_URI']);
		        $sucursal='';
		        switch ($tmparr[1]) {
		          case 'APICS':
		            $sucursal='Pruebas';
		            break;
		          case 'APICSVALLARTA':
		            $sucursal='Vallarta';
		            break;
		          case 'APICSMONTERREY':
		            $sucursal='Monterrey';
		            break;
		          case 'APICSMEXICO':
		            $sucursal='Mexico';
		            break;
		          case 'APICSMATRIZ':
		            $sucursal='Matriz';
		            break;
		          case 'APICSGUADALAJARA':
		            $sucursal='Guadalajara';
		            break;
		          case 'APICSCANCUN':
		            $sucursal='Cancún';
		            break;
		          case 'APICSCABOS':
		            $sucursal='Cabos';
		            break;
				  case 'APICSSALTILLO':
		            $sucursal='Saltillo';
		            break;
		        }

		        echo $this->lang->line('Branch').': '.$sucursal;
		        ?>
		    	<?php }else{
		    		//echo 'El nombre de tu empresa';
					$this->config->item('title');
		    	} ?>
				<!--<span class="label bg-success">Online</span>-->
			</p>
			<div class="navbar-right">
				<ul class="nav navbar-nav">
					<li class="dropdown dropdown-user">
						<a class="dropdown-toggle" data-toggle="dropdown">
							<i class="glyphicon glyphicon-user" style="font-size:30px;"></i>
							<span><?php echo $this->session->userdata('ifatrimname'); ?></span>
							<i class="caret"></i>
						</a>

						<ul class="dropdown-menu dropdown-menu-right">
							<li><a href="<?php echo base_url();?>login/logout"><i class="icon-switch2"></i> <?php echo $this->lang->line('logout'); ?></a></li>
						</ul>
					</li>
				</ul>
			</div>
			<div class="navbar-right">
				<ul class="nav navbar-nav">
					<li class="dropdown dropdown-user">
						<a class="dropdown-toggle" data-toggle="dropdown">
							<i class="glyphicon glyphicon-text-color" style="font-size:30px;"></i>
							<span><?php echo ucfirst($this->session->userdata('site_lang')); ?></span>
							<i class="caret"></i>
						</a>

						<ul class="dropdown-menu dropdown-menu-right">
							<li><a href="<?php echo base_url(); ?>LanguageSwitcher/switchLang/spanish">Spanish</a></li>
							<li><a href="<?php echo base_url(); ?>LanguageSwitcher/switchLang/english">English</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</div>