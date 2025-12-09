<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<form class="form-horizontal searchReportToday" action="#" method="post" id="form_report_today">
					<div class="row">
						<div class="col-2">
							<div class="form-group">
								<label for="s_event_id">Event ID</label>
								<input type="text" class="form-control" name="s_event_id" id="s_event_id" placeholder="Event ID">
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label for="s_event_point">Event Point</label>
								<input type="text" class="form-control" name="s_event_point" id="s_event_point" placeholder="Event Point">
							</div>
						</div>
						<div class="col-2">
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
								<label for="s_card">Card Number</label>
								<input type="text" class="form-control" name="s_card" id="s_card" placeholder="Card Number">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-3">
							<div class="form-group">
								<label for="s_reader">Reader Name</label>
								<input type="text" class="form-control" name="s_reader" id="s_reader" placeholder="Reader Name">
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label for="s_device_name">Device Name</label>
								<input type="text" class="form-control" name="s_device_name" id="s_device_name" placeholder="Device Name">
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label for="s_department_id">Department Number</label>
								<input type="text" class="form-control" name="s_department_id" id="s_department_id" placeholder="Department Number">
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<label for="s_department">Department Name</label>
								<input type="text" class="form-control" name="s_department" id="s_department" placeholder="Department Name">
							</div>
						</div>
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
					<table id="tbl_reportall" class="table table-striped table-sm nowrap">
						<thead class="bg-dark text-white">
							<tr>
								<th>#</th>
								<th>Time</th>
								<th>Area Name</th>
								<th>Device</th>
								<th>Event Point</th>
								<th>ID</th>
								<th>Name</th>
								<th>Card Number</th>
								<th>Dept. Number</th>
								<th>Dept. Name</th>
								<th>Foto</th>
								<th>Act.</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view("reports/all-transactions/modal.php") ?>

<script>
$(document).ready(function() {

	$('.select2').select2();
	$('.select2-no').select2({
		minimumResultsForSearch: 1 / 0
	});
	$('.select2-container').css('width', '100%');

	var getUrl = window.location;
	//var base_url = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
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
				targets: [10, 11],
				className: 'text-center',
				orderable: false
			}
		],
		ajax: {
			url: base_url + '/reports/view_today_transactions',
			dataType: "json",
			type: "POST",
			data: function(data) {
				data.sEventId		= $('#s_event_id').val();
				data.sEventPoint	= $('#s_event_point').val();
				data.sCard			= $('#s_card').val();
				data.sPersonelId	= $('#s_personel_id').val();
				data.sPersonel		= $('#s_personel_name').val();
				data.sDevice		= $('#s_device_name').val();
				data.sDepartmentId	= $('#s_department_id').val();
				data.sDeparment		= $('#s_department').val();
			}
		},
		columns: [
			// { "data": "tl_id","sortable": false, 
			// 	render: function (data, type, row, meta) {
			// 		return meta.row + meta.settings._iDisplayStart + 1;
			// 		// return '';
			// 	}
			// },
			{ "data": "tl_id" },
			{ "data": "tl_time" },
			{ "data": "area_name", visible: false },
			{ "data": "device_name" },
			{ "data": "event_point" },
			{ "data": "host_id" },
			{ "data": "host_name" },
			{ "data": "tl_cardno" },
			{ "data": "department_id" },
			{ "data": "department_name" },
			{ "data": "tl_photo" },
			{ data: null, render: function ( data, type, row, meta) {
				var _option = '<div class="btn-group btn-group-sm float-right" role="group" aria-label="Basic example">';
				_option += '<button class="btn btn-info view_data" data-id="'+ data.tl_id +'" type="button"><i class="bx bx-id-card font-size-12"></i></button>';
				_option += '</div>';		
				return _option;
				} 
			}
		],
		buttons: {
			buttons: [
				{
					extend: 'pdfHtml5',
					exportOptions: {
						columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
					},
					className: 'btn btn-danger btn-sm',
					text: 'Export to PDF &nbsp;&nbsp; <i class="bx bxs-file-pdf"></i>',
					// orientation: 'potrait',
					orientation: 'landscape',
					pageSize: 'A4'
				},
				{
					extend: 'excel',
					exportOptions: {
						columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
					},
					className: 'btn btn-success btn-sm',
					text: 'Export to Excell &nbsp;&nbsp; <i class="bx bx-file"></i>',
					orientation: 'landscape',
					pageSize: 'A4'
				},
				{
					extend: 'print',
					exportOptions: {
						columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
					},
					className: 'btn btn-info btn-sm',
					text: 'Print &nbsp;&nbsp; <i class="bx bx-printer"></i>',
					orientation: 'potrait',
					pageSize: 'A4'
				},
				{
					className: 'btn bg-primary btn-sm',
					text: 'Refresh Table &nbsp;&nbsp; <i class="bx bx-check-double"></i>',
					action: function () {
						$(".searchReportToday")[0].reset();
						table.draw();
					}
				}
			]
		}
	});

	$('#search_button').click(function(){
		table.draw();
	});

	$('#tbl_reportall tbody').on('click', 'button.view_data', table, function () {
		var getDetail = $(this).data('id');
		$.ajax({
			url: base_url + '/reports/detail_all_reports',
			type: "POST",
			data: {id: getDetail},
			success: function(data){
				$('#modaldata').html(data);
				$('#detailData').modal('show');
			}
		});
	});

});
</script>