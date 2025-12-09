'use strict';

let csrfToken = document.querySelector('meta[name="csrf_token"]') ;
const form = document.forms['form-add'];
const btnStartExam = document.getElementById('btnStartExam');
const questions = document.getElementsByClassName('question');
const storeAnswer = new StoringAnswer('exam-answer');
const periods = JSON.parse(document.getElementById('period').textContent);

var is_update = false;
var btnClick;
let tipe = document.querySelector('input[name="tipe"]').value;

if(tipe == 1) document.querySelectorAll('.testpaper-sectionContainer')[1].classList.add("d-none");

const quill = new Quill('#editor', {
    theme: 'snow',

});


$(document).ready(function () {
	let table = $('#table-asesmen-standar').DataTable({
		ajax: BASE_URL + 'asesmen/getAsesmen',
		serverSide: true,
		processing: true,
		columns: [
			{
				data: 'materi_id',
				visible: false
			},
			{
				data: null,
				render(data, row, type, meta){
					return `<a href="${BASE_URL+'asesmen/exam_student/'+type.exam_id}">${type.title}</a>
						${(type.status == 0) ? `<span class="ms-2" style="background-color: #DAEBFF;">Dalam Draft</span>` : ''}
					`;
				}
			},
			{
				data: 'subject_name',
			},
			{
				data: 'class_name',
			},
			{
				data: 'start_date',
			},
			{
				data: null,
				render(data, row, type, meta) {
					var view = `<div class="btn-group btn-group-sm float-right">
                                <button class="btn btn-warning copy_asesmen">
									<i class="fa-solid fa-copy text-white"></i>
									<span class="tooltiptext">Gandakan lembar asesmen</span>
								</button>
                                <a href="${BASE_URL+'asesmen/view/'+type.exam_id}" class="btn btn-primary view_asesmen"><i class="fa-solid fa-eye text-white"></i></a>
                                <a href="${BASE_URL+'asesmen/create_standar/'+type.exam_id}" class="btn btn-success edit_materi"><i class="fa-solid fa-pencil text-white"></i></a>
                                <button class="btn btn-sm btn-danger delete_materi"><i class="fa-solid fa-trash text-white"></i></button>
								<div class="jawaban-essay">

								</div>
                            </div>`;
               	 	return view;
				}
			}
		],
	}).columns(3).search($('input[name="teacher_id"]').val()).draw();

	/**
	 * Cari
	 */	 

	$('#cari').on('click', function(e){
		e.preventDefault();
		table.columns(0).search($('select[name="select-mapel"]').val()).draw();
		table.columns(1).search($('input[name="s_title"]').val()).draw();
	});


	/**
	 * GET Data Soal
	 */
	$.ajax({
		type: "GET",
		url: BASE_URL+"asesmen/get_soal_by_exam_id",
		data: {
			exam_id: $('input[name="exam_id"]').val()
		},
		dataType: "JSON",
		success: function (res) {
			Array.from(res, val => {
				let soal;
				if(parseInt(val.type) == 1)
					soal = pilihanGanda(parseInt(val.grouping), val.no_urut, val);
				if(parseInt(val.type) == 2)
					soal = essay(parseInt(val.grouping), val.no_urut, val);

				$(`.content-${val.grouping}`).append(soal);

			});
		}
	});

	// =============================== Content Pilihan Ganda & Essay ==========================
	function pilihanGanda(buttonPilihAktif, nomor, item){
		return `<div class="card-question-${buttonPilihAktif} question p-3" id="${buttonPilihAktif}-${item.soal_id}" data-soal-nomor="${item.no_urut}" data-soal-tipe="1">
					
					<input type="hidden" name="soal_id[]" value="${item.soal_id}">
					<p><span data-soal-nomor="${item.no_urut}" class="nomor">${item.no_urut}</span> ) ${item.question}</p>
					${hasImage(item.question_file)}

					<div class="row">
					<div class="col-12">
						${(item.choice_a || item.choice_a_file) ? `<p>A. `+item.choice_a+`</p>` : ''}
						${(item.choice_a_file) ? `<img width="150" src="${ADMIN_URL + item.choice_a_file}">` : '' }
					</div>
					<div class="col-12">
						${(item.choice_b || item.choice_b_file) ? `<p>B. `+item.choice_b+`</p>` : ''}
						${(item.choice_b_file ) ? `<img width="150" src="${ADMIN_URL + item.choice_b_file}">` : '' }
					</div>
					<div class="col-12">
						${(item.choice_c || item.choice_c_file) ? `<p>C. `+item.choice_c+`</p>` : ''}
						${(item.choice_c_file ) ? `<img width="150" src="${ADMIN_URL + item.choice_c_file}">` : '' }
					</div>
					<div class="col-12">
						${(item.choice_d || item.choice_d_file) ? `<p>D. `+item.choice_d+`</p>` : ''}
						${(item.choice_d_file ) ? `<img width="150" src="${ADMIN_URL + item.choice_d_file}">` : '' }
					</div>
					<div class="col-12">
						${(item.choice_e || item.choice_e_file) ? `<p>E. `+item.choice_e+`</p>` : ''}
						${(item.choice_e_file ) ? `<img width="150" src="${ADMIN_URL + item.choice_e_file}">` : '' }
					</div>
					<div class="col-12">
						${(item.choice_f || item.choice_f_file) ? `<p>F. `+item.choice_f+`</p>` : ''}
						${(item.choice_f_file ) ? `<img width="150" src="${ADMIN_URL + item.choice_f_file}">` : '' }
					</div>
					</row>
					
					<div class="row section-answer p-2">
						<div class="col-2">Jawaban Anda:</div>
						<div class="col-6 options">

							<div class="row">

								${((item.choice_a || item.choice_a_file)) ? `<div class="col">
									<div class="round">
										<input type="radio" class="answer-1" data-soal-id="${item.soal_id}" name="${buttonPilihAktif}-${item.soal_id}" id="${buttonPilihAktif}-${item.soal_id}-a"  value="A" />
										<label for="${buttonPilihAktif}-${item.soal_id}-a" ></label>
									</div>
									<span class="alphabet">A</span>
								</div>` : ''}
								
								${((item.choice_b || item.choice_b_file)) ? `<div class="col">
									<div class="round">
										<input type="radio" class="answer-1" data-soal-id="${item.soal_id}" name="${buttonPilihAktif}-${item.soal_id}" id="${buttonPilihAktif}-${item.soal_id}-b" value="B" />
										<label for="${buttonPilihAktif}-${item.soal_id}-b"></label>
									</div>
									<span class="alphabet">B</span>
								</div>` : ''}
								
								${((item.choice_c || item.choice_c_file)) ? `<div class="col">
									<div class="round">
										<input type="radio" class="answer-1" data-soal-id="${item.soal_id}" name="${buttonPilihAktif}-${item.soal_id}" id="${buttonPilihAktif}-${item.soal_id}-c" value="C"/>
										<label for="${buttonPilihAktif}-${item.soal_id}-c"></label>
									</div>
									<span class="alphabet">C</span>
								</div>` : ''}
								
								${((item.choice_d || item.choice_d_file)) ? `<div class="col">
									<div class="round">
										<input type="radio" class="answer-1" data-soal-id="${item.soal_id}" name="${buttonPilihAktif}-${item.soal_id}" id="${buttonPilihAktif}-${item.soal_id}-d" value="D"/>
										<label for="${buttonPilihAktif}-${item.soal_id}-d"></label>
									</div>
									<span class="alphabet">D</span>
								</div>` : ''}
								
								${((item.choice_e || item.choice_e_file)) ? `<div class="col">
									<div class="round">
										<input type="radio" class="answer-1" data-soal-id="${item.soal_id}" name="${buttonPilihAktif}-${item.soal_id}" id="${buttonPilihAktif}-${item.soal_id}-e" value="E"/>
										<label for="${buttonPilihAktif}-${item.soal_id}-e"></label>
									</div>
									<span class="alphabet">E</span>
								</div>` : ''}
								
								${((item.choice_f || item.choice_f_file)) ? `<div class="col">
									<div class="round">
										<input type="radio" name="${buttonPilihAktif}-${item.soal_id}" id="${buttonPilihAktif}-${item.soal_id}-f" value="F"/>
										<label for="${buttonPilihAktif}-${item.soal_id}-f"></label>
									</div>
									<span class="alphabet">F</span>
								</div>` : ''}
								
							</div>

							
						
						</div>
					</div>

					<div class="mt-2 line"></div>
				</div>
			<br>`
	}

		/**
	 * 
	 * @param {*} buttonPilihAktif 
	 * @param {*} nomor 
	 * @param {*} item 
	 * @returns 
	 */

	function essay(buttonPilihAktif, nomor, item){
		return `<div class="card-question-${buttonPilihAktif} question p-3" id="${buttonPilihAktif}-${item.soal_id}" data-soal-nomor="${item.no_urut}" data-soal-tipe="2">
					
					<input type="hidden" name="soal_id[]" value="${item.soal_id}">
					<p><span class="nomor">${item.no_urut}</span>) ${item.question}</p>
					<div class="w-100 d-flex flex-nowrap align-items-center button-group">
						<button class="btn btn-sm btn-primary text-white jawab">Jawab</button>
						<button class="btn btn-sm btn-success text-white edit_answer d-none">Ubah Jawaban</button>
						<button class="btn btn-sm btn-danger py-0 text-white ms-auto fs-6 aling-middle delete_answer d-none">&times;</button>
					</div>
					<div class="essay-answer border rounded mt-1 p-1"></div>
				</div>
			<br>`;
	}

	/**
	 * CHECK BOX JAWABAN DI KLIK
	 */

		

	/**
	 * Button Exam Submit di klik
	 */
	// $('#exam_submit').on('click', e => {
	// 	let input = document.querySelectorAll('.card-question-1 input[name="soal_id[]"]');
	// 	let input2 = document.querySelectorAll('.card-question-2 input[name="soal_id[]"]');

	// 	let options = document.querySelectorAll('.options');
	// 	let essayAnswers = document.querySelectorAll('.essay-answer');

	// 	// looping data jawaban bagian 1
	// 		let data = [];
	// 		for(let i=0; i<input.length; i++){
	// 			let soal_id = input[i].value;
	// 			let answers = options[i].querySelectorAll('input');
		
	// 			let jawaban = null;
	// 			// LOOPING PILIHAN GANDA
	// 			for(let a=0; a<answers.length; a++){
	// 				if(answers[a].hasAttribute("checked")){
	// 					jawaban = answers[a].value
	// 				}
	// 			}

	// 			data.push({
	// 				soal_id: soal_id,
	// 				jawaban: jawaban
	// 			});
	// 		}

	// 	// looping data jawaban bagian 2
	// 		for(let i=0; i<input2.length; i++){
	// 			let soal_id = input2[i].value;
	// 			let answer = essayAnswers[i].innerHTML;

	// 			data.push({
	// 				soal_id: soal_id,
	// 				jawaban: answer
	// 			});
	// 		}

	// 	let post = {
	// 		data: data,
	// 		exam_id: $('input[name="exam_id"]').val()
	// 	};

	// 	Swal.fire({
	// 		title: "Apakah kamu yakin?",
	// 		text: "Setelah di kirim ujian akan berakhir!",
	// 		icon: "warning",
	// 		showCancelButton: true,
	// 		confirmButtonColor: "#3085d6",
	// 		cancelButtonColor: "#d33",
	// 		confirmButtonText: "Ya!"
	// 	}).then((result) => {
	// 		if (result.isConfirmed) {
	// 			$.ajax({
	// 				type: "POST",
	// 				url: BASE_URL+"asesmen/save_answer",
	// 				data: JSON.stringify(post),
	// 				contentType: 'application/json',
	// 				success: function (res) {
	// 					let result = JSON.parse(res)
	// 					if( result.success){
	// 						Swal.fire({
	// 							title: "Sukses!",
	// 							text: "Your file has been deleted.",
	// 							icon: "success"
	// 						});

	// 						window.location.href = BASE_URL+`asesmen/show_answer?id=`+post.exam_id
	// 					}
	// 				}
	// 			});

	// 		}
	// 	});
	// });



	/**
	 * Button Jawab di Klik
	 */

	// $('.content-2').on('click', 'button.jawab', e => {
	// 	$('#answerModal').modal('show');
		
	// 	// btnClick = e.currentTarget;
	// });
	
	$('button.save').on('click', function(f){
		var answerIsian = quill.container;
		$('#answerModal').modal('hide');
		const answerContainer = btnClick.parentNode.nextElementSibling;
		answerContainer.innerHTML = quill.container.getElementsByClassName('ql-editor')[0].innerHTML;
		const container = btnClick.parentNode.closest('div.question');

		const trans = {
			id: container.id,
			group: parseInt((container.id.split('-'))[0]),
			soal_id: parseInt((container.id.split('-'))[1]),
			tipe: container.dataset.soalTipe,
			jawaban: answerContainer.innerHTML
		};
		
		storeAnswer.saveData(trans);

		quill.setText('');
	});

	/**
	 * 
	 * @param {*} url 
	 * @returns 
	 */
	function hasImage(url){
		let image = '';
		if(url != null){
			image += `<img src="${ADMIN_URL+url}" width="250" ></img>`
		}
		return image
	}

	

});

const shortcutContainer = document.getElementById('shortcut-container');

/**
 * 
 *
 * @param {*} collections
 * @returns {}
 */
function createShortcutButton(collections) {
	
	const ul = document.createElement('ul');
	ul.classList.add('list-unstyled', 'd-flex', 'flex-wrap', 'justify-content-left', 'shortcut-list');

	for(const question of collections)
	{
		const li = document.createElement('li');
		const a = document.createElement('a');
		li.classList.add('list-box', 'm-1', 'p-0');
		a.classList.add('rounded-circle', 'd-flex', 'bg-primary', 'shadow-sm', 'text-white', 'justify-content-center', 'align-items-center', 
						'text-decoration-none');
		a.onclick = e => document.getElementById(question.id).scrollIntoView({ behavior: 'smooth', block: 'center' });
		a.href=`${window.location.href}#${question.id}`;
		a.innerHTML = question.dataset.soalNomor;
		li.appendChild(a)
		ul.appendChild(li);
	}

	return ul;
}


const answerType = 
{
	answerType1(e) {
		// lakukan looping untuk delete checked
		let arrCol = e.currentTarget.parentNode.parentNode.children;
		for(let i=0; i < arrCol.length; i++){
			arrCol[i].children[0].children[0].removeAttribute('checked')
		}

		// set checked
		if(e.currentTarget.children[0].hasAttribute("checked")){
			e.currentTarget.children[0].removeAttribute("checked");
		}else{
			e.currentTarget.children[0].setAttribute("checked", "checked");
		}
	},
	answerType2(e) {

	}		
};


const endTime = (new Date(periods.end_time).getTime());
const timer =  setInterval(() => {
	const distance = endTime - new Date().getTime();
	
	var days = Math.floor(distance / (1000 * 60 * 60 * 24));
	var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
	var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
	var seconds = Math.floor((distance % (1000 * 60)) / 1000);

	var jam = hours < 10 ? "0" + hours : hours;
	var menit = minutes < 10 ? "0" + minutes : minutes;
	var detik = seconds < 10 ? "0" + seconds : seconds;


	document.getElementById('timer').innerHTML = days + " hari " + jam + ":" + menit + ":" + detik;

	if(days == 0 && hours == 0 && minutes == 0 && seconds == 0) 
	{
		clearInterval(timer);
		submitExam();
	}
}, 1000);

let fetchData = [];
window.addEventListener('load', e => {
	

	

	const observer = new MutationObserver(mut => {
		const mutator = mut[0];
		const parent = mutator.target.parentNode;

		const btnGroup = parent.getElementsByClassName('button-group')[0],
			  soalId = parent.querySelector('input[name="soal_id[]"]');		
		const btnJawab = btnGroup.querySelector('.jawab'),
			  btnEdit = btnGroup.querySelector('.edit_answer'),
			  btnHapus = btnGroup.querySelector('.delete_answer');		
			  
		console.log(parent.dataset.soalTipe);
		
		const shortcut = document.querySelector(`.list-box > a[href="#${parent.id}"]`);

		if(mutator.target.innerHTML)
		{
			btnJawab.classList.add('d-none');
			btnEdit.classList.remove('d-none');
			btnHapus.classList.remove('d-none');

			shortcut.classList.remove('bg-primary');
			shortcut.classList.add('bg-success');
		}
		else 
		{
			btnJawab.classList.remove('d-none');
			btnEdit.classList.add('d-none');
			btnHapus.classList.add('d-none');

			shortcut.classList.add('bg-primary');
			shortcut.classList.remove('bg-success');
		}
	});

	const observConfig = {
		attributes: true, 
		childList: true,
		characterData: true
	};

	setTimeout(() => {

		const questions_1 = document.getElementsByClassName('content-1')[0].getElementsByClassName('question'),
			  questions_2 = document.getElementsByClassName('content-2')[0].getElementsByClassName('question'),
			  allQuestions = document.getElementsByClassName('question');
		
		document.getElementById('c-1').appendChild(createShortcutButton([...questions_1]));
		document.getElementById('c-2').appendChild(createShortcutButton([...questions_2]));

		for(const q of allQuestions)
		{
			const getResult = storeAnswer.fetchOne(q.id);
			
			if(parseInt(q.dataset.soalTipe) == 1)
			{	
				const ans = q.getElementsByClassName('answer-1');
				
				[...ans].forEach(item => {
					
					item.addEventListener('click', elem => {

						// answerType.answerType1(elem);

						const src = elem.srcElement;
						const shortcut = document.querySelector(`.list-box > a[href="${window.location.href}#${src.name}"]`);

						const trans = {
							id: src.name,
							group: parseInt((src.name.split('-'))[0]),
							soal_id: src.dataset.soalId,
							tipe: q.dataset.soalTipe,
							jawaban: src.value
						};
						
						storeAnswer.saveData(trans);

						shortcut.classList.remove('bg-primary');
						shortcut.classList.add('bg-success');
					}, false)

				});

				getResult.onsuccess = evt => {
					const jawaban = evt.target.result;
					if(jawaban.jawaban) 
					{
						q.querySelector('input[name="'+q.id+'"][value="'+jawaban.jawaban+'"]').checked = true;
						const shortcut = document.querySelector(`.list-box > a[href="${window.location.href}#${q.id}"]`);

						shortcut.classList.remove('bg-primary');
						shortcut.classList.add('bg-success');
					}
				};
			}

			if(parseInt(q.dataset.soalTipe) == 2) {
				const answerContainer = q.querySelector('.essay-answer'),
					  btnGroup = q.getElementsByClassName('button-group')[0];

				getResult.onsuccess = evt => {
					const answer = evt.target.result;
					if(answer.jawaban)
					{
						answerContainer.innerHTML = answer.jawaban;
					}
				}
				
				observer.observe(answerContainer, observConfig);

				btnGroup.querySelector('.jawab').addEventListener('click', e => {
					new bootstrap.Modal('#answerModal').show();
					btnClick = e.currentTarget;
				});

				btnGroup.querySelector('.delete_answer').addEventListener('click', e => {
					const confirmation = confirm('Apakah anda ingin mengulangi jawaban anda ?');

					if(confirmation) 
					{ 
						answerContainer.innerHTML = null;
						storeAnswer.deleteData(q.id);
					}
				});

				btnGroup.querySelector('.edit_answer').addEventListener('click', e => {
					const answerContent = answerContainer.innerHTML;

					(quill.container.getElementsByClassName('ql-editor')[0]).innerHTML = answerContent;
					
					new bootstrap.Modal('#answerModal').show();

				});
			}
		}

		document.getElementById('answerModal').addEventListener('hidden.bs.modal', e => {
			(quill.container.getElementsByClassName('ql-editor')[0]).innerHTML = null;
		});

	}, 2500);

});

window.addEventListener('scroll', e => {
	const card = document.getElementById('shortcutCard');

	if(window.scrollY >= 300) {
		card.classList.remove('sticky-top');
		card.classList.add('position-fixed', 'top-0', 'end-0', 'mt-lg-6');
		card.style.width = '315px';
	}
	else {
		card.classList.add('sticky-top');
		card.classList.remove('position-fixed', 'top-0', 'end-0', 'mt-lg-6');
		card.style.width = '100%';
		card.style.marginTop = '';
	}

});


document.getElementById('exam_submit').addEventListener('click', e => { 

	Swal.fire({
		title: "Anda Yakin ?",
		text: "Cek jawaban anda terlebih dahulu sebelum mengumpulkan",
		type: "warning",
		showCancelButton: true,
		confirmButtonClass: "btn btn-success mt-2",
		cancelButtonColor: "#f46a6a",
		confirmButtonText: "Kumpulkan",
		cancelButtonText: "Batalkan" 
	})
	.then(async val => {
		if(!val.value)
			return false;

		submitExam();
	});
	
});


/**
 * submit exam
 *
 * @async
 * @returns {*}
 */
function submitExam() {
	const formData = {};
	const questions = document.getElementsByClassName('question');

	Object.assign(formData, {csrf_token_name : document.querySelector('input[name="csrf_token_name"]').value});
	Object.assign(formData, {exam_id : document.querySelector('input[name="exam_id"]').value});
	Object.assign(formData, {exam_type: document.querySelector('input[name="tipe"]').value});

	
	let i=0;
	const data = [];
	for(const question of questions) {
		const getCache = storeAnswer.fetchOne(question.id);
		const split = question.id.split('-');
		const group = parseInt(split[0]);

		getCache.onerror = evt => {
			console.log(evt);
		}
		getCache.onsuccess = evt => {
			let item = {};
			if(evt.target.result && evt.target.result.jawaban)
			{
				item = {
					soal_id: split[1],
					group: group,
					tipe_soal: question.dataset.soalTipe,
					nomor: question.dataset.soalNomor,
					jawaban: evt.target.result.jawaban
				};
			}
			else 
			{
				item = {
					soal_id: split[1],
					group: group,
					tipe_soal: question.dataset.soalTipe,
					nomor: question.dataset.soalNomor,
					jawaban: ''
				};
			}
				
			data.push(item);
		}
		// formData.append('answers['+i+'][jawaban]');
		i++;
	}

	
	setTimeout(async () => {
		Object.assign(formData, {'data': data});

		Swal.fire({
			html: 	'<div class="d-flex flex-column align-items-center">'
			+ '<span class="spinner-border text-primary"></span>'
			+ '<h5 class="mt-2">Loading...</h5>'
			+ '<div>',
			showConfirmButton: false,
			width: '20rem'
		});
	

		try
		{
			const f = await fetch(BASE_URL + 'asesmen/save_answer', {
				method: 'POST',
				body: JSON.stringify(formData),
				headers: {
					'Content-Type': 'application/json'
				}
			});

			const resp = await f.json();

			Swal.close();

			if(!f.ok)
			{
				Swal.fire({
					type: 'error',
					icon: 'error',
					title:`<h5 class="text-error text-uppercase">Error</h5>`,
					html: '<span class="text-error">' + resp.message + '</span>',
					timer: 2000
				});

				return false;
			}

			Swal.fire({
				type: 'success',
				icon: 'success',
				title:`<h5 class="text-success text-uppercase">Sukses</h5>`,
				html: '<span class="text-success">' + resp.message + '</span>',
				timer: 2000
			})
			.then(t => {
				storeAnswer.truncateDatabase();
				window.location.href = BASE_URL + 'asesmen';
			});
		}
		catch(err)
		{
			Swal.fire({
				type: 'error',
				icon: 'error',
				title:`<h5 class="text-error text-uppercase">Error</h5>`,
				html: '<span class="text-error">Ujian gagal di kumpulkan !!!</span>',
				timer: 2000
			});
		}

	
	}, i*100);
	
}
