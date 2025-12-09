<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="<?=base_url('assets/node_modules/daterangepicker/daterangepicker.css')?>" />

<style>
	.fc-event-title {
		inline-size: 300px;
		overflow-wrap: break-word;
	}

	.fc-daygrid-dot-event .fc-event-title{
		overflow-x: auto !important;
	}
</style>

<?php 
	$user_level = $this->session->userdata('user_level');
?>

<section class="explore-section section-padding" id="section_2">
 


	<!-- laporan kinerja siswa -->
	<div class="container p-4">
		<div class="row border rounded">
			<h6 class="text-center mt-4">Table Waktu Saya</h6>
 
 
				
				<div class="tab-pane fade show active" id="nav-sesi" role="tabpanel" aria-labelledby="nav-sesi-tab" tabindex="0">
					<div class="container p-3 border rounded" style="height: 100%;">  
								<div id="calendar" class="mb-3"></div> 
					</div>
				</div>
	 
		</div>
	</div>
</section> 

 
<script type="text/javascript" src="<?=base_url('assets/node_modules/moment/moment.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/node_modules/daterangepicker/daterangepicker.js')?>"></script>
<script src="<?=base_url('assets/fullcalendar/index.global.js')?>"></script> 
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
var currentPage = 1; 
let startDate 	= moment().startOf('month').startOf('day').format('YYYY-MM-DD');
let endDate		= moment().startOf('day').format('YYYY-MM-DD');
 
$(document).ready(function () {
  
	var calendarEl = document.getElementById('calendar');
	var calendar = new FullCalendar.Calendar(calendarEl, {
		height:500,
		headerToolbar: { left: 'dayGridMonth', center: 'title' }, // buttons for switching between views
		views: {
				dayGridMonth: {  }
		},

		initialView: 'dayGridMonth',
		initialDate: moment().format('YYYY-MM-DD'),
		navLinks: true, // can click day/week names to navigate views
		editable: true,
		dayMaxEvents: true, // allow "more" link when too many events
		events: {
				url: BASE_URL + 'teacher/loadtimeline',
				error: function() {
					$('#script-warning').show();
				}
			},
		eventDidMount: function(info) {
				let title = info.el.children[2].innerText;
				let teacher = info.event._def.extendedProps.teacher;
				let subject_name = info.event._def.extendedProps.subject_name;
				let sesi_note = info.event._def.extendedProps.sesi_note;
				let start_time = info.event._def.extendedProps.start_time; 
				let end_time = info.event._def.extendedProps.end_time; 
				info.el.children[2].innerHTML = (`<p class="fs-14">${title} (${start_time} - ${end_time})</p> 
					<span>Mata Pelajaran: 
						<span style="background-color: #3989d9; color: white; padding-left: 5px; padding-right: 5px; border-radius: 10px; box-shadow: 3px 3px 5px lightblue;">
							${subject_name}
						</span>
					</span>
					<br><span class="sesi-note">${sesi_note}</span>`);
			},  
	});

	calendar.render();
});
 
 


</script>
