<!DOCTYPE html>
<html>
	<head>
		<?php

		echo $this->Html->charset('utf-8');

		?>
		<title>
			<?php
			if(isset($title_for_layout))   echo $title_for_layout; ?>
		</title>
		<meta name="keywords" content="<?php
		if(isset($keywords_for_layout))   echo $keywords_for_layout ?>"/>
		<meta name="description" content="<?php
		if(isset($description_for_layout))  echo $description_for_layout; ?>">
		<META NAME="ROBOTS" CONTENT="INDEX, FOLLOW">
		<?php

		echo $this->Html->meta('icon');

		echo $this->Html->css('admin/bootstrap/css/bootstrap.min');
		echo $this->Html->css('admin/font-awesome.min');
		echo $this->Html->css('admin/ionicons.min');
		echo $this->Html->css('/js/admin/plugins/jvectormap/jquery-jvectormap-1.2.2');

		echo $this->Html->css('admin/AdminLTE');
		echo $this->Html->css('admin/skins/_all-skins.min');

		echo $this->Html->script('admin/plugins/jQuery/jQuery-2.2.0.min');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
		
		?>
	</head>
	<body class="hold-transition skin-blue sidebar-mini">

		<script>
			_url = "<?php echo __SITE_URL.'admin/'  ?>";
			_inactive = "<?php echo __('inactive') ?>";
			_active = "<?php echo __('active') ?>";
			_durl = "<?php echo __SITE_URL;  ?>";
		</script>

		<div class="wrapper">

			<header class="main-header">

				<!-- Logo -->
				<a href="index2.html" class="logo">
					<!-- mini logo for sidebar mini 50x50 pixels -->
					<span class="logo-lg">
						<b>
							<?php echo __('main_navigation'); ?>
						</b>
					</span>
				</a>

				<!-- Header Navbar: style can be found in header.less -->
				<nav class="navbar navbar-static-top">
					<!-- Sidebar toggle button-->
					<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
						<span class="sr-only">
							Toggle navigation
						</span>
					</a>
					<!-- Navbar Right Menu -->
					<div class="navbar-custom-menu">
						<ul class="nav navbar-nav">
							<!-- Control Sidebar Toggle Button -->

							<?php /*echo $this->element('Admin/skin_toggle');*/ ?>
							<!-- Messages: style can be found in dropdown.less-->
							<?php /*echo $this->element('Admin/messages_menu');*/ ?>
							<!-- Notifications: style can be found in dropdown.less -->
							<?php /*echo $this->element('Admin/notifications_menu');*/ ?>
							<!-- Tasks: style can be found in dropdown.less -->
							<?php /*echo $this->element('Admin/tasks_menu');*/ ?>
							<!-- User Account: style can be found in dropdown.less -->
							<li class="dropdown user user-menu">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<?php echo $this->Html->image('admin/user2-160x160.jpg', array('class'=>'user-image','alt'  => $this->UserSession->getName())); ?>
									<span class="hidden-xs">
										<?php echo $this->UserSession->getName() ?>
									</span>
								</a>
								<ul class="dropdown-menu">
									<!-- User image -->
									<li class="user-header">
										<?php echo $this->Html->image('user2-160x160.jpg', array('class'=>'img-circle','alt'  => $this->UserSession->getName())); ?>
										<p>
											<?php echo __('مدیر سایت'); ?>
											<small>
												<?php echo $this->UserSession->getName() ?>
											</small>
										</p>
									</li>
									<!-- Menu Body -->

									<!-- Menu Footer-->
									<li class="user-footer">
										<div class="pull-left">
											<a href="<?php echo __SITE_URL.'admin/users/edit/'.$this->UserSession->getId() ?>" class="btn btn-default btn-flat">
												<?php echo __('edit'); ?>
											</a>
										</div>
										<div class="pull-right">
											<a href="<?php echo __SITE_URL.'admin/users/logout' ?>" class="btn btn-default btn-flat">
												<?php echo __('exit'); ?>
											</a>
										</div>
									</li>
								</ul>
							</li>

						</ul>
					</div>

				</nav>
			</header>
			<!-- Left side column. contains the logo and sidebar -->
			<aside class="main-sidebar">
				<!-- sidebar: style can be found in sidebar.less -->
				<section class="sidebar">
					<!-- Sidebar user panel -->
					<!-- sidebar menu: : style can be found in sidebar.less -->
					<ul class="sidebar-menu">
						<li>
							<a href="<?php echo __SITE_URL."admin"; ?>">
								<i class="fa fa-dashboard">
								</i>
								<span>
									<?php echo __('dashboard'); ?>
								</span>
							</a>
						</li>
						<?php
						  $controllers = array('users','roles');
						?>
						<li class="treeview <?php if(in_array($this->request->params['controller'],$controllers)) echo 'active'; ?> ">
							<a href="#">
								<i class="fa fa-users">
								</i>
								<span>
									<?php echo __('permision_and_users'); ?>
								</span>
								<i class="fa fa-angle-left pull-right">
								</i>
							</a>
							<ul class="treeview-menu">
								<li <?php if($this->request->params['controller'] == 'roles') echo 'class="active"'; ?> >
									<a href="<?php echo __SITE_URL.'admin/roles/index' ?>">
										<i class="fa fa-circle-o">
										</i> <?php echo __('role_managment'); ?>
									</a>
								</li>
								<li <?php if($this->request->params['controller'] == 'users') echo 'class="active"'; ?>> 
									<a href="<?php echo __SITE_URL.'admin/users/index' ?>">
										<i class="fa fa-circle-o">
										</i> <?php echo __('user_managment'); ?>
									</a>
								</li>
							</ul>
						</li>
						<?php
						  $controllers = array('pages');
						  $id = 0;
						  if(!empty($this->request->params['pass'][0])){
						  	$id = $this->request->params['pass'][0];
						  }
						   
						?>
						<li class="treeview <?php if(in_array($this->request->params['controller'],$controllers)) echo 'active'; ?> ">
							<a href="#">
								<i class="fa fa-file-text-o">
								</i>
								<span>
									<?php echo __('page_managment'); ?>
								</span>
								<i class="fa fa-angle-left pull-right">
								</i>
							</a>
							<ul class="treeview-menu">
								<li <?php if($this->request->params['controller'] == 'pages' && $id == 1) echo 'class="active"'; ?> >
									<a href="<?php echo __SITE_URL.'admin/pages/edit/1' ?>">
										<i class="fa fa-circle-o">
										</i> <?php echo __('about_us'); ?>
									</a>
								</li>
								<li <?php if($this->request->params['controller'] == 'pages' && $id ==2) echo 'class="active"'; ?>> 
									<a href="<?php echo __SITE_URL.'admin/pages/edit/2' ?>">
										<i class="fa fa-circle-o">
										</i> <?php echo __('contact_us'); ?>
									</a>
								</li>
							</ul>
						</li>
						<li <?php if($this->request->params['controller'] == 'plugins') echo 'class="active"'; ?>>
							<a href="<?php echo __SITE_URL.'admin/plugins/index' ?>">
								<i class="fa fa-plug">
								</i>
								<span>
									<?php echo __('plugin_managment'); ?>
								</span>
							</a>
						</li>
						<?php $this->Plugin->run_hook('admin_menu',array("myparam"=>"123456799")); ?>
						<?php $this->Plugin->run_hook('admin_group_menu',array("myparam"=>"123456799")); ?>
						<li>
							<a href="<?php echo __SITE_URL.'admin/settings/edit' ?>">
								<i class="fa fa-cog">
								</i>
								<span>
									<?php echo __('site_setting'); ?>
								</span>
							</a>
						</li>
						<li>
							<a href="<?php echo __SITE_URL.'admin/backups/index' ?>">
								<i class="fa fa-database">
								</i>
								<span>
									<?php echo __('database_managment'); ?>
								</span>
							</a>
						</li>
						
						<!--<li>
						<a href="pages/widgets.html">
						<i class="fa fa-th"></i> <span>Widgets</span>
						<small class="label pull-right bg-green">new</small>
						</a>
						</li>-->

					</ul>
				</section>
				<!-- /.sidebar -->
			</aside>

			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<?php
				echo $this->Flash->render();
				echo $this->fetch('content');
				?>
			</div>
			<!-- /.content-wrapper -->

			<footer class="main-footer">
				<div class="pull-right hidden-xs">
					<b>
						ورژن
					</b> 3
				</div>
				<strong>
					&copy; 2016
					<a href="http://springdesigng.com/">
						<?php echo __('bahar_group'); ?>
					</a>.
				</strong>

			</footer>

			<!-- Control Sidebar -->
			<aside class="control-sidebar control-sidebar-dark">
				<!-- Create the tabs -->
				<ul class="nav nav-tabs nav-justified control-sidebar-tabs">
					<li>
						<a href="#control-sidebar-home-tab" data-toggle="tab">
							<i class="fa fa-home">
							</i>
						</a>
					</li>
					<li>
						<a href="#control-sidebar-settings-tab" data-toggle="tab">
							<i class="fa fa-gears">
							</i>
						</a>
					</li>
				</ul>
				<!-- Tab panes -->
				<div class="tab-content">
					<!-- Home tab content -->
					<div class="tab-pane" id="control-sidebar-home-tab">
						<h3 class="control-sidebar-heading">
							Recent Activity
						</h3>
						<ul class="control-sidebar-menu">
							<li>
								<a
									href="javascript:void(0)">
									<i class="menu-icon fa fa-birthday-cake bg-red">
									</i>

									<div class="menu-info">
										<h4 class="control-sidebar-subheading">
											Langdon's Birthday
										</h4>

										<p>
											Will be 23 on April 24th
										</p>
									</div>
								</a>
							</li>
							<li>
								<a
									href="javascript:void(0)">
									<i class="menu-icon fa fa-user bg-yellow">
									</i>

									<div class="menu-info">
										<h4 class="control-sidebar-subheading">
											Frodo Updated His Profile
										</h4>

										<p>
											New phone +1(800)555-1234
										</p>
									</div>
								</a>
							</li>
							<li>
								<a
									href="javascript:void(0)">
									<i class="menu-icon fa fa-envelope-o bg-light-blue">
									</i>

									<div class="menu-info">
										<h4 class="control-sidebar-subheading">
											Nora Joined Mailing List
										</h4>

										<p>
											nora@example.com
										</p>
									</div>
								</a>
							</li>
							<li>
								<a
									href="javascript:void(0)">
									<i class="menu-icon fa fa-file-code-o bg-green">
									</i>

									<div class="menu-info">
										<h4 class="control-sidebar-subheading">
											Cron Job 254 Executed
										</h4>

										<p>
											Execution time 5 seconds
										</p>
									</div>
								</a>
							</li>
						</ul>
						<!-- /.control-sidebar-menu -->

						<h3 class="control-sidebar-heading">
							Tasks Progress
						</h3>
						<ul class="control-sidebar-menu">
							<li>
								<a
									href="javascript:void(0)">
									<h4 class="control-sidebar-subheading">
										Custom Template Design
										<span class="label label-danger pull-right">
											70%
										</span>
									</h4>

									<div class="progress progress-xxs">
										<div class="progress-bar progress-bar-danger" style="width: 70%">
										</div>
									</div>
								</a>
							</li>
							<li>
								<a
									href="javascript:void(0)">
									<h4 class="control-sidebar-subheading">
										Update Resume
										<span class="label label-success pull-right">
											95%
										</span>
									</h4>

									<div class="progress progress-xxs">
										<div class="progress-bar progress-bar-success" style="width: 95%">
										</div>
									</div>
								</a>
							</li>
							<li>
								<a
									href="javascript:void(0)">
									<h4 class="control-sidebar-subheading">
										Laravel Integration
										<span class="label label-warning pull-right">
											50%
										</span>
									</h4>

									<div class="progress progress-xxs">
										<div class="progress-bar progress-bar-warning" style="width: 50%">
										</div>
									</div>
								</a>
							</li>
							<li>
								<a
									href="javascript:void(0)">
									<h4 class="control-sidebar-subheading">
										Back End Framework
										<span class="label label-primary pull-right">
											68%
										</span>
									</h4>

									<div class="progress progress-xxs">
										<div class="progress-bar progress-bar-primary" style="width: 68%">
										</div>
									</div>
								</a>
							</li>
						</ul>
						<!-- /.control-sidebar-menu -->

					</div>
					<!-- /.tab-pane -->

					<!-- Settings tab content -->
					<div class="tab-pane" id="control-sidebar-settings-tab">
						<form method="post">
							<h3 class="control-sidebar-heading">
								General Settings
							</h3>

							<div class="form-group">
								<label class="control-sidebar-subheading">
									Report panel usage
									<input type="checkbox" class="pull-right" checked>
								</label>

								<p>
									Some information about this general settings option
								</p>
							</div>
							<!-- /.form-group -->

							<div class="form-group">
								<label class="control-sidebar-subheading">
									Allow mail redirect
									<input type="checkbox" class="pull-right" checked>
								</label>

								<p>
									Other sets of options are available
								</p>
							</div>
							<!-- /.form-group -->

							<div class="form-group">
								<label class="control-sidebar-subheading">
									Expose author name in posts
									<input type="checkbox" class="pull-right" checked>
								</label>

								<p>
									Allow the user to show his name in blog posts
								</p>
							</div>
							<!-- /.form-group -->

							<h3 class="control-sidebar-heading">
								Chat Settings
							</h3>

							<div class="form-group">
								<label class="control-sidebar-subheading">
									Show me as online
									<input type="checkbox" class="pull-right" checked>
								</label>
							</div>
							<!-- /.form-group -->

							<div class="form-group">
								<label class="control-sidebar-subheading">
									Turn off notifications
									<input type="checkbox" class="pull-right">
								</label>
							</div>
							<!-- /.form-group -->

							<div class="form-group">
								<label class="control-sidebar-subheading">
									Delete chat history
									<a
										href="javascript:void(0)" class="text-red pull-right">
										<i class="fa fa-trash-o">
										</i>
									</a>
								</label>
							</div>
							<!-- /.form-group -->
						</form>
					</div>
					<!-- /.tab-pane -->
				</div>
			</aside>
			<!-- /.control-sidebar -->
			<!-- Add the sidebar's background. This div must be placed
			immediately after the control sidebar -->
			<div class="control-sidebar-bg">
			</div>

		</div>
		<!-- ./wrapper -->


		<?php


		echo $this->Html->script('/css/admin/bootstrap/js/bootstrap.min');
		echo $this->Html->script('admin/plugins/fastclick/fastclick');
		echo $this->Html->script('admin/app');
		echo $this->Html->script('admin/plugins/sparkline/jquery.sparkline.min');
		echo $this->Html->script('admin/plugins/jvectormap/jquery-jvectormap-1.2.2.min');
		echo $this->Html->script('admin/plugins/jvectormap/jquery-jvectormap-world-mill-en');
		echo $this->Html->script('admin/plugins/slimScroll/jquery.slimscroll.min');
		echo $this->Html->script('admin/plugins/chartjs/Chart.min');
		echo $this->Html->script('admin/pages/dashboard2');
		echo $this->Html->script('admin/demo');
		echo $this->element('sql_dump');

		?>
	</body>
</html>
