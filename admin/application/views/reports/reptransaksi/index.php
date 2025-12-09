<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<form class="form-horizontal searchReportAll" action="#" method="post" id="form_report_all">
					<div class="row"> 
						<div class="col-md-4">
							<div class="form-group">
								<label for="s_start">Start Date</label> 
								<input type="text" class="form-control" placeholder="<?=$start_date?>" id="s_start" name="s_start" data-date-format="dd-mm-yyyy"  data-provide="datepicker"  />
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="s_end">End Date</label>
								<input type="text" class="form-control" placeholder="<?=$end_date?>" id="s_end" name="s_end" data-date-format="dd-mm-yyyy"  data-provide="datepicker"  /> 
							</div>
						</div>	
						<div class="col-md-4">
							<div class="form-group">
								<label for="s_jenis_bayar">Kategori Pembayaran</label>
								<select id="s_jenis_bayar" name="s_jenis_bayar" class="form-control" >
									<option value="">- Semua - </option>
									<?php 
									foreach($jenis_bayar as $key=>$val){?>
									<option value="<?=$key?>"><?=$val?></option> 
									<?php } ?>
								</select>
							</div>
						</div>						
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="s_rombongan">Jenis Rombongan</label>
								<select id="s_rombongan" name="s_rombongan" class="form-control" >
									<option value="">- Semua - </option>
									<?php 
									
									foreach($rombongan as $key=>$val){?>
									<option value="<?=$key?>"><?=$val?></option> 
									<?php } ?>
								</select> 
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="s_user">User</label>
								<select id="s_user" name="s_user" class="form-control" >
									<option value="">- Semua - </option>
									<?php 
									foreach ($recusers as $rec) { 
										?>
									<option value="<?=$rec->userid?>"><?=$rec->username?></option> 
									<?php } ?>
								</select> 
							</div>
						</div>
						

						<!--
						<div class="col-md-4">
							<div class="form-group">
								<label for="s_wahana">Wahana</label>
								<select id="s_wahana" name="s_wahana" class="form-control" >
									<option value="">- Semua - </option>
									<?php  
									foreach($wahana as $rec) {?>
									<option value="<?=$rec->id?>"><?=$rec->nama?></option> 
									<?php } ?>
								</select> 
							</div>
						</div> -->		
					</div>
					<div class="row">
						<div class="col-12">
							<div class="form-group pt-2 mr-2">
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
				<div class="table-responsive mt-4">
					<table id="tbl_reportall" class="table table-striped table-sm nowrap w-100">
						<thead class="bg-dark text-white">
							<tr>
								<th class="bg-dark">#</th>
								<th class="bg-dark">Kode Transaksi</th>
								<th class="bg-dark">Tanggal</th>
								<th class="bg-dark">Metode Bayar</th>
								<th class="bg-dark">No Referensi</th>
								<th class="bg-dark">User</th>
								<th class="bg-dark">Kuantitas</th>
								<th class="bg-dark">Harga Jual</th>
								<th class="bg-dark">Service Fee</th>
								<th class="bg-dark">Total Jual</th>
								<th class="bg-dark">Total Service</th> 
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

	$('.select2').select2();
	$('.select2-no').select2({
		minimumResultsForSearch: 1 / 0
	});
	$('.select2-container').css('width', '100%');

	var today = new Date();
	var dd = String(today.getDate()).padStart(2, '0');
	var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
	var yyyy = today.getFullYear();

	today = mm + '/' + dd + '/' + yyyy;
 
	var getUrl = window.location; 
	var base_url = document.querySelector('base').href;


	var table = $("#tbl_reportall").DataTable({
		dom: 'Bf<"float-right"l>rtip',
		lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
		bFilter: true,
		searching: false,
		processing: true,
		serverSide: true,
		pageLength: 25,
		order: [[ 1, 'desc' ]],
		columnDefs: [
			{
				targets: 0,
				orderable: true
			},
			{
				targets: [4, 5, 7, 8],
				className: 'text-center',
				orderable: true
			},
			{ 
				targets: [6,10],
				className: 'text-center',
				orderable: false
			}
		],
		ajax: {
			url: base_url + '/reports/view_reptransaksi',
			dataType: "json",
			type: "POST",
			data: function(data) {
				data.sStart			= $('#s_start').val();
				data.sEnd			= $('#s_end').val(); 
				data.sRombongan	= $('#s_rombongan').val();
				data.sUser			= $('#s_user').val();
				data.sJenisBayar	= $('#s_jenis_bayar').val(); 
			}
		},
		columns: [ 
			{ 
				"data": "tl_id",  
				render(data, type, row, meta) {
					return meta.row + meta.settings._iDisplayStart + 1;
				}
			},
			{ "data": "no_transaksi" }, 
			{ "data": "tanggal" }, 
			{ "data": "jenis_bayar" },
			{ "data": "no_ref" }, 
			{ "data": "user" },
			{ "data": "kuantitas" },
			{ "data": "harga_jual" }, 
			{ "data": "service_fee" }, 
			{ "data": "total_jual" }, 
			{ "data": "total_fee" } 
		],
		buttons: {
			buttons: [
				{
					extend: 'pdfHtml5',
					exportOptions: {
						columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9,10]
					},
					className: 'btn btn-danger btn-sm',
					text: 'Export to PDF &nbsp;&nbsp; <i class="bx bxs-file-pdf"></i>', 
					orientation: 'landscape',
					pageSize: 'A4'
				},
				{
					extend: 'excel',
					exportOptions: {
						columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9,10]
					},
					className: 'btn btn-success btn-sm',
					text: 'Export to Excell &nbsp;&nbsp; <i class="bx bx-file"></i>',
					orientation: 'landscape',
					pageSize: 'A4'
				}, 
				{
					className: 'btn bg-primary btn-sm',
					text: 'Refresh Table &nbsp;&nbsp; <i class="bx bx-check-double"></i>',
					action: function () {
						$(".searchReportAll")[0].reset();
						table.draw();
					}
				}
			]
		}
	});

	$('#search_button').click(function(){
		table.draw();
	});

 

});
</script>