let listVoucher = document.getElementById('list-voucher');

function getAllVoucher() {
	return new Promise((resolve, reject) => {

	let url = BASE_URL + 'api/api_voucher/getVoucherList';
	let xhr = new XMLHttpRequest();
	xhr.open('GET', url, true);
	xhr.onload = function () {
		if (this.status == 200) {
			let data = JSON.parse(this.responseText);
			let html = '';
			if (data.data.fetch.length > 0) {
				let i = 1;
				data.data.fetch.forEach(voucher => {
					html += `
					<div class="col-md-4 mt-4">
						<div class="card shadow border-0">
							<img src="${voucher.image_path}" class="voucher-img mx-auto mt-3" alt="Voucher Image">
							<div class="card-body">
							<p class="text-muted small mb-1">${moment(voucher.start_date).format('DD MMM YYYY')} - ${moment(voucher.end_date).format('DD MMM YYYY')}</p>
							<h6 class="card-title fw-bold">${voucher.name}</h6>
							
							<div class="input-group mb-3">
								<input type="text" class="form-control" placeholder="${voucher.code}" aria-label="code" aria-describedby="button-addon2" value="${voucher.code}">
								<button class="btn btn-primary salin-kode" type="button">Salin Kode</button>
							</div>

							<a class="text-decoration-none small fw-semibold" data-bs-toggle="collapse" href="#collapseExample${i}" role="button" aria-expanded="false" aria-controls="collapseExample">Syarat & Ketentuan</a>
							<div class="collapse mt-2" id="collapseExample${i}">
								<div class="card card-body">
									${voucher.description}
								</div>
							</div>
							</div>
						</div>
					</div>
					`;
					i++;
				});

				resolve(true);
			} else {
				html = '<p class="text-center">Tidak ada voucher tersedia.</p>';
			}
			listVoucher.innerHTML = html;
		}
	};
	xhr.send();
	});
}

getAllVoucher().then(() => {
	let copyButtons = document.querySelectorAll('.salin-kode');
	copyButtons.forEach(button => {
		button.addEventListener('click', function() {
			let codeInput = this.previousElementSibling;
			navigator.clipboard.writeText(codeInput.value).then(() => {
				this.textContent = 'Tersalin!';
				setTimeout(() => {
					this.textContent = 'Salin Kode';
				}, 2000);
			});
		});
	});
});


