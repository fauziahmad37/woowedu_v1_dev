let ebookId = document.getElementById('ebook_id').value;

function getEbookDetail() {
	let xhr = new XMLHttpRequest();
	xhr.open('GET', 'api/Api_ebook/getEbookDetail?ebook_id=' + ebookId, true);
	xhr.setRequestHeader('Content-Type', 'application/json');
	xhr.onreadystatechange = function () {
		if (xhr.readyState === 4) {
			if (xhr.status === 200) {
				let response = JSON.parse(xhr.responseText);
				let data = response.data;
				document.getElementsByName('publisher_id')[0].value = data.publisher.id;
				console.log(data);
			} else {
				console.error('Error fetching ebook details:', xhr.statusText);
			}
		}
	}
	xhr.send();
}	
getEbookDetail();

function getEbookPaket() {
	let xhr = new XMLHttpRequest();
	xhr.open('GET', 'api/Api_ebook/getEbookPaket?ebook_id=' + ebookId + '&subscribe_period=all', true);
	xhr.setRequestHeader('Content-Type', 'application/json');
	xhr.onreadystatechange = function () {
		if (xhr.readyState === 4) {
			if (xhr.status === 200) {
				let response = JSON.parse(xhr.responseText);
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
										<span class="badge-header d-flex justify-content-center align-items-center rounded-circle bg-primary-subtle mx-auto">
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
										<a onclick="checkout(${paket.id})" class="btn btn-primary w-100">Pilih Paket</a>
									</div>
								</div>
							</div>
						`;
					} else {
						cardAksesBulanan += `
							<div class="col-12 col-lg-4 px-4 pt-3">
								<div class="card border border-primary shadow h-100">
									<div class="card-body">
										<span class="badge-header d-flex justify-content-center align-items-center rounded-circle bg-primary-subtle mx-auto">
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
										<a onclick="checkout(${paket.id})" class="btn btn-primary w-100">Pilih Paket</a>
									</div>
								</div>
							</div>
						`;
					}
				});

				listAksesSelamanya.innerHTML = cardAksesSelamanya;
				listAksesBulanan.innerHTML = cardAksesBulanan;

			} else {
				console.error('Error fetching ebook packages:', xhr.statusText);
			}
		}
	}
	xhr.send();
}

getEbookPaket();

function listBenefit($data) {
	benefits = '';
	$data.forEach(function (item) {
		benefits += `<li class="list-item-positive">${item.benefit}</li>`;
	});
	return benefits;
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

function checkout(subscribe_id) {
	let publisherId = document.getElementsByName('publisher_id')[0].value;

	let tax = 0; // Ganti dengan nilai pajak yang sesuai
	let biayaAdmin = 0; // Ganti dengan nilai biaya admin yang sesuai
	let items = [{
		"item_id": parseInt(ebookId),
		"item_type": "ebook",
		"id_subscribe_type": subscribe_id,
		"id_publisher": parseInt(publisherId),
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
