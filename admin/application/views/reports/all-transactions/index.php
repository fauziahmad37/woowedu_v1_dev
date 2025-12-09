<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<form class="form-horizontal searchReportAll" action="#" method="post" id="form_report_all">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="raw_date">Date Range</label>
								<div class="input-daterange input-group" data-provide="datepicker">
									<input type="text" class="form-control" placeholder="Time From" name="s_start" id="s_start" data-date-format="dd-mm-yyyy" />
									<input type="text" class="form-control" placeholder="To" name="s_end" id="s_end" data-date-format="dd-mm-yyyy" />
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="s_event_id">Event ID</label>
								<input type="text" class="form-control" name="s_event_id" id="s_event_id" placeholder="Event ID">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="s_event_point">Event Point</label>
								<input type="text" class="form-control" name="s_event_point" id="s_event_point" placeholder="Event Point">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="s_personel_id">Personel ID</label>
								<input type="text" class="form-control" name="s_personel_id" id="s_personel_id" placeholder="Personel ID">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="s_card">Card Number</label>
								<input type="text" class="form-control" name="s_card" id="s_card" placeholder="Card Number">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label for="s_personel_name">Personel Name</label>
								<input type="text" class="form-control" name="s_personel_name" id="s_personel_name" placeholder="Personel Name">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="s_device_name">Device Name</label>
								<input type="text" class="form-control" name="s_device_name" id="s_device_name" placeholder="Device Name">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="s_department_id">Department Number</label>
								<input type="text" class="form-control" name="s_department_id" id="s_department_id" placeholder="Department Number">
							</div>
						</div>
						<div class="col-md-3">
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
					<table id="tbl_reportall" class="table table-striped table-sm nowrap w-100">
						<thead class="bg-dark text-white">
							<tr>
								<th class="bg-dark">#</th>
								<th class="bg-dark">Time</th>
								<th class="bg-dark">Area Name</th>
								<th class="bg-dark">SN</th>
								<th class="bg-dark">Device</th>
								<th class="bg-dark">Person ID</th>
								<th class="bg-dark">Name</th>
								<th class="bg-dark">Event Point</th>
								<th class="bg-dark">Event Type</th>
								<th class="bg-dark">Card Number</th>
								<th class="bg-dark">Dept. Number</th>
								<th class="bg-dark">Dept. Name</th>
								<th class="bg-dark">Foto</th>
								<th class="bg-dark">Act.</th>
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

	var today = new Date();
	var dd = String(today.getDate()).padStart(2, '0');
	var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
	var yyyy = today.getFullYear();

	today = mm + '/' + dd + '/' + yyyy;

	// $("#picker").datepicker({
	// 	datesDisabled:[today]
	// });

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
			url: base_url + '/reports/view_all_transactions',
			dataType: "json",
			type: "POST",
			data: function(data) {
				data.sStart			= $('#s_start').val();
				data.sEnd			= $('#s_end').val();
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
			{ 
				"data": "tl_id", 
				//visible: false,
				render(data, type, row, meta) {
					return meta.row + meta.settings._iDisplayStart + 1;
				}
			},
			{ "data": "tl_time" },
			{ 
				"data": "area_name", 
				visible: false	
			},
			{ 
				"data": "sn",
				visible: false
			},
			{ 
				"data": "devicename"
			},
			{ "data": "host_id" },
			{ "data": "host_name" },
			{ 
				"data": "event_point",
				visible: false
			},
			{ 
				"data": "desk_events",
			},
			{ "data": "tl_cardno" },
			{ "data": "department_code" },
			{ "data": "department_name" },
			{ 
				"data": "tl_photo",
				render(data, type, row, _meta) {
					console.log(data);
					var $img = '<img class="rounded-circle avatar-xs m-1" src="'+data+'" />';
					return $img;
				}
			},
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