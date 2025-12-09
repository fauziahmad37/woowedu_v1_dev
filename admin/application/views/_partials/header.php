<header id="page-topbar">
	<div class="navbar-header bg-info">
		<div class="d-flex">
			<!-- LOGO -->
			<div class="navbar-brand-box">
				<a href="<?= site_url() . 'dashboard' ?>" class="logo logo-dark">
					<span class="logo-sm">
						<img src="<?= base_url('assets/images/logo/' . $settings->logo_mini) ?>" alt="" height="35" class="mt-0">
					</span>
					<span class="logo-lg">
						<img src="<?= base_url('assets/images/logo/' . $settings->logo_light) ?>" alt="" height="40" class="mt-0">
					</span>
				</a>

				<a href="<?= site_url() . 'dashboard' ?>" class="logo logo-light">
					<span class="logo-sm">
						<img src="<?= base_url('assets/images/logo/' . $settings->logo_mini) ?>" alt="" height="35" class="mt-0">
					</span>
					<span class="logo-lg">
						<img src="<?= base_url('assets/images/logo/' . $settings->logo_light) ?>" alt=""   class="mt-0">
					</span>
				</a>
			</div>

			<button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect text-white" id="vertical-menu-btn">
				<i class="fa fa-fw fa-bars"></i>
			</button>

		</div>

		<div class="d-flex">
 

			<div class="d-inline-block mt-4 mr-3">
				<span class="badge badge-pill badge-danger font-size-12"><?php echo $this->lang->line('woow_header_badge_admin'); ?></span>
			</div>

			<div class="dropdown d-none d-lg-inline-block ml-1">
				<button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
					<i class="bx bx-fullscreen text-white"></i>
				</button>
			</div>

			<div class="dropdown d-inline-block">
 

				<div class="dropdown-menu dropdown-menu-right">

					<!-- item-->
					<a href="<?php echo base_url('language/english') ?>" class="dropdown-item notify-item">
						<img src="<?php echo base_url(); ?>assets/new/images/flags/usa.png" alt="user-image" class="mr-1" height="12"> <span class="align-middle"><?php echo $this->lang->line('woow_header_language_english'); ?></span>
					</a>

					<!-- item-->
					<a href="<?php echo base_url('language/indonesia') ?>" class="dropdown-item notify-item">
						<img src="<?php echo base_url(); ?>assets/new/images/flags/indonesia.png" alt="user-image" class="mr-1" height="12"> <span class="align-middle"><?php echo $this->lang->line('woow_header_language_indonesian'); ?></span>
					</a>

					<a href="<?php echo base_url('language/germany') ?>" class="dropdown-item notify-item">
						<img src="<?php echo base_url(); ?>assets/new/images/flags/germany.png" alt="user-image" class="mr-1" height="12"> <span class="align-middle"><?php echo $this->lang->line('woow_header_language_germany'); ?></span>
					</a>
				</div>
			</div>

			<div class="dropdown d-inline-block">
				<button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<img class="rounded-circle header-profile-user" src="<?= !empty($_SESSION['userpic']) ? base_url('assets/images/users/'.$_SESSION['userpic']) : base_url('assets/new/images/ava-user.png') ?>" alt="Header Avatar">
					<span class="d-none d-xl-inline-block ml-1 text-white"><?= $this->session->userdata('username') ?></span>
					<i class="mdi mdi-chevron-down d-none d-xl-inline-block text-white"></i>
				</button>
				<div class="dropdown-menu dropdown-menu-right">
					<!-- item-->
					<?php if ($this->session->userdata('id_role') == 1) { ?>
						<a class="dropdown-item" href="<?= base_url() . 'admin/profile/' ?>"><i class="bx bx-user font-size-16 align-middle mr-1"></i> <?php echo $this->lang->line('woow_header_profile'); ?></a>
					<?php } else { ?>
						<a class="dropdown-item" href="<?= base_url() . 'profile/' ?>"><i class="bx bx-user font-size-16 align-middle mr-1"></i> <?php echo $this->lang->line('woow_header_profile'); ?></a>
					<?php } ?> 

					<div class="dropdown-divider"></div>
					<a class="dropdown-item text-danger" href="<?= base_url() . 'auth/logout' ?>"><i class="bx bx-power-off font-size-16 align-middle mr-1 text-danger"></i> <?php echo $this->lang->line('woow_header_logout'); ?></a>
				</div>
			</div>

		</div>
	</div>
</header>