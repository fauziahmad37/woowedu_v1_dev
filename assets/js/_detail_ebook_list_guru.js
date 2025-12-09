let ebook_teacher_id = document.querySelector('input[name="ebook_teacher_id"]').value;
let teacherId = document.querySelector('input[name="teacher_id"]').value;
let fromFilterFirst = false;
let btnGroupShare = document.querySelector('.btn-group-share');

if (!teacherId) {
	btnGroupShare.classList.add('d-none');
}

let selectedClasses = [];
/**
 * Fetch detail ebook list for guru
 * @return {void}
 */
function getDetailEbookListGuru() {
	let xhr = new XMLHttpRequest();
	xhr.open("GET", `ebook/get_detail_ebook_list_guru/${ebook_teacher_id}`);
	xhr.onload = function () {
		if (xhr.readyState === 3) {
			// Show loading indicator
			let containerListEbook = document.getElementById("daftar_ebook");
			if (fromFilterFirst) {
				containerListEbook.innerHTML = '<p>Loading...</p>';
			}
		}

		if (xhr.readyState === 4 && xhr.status === 200) {
			let response = JSON.parse(xhr.responseText);
			if (response.success) {
				let data = response.data;
				let ebooks = data.ebooks;
				selectedClasses = data.class_levels.map(c => c.class_id); // Store selected classes
				classNames = data.class_levels.map(c => c.class_name); // Store class names

				document.querySelector('.card-title').textContent = data.title;
				document.querySelector('.card-description').textContent = data.description;
				document.querySelector('.total-ebooks').textContent = ebooks.length;
				document.querySelector('.class-levels').textContent = classNames.join(', ');

				// clear loading
				// Create a container for the ebook items
				let containerListEbook = document.getElementById("daftar_ebook");
				if (fromFilterFirst) {
					containerListEbook.innerHTML = '';
				}

				let ebookItems = '';
				let no = 1;
				ebooks.forEach(el => {
					ebookItems += `<div class="d-flex mb-4 ${(no !== ebooks.length) ? 'border-bottom' : ''} pb-4">
										<div class="d-flex">
											<div class="d-inline-block me-3">
												${no}
											</div>
											<div class="d-inline-block me-3">
												<img class="rounded-3" src="${ el.cover_img.includes('http') ? el.cover_img : 'assets/images/ebooks/cover/' + el.cover_img}" alt="${el.title}" style="width: 80px; height: 120px;">
											</div>
											<div>
												<p style="color: #74788D;">${el.publisher_name}</p>
												<h5 style="font-size: 16px;">${el.title}</h5>
											</div>
										</div>
										<div class="ms-auto align-self-center">
											<div class="dropdown">
												<button class="btn btn-primary text-white rounded-circle" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots"></i></button>
												<ul class="dropdown-menu" style="min-width: 40px;">
													<li class="text-center btn-delete-ebook ${teacherId ? '' : 'd-none'}" onclick="delete_ebook(${el.id})"><i class="bi bi-trash text-danger"></i></li>
													<li class="text-center btn-lihat-detail-ebook ${!teacherId ? '' : 'd-none'}"><a href="ebook/detail/${el.ebook_id}"><i class="bi bi-eye text-primary"></i></a></li>
												</ul>
											</div>
										</div>

									</div>`;
					no++;
				});

				document.querySelector('#list_ebook_dipilih').innerHTML = ebookItems;

			}
		} else {
			console.error("Error fetching ebook details:", xhr.statusText);
		}
	};
	xhr.send();
}

getDetailEbookListGuru();

/**
 * Get All Kelas Teacher
 * @return {json}
 */
function getAllClassTeacher() {
	let xhr = new XMLHttpRequest();
	xhr.open("GET", `Kelas/getAllClassTeacher`, true);
	xhr.onload = function () {
		if (this.status == 200) {
			let response = JSON.parse(this.responseText);
			// Process the response
			document.querySelector('#pilih_kelas_section').innerHTML = '';
			let itemCheck = '';
			let classes = response.data;
			classes.forEach(c => {
				let isChecked = selectedClasses.includes(c.class_id) ? 'checked' : '';

				itemCheck += `<div class="form-check mb-3">
					<input class="form-check-input" type="checkbox" value="${c.class_id}" id="kelas_${c.class_id}" ${isChecked}>
					<label class="form-check-label" for="kelas_${c.class_id}">${c.class_name}</label>
				</div>`;
			});
			document.querySelector('#pilih_kelas_section').innerHTML = itemCheck;

		}
	}
	xhr.send();
}


let buttonShare = document.getElementById('button_share');
buttonShare.addEventListener('click', function () {
	getDetailEbookListGuru();
	getAllClassTeacher();
});

let buttonSimpanShare = document.getElementById('simpan_share');
buttonSimpanShare.addEventListener('click', function () {
	let selectedKelas = [];
	document.querySelectorAll('#pilih_kelas_section .form-check-input:checked').forEach(checkbox => {
		selectedKelas.push(checkbox.value);
	});
	console.log("Kelas yang dipilih:", selectedKelas);

	let formData = new FormData();
	formData.append("ebook_teacher_id", ebook_teacher_id);
	formData.append("kelas", JSON.stringify(selectedKelas));
	formData.append("csrf_token_name", document.querySelector('meta[name="csrf_token"]').getAttribute('content'));

	let xhr = new XMLHttpRequest();
	xhr.open("POST", "ebook/saveShareKelasEbookListGuru", true);
	xhr.onload = function () {
		if (xhr.status === 200) {
			let response = JSON.parse(xhr.responseText);
			if (response.success) {
				alert(response.message);
				document.querySelector('meta[name="csrf_token"]').setAttribute('content', response.csrf_token);
			} else {
				alert("Error saving data");
				document.querySelector('meta[name="csrf_token"]').setAttribute('content', response.csrf_token);
			}
		}
	};
	xhr.send(formData);
});

/**
 * Delete Ebook
 * @param {number} id - The ID of the ebook to delete
 * @return {void}
 */
function delete_ebook(id) {
	let form = new FormData();
	form.append('csrf_token_name', document.querySelector('meta[name="csrf_token"]').getAttribute('content'));
	form.append('id', id);

	let xhr = new XMLHttpRequest();
	xhr.open("POST", `ebook/deleteEbookTeacherDetail`, true);
	xhr.onload = function () {
		if (xhr.status === 200) {
			let response = JSON.parse(xhr.responseText);
			if (response.success) {
				swal.fire({
					title: "Berhasil!",
					text: response.message,
					icon: "success",
					type: "success"
				});
				getDetailEbookListGuru(); // Refresh the list after deletion
				document.querySelector('meta[name="csrf_token"]').setAttribute('content', response.csrf_token);
			} else {
				swal.fire({
					title: "Gagal!",
					text: response.message,
					icon: "error",
					type: "error"
				});
				document.querySelector('meta[name="csrf_token"]').setAttribute('content', response.csrf_token);
			}
		}
	};
	xhr.send(form);
}

// TAMBAH LIST BUKU
let tambahBuku = document.getElementById('add_ebook');
tambahBuku.addEventListener('click', function () {
	page = 1; // Reset page number
	checkedEbooks.clear(); // Reset checked ebooks

	document.getElementById("daftar_ebook").innerHTML = ''; // Clear ebook list
	document.getElementById("daftar_ebook").innerHTML = `<div class="text-center mt-5">
						<div class="spinner-border text-primary loading-tambah-buku text-center" role="status">
							<span class="visually-hidden">Loading...</span>
						</div>
					</div>`;
	getAllEbooks();
});

let page = 1;
let per_page = 3;
let checkedEbooks = new Set();

/**
 * Fetch all ebooks based on the filters applied
 * @return {void}
 */
function getAllEbooks() {
	let title = document.getElementById("a_title").value;
	let category = document.getElementById("select-category").value;

	if (!category) category = 0;

	let xhr = new XMLHttpRequest();
	xhr.open("GET", BASE_URL + "api/api_ebook/getAllEbooks?title=" + title + "&category=" + category + "&page=" + page + "&per_page=" + per_page, true);
	xhr.onreadystatechange = function () {
		if (xhr.readyState === 3) {
			// Show loading indicator
			let containerListEbook = document.getElementById("daftar_ebook");
			if (fromFilterFirst) {
				containerListEbook.innerHTML = '<p>Loading...</p>';
			}
		}

		if (xhr.readyState === 4 && xhr.status === 200) {
			let response = JSON.parse(xhr.responseText);
			let data = response.data.fetch;

			// Hide the loading spinner
			let loadingSpinner = document.querySelector('.loading-tambah-buku');
			if (loadingSpinner) loadingSpinner.classList.add('d-none');

			let containerListEbook = document.getElementById("daftar_ebook");

			// clear loading
			if (fromFilterFirst) {
				containerListEbook.innerHTML = '';
			}

			// Hapus tombol load more lama (supaya tidak dobel / tidak reset checkbox)
			let oldLoadMore = document.getElementById("load_more_wrapper");
			if (oldLoadMore) oldLoadMore.remove();

			// Tambah ebook baru
			let ebookItems = '';
			data.forEach(el => {
				let item = document.createElement("div");
				item.className = "d-flex mb-4";
				item.innerHTML = `<div class="d-inline-block me-3">
										<input 
											class="form-check-input rounded-3" 
											type="checkbox" 
											name="a_check[]" 
											value="${el.id}" 
											${checkedEbooks.has(el.id) ? "checked" : ""} 
											style="width: 24px; height: 24px;">
									</div>
									<div class="d-inline-block me-3">
										<img class="rounded-3" src="${el.cover_img}" alt="${el.title}" style="width: 80px; height: 120px;">
									</div>
									<div>
										<p style="color: #74788D;">${el.publisher.publisher_name}</p>
										<h5>${el.title}</h5>
									</div>`;

				// Tambahkan event listener untuk simpan state
				item.querySelector("input").addEventListener("change", function (e) {
					if (e.target.checked) {
						checkedEbooks.add(el.id);
					} else {
						checkedEbooks.delete(el.id);
					}
				});

				containerListEbook.appendChild(item);
			});

			// Tambah tombol Load More hanya jika masih ada data
			if (data.length == per_page) {
				let loadMoreWrapper = document.createElement("div");
				loadMoreWrapper.className = "text-center mt-4";
				loadMoreWrapper.id = "load_more_wrapper";
				loadMoreWrapper.innerHTML = `
					<button class="btn btn-light border-primary" type="button" id="load_more">Load More</button>
				`;
				loadMoreWrapper.querySelector("#load_more").addEventListener("click", loadMoreEbooks);
				containerListEbook.appendChild(loadMoreWrapper);
			}
		}
	};
	xhr.send();
}

// getAllEbooks();

/**
 * Load more ebooks when the button is clicked
 * @return {void}
 */
function loadMoreEbooks(e) {
	fromFilterFirst = false;
	console.log(e.target.classList.add('d-none'));
	e.preventDefault();

	page++;
	getAllEbooks();
}

// Simpan Buku Dipilih
let simpanBuku = document.getElementById('simpan_ebook');
simpanBuku.addEventListener('click', function () {
	let selectedEbooks = [];
	document.querySelectorAll('input[name="a_check[]"]:checked').forEach(function (checkbox) {
		selectedEbooks.push(checkbox.value);
	});

	if (selectedEbooks.length > 0) {
		// Lakukan aksi simpan buku
		let form = new FormData();
		form.append('ebooks', JSON.stringify(selectedEbooks));
		form.append('ebook_teacher_id', ebook_teacher_id);
		form.append('csrf_token_name', document.querySelector('meta[name="csrf_token"]').getAttribute('content'));

		let xhr = new XMLHttpRequest();
		xhr.open("POST", BASE_URL + "api/api_ebook/saveEbookTeacherDetail", true);
		xhr.onreadystatechange = function () {
			if (xhr.readyState === 4 && xhr.status === 200) {
				let response = JSON.parse(xhr.responseText);
				if (response.success) {
					swal.fire({
						title: "Berhasil!",
						text: response.message,
						icon: "success",
						type: "success"
					});
					getDetailEbookListGuru(); // Refresh the list after saving
					document.querySelector('meta[name="csrf_token"]').setAttribute('content', response.csrf_token);

					//  close modal
					$('#modalTambahEbook').modal('hide');
				} else {
					swal.fire({
						title: "Gagal!",
						text: response.message,
						icon: "error",
						type: "error"
					});
					document.querySelector('meta[name="csrf_token"]').setAttribute('content', response.csrf_token);
				}
			}
		};
		xhr.send(form);
	} else {
		alert("Silakan pilih buku yang ingin disimpan.");
	}
});

// HAPUS SEMUA DATA
let deleteAllData = document.getElementById('delete_all_data');
deleteAllData.addEventListener('click', function () {
	let form = new FormData();
	form.append('ebook_teacher_id', ebook_teacher_id);
	form.append('csrf_token_name', document.querySelector('meta[name="csrf_token"]').getAttribute('content'));

	let xhr = new XMLHttpRequest();
	xhr.open("POST", BASE_URL + "api/api_ebook/deleteAllEbookTeacher", true);
	xhr.onreadystatechange = function () {
		if (xhr.readyState === 4 && xhr.status === 200) {
			let response = JSON.parse(xhr.responseText);
			if (response.success) {
				swal.fire({
					title: "Berhasil!",
					text: response.message,
					icon: "success",
					type: "success"
				});
				document.querySelector('meta[name="csrf_token"]').setAttribute('content', response.csrf_token);

				window.location.href = 'ebook/ebook_list_guru';
			} else {
				swal.fire({
					title: "Gagal!",
					text: response.message,
					icon: "error",
					type: "error"
				});
				document.querySelector('meta[name="csrf_token"]').setAttribute('content', response.csrf_token);
			}
		}
	};
	xhr.send(form);
});

// ================================================ FILTER SEARCH ==============================================

let ebookTitleSearch = document.getElementById("a_title");
ebookTitleSearch.addEventListener("input", function () {
	fromFilterFirst = true;
	page = 1; // reset page number
	// clear list ebook
	if (fromFilterFirst) {
		document.getElementById("daftar_ebook").innerHTML = '';
	}
	getAllEbooks();
});

// GET EBOOK CATEGORY
$('#modalTambahEbook').on('shown.bs.modal', function () {
	console.log("Modal shown");

	let select = $('select[name="select-category"]');

	if (!select.hasClass("select2-hidden-accessible")) {
		select.select2({
			theme: "bootstrap-5",
			placeholder: 'Pilih category',
			allowClear: true,
			dropdownParent: $('#modalTambahEbook'),
			dropdownAutoWidth: true,
			ajax: {
				url: `${BASE_URL}/api/Api_ebook/getAllCategories`,
				dataType: 'json',
				delay: 250,
				data: function (params) {
					return {
						page: params.page || 1,
						per_page: 20,
						with_children: false,
						only_main_category: true,
						category_name: params.term || ''
					};
				},
				processResults: function (data, params) {
					params.page = params.page || 1;
					return {
						results: data.data.fetch.map(x => ({
							id: x.id,
							text: x.category_name
						})),
						pagination: {
							more: (params.page * 20) < data.data.total
						}
					};
				},
				cache: true
			}
		});
	}

	// Event ketika kategori dipilih
	select.on('select2:select', function (e) {
		let data = e.params.data;
		console.log("Kategori dipilih:", data);

		// Kirim request ke server berdasarkan kategori terpilih
		fromFilterFirst = true;
		page = 1; // reset page number
		// clear list ebook
		if (fromFilterFirst) {
			document.getElementById("daftar_ebook").innerHTML = '';
		}
		getAllEbooks();
	});

	// Event ketika select2 di-clear
	select.on('select2:clear', function (e) {
		console.log("Kategori dihapus, ambil semua ebook");

		// Kirim request ke server berdasarkan kategori terpilih
		fromFilterFirst = true;
		page = 1; // reset page number
		// clear list ebook
		if (fromFilterFirst) {
			document.getElementById("daftar_ebook").innerHTML = '';
		}

		// Panggil API untuk ambil semua ebook (tanpa filter kategori)
		getAllEbooks(null);
	});
});

// KLIK FILTER KATEGORI
// $('#select-category').on('change', function () {
// 	page = 1; // reset page number
// 	// clear list ebook
// 	document.getElementById("daftar_ebook").innerHTML = '';
// 	getAllEbooks();
// });
