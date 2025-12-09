<section id="show-by-category">
	<!-- <div class="breadcrumbs">
		<a href="<? // = base_url('ebook') 
					?>">Onboarding</a> <i class="bi bi-chevron-right"></i> <span><?= $category ?></span>
	</div> -->

	<input type="hidden" name="id" value="<?= $id ?>">
	<input type="hidden" name="ebook_id" value="<?= $ebook_id ?>">
	<input type="hidden" name="category_name" value="<?= $category ?>">
	<input type="hidden" name="publisher_id" value="<?= $publisher_id ?>">

	<?php if ($category !== 'publisher') : ?>
		<div class="banner mt-4 <?= (strtolower($category) == '') ? 'd-none' : '' ?>">
			<img src="<?= $banner ?>" alt="" height="350" class="w-100">
		</div>
	<?php endif; ?>

	<?php if ($category == 'publisher') : ?>
		<div class="banner mt-4 border border-1 rounded-4">
			<div><img src="assets/images/card-penerbit-bg.png" alt="" height="80" style="width: 100%; border-radius: 12px 12px 0 0;"></div>
			<div class="position-relative" style="left: 30px; top: -50px;">
				<img src="<?= $this->config->item('admin_url') ?>assets/images/publisher/<?= $details['company_logo'] ?>" alt="" width="" height="80" class="rounded-4">
			</div>
			<h4 class="ms-4" style="position: relative;top: -30px;"><?= $details['publisher_name'] ?></h4>
			<div class="ms-4" style="position: relative;top: -30px;"><?= $details['caption'] ?></div>
		</div>
	<?php endif; ?>

	<div class="list-book mt-5 row"></div>
	<div id="pagination" class="mt-4 mb-4 text-center">
		<button class="btn btn-primary" id="load-more">Lihat Lebih Banyak <i class="bi bi-chevron-down"></i></button>
	</div>
</section>

<script>
	const id = document.querySelector('input[name="id"]').value;
	const ebookId = document.querySelector('input[name="ebook_id"]').value;
	const categoryName = document.querySelector('input[name="category_name"]').value;
	const publisherId = document.querySelector('input[name="publisher_id"]').value;
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

		let url;
		// if (categoryName == 'baru_dirilis') {
		// 	url = `<?//= base_url('ebook/getRekomendasi') ?>?filter[baru_dirilis]=true&page=${page}`;
		// } else if (categoryName == 'terbanyak_dibaca') {
		// 	url = `<?//= base_url('ebook/getRekomendasi') ?>?filter[terbanyak_dibaca]=true&page=${page}`;
		// } else if (categoryName == 'publisher') {
		// 	url = `<?//= base_url('ebook/getRekomendasi') ?>?filter[publisher_id]=${publisherId}&page=${page}`;
		// } else {
		// 	url = `<?//= base_url('ebook/get_books_by_category') ?>?category=${category}&page=${page}`;
		// }

		if(id == 0){
			url = `api/api_ebook/getAllEbooks?page=${page}&per_page=10`;
		} else {
			url = `api/api_ebook/getPilihanKategoriMenarikById?id=${id}&page=${page}&per_page=10`;
		}

		if(categoryName == 'similar'){
			url = `api/api_ebook/getSimilarEbook?book_id=${ebookId}&page=${page}&per_page=100`;
		} else if(categoryName == 'publisher'){
			url = `api/api_ebook/getAllEbooks?publisher_id=${publisherId}&page=${page}&per_page=10`;
		}

		fetch(url)
			.then(response => response.json())
			.then(data => {
				if (data.data.fetch.length > 0) {
					data.data.fetch.forEach(book => {
						const bookItem = document.createElement('div');
						bookItem.className = 'col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6 mb-4';
						bookItem.innerHTML = `
							<div class="card rounded-4 border-light-subtle p-3 m-1 text-center" style="height: 100%; width: 160px;">
							    <a href="<?= base_url('ebook/detail/') ?>${(id == 0) ? book.id : book.ebook_id}" class="text-decoration-none">
									<img class="rounded-3" src="${(book.cover_img.includes('http') ? book.cover_img : 'assets/images/ebooks/cover/' + book.cover_img)}" alt="" width="128" height="172" style="align-self: center;">
									<p class="fs-12 text-start lh-1 mt-3 mb-2 book-publiher-card" style="color: #74788D;">Penerbit: ${(id == 0) ? book.publisher.publisher_name : book.publisher_name}</p>
									<p class="fs-12 text-start fw-bold mb-0 book-title-card" style="color: #1D1F2C;">${book.title}</p>
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
</script>
