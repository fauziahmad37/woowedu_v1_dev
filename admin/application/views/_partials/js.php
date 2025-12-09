	<script src="<?= base_url('assets/new/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js'); ?>"></script>
	<script src="<?= base_url('assets/new/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js'); ?>"></script>
	<!-- Required datatable js -->
	<script src="<?= base_url('assets/new/libs/datatables.net/js/jquery.dataTables.min.js'); ?>"></script>
	<script src="<?= base_url('assets/new/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js'); ?>"></script>
	<!-- Buttons examples -->
	<script src="<?= base_url('assets/new/libs/datatables.net-buttons/js/dataTables.buttons.min.js'); ?>"></script>
	<script src="<?= base_url('assets/new/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js'); ?>"></script>
	<script src="<?= base_url('assets/new/libs/datatables.net-buttons-bs4/js/select.bootstrap4.min.js'); ?>"></script>
	<script src="<?= base_url('assets/new/libs/jszip/jszip.min.js'); ?>"></script>
	<script src="<?= base_url('assets/new/libs/pdfmake/build/pdfmake.min.js'); ?>"></script>
	<script src="<?= base_url('assets/new/libs/pdfmake/build/vfs_fonts.js'); ?>"></script>
	<script src="<?= base_url('assets/new/libs/datatables.net-buttons/js/buttons.html5.min.js'); ?>"></script>
	<script src="<?= base_url('assets/new/libs/datatables.net-buttons/js/buttons.print.min.js'); ?>"></script>
	<script src="<?= base_url('assets/new/libs/datatables.net-buttons/js/buttons.colVis.min.js'); ?>"></script>
	<script src="<?= base_url('assets/new/libs/sweetalert2/sweetalert2.min.js') ?>"></script>
	<script src="<?= base_url('assets/new/libs/dropzone/min/dropzone.min.js') ?>"></script>
	<script src="<?= base_url('assets/new/libs/magnific-popup/jquery.magnific-popup.min.js') ?>"></script>

	<!-- Responsive examples -->
	<script src="<?= base_url('assets/new/libs/datatables.net-responsive/js/dataTables.responsive.min.js'); ?>"></script>
	<script src="<?= base_url('assets/new/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js'); ?>"></script>

	<!-- form repeater js -->
	<script src="<?= base_url('assets/new/libs/jquery.repeater/jquery.repeater.min.js') ?>"></script>
	<script src="<?= base_url('assets/new/libs/bs-custom-file-input/bs-custom-file-input.min.js') ?>"></script>


	<!-- select2 js -->
	<script src="<?= base_url('assets/new/libs/select2/js/select2.min.js') ?>"></script>

	<!-- Datatable init js -->
	<script src="<?= base_url('assets/new/js/pages/datatables.init.js'); ?>"></script>
	<!-- App js -->
	<script src="<?= base_url('assets/new/js/app.js'); ?>"></script>

	<script>
		function hanyaAngka(evt) {
			var charCode = (evt.which) ? evt.which : event.keyCode
			if (charCode > 31 && (charCode < 48 || charCode > 57))

				return false;
			return true;
		}
	</script>