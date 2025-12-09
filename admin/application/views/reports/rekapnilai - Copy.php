<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<form class="form-horizontal searchReportAll" action="#" method="post" id="form_report_all">
					<div class="row"> 
						<div class="col-md-4">
							<div class="form-group">
								<label for="s_start">Tanggal Mulai</label> 
								<input type="text" class="form-control" placeholder="<?=$start_date?>" id="s_start" name="s_start" data-date-format="dd-mm-yyyy"  data-provide="datepicker"  />
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="s_end">Tanggal Akhir</label>
								<input type="text" class="form-control" placeholder="<?=$end_date?>" id="s_end" name="s_end" data-date-format="dd-mm-yyyy"  data-provide="datepicker"  /> 
							</div>
						</div>	 					
					</div>
					<div class="row">
						<div class="col-12">
							<div class="form-group pt-2 mr-2">
								<div class="button-items float-right">
									<button type="button" class="btn btn-block btn-primary waves-effect waves-light" id="search_button">
										<i class="bx bx-search font-size-16 align-middle mr-2"></i>
										Cari
									</button>
								</div>
							</div>
						</div>
					</div>
				</form>
				<hr>
				<div class="table-responsive mt-4">
					<table id="tbl_rekapnilai" class="table table-striped table-sm nowrap w-100">
						<thead class="bg-dark text-white">
							<tr>
								<th class="bg-dark">#</th>
								<th class="bg-dark">NIS</th>
								<th class="bg-dark">Nama</th>
								<th class="bg-dark">Kelas</th> 
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
 
<script>
$(document).ready(function() {

	var getUrl = window.location; 
	var base_url = document.querySelector('base').href;
	var table = $("#tbl_rekapnilai").DataTable({
		dom: 'Bf<"float-right"l>rtip',
		lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
		bFilter: true,
		searching: false,
		processing: true,
		serverSide: true,
		pageLength: 25, 
		ajax: {
			url: base_url + '/reports/view_rekap_nilai',
			dataType: "json",
			type: "POST",
			data: function(data) {
				data.sStart			= $('#s_start').val();
				data.sEnd			= $('#s_end').val();  
			}
		},
		columns: [ 
			{ 
				"data": "student_id",   
                visible: false 
			},
			{ "data": "nis" }, 
			{ "data": "student_name" }, 
			{ "data": "class_id" }
		] 
	});

	$('#search_button').click(function(){
		table.draw();
	});

 

});
</script>