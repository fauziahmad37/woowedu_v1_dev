var currentPage = 1;
var student_id 	= $('#student_id').val();
let teacherId 	= $('#teacher_id').val();
var class_id 	= $('#class_id').val();
var classLevel 	= $('#class_level').val();
let startDate 	= moment().startOf('month').startOf('day').format('YYYY-MM-DD');
let endDate		= moment().startOf('day').format('YYYY-MM-DD');
let sekolah_id 	= $('#sekolah_id').val();
let userLevel 	= $('#user_level').val();

// const isMobile = navigator.userAgentData.mobile;

// ############################## COMBO BOX TAMPILAN MOBILE ##############################

let tabPane = document.querySelectorAll('div.tab-pane');
if(!isUserUsingMobile()){
	$('.select-group').addClass('d-none');
}

if(isUserUsingMobile()){
	$('.nav-tabs').addClass('d-none');
}

$('#select-menu').on('change', e => {
	$.each(tabPane, function (i, val) { 
		$(val).removeClass('show active');
		if (val.attributes.id.value == e.target.value) $(val).addClass('show active');
	});
});

// #######################################################################################

getSummary(student_id, startDate, endDate); 

// ######################## PILIH MURID ########################
$('#a_select_student').on('change', function(e){
	student_id = e.currentTarget.value;
	window.location.href = BASE_URL+'student/detail/'+student_id;
});


// ================================ Start Search Materi Guru ================================
	let url;
	if(student_id){
		url = ADMIN_URL+`api/subject/getAll?sekolah_id=${sekolah_id}&class_level_id=${classLevel}`;
	}else{
		url = ADMIN_URL+`api/subject/getAll?sekolah_id=${sekolah_id}`;
	}

	$.ajax({
		type: "GET",
		url: url,
		success: function (response) {
			let res = JSON.parse(response);
			const materi = [...res.data].map(x => ({ id: x.id_mapel, text: x.nama_mapel }));

			$('#s_materi_guru_mapel').select2({
				theme: "bootstrap-5",
				data: materi,
				placeholder: 'Pilih Mapel',
				allowClear: true,
			});
			$('#s_materi_guru_mapel').val(null).trigger('change');
		}
	});

	$.ajax({
		type: "GET",
		url: `${BASE_URL}teacher/getAll`,
		success: function (response) {
			let res = JSON.parse(response);
			const teacher = [...res.data].map(x => ({ id: x.teacher_id, text: x.teacher_name }));

			$('#s_materi_guru_nama_guru').select2({
				theme: "bootstrap-5",
				data: teacher,
				placeholder: 'Pilih Guru',
				allowClear: true,
			});
			$('#s_materi_guru_nama_guru').val(null).trigger('change');
		}
	});

	$('#cari-materi').on('click', function(){
		let teacherId = $('#s_materi_guru_nama_guru').val();
		teacherId = (teacherId != null) ? teacherId : '';

		let mapelId = $('#s_materi_guru_mapel').val();
		mapelId = (mapelId != null) ? mapelId : '';

		tableMateriGuru.columns(1).search(teacherId).draw();
		tableMateriGuru.columns(2).search(mapelId).draw();
	});

// ================================ End Search Materi Guru ================================

$(document).ready(function () {
	$('input[name="daterange"]').daterangepicker({
		opens: 'right',
		minYear: 2000,
		maxYear: 2025,
		showDropdowns: true,
		ranges: {
				'Today': [moment().startOf('day'), moment().endOf('day')],
				'Yesterday': [moment().subtract(1, 'days').startOf('day'), moment().subtract(1, 'days').endOf('day')],
				'Last 7 Days': [moment().subtract(6, 'days').startOf('day'), moment().endOf('day')],
				'This Month': [moment().startOf('month').startOf('day'), moment().endOf('month').endOf('day')],
			}
		}, function(start, end, label) {
			console.log(start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));

			getSummary(student_id, start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
		}
	);

	// ###################################################################################################
	// ########################################## SESI CALENDAR ##########################################
	// ###################################################################################################

	var calendarEl = document.getElementById('calendar');
	var calendar = new FullCalendar.Calendar(calendarEl, {
		locale: 'id',
		buttonText: {
			today: 'Hari Ini',
			day: 'Hari',
			week:'Minggu',
			month:'Bulan'
		},
		height:500,
		headerToolbar: { left: 'dayGridMonth', center: 'title' }, // buttons for switching between views

		// customize the button names,
		// otherwise they'd all just say "list"
		views: {
				dayGridMonth: { // name of view
					// titleFormat: { year: 'numeric', month: '2-digit', day: '2-digit' }
					// other view-specific options here
			}
		},

		initialView: 'dayGridMonth',
		initialDate: moment().format('YYYY-MM-DD'),
		navLinks: true, // can click day/week names to navigate views
		editable: true,
		dayMaxEvents: true, // allow "more" link when too many events
		events: {
				url: BASE_URL + 'student/sesi_load_data?student_id='+student_id,
				error: function() {
					$('#script-warning').show();
				}
			},
		eventTimeFormat: { // like '14:30:00'
				hour: '2-digit',
				minute: '2-digit',
				meridiem: false
			},
		eventDidMount: function(info) {
				let title = info.el.children[2].innerText;
				let teacher = info.event._def.extendedProps.teacher;
				let subject_name = info.event._def.extendedProps.subject_name;
				let sesi_note = info.event._def.extendedProps.sesi_note;
				let sesiId = info.event._def.publicId;
				info.el.children[2].innerHTML = (`<a class="text-decoration-none" href="${BASE_URL+'sesi/lihat_sesi/'+sesiId}"><p class="fs-14">${title}</p>
						<p class="text-success fw-bold fs-14">Guru: ${teacher}</p>
						<span>Mata Pelajaran: 
							<span style="background-color: #3989d9; color: white; padding-left: 5px; padding-right: 5px; border-radius: 10px; box-shadow: 3px 3px 5px lightblue;">
								${subject_name}
							</span>
						</span>
						<br><span class="sesi-note">${sesi_note}</span>
					</a>`);
			},
		eventClick: function(info) {
				var eventObj = info.event;
				$('#sesi_content').load(BASE_URL + 'sesi/sesidetail/' + eventObj.id);
			},
		loading: function(bool) {
				$('#loading').toggle(bool);
			}
	});

	calendar.render();
});

// ######################################### FUNGSI UNTUK UBAH DATA CONTENT SUMMARY #########################################

function getSummary(student_id, start, end){
	$.ajax({
		type: "POST",
		url: BASE_URL+"student/get_summary",
		data: {
			student_id, student_id
		},
		dataType: "JSON",
		success: function (response) {
			$('.total-tugas').html(response.total_task);
			$('.total-tugas-dikerjakan').html(response.total_task_submit);
		}
	});
}

// ###########################################################################################
// ################################## DATA TABLE MATERI GURU #################################
// ###########################################################################################

var tableMateriGuru = $('#tableMateriGuru').DataTable({
	"oLanguage": {
		"sUrl": "https://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json"
	},
	"pagingType": "simple_numbers",
	"language": {
		"paginate": {
		"first":    "«",
		"previous": "‹",
		"next":     "›",
		"last":     "»"
		}
	},
	bInfo: (isUserUsingMobile()) ? false : true,
	ordering: false,
	serverSide: true,
	ajax: {
		url: BASE_URL + 'student/get_materi_kelas',
		method: 'GET',
		data: {
			student_id: student_id
		}
	},
	select: { 
		style:	'multi',  
		selector: 'td:first-child'
	},
	columns: [
		{
			data: 'teacher_name',
		},
		{
			data: 'subject_name',
		},
		{
			data: 'title',
		},
		{
			data: 'edit_at',
			render(data, type, row, meta){
				return moment(data).format('DD MMM YYYY, HH:mm');
			}
		},
		{
			data: 'type',
			class: 'text-center',
			render(data, type, row, meta){
				let button = '';
				if(row.type == 'link'){
					button += `<a href="${row.materi_file}" target="_blank"><img src="./assets/themes/space/icons/link-45deg.svg"></a>`;
				}

				if(row.type == 'file'){
					button += `<a href="${BASE_URL + `assets/files/materi/` + row.materi_file}" target="_blank">
									<img src="./assets/themes/space/icons/file-text-fill.svg">
								</a>`;
				}
				return button;
			}
		}
	]
}).columns(0).search(class_id).draw();
// tableMateriGuru.columns(1).search(student_id).draw();



// ###########################################################################################
// ################################## DATA TABLE LIST TUGAS ##################################
// ###########################################################################################

var tableTask = $('#tableTask').DataTable({
		"oLanguage": {
			"sUrl": "https://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json"
		},
		"pagingType": "simple_numbers",
		"language": {
			"paginate": {
			"first":    "«",
			"previous": "‹",
			"next":     "›",
			"last":     "»"
			}
		},
		bInfo: (isUserUsingMobile()) ? false : true,
		ordering: false,
		serverSide: true,
		ajax: {
			url: BASE_URL + 'student/get_task',
			method: 'GET',
			data: {
				student_id: student_id
			}
		},
		select: {
			style:	'multi',  
			selector: 'td:first-child'
		},
		columns: [
			{
				data: 'task_id',
				visible: false
			},
			{
				data: 'code',
				visible: false
			},
			{
				data: 'subject_name',
			},
			{
				data: 'teacher_name',
			},
			{
				data: 'available_date',
				render(data, row, type, meta) {
					return moment(data).format('DD MMM YYYY, HH:mm');
				}
			},
			{
				data: 'due_date',
				render(data, row, type, meta){
					return moment(data).format('DD MMM YYYY, HH:mm');
				}
			},
			{
				data: 'task_submit',
				class: 'text-center',
				render(data, row, type, meta){
					return (data) ? moment(data).format('DD MMM YYYY, HH:mm') : `<div class="badge bg-danger-subtle text-danger">Belum Dikerjakan</div>`;
				}
			},
			{
				data: 'task_file_answer',
				class: 'text-center',
				render(data, row, type, meta){
					return (data) ? `<a href="${BASE_URL+'assets/files/student_task/'+class_id+'/'+data}"><i class="bi bi-file-text-fill fs-20"></i></a>` : `-`;
				}
			},
			{
				data: null,
				class: 'text-center',
				render(data, type, row, meta){
					let status = '';
					if(!row.task_submit && !row.task_nilai){
						status += '-';
					}else if(row.task_submit && row.task_nilai){
						status += '<span class="badge bg-success-subtle text-success">Sudah Dinilai Guru</span>';
					}else if(row.task_submit && !row.task_nilai){
						status += '<span class="badge bg-danger-subtle text-danger">Menunggu Penilaian</span>';
					}
					return status;
				}
			},
			{
				data: 'task_nilai',
				class: 'text-center',
				render(data, row, type, meta){
					return `<b>${(data) ? data : '-'}</b>`;
				}
			},
			{
				data: null,
				class: 'text-center',
				render(data, type, row, meta) {
					let btnEdit = `<a href="${BASE_URL+'task/detail/'+row.task_id}" class="btn btn-primary edit_subject rounded-5"><i class="bi bi-pencil-square text-white"></i></a>`;
					let btnView = `<a href="${BASE_URL+'task/detail/'+row.task_id}" class="btn btn-primary view_subject rounded-5"><i class="bi bi-eye text-white"></i></a>`;
					
					let endDt = new Date(row.due_date);	
					let now = new Date();
					
					let container = `<div class="btn-group btn-group-sm float-right">
									${(!row.task_submit && userLevel == 4 && moment().format('Y-MM-D H:m:s') >= row.available_date && moment().format('Y-MM-D H:m:s') <= row.due_date ) ? btnEdit : ''}
									${(row.task_submit) ? btnView : ''}
								</div>`;
					return container;
				}
			}
		]
	});


// ##########################################################################################
// ################################## DATA TABLE LIST EXAM ##################################
// ##########################################################################################


var tableExam = $('#tableExam').DataTable({
	"oLanguage": {
		"sUrl": "https://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json"
	},
	"oLanguage": {
		"sUrl": "https://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json"
	},
	"pagingType": "simple_numbers",
	"language": {
		"paginate": {
		"first":    "«",
		"previous": "‹",
		"next":     "›",
		"last":     "»"
		}
	},
	bInfo: (isUserUsingMobile()) ? false : true,
	ordering: false,
	serverSide: true,
	ajax: {
		url: BASE_URL + 'student/get_exam',
		method: 'GET',
		data: {
			student_id: student_id
		}
	},
	select: {
		style: 'multi',
		selector: 'td:first-child',
	},
	columns: [
		{
			data: 'exam_id',
			visible: false
		},
		{
			data: 'title',
		},
		{
			data: 'subject_name',
		},
		{
			data: 'teacher_name',
		},
		{
			data: 'end_date',
			class: 'text-center',
			render(data, row, type, meta){
				return moment(data).format('DD MMM YYYY, HH:mm');
			}
		},
		{
			data: 'exam_submit',
			class: 'text-center',
			render(data, row, type, meta){
				return (data) ? moment(data).format('DD MMM YYYY, HH:mm') : `<span class="badge text-danger bg-danger-subtle">Belum Dikerjakan</span>`;
			}
		},
		{
			data: null,
			render(data, type, row, meta){
				let status;
				// jika exam_submit nya ada dan exam_total_nilai nya null maka status nya menunggu penilaian
				if(row.exam_submit && !row.exam_total_nilai) {
					status = `<span class="badge text-danger bg-danger-subtle">Menunggu penilaian</span>`
				// jika tidak ada exam_submit
				}else if(row.exam_submit && row.exam_total_nilai){
					status = `<span class="badge text-success bg-success-subtle">Sudah dinilai</span>`
				}else{
					status = `-`
				}
				return status
			}
		},
		{
			data: 'exam_total_nilai',
			class: 'text-center',
			render(data, row, type, meta){
				return (data) ? `<b>${data}</b>` : `-`;
			}
		},
		{
			data: null,
			render(data, row, type, meta) {
				var view = `<div class="text-center">
							${(student_id && type.status == 1 && !type.exam_submit && moment().format('Y-MM-D H:m:s') >= type.start_date && moment().format('Y-MM-D H:m:s') <= type.end_date && userLevel != 5 ) ? `<a href="${BASE_URL+'asesmen/do_exercise/'+type.exam_id}" target="_blank" class="btn btn-sm btn-primary rounded-circle view_student">
									<i class="bi bi-pencil-square text-white"></i></a>` : ``}
							${(student_id && type.exam_total_nilai) ? `<a href="${BASE_URL+'asesmen/show_answer?id='+type.exam_id+'&student_id='+student_id}" class="btn btn-sm btn-primary rounded-circle view_asesmen"><i class="bi bi-eye text-white"></i></a>` : ``}
						</div>`;
				return view;
			}
		}
	]
});


var tableKehadiran = $('#tableKehadiran').DataTable({
	"oLanguage": {
		"sUrl": "https://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json"
	},
	"pagingType": "simple_numbers",
	"language": {
		"paginate": {
		"first":    "«",
		"previous": "‹",
		"next":     "›",
		"last":     "»"
		}
	},
	bInfo: (isUserUsingMobile()) ? false : true,
	ordering: false,
	serverSide: true,
	ajax: {
		url: BASE_URL + 'student/get_kehadiran',
		method: 'GET',
		data: {
			// student_id: student_id
		}
	},
	select: {
		style: 'multi',
		selector: 'td:first-child',
	},
	columns: [
		// {
		// 	data: 'id',
		// 	visible: false
		// },
		{
			data: null,
			render(data, type, row, meta){
				return meta.row + meta.settings._iDisplayStart + 1;
			}
		},
		{
			data: 'sesi_title',
		},
		{ 
			data: 'sesi_date',
		},
		{
			data: 'sesi_waktu',
		},
		{
			data: 'status_hadir' 
		}  
	]
}).columns(1).search(student_id).draw();

tableKehadiran.columns(0).search(class_id).draw();
tableKehadiran.columns(1).search(student_id).draw();

// #############################################################################################
// ################################## DATA TABLE HISTORY BOOK ##################################
// #############################################################################################

var tableBookHistory = $('#tableBookHistory').DataTable({
	"oLanguage": {
		"sUrl": "https://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json"
	},
	"oLanguage": {
		"sUrl": "https://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json"
	},
	"pagingType": "simple_numbers",
	"language": {
		"paginate": {
		"first":    "«",
		"previous": "‹",
		"next":     "›",
		"last":     "»"
		}
	},
	bInfo: (isUserUsingMobile()) ? false : true,
	ordering: false,
	serverSide: true,
	ajax:{
		url: BASE_URL + 'student/get_history_book',
		method: 'GET',
		data: {
			student_id: student_id
		}
	},
	select: {
		style: 'multi',
		selector: 'td:first-child'
	},
	columns: [
		{
			data: 'book_id',
			visible: false
		},
		{
			data: 'cover_img',
			class: 'text-center',
			render(data, type, row, meta){
				return `<img src="${BASE_URL+'assets/images/ebooks/cover/'+data}" alt="" width="100">`;
			}
		},
		{
			data: 'max',
			render(data, type, row, meta){
				let tanggal = moment(data).format('DD MMM YYYY HH:mm');
				return tanggal;
			}
		},
		{
			data: 'book_code',
			visible: false
		},
		{
			data: 'title'
		},
		{
			data: 'category_name'
		},
		{
			data: 'author'
		},
		{
			data: 'publish_year'
		},
		{
			data: 'description',
			visible: false,
			render(data, type, row, meta){
				let desc = (data != null) ? data.substring(0,100)+' ...' : '';
				return desc;
			}
		},
		{
			data: null,
			class: 'text-center',
			render(data, type, row, meta) {
				var view = `<div class="btn-group btn-group-sm float-right">
								<button class="btn btn-success edit_subject rounded-5" onclick="showModalBook(${row.book_id})"><i class="bi bi-eye text-white"></i></button>
							</div>`;
				return view;
			}
		}
	]
});


// #############################################################################################
// ################################## DATA TABLE ABSENSI ##################################
// #############################################################################################

var tableAbsensi = $('#tableAbsensi').DataTable({
	"oLanguage": {
		"sUrl": "https://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json"
	},
	"oLanguage": {
		"sUrl": "https://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json"
	},
	"pagingType": "simple_numbers",
	"language": {
		"paginate": {
		"first":    "«",
		"previous": "‹",
		"next":     "›",
		"last":     "»"
		}
	},
	bInfo: (isUserUsingMobile()) ? false : true,
	ordering: false,
	serverSide: true,
	ajax: {
		url: BASE_URL + 'student/get_absensi',
		method: 'GET',
		data: {
			student_id: student_id
		}
	},
	select: {
		style: 'multi',
		selector: 'td:first-child',
	},
	columns: [
		{
			data: 'tanggal',
		},
		{ 
			data: 'absen_masuk',
		},
		{
			data: 'absen_pulang',
		},
		{
			data: null,
			render(){
				return `-`;
			}
		},
		{
			data: null,
			render(){
				return `-`;
			}
		} 
	]
});

// ############################################################################################
// ################################## SHOW MODAL DETAIL BUKU ##################################
// ############################################################################################

function showModalBook(book_id){
	$.ajax({
		type: "GET",
		url: BASE_URL+"student/book_detail",
		data: {
			book_id: book_id
		},
		dataType: "JSON",
		success: function (response) {
			if(response.success == true){
				let data = response.data;
				$('#modal-show').modal('show');
				$('[data-item="cover_img"]')[0].src = BASE_URL+'/assets/images/ebooks/cover/'+data.cover_img;
				$('[data-item="book_code"]')[0].innerHTML = data.book_code;
				$('[data-item="author"]')[0].innerHTML = data.author;
				$('[data-item="publisher_name"]')[0].innerHTML = data.publisher_name;
				$('[data-item="publish_year"]')[0].innerHTML = data.publish_year;
				$('[data-item="isbn"]')[0].innerHTML = data.isbn;
				$('[data-item="description"]')[0].innerHTML = data.description;
				$('.read-link')[0].href = BASE_URL+'ebook/open_book?id='+book_id;
			}

		}
	});
}

// styling header kalender
document.addEventListener('DOMContentLoaded', function() {
	console.log('The page has loaded!');
	// UBAH WARNA THEAD SESI
	$('thead[role="presentation"]')[0].classList.value = 'bg-primary';

	// UBAH WARNA TEXT THEAD
	let heads = $('.fc .fc-col-header-cell-cushion');
	$.each(heads, function (index, val) { 
		heads[index].classList.add('text-white');
		heads[index].classList.add('text-decoration-none');
	});
	
  });

/**
 * ************************************
 *          SEARCH / FILTER
 * ************************************
 */
const filter = async e => {
	e.preventDefault();

	let mapel = ($('#s_materi_guru_mapel').val() !== null) ? $('#s_materi_guru_mapel').val() : '';

	let teacher = ($('#s_materi_guru_nama_guru').val() !== null) ? $('#s_materi_guru_nama_guru').val() : '';

	try{
		tableMateriGuru.columns(0).search(mapel).draw();
		tableMateriGuru.columns(1).search(teacher).draw();
	}catch(err){

	}
}
