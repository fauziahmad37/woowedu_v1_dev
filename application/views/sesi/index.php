<style>
	@media screen and (max-width: 576px) {
		form[name="frm-filter"]{
			display: flex;
		}

		form[name="frm-filter"] div:nth-child(1) {
			width: 75%;
		}
	
		form[name="frm-filter"] div:nth-child(2) {
			width: 25%;
		}
	}

	@media screen and (max-width: 375px) {
		form[name="frm-filter"]{
			display: flex;
		}

		form[name="frm-filter"] div:nth-child(1) {
			width: 70%;
		}
	
		form[name="frm-filter"] div:nth-child(2) {
			width: 30%;
		}
	}
	
	@media screen and (max-width: 425px){
		#buat-sesi {
			float: right;
		}
	}
	
</style>

<section class="explore-section section-padding" id="section_2">
<!-- <div class="container"> -->

	<h4>Jadwal Sesi</h4>

	 
	<div class="row">
		<div class="col-xl-8 col-lg-8 col-md-6 col-sm-12 col-xs-12"></div>
		<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12"> 
			<a href="<?=base_url()?>sesi/create" class="btn btn-light btn-outline-primary border-2 rounded-pill fw-semibold" id="buat-sesi" style>+ Buat Sesi</a>
		</div>
	</div>

	<!-- content -->
	<div class="row" style="padding-top:20px">  
		<script src="<?=base_url('assets/fullcalendar/index.global.js')?>"></script> 
			<div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-xs-12 mb-3">
		    	<div id="calendar" class="col"></div>
			</div>
			<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-xs-12" id="sesi_content">
				
			</div>
	</div>
		
 
	
</section>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
<script>
$(document).ready(function() {
	var calendarEl = document.getElementById('calendar');
 

    var calendar = new FullCalendar.Calendar(calendarEl, {

		headerToolbar: {
			left: 'prev,next today',
			center: 'title',
			right: 'listDay,listWeek'
		},
		locale: 'id',
		buttonText: {
			today: 'Hari Ini',
			day: 'Hari',
			week:'Minggu',
			month:'Bulan'
		},
		// customize the button names,
		// otherwise they'd all just say "list"
		views: {
			listDay: { buttonText: 'list day' },
			listWeek: { buttonText: 'list week' }
		},
		initialView: 'listWeek',
		initialDate: '<?=date('Y-m-d')?>',
		navLinks: true, // can click day/week names to navigate views
		editable: true,
		dayMaxEvents: true, // allow "more" link when too many events
		events: {
			url: BASE_URL+'sesi/loaddata', 
			error: function() {
				$('#script-warning').show();
			},
		},		 
		eventClick: function(info) {
			var eventObj = info.event;
			$('#sesi_content').load('<?php echo base_url(); ?>sesi/sesidetail/'+eventObj.id);
				
			/*
			var ev = calEvent.className;
			var _eurl = '<?php // echo base_url(); ?>calendar/detail?id='+calEvent.id+'';			
			$('#calendar_detail').load(_eurl);
			$("#calendar_detail").show();*/

		},
		loading: function(bool) {
			$('#loading').toggle(bool);
		}
    });

    calendar.render();
});


	function deleteSesi(id){
		Swal.fire({
			title: 'Anda yakin untuk menghapus?',
			text: "Hapus data sesi!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, hapus!'
		}).then((result) => {
			console.log(result.value)
			if (result.value) {

				$.ajax({
					type: "POST",
					url: BASE_URL+"sesi/delete",
					data: {
						id: id, 
						csrf_token_name: '<?=$this->security->get_csrf_hash();?>'
					},
					dataType: "JSON",
					success: function (response) {
						if(response.success == true){
							Swal.fire('Deleted!', response.message, 'success');
							window.location.href = BASE_URL+'sesi';
						}
					}
				});

				
			}
		})
	}
</script>
