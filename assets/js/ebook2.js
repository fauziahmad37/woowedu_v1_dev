let classId = document.getElementById('class_id').value;


// CHECK USER APAKAH USER BARU ATAU USER LAMA
function checkUserNewOrOld() {
	return new Promise((resolve, reject) => {
		let isNewUser = (document.getElementById('is_new_user').value == '1') ? true : false;
		resolve(isNewUser);
	});
}

checkUserNewOrOld().then((isNewUser) => {
	if (isNewUser) {
		// Tampilkan modal onboarding
		setTimeout(() => {
			$('#onBoardingSteps').modal('show');
		}), 1000;
	}
});

// END CHECK USER BARU ATAU LAMA

// EVENT LISTENER UNTUK BUTTON SELANJUTNYA DI MODAL ONBOARDING
let btnSelanjutnya = document.getElementsByClassName('btn-selanjutnya')[0];
btnSelanjutnya.addEventListener('click', function (e) {
	let carouselIndicator = $(e.currentTarget).closest('.modal-content').find('.carousel-indicators button');

	// jika carousel data-bs-slide-to 2 ubah tulisan button selanjutnya menjadi selesai dan close modal
	if (carouselIndicator.length > 2) {
		let activeIndex = Array.from(carouselIndicator).findIndex(el => el.classList.contains('active'));
		if (activeIndex === 2) {
			btnSelanjutnya.innerHTML = 'Mulai Cari Buku';
			btnSelanjutnya.setAttribute('data-bs-dismiss', 'modal');
			btnSelanjutnya.removeAttribute('data-bs-target');
		} else {
			btnSelanjutnya.disabled = false;
			btnSelanjutnya.innerHTML = 'Selanjutnya';
			btnSelanjutnya.classList.add('btn-primary');
			btnSelanjutnya.classList.remove('btn-success');
			btnSelanjutnya.removeAttribute('data-bs-dismiss');
		}
	}
});


document.addEventListener('DOMContentLoaded', function () {
	splideJs('#thumbnail-carousel-rekomendasi');
	splideJs('#thumbnail-carousel-terbaru');
	splideJs('#thumbnail-carousel-terbanyak-dibaca');

});

// SPLIDE JS PENERBIT UNGGULAN
splideJs('#thumbnail-carousel-penerbit-unggulan');

// SPLIDE JS BUNDLING PACKAGE
splideJs('#thumbnail-carousel-paket-bundling');

function splideJs($id) {
	new Splide($id, {
		fixedWidth: 160,
		fixedHeight: 280,
		gap: 10,
		rewind: true,
		pagination: false,
	}).mount();
}

var arrPage = [];

// SEARCH BUKU
$('form[name="frm-search"]').on('submit', e => {
	e.preventDefault();

	load_data();
	pagination();
});

// RESET FILTER
$('.reset-filter').on('click', e => {
	window.location.href = 'ebook';
});

$('#lihat-rekomendasi').on('click', e => {
	console.log('tes')
	e.preventDefault();

	load_data();
	pagination();
});

function load_data(page = 1, limit = 12) {
	let title = $('#title').val();
	let publisher = $('#publisher').val();
	let data = {};
	data.title = title;
	data.penerbit = publisher;
	data.classLevels = classLevels; // belum di pake di model list.

	$.ajax({
		type: "GET",
		// url: BASE_URL + "ebook/list",
		url: BASE_URL + "api/api_ebook/getAllEbooks",
		dataType: 'JSON',
		async: false, // async nya di matika supaya arrPage nya terisi data dulu daru pagination nya di load
		data: {
			count: limit,
			page: page,
			filter: data,
			title: title,
			publisher_id: publisher
		},
		success: function (response) {
			$('#title-pencarian').html('');
			$('#container-hasil-pencarian').html('');
			$('#ebook-siswa-onboarding').html('');
			$('#ebook-footer').addClass('d-none');

			let cardBook = '';
			response.data.fetch.forEach(el => {

				cardBook += `<div class="col-xl-2 col-lg-3 col-md-4 col-sm-4 col-xs-6">
								<a href="Ebook/detail/${el.id}" class="text-decoration-none">
								<div class="card rounded-3 border-light-subtle p-3 m-1 mb-3" style="width:160px; height: 270px;">
									<img class="rounded-3" src="${el.cover_img.includes('http') ? el.cover_img : 'assets/images/images-placeholder.png'}" alt="" width="128" height="172">
									<p class="fs-12 lh-1 mt-3 mb-2 book-publiher-card">Penerbit: ${el.publisher.publisher_name}</p>
									<p class="fs-14 mb-0 book-title-card">${el.title}</p>
								</div>
								</a>
							</div>`;


			});

			$('#container-hasil-pencarian').html(cardBook);

			// ########################## PAGINATION JS ##############################
			arrPage = [];
			for (let i = 1; i <= response.data.total_data; i++) {
				arrPage.push(i);
			}

			// TITLE PENCARIAN
			$('#title-pencarian').html(`Menampilkan ${response.data.fetch.length} dari ${response.data.total_data} hasil pencarian untuk "${title}"`);
		}
	});
}


function pagination() {
	// ########################## PAGINATION JS ##############################
	$('#page').pagination({
		// dataSource: [1, 2, 3, 4, 5, 6, 7, 8,9,10,11,12,13,14,15,16,17,18,19,20],
		dataSource: arrPage,
		className: 'paginationjs-theme-blue paginationjs-big',
		pageSize: 12,
		callback: function (data, pagination) {
			// template method of yourself
			// var html = template(data);
			// dataContainer.html(html);
			load_data(pagination.pageNumber);
		}
	})
}

// check gambar ada atau tidak di server
function checkImage(url) {
	var http = new XMLHttpRequest();
	http.open('HEAD', url, false);
	http.send();
	return http.status != 400;
}

function autocomplete(inp, arr) {
	/*the autocomplete function takes two arguments,
	the text field element and an array of possible autocompleted values:*/
	var currentFocus;
	/*execute a function when someone writes in the text field:*/
	inp.addEventListener("input", function (e) {
		var a, b, i, val = this.value;
		/*close any already open lists of autocompleted values*/
		closeAllLists();
		if (!val) { return false; }
		currentFocus = -1;
		/*create a DIV element that will contain the items (values):*/
		a = document.createElement("DIV");
		a.setAttribute("id", this.id + "autocomplete-list");
		a.setAttribute("class", "autocomplete-items");
		/*append the DIV element as a child of the autocomplete container:*/
		this.parentNode.appendChild(a);
		/*for each item in the array...*/
		for (i = 0; i < arr.length; i++) {
			/*check if the item starts with the same letters as the text field value:*/
			if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
				/*create a DIV element for each matching element:*/
				b = document.createElement("DIV");
				/*make the matching letters bold:*/
				b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
				b.innerHTML += arr[i].substr(val.length);
				/*insert a input field that will hold the current array item's value:*/
				b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
				/*execute a function when someone clicks on the item value (DIV element):*/
				b.addEventListener("click", function (e) {
					/*insert the value for the autocomplete text field:*/
					inp.value = this.getElementsByTagName("input")[0].value;
					/*close the list of autocompleted values,
					(or any other open lists of autocompleted values:*/
					closeAllLists();
				});
				a.appendChild(b);
			}
		}
	});
	/*execute a function presses a key on the keyboard:*/
	inp.addEventListener("keydown", function (e) {
		var x = document.getElementById(this.id + "autocomplete-list");
		if (x) x = x.getElementsByTagName("div");
		if (e.keyCode == 40) {
			/*If the arrow DOWN key is pressed,
			increase the currentFocus variable:*/
			currentFocus++;
			/*and and make the current item more visible:*/
			addActive(x);
		} else if (e.keyCode == 38) { //up
			/*If the arrow UP key is pressed,
			decrease the currentFocus variable:*/
			currentFocus--;
			/*and and make the current item more visible:*/
			addActive(x);
		} else if (e.keyCode == 13) {
			/*If the ENTER key is pressed, prevent the form from being submitted,*/
			e.preventDefault();
			if (currentFocus > -1) {
				/*and simulate a click on the "active" item:*/
				if (x) x[currentFocus].click();
			}
		}
	});
	function addActive(x) {
		/*a function to classify an item as "active":*/
		if (!x) return false;
		/*start by removing the "active" class on all items:*/
		removeActive(x);
		if (currentFocus >= x.length) currentFocus = 0;
		if (currentFocus < 0) currentFocus = (x.length - 1);
		/*add class "autocomplete-active":*/
		x[currentFocus].classList.add("autocomplete-active");
	}
	function removeActive(x) {
		/*a function to remove the "active" class from all autocomplete items:*/
		for (var i = 0; i < x.length; i++) {
			x[i].classList.remove("autocomplete-active");
		}
	}
	function closeAllLists(elmnt) {
		/*close all autocomplete lists in the document,
		except the one passed as an argument:*/
		var x = document.getElementsByClassName("autocomplete-items");
		for (var i = 0; i < x.length; i++) {
			if (elmnt != x[i] && elmnt != inp) {
				x[i].parentNode.removeChild(x[i]);
			}
		}
	}
	/*execute a function when someone clicks in the document:*/
	document.addEventListener("click", function (e) {
		closeAllLists(e.target);
	});
}

/*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
let countries;

$('#title').on('keyup', function (e) {
	$.ajax({
		type: "GET",
		url: "ebook/list",
		async: false,
		data: {
			count: 10,
			page: 1,
			filter: {
				title: e.currentTarget.value
			}
		},
		dataType: "JSON",
		success: function (response) {
			countries = response.data.map(function (item) {
				return item.title
			});

			autocomplete(document.getElementById("title"), countries);
		}
	});
});

// Get Banners
async function getBanner() {
	const ajax = await new XMLHttpRequest();
	ajax.open('GET', BASE_URL + 'ebook/getBanner', true);
	ajax.onload = function () {
		if (this.status === 200) {
			const response = JSON.parse(this.responseText);

			$('#carouselBanner .carousel-inner').html('');
			$('#carouselBanner .carousel-indicators').html('');

			let carouselItem = '';
			let carouselIndicator = '';

			response.forEach((el, index) => {
				let activeClass = index === 0 ? 'active' : '';
				carouselItem += `<div class="carousel-item top-0 rounded-5 ${activeClass}" data-bs-interval="5000">
									<!-- a href="Ebook/show_ebook_by_category?category=${el.banner_category}" target="_blank" -->
									<a href="ebook/storeBannerClick?id=${el.id}&url=${el.url}" target="_blank">
										<img src="${ADMIN_URL}assets/images/banner/${el.image_url}" class="d-block w-100 rounded-5" alt="${el.title}">
									</a>
								</div>`;
				carouselIndicator += `<button type="button" data-bs-target="#carouselBanner" data-bs-slide-to="${index}" class="bg-primary rounded-circle ${activeClass}" aria-current="true" aria-label="Slide ${index + 1}" style="height:10px; width: 10px;"></button>`;

			});

			$('#carouselBanner .carousel-inner').html(carouselItem);
			$('#carouselBanner .carousel-indicators').html(carouselIndicator);
		}
	};
	ajax.send();
}

getBanner();

// Fungsi untuk memunculkan modal highlight atau story
function showStory() {
	// Show modal highlight
	$('#highlightModal').modal('show');
}

function getHighlight() {

	// GET getHighlight
	const ajax = new XMLHttpRequest();
	ajax.open('GET', BASE_URL + 'ebook/getHighlight', true);
	ajax.onload = function () {
		if (this.status === 200) {

			// Parse the JSON response, response berupa gambar icon highlight dan judul highlight
			const response = JSON.parse(this.responseText);

			// document.querySelector('#thumbnail-highlight-fitur-platform > div').innerHTML = '';

			let highlightItem = '';
			let no = 0;
			response.forEach(el => {

				// GET getHighlightDetailToday, // untuk mendapatkan detail highlight hari ini. jika ada maka lingkarang gambar icon akan berwarna merah
				$.ajax({
					type: "GET",
					url: BASE_URL + 'ebook/getHighlightDetailToday?id=' + el.id,
					async: false,
					success: function (response) {

						let borderColor = '';
						if (response.length > 0) {
							borderColor = 'border-danger !important';
						} else {
							borderColor = '';
						}

						// komponen highlightItem berisi gambar icon lingkaran highlight dan judul highlight
						highlightItem += `
								<li class="splide__slide">	
									<div class="text-center" style="">
										<img class="show-story rounded-circle bg-primary border border-5 ${borderColor}" data-id="${el.id}" src="${(el.image) ? ADMIN_URL + '/assets/images/banner/' + el.image : 'assets/images/icons/logo-woowedu-putih.png'}" alt="" style="width:100px; height: 100px; object-fit: contain;">
									
										<p class="text-body-secondary mt-3">${el.title}</p>
									</div>
								</li>`;

						// komponen my-card yang bisa berputar atau begeser ketika di klik 
						$('.card-carousel').append(`<div class="my-card" data-id="${el.id}">
							<div class="card-header" style="background-color: rgba(0, 0, 0, 0.3); height: 100px; position: relative;">
								<div class="row px-3">
									${stripItemImage(response.length)}
								</div>

								<div class="ps-3 mt-2" style="">
									<img class="rounded-circle bg-primary border border-3" src="${(el.image) ? ADMIN_URL + '/assets/images/banner/' + el.image : 'assets/images/icons/logo-woowedu-putih.png'}" alt="" style="width:50px; height: 50px; object-fit: contain;">
									<span class="text-white fs-6 ms-2">${el.title}</span>
								</div>
							</div>

							<div class="card-body">
								<div class="row">
									${carouselStory(response, el.id)}
								</div>
							</div>
						</div>`);


						// fungsi untuk membuat strip item image
						// stripItemImage akan membuat strip item image sesuai dengan jumlah cerita yang ada pada highlight
						// jika jumlah cerita lebih dari 0 maka akan membuat strip item image sesuai dengan jumlah
						function stripItemImage(a) {
							if (a > 0) {
								let stripItem = '';
								for (let i = 0; i < a; i++) {
									stripItem += `<div class="col p-1"><span class="line-story ${(i == 0) ? 'seen' : ''}"></span></div>`;
								}
								return stripItem;
							} else {
								return '';
							}
						}

						// fungsi untuk membuat carousel story
						// carouselStory akan membuat carousel story sesuai dengan jumlah cerita yang ada pada highlight di mana setiap cerita akan ditampilkan dalam bentuk carousel item
						function carouselStory(a, parentCardActive) {

							if (a.length > 0) {
								let buttons = '';
								let image = '';
								for (let i = 0; i < a.length; i++) {
									buttons += `<button type="button" data-bs-target="#carouselExampleIndicators${i}" data-bs-slide-to="${i}" class="${i === 0 ? 'active' : ''}" aria-current="true" aria-label="Slide ${i + 1}"></button>`;
									image += `<div data-image-number="${i}" class="carousel-item ${i === 0 ? 'active' : ''}">
												<a href="ebook/detail/${a[i].ebook_id}" class="h-100">
													<img class="image-ebook-story" src="${(a[i].cover_img).includes('http') ? a[i].cover_img : 'assets/images/ebooks/cover/' + a[i].cover_img}" class="d-block" alt="${a[i].title}">
												</a>
											</div>`;
								}

								let carousel = `<div id="carouselExampleIndicators${no}" class="carousel slide">
									<div class="carousel-indicators">
										${buttons}
									</div>
									<div class="carousel-inner">
										${image}
									</div>
									<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators${no}" data-bs-slide="prev">
										<span class="carousel-control-prev-icon" aria-hidden="true"></span>
										<span class="visually-hidden">Previous</span>
									</button>
									<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators${no}" data-bs-slide="next">
										<span class="carousel-control-next-icon" aria-hidden="true"></span>
										<span class="visually-hidden">Next</span>
									</button>
								</div>`;

								// Disable button next pada carousel terakhir
							

								

								return carousel;
							} else {
								return '';
							}
						}

					}
				});

				no++;
			});

			// Ini adalah JS untuk menggeser atau memutar carousel highlight buku
			// Inisialisasi carousel highlight buku

			let $cards = $('.my-card');
			let total = $cards.length;
			let centerIndex = Math.floor(total / 2);

			// Inisialisasi awal
			updateClasses(centerIndex);

			// Fungsi untuk update class berdasarkan index aktif
			function updateClasses(activeIndex) {
				$cards.removeClass('prev active next');

				let prevIndex = (activeIndex - 1 + total) % total;
				let nextIndex = (activeIndex + 1) % total;

				$cards.eq(activeIndex).addClass('active');
				$cards.eq(prevIndex).addClass('prev');
				$cards.eq(nextIndex).addClass('next');
			}

			// Klik kartu
			$cards.click(function () {
				let clickedIndex = $cards.index(this);
				let activeIndex = $cards.index($('.active'));

				// hanya jika klik di kartu sebelah kiri/kanan dari yang aktif
				if ($(this).hasClass('prev') || $(this).hasClass('next')) {
					updateClasses(clickedIndex);
				}
			});

			// Keyboard navigation
			// $('html body').keydown(function (e) {
			// 	let currentIndex = $cards.index($('.active'));

			// 	if (e.keyCode === 37) { // Left arrow
			// 		let newIndex = (currentIndex - 1 + total) % total;
			// 		updateClasses(newIndex);
			// 	} else if (e.keyCode === 39) { // Right arrow
			// 		let newIndex = (currentIndex + 1) % total;
			// 		updateClasses(newIndex);
			// 	}
			// });

			// Tombol next/prev opsional
			$('#prevBtn').click(() => {
				let currentIndex = $cards.index($('.active'));
				let newIndex = (currentIndex - 1 + total) % total;
				updateClasses(newIndex);
			});
			$('#nextBtn').click(() => {
				let currentIndex = $cards.index($('.active'));
				let newIndex = (currentIndex + 1) % total;
				updateClasses(newIndex);
			});

			//  end, Ini adalah JS untuk menggeser atau memutar carousel highlight buku

			// document.querySelector('#thumbnail-highlight-fitur-platform > div').innerHTML = highlightItem;
			document.querySelector('#list-highlight-fitur-platform').innerHTML = highlightItem;

			// Inisialisasi Splide setelah isi ditambahkan ke DOM
			new Splide('#thumbnail-highlight-fitur-platform', {
				fixedWidth: 175,
				fixedHeight: 150,
				gap: 5,
				rewind: false,
				pagination: false,
				breakpoints: {
					2560: {
						fixedWidth: 235,
					},
					1440: {
						fixedWidth: 175,
						// perPage: 4,
					},
					1024: {
						fixedWidth: 175,
					},
					375: {
						fixedWidth: 125,
						fixedHeight: 100
					}
				}
			}).mount();
		}
	};
	ajax.send();
}

getHighlight();

// Tampilkan cerita saat klik pada highlight
$(document).on('click', '.show-story', function (e) {
	let id = e.currentTarget.dataset.id;
	$('.my-card').removeClass('active prev next');
	$('.my-card[data-id="' + id + '"]').addClass('active');
	$('.my-card').each(function (index) {
		if ($(this).hasClass('active')) {
			$(this).prev().addClass('prev');
			$(this).next().addClass('next');
		}
	});

	// Tampilkan modal highlight
	showStory();
});

// aksi ketika carousel-control-next-icon diklik
$(document).on('click', '.carousel-control-next-icon', function (e) {
	let lineStory = $(e.currentTarget).closest('.my-card').find('.line-story'); // mengambil semua elemen dengan class line-story dalam my-card yang aktif
	let carouselIndicator = $(e.currentTarget).closest('.my-card').find('.carousel-indicators button'); // mengambil semua elemen dengan class carousel-indicators button dalam my-card yang aktif

	// menandai cerita yang sudah dilihat
	for (let i = 0; i < carouselIndicator.length; i++) {
		// jika carousel-indicator button memiliki class active
		if (carouselIndicator[i].classList.contains('active')) {
			// tambahkan class seen pada line-story yang sesuai dengan index carousel-indicator button
			for (let j = 0; j <= lineStory.length; j++) {
				$(lineStory[j]).removeClass('seen');
			}
			$(lineStory[i]).addClass('seen');

		}
	}
});

// aksi ketika carousel-control-prev-icon diklik
$(document).on('click', '.carousel-control-prev-icon', function (e) {
	let lineStory = $(e.currentTarget).closest('.my-card').find('.line-story'); // mengambil semua elemen dengan class line-story dalam my-card yang aktif
	let carouselIndicator = $(e.currentTarget).closest('.my-card').find('.carousel-indicators button'); // mengambil semua elemen dengan class carousel-indicators button dalam my-card yang aktif
	console.log(lineStory)
	// menandai cerita yang sudah dilihat
	for (let i = 0; i < carouselIndicator.length; i++) {
		// jika carousel-indicator button memiliki class active
		if (carouselIndicator[i].classList.contains('active')) {
			// tambahkan class seen pada line-story yang sesuai dengan index carousel-indicator button
			for (let j = 0; j <= lineStory.length; j++) {
				$(lineStory[j]).removeClass('seen');
			}
			$(lineStory[i]).addClass('seen');

		}
	}
});

// ================================ PILIHAN KATEGORI MENARIK ==================================== 

function getPilihanKategoriMenarik() {
	const ajax = new XMLHttpRequest();
	let data = {
		per_page: 5,
		page: 1,
	}
	ajax.open('GET', BASE_URL + 'api/api_ebook/getPilihanKategoriMenarik', true);
	ajax.onload = function () {
		if (this.status === 200) {
			const response = JSON.parse(this.responseText);
			let kategoriItem = '';
			response.data.forEach(el => {
				kategoriItem += `
							<li class="splide__slide">
								
								<a href="ebook/show_ebook_by_category?category=${el.category_name}&id=${el.id}" class="text-decoration-none">
									<div class="p-3 text-center">
										<img src="${el.category_image}" alt="" style="width:100px; height: 100px;">
										<h5 class="text-body-dark mt-3">${el.category_name}</h5>
									</div>
								</a>
								
							</li>`;
			});

			// STATIC KATEGORI UNTUK LAINNYA
			kategoriItem += `<li class="splide__slide">
								<a href="ebook/show_ebook_by_category?category=Lainnya&id=0" class="text-decoration-none">
									<div class="p-3 text-center">
										<img src="assets/images/icons/lainnya.png" alt="" style="width:100px; height: 100px;">
										<h5 class="text-body-dark mt-3">Lainnya</h5>
									</div>
								</a>
							</li>`;

			document.querySelector('#list-pilihan-kategori-menarik').innerHTML = kategoriItem;

			// Inisialisasi Splide setelah isi ditambahkan ke DOM
			const splide = new Splide('#thumbnail-pilihan-kategori-menarik', {
				fixedWidth: 188,
				fixedHeight: 200,
				gap: 10,
				rewind: false,
				pagination: false,
				breakpoints: {
					2560: {
						fixedWidth: 300,
					},
					1440: {
						fixedWidth: 200,
						perPage: 4,
					},
					1024: {
						fixedWidth: 175,
					},
					768: {
						fixedWidth: 150,
						perPage: 3,
					},
					375: {
						fixedWidth: 100,
						fixedHeight: 100,
						perPage: 3,
					}
				}
			}).mount();

		}
	};
	ajax.send(JSON.stringify(data));
}

getPilihanKategoriMenarik();

// ================================ END ==================================== 

// GET PROMO TERBATAS
function getPromoTerbatas() {
	const ajax = new XMLHttpRequest();
	ajax.open('GET', BASE_URL + 'ebook/getPromoTerbatas?per_page=5', true);
	ajax.onload = function (e) {
		if (this.status === 200) {
			const response = JSON.parse(this.responseText);

			let promoItem = '';
			response.data.fetch.forEach(el => {
				promoItem += `<li class="splide__slide">
								<a href="Ebook/storePromoTerbatasClick?ebook_id=${el.ebook_id}" class="text-decoration-none">
									<div class="card h-100 rounded-4 border-light-subtle p-3 m-1">
										<div class="timer-promo fw-bold text-danger bg-danger-subtle rounded-pill p-1 mb-3" style="font-size: 10px; width:fit-content; padding-left: 8px !important; padding-right: 8px !important;" data-end="${el.end_date}">${timerPromo(el.end_date)}</div>
										<img class="rounded-3" src="${(el.cover_img.includes('http') ? el.cover_img : 'assets/images/ebooks/cover/' + el.cover_img)}" alt="" style="width:100%; height: 172px; object-fit: contain;">
										<p class="fs-12 lh-1 mt-3 mb-2 book-publiher-card">Penerbit: ${el.publisher_name}</p>
										<p class="fs-12 fw-bold mb-0 book-title-card">${el.title}</p>

										${hargaPromo(el)}
										
									</div>
								</a>
							</li>`;
			});

			document.querySelector('#list-promo-terbatas').innerHTML = promoItem;

			// Inisialisasi Splide setelah isi ditambahkan ke DOM
			new Splide('#thumbnail-carousel-promo-terbatas', {
				fixedWidth: 188,
				fixedHeight: 375,
				gap: 10,
				rewind: true,
				pagination: false,
			}).mount();
		}
	}

	ajax.send();

	function timerPromo(endDate) {

		const end = new Date(endDate);
		// now gmt +7
		const now = new Date();
		now.setHours(now.getHours() + 7);
		const diff = end - now;

		if (diff <= 0) {
			return 'Promo telah berakhir';
		}

		const days = Math.floor(diff / (1000 * 60 * 60 * 24));
		let hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		let minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
		let seconds = Math.floor((diff % (1000 * 60)) / 1000);

		// jika minutes < 10 tambahkan 0 didepannya
		if (hours < 10) {
			hours = '0' + hours;
		}
		if (minutes < 10) {
			minutes = '0' + minutes;
		}
		if (seconds < 10) {
			seconds = '0' + seconds;
		}

		return `<i class="bi bi-clock-fill"> </i> ${days}d ${hours}:${minutes}:${seconds}`;
	}

	// hitung mundur untuk promo terbatas
	setInterval(() => {
		const timers = document.querySelectorAll('.timer-promo');
		timers.forEach(timer => {
			const endDate = timer.getAttribute('data-end');
			timer.innerHTML = timerPromo(endDate);
		});
	}, 1000);
}
getPromoTerbatas();

// Fungsi untuk menampilkan harga promo
function hargaPromo(el) {
	// jika tipe disokon nominal
	if (el.discount_type === 'nominal') {
		let hargaDiskon = parseInt(el.price) - parseInt(el.discount_value);
		return `<div class="d-flex align-items-center mt-3" style="font-size: 11px;">
					<span class="text-danger bg-danger-subtle border border-danger rounded-pill me-2" style="padding: 2px 8px;">${numberWithCommas(parseInt(el.discount_value))}</span>
					<span class="text-danger text-decoration-line-through">Rp ${numberWithCommas(parseInt(el.price))}</span>
				</div>
				<p class="fw-bold mt-1 mb-0 book-price-card">Rp. ${numberWithCommas(parseInt(hargaDiskon))}</p>`;
	} else {
		let hargaDiskon = parseInt(el.price) - (parseInt(el.price) * (parseInt(el.discount_value) / 100));
		return `<div class="d-flex align-items-center mt-3" style="font-size: 11px;">
					<span class="text-danger bg-danger-subtle border border-danger rounded-pill me-2" style="padding: 2px 8px;">${numberWithCommas(parseInt(el.discount_value))}%</span>
					<span class="text-danger text-decoration-line-through">Rp ${numberWithCommas(parseInt(el.price))}</span>
				</div>
				<p class="fw-bold mt-1 mb-0 book-price-card">Rp. ${numberWithCommas(parseInt(hargaDiskon))}</p>`;
	}
}

// END HARGA PROMO

// Fungsi untuk membuat number separator
function numberWithCommas(x) {
	return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// FUNGSI UNTUK MENGAMBIL DATA PENERBIT UNGGULAN
function getPenerbitUnggulan() {
	const ajax = new XMLHttpRequest();
	ajax.open('GET', BASE_URL + 'ebook/getPenerbitUnggulan', true);
	ajax.onload = function () {
		if (this.status === 200) {
			const response = JSON.parse(this.responseText);

			let penerbitItem = '';
			response.forEach(el => {
				penerbitItem += `<div class="splide__slide text-center">
									<a href="Ebook/show_ebook_by_category?publisher_id=${el.id}&category=publisher" class="text-decoration-none">
										<div class="card rounded-4 border-light-subtle p-3 m-1 mb-3 me-5" style="width:212px; height: 216px;">
											<img class="rounded-4" src="${(el.company_logo.includes('http') ? el.company_logo : ADMIN_URL + 'assets/images/publisher/' + el.company_logo)}" alt="" style="width: 128px; height: 128px; object-fit: contain; align-self: center;">
											<p class="fs-14 fw-bold lh-2 mt-3 mb-2 publisher-name">Penerbit: ${el.publisher_name}</p>
										</div>
									</a>
								</div>`;
			});

			document.querySelector('#list-penerbit-unggulan').innerHTML = penerbitItem;

			// Inisialisasi Splide untuk penerbit unggulan
			new Splide('#thumbnail-carousel-penerbit-unggulan', {
				fixedWidth: 212,
				fixedHeight: 220,
				gap: 40,
				rewind: false,
				pagination: false,
				breakpoints: {
					375: {
						fixedWidth: 136,
						fixedHeight: 130,
						gap: 10,
					}
				}
			}).mount();
		}
	};
	ajax.send();

}

getPenerbitUnggulan();

// FUNGSI UNTUK EBOOK LIST REKOMENDASI GURU
function getEbookByGuruRecommendation() {
	let xhr = new XMLHttpRequest();
	xhr.open("GET", BASE_URL + "api/Api_ebook/ebookListRekomendasiGuru?class_id=" + document.getElementById('class_id').value);
	xhr.addEventListener("readystatechange", function () {
		if (this.readyState === 4) {
			const response = JSON.parse(this.responseText);
			const data = response.data;

			let ebookItem = '';
			for (let i = 0; i < data.length; i++) {
				const el = data[i];
				ebookItem += `<li class="splide__slide">
					<a href="Ebook/detail_ebook_list_guru/${el.id}" class="text-decoration-none">
						<div class="card-item border-primary rounded-4 bg-primary-subtle border p-3 text-primary" style="" data-id="${el.id}">
							<p class="fw-bold" style="font-size: 18px;">${el.title}</p>
							<p>${el.description}</p>
							<div class="d-flex">
								<div class="me-3">
									<i class="bi bi-journal-bookmark-fill me-2" style="width: 14px; height: 14px;"></i>${el.ebooks.length}
									Ebook
								</div>
								<div>
									<i class="bi bi-person-fill me-2" style="width: 14px; height: 14px;"></i> Guru ${el.teacher_name}
								</div>
							</div>
						</div>
					</a>
				</li>`;
			}

			document.querySelector('#list-ebook-rekomendasi-guru').innerHTML = ebookItem;

			// Inisialisasi Splide untuk buku rekomendasi
			new Splide('#thumbnail-carousel-rekomendasi-guru', {
				fixedWidth: 350,
				fixedHeight: 175,
				gap: 10,
				rewind: true,
				pagination: false,
			}).mount();
		}
	});
	xhr.send();
}

if (classId) getEbookByGuruRecommendation();


// FUNGSI UNTUK MENGAMBIL BUKU REKOMENDASI
function getEbookByClassId() {
	const ajax = new XMLHttpRequest();
	ajax.open('GET', BASE_URL + `api/Api_ebook/getEbookByClassId?class_id=${classLevels}&page=1&per_page=10`, true);
	ajax.onload = function () {
		if (this.status === 200) {
			const response = JSON.parse(this.responseText);
			const data = response.data.fetch;

			let ebookItem = '';
			for (let i = 0; i < data.length; i++) {
				const el = data[i];
				ebookItem += `<li class="splide__slide">
					<a href="Ebook/detail/${el.id}" class="text-decoration-none">
						<div class="card rounded-3 border-light-subtle p-3 m-1">
							<img class="rounded-3" src="${(el.cover_img.includes('http') ? el.cover_img : 'assets/images/ebooks/cover/' + el.cover_img)}" alt="">
							<p class="fs-12 lh-1 mt-3 mb-2 book-publiher-card">Penerbit: ${el.publisher.publisher_name}</p>
							<p class="fs-14 mb-0 book-title-card">${el.title}</p>
						</div>
					</a>
				</li>`;
			}

			document.querySelector('#list-ebook-rekomendasi').innerHTML = ebookItem;

			// Inisialisasi Splide untuk buku rekomendasi
			new Splide('#thumbnail-carousel-rekomendasi', {
				fixedWidth: 160,
				fixedHeight: 280,
				gap: 10,
				rewind: true,
				pagination: false,
			}).mount();
		}
	};
	ajax.send();
}

getEbookByClassId();


// FUNGSI UNTUK MENGAMBIL BUKU TERBARU ATAU NEWEST BOOKS
function getNewestBooks() {
	const ajax = new XMLHttpRequest();
	ajax.open('GET', BASE_URL + 'api/Api_ebook/getEbookByNewest?order_by_newest=true&page=1&per_page=10', true);
	ajax.onload = function () {
		if (this.status === 200) {
			const response = JSON.parse(this.responseText);
			const data = response.data.fetch;

			let newestItem = '';
			for (let i = 0; i < data.length; i++) {
				const el = data[i];
				newestItem += `<li class="splide__slide">
									<a href="Ebook/detail/${el.id}" class="text-decoration-none">
										<div class="card rounded-3 border-light-subtle p-3 m-1">
											<img class="rounded-3" src="${(el.cover_img.includes('http') ? el.cover_img : 'assets/images/ebooks/cover/' + el.cover_img)}" alt="" width="128" height="172">
											<p class="fs-12 lh-1 mt-3 mb-2 book-publiher-card">Penerbit: ${el.publisher.publisher_name}</p>
											<p class="fs-14 mb-0 book-title-card">${el.title}</p>
										</div>
									</a>
								</li>`;
			}

			document.querySelector('#list-newest-books').innerHTML = newestItem;

			// Inisialisasi Splide untuk buku terbaru
			new Splide('#thumbnail-carousel-terbaru', {
				fixedWidth: 160,
				fixedHeight: 280,
				gap: 10,
				rewind: true,
				pagination: false,
			}).mount();
		}
	};
	ajax.send();

}
getNewestBooks();


// =============================== PALING BANYAK DIBACA ================================
function getMostReadBooks() {
	var xhr = new XMLHttpRequest();
	xhr.open("GET", BASE_URL + "api/Api_ebook/getMostRead");
	xhr.addEventListener("readystatechange", function () {
		if (this.readyState === 4) {
			document.querySelector('#testimonial-container').innerHTML = ''; // Kosongkan container testimonial sebelum menambahkan yang baru

			const response = JSON.parse(this.responseText);
			const data = response.data.fetch;

			// looping buku-buku yang paling banyak dibaca, untuk mengambil testimonial dari masing-masing buku
			// Ganti 155 dengan el.id jika ingin mengambil data berdasarkan id buku
			// dan juga looping untuk di gunakan sebagai list buku terbanyak di baca

			let mostReadListBooks = '';
			for (let i = 0; i < data.length; i++) {
				const el = data[i];

				// Tambahkan buku ke list buku terbanyak dibaca
				mostReadListBooks += `<li class="splide__slide">
										<a href="Ebook/detail/${el.id}" class="text-decoration-none">
											<div class="card rounded-3 border-light-subtle p-3 m-1">
												<img class="rounded-3" src="${(el.cover_img.includes('http') ? el.cover_img : 'assets/images/ebooks/cover/' + el.cover_img)}" alt="" width="128" height="172">
												<p class="fs-12 lh-1 mt-3 mb-2 book-publiher-card">Penerbit: ${el.publisher.publisher_name}</p>
												<p class="fs-14 mb-0 book-title-card">${el.title}</p>
											</div>
										</a>
									</li>`;
			}
			document.querySelector('#list-most-read').innerHTML = mostReadListBooks;

			// Inisialisasi Splide untuk buku terbanyak dibaca
			new Splide('#thumbnail-carousel-terbanyak-dibaca', {
				fixedWidth: 160,
				fixedHeight: 280,
				gap: 10,
				rewind: true,
				pagination: false,
			}).mount();
		}
	});
	xhr.send();

}

getMostReadBooks();

//================================ END PALING BANYAK DIBACA ================================

// ============================== TESTIMONIAL ================================
function getTestimonial() {
	
	// GET Data Testimonial
	let xhr2 = new XMLHttpRequest();
	// xhr2.open("GET", BASE_URL + "api/Api_ebook/getRating?book_id=" + el.id + "&rating_level=5,4");
	xhr2.open("GET", BASE_URL + "api/Api_ebook/getRating?book_id=" + 155 + "&rating_level=5,4&page=1&per_page=3"); // Ganti 155 dengan el.id jika ingin mengambil data berdasarkan id buku
	xhr2.addEventListener("readystatechange", function () {
		if (this.readyState === 4) {


			const response2 = JSON.parse(this.responseText);
			const data2 = response2.data.fetch;

			if (data2.length > 0) {
				let testimonialItem = '';
				data2.forEach((item, i) => {
					testimonialItem += `<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
											<div class="card mb-2" style="border-radius: 12px;">
												<div class="card-body d-flex">
													<div class="me-2"><img src="${(item.photo) ? item.photo : 'assets/images/user.png'}" alt="" width="28" class="rounded-circle"></div>
													<div class="ms-2">
														<p class="card-author fs-14 fw-bold">${item.full_name} <span class="fs-12 fw-light"><i class="bi-dot"></i> SMP Woowedu</span></p>
														${ratingStars(item.rate)}
														<p class="card-text mt-2 komentar-testi">"${item.komentar}"</p>
													</div>
												</div>
											</div>
										</div>`;
				});

				document.querySelector('#testimonial-container').innerHTML += testimonialItem;
			}
		}
	});
	xhr2.send();
}
getTestimonial();

function ratingStars(rate) {
	let stars = '';
	for (let i = 1; i <= 5; i++) {
		if (i <= rate) {
			stars += '<i class="bi bi-star-fill text-warning"></i>';
		} else {
			stars += '<i class="bi bi-star text-secondary"></i>';
		}
	}
	return stars;
}

// =============================== END TESTIMONIAL ================================

// FUNGSI UNTUK MENGAMBIL DATA PAKET BUNDLING
function getPaketBundling() {
	const ajax = new XMLHttpRequest();
	ajax.open('GET', BASE_URL + 'api/api_ebook/getBundlingPackages?page=1&per_page=10', true);
	ajax.onload = function () {
		if (this.status === 200) {
			const response = JSON.parse(this.responseText);

			let paketItem = '';
			response.data.fetch.forEach(el => {
				paketItem += `<li class="splide__slide">
									<a href="BundlingPackage/detail/${el.id}" class="text-decoration-none">
										<div class="card rounded-3 border-light-subtle p-3 m-1">
											<img loading="lazy" class="rounded-3" src="${el.package_image.includes('http') ? el.package_image : 'assets/images/bundlings/' + el.package_image}" alt="" style="width:340px; height:220px; object-fit:fill;">
											<p class="fs-12 lh-1 mt-3 mb-2 book-publiher-card">Penerbit: ${el.publisher.publisher_name}</p>
											<p class="fs-14 mb-0 book-title-card">${el.package_name}</p>
											<button class="btn btn-primary text-white mt-3">Detail Paket Bundling</button>
										</div>
									</a>
								</li>`;
			});

			document.querySelector('#list-paket-bundling').innerHTML = paketItem;

			// Inisialisasi Splide untuk paket bundling
			new Splide('#thumbnail-carousel-paket-bundling', {
				fixedWidth: 372,
				fixedHeight: 378,
				gap: 10,
				rewind: true,
				pagination: false,
			}).mount();
		}
	};
	ajax.send();
}

getPaketBundling();
