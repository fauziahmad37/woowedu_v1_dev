<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-8">
						<h4 class="card-title mb-4">Access Rights By Door</h4>
						<form class="form-horizontal seachByDoor" action="#" method="post" id="form_report_all">
							<div class="row">
								<div class="col-3">
									<div class="form-group">
										<label for="s_door">Door Number</label>
										<input type="text" class="form-control" name="s_door" id="s_door" placeholder="Door Number">
									</div>
								</div>
								<div class="col-3">
									<div class="form-group">
										<label for="s_device">Device Name</label>
										<input type="text" class="form-control" name="s_device" id="s_device" placeholder="Device Name">
									</div>
								</div>
								<div class="col-3">
									<div class="form-group">
										<label for="s_owned">IP Owned Device</label>
										<input type="text" class="form-control" name="s_owned" id="s_owned" placeholder="IP Owned Device">
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
							<table id="tbl_by_door1" class="table table-striped table-sm nowrap">
								<thead class="bg-dark text-white">
									<tr>
										<th width="2%">#</th>
										<th width="10%">Door Name</th>
										<th width="2%">Door Number</th>
										<th width="25%">Device Name</th>
										<th width="25%">Owned Device</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
					<div class="col-4">
						<h4 class="card-title mb-4">Door</h4>
						<div class="table-responsive mt-4">
							<table id="tbl_by_door2" class="table table-striped table-sm nowrap">
								<thead class="bg-dark text-white">
									<tr>
										<th>ID</th>
										<th>Personel Name</th>
										<th>Department Name</th>
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
	var base_url = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];

	var table1 = $("#tbl_by_door1").DataTable({
		dom: 'Bf<"float-right"l>rtip',
		lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
		bFilter: true,
		searching: false,
		processing: true,
		serverSide: true,
		pageLength: 25,
		order: [[ 0, 'desc' ]],
		columnDefs: [
			{
				targets: [0, 1, 2, 3],
				orderable: true
			}
		],
		ajax: {
			url: base_url + '/reports/view_by_door',
			dataType: "json",
			type: "POST",
			data: function(data) {
				data.sDoor		= $('#s_door').val();
				data.sDevice	= $('#s_device').val();
				data.sOwned		= $('#s_owned').val();
			}
		},
		columns: [
			{ "data": "id" },
			{ "data": "door_name" },
			{ "data": "door_number" },
			{ "data": "devicename" },
			{ "data": "owned_device" }
		],
		buttons: {
			buttons: [
				{
					className: 'btn bg-primary btn-sm',
					text: 'Refresh Table &nbsp;&nbsp; <i class="bx bx-check-double"></i>',
					action: function () {
						$(".seachByDoor")[0].reset();
						table1.draw();
					}
				}
			]
		}
	});

	var table2 = $("#tbl_by_door2").DataTable({
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

	$('#tbl_by_door1 tbody').on('click', 'tr', function () {

		var door_id = table1.row($(this).closest('tr')).id();
		var door_name = table1.row(this).data().door_name;
		var blast_name = table1.cell( $(this).closest('tr'), 0 ).data();
		var cv;
		$("#tbl_by_door2").removeClass("hidden");
		
		if(table2 !== null){
			table2.destroy();
		};
			table2 = $("#tbl_by_door2").DataTable({
			dom: 'Bf<"float-right"l>rtip',
			lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
			bFilter: false,
			searching: false,
			processing: true,
			serverSide: true,
			paging: false,
			order: [[ 0, 'desc' ]],
			ajax: {
				url: base_url + '/reports/detail_by_door/' + door_id,
				dataType: "json",
				type: 'POST'
			},
			columns: [
				{ "data": "host_id" },
				{ "data": "host_name" },
				{ "data": "department_name" }
			],
			buttons: {
				buttons: [
					{
						extend: 'pdfHtml5',
						messageTop: 'Door Name : ' + door_name,
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
						messageTop: 'Door Name : ' + door_name,
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
						messageTop: 'Door Name : ' + door_name,
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