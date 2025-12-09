let paketBundlingId = document.getElementsByName('id')[0].value;
let packageName = document.getElementById('package_name');
let price = document.getElementById('price');
let totalQty = document.getElementById('total_qty');
let grossAmount = document.getElementById('gross_amount');
let totalPrice = document.getElementById('total_price');
let biayaAdmin = document.getElementById('biaya_admin');
let btnSimpan = document.getElementById('simpan');
let termsCheckbox = document.getElementById('terms');
let diskonVoucherContainer = document.querySelector('.diskon-voucher-container');
let voucherDiscount = document.getElementById('voucher_discount');
let btnVoucher = document.getElementsByClassName('btn-voucher')[0];
let listVoucherModal = document.getElementById('listVoucherModal');
let btnGunakanVoucher = document.getElementById('btn-gunakan-voucher');
let aVoucherCode = document.getElementById('a_voucher_code');
let isCancelTab = true; // Flag to track if the tab close is intentional

let biaya_admin = 0; // Ganti dengan nilai biaya admin yang sesuai
let tax = 0; // Ganti dengan nilai pajak yang sesuai

function getDetailCheckout(id) {
	return new Promise((resolve, reject) => {

		let xml = new XMLHttpRequest();
		xml.open('GET', BASE_URL + 'api/Api_ebook/getDetailCheckout?checkout_id=' + id, true);
		xml.setRequestHeader('Content-Type', 'application/json');
		xml.onreadystatechange = function () {
			if (xml.readyState === 4 && xml.status === 200) {
				let response = JSON.parse(xml.responseText);

				if (response.status === true) {
					let data = response.data;
					let items = data.checkout_items;

					totalQty.innerText = data.total_qty;
					grossAmount.innerText = 'Rp ' + data.gross_amount.toLocaleString();
					biayaAdmin.innerText = 'Rp ' + data.biaya_admin.toLocaleString();
					totalPrice.innerText = 'Rp ' + data.total_price.toLocaleString();

					// kondisi jika ada voucher
					if (data.voucher) {
						btnVoucher.classList.add('bg-secondary');

						$('.diskon-voucher-container').removeClass('d-none'); // Tampilkan elemen diskon

						// ISI INPUT KODE VOUCHER
						aVoucherCode.value = data.voucher.code;

						// UBAH TEXT BUTTON GUNAKAN VOUCHER MENJADI HAPUS VOUCHER
						btnGunakanVoucher.innerText = `Hapus Voucher`;
						btnGunakanVoucher.classList.remove('btn-primary');
						btnGunakanVoucher.classList.add('btn-light');
						btnGunakanVoucher.classList.add('btn-outline-primary');

						voucherDiscount.innerHTML = `<span class="text-danger">Rp -${(data.gross_amount - data.total_price).toLocaleString()}</span>`;	
						
						if (data.voucher.discount_type === 'percentage') {
							btnVoucher.children[0].children[0].innerHTML = `<div class="d-flex align-items-center">
								<div>🎉 </div>
								<div class="ms-2">
									<p class="m-0">Kupon berhasil di pakai</p>
									<p class="m-0">Hemat ${data.voucher.discount_value.toLocaleString()} %</p>
								</div>
							</div>`;
						} else {
							// voucherDiscount.innerHTML = `<span class="text-danger">Rp -${(data.gross_amount - data.total_price).toLocaleString()}</span>`;
							btnVoucher.children[0].children[0].innerHTML = `<div class="d-flex align-items-center">
								<div>🎉 </div>
								<div class="ms-2">
									<p class="m-0">Kupon berhasil di pakai</p>
									<p class="m-0">Hemat Rp ${data.voucher.discount_value.toLocaleString()}</p>
								</div>
							</div>`;
						}

						// Ubah warna button voucher
						btnVoucher.classList.remove('bg-secondary');
						btnVoucher.style.backgroundColor = '#D4D1E9';
						btnVoucher.classList.add('border-primary');
						btnVoucher.classList.add('text-primary');


					} else {
						// jika tidak ada voucher, sembunyikan elemen diskon
						$('.diskon-voucher-container').addClass('d-none');
						diskonVoucherContainer.style.display = 'none';
						voucherDiscount.innerText = 'Rp 0';
					}

					let cardItems = document.getElementById('list-card-pesanan');
					cardItems.innerHTML = ''; // Clear previous content
					let card = '';
					for (let i = 0; i < items.length; i++) {
						let item = items[i];

						// Lakukan sesuatu dengan setiap item
						card += `
						<div class="card p-3 rounded-4 mb-3">
							<input type="hidden" name="publisher_id" value="${item.id_publisher}">

							<div class="row mt-2">
								<div class="col">
									<h6 class="fw-bold">Pesanan ${i + 1}</h6>
								</div>
								<div class="col text-end"><i class="bi bi-shop"></i> ${item.publisher_name}</div>
							</div>

							<hr style="border: 1px solid #b1b1b1; margin-top:7px;">
							<div class='list-book-container'>
								${cardBookItem(item.publisher_items, item.publisher_name)}
							</div>
							
						</div>
					`;
					}

					cardItems.innerHTML = card;

					resolve(response);
				} else {
					console.error('Error fetching package details:', response.message);
				}
			}
		}
		xml.send();
	});
}
getDetailCheckout(paketBundlingId).then(response => {
	if (response.status === true) {
		// UBAH LANGGANAN BUTTON CLICK
		$('.ubah-langganan').on('click', function (e) {
			let ebookId = e.currentTarget.closest('.list-book-container').querySelector('input[name="item_id"]').value;
			let card = e.currentTarget.closest('.card');
			let publisherId = card.querySelector('input[name="publisher_id"]').value;
			
			let modalPublisherIdInput = document.querySelector('#listPaketLanggananModal input[name="publisher_id"]');
			modalPublisherIdInput.value = publisherId;

			let modalEbookIdInput = document.querySelector('#listPaketLanggananModal #ebook_id');
			modalEbookIdInput.value = ebookId;

			$('#listPaketLanggananModal').modal('show');

			// GET PAKET LANGGANAN
			getDetailPaketLangganan(ebookId);
		});
	}
});

// CARD BOOK ITEM
function cardBookItem(items, publisherName) {
	let card = '';
	items.forEach(item => {
		card += `<div class="row list-book-section">
					<div class="col-9">
						<div class="d-flex flex-row mt-2 mb-3">
							<input type="hidden" name="item_id" value="${item.item_id}">
							<img src="${item.thumbnail_image}" alt="" width="60" height="90" class="rounded-3">
							
							<div class="ms-2 position-relative w-100">
								<p class="fs-12 text-body-tertiary mb-0">Penerbit: <span>${publisherName}</span></p>
								<a href="${(item.item_type == 'ebook') ? 'ebook/detail/' + item.item_id : 'BundlingPackage/detail/' + item.item_id}"><h4 class="mt-2" style="font-size: 16px;">${item.item_name}</h4></a>
							</div>

						</div>
					</div>
					<div class="col-3 text-end align-self-end">
						<p class="fs-12 fw-bold mb-2 text-body-tertiary" style="bottom: 20px;">Total Harga</p>
						<h6 class="fw-bold">Rp ${item.item_price.toLocaleString()}</h6>
					</div>

				</div>
				<hr>
				<div class="d-flex justify-content-between ${item.item_type == 'ebook' ? '' : 'd-none'}">
					<p class="fs-12 text-body-tertiary">${item.detail_data.subscribe_type}</p>
					<p class="fs-12 text-primary link-primary ubah-langganan" style="cursor: pointer;"><u>Ubah Langganan</u></p>
				</div>
			`;

	});
	return card;
}

// ===================================== GET DETAIL PAKET LANGGANAN ==============================
function getDetailPaketLangganan(ebookId) {
	let xml = new XMLHttpRequest();
	xml.open('GET', BASE_URL + 'api/Api_ebook/getEbookPaket?ebook_id=' + ebookId + '&subscribe_period=all', true);
	xml.setRequestHeader('Content-Type', 'application/json');
	xml.onreadystatechange = function () {
		if (xml.readyState === 1) {
			// Show loading spinner or message
			document.getElementById('list-akses-selamanya').innerHTML = '<div class="text-center my-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
			document.getElementById('list-akses-bulanan').innerHTML = '<div class="text-center my-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
		}

		if (xml.readyState === 4 && xml.status === 200) {
			let response = JSON.parse(xml.responseText);
			if (response.status === true) {
				let data = response.data;

				let listAksesSelamanya = document.getElementById('list-akses-selamanya');
				let listAksesBulanan = document.getElementById('list-akses-bulanan');
				let cardAksesSelamanya = '';
				let cardAksesBulanan = '';

				data.forEach(function (paket) {
					if (paket.subscribe_periode === 'full_access') {
						cardAksesSelamanya += `
							<div class="col-12 col-lg-4 px-4 pt-3">
								<div class="card border border-primary shadow h-100">
									<div class="card-body">
										<span class="badge-header d-flex justify-content-center align-items-center rounded-circle bg-primary-subtle mx-auto" style="width: 50px; height: 50px;">
											<i class="bi bi-layers text-primary fw-bold"></i>
										</span>
										<h6 class="text-primary text-center my-3 fw-semibold">${paket.name}</h6>
										
											${cekPromo(paket)}

										<h4 class="fs-4 text-center fw-bold my-3"><strong>Rp ${paket.actual_price.toLocaleString()}</strong></h4>

										<ul class="ps-0 mb-3 mt-4 list-features d-flex flex-column align-items-start">
											${listBenefit(paket.benefit)}
										</ul>
										
									</div>
									<div class="card-footer">
										<!-- <button class="btn btn-primary w-100">Pilih Paket</button> -->
										<a onclick="updateCheckout(this)" class="btn btn-primary w-100" data-id="${paket.id}">Pilih Paket</a>
									</div>
								</div>
							</div>
						`;``
					} else {
						cardAksesBulanan += `
							<div class="col-12 col-lg-4 px-4 pt-3">
								<div class="card border border-primary shadow h-100">
									<div class="card-body">
										<span class="badge-header d-flex justify-content-center align-items-center rounded-circle bg-primary-subtle mx-auto" style="width: 50px; height: 50px;">
											<i class="bi bi-layers text-primary fw-bold"></i>
										</span>
										<h6 class="text-primary text-center my-3 fw-semibold">${paket.name}</h6>
								
											${cekPromo(paket)}

										<span class="d-flex justify-content-center align-items-center my-3">
											<h4 class="fs-4 fw-bold"><strong>Rp ${paket.actual_price.toLocaleString()}</strong></h4>
											<h6 class="text-black-50 ms-1 fs-5">/ ${paket.subscribe_periode.replace('_', ' ')}</h6>
										</span>

										<ul class="ps-0 mb-3 mt-4 list-features d-flex flex-column align-items-start">
											${listBenefit(paket.benefit)}
										</ul>
										
									</div>
									<div class="card-footer">
										<a onclick="updateCheckout(this)" class="btn btn-primary w-100" data-id="${paket.id}">Pilih Paket</a>
									</div>
								</div>
							</div>
						`;
					}
				});

				listAksesSelamanya.innerHTML = cardAksesSelamanya;
				listAksesBulanan.innerHTML = cardAksesBulanan;
			} else {
				console.error('Error fetching paket langganan:', response.message);
			}
		}
	}
	xml.send();
}

function cekPromo(data) {
	if (data.promo.promo_status !== 3) {
		return `<div class="d-flex justify-content-center align-items-center text-danger mt-2">
											<small class="d-inline-flex bg-danger-subtle rounded-pill px-2 py-1"><i>${data.promo.percentage}%</i></small> 
											<small class="d-inline-flex text-decoration-line-through ms-1"><i>Rp. ${data.price}</i></small>
										</div>`;
	}
	return '';
}

function listBenefit($data) {
	benefits = '';
	$data.forEach(function (item) {
		benefits += `<li class="list-item-positive">${item.benefit}</li>`;
	});
	return benefits;
}

// ===================================== GET PAKET LANGGANAN END ==============================

// GET LIST VOUCHER
function getListVoucher() {
	let xml = new XMLHttpRequest();
	xml.open('GET', BASE_URL + 'api/Api_voucher/getVoucherList', true);
	xml.setRequestHeader('Content-Type', 'application/json');
	xml.onreadystatechange = function () {
		if (xml.readyState === 4 && xml.status === 200) {
			let response = JSON.parse(xml.responseText);
			if (response.status === true) {
				let data = response.data.fetch;
				let listVoucher = document.getElementById('list-voucher');
				listVoucher.innerHTML = ''; // Clear previous content
				let card = '';
				for (let i = 0; i < data.length; i++) {
					let voucher = data[i];
					card += `
						<div class="card p-3 rounded-4 mb-2">
							<div class="row">
								<div class="col-12">
									<p style="font-size: 14px; color=#74788D">${moment(voucher.start_date).format('DD MMMM YYYY')} - ${moment(voucher.end_date).format('DD MMMM YYYY')}</p>
									<h6 class="fw-bold">${voucher.name} - ${voucher.publisher_name.substring(0, 20)}</h6>
									
									<div class="input-group mb-3">
										<input type="text" class="form-control border-dash" placeholder="${voucher.code}" aria-label="Recipient’s username" aria-describedby="button-addon2">
										<button class="btn btn-primary text-white" type="button" id="button-addon2" onclick="copyToClipboard('${voucher.code}')"><i class="bi bi-clipboard"></i> Salin Kode</button>
									</div>

									<a data-bs-toggle="collapse" href="#collapseExample${i}" role="button" aria-expanded="false" aria-controls="collapseExample">Syarat & ketentuan</a>
									<div class="collapse mt-3" id="collapseExample${i}">
										<div class="card card-body">
											<p>Potongan Harga ${(voucher.discount_type == 'nominal') ? 'Rp ' + voucher.discount_value.toLocaleString() : voucher.discount_value + '%'}</p>

											<p>Keterangan: ${voucher.description}</p>
										</div>
									</div>
								
								</div>
								
							</div>
						</div>
					`;
				}
				listVoucher.innerHTML = card;
			} else {
				console.error('Error fetching vouchers:', response.message);
			}
		}
	}
	xml.send();
}
getListVoucher();

btnVoucher.addEventListener('click', function () {
	console.log('Show voucher modal');
	$('#listVoucherModal').modal('show');
});

// CREATE PAYMENT
btnSimpan.addEventListener('click', function (e) {
	e.preventDefault();
	isCancelTab = false;

	if (!termsCheckbox.checked) {
		Swal.fire({
			type: 'warning',
			title: 'Perhatian',
			text: 'Anda harus menyetujui syarat dan ketentuan sebelum melanjutkan.',
			confirmButtonText: 'OK',
		});
		return;
	}

	localStorage.setItem('checkout_id', paketBundlingId);

	let checkoutId = paketBundlingId;
	// let callbackUrl = 'http://localhost/woowedu/BundlingPackage/statusPembayaran';
	let callbackUrl = 'http://103.15.226.136/web/BundlingPackage/statusPembayaran';
	// let callbackUrl = 'https://hitcorporation.com';

	let data = {
		checkout_id: parseInt(checkoutId),
		callback_url: callbackUrl,
	};

	let xml = new XMLHttpRequest();
	xml.open('POST', BASE_URL + 'api/Api_ebook/makePayment', true);
	xml.setRequestHeader('Content-Type', 'application/json');
	xml.onreadystatechange = function () {
		if (xml.readyState === 1) {
			// Show loading spinner or message
			e.target.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Memproses...';
		}

		if (xml.readyState === 4 && xml.status === 200) {
			let response = JSON.parse(xml.responseText);
			if (response.status === true) {
				console.log('Checkout successful:', response);
				//  set localstorage redirect url
				localStorage.setItem('redirect_url', response.data.redirect_url);
				window.location.href = BASE_URL + 'BundlingPackage/payment'
				// window.location.href = BASE_URL + 'BundlingPackage/payment?order_id=' + response.data.order_id + '&snap_token=' + response.data.snap_token + '&callback_url=' + encodeURIComponent(response.data.redirect_url);
			} else {
				Swal.fire({
					type: 'error',
					title: 'Gagal',
					text: response.message,
					confirmButtonText: 'OK',
				});

				// update token
				document.getElementsByName('csrf_token_name')[0].value = response.csrf_token

				// Reset button text
				e.target.innerHTML = 'Pembayaran';
				
			}
		}

		if (xml.readyState === 4 && xml.status == 400) {
			// If request fails, reset button text
			e.target.innerHTML = 'Pembayaran';
			Swal.fire({
				type: 'error',
				title: 'Gagal',
				text: 'Terjadi kesalahan saat memproses pembayaran. Silahkan coba lagi.',
				confirmButtonText: 'OK',
			});
			history.back();
		}

	};
	xml.send(JSON.stringify(data));
});

function copyToClipboard(text) {
	// validasi copy voucher
	if (btnGunakanVoucher.innerText === 'Hapus Voucher') {
		Swal.fire({
			type: 'warning',
			title: 'Perhatian',
			text: 'Anda sudah menggunakan voucher, silahkan hapus voucher terlebih dahulu untuk menggunakan voucher lain.',
			confirmButtonText: 'OK',
		});
		return;
	}

	$('#a_voucher_code').val(text);

	// navigator.clipboard.writeText(text).then(function () {
	Swal.fire({
		type: 'success',
		title: 'Berhasil',
		text: 'Kode voucher telah disalin ke clipboard.',
		confirmButtonText: 'OK',
	});
	// }).catch(function (error) {
	// 	console.error('Error copying text: ', error);
	// 	Swal.fire({
	// 		type: 'error',
	// 		title: 'Gagal',
	// 		text: 'Terjadi kesalahan saat menyalin kode voucher.',
	// 		confirmButtonText: 'OK',
	// 	});
	// });
}

btnGunakanVoucher.addEventListener('click', function () {
	let voucherCode = aVoucherCode.value.trim();
	if (voucherCode === '') {
		Swal.fire({
			type: 'warning',
			title: 'Perhatian',
			text: 'Kode voucher tidak boleh kosong.',
			confirmButtonText: 'OK',
		});
		return;
	}

	let url = '';
	if (btnGunakanVoucher.innerText === 'Hapus Voucher') {
		url = BASE_URL + 'api/Api_voucher/removeVoucher';
	} else {
		url = BASE_URL + 'api/Api_voucher/useVoucher';
	}

	let form = new FormData();
	form.append('voucher_code', voucherCode);
	form.append('checkout_id', paketBundlingId);
	form.append('csrf_token_name', document.getElementsByName('csrf_token_name')[0].value);

	let xml = new XMLHttpRequest();
	xml.open('POST', url, true);
	xml.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xml.onreadystatechange = function () {
		if (xml.readyState === 4 && xml.status === 200) {
			let response = JSON.parse(xml.responseText);
			if (response.status === true) {
				// update token
				document.getElementsByName('csrf_token_name')[0].value = response.csrf_token;

				Swal.fire({
					type: 'success',
					title: 'Berhasil',
					text: (btnGunakanVoucher.innerText === 'Hapus Voucher') ? 'Voucher berhasil dihapus.' : 'Voucher berhasil digunakan.',
					confirmButtonText: 'OK',
				}).then(() => {
					location.reload(); // Reload the page to reflect changes
				});

			} else {
				Swal.fire({
					type: 'error',
					title: 'Gagal',
					text: response.message,
					confirmButtonText: 'OK',
				});
				// update token
				document.getElementsByName('csrf_token_name')[0].value = response.csrf_token
			}
		}
	};
	xml.send(new URLSearchParams(form).toString());


});

// ===================================== UPDATE CHECKOUT ==============================
function updateCheckout(e) {
	let modalListPaketLangganan = document.querySelector('#list-paket-langganan');
	let ebookId = modalListPaketLangganan.querySelector('#ebook_id').value;
	let publisherId = modalListPaketLangganan.querySelector('input[name="publisher_id"]').value;
	let subscribe_id = e.getAttribute('data-id');

	let items = [{
		"item_id": parseInt(ebookId),
		"item_type": "ebook",
		"id_subscribe_type": subscribe_id,
		"id_publisher": parseInt(publisherId),
	}];
	let data = {
		"tax": tax,
		"biaya_admin": biaya_admin,
		"items": items
	};

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

// CANCEL CHECKOUT
function cancelCheckout(e) {
	if(btnGunakanVoucher.innerText === 'Hapus Voucher'){ 
		// UNREDEEM VOUCHER
		let form = new FormData();
		form.append('voucher_code', aVoucherCode.value.trim());
		form.append('checkout_id', paketBundlingId);
		form.append('csrf_token_name', document.getElementsByName('csrf_token_name')[0].value);

		let xml = new XMLHttpRequest();
		xml.open('POST', BASE_URL + 'api/Api_voucher/removeVoucher', true);
		xml.onreadystatechange = function () {
			if (xml.readyState === 4 && xml.status === 200) {
				let response = JSON.parse(xml.responseText);
				if(response.status === true){
					// update token
					document.getElementsByName('csrf_token_name')[0].value = response.csrf_token;
					console.log('Voucher berhasil dihapus');
				} else {
					// update token
					document.getElementsByName('csrf_token_name')[0].value = response.csrf_token;
					console.error('Error removing voucher:', response.message);
				}
			}
		};
		xml.send(form);
	}

	history.back();
}

// Cegah user close tab / browser / refresh
window.addEventListener("beforeunload", function (e) {
    // Pesan custom sudah tidak bisa ditampilkan di browser modern
    // Tapi returnValue wajib di-set agar event terpicu
    // e.preventDefault();
    // e.returnValue = "";

    console.log("⚠️ User mencoba close tab / browser / refresh", isCancelTab);

	if(isCancelTab){
		cancelCheckout();
	}
});
// ===================================== UPDATE CHECKOUT END ==============================
