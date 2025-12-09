	<?php $theme = 'space';?>
	
	<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> -->
	
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<script src="<?=base_url('assets/libs/sweetalert2/sweetalert2.min.js') ?>"></script>
    <!--<script src="<?//= base_url('assets/new/libs/dropzone/min/dropzone.min.js') ?>"></script>
	<script src="<?//= base_url('assets/new/libs/magnific-popup/jquery.magnific-popup.min.js') ?>"></script> -->

	<!-- Responsive examples -->
	<!-- <script src="<?//= base_url('assets/new/libs/datatables.net-responsive/js/dataTables.responsive.min.js'); ?>"></script>
	<script src="<?//= base_url('assets/new/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js'); ?>"></script> -->

	<!-- form repeater js -->
	<!-- <script src="<?//= base_url('assets/new/libs/jquery.repeater/jquery.repeater.min.js') ?>"></script>
	<script src="<?//= base_url('assets/new/libs/bs-custom-file-input/bs-custom-file-input.min.js') ?>"></script> -->

	<!-- PAGINATION JS -->
	<script src="<?=base_url('assets/themes/'.$theme.'/js/pagination.js')?>"></script>

	<!-- select2 js -->
	<script src="<?= base_url('assets/libs/select2/js/select2.min.js') ?>"></script>
	<script src="<?=base_url('assets/themes/'.$theme.'/js/jquery.dataTables.min.js')?>"></script>
	<script src="<?=base_url('assets/themes/'.$theme.'/js/dataTables.bootstrap5.min.js')?>"></script>

	<!-- Datatable init js -->
	<!-- <script src="<?//= base_url('assets/new/js/pages/datatables.init.js'); ?>"></script> -->
	<!-- App js -->
	<!-- <script src="<?//= base_url('assets/new/js/app.js'); ?>"></script> -->
	<script src="<?=base_url('assets/node_modules/moment/moment.js')?>"></script>
	<script src="<?=base_url('assets/js/custom.js')?>"></script>

	<script>
		function hanyaAngka(evt) {
			var charCode = (evt.which) ? evt.which : event.keyCode
			if (charCode > 31 && (charCode < 48 || charCode > 57))

				return false;
			return true;
		}

		const isUserUsingMobile = () => {
			// user agent string method
			let isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
			
			// Screen resolution method
			if(!isMobile){
				let screenWidth = window.screen.width;
				let screenHeight = window.screen.height;
				isMobile = (screenWidth < 768 || screenHeight < 768);
			}

			// Touch events method
			if(!isMobile){
				isMobile = (('ontouchstart' in window) || (navigator.maxTouchPoints > 0) || (navigator.msMaxTouchPoints > 0));
			}

			// CSS media queries method
			if(!isMobile){
				let bodyElement = document.getElementsByTagName('body')[0];
				isMobile = window.getComputedStyle(bodyElement).getPropertyValue('content').indexOf('mobile') !== -1;
			}

			// css ukuran lebar body
		
			let bodyElement = document.getElementsByTagName('body')[0];
			if(bodyElement.clientWidth < 768){
				isMobile = true;
			}else{
				isMobile = false;
			}
			

			return isMobile
		}
	</script>

