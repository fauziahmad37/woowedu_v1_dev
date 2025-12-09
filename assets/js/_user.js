let csrfName = document.getElementsByName('csrf_name')[0];
let csrfToken = document.getElementsByName('csrf_token')[0];

let group_semua_buku = document.getElementById('group-semua-buku');
let group_belum_dibaca = document.getElementById('group-belum-dibaca');
let group_sedang_dibaca = document.getElementById('group-sedang-dibaca');
let group_selesai_dibaca = document.getElementById('group-selesai-dibaca');

let btnBelumDibaca = document.getElementById('btn-belum-dibaca');
let btnSedangDibaca = document.getElementById('btn-sedang-dibaca');
let btnSelesaiDibaca = document.getElementById('btn-selesai-dibaca');

let totalBukuSayaCount = document.getElementsByClassName('total-buku-saya-count')[0];
let totalRiwayatPembelianCount = document.getElementsByClassName('total-riwayat-pembelian-count')[0];
let btnCariRiwayatPembelian = document.getElementById('btn-cari-riwayat-pembelian');
let btnResetRiwayatPembelian = document.getElementById('btn-reset-riwayat-pembelian');

// ========================================================================================
// =================================== SECTION MY EBOOK ====================================
// ========================================================================================

let kategoriBuku = [];
let filterTanggalAwalEbook = '';
let filterTanggalAkhirEbook = '';

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

// GET MY EBOOK
function getMyEbook(read_status = 0, page = 1, limit = 10) {
	let category_id = document.getElementById('select-category').value;
	let tanggalAwal = document.getElementById('filter-tanggal-awal-ebook').value;
	let tanggalAkhir = document.getElementById('filter-tanggal-akhir-ebook').value;

	// Clear Group Semua Buku
	document.getElementById('group-semua-buku').innerHTML = '';

	let xhr = new XMLHttpRequest();

	let url = '';
	if (read_status === -1) {
		url = 'api/Api_my_ebook/getMyEbook?page=' + page + '&limit=' + limit + '&category_id=' + category_id + '&tanggal_awal=' + tanggalAwal + '&tanggal_akhir=' + tanggalAkhir;
	} else {
		url = 'api/Api_my_ebook/getMyEbook?read_status=' + read_status + '&category_id=' + category_id + '&tanggal_awal=' + tanggalAwal + '&page=' + page + '&limit=' + limit;
	}

	xhr.open('GET', url, true);
	xhr.setRequestHeader('Content-Type', 'application/json');
	xhr.onreadystatechange = function () {
		if (xhr.readyState === 4 && xhr.status === 200) {
			let response = JSON.parse(xhr.responseText);
			let data = response.data.fetch;
			if (response.status) {
				// Clear previous content	

				if (data.length > 0) {
					let ebookItem = '';
					data.forEach(item => {
						ebookItem += `
							<div class="card rounded-4 me-4 pb-3" style="width: 233px; display:inline-block; float:none;">
								<img class="img-fluid mt-2 rounded-3" src="${(item.cover_img.includes('http') ? item.cover_img : 'assets/images/ebooks/cover/' + item.cover_img)}" alt="">

								<div class="cover-kadaluarsa ${(moment(item.end_activation) < moment()) ? '' : 'd-none'}">
									<button class="btn btn-lg fs-12 rounded-pill text-white" style="background: #74788D;">KADALUARSA</button>
								</div>

								<p class="publisher-name fs-12 mt-3 text-body-secondary">Penerbit: ${item.author}</p>
								<p class="book-title-card mt-2 fs-16 fw-bold">${item.title}</p>

								<div class="row text-body-secondary">
									<div class="col-8 fs-12">Progress Membaca</div>
									<div class="col-4 fs-12 text-end">${item.reading_progress}%</div>
								</div>

								<div class="progress mt-2" style="margin-bottom: 60px;" role="progressbar" aria-label="Example 5px high" aria-valuenow="${item.reading_progress}" aria-valuemin="0" aria-valuemax="100" style="height: 10px">
									<div class="progress-bar" style="width: ${item.reading_progress}%"></div>
								</div>
								${buttonGroup(item)}
							</div>
						`;
					});

					if (read_status === 0) {
						group_belum_dibaca.innerHTML = ebookItem;

					} else if (read_status === 1) {
						group_sedang_dibaca.innerHTML = ebookItem;

					} else if (read_status === 2) {
						group_selesai_dibaca.innerHTML = ebookItem;
					} else if (read_status === -1) {
						btnBelumDibaca.classList.remove('active');
						group_belum_dibaca.classList.add('d-none');
						group_semua_buku.classList.remove('d-none');
						group_semua_buku.classList.add('d-flex');
						group_semua_buku.innerHTML = ebookItem;
						totalBukuSayaCount.innerHTML = data.length;
					}
				} else {
					if (read_status === 0) {
						group_belum_dibaca.innerHTML = '<p>Buku belum dibaca tidak ditemukan.</p>';
					} else if (read_status === 1) {
						group_sedang_dibaca.innerHTML = '<p>Buku sedang dibaca tidak ditemukan.</p>';
					} else if (read_status === 2) {
						group_selesai_dibaca.innerHTML = '<p>Buku selesai dibaca tidak ditemukan.</p>';
					} else if (read_status === -1) {
						group_semua_buku.classList.add('d-flex');
						group_semua_buku.innerHTML = '<p>Buku tidak ditemukan.</p>';
						totalBukuSayaCount.innerHTML = 0;
					}
				}

			} else {
				console.error('Error fetching unread ebook count:', response.message);
			}
		}
	}
	xhr.send();

}

//GET ALL MY EBOOK
getMyEbook(-1, 1, 10);

// GET MY EBOOK UNREAD
getMyEbook();

// GET MY EBOOK SEDANG DIBACA
getMyEbook(1, 1, 10);

// GET MY EBOOK SUDAH DIBACA
getMyEbook(2, 1, 10);

// FUNGSI UNTUK BUTTON BACA
function buttonGroup(data) {
	let button = '';
	if (moment(data.end_activation) < moment()) {
		// Jika ebook sudah kadaluarsa
		button = `<a href="ebook/detail/${data.ebook_id}" class="btn btn-lg position-absolute mb-2 fs-12" style="bottom:5px; width: 90%; background:#D1D2D9;">Aktifkan Langganan</a>`;
	}
	else {
		// Jika ebook masih aktif
		// jika status read nya 1 atau sedang di baca
		if (data.read_status === 1) {
			button = `<a href="user/my_ebook_detail?id=${data.ebook_id}&continue=true&my_ebook_id=${data.id}" class="btn btn-primary btn-lg position-absolute mb-2 fs-12" style="bottom:5px; width: 90%;"><img src="assets/images/icons/book-open.png" width="16" alt="" class="me-2">Lanjutkan Baca</a>`;
		} else {
			button = `<a href="user/my_ebook_detail?id=${data.ebook_id}&continue=true&my_ebook_id=${data.id}" class="btn btn-primary btn-lg position-absolute mb-2 fs-12" style="bottom:5px; width: 90%;"><img src="assets/images/icons/book-open.png" width="16" alt="" class="me-2">Lanjutkan Baca</a>`;
		}
	}
	return button;
}

// SECTION FILTER BUKU SAYA
document.getElementById('btn-cari-riwayat-ebook-saya').addEventListener('click', function () {
	filterKategoriBuku = document.getElementById("select-category").value;
	filterTanggalAwalEbook = document.getElementById('filter-tanggal-awal-ebook').value;
	filterTanggalAkhirEbook = document.getElementById('filter-tanggal-akhir-ebook').value;

	// TAB BUKU BELUM DI BACA KLIK
	// document.getElementById('btn-belum-dibaca').click();

	// CLEAR ACTIVE TAB STATUS BUKU
	let btnStatusBuku = document.querySelectorAll('#btn-status-buku button');
	btnStatusBuku.forEach((element) => {
		element.classList.remove('active');
	});

	// CLEAR GROUP BUKU BELUM DIBACA, SEDANG DI BACA & SELESAI DI BACA DAN HIDE SEMUA NYA
	$('#group-belum-dibaca').empty();
	$('#group-sedang-dibaca').empty();
	$('#group-selesai-dibaca').empty();

	// HIDE SEMUA GROUP
	$('.group-ebook-saya').hide();

	// UNHIDE GROUP SEMUA BUKU
	group_semua_buku.classList.remove('d-none');

	// JALANKAN FUNGSI SEMUA BUKU
	getMyEbook(-1, 1, 1000);

	// JALANKAN FUNGSI GET DATA MY EBOOK
	// GET MY EBOOK UNREAD
	getMyEbook();

	// GET MY EBOOK SEDANG DIBACA
	getMyEbook(1, 1, 10);

	// GET MY EBOOK SUDAH DIBACA
	getMyEbook(2, 1, 10);

});

document.getElementById('btn-reset-riwayat-ebook-saya').addEventListener('click', function () {
	// RESET FILTER
	document.getElementById("select-category").value = '';
	document.getElementById('filter-tanggal-awal-ebook').value = '';
	document.getElementById('filter-tanggal-akhir-ebook').value = '';

	filterKategoriBuku = '';
	filterTanggalAwalEbook = '';
	filterTanggalAkhirEbook = '';

	$('select[name="select-category"]').val(null).trigger('change');

	// TAB BUKU BELUM DI BACA KLIK
	document.getElementById('btn-belum-dibaca').click();

	// HIDE GROUP SEMUA BUKU
	group_semua_buku.classList.add('d-none');

	// CLEAR ACTIVE TAB STATUS BUKU
	let btnStatusBuku = document.querySelectorAll('#btn-status-buku button');
	btnStatusBuku.forEach((element) => {
		element.classList.remove('active');
	});

	// CLEAR GROUP BUKU BELUM DIBACA, SEDANG DI BACA & SELESAI DI BACA
	$('#group-belum-dibaca').empty();
	$('#group-sedang-dibaca').empty();
	$('#group-selesai-dibaca').empty();

	//GET ALL MY EBOOK
	getMyEbook(-1, 1, 10);

	// GET MY EBOOK UNREAD
	getMyEbook();

	// GET MY EBOOK SEDANG DIBACA
	getMyEbook(1, 1, 10);

	// GET MY EBOOK SUDAH DIBACA
	getMyEbook(2, 1, 10);
});

// ENTER KEY PRESS DI INPUT FILTER EBOOK SAYA
document.querySelector('#filter-ebook-saya').addEventListener('keydown', (event) => {
    // Check if the pressed key is "Enter"
	if (event.key === 'Enter') {
		event.preventDefault(); // Prevent the default action (form submission)
		document.getElementById('btn-cari-riwayat-ebook-saya').click(); // Trigger the search button click
	}
});

// BUTTON BUKU BELUM DI BACA KLIK
btnBelumDibaca.addEventListener('click', function () {
	// REMOVE DISPLAY NONE DI GROUP BELUM DI BACA
	group_belum_dibaca.classList.remove('d-none');
});

// =========================================================================================
// ================================== END SECTION MY EBOOK =================================
// =========================================================================================

// ========================================================================================
// ============================== SECTION RIWAYAT PEMBELIAN ===============================
// ========================================================================================

let btnShowMore = document.getElementById('show-more-riwayat');
let pageTransaksi = 1;
let filterStatusPembayaran = document.getElementById('filter-status-pembelian').value;
let filterTanggalAwal = document.getElementById('filter-tanggal-awal').value;
let filterTanggalAkhir = document.getElementById('filter-tanggal-akhir').value;

function getRiwayatPembelian(page = 1, limit = 10) {
	let xhr = new XMLHttpRequest();
	xhr.open('GET', 'api/Api_ebook/getAllTransaction?page=' + page + '&limit=' + limit + '&status=' + filterStatusPembayaran + '&start_date=' + filterTanggalAwal + '&end_date=' + filterTanggalAkhir, true);
	xhr.setRequestHeader('Content-Type', 'application/json');
	xhr.onreadystatechange = function () {
		if (xhr.readyState === 4 && xhr.status === 200) {
			let response = JSON.parse(xhr.responseText);
			if (response.status) {
				let data = response.data.fetch;
				let totalData = response.data.total_data;
				let riwayatItem = '';
				data.forEach(item => {
					riwayatItem += cardItemTransaksi(item);
				});

				$('#list-riwayat-pembelian-container').append(riwayatItem);
				totalRiwayatPembelianCount.innerHTML = totalData;
				$('.riwayat-count').eq(0).html(totalData);
				$('.riwayat-count').eq(1).html(`(${totalData} Item)`);

				// JALANKAN FUNGSI COUNTER PENDING PEMBAYARAN
				startPaymentPendingCounter();

				// BUTTON SHOW MORE
				if (data.length < limit) {
					$('#show-more-riwayat').hide();
				}
				else {
					$('#show-more-riwayat').show();
				}

				// JIKA TOTAL DATANYA 0
				if (totalData === 0) {
					$('#list-riwayat-pembelian-container').html('<p>Data transaksi tidak di temukan.</p>');
				}
			} else {
				console.error('Error fetching purchase history:', response.message);
				$('#list-riwayat-pembelian-container').html('<p>No purchase history found.</p>');
			}
		}
	};
	xhr.send();
}

// GET RIWAYAT PEMBELIAN
getRiwayatPembelian();

btnShowMore.addEventListener('click', function () {
	pageTransaksi++;
	getRiwayatPembelian(pageTransaksi);
});

// Function to determine the status of the payment
function statusPembayaran(item) {
	let statusClass = '';
	let statusText = '';
	switch (item.status) {
		case 'settlement':
			statusClass = 'bg-success-subtle text-success';
			statusText = 'Pembayaran Berhasil';
			break;
		case 'pending':
			statusClass = 'bg-warning-subtle text-warning';
			statusText = 'Menunggu Pembayaran';
			break;
		case 'expire':
			statusClass = 'bg-danger-subtle text-danger';
			statusText = 'Pembayaran Kadaluarsa';
			break;
		case 'cancel':
			statusClass = 'bg-danger-subtle text-danger';
			statusText = 'Pembayaran Dibatalkan';
			break;
		default:
			statusClass = 'bg-secondary-subtle text-secondary';
			statusText = 'Status Tidak Diketahui';
			break;
	}
	return `
		<div class="d-flex align-items-center mb-3">
			<div class="badge ${statusClass} rounded-3 px-3 py-2">
				<i class="bi bi-check-circle-fill me-2"></i>
				${statusText}
			</div>
			<div class="ms-2" style="color: #74788D; font-size: 12px;">
				<span class="mb-0 me-3">${moment(item.created_at).format('DD MMM YYYY, HH:mm')}</span>
				<span class="mb-0">${item.transaction_number}</span>
			</div>
		</div>
	`;
}

// FUNGSI BUTTON GROUP PEMBAYARAN
function buttonGroupPembayaran(item) {
	let button = '';
	let itemRating = `{
		ebook_id: ${item.checkout_items[0].item_id},
		title: '${item.checkout_items[0].item_name}',
		publisher: '-',
		cover_img: '${item.checkout_items[0].detail_data.cover_img.includes('http') ? item.checkout_items[0].detail_data.cover_img : 'assets/images/ebooks/cover/' + item.checkout_items[0].detail_data.cover_img}',
	}`;
	let buttonUlasan = (item.checkout_items.length == 1) ? `<button class='btn btn-light border-primary me-3' onclick="beriUlasan(${itemRating})">Beri Ulasan</button>` : '';
	let buttonBeliLagi = (item.checkout_items.length == 1) ? `<a class='btn btn-primary' onclick="checkoutUlang(${item.field_id})">Beli Lagi</a>` : '';
	if (item.status == 'settlement') {
		button = `${buttonUlasan}
				${buttonBeliLagi}`;
	} else if (item.status == 'pending') {
		button = `<a class='btn btn-primary' onclick="getTransactionStatus('${item.transaction_number}')">Lanjutkan Pembayaran</a>`;
	} else if (item.status == 'failed') {
		// button = `<a class='btn btn-light border-primary me-3'>Beri Ulasan</a><a class='btn btn-primary' href='checkout/detail/${item.item_id}'>Checkout Ulang</a>`;
		button = `<a class='btn btn-light border-primary me-3'>Beri Ulasan</a><button type="button" class='btn btn-primary' onclick="checkoutUlang(${item.item_id})">Checkout Ulang</button>`;
	} else if (item.status == 'cancel') {
		// button = `<a class='btn btn-primary' href='bundlingPackage/checkout/${item.field_id}'>Checkout Ulang</a>`;
		button = `<button type="button" class='btn btn-primary' onclick="checkoutUlang(${item.field_id})">Checkout Ulang</button>`;
	} else {

	}

	return button;
}

// function to generate the checkout item HTML
function checkoutItem(items) {
	let listItem = '';

	// jika items nya hanya satu
	if (items.length === 1) {
		items.forEach(item => {

			// JIKA ITEM TYPE NYA EBOOK
			let itemType = item.item_type;
			listItem += `
				<div class="row mt-4 mb-3">
					<div class="col-12 d-flex" onclick="checkDetail('${itemType}', '${item.item_id}')" style="cursor: pointer;">
						<img class="" src="${item.detail_data.cover_img.includes('http') ? item.detail_data.cover_img : 'assets/images/ebooks/cover/' + item.detail_data.cover_img}" alt="ebook-cover" width="80" height="120" style="border-radius: 12px;">
						<div class="flex-row ms-3">
							<h6 class="">${item.item_name}</h6>
							<h4>Rp ${item.item_price.toLocaleString()}</h4>
						</div>
					</div>
				</div>
				`;

		});
	} else {
		items.forEach((item, idx) => {
			if (idx === 0) {

				listItem += `
					<div class="row mt-4 mb-3">
						<div class="col-12 d-flex">
							<img class="" src="${item.detail_data.cover_img.includes('http') ? item.detail_data.cover_img : 'assets/images/ebooks/cover/' + item.detail_data.cover_img}" alt="ebook-cover" width="80" height="120" style="border-radius: 12px;">
								<div class="flex-row ms-3">
									<h6 class="">${item.item_name}</h6>
									<h4>Rp ${item.item_price.toLocaleString()}</h4>
								</div>
						</div>
					</div>

					<p class="d-inline-flex gap-1">
						<button class="btn btn-light text-primary collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
							Lihat ${items.length - 1} Produk Lainnya
							<i class="bi bi-chevron-down"></i>
						</button>
					</p>

					<div class="collapse" id="collapseExample" style="">
					`;
			} else {
				listItem += `
					<div class="row mt-4 mb-3">
						<div class="col-12 d-flex">
							<img class="" src="${item.detail_data.cover_img.includes('http') ? item.detail_data.cover_img : 'assets/images/ebooks/cover/' + item.detail_data.cover_img}" alt="ebook-cover" width="80" height="120" style="border-radius: 12px;">
								<div class="flex-row ms-3">
									<h6 class="">${item.item_name}</h6>
									<h4>Rp ${item.item_price.toLocaleString()}</h4>
								</div>
						</div>
					</div>`;
			}
		});
		listItem += `
					</div>
				`;
	}


	return listItem;
}

// FUNGSI GET TRANSACTION STATUS
function getTransactionStatus(transactionNumber) {
	let xhr = new XMLHttpRequest();
	xhr.open('GET', 'api/Api_ebook/getTransactionDetail?transaction_number=' + transactionNumber, true);
	xhr.setRequestHeader('Content-Type', 'application/json');
	xhr.onreadystatechange = function () {
		if (xhr.readyState === 4 && xhr.status === 200) {
			let response = JSON.parse(xhr.responseText);
			if (response.status) {
				let data = response.data;
				// if(data.transaction_status === 'settlement') {
				// jika response status nya true
				if (data.status) {
					window.location.href = 'checkout/statusPembayaran?transaction_number=' + transactionNumber;
				} else {
					// jika response status nya false
					// jalankan ajax untuk melakukan update status transaksi menjadi cancel
					let cancelXhr = new XMLHttpRequest();
					cancelXhr.open('GET', 'api/Api_ebook/updateTransactionStatus?transaction_number=' + transactionNumber + '&status=cancel', true);
					cancelXhr.onreadystatechange = function () {
						if (cancelXhr.readyState === 4 && cancelXhr.status === 200) {
							let cancelResponse = JSON.parse(cancelXhr.responseText);
							if (cancelResponse.status) {
								alert('Transaksi telah dibatalkan karena tidak ada metode pembayaran yang dipilih.');
								window.location.reload();
							} else {
								console.error('Error updating transaction status:', cancelResponse.message);
							}
						}
					}
					cancelXhr.send();
				}
			} else {
				console.error('Error fetching transaction status:', response.message);
			}
		}
	};
	xhr.send();
}

// FILTER RIWAYAT TRANSAKSI
btnCariRiwayatPembelian.addEventListener('click', function () {
	$('#list-riwayat-pembelian-container').html(''); // hapus semua data
	pageTransaksi = 1;
	filterStatusPembayaran = document.getElementById('filter-status-pembelian').value;
	filterTanggalAwal = document.getElementById('filter-tanggal-awal').value;
	filterTanggalAkhir = document.getElementById('filter-tanggal-akhir').value;
	getRiwayatPembelian(pageTransaksi);


	// let xhr = new XMLHttpRequest();
	// xhr.open('GET', 'api/Api_ebook/getAllTransaction?status=' + filterStatusPembayaran + '&tanggal_awal=' + filterTanggalAwal + '&tanggal_akhir=' + filterTanggalAkhir, true);
	// xhr.setRequestHeader('Content-Type', 'application/json');
	// xhr.onreadystatechange = function () {
	// 	if (xhr.readyState === 4 && xhr.status === 200) {
	// 		let response = JSON.parse(xhr.responseText);
	// 		if (response.status) {
	// 			// Tampilkan data riwayat transaksi
	// 			// clear list-riwayat-pembelian-container
	// 			$('#list-riwayat-pembelian-container').html('');
	// 			let data = response.data.fetch;
	// 			data.forEach(item => {
	// 				$('#list-riwayat-pembelian-container').append(cardItemTransaksi(item));
	// 			});

	// 		} else {
	// 			console.error('Error fetching transaction history:', response.message);
	// 		}
	// 	}
	// };
	// xhr.send();
});

// RESET FILTER ACTION
btnResetRiwayatPembelian.addEventListener('click', function () {
	document.getElementById('filter-status-pembelian').value = '';
	document.getElementById('filter-tanggal-awal').value = '';
	document.getElementById('filter-tanggal-akhir').value = '';

	filterStatusPembayaran = ''; // kosongkan filter status pembayaran
	$('#list-riwayat-pembelian-container').html(''); // hapus semua data

	// Reset the transaction history
	getRiwayatPembelian(pageTransaksi);
});

// ENTER KEY PRESS DI INPUT FILTER RIWAYAT PEMBELIAN
document.querySelector('#filter-riwayat-pembelian').addEventListener('keydown', (event) => {
	// Check if the pressed key is "Enter"
	if (event.key === 'Enter') {
		event.preventDefault(); // Prevent the default action (form submission)
		document.getElementById('btn-cari-riwayat-pembelian').click(); // Trigger the search button click
	}
});	

// CARD ITEM TRANSAKSI
function cardItemTransaksi(item) {
	return `<div class="card p-3 mt-4 rounded-4 border-light-subtle border-2">

			${statusPembayaran(item)}

			<div class="checkout-item-container mt-3">
				${checkoutItem(item.checkout_items)}
			</div>

			<div class="py-3 fw-bold border border-light-subtle border-start-0 border-end-0 border-1 row">
				<div class="col-6">
					<span class="">Jumlah Bayar</span>
				</div>
				<div class="col-6 text-end">
					<span class="">Rp ${item.total_payment.toLocaleString()}</span>
				</div>
			</div>
			

			<div class="row">
				<div class="col">
				${(item.status == 'pending') ? `<p class="mt-4" style="font-size: 12px;">Batas waktu pembayaran, <span class="text-danger fw-bold expire-time" data-transaction-id="${item.field_id}" data-expire-time="${item.created_at}"></span> </p>` : ``}

				</div>
				<div class="col text-end pt-3">

					${buttonGroupPembayaran(item)}

					<button class="btn btn-outline-primary ms-2" onclick="getDetailCheckout('${item.field_id}')" data-bs-toggle="modal" data-bs-target="#detailTransaksi"><i class="bi bi-eye"></i></button>
				</div>
			</div>

		</div>`;
}

// GET DETAIL TRANSAKSI
function getDetailCheckout(checkout_id) {
	let xhr = new XMLHttpRequest();
	xhr.open('GET', 'api/Api_ebook/getDetailCheckout?checkout_id=' + checkout_id, true);
	xhr.setRequestHeader('Content-Type', 'application/json');
	xhr.onreadystatechange = function () {
		if (xhr.readyState === 4 && xhr.status === 200) {
			let response = JSON.parse(xhr.responseText);
			if (response.status) {
				// Tampilkan detail transaksi
				$('.invoice-number').html(response.data.transaction_number);
				$('.tanggal-pembelian').html(moment(response.data.created_at).format('DD MMM YYYY, hh:mm'));
				$('.total-ebook').html(response.data.total_qty);
				$('.total-harga').html('Rp ' + response.data.gross_amount.toLocaleString());
				$('.amount-admin').html('Rp ' + response.data.biaya_admin.toLocaleString());
				$('.amount-voucher').html(response.data.voucher ? 'Rp ' + response.data.voucher.toLocaleString() : 'Tidak ada voucher');
				$('.amount-promo').html('Rp ' + response.data.discount.toLocaleString());
				$('.total-amount').html('Rp ' + response.data.total_price.toLocaleString());
			} else {
				console.error('Error fetching transaction detail:', response.message);
			}
		}
	};
	xhr.send();
}

// BERI ULASAN
let beriUlasanModal = new Vue({
	el: '#reviewModal',
	data: {
		ebook: {}
	},
	methods: {
		getEbookDetail(ebookId) {
			const xhr = new XMLHttpRequest();
			xhr.open('GET', 'api/api_my_ebook/getDetailMyEbook?ebook_id=' + ebookId, true);
			xhr.onload = () => {
				if (xhr.status === 200) {
					let response = JSON.parse(xhr.responseText);
					if (response.status === true) {
						this.ebook = response.data;
						this.ebook.cover_img = (response.data.cover_img.includes('http')) ? response.data.cover_img : 'assets/images/ebooks/cover/' + response.data.cover_img;
					} else {
						console.error('Error fetching ebook details:', response.message);
					}
				} else {
					console.error('Error fetching ebook details:', xhr.statusText);
				}
			};
			xhr.send();
		},

		setRating(star) {
			// set color star
			this.ebook.rating = star;
			this.$forceUpdate();
		},

		submitReview() {
			let form = new FormData();
			form.append('ebook_id', this.ebook.id);
			form.append('rating', this.ebook.rating);
			form.append('review', document.getElementById('review').value);
			form.append(csrfName.content, csrfToken.content);

			const xhr = new XMLHttpRequest();
			xhr.open('POST', 'api/api_my_ebook/giveRating', true);
			xhr.onload = () => {
				if (xhr.status === 200) {
					let response = JSON.parse(xhr.responseText);
					if (response.status === true) {
						// sweet alert
						Swal.fire({
							icon: 'success',
							title: 'Ulasan Berhasil Dikirim',
							text: 'Terima kasih atas ulasan Anda!',
						});

						// close modal
						$('#reviewModal').modal('hide');
					} else {
						Swal.fire({
							icon: 'error',
							title: 'Gagal Mengirim Ulasan',
							text: response.message,
						});
					}

					// update token
					csrfToken.content = response.csrf_token;
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Gagal Mengirim Ulasan',
						text: xhr.statusText,
					});
				}
			};
			xhr.send(form);
		}
	}
});

// Set the ebook data in the modal beri ulasan
function beriUlasan(item) {
	// Set the ebook data in the modal vue js
	beriUlasanModal.getEbookDetail(item.ebook_id);

	$('#reviewModal').modal('show');
}

// Counter Payment Pending
function startPaymentPendingCounter() {
	let expireElements = document.querySelectorAll('.expire-time');

	expireElements.forEach(el => {
		let expireTime = moment(el.dataset.expireTime) + moment.duration(30, 'minutes');

		let countdown = setInterval(() => {
			let now = moment();
			let duration = moment.duration(now.diff(expireTime));
			// console.log(duration.asMilliseconds() * -1);
			if (duration.asSeconds() * -1 <= 0) {
				clearInterval(countdown);
				el.innerHTML = 'Waktu habis';
			} else {
				el.innerHTML = moment(duration.asMilliseconds() * -1).format('mm:ss') + ' lagi';
			}
		}, 1000);
	});
}

// PROSES CHECKOUT ULANG
function checkoutUlang(checkoutId) {

	// get detail checkout
	let xhr = new XMLHttpRequest();
	xhr.open('GET', 'api/Api_ebook/getDetailCheckout?checkout_id=' + checkoutId, true);
	xhr.setRequestHeader('Content-Type', 'application/json');
	xhr.onreadystatechange = function () {
		// Loading
		if (xhr.readyState === 1) {
			Swal.fire({
				title: 'Memproses Checkout Ulang...',
				text: 'Mohon tunggu sebentar.',
				didOpen: () => {
					Swal.showLoading();
				},
				allowOutsideClick: false,
				showConfirmButton: false
			});
		}

		if (xhr.readyState === 4 && xhr.status === 200) {
			let response = JSON.parse(xhr.responseText);
			if (response.status) {

				// redirect to checkout page
				// window.location.href = 'checkout/detail/' + checkoutId;
				// let publisherId = document.getElementsByName('publisher_id')[0].value;

				let tax = response.data.tax; // Ganti dengan nilai pajak yang sesuai
				let biayaAdmin = response.data.biaya_admin; // Ganti dengan nilai biaya admin yang sesuai

				let items = [];
				response.data.checkout_items.forEach(item => {
					item.publisher_items.forEach(pub => {
						items.push({
							"item_id": pub.item_id,
							"item_type": pub.item_type,
							"id_subscribe_type": pub.id_subscribe_type,
							"id_publisher": pub.id_publisher
						});
					});
				});

				let data = {
					"tax": tax,
					"biaya_admin": biayaAdmin,
					"items": items
				};


				let xml2 = new XMLHttpRequest();
				xml2.open('POST', BASE_URL + 'api/Api_ebook/checkout', true);
				xml2.setRequestHeader('Content-Type', 'application/json');
				xml2.onreadystatechange = function () {
					if (xml2.readyState === 4 && xml2.status === 200) {
						let response = JSON.parse(xml2.responseText);
						if (response.status === true) {
							// console.log('Checkout successful:', response);
							window.location.href = BASE_URL + 'BundlingPackage/checkout/' + response.data.id;
						} else {
							console.error('Error during checkout:', response.message);
						}
					}
				}
				xml2.send(JSON.stringify(data));
			} else {
				console.error('Error fetching transaction detail:', response.message);
			}
		}
	};
	xhr.send();

}

// CHECK DETAIL BUKU ATAU BUNDLING DARI RIWAYAT PEMBELIAN
function checkDetail(itemType, itemId) {
	let url = '';
	if (itemType === 'ebook') {
		url = 'api/Api_ebook/getEbookDetail?ebook_id=' + itemId;
	} else {
		url = 'api/Api_ebook/getDetailPaketBundling?package_id=' + itemId;
	}

	let xhr = new XMLHttpRequest();
	xhr.open('GET', url, true);
	xhr.setRequestHeader('Content-Type', 'application/json');
	xhr.onreadystatechange = function () {
		if (xhr.readyState === 4 && xhr.status === 200) {
			let response = JSON.parse(xhr.responseText);
			
			if (response.status) {
				let data = response.data;
				if (itemType === 'ebook') {
					// redirect to ebook detail
					window.location.href = 'ebook/detail/' + itemId;
				} else {
					// redirect to bundling detail
					window.location.href = 'BundlingPackage/detail/' + itemId;
				}
			} else {
				Swal.fire({
					icon: 'error',
					title: 'Data Tidak Ditemukan',
					type: 'error',
					text: response.message,
				});
			}
		}
	};
	xhr.send();
}

// ========================================================================================
// =================================== SECTION WISHLIST ===================================
// ========================================================================================

function getAllWishlist() {
	let xhr = new XMLHttpRequest();
	xhr.open('GET', 'api/Api_wishlist/getAllWishlist', true);
	xhr.setRequestHeader('Content-Type', 'application/json');
	xhr.onreadystatechange = function () {
		if (xhr.readyState === 4 && xhr.status === 200) {
			let response = JSON.parse(xhr.responseText);
			let data = response.data.fetch;
			let totalData = response.data.total_data;
			if (response.status) {
				let wishlistItem = '';
				data.forEach(item => {
					wishlistItem += `
						<li class="splide__slide">
							<a href="${item.item_type == 'ebook' ? 'Ebook/detail/' + item.item_id : 'BundlingPackage/detail/' + item.item_id}" class="text-decoration-none">
								<div class="card rounded-3 border-light-subtle p-3 m-1">
									<img class="rounded-3" src="${item.item_type == 'ebook' ? (item.item_detail.cover_img.includes('http') ? item.item_detail.cover_img : 'assets/images/ebooks/cover/' + item.item_detail.cover_img) : 'assets/images/bundlings/cover/' + item.cover_img}" alt="" width="128" height="172">
									<p class="fs-12 lh-1 mt-3 mb-2 book-publiher-card">Penerbit: ${item.item_detail.publisher}</p>
									<p class="fs-14 mb-0 book-title-card">${item.item_detail.title}</p>
								</div>
							</a>
						</li>
					`;
				});
				document.getElementById('list-wishlist').innerHTML = wishlistItem;
			} else {
				console.error('Error fetching wishlist:', response.message);
				document.getElementById('list-wishlist').innerHTML = '<p>No wishlist items found.</p>';
			}

			// Total wishlist count
			document.getElementsByClassName('wishlist-count')[0].innerHTML = totalData;
			document.getElementsByClassName('wishlist-count')[1].innerHTML = `(${totalData} Ebook)`;

			// Initialize Splide for wishlist carousel
			new Splide('#thumbnail-carousel-wishlists', {
				fixedWidth: 160,
				fixedHeight: 280,
				gap: 10,
				rewind: true,
				pagination: false,
			}).mount();
		}
	};
	xhr.send();
}

// GET ALL WISHLIST
getAllWishlist();

// =========================================================================================
// =================================== END SECTION WISHLIST ================================
// =========================================================================================

// ========================================================================================
// =================================== SECTION SHOPPING CART ==============================
// ========================================================================================

function getAllShoppingCart() {
	let getData = new Promise((resolve, reject) => {
		let xhr = new XMLHttpRequest();
		xhr.open('GET', 'api/Api_shopping_cart/getAllShoppingCart', true);
		xhr.setRequestHeader('Content-Type', 'application/json');
		xhr.onreadystatechange = function () {
			if (xhr.readyState === 4 && xhr.status === 200) {
				let response = JSON.parse(xhr.responseText);
				if (response.status) {
					let data = response.data.fetch;
					let totalData = response.data.total_item;
					let cartItem = '';
					data.forEach(item => {

						cartItem += `
						 <div class="card w-100 flex-row align-items-center p-3 mb-3">
							<ul class="list-group list-group-flush w-100">
								<li class="list-group-item d-flex align-items-center">
									<span class="form-check align-middle">
										<input type="checkbox" class="form-check-input" x-bind="SelectAllPublisher">
										<label class="form-check-label fw-semibold"><small></small>${item.title_cart}</label>
									</span>

									
								</li>
								
								<div>
									${ebookListItem(item.items, item.wishlist)}
								</div>
							
							</ul>
						</div>
					`;
					});

					document.getElementById('cart-ebook-container').innerHTML = cartItem;

					document.getElementsByClassName('cart-count')[0].innerHTML = totalData;
					document.getElementsByClassName('cart-count')[1].innerHTML = `(${totalData} Item)`;

					document.querySelector('#total-cart-container .total-barang').innerHTML = totalData;
					document.querySelector('#total-cart-container .total-harga').innerHTML = `Rp ${response.data.total_price.toLocaleString()}`;

					return resolve('ok');
				} else {
					console.error('Error fetching cart:', response.message);
				}
			}
		};
		xhr.send();

	})

	// After fetching data, we can set up event listeners for removing items
	getData.then(() => {
		// Remove item from cart
		let itemsChart = document.getElementsByClassName('remove-item-cart');
		for (let i = 0; i < itemsChart.length; i++) {
			itemsChart[i].addEventListener('click', function () {
				let itemId = this.closest('.list-group-item').dataset.itemId;

				removeItemCart(itemId);
			});
		}

		// CHECKLIST SEMUA ITEM DAN HITUNG TOTAL HARGA DAN QTY SAAT PERTAMA KALI HALAMAN DI LOAD
		let totalHarga = 0;
		let totalQty = 0;

		let checkboxes = document.querySelectorAll('input[name="book_id[]"]');
		checkboxes.forEach(checkbox => {
			checkbox.checked = true;
			totalHarga += parseInt(checkbox.dataset.itemPrice);
			totalQty++;
		});
		document.querySelector('#total-cart-container .total-barang').innerHTML = totalQty;
		document.querySelector('#total-cart-container .total-harga').innerHTML = `Rp ${totalHarga.toLocaleString()}`;

		// SETELAH KOMPONEN SELESAI DI RENDER AMBIL SEMUA CHECKBOX
		// INPUT CHECK ITEM KETIKA DI KLIK, HITUNG ULANG TOTAL HARGA
		checkboxes.forEach(checkbox => {
			checkbox.addEventListener('click', function () {
				if (this.checked) {
					checkbox.checked = true;
				} else {
					checkbox.checked = false;
				}

				let totalHarga = 0;
				let totalQty = 0;
				checkboxes.forEach(checkbox => {
					if (checkbox.checked) {
						let itemPrice = checkbox.dataset.itemPrice;
						totalHarga += parseInt(itemPrice);
						totalQty++;
					}
				});

				document.querySelector('#total-cart-container .total-barang').innerHTML = totalQty;
				document.querySelector('#total-cart-container .total-harga').innerHTML = `Rp ${totalHarga.toLocaleString()}`;
			});
		});

		// SELECT ALL CART KLIK
		let selectAllCheckbox = document.querySelector('#select-all-cart');
		selectAllCheckbox.addEventListener('change', function () {
			checkboxes.forEach(checkbox => {
				let totalHarga = 0;
				let totalQty = 0;
				checkboxes.forEach(checkbox => {
					if (checkbox.checked) {
						let itemPrice = parseInt(checkbox.dataset.itemPrice);
						totalHarga += itemPrice;
						totalQty++;
					}
				});

				document.querySelector('#total-cart-container .total-barang').innerHTML = totalQty;
				document.querySelector('#total-cart-container .total-harga').innerHTML = `Rp ${totalHarga.toLocaleString()}`;
			});
		});

		// CHECKLIST PER PUBLISHER
		let publisherCheck = document.querySelectorAll('.list-group-item > span > input');
		publisherCheck.forEach(publisher => {
			publisher.addEventListener('change', function () {
				let parent = this.closest('.list-group-item');
				let checkboxes = parent.nextElementSibling.querySelectorAll('input[name="book_id[]"]');
				checkboxes.forEach(checkbox => {
					if (parent.querySelector('input').checked === true) {
						checkbox.checked = true;
					} else {
						checkbox.checked = false;
					}
				});

				let totalHarga = 0;
				let totalQty = 0;
				let allCheckboxes = document.querySelectorAll('input[name="book_id[]"]');
				allCheckboxes.forEach(checkbox => {
					if (checkbox.checked) {
						let itemPrice = parseInt(checkbox.dataset.itemPrice);
						totalHarga += itemPrice;
						totalQty++;
					}
				});

				document.querySelector('#total-cart-container .total-barang').innerHTML = totalQty;
				document.querySelector('#total-cart-container .total-harga').innerHTML = `Rp ${totalHarga.toLocaleString()}`;
			});
		});

	});

}

// GET ALL SHOPPING CART
getAllShoppingCart();

function ebookListItem(items, wishlist) {
	let ebook = '';
	items.forEach(item => {

		ebook += `
			<li class="list-group-item d-flex align-items-baseline" data-item-id="${item.items_detail.id}" >
				<div class="d-flex align-self-start my-2">
					<span class="form-check align-middle">
						<input type="checkbox"  class="form-check-input" :value="item.id" name="book_id[]" :id="'select-all-' + idx" data-item-id="${item.items_detail.id}" data-item-price="${(item.type == 'ebook') ? item.items_detail.subscribe_type.actual_price : item.items_detail.price}" data-item-publisher-id="${item.items_detail.publisher_id}"  data-item-type="${item.type}">
						<input type="hidden" name="item_subscribe_id[]" value="${(item.type == 'ebook') ? item.items_detail.subscribe_type.id : -1}">
					</span>
				</div>

				<!-- figure class="d-flex ms-1 my-2" x-data="{cover: []}" x-init="cover = item.cover_img.split('.')" -->
				<div class="d-flex ms-1 my-2">
					<img loading="lazy" src="${(item.type == 'ebook') ? (item.items_detail.cover_img.includes('http') ? item.items_detail.cover_img : 'assets/images/ebooks/cover/' + item.items_detail.cover_img) : item.items_detail.package_image}" class="cart-cover rounded shadow-sm" alt="" >
					<figcaption class="d-flex flex-column ms-2">
						<span class="fw-semibold fs-6 lh-base">${(item.type == 'ebook') ? item.items_detail.title : item.items_detail.package_name}</span>
						${hargaCoret(item)}
						
						<h4 class="mt-auto fw-semibold" style="font-size: 18px;">Rp ${(item.type == 'ebook') ? item.items_detail.subscribe_type.actual_price.toLocaleString() : item.items_detail.price.toLocaleString()}</h4>
					</figcaption>
				</div>
				<!-- /figure -->
				<div class="ms-auto d-flex my-2 align-self-end">
					<a role="checkbox" class="mx-2 fs-5 remove-item-cart" aria-checked="false">
						<i class="bi bi-trash"></i>
					</a>
				</div>
			</li>
		`;
	});
	return ebook;
}

function hargaCoret(item) {
	if (item.type == 'ebook') {
		if (item.items_detail.subscribe_type.promo.promo_status == 1) {
			return `<span class="text-danger d-inline-flex align-items-center mt-2">
						<span class="px-2 py-1 lh-base rounded-pill bg-danger-subtle">${item.items_detail.subscribe_type.promo.percentage}%</span>
						<i class="ms-1 text-decoration-line-through">Rp ${item.items_detail.subscribe_type.price.toLocaleString()}</i>
					</span>`;
		} else {
			return ``;
		}

	} else {
		return ``;
	}
}

function removeItemCart(itemId) {
	let itemType = $(this).closest('.list-group-item').data('item-type');

	let form = new FormData();
	form.append('ebook_id', itemId);
	form.append('item_type', itemType);
	form.append('csrf_token_name', csrfToken.content);

	let xhr = new XMLHttpRequest();
	xhr.open("POST", "api/Api_shopping_cart/removeFromShoppingCart", true);
	xhr.onreadystatechange = function () {
		if (xhr.readyState === XMLHttpRequest.DONE) {
			let response = JSON.parse(xhr.responseText);
			if (response.status) {
				getAllShoppingCart();

				// update token
				csrfToken.content = response.csrf_token;

				// Sukses Alert
				Swal.fire({
					icon: 'success',
					type: 'success',
					title: 'Sukses',
					text: 'Item berhasil dihapus dari keranjang.',
				});
			} else {
				console.error('Error removing item from cart:', response.message);

				// update token
				csrfToken.content = response.csrf_token;

				// Error Alert
				Swal.fire({
					icon: 'error',
					type: 'error',
					title: 'Error',
					text: response.message,
				});
			}
		}
	};
	xhr.send(form);
}

// SELECT ALL CART
let btnSelectAllCart = document.getElementById('select-all-cart');
btnSelectAllCart.addEventListener('click', function () {
	let checkboxes = document.querySelectorAll('input[name="book_id[]"]');
	checkboxes.forEach(checkbox => {
		if (this.checked === true) {
			checkbox.checked = true;
		} else {
			checkbox.checked = false;
		}
	});
});

// DELETE ALL CART
let btnDeleteAllCart = document.querySelector('.delete-all-cart');
btnDeleteAllCart.addEventListener('click', function () {
	let checkboxes = document.querySelectorAll('input[name="book_id[]"]:checked');
	if (checkboxes.length > 0) {
		Swal.fire({
			type: 'warning',
			title: 'Hapus Semua Item',
			text: 'Apakah Anda yakin ingin menghapus semua item yang dipilih?',
			showCancelButton: true,
			confirmButtonText: 'Hapus',
			cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.value) {
				checkboxes.forEach(checkbox => {
					removeItemCart(checkbox.dataset.itemId);
				});
			}
		});
	} else {
		Swal.fire({
			type: 'info',
			title: 'Tidak Ada Item Dipilih',
			text: 'Silakan pilih item yang ingin dihapus.'
		});
	}
});

// BUTTON CHECKOUT
let checkoutBtn = document.querySelector('.checkout');
checkoutBtn.addEventListener('click', function () {

	let itemIds = Array.from(document.querySelectorAll('input[name="book_id[]"]:checked')).map(checkbox => checkbox.dataset.itemId);
	let itemTypes = Array.from(document.querySelectorAll('input[name="book_id[]"]:checked')).map(checkbox => checkbox.dataset.itemType);
	let subscribeTypeIds = Array.from(document.querySelectorAll('input[name="book_id[]"]:checked')).map(checkbox => checkbox.parentNode.querySelector('input[name="item_subscribe_id[]"]').value);
	let publisherIds = Array.from(document.querySelectorAll('input[name="book_id[]"]:checked')).map(checkbox => checkbox.dataset.itemPublisherId);

	let items = itemIds.map((id, index) => {
		return {
			"item_id": id,
			"item_type": itemTypes[index],
			"id_subscribe_type": subscribeTypeIds[index],
			"id_publisher": publisherIds[index]
		};
	});

	let data = {
		tax: 0,
		biaya_admin: 2000,
		items: items
	}

	let xhr = new XMLHttpRequest();
	xhr.open("POST", "api/Api_ebook/checkout", true);
	xhr.setRequestHeader('Content-Type', 'application/json');
	xhr.onreadystatechange = function () {
		if (xhr.readyState === XMLHttpRequest.DONE) {
			let response = JSON.parse(xhr.responseText);
			if (response.status) {
				Swal.fire({
					icon: 'success',
					type: 'success',
					title: 'Sukses',
					text: 'Checkout berhasil.',
				});

				let checkoutId = response.data.id;
				window.location.href = 'BundlingPackage/checkout/' + checkoutId;
			} else {
				console.error('Error during checkout:', response.message);
				Swal.fire({
					icon: 'error',
					type: 'error',
					title: 'Error',
					text: response.message,
				});
			}
		}
	};
	xhr.send(JSON.stringify(data));
});

// ================================================
// ================================== END SECTION SHOPPING CART ============================
// =========================================================================================
