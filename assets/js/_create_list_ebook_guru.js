let btnSimpan = document.getElementById("simpan");
let fromFilterFirst = true;

let page = 1;
let per_page = 3;

/**
 * Fetch all ebooks based on the filters applied
 * @return {void}
 */
function getAllEbooks(){
	let title = document.getElementById("a_title").value;
	let category = document.getElementById("select-category").value;

	if(!category) category = 0;

	let xhr = new XMLHttpRequest();
	xhr.open("GET", BASE_URL + "api/api_ebook/getAllEbooks?title=" + title + "&category=" + category + "&page=" + page + "&per_page=" + per_page, true);
	xhr.onreadystatechange = function () {
		
		if(xhr.readyState === 3){
			// Show loading indicator
			let containerListEbook = document.getElementById("daftar_ebook");
			if(fromFilterFirst) {
				containerListEbook.innerHTML = '<p>Loading...</p>';
			}
		}

		if (xhr.readyState === 4 && xhr.status === 200) {
			let response = JSON.parse(xhr.responseText);
			let data = response.data.fetch;

			// Create a container for the ebook items
			let containerListEbook = document.getElementById("daftar_ebook");

			// clear loading
			if (fromFilterFirst) {
				containerListEbook.innerHTML = '';
			}

			// Hapus tombol load more lama (supaya tidak dobel / tidak reset checkbox)
			let oldLoadMore = document.getElementById("load_more");
			if (oldLoadMore) oldLoadMore.remove();

			let ebookItems = '';
			data.forEach(el => {
				console.log(el)
				ebookItems += `<div class="d-flex mb-4">
									<div class="d-inline-block me-3">
										<input class="form-check-input rounded-3" type="checkbox" name="a_check[]" value="${el.id}" style="width: 24px; height: 24px;">
									</div>
									<div class="d-inline-block me-3">
										<img class="rounded-3" src="${el.cover_img}" alt="${el.title}" style="width: 80px; height: 120px;">
									</div>
									<div>
										<p style="color: #74788D;">${el.publisher.publisher_name}</p>
										<h5>${el.title}</h5>
									</div>

								</div>`;
			});

			if(data.length == per_page){
				ebookItems += `<div class="text-center mt-4">
					<button class="btn btn-light border-primary" id="load_more" onclick="loadMoreEbooks(event)">Load More</button>
				</div>`;
			}

			containerListEbook.innerHTML += ebookItems;
		}
	};
	xhr.send();
}

getAllEbooks();

/**
 * Load more ebooks when the button is clicked
 * @return {void}
 */
function loadMoreEbooks(e){
	fromFilterFirst = false;

	console.log(e.target.classList.add('d-none'));
	e.preventDefault();

	page++;
	getAllEbooks();
}

/**
 * Save data
 * @return {void}
 */
function saveData(){

	btnSimpan.innerText = "Loading...";
	btnSimpan.setAttribute("disabled", "disabled");

	let formData = new FormData();
	formData.append("title", document.getElementById("title").value);
	formData.append("description", document.getElementById("description").value);
	formData.append("teacher_id", document.getElementById("teacher_id").value);
	formData.append("csrf_token_name", document.querySelector('meta[name="csrf_token"]').getAttribute('content'));
	formData.append("ebooks", JSON.stringify(getSelectedEbooks()));

	let xhr = new XMLHttpRequest();
	xhr.open("POST", BASE_URL + "api/api_ebook/saveEbookListGuru", true);
	xhr.onreadystatechange = function () {
		if (xhr.readyState === 4 && xhr.status === 200) {
			let response = JSON.parse(xhr.responseText);
			if(response.success){
				swal.fire({
					title: "Success",
					text: "Data saved successfully",
					icon: "success",
					type: "success",
					confirmButtonText: "OK"
				}).then(() => {
					window.location.href = BASE_URL + "ebook/ebook_list_guru";
				});
			}else{
				swal.fire({
					title: "Error",
					text: "Failed to save data.",
					icon: "error",
					type: "error",
					confirmButtonText: "OK"
				});
			}
			btnSimpan.innerText = "Simpan";
			btnSimpan.removeAttribute("disabled");
		}
	};
	xhr.send(formData);
}	

/**
 * Get selected ebooks
 * @returns {Array}
 */
function getSelectedEbooks(){
	let selectedEbooks = [];
	let checkboxes = document.querySelectorAll('input[name="a_check[]"]:checked');
	checkboxes.forEach((checkbox) => {
		selectedEbooks.push(checkbox.value);
	});
	return selectedEbooks;
}

// =============================== FILTER ====================================
let timer = null;
let ebookTitleSearch = document.getElementById("a_title");
ebookTitleSearch.addEventListener("input", function() {
	fromFilterFirst = true;
	// clearTimeout(timer); // reset timer setiap kali user mengetik
	// timer = setTimeout(function() {
		// if (ebookTitleSearch.value.length > 2) {
			page = 1; // reset page number
			// clear list ebook
			if (fromFilterFirst) {
				document.getElementById("daftar_ebook").innerHTML = '';
			}
			getAllEbooks();
		// }
	// }, 300);
});

// GET EBOOK CATEGORY
/**
 * Get Subjects From api
 * @date 19/08/2025 - 11:04:00 PM
 *
 * @async
 * @returns {Response}
 */

const getCategory = async () => {
	let page = 1;
	let perPage = 100;
	let withChildren = false;
	let onlyMainCategory = true;

	try {
		// const url = new URL(`${ADMIN_URL}/api/subject/getAll?sekolah_id=${sekolah_id}`);
		const url = new URL(`${BASE_URL}/api/Api_ebook/getAllCategories?page=${page}&per_page=${perPage}&with_children=${withChildren}&only_main_category=${onlyMainCategory}`);
		const f = await fetch(url.href);

		return await f.json();
	} catch (err) {
		console.log(err);
	}
}

// JALANKAN FUNGSI KATEGORI
(async function () {
	const materi = [...(await getCategory()).data.fetch].map(x => ({ id: x.id, text: x.category_name }));

	// Materi
	$('select[name="select-category"]').select2({
		theme: "bootstrap-5",
		data: materi,
		placeholder: 'Pilih category',
		allowClear: true,
		dropdownParent: $("#modal-add"),
		dropdownAutoWidth: true
	});
	$('select[name="select-category"]').val(null).trigger('change');

	// Filter
	$('#select-category').select2({
		theme: "bootstrap-5",
		data: materi,
		placeholder: 'Pilih Kategori',
		allowClear: true
	})
		.on('select2:clear', e => {

		});

	$('#select-category').val(null).trigger('change');
}
)();

// KLIK FILTER KATEGORI
$('#select-category').on('change', function() {
	page = 1; // reset page number
	// clear list ebook
	document.getElementById("daftar_ebook").innerHTML = '';
	getAllEbooks();
});
