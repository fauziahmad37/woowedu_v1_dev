<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<form class="form-horizontal searchReportAll" action="#" method="post" id="form_report_all">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="raw_date">Tanggal <?php //echo date("d m Y",strtotime("16-03-2022"))?></label>
								<input type="text" class="form-control" placeholder="mm-dd-yyyy" id="s_start" name="s_start" data-date-format="dd-mm-yyyy"  data-provide="datepicker" value="<?php echo date("d-m-Y"); ?>" /> 
							</div>
						</div> 
					</div>
 
					<div class="row">
						<div class="col-12">
							<div class="form-group pt-2 mr-2">
								<div class="button-items float-right">
									<button type="button" class="btn btn-danger btn-primary waves-effect waves-light" id="print_button">
										<i class="bx bxs-file-pdf font-size-16 align-middle mr-2"></i>
										Cetak PDF
									</button> 						
								</div>
								<div class="button-items float-right"> 
									<button type="button" class="btn btn-block btn-primary waves-effect waves-light" id="search_button">
										<i class="bx bx-search font-size-16 align-middle mr-2"></i>
										Search
									</button>									
								</div>								
							</div>
						</div>
					</div>
				</form>
				<hr>
				<div id="search_result">

				</div>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view("reports/all-transactions/modal.php") ?>

<script>
$(document).ready(function() {

 $("#print_button").click(function(){
	  var s_start = $("#s_start").val();
	  var s_end = $("#s_end").val();
		
		Swal.fire({
			html: 	'<div class="d-flex flex-column align-items-center">'
			+ '<span class="spinner-border text-primary"></span>'
			+ '<h3 class="mt-2">Loading...</h3>'
			+ '<div>',
			showConfirmButton: false,
			width: '10rem',
			timer: 1000
		});		
		window.location.href = '<?= base_url('reports/rekappendapatan_downloadPdf') ?>?tanggal='+s_start; 
 });
 $("#search_button").click(function(){
	  var s_start = $("#s_start").val();
	  var s_end = $("#s_end").val();
		var fData = new FormData();
		fData.append('tanggal', s_start);  
		$.ajax({
			url: '<?= base_url('reports/search_rekappendapatan') ?>',
			type: 'POST',
			data: fData,
			processData: false,
			contentType: false,
			beforeSend: (xhr) => {
                Swal.fire({
                    html: 	'<div class="d-flex flex-column align-items-center">'
                    + '<span class="spinner-border text-primary"></span>'
                    + '<h3 class="mt-2">Loading...</h3>'
                    + '<div>',
                    showConfirmButton: false,
                    width: '10rem'
                });
            },
            success: (resp) => { 
                var res = resp; 
								 Swal.fire({
                    type: 'success',
                    title:'<h5 class="text-success text-uppercase">Data Generated</h5>',
                    html: "Success"
                }); 
                 $("#search_result").html(resp);
 
						},
            error: (err) => {
                var response = JSON.parse(err.responseText);
                Swal.fire({
                    type: response.err_status,
                    title: '<h5 class="text-danger text-uppercase">'+response.err_status+'</h5>',
                    html: response.message
                });
                //csrfToken.setAttribute('content', response.token);
            }
		});
 });

});
</script>