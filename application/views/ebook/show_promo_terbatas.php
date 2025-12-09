<section id="show-by-category">

	<input type="hidden" name="category_name" value="<?= $category ?>">

	<div class="banner mt-4 <?= (strtolower($category) == '') ? 'd-none' : '' ?>">
		<img src="<?= $banner ?>" alt="" height="350" class="w-100">
	</div>

	<div class="list-book mt-5 row"></div>
	<div id="pagination" class="mt-4 text-center">
		<button class="btn btn-primary" id="load-more">Lihat Lebih Banyak <i class="bi bi-chevron-down"></i></button>
	</div>
</section>

<script>
	const categoryName = document.querySelector('input[name="category_name"]').value;
	const loadMoreButton = document.getElementById('load-more');
	let currentPage = 1;

	loadMoreButton.addEventListener('click', function() {
		const nextPage = currentPage + 1;

		fetchBooksByCategory(categoryName, nextPage);
		loadMoreButton.dataset.page = nextPage;
	});

	document.addEventListener('DOMContentLoaded', function() {
		fetchBooksByCategory(categoryName);
	});

	function fetchBooksByCategory(category = '', page = 1) {
		const listBook = document.querySelector('.list-book');

		// fetch(`<?//= base_url('ebook/get_books_promo_terbatas') ?>?category=${category}&page=${page}`)
		fetch(`<?= base_url('ebook/getPromoTerbatas') ?>?per_page=10&page=${page}`)
			.then(response => response.json())
			.then(data => {
				if (data.data.fetch.length > 0) {
					data.data.fetch.forEach(book => {
						const bookItem = document.createElement('div');
						bookItem.className = 'col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6 mb-4';
						bookItem.innerHTML = `
							
									<div class="card rounded-4 border-light-subtle p-3 m-1 text-center" style="height: 100%; width: 160px;">
										<a href="<?= base_url('ebook/detail/') ?>${book.ebook_id}" class="text-decoration-none">
											<div class="timer-promo fw-bold text-danger bg-danger-subtle rounded-pill p-1 mb-3" style="font-size: 10px; width:fit-content; padding-left: 8px !important; padding-right: 8px !important;" data-end="${book.end_date}">${timerPromo(book.end_date)}</div>
											<img class="rounded-3" src="${(book.cover_img.includes('http') ? book.cover_img : 'assets/images/ebooks/cover/' + book.cover_img)}" alt="" width="128" height="172" style="align-self: center;">
											<p class="fs-12 text-start lh-1 mt-3 mb-2 book-publiher-card" style="color: #74788D;">Penerbit: ${book.publisher_name}</p>
											<p class="fs-12 text-start fw-bold mb-0 book-title-card" style="color: #1D1F2C;">${book.title}</p>

											${hargaPromo(book)}
										</a>
									</div>
								
						`;
						listBook.appendChild(bookItem);
					});
					currentPage = page;


				} else {
					// disable the load more button if no more books are available
					loadMoreButton.disabled = true;
					loadMoreButton.textContent = 'Tidak ada buku lagi';
				}
			})
			.catch(error => {
				console.error('Error fetching books:', error);
				listBook.innerHTML = '<p>Error loading books. Please try again later.</p>';
			});
	}

	// Fungsi untuk menampilkan harga promo
	function hargaPromo(el) {
		// jika tipe disokon nominal
		if (el.discount_type === 'nominal') {
			let hargaDiskon = el.price - el.discount_value;
			return `<div class="d-flex align-items-center mt-3" style="font-size: 11px;">
					<span class="text-danger bg-danger-subtle border border-danger rounded-pill me-2" style="padding: 2px 8px;">${numberWithCommas(el.discount_value)}</span>
					<span class="text-danger text-decoration-line-through">Rp ${numberWithCommas(el.price)}</span>
				</div>
				<p class="fw-bold text-start mt-1 mb-0 book-price-card">Rp. ${numberWithCommas(hargaDiskon)}</p>`;
		} else {
			let hargaDiskon = el.price - (el.price * (el.discount_value / 100));
			return `<div class="d-flex align-items-center mt-3" style="font-size: 11px;">
					<span class="text-danger bg-danger-subtle border border-danger rounded-pill me-2" style="padding: 2px 8px;">${numberWithCommas(el.discount_value)}%</span>
					<span class="text-danger text-decoration-line-through">Rp ${numberWithCommas(el.price)}</span>
				</div>
				<p class="fw-bold text-start mt-1 mb-0 book-price-card">Rp. ${numberWithCommas(hargaDiskon)}</p>`;
		}
	}

	// Fungsi untuk membuat number separator
	function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
	}

	// Fungsi untuk menghitung mundur waktu promo
	function timerPromo(endDate) {

		const end = new Date(endDate);
		const now = new Date();
		const diff = end - now;

		if (diff <= 0) {
			return 'Promo telah berakhir';
		}

		const days = Math.floor(diff / (1000 * 60 * 60 * 24));
		const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
		const seconds = Math.floor((diff % (1000 * 60)) / 1000);

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
</script>
