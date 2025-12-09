<?php $this->load->view("_partials/head.php") ?>
	<style>
		.datepicker {
		  z-index: 1600 !important; /* has to be larger than 1050 */
		}
	</style>
	<body data-sidebar="dark" data-keep-enlarged="true"  >

		<!-- Begin page -->
		<div id="layout-wrapper">

			<?php $this->load->view("_partials/header.php") ?>

			<!-- ========== Left Sidebar Start ========== -->

			<?php $this->load->view("_partials/navbar.php") ?>

			<!-- Left Sidebar End -->

			<!-- ============================================================== -->
			<!-- Start right Content here -->
			<!-- ============================================================== -->
			<div class="main-content">

				<div class="page-content">
					<div class="container-fluid">

						<!-- start page title -->
						<?php $this->load->view("_partials/breadcrumb.php") ?>
						<!-- end page title -->

						<?php echo $contents; ?>

					</div>
					<!-- container-fluid -->
				</div>
				<!-- End Page-content -->

				<?php $this->load->view("_partials/footer.php") ?>
			</div>
			<!-- end main content-->

		</div>
		<!-- END layout-wrapper -->

		<!-- JAVASCRIPT -->
		<?php $this->load->view("_partials/js.php") ?>
		<?= !empty($page_js) ? add_js($page_js) : trim('') ?>
	</body>
	</html>