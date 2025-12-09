var paketBundlingId = document.getElementsByName('item_id')[0].value;
var thumbnailMain = document.getElementById('thumbnail-main');
var publisherName = document.getElementById('publisher_name');
var packageName = document.getElementById('package_name');
var mainPrice = document.getElementById('main_price');
var product_description = document.getElementById('product_description');
var publisherId = document.getElementsByName('publisher_id')[0];

function getDetailPaketBundling(id)
{
	let xml = new XMLHttpRequest();
	xml.open('GET', BASE_URL + 'api/Api_ebook/getDetailPaketBundling?package_id=' + id, true);
	xml.setRequestHeader('Content-Type', 'application/json');
	xml.onreadystatechange = function () {
		if (xml.readyState === 4 && xml.status === 200) {
			let response = JSON.parse(xml.responseText);
			if (response.status === true) {
				let data = response.data;

				thumbnailMain.src = data.package_image;
				publisherName.innerText = data.publisher.publisher_name;
				packageName.innerText = data.package_name;
				mainPrice.innerText = 'Rp ' + data.price.toLocaleString();
				product_description.innerText = data.description;
				publisherId.value = data.publisher.id;

				let buku = '';
				let percentage = 0;
				let normalPrice = 0;
				// let actualPrice = 0;
				let actualPrice = data.price; // Menggunakan harga paket bundling sebagai harga aktual
				for (let i = 0; i < data.books.length; i++) {
					let book = data.books[i];
					percentage += book.percentage;
					normalPrice += book.normal_price;
					// actualPrice += book.actual_price;
					// Lakukan sesuatu dengan setiap buku
					buku += `
						<div class="d-flex flex-row mt-2">
							<div class="">
								<img src="${book.cover_img}" alt="" width="60" height="90">
							</div>
							<div class="ms-2 position-relative w-100">
								<p class="fs-12 text-body-tertiary mb-0">Penerbit: ${book.publisher_name}</p>
								<p class="fs-14 fw-bold">${book.title}</p>
								<a class="position-absolute bottom-0 end-0 fs-12 fw-bold" onclick="" href="Ebook/detail/${book.ebook_id}">Lihat detail buku</a>
							</div>
						</div>`;
				}

				// Update persentase & normal price
				document.getElementById('normal_price').innerText = 'Rp ' + normalPrice.toLocaleString();
				document.getElementById('main_price').innerText = 'Rp ' + actualPrice.toLocaleString();
				// document.getElementById('percentage').innerText = (percentage / data.books.length).toFixed(1) + '%';

				document.getElementById('list-book-section').innerHTML = buku;

			} else {
				console.error('Error fetching package details:', response.message);
			}
		}
	}
	xml.send();	
}
getDetailPaketBundling(paketBundlingId);

// CEK STATUS SHOPING CART EBOOK
const checkStatusShoppingCart = () => {
	const ajax = new XMLHttpRequest();
	ajax.open('GET', BASE_URL + 'api/Api_shopping_cart/checkShoppingCartByEbookId?ebook_id=' + paketBundlingId, true);
	ajax.setRequestHeader('Content-Type', 'application/json');
	ajax.onreadystatechange = function () {
		if (ajax.readyState === 4 && ajax.status === 200) {
			let response = JSON.parse(ajax.responseText);
			if (response.status === true) {
				// Update UI sesuai dengan status cart
				let cartButton = document.getElementById('btn-cart');
				if (response.data) {
					cartButton.classList.add('active');
				} else {
					cartButton.classList.remove('active');
				}
			} else {
				console.error('Error checking cart status:', response.message);
			}
		}
	}
	ajax.send();
}
checkStatusShoppingCart();

// FUNGSI UNTUK MENGAMBIL LIST PAKET BUNDLING
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

function checkout(itemId) {
	let publisherId = document.getElementsByName('publisher_id')[0].value;

	let tax = 0; // Ganti dengan nilai pajak yang sesuai
	let biayaAdmin = 0; // Ganti dengan nilai biaya admin yang sesuai
	let items = [{
		"item_id": itemId,
		"item_type": "bundle",
		"id_subscribe_type": -1,
		"id_publisher": publisherId,
	}];
	let data = {
		"tax": tax,
		"biaya_admin": biayaAdmin,
		"items": items
	};

	console.log(data);

	let xml = new XMLHttpRequest();
	xml.open('POST', BASE_URL + 'api/Api_ebook/checkout', true);
	xml.setRequestHeader('Content-Type', 'application/json');
	xml.onreadystatechange = function () {
		if (xml.readyState === 4 && xml.status === 200) {
			let response = JSON.parse(xml.responseText);
			if (response.status === true) {
				// console.log('Checkout successful:', response);
				window.location.href = BASE_URL + 'BundlingPackage/checkout/' + response.data.id;
			} else {
				console.error('Error during checkout:', response.message);
			}
		}
	}
	xml.send(JSON.stringify(data));
}
