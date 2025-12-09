<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script defer src="<?=base_url('assets/node_modules/moment/min/moment-with-locales.min.js')?>"></script>
<script defer>
    'use strict';

	let ebookId = document.getElementById('ebook_id').value;
	let thumbnailMain = document.getElementById('thumbnail-main');
	let thumbnailSelect1Img = document.getElementById('thumbnail-select-1-img');
	let thumbnailSelect2Img = document.getElementById('thumbnail-select-2-img');
	let thumbnailSelect3Img = document.getElementById('thumbnail-select-3-img');
	let thumbnailSelect4Img = document.getElementById('thumbnail-select-4-img');
	let publisherName = document.getElementsByClassName('publisher_name');
	let ebookTitle = document.getElementById('ebook_title');
	let isbn = document.getElementById('isbn');
	let totalPages = document.getElementById('total_pages');
	let publishYear = document.getElementById('publish_year');
	let description = document.getElementById('description');
	let badgeDiscount = document.querySelector('.badge-discount');
	let ebookPrice = document.getElementById('ebook_price');
	let hargaCoret = document.querySelector('.harga-coret');

    // create read more
    const descContent = document.querySelector('.desc-content'),
          openContent = document.getElementById('open-content'),
          closeContent = document.getElementById("close-content"),
          csrfName = document.querySelector("meta[name=\"csrf_name\"]"),
          csrfToken = document.querySelector("meta[name=\"csrf_token\"]"),
          btnDropdownRating = document.querySelector('#rating-dropdown > button'),
          cbDropdownRating = document.getElementById('list-rating'),
          filledStar = document.getElementById('filled-star');

    const splitDescContent = descContent.textContent.trim().split(" ");
    if(splitDescContent.length > 50)
    {
        openContent.classList.remove("d-none");
        let newContent = '';
        const firstWords = splitDescContent.slice(0, 50);
        newContent += firstWords.join(" ");
        newContent += "<span class=\"readMore-dots\">...</span>";
        const secondWords = splitDescContent.slice(51, splitDescContent.length);
        newContent += "<span class=\"readMore-content d-none\">";
        newContent += secondWords.join(" ");
        newContent += "</span>";

        descContent.innerHTML = newContent;

        openContent.addEventListener("click", e => {
            e.stopPropagation;

            const dots = document.querySelector(".readMore-dots");
            const content = document.querySelector(".readMore-content");

            dots.innerText = " ";
            content.classList.remove("d-none");
            openContent.classList.add("d-none");
            closeContent.classList.remove("d-none");
        });

        closeContent.addEventListener("click", e => {
            e.stopPropagation;
            const dots = document.querySelector(".readMore-dots");
            const content = document.querySelector(".readMore-content");

            dots.innerText = "...";
            content.classList.add("d-none");
            openContent.classList.remove("d-none");
            closeContent.classList.add("d-none");
            
        });
    }

	// GET DETAIL EBOO
	function getDetailEbook() {
		const xhr = new XMLHttpRequest();
		xhr.open('GET', 'api/api_ebook/getEbookDetail?ebook_id=' + ebookId, true);
		xhr.onload = function () {
				if (this.status === 200) {
					let response = JSON.parse(this.responseText);
					if (response.status === true) {
						let data = response.data;

						// Update the page with the ebook details
						thumbnailMain.alt = data.title;
						thumbnailMain.src = data.cover_img.includes('http') ? data.cover_img :  BASE_URL + 'assets/images/ebooks/cover/' + data.cover_img;
						thumbnailSelect1Img.src = data.cover_img.includes('http') ? data.cover_img : BASE_URL + 'assets/images/ebooks/cover/' + data.cover_img;
						thumbnailSelect2Img.src = data.cover_1 ? data.cover_1 : BASE_URL + 'assets/images/images-placeholder.png';
						thumbnailSelect3Img.src = data.cover_2 ? data.cover_2 : BASE_URL + 'assets/images/images-placeholder.png';
						thumbnailSelect4Img.src = data.cover_3 ? data.cover_3 : BASE_URL + 'assets/images/images-placeholder.png';
						publisherName[0].innerText = data.publisher.publisher_name;
						publisherName[1].innerText = data.publisher.publisher_name;
						ebookTitle.innerText = data.title;
						isbn.innerText = data.isbn;
						totalPages.innerText = data.total_pages;
						publishYear.innerText = data.publish_year;
						description.innerText = data.description;
						// console.log(data.subscribe_type.promo);
						badgeDiscount.innerText = (data.subscribe_type.promo.promo_status == 1) ? data.subscribe_type.promo.percentage + '%' : '';
						hargaCoret.innerText = (data.subscribe_type.promo.promo_status == 1) ? 'Rp. ' + data.subscribe_type.price.toLocaleString() : '';
						ebook_price.innerText = (data.subscribe_type.promo.promo_status == 1) ? 'Rp. ' + data.subscribe_type.actual_price.toLocaleString() : 'Rp. ' + data.subscribe_type.price.toLocaleString();

						let badge = '';
						data.categories.forEach(category => {
							badge += `<span class="badge text-primary rounded-pill me-1 p-2" style="background-color: #D4D1E9;">${category}</span>`;
						});

						badge += `<span class="badge text-primary rounded-pill me-1 p-2" style="background-color: #D4D1E9;">Kelas ${data.class_level}</span>`;

						document.getElementById('category-list').innerHTML = badge;

					} else {
						console.error('Error fetching ebook details:', response.message);
						document.querySelector('.book-details').innerHTML = '<p class="text-danger">Gagal memuat detail ebook.</p>';
					}
				} else {
					console.error('Error fetching ebook details:', xhr.statusText);
					document.querySelector('.book-details').innerHTML = '<p class="text-danger">Gagal memuat detail ebook.</p>';
				}
			
		};
		xhr.send();
	}
	getDetailEbook();


	// CHECK IF EBOOK IS IN WISHLIST OR SHOPPING CART

    // WISHLIST
    const btnWishlist = document.getElementById("btn-wishlist");
    let isLiked = false;
    async function getWishlist() {
        try
        {
            const f = await fetch("api/api_wishlist/checkWishlistByEbookId?ebook_id=" + ebookId);
            const j = await f.json();

            if(j.data)
            {
                btnWishlist.ariaChecked = true;
                btnWishlist.classList.remove("btn-outline-primary");
                btnWishlist.classList.add("btn-primary");
            }
            
        }
        catch(err)
        {
            console.error(err);
        }
    }

    btnWishlist.addEventListener("click", async e => {
        const fData = new FormData();
        fData.append("ebook_id", ebookId);
        fData.append("item_type", "ebook");
        fData.append("csrf_token_name", csrfToken.content);
        const defaultText = "<i class=\"bi bi-heart\"></i>";
        const loader = '<div class="spinner-border spinner-border-sm text-secondary" role="status" height=>' +
                          '<span class="visually-hidden">Loading...</span>' +
                       '</div>';

        btnWishlist.innerHTML = loader;
        try
        {
		
			if (btnWishlist.ariaChecked == 'true') {

				const remove = await fetch("api/Api_wishlist/removeFromWishlist", {
					method: "POST",
					body: fData
				});

				const j = await remove.json();
				btnWishlist.innerHTML = defaultText;

				if(j.data)
				{
					btnWishlist.ariaChecked = false;
					btnWishlist.classList.add("btn-outline-primary");
					btnWishlist.classList.remove("btn-primary");

					Swal.fire({
						type: 'success',
						title: "Berhasil!",
						text: j.message,
						icon: "success"
					});
				}
				else
				{
				   btnWishlist.ariaChecked = true;
				   btnWishlist.classList.remove("btn-outline-primary");
				   btnWishlist.classList.add("btn-primary");

					Swal.fire({
						type: 'error',
						title: "Gagal!",
						text: j.message,
						icon: "error"
					});
				}

				// csrfName.setAttribute("content", j.csrf_name);
				csrfToken.content = j.csrf_token;

			} else {
				const add = await fetch("api/Api_wishlist/addToWishlist", {
					method: "POST",
					body: fData
				});

				const j = await add.json();
				btnWishlist.innerHTML = defaultText;
	
				if(j.data)
				{
					btnWishlist.ariaChecked = true;
					btnWishlist.classList.remove("btn-outline-primary");
					btnWishlist.classList.add("btn-primary");
	
					Swal.fire({
						type: 'success',
						title: "Berhasil!",
						text: j.message,
						icon: "success"
					});

					// show success modal add to wishlist
					const a = () => {
						return new Promise(resolve => {
							resolve({success: true});
						});
					}

					a().then((e) => {
						if (e.success) {
							setTimeout(() => {
								$('#modalAddToWishlistSuccess').modal('show');
							}, 2000);
							
						}
					});

					csrfToken.content = j.csrf_token;
				}
				else
				{
				   btnWishlist.ariaChecked = false;
				   btnWishlist.classList.add("btn-outline-primary");
				   btnWishlist.classList.remove("btn-primary");
	
				   
					if(j.limit){
						$('#btn-wishlist')[0].classList.remove('active')
						Swal.fire({
							type: 'info',
							title: "<h4>Maaf, Wishlist anda sudah penuh!</h4>",
							text: j.message,
							iconHtml: '<img src="assets/images/icons/wishlist-round.png">',
							// icon: "info",
							showConfirmButton: false,
							footer: '<a class="btn btn-primary" href="user/index">Atur Wishlist</a>'
						});
						return
					}else{
						$('#btn-wishlist')[0].classList.remove('active')
						Swal.fire({
							type: 'info',
							title: "Peringatan!",
							text: j.message,
							// icon: "info"
							iconHtml: '<img src="assets/images/icons/wishlist-round.png">',
						});
					}

					csrfToken.content = j.csrf_token;
				}
				
			}

        }
        catch(err)
        {
            console.error(err);
        }
    });

    // SHOPPING CART
    const btnCart = document.getElementById("btn-cart");

    async function getShoppingCart() {
        try
        {
            const f = await fetch("api/Api_shopping_cart/checkShoppingCartByEbookId?ebook_id=" + ebookId);
            const j = await f.json();

            if(j.data)
            {
                btnCart.ariaChecked = true;
                btnCart.classList.remove("btn-outline-primary");
                btnCart.classList.add("btn-primary");
            }
        }
        catch(err)
        {
            console.error(err);
        }
    }

    btnCart.addEventListener("click", async e => {
        const fData = new FormData();
        fData.append("ebook_id", ebookId);
        fData.append("csrf_token_name", csrfToken.content);

        const defaultText = "<i class=\"bi bi-basket2\"></i>";
        const loader = '<div class="spinner-border spinner-border-sm text-secondary" role="status" height=>' +
                          '<span class="visually-hidden">Loading...</span>' +
                       '</div>';

        btnCart.innerHTML = loader;
        try
        {
			// console.log(btnCart.ariaChecked);
			// return 
			if (btnCart.ariaChecked == 'false') {
				const f = await fetch("api/Api_shopping_cart/addToShoppingCart", {
					method: "POST",
					body: fData
				});
				
				const j = await f.json();
				btnCart.innerHTML = defaultText;

				if(j.status == true)
				{
					btnCart.ariaChecked = true;
					btnCart.classList.remove("btn-outline-primary");
					btnCart.classList.add("btn-primary");

					Swal.fire({
						type: 'success',
						title: "Berhasil!",
						text: j.message,
						icon: "success"
					});

					// show success modal add to cart
					const a = () => {
						return new Promise(resolve => {
							resolve({success: true});
						});
					}

					a().then((e) => {
						if (e.success) {
							setTimeout(() => {
								$('#modalAddToChartSuccess').modal('show');
							}, 2000);
							
						}
					});
					
				}
				else
				{
				btnCart.ariaChecked = false;
				btnCart.classList.add("btn-outline-primary");
				btnCart.classList.remove("btn-primary");
				}

				csrfToken.content = j.csrf_token;
			} else {
				const f = await fetch("api/Api_shopping_cart/removeFromShoppingCart", {
					method: "POST",
					body: fData
				});

				const j = await f.json();
				btnCart.innerHTML = defaultText;

				if(j.status)
				{
					btnCart.ariaChecked = false;
					btnCart.classList.add("btn-outline-primary");
					btnCart.classList.remove("btn-primary");

					Swal.fire({
						type: 'success',
						title: "Berhasil!",
						text: j.message,
						icon: "success"
					});
				}
				else
				{
				   btnCart.ariaChecked = true;
				   btnCart.classList.remove("btn-outline-primary");
				   btnCart.classList.add("btn-primary");

					Swal.fire({
						type: 'error',
						title: "Gagal!",
						text: j.message,
						icon: "error"
					});
				}

				csrfToken.content = j.csrf_token;
			}

            
        }
        catch(err)
        {
            console.error(err);
        }
    });

    // RATING2 RATINGAN
    async function ratingData() {
        try
        {
            // const f = await fetch(`<?//=base_url('api/api_ebook/getRating?book_id='.$book['id'])?>`);
            const f = await fetch(`<?=base_url('api/api_ebook/getRating?book_id=155&page=1&per_page=3')?>`);
            const j = await f.json();

            return j;
        }
        catch(err)
        {

        }
    }

    const createStarRating = rating => {
	    let star = '';
	    for(let i=1; i<=5; i++){
	    	star += (i <= rating) ? '<i class="bi bi-star-fill text-warning"></i>' : '<i class="bi bi-star text-warning"></i>';
	    }
	    return star;
    }

    const ratingList = list => {
        
        const ul = document.getElementById('daftar-ulasan');

		let no = 1;
        Array.from(list, item => {

            const li = document.createElement('li');
            li.classList.add('list-group-item');

            // create elements
            const figure = document.createElement('figure');
            const img = new Image();
            const figcaption = document.createElement('figcaption');
            // img.
            img.src = item.photo ? item.photo : '<?=base_url('assets/images/user.png')?>';
            img.onerror = e => { e.target.src = '<?=base_url('assets/images/user.png')?>'; };
            img.classList.add('rounded-circle', 'img-fluid', 'shadow-sm');
            img.style.height = '40px';
            // figcaption
            const captionHead = document.createElement('div');
            moment.locale('id');
            const tanggal = moment(item.created_at.split(' ').join('T'));

            captionHead.innerHTML = `<h6 class="text-capitalize mb-1">${item.full_name}</h6>` + 
                                        `<span class="mx-2">&#x2022;</span>` + 
                                    `<small class="text-body-tertiary fw-semibold">${tanggal.format('DD MMM YYYY')}</small>`;
            captionHead.classList.add('d-inline-flex', 'w-100');
            figcaption.appendChild(captionHead);

            // rating
            const starRated = document.createElement('span');
            starRated.innerHTML = createStarRating(+item.rate);
            starRated.classList.add('mb-3');
            figcaption.appendChild(starRated);

            const comment = document.createElement('p');
            comment.innerText = item.komentar;
            comment.classList.add('mt-3');
            figcaption.appendChild(comment);
            
            figcaption.classList.add('d-block', 'ms-3');
            // append to figure
            figure.classList.add('d-flex', 'flex-nowrap');
            figure.appendChild(img);
            figure.appendChild(figcaption);
            // append to li
            li.appendChild(figure);

			ul.appendChild(li);

			no++;
        });
    }

    // FILTER RATING
    Array.from(cbDropdownRating.querySelectorAll('li')).forEach(item => {
        
        item.addEventListener('click', function(e) {
            const child = this.querySelector('a');
            btnDropdownRating.innerHTML = child.innerHTML; 
            
        });

    }, false);


    const calculateAvg = data => {
        const totalComment = data.count;

        // get total rate by start number 1 by 1
        const totalRate1 = data.data.filter(x => x.rate == 1);
        const totalRate2 = data.data.filter(x => x.rate == 2);
        const totalRate3 = data.data.filter(x => x.rate == 3);
        const totalRate4 = data.data.filter(x => x.rate == 4);
        const totalRate5 = data.data.filter(x => x.rate == 5);

        const weight = (1*totalRate1.length) + (2*totalRate2.length) + (3*totalRate3.length) + (4*totalRate4.length) + (5*totalRate5.length);
        const sum = totalRate1.length + totalRate2.length + totalRate3.length + totalRate4.length + totalRate5.length;
        const avg = weight / sum;
        // average number
        return avg.toFixed(1);
    }

    const countAvgToPercent = val => (val*100) / 5;
    // const toPercent = (sum,total) => (sum/total) * 100; 
    const toPercent = (rating, totalRating) => {
		let hasil = rating / totalRating * 100;
		if (Number.isNaN(hasil)) {
			return 0;
		}
		return hasil;
	}

	var pageRating = 1;
	var ratingData;

	(async () => {
		await getWishlist();
		await getShoppingCart();

		// RATING
		const ratings = await ratingData();
		let totalRating = ratings.data.ebook_rating.total_rating;
		let averageRating = ratings.data.ebook_rating.average_rating;
		let rating1 = ratings.data.ebook_rating.rating_1;
		let rating2 = ratings.data.ebook_rating.rating_2;
		let rating3 = ratings.data.ebook_rating.rating_3;
		let rating4 = ratings.data.ebook_rating.rating_4;
		let rating5 = ratings.data.ebook_rating.rating_5;

		ratingData = ratings.data.fetch;
		// jika ratingData tidak ada atau kurang dari 3 maka sembunyikan button load more
		if(ratingData.length < 3){
			document.getElementById('load-more-rating').classList.add('d-none');
		}

		ratingList(ratingData);

		document.getElementById('value-avg').innerText = averageRating
		filledStar.style.width = (averageRating / 5 * 100) + '%';

		// akumulasi bintang 5
		document.getElementById('rate-5-prog').style.width = `${rating5}%`;
		document.getElementById('rate-5-percent').innerHTML = `${rating5}%`;
		// akumulasi bintang 4
		document.getElementById('rate-4-prog').style.width = `${rating4}%`;
		document.getElementById('rate-4-percent').innerHTML = `${rating4}%`;
		// akumulasi bintang 3
		document.getElementById('rate-3-prog').style.width = `${rating3}%`;
		document.getElementById('rate-3-percent').innerHTML = `${rating3}%`;
		// akumulasi bintang 2
		document.getElementById('rate-2-prog').style.width = `${rating2}%`;
		document.getElementById('rate-2-percent').innerHTML = `${rating2}%`;
		// akumulasi bintang 1
		document.getElementById('rate-1-prog').style.width = `${rating1}%`;
		document.getElementById('rate-1-percent').innerHTML = `${rating1}%`;
	})()

	// ============================= Load more rating ==========================
	document.getElementById('load-more-rating').addEventListener('click', async e => {
		e.preventDefault();
		pageRating++;
		
		try
        {
            // const f = await fetch(`<?//=base_url('api/api_ebook/getRating?book_id='.$book['id'])?>`);
            const f = await fetch(`api/api_ebook/getRating?book_id=155&page=${pageRating}&per_page=3`);
            const j = await f.json();

			if(j.data.fetch.length < 3){
				document.getElementById('load-more-rating').classList.add('d-none');
			}

			ratingData = j.data.fetch;
			ratingList(ratingData);
        }
        catch(err)
        {

        }
	});

	// ============================= END Load more rating ==========================

	// ============================= PILIH RATING DROP DOWN  ==========================
	$('#rating-dropdown .dropdown-item').on('click', async function() {
		pageRating = 1;
		$('#daftar-ulasan').empty();

		var selectedRating = $(this).parent().data('value');
		
		try
        {
            // const f = await fetch(`<?//=base_url('api/api_ebook/getRating?book_id='.$book['id'])?>`);
            const f = await fetch(`api/api_ebook/getRating?book_id=155&page=${pageRating}&per_page=3&rating_level=${selectedRating}`);
            const j = await f.json();

			console.log(j.data.fetch.length);
			if(j.data.fetch.length < 3){
				document.getElementById('load-more-rating').classList.add('d-none');
			}else{
				document.getElementById('load-more-rating').classList.remove('d-none');
			}

			ratingData = j.data.fetch;
			ratingList(ratingData);
        }
        catch(err)
        {

        }
	});

	function getSimilarBooks() {
		// Fetch similar books based on the current book's ID
		fetch(`api/api_ebook/getSimilarEbook?book_id=155&page=1&per_page=10`)
			.then(response => response.json())
			.then(data => {
				// Process and display similar books
				let similarBooks = data.data.fetch;
				console.log(similarBooks);
			
				let similar = '';
				for(let i = 0; i < similarBooks.length; i++) {
					const book = similarBooks[i];
					console.log(book);
					similar += `<li class="splide__slide">
									<a href="Ebook/detail/${book.id}" class="text-decoration-none">
										<div class="card rounded-3 border-light-subtle p-3 m-1">
											<img class="rounded-3" src="${(book.cover_img.includes('http') ? book.cover_img : 'assets/images/ebooks/cover/' + book.cover_img)}" alt="" width="128" height="172">
											<p class="fs-12 lh-1 mt-3 mb-2 book-publiher-card">Penerbit: ${book.publisher.publisher_name}</p>
											<p class="fs-14 mb-0 book-title-card">${book.title}</p>
										</div>
									</a>
								</li>`;

				}

				document.querySelector('#list-similar-book').innerHTML = similar;

				// Inisialisasi Splide untuk penerbit unggulan
				new Splide('#thumbnail-carousel-similar-book', {
					fixedWidth: 160,
					fixedHeight: 270,
					gap: 30,
					rewind: false,
					pagination: false,
				}).mount();
			})
			.catch(error => {
				console.error('Error fetching similar books:', error);
			});
	}
	getSimilarBooks();

	function imageExists(image_url){

		var http = new XMLHttpRequest();

		http.open('HEAD', image_url, false);
		http.send();

		return http.status != 404;

	}

	// FUNGSI UNTUK KLIK THUMBNAIL
	$('.thumbnail-select').on('click', (e) => {
		let img = (e.currentTarget.src)
		$('#thumbnail-main').attr('src', img)
	});
</script>

