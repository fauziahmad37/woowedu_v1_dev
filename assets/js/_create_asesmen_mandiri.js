let csrfToken = document.querySelector('meta[name="csrf_token"]') ;
const form = document.forms['form-add'];
var is_update = false;
var buttonPilihAktif = 1;
let exam_id;
const sekolah_id = $('input[name="sekolah_id"]').val();
const teacher_id = $('input[name="teacher_id"]').val();

let columns = [];
columns[0] = {
	search: { value: sekolah_id }
};
columns[1] = {
	search: { value: $('#select-mapel').val() }
};


$(document).ready(function () {

	/**
	 * LOAD COMBO BOX MATERI
	 */
	// const loadMateri = () => {
		
	// 	$.ajax({
	// 		type: "POST",
	// 		url: BASE_URL+"materi/getAll",
	// 		data: { columns: columns },
	// 		dataType: 'JSON',
	// 		success: function (response) {
	// 			if(response.data){
	// 				$('#select-materi').html('');
	// 				let data = response.data;
	// 				for(let i=0; i < data.length; i++){
	// 					$('#select-materi').append(`<option value="${data[i].materi_id}">${data[i].note}</option>`);
	// 				}
	// 			}
	// 		}
	// 	});
	// }

	// loadMateri();

	

	/**
	 * MATA PELAJARAN DI PILIH
	 */
	// $('select[name="select-mapel"]').on('change', e => {
		// let subject_id = e.currentTarget.value;
		// columns[0] = new Object();
		// columns[0].search = {value: sekolah_id};

		// columns[1] = new Object();
		// columns[1].search = {value: subject_id};

		// delete columns[3];
		// delete columns[4];
		// loadMateri();
	// });

	/**
	 * Button pilih-pertanyaan di klik
	 * lakukan refresh table
	 */

	// pertanyaan bagian 1 atau GENERATE PERTANYAAN
	$('button.pilih-pertanyaan-1').on('click', function(e){
		delete columns[1];
		columns[0] = new Object();
		columns[0].search = { value: $('#select-mapel').val() }

		columns[2] = new Object();
		columns[2].search = { value: $('#a_jenis_pertanyaan_1').val() }

		columns[3] = new Object();
		columns[3].search = { value: sekolah_id}

		columns[4] = new Object();
		columns[4].search = { value: $('#select-materi').val()}

		// kosongkan column 5 karna sudah di isi oleh soal_id di button ganti pertanyaan
		columns[5] = new Object();
		columns[5].search = { value: []}

		// validasi select mapel jika tidak di pilih
		let title = $('#a_title').val();
		if(!title){
			alert('Nama Asesmen Wajib diisi!');
			$('#a_title').focus();
			return
		}

		// validasi select mapel jika tidak di pilih
		let pilihMateri = $('#select-mapel').val();
		if(!pilihMateri){
			alert('Mata Pelajaran Wajib di pilih!');
			$('#select-mapel').focus();
			return
		}
	
		// validasi ketika jumlah pertanyaan jika tidak di isi
		let limit = $('#a_jumlah_petanyaan_1').val();
		if(!limit){
			alert('jumlah pertanyaan harus di isi!');
			$('#a_jumlah_petanyaan_1').select();
			return
		}

		$.ajax({
			type: "POST",
			url: BASE_URL+ "asesmen/generate_pertanyaan_mandiri",
			data: {
				columns: columns
			},
			dataType: "JSON",
			contentType: 'application/x-www-form-urlencoded',
			success: function (response) {
				let data = response.data;
				
				// untuk validasi kalau soalnya lebih sedikit dari jumlah soal yg di input maka muncul alert
				if(data.length < limit){
					$('#jumlah-soal-modal').modal('show'); 
					return;
				}

				let number = Array.from(Array(data.length).keys()); // generate array sebanyak length data nya

				const shuffle = (data) => { // fungsi untuk acak angka sumber dari: https://www.freecodecamp.org/news/how-to-shuffle-an-array-of-items-using-javascript-or-typescript/
					return data.sort(() => Math.random() - 0.5); 
				}; 

				// const myArray = ["apple", "banana", "cherry", "date", "elderberry"]; 
				const shuffledArray = shuffle(number);

				$('.content-1').html('');
				for (let i = 0; i < shuffledArray.length; i++) {
					$('.content-1').append(pilihanGanda(1, i+1, data[shuffledArray[i]]));

					// jika melebihi limit maka keluar dari loop
					if(i+1 >= limit) break
				}
			}
		});
	});


	// =============================== Content Pilihan Ganda & Essay ==========================
	function pilihanGanda(buttonPilihAktif, nomor, item){
		return `<div class="card card-question-${buttonPilihAktif} p-3">
					<div class="col text-end mb-2">
						<button class="btn btn-sm btn-danger delete-card"><i class="fa fa-trash text-white"></i></button>
						<button class="btn btn-sm btn-secondary ganti-pertanyaan"><i class="fas fa-exchange-alt text-white"></i></button>
					</div>
					<input type="hidden" name="soal_id[]" value="${item.soal_id}">
					<p><span class="nomor">${nomor++}</span>) ${item.question}</p>
					${hasImage(item.question_file)}

					<div class="row choice">
						${choice(item)}
					</row>
					
				</div>
			<br>`
	}

	function hasImage(url){
		let image = '';
		if(url != null){
			image += `<img src="${ADMIN_URL+url}" width="250" ></img>`
		}
		return image
	}

	/**
	 * BUTTON delete-card DI KLIK
	 * BUTTON ganti DI KLIK
	 */
	for(let a=1; a<=2; a++){
		$(`.content-${a}`).on('click', '.card button.delete-card', function(e){
			e.currentTarget.parentNode.parentNode.remove(); // hapus card nya
	
			// reset nomor urut soal
			let noBaru = document.getElementsByClassName('nomor');
			nomor = noBaru.length;
			for(let i=0; i<noBaru.length; i++){
				console.log(noBaru[i].innerHTML = i+1);
			}
		});

		$(`.content-${a}`).on('click', '.card button.ganti-pertanyaan', function(e){
			// console.log(e.currentTarget.parentNode.parentNode)
			e.preventDefault();

			let inputs = document.querySelectorAll('input[name^="soal_id"]');
			let inputValues = [];

			for(let i=0; i<inputs.length; i++){
				inputValues.push(inputs[i].value);
			}

			columns[5] = new Object();
			columns[5].search = { value: inputValues}

			$.ajax({
				type: "POST",
				url: BASE_URL+"asesmen/generate_pertanyaan_mandiri",
				data: {columns: columns},
				dataType: "JSON",
				contentType: 'application/x-www-form-urlencoded',
				success: function (res) {
					// set token
					$('input[name="csrf_token_name"]').val(res.token);

					let data = res.data;
					let number = Array.from(Array(data.length).keys()); // generate array sebanyak length data nya

					const shuffle = (data) => { // fungsi untuk acak angka sumber dari: https://www.freecodecamp.org/news/how-to-shuffle-an-array-of-items-using-javascript-or-typescript/
						return data.sort(() => Math.random() - 0.5); 
					}; 
	
					// const myArray = ["apple", "banana", "cherry", "date", "elderberry"]; 
					const shuffledArray = shuffle(number);

					let target = $(e.currentTarget.parentNode.parentNode)[0];
					let nomor = target.children[2].children[0].innerHTML;

					target.innerHTML = `<div class="col text-end mb-2">
											<button class="btn btn-sm btn-danger delete-card"><i class="fa fa-trash text-white"></i></button>
											<button class="btn btn-sm btn-secondary ganti-pertanyaan"><i class="fas fa-exchange-alt text-white"></i></button>
										</div>
										<input type="hidden" name="soal_id[]" value="${data[shuffledArray[0]].soal_id}">
										<p><span class="nomor">${nomor}</span>) ${data[shuffledArray[0]].question}</p>
										${hasImage(data[shuffledArray[0]].question_file)}

										<div class="row choice">
											${choice(data[shuffledArray[0]])}
										</row>`;
				}
			});
		});
	}


	/**
	 * JIKA EXAM ID ADA MAKA ISI DATA CARD SOAL
	 */
	exam_id = $('input[name="exam_id"]').val();
	if(exam_id != ''){
		$.ajax({
			type: "GET",
			url: BASE_URL + 'asesmen/get_soal_by_exam_id',
			data: { exam_id: exam_id },
			dataType: "JSON",
			contentType: 'application/x-www-form-urlencoded',
			success: function (res) {
				for(let i=0; i<res.length; i++){
					if(res[i].type == 1){
						$('.content-1').append(pilihanGanda(1, i+1, res[i]));
					}else{
						$('.content-2').append(essay(2, i+1, res[i]));
					}
				}	
			}
		});
	}


	const formNS = document.forms['frm-input'];

	formNS.addEventListener('submit', async e => false);
	document.querySelector('.publish').addEventListener('click', async e => {
		await postAssesment(formNS, 1, BASE_URL + 'asesmen');
	});
	// document.querySelector('.pratinjau').addEventListener('click', async e => {
	// 	await postAssesment(formNS, 0, base_url+'asesmen/view/'+exam_id);
	// });
	// document.querySelector('.simpan-draft').addEventListener('click', async e => {
	// 	await postAssesment(formNS, 0, base_url+'asesmen/create_standar/'+exam_id);
	// });



/**
 * Publish Assement
 *
 * @async
 * @param {*} e
 * @returns {*}
 */
async function postAssesment(e, is_draft=0, redirect='') {
	let obj = {};

	Swal.fire({
		icon: '',
		html: 	'<div class="d-flex flex-column align-items-center">'
				+ '<span class="spinner-border text-primary"></span>'
				+ '<h3 class="mt-2">Loading...</h3>'
				+ '<div>',
		showConfirmButton: false,
		width: '10rem'
	});

	try 
	{
		const formData = new FormData(e);

		formData.append('status', is_draft);
		formData.append('tipe', 1);

		// obj = Object.fromEntries(formData.entries());

		// Object.assign(obj, {
		// 	status: is_draft,
		// 	tipe: 1,
		// });

		// console.log(formData);

		const f = await fetch(BASE_URL + 'asesmen/save_mandiri', {
			method: 'POST',
			// body: new URLSearchParams(obj).toString(),
			body: formData,
			// headers: {
			// 	'Content-Type': 'application/x-www-form-urlencoded'
			// }
		});

		Swal.close();

		const resp = await f.json();

		if(!f.ok)
		{
			Swal.fire({
				icon: 'error',
				title: '<h5 class="text-danger text-uppercase">Error</h5>',
				html: '<span class="text-danger">'+resp.message+'</span>',
				timer: 2000
			});

			// set token csrf
			$('input[name="csrf_token_name"]').val(resp.token);

			return;
		}

		Swal.fire({
			icon: 'success',
			title: '<h4 class="text-success">Success</h4>',
			html: '<span class="text-success">'+resp.message+'</span>',
			timer: 2000
		})
		.then(t => {
			
				window.location.href = BASE_URL+'asesmen/';
		});		
	} 
	catch (error) {
		Swal.fire({
			icon: 'error',
			title: '<h5 class="text-danger text-uppercase">Error</h5>',
			html: '<span class="text-danger">Gagal menerbitkan Asesmen !!!</span>',
			timer: 2000
		});

	}
}


	/**
	 * Fungsi poin ABC pilihan ganda
	 */
	function choice(item){
		return `
		<div class="col-12">
			${(item.choice_a || item.choice_a_file) ? `<p>A. `+item.choice_a+`</p>` : ''}
			${(item.choice_a_file) ? `<img width="150" src="${ADMIN_URL+item.choice_a_file}">` : '' }
		</div>
		<div class="col-12">
			${(item.choice_b || item.choice_b_file) ? `<p>B. `+item.choice_b+`</p>` : ''}
			${(item.choice_b_file) ? `<img width="150" src="${ADMIN_URL+item.choice_b_file}">` : '' }
		</div>
		<div class="col-12">
			${(item.choice_c || item.choice_c_file) ? `<p>C. `+item.choice_c+`</p>` : ''}
			${(item.choice_c_file) ? `<img width="150" src="${ADMIN_URL+item.choice_c_file}">` : '' }
		</div>
		<div class="col-12">
			${(item.choice_d || item.choice_d_file) ? `<p>D. `+item.choice_d+`</p>` : ''}
			${(item.choice_d_file) ? `<img width="150" src="${ADMIN_URL+item.choice_d_file}">` : '' }
		</div>
		<div class="col-12">
			${(item.choice_e || item.choice_e_file) ? `<p>E. `+item.choice_e+`</p>` : ''}
			${(item.choice_e_file) ? `<img width="150" src="${ADMIN_URL+item.choice_e_file}">` : '' }
		</div>
		<div class="col-12">
			${(item.choice_f || item.choice_f_file) ? `<p>F. `+item.choice_f+`</p>` : ''}
			${(item.choice_f_file) ? `<img width="150" src="${ADMIN_URL+item.choice_f_file}">` : '' }
		</div>`;
	}

});

