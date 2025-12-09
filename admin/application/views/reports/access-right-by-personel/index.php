<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-8">
						<h4 class="card-title mb-4">Access Rights By Personel</h4>
						<form class="form-horizontal seachByPersonel" action="#" method="post" id="form_report_all">
							<div class="row">
								<div class="col-3">
									<div class="form-group">
										<label for="s_personel_id">Personel ID</label>
										<input type="text" class="form-control" name="s_personel_id" id="s_personel_id" placeholder="Personel ID">
									</div>
								</div>
								<div class="col-3">
									<div class="form-group">
										<label for="s_personel_name">Personel Name</label>
										<input type="text" class="form-control" name="s_personel_name" id="s_personel_name" placeholder="Personel Name">
									</div>
								</div>
								<div class="col-3">
									<div class="form-group">
										<label for="s_department_name">Department Name</label>
										<input type="text" class="form-control" name="s_department_name" id="s_department_name" placeholder="Department Name">
									</div>
								</div>
								<div class="col-3">
									<div class="form-group pt-4">
										<button type="button" class="btn btn-block btn-primary waves-effect waves-light" id="search_button">
											<i class="bx bx-search font-size-16 align-middle mr-2"></i>
											Search
										</button>
									</div>
								</div>
							</div>
						</form>

						<div class="table-responsive mt-4">
							<table id="tbl_by_person1" class="table table-striped table-sm nowrap">
								<thead class="bg-dark text-white">
									<tr>
										<th width="10%">ID</th>
										<th>Personel Name</th>
										<th width="25%">Department Name</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
					<div class="col-4">
						<h4 class="card-title mb-4">Door</h4>
						<div class="table-responsive mt-4">
							<table id="tbl_by_person2" class="table table-striped table-sm nowrap">
								<thead class="bg-dark text-white">
									<tr>
										<th>Door Number</th>
										<th>Door Name</th>
										<th>Personel Name</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
$(document).ready(function() {

	$('.select2').select2();
	$('.select2-container').css('width', '100%');

	var getUrl = window.location;
	//var base_url = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
	var base_url = document.querySelector('base').href;

	var table1 = $("#tbl_by_person1").DataTable({
		dom: 'f<"float-right"l>rtip',
		lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
		bFilter: true,
		searching: false,
		processing: true,
		serverSide: true,
		pageLength: 25,
		order: [[ 0, 'desc' ]],
		columnDefs: [
			{
				targets: [0, 1, 2],
				orderable: true
			}
		],
		ajax: {
			url: base_url + '/reports/view_by_person',
			dataType: "json",
			type: "POST",
			data: function(data) {
				data.sPersonelId	= $('#s_personel_id').val();
				data.sPersonel		= $('#s_personel_name').val();
				data.sDepartment	= $('#s_department_name').val();
			}
		},
		columns: [
			{ "data": "host_id" },
			{ "data": "host_name" },
			{ "data": "department_name" }
		],
		buttons: {
			buttons: [
				{
					className: 'btn bg-primary btn-sm',
					text: 'Refresh Table &nbsp;&nbsp; <i class="bx bx-check-double"></i>',
					action: function () {
						$(".seachByPersonel")[0].reset();
						table1.draw();
					}
				}
			]
		}
	});

	var table2 = $("#tbl_by_person2").DataTable({
		// dom: 'Bf<"float-right"l>rtip',
		// lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
		bFilter: true,
		searching: false,
		pageLength: 25,
		order: [[ 0, 'desc' ]],
		columnDefs: [
			{
				targets: [0, 1, 2],
				orderable: true
			}
		]
	});

	// $('#tbl_by_person1 tbody').on('click', 'tr', function () {
	// 	var tr = $(this).closest("tr");
	// 	var row = table1.row(tr);
		
	// 	var data = JSON.parse(JSON.stringify(row.data()));

	// 	// this actually destroys the row so you can't add it to the other table.
	// 	row.remove().draw();

	// 	// then add and draw.
	// 	table2.row.add(data).draw();
	// });

	$('#tbl_by_person1 tbody').on('click', 'tr', function () {

		var personel_id = table1.row($(this).closest('tr')).id();
		var nama_personel = table1.row(this).data().host_name;
		var blast_name = table1.cell( $(this).closest('tr'), 0 ).data();
		var cv;
		$("#tbl_by_person2").removeClass("hidden");
		
		if(table2 !== null){
			table2.destroy();
		};
			table2 = $("#tbl_by_person2").DataTable({
			dom: 'Bf<"float-right"l>rtip',
			lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
			bFilter: false,
			searching: false,
			processing: true,
			serverSide: true,
			paging: false,
			ajax: {
				url: base_url + '/reports/detail_by_person/' + personel_id,
				dataType: "json",
				type: 'POST'
			},
			columns: [
				{ "data": "id" },
				{ "data": "door_name" },
				{ "data": "host_name" }
			],
			buttons: {
				buttons: [
					{
						extend: 'pdfHtml5',
						messageTop: 'Nama Personel : ' + nama_personel,
						exportOptions: {
							columns: [0, 1, 2]
						},
						className: 'btn btn-danger btn-sm',
						text: 'Export to PDF &nbsp;&nbsp; <i class="bx bxs-file-pdf"></i>',
						// orientation: 'potrait',
						orientation: 'landscape',
						pageSize: 'A4'
					},
					{
						extend: 'excel',
						messageTop: 'Nama Personel : ' + nama_personel,
						exportOptions: {
							columns: [0, 1, 2]
						},
						className: 'btn btn-success btn-sm',
						text: 'Export to Excell &nbsp;&nbsp; <i class="bx bx-file"></i>',
						orientation: 'landscape',
						pageSize: 'A4'
					},
					{
						extend: 'print',
						messageTop: 'Nama Personel : ' + nama_personel,
						exportOptions: {
							columns: [0, 1, 2]
						},
						className: 'btn btn-info btn-sm',
						text: 'Print &nbsp;&nbsp; <i class="bx bx-printer"></i>',
						orientation: 'potrait',
						pageSize: 'A4'
					}
				]
			}
		});
	});

	$('#search_button').click(function(){
		table1.draw();
	});

});
</script>