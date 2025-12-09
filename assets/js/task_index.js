'use strict';

let userLevel = $('#user_level').val();
let thisTime = new Date();
let waktuMulai;
let filter = false; 
var arrPage = [];

	$(document).ready(function () {
		load_data(1,10);
		// ########################## PAGINATION JS ##############################
		$('#page').pagination({
			dataSource: arrPage,
			className: 'paginationjs-theme-blue paginationjs-big',
			callback: function(data, pagination){
				load_data(pagination.pageNumber);
			}
		});
	});

	// KETIKA BUTTON CARI DI KLIK
	$('#search').on('click', function(e){
		filter = true;
		arrPage = []; // reset arrPage, nanti terisi lagi di dalam function load_data
		load_data();

		// jalankan render pagination js nya
		$('#page').pagination({
			dataSource: arrPage,
			className: 'paginationjs-theme-blue paginationjs-big',
			callback: function(data, pagination){
				load_data(pagination.pageNumber);
			}
		});
	});

	// create function load data
	function load_data(page = 1, limit = 10){
		let mapel = $('#select-mapel').val();
		let startDate = $('#start-date').val();
		let endDate = $('#end-date').val();

		$.ajax({
			type: "GET",
			url: BASE_URL+"task/getlist",
			async: false,
			data: {
				page: page,
				limit: limit,
				mapel: mapel,
				startDate: startDate,
				endDate: endDate
			},
			success: function (response) {
				$('#task-content').html('');

				// jika pencarian tugas kosong maka muncul kan gambar tugas kosong
				if(response.task.length == 0) {
					$('#task-content').append(`
						<span class="text-center mt-5 mb-5">
							<img src="${BASE_URL+'assets/images/tugas-kosong.png'}" style="width: 30vh;" />
						</span>
						`);
					return
				}

				let colors = ['bg-primary-subtle', 'bg-danger-subtle', 'bg-secondary-subtle', 'bg-success-subtle', 
					'bg-warning-subtle', 'bg-info-subtle', 'bg-dark-subtle']; // create array colors bootstrap
				
				let jawab = 0;

				// jika tidak menggunakan filter atau tidak klik tombol cari atau saat pertama kali data di load
				// hanya untuk murid
				if(!filter & userLevel == 4){
					$.each(response.task, function (a, val) { 
						if(!val.answer) {
							jawab++; // jika tugas sudah di jawab maka tidak di hitung
						}
					});
					if(jawab == 0){
						$('#task-content').append(`
							<span class="text-center mt-5 mb-5">
								<img src="${BASE_URL+'assets/images/tugas-kosong.png'}" style="width: 30vh;" />
							</span>
							`);
						return
					}
				}
				
				let no = 0;
				let color;
				$.each(response.task, function (key, value){
					waktuMulai = new Date(value.available_date);

					let title = value.title;
					color = (no >= colors.length) ? no = 0 : colors[no]; // jika no lebih besar atau sama dengan panjang array color maka no reset kembali menjadi 0
					if(title && title.length > 100) title = title.substring(0, 300) + ' ...' // jika panjang deskripsi lebih dari 100 karakter maka lakukan substring 300 huruf

					$('#task-content').append(`
						<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-xs-12 p-2 mb-1 task-item d-flex">
							<!-- Jika tanggal mengerjakan tugas nya lebih besar dari sekarang maka disable href nya -->
							<a class="w-100 text-decoration-none ${((userLevel == 4) && (waktuMulai > thisTime)) ? 'disabled-link':'' }" href="task/detail/${value.task_id}">
							<div class="container h-100 border-0 rounded-4 ${colors[no]} p-3 w-100">
								
								<div class="text-decoration-none text-black mt-2">
									<h5><b>${(value.subject_name != null) ? value.subject_name : value.subject_name2}</b></h5>
								</div>

								<p class="${(userLevel == 3) ? 'd-none' : ''}">${value.teacher_name}</p>

								<h6 class="mt-2"><span class="badge bg-dark lh-lg bg-opacity-75 ${(userLevel == 4) ? 'd-none':''}">${value.class_name}</span></h6>

								<h6 class="mt-2 mb-3">
									<span class="badge rounded-3 ${colors[no].replace('-subtle', '')} text-white lh-lg bg-opacity-75">Waktu Mulai : ${moment(value.available_date).format('D MMM Y, HH:mm')}</span>
								</h6>
								
								<p style="font-size: 14px;">${title}</p>

								<h6 class="mb-5">
									<span class="badge rounded-3 ${colors[no].replace('-subtle', '')} text-white lh-lg bg-opacity-75"">Batas Akhir : ${moment(value.due_date).format('D MMM Y, HH:mm')}</span>
								</h6>
								
								<!-- div class="container position-absolute bottom-0 end-0 d-flex justify-content-end">${buttonGroup(response.user_level, value.task_id)}</div -->
							</div>
							</a>
						</div>
					`);

					no++;
				});

				// ########################## PAGINATION JS ##############################
				for(let i=1; i<=response.total_records; i++){
					arrPage.push(i);
				}
			}
		});
	}

	// BUTTON GROUP EDIT & DELETE
	function buttonGroup(user_level, id){
		let buttonGroup = `<a href="${BASE_URL+'task/create/'+id}" class="btn btn-clear border d-inline me-1 rounded-5"><i class="bi bi-pencil-square"></i></a>
							<a class="btn btn-clear border d-inline rounded-5" onclick="deleteTask(${id})"><i class="bi bi-trash3-fill"></i></a>`;
		if(user_level == 3  ){
			return buttonGroup;
		}

		return '';
	}

	function deleteTask(id){
		Swal.fire({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
			if (result.isConfirmed) {

				$.ajax({
					type: "POST",
					url: BASE_URL+"task/delete",
					data: {
						id: id
					},
					dataType: "JSON",
					success: function (response) {
						if(response.success == true){
							Swal.fire('Deleted!', response.message, 'success');
							window.location.href = BASE_URL+'task';
						}
					}
				});

				
			}
		})
	}


    
    //=======================================================
    //        EBOOK TASK
    //=======================================================

	const listContainer = document.getElementById('ebook-list-container'),
		  pagesDialog 	= document.getElementById('ebook-pages'),
		  pagesContainer = document.getElementById('pages-grid'),
		  inputSearchEbook = document.getElementById('search-ebook-task'),
		  frmTaskEbook 	= document.forms['frm-task-ebook'];
	const pagesModal = new bootstrap.Modal(pagesDialog);
	let pdfDoc = null;
	
    /**
	 * Get List Ebooks
	 *
	 * @async
	 * @returns {Body.json()}
	 */
	async function getEbooks() {
        try 
        {
            const f = await fetch(`${BASE_URL}/ebook/list`);
            const j = await f.json();
            
            return j;
        } 
        catch (error) 
        {
            console.error(error);
        }
    }

	
    /**
	 * Create html list for get ebook 
	 *
	 * @async
	 * @param {*} data
	 * @returns {HTMLUListElement}
	 */
	async function createEbookListElement(data) {
        try
        {
            const ul = document.createElement('ul');
            ul.classList.add('list-group');

            Array.from(data, (item, idx) => {
                const li = document.createElement('li');
                const link = document.createElement('a');
                
                // IMG
                const img = new Image(36, 42);
                img.src = 'assets/images/ebooks/cover/' + item.cover_img;
                img.onerror = e => e.target.src = 'assets/images/ebooks/cover/default.png';
                link.appendChild(img);
                // DIV INFO
                const info = document.createElement('div');
                info.classList.add('d-block', 'w-100', 'ms-3');
                const h6 = document.createElement('h6');
                h6.innerText = item.title;
                info.appendChild(h6);
                const h6Small = document.createElement('small');
                link.appendChild(info);
                // label
                link.dataset.id=item.book_code;
				link.role = "button";
				link.onclick = async e => {
					frmTaskEbook['book_code'].value = item.book_code;
					frmTaskEbook['title'].value = item.title;
					pagesContainer.innerHTML = null;
					pagesModal.show();
					await renderBookPages(BASE_URL+'/assets/files/ebooks/' + item.file_1);
					
				}
                link.classList.add('d-flex', 'align-items-center', 'text-decoration-none');

                li.classList.add('list-group-item');
                li.appendChild(link);
                ul.appendChild(li);
            });

            return ul;
        }
        catch(err)
        {

        }
    }
	
	/**
	 * Create Elment for thubmnail viewer
	 *
	 * @param {HTMLCanvasElement} canvas
	 * @param {Int} num
	 * @returns {HTMLDivElement}
	 */
	function thumbnailViewer(canvas, num) {
		const url = canvas.toDataURL("image/jpeg");
		const col = document.createElement('div'),
				divContainer = document.createElement('div'),
				label = document.createElement('label'),
				overlay = document.createElement('a'),
				input = document.createElement('input'),
				img = new Image();
	
		input.type = 'checkbox';
		input.name = 'pages[]';
		input.id='pageIndex_' + num;
		input.value=num;
		input.classList.add('position-absolute', 'd-none');
		input.onclick= e => {
			if(e.target.checked) 
				label.classList.add('bg-primary');
			else 
				label.classList.remove('bg-primary');
		
		};
		label.appendChild(input);

		img.src = url;
		img.height = 200;
		label.appendChild(img);

		label.setAttribute('for', 'pageIndex_' + num);
		label.style.cursor = 'pointer';
		label.classList.add('p-1', 'border', 'rounded', 'position-relative');
		divContainer.appendChild(label);

		// OVERLAY
		overlay.title = 'Pratinjau';
		overlay.role = "button";
		overlay.onclick = e => {
			console.log(document.getElementById('dialog-preview'));
			document.getElementById('img-preview').src = url;
			document.getElementById('dialog-preview').showModal();
		};
		overlay.classList.add('overflow-hidden', 'position-absolute', 'w-100', 'pdf-page-preview', 'text-center', 'align-middle');
		overlay.innerHTML = '<i class="bi bi-eye text-white d-inline-block align-middle mt-2"></i>';
		divContainer.appendChild(overlay);

		// DIV CONTAINER
		divContainer.classList.add('position-relative', 'm-0', 'p-0', 'w-auto', 'h-auto', 'd-inline-block', 'pdf-thumbnail-container');

		col.classList.add('col-12', 'col-sm-4', 'mb-2', 'position-relative');
		col.dataset.pageIndex = num;
		col.appendChild(divContainer);

		return col;
			
	}


	/**
	 * Render pages from seleced ebook
	 *
	 * @async
	 * @param {*} ebook
	 * @returns {*}
	 */
	async function renderBookPages(ebook) {

		pdfjsLib.GlobalWorkerOptions.workerSrc = 'assets/node_modules/pdfjs-dist/build/pdf.worker.min.js';

		if(pdfDoc)
			pdfDoc.destroy();

		const pdfLoad = pdfjsLib.getDocument({ 
			url: ebook,
			withCredentials: true
		});
		pdfDoc = await pdfLoad.promise;
		
		pagesContainer.innerHTML = null;

		try 
		{
			for(var i=1;i<=pdfDoc.numPages;i++)
			{
				const page = await pdfDoc.getPage(i);
				const canvas = document.createElement("canvas");
				
				console.log(page._transport);

				var scale = 1;
				var viewport = page.getViewport({scale: scale});
				const ctx = canvas.getContext("2d",  { willReadFrequently: true });
		
				canvas.height = viewport.height;
				canvas.width = viewport.width;
		
				var renderContext = {
					canvasContext: ctx,
					viewport: page.getViewport({ scale: Math.min(canvas.width / viewport.width, canvas.height / viewport.height) })
				};
				var renderTask = page.render(renderContext);

				await renderTask.promise.then(() => {
					pagesContainer.appendChild(thumbnailViewer(canvas, i));
				});
		
			}
		} 
		catch (error) 
		{
			
		}
		
	}

	
	/**
	 * Search Ebook Event Listener
	 *
	 * @param {HTMLInputElement} e
	 */
	function seachEbook(e) {
		const content = e.target.value;
		const lists = listContainer.querySelectorAll('li');
		
		for(var item of lists) {
			const a = item.querySelector('a');
			const text = item.innerText;

			if(text.toUpperCase().indexOf(content.toUpperCase()) > -1) 
				item.classList.remove('d-none');
			else
				item.classList.add('d-none');
		}
	}


	function submitTaskBook(e) {

		const formData = new FormData(e.target);
		const entries = Object.fromEntries(formData.entries());
		delete entries['pages[]'];

		const obj = Object.assign(entries, {"pages[]": formData.getAll("pages[]")});
		
		if(obj['pages[]'].length === 0)
		{
			alert('Tidak ada halaman yang di pilih !!!');
			e.preventDefault();
			return false;
		}
	} 


(async () => {

    const ebooks = [...(await getEbooks()).data];
	// const ebookSelected = ebooks.find(val => val.book_code == 'B0000000000000007242');

    listContainer.appendChild(await createEbookListElement(ebooks));

	
	frmTaskEbook.addEventListener('submit', submitTaskBook);
	inputSearchEbook.addEventListener('input', seachEbook);

})();


    