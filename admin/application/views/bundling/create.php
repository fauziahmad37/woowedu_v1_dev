<style>
	.btn-clear.btn-pilih-ebook {
		/* background color same with input text */
		background-color: #fff;
		border: 1px solid #ced4da;
		color: #495057;
	}

	#list-card-ebook {
		max-height: 400px;
		overflow-y: auto;
	}

	.form-check-input {
		width: 20px;
		height: 20px;
	}

	/* hide arrow input number */
	/* Chrome, Safari, Edge, Opera */
	input::-webkit-outer-spin-button,
	input::-webkit-inner-spin-button {
		-webkit-appearance: none;
		margin: 0;
	}

	/* Firefox */
	input[type=number] {
		-moz-appearance: textfield;
	}

	/* input disable */
	input[type="text"]:disabled {
		background-color: blue;
	}

	/* style acordion */
	.accordion {
		border: 1px solid #f1f1f1;
		border-radius: 10px;
	}

	#book-category {
		border-radius: 5px;
	}
</style>

<section class="content mt-3">
	<h4>Tambah Paket Bundling</h4>
	<p>Buat paket bundling dari beberapa Ebook pilihan anda</p>

	<form id="form-bundling">
		<!-- card pengaturan paket bundling -->
		<div class="card">

			<div class="card-body">

				<h5 class="mb-3">Pengaturan Paket Bundling</h5>

				<!-- csrf token -->
				<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
				<input type="hidden" name="publisher_id" value="<?= $publisher_id ?>">
				<input type="hidden" id="bundling_id" name="bundling_id" value="<?= isset($bundling->id) ? $bundling->id : '' ?>">

				<!-- jumlah ebook option min 2 max 10 -->
				<div class="form-group">
					<label for="jumlah_ebook">Jumlah Ebook <span class="text-danger">*</span></label>
					<select class="form-control" id="jumlah_ebook" name="jumlah_ebook">
						<option value="2">2 Ebook</option>
						<option value="3">3 Ebook</option>
						<option value="4">4 Ebook</option>
						<option value="5">5 Ebook</option>
						<option value="6">6 Ebook</option>
						<option value="7">7 Ebook</option>
						<option value="8">8 Ebook</option>
						<option value="9">9 Ebook</option>
						<option value="10">10 Ebook</option>
					</select>
				</div>

				<!-- pilih ebook button clear and show modal list book -->
				<div class="form-group">
					<label for="ebook">Pilih Ebook <span class="text-danger">*</span></label>

					<!-- button modal full width -->
					<select class="form-control" data-toggle="modal" data-target="#modal-list-book" id="ebook" name="ebook">
						<option value="">Pilih Ebook</option>
					</select>
				</div>

				<!-- list ebook selected -->
				<div id="list-ebook-selected" class="d-none">
					<!-- table list ebook selected -->
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>Info Produk</th>
								<th>Jenis Paket</th>
								<th>Harga Normal</th>
								<th>Harga Paket</th>
								<th>Diskon %</th>
								<th>Stok</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							<!-- list ebook selected will be here -->
							<?php if (isset($ebook)) : ?>
								<?php foreach ($ebook as $val) : ?>
									<tr>
										<td>
											<input type="hidden" name="ebook_id[]" value="<?= $val['ebook_subscribe_id'] ?>">
											<div class="d-flex">
												<div class="flex-item">
													<img class="rounded" src="<?= $this->config->item('url_client') . 'assets/images/ebooks/cover/' . $val['cover_img'] ?>" alt="Cover Ebook" style="max-width: 100px; max-height: 100px;">
												</div>
												<div class="flex-item ml-3">
													<p><?= $val['title'] ?></p>
												</div>
											</div>
										</td>
										<td>
											<input readonly class="form-control" name="variant_name[]" value="<?= $val['subscribe_periode'] ?>">
										</td>
										<td>
											<input readonly type="number" class="form-control" name="harga_normal[]" value="<?= $val['normal_price'] ?>" placeholder="Harga Normal">
										</td>
										<td>
											<input type="number" class="form-control" name="harga_paket[]" placeholder="Harga Paket" value="<?= $val['discount_price'] ?>">
										</td>
										<td>
											<input type="number" class="form-control" name="diskon[]" placeholder="Diskon" value="<?= (($val['normal_price'] - $val['discount_price']) / $val['normal_price'] * 100) ?>">
										</td>
										<td>
											<input type="number" class="form-control" name="stok[]" value="<?= $val['stock'] ?>" placeholder="Stok">
										</td>
										<td>
											<button type="button" class="btn btn-danger btn-sm btn-hapus-ebook" onclick="deleteEbook(this)"><i class="fa fa-trash"></i></button>
										</td>
									</tr>
								<?php endforeach ?>
							<?php endif ?>
						</tbody>
					</table>

					<!-- total harga paket & total diskon -->
					<div class="total-harga d-flex">
						<div class="">
							<p>Total Harga Paket</p>
							<h5 id="total-harga-paket">
								<?= isset($total_harga_paket) ? 'Rp ' . number_format($total_harga_paket) : 'Rp 0' ?>
							</h5>
						</div>

						<div class="ml-3">
							<p>Total Diskon</p>
							<h5 id="total-diskon">
								<?= isset($total_diskon) ? 'Rp ' . number_format($total_diskon) : 'Rp 0' ?>
							</h5>
						</div>
					</div>

				</div>



			</div>

		</div>

		<!-- card informasi paket bundling -->
		<div class="card mt-3">
			<div class="card-body">
				<h5 class="mb-3">Informasi Paket Bundling</h5>

				<!-- waktu start dan waktu end -->
				<div class="form-row">
					<div class="form-group col-md-6">
						<label for="waktu_start">Waktu Mulai <span class="text-danger">*</span></label>
						<input type="date" class="form-control" id="waktu_start" name="waktu_start" value="<?= isset($bundling->start_date) ? date('Y-m-d', strtotime($bundling->start_date)) : '' ?>">
					</div>
					<div class="form-group col-md-6">
						<label for="waktu_end">Waktu Berakhir <span class="text-danger">*</span></label>
						<input type="date" class="form-control" id="waktu_end" name="waktu_end" value="<?= isset($bundling->end_date) ? date('Y-m-d', strtotime($bundling->end_date)) : '' ?>">
					</div>
				</div>

				<!-- nama paket -->
				<div class="form-group">
					<label for="nama_paket">Nama Paket <span class="text-danger">*</span></label>
					<input type="text" class="form-control" id="nama_paket" name="nama_paket" placeholder="Nama Paket" value="<?= isset($bundling->package_name) ? $bundling->package_name : '' ?>">
				</div>

				<!-- deskripsi paket -->
				<div class="form-group">
					<label for="deskripsi_paket">Deskripsi Paket <span class="text-danger">*</span></label>
					<textarea class="form-control" id="deskripsi_paket" name="deskripsi_paket" rows="3" placeholder="Deskripsi Paket"><?= isset($bundling->description) ? $bundling->description : '' ?></textarea>
				</div>

				<!-- foto sampul -->
				<div class="form-group">
					<label for="foto_sampul">Foto Sampul Paket Bundling<span class="text-danger">*</span></label>
					<!-- old foto sampul -->
					<input type="hidden" name="old_foto_sampul" value="<?= isset($bundling->package_image) ? $bundling->package_image : '' ?>">

					<input type="file" class="form-control-file d-none" id="foto_sampul" name="foto_sampul" accept="image/*">
					<!-- foto sampul preview -->
					<img id="foto_sampul_preview" class="mt-3 d-block" style="max-width: 100px; max-height: 100px;" src="<?= (isset($bundling->package_image)) ? $this->config->item('url_client') . 'assets/images/bundlings/' . $bundling->package_image :  base_url('assets/images/foto-sampul-place.png') ?>" alt="Foto Sampul">

				</div>

				<!-- kuota paket bundling -->
				<div class="form-group">
					<label for="kuota">kuota Paket Bundling <span class="text-danger">*</span></label>
					<input type="number" class="form-control" id="kuota" name="kuota" placeholder="Kuota Paket Bundling" value="<?= isset($bundling->stock) ? $bundling->stock : '' ?>">
				</div>

				<!-- button batal & simpan -->
				<div class="form-group" style="text-align: end;">
					<a href="<?= base_url('bundling') ?>" class="btn btn-secondary">Batal</a>
					<button type="submit" class="btn btn-primary">Simpan</button>
				</div>

			</div>
		</div>

	</form>


</section>

<!-- modal list book -->
<div class="modal fade" id="modal-list-book" tabindex="-1" role="dialog" aria-labelledby="modal-list-book" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h5 class="modal-title">Pilih Ebook</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<!-- alert -->
				<div class="alert alert-info" role="alert" style="border-radius: 12px;">
					<p class="mb-0"><img src="<?= base_url() . 'assets/images/icons/information-fill.svg' ?>" alt=""> Harap pilih Ebook dengan jumlah yang sesuai dengan pengaturan anda sebelumnya</p>
				</div>

				<!-- search group -->
				<div class="d-flex mt-4">
					<div class="flex-item">
						<div class="form-group">
							<!-- input placeholder with icon left -->
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text" id="basic-addon1"><img src="<?= base_url() . 'assets/images/icons/search.svg' ?>" alt=""></span>
								</div>
								<input type="text" class="form-control" id="search" placeholder="Cari Ebook">
							</div>
						</div>
					</div>

					<div class="form-group form-group-category ml-2">
						<input type="hidden" class="form-control" id="book-category-id" name="book-category-id" />
						<input type="text" class="form-control" id="book-category" name="book-category" placeholder="-- Pilih Kategori --" />
					</div>

				</div>

				<!-- list ebook -->
				<!-- list ebook header -->
				<div class="row mt-4">
					<div class="col-6">
						<h6 class="fw-bold">Pilihan Ebook</h6>
					</div>
					<div class="col-6 text-right">(100) Ebook</div>
				</div>

				<!-- list ebook body container -->
				<div id="list-card-ebook"></div>

			</div>
		</div>
	</div>
</div>

<script>
	// get list ebook
	function getListEbook(pTitle, pCategoryIds) {
		$.ajax({
			url: '<?= base_url('bundling/get_list_ebook') ?>',
			type: 'GET',
			data: {
				'search': pTitle,
				'kategori': pCategoryIds
			},
			dataType: 'json',
			success: function(data) {
				// console.log(data);

				// list card ebook
				let list_card_ebook = '';
				$.each(data.data, function(index, value) {
					list_card_ebook += `
						<div class="col-12 mb-3">
							<div class="card list-card-ebook">
								<div class="card-body">

									<div class="d-flex">
										<div class="form-check d-flex pr-2">
											<input class="form-check-input" type="checkbox" value="${value.id}" id="ebook-${value.id}">
											<label class="form-check-label" for="ebook-${value.id}"></label>
											<input type="hidden" name="ebook_id[]" value="${value.id}">
										</div>
										<div class="flex-item">
											<img class="rounded" src="<?= $this->config->item('url_client') . '/assets/images/ebooks/cover/'; ?>${value.cover_img}" alt="Cover Ebook" style="max-width: 100px; max-height: 100px;">
										</div>
										<div class="flex-item ml-3">
											<div class="row">
												<div class="col-8">
													<p>${value.publisher_name}</p>
													<h6 class="fw-bold ebook-title">${value.title}</h6>
												</div>
												<div class="col-4 text-end">
													
													<div class="d-flex" style="float: inline-end;">
														<div class="flex-item mr-3">
															<p class="text-muted">Stok</p>
															<h6 class="fw-bold ebook-qty">${value.qty}</h6>
														</div>

														<div class="flex-item">
															<p class="text-muted">Harga</p>
															<h6 class="fw-bold ebook-price">Rp ${value.price}</h6>
														</div>
														
													</div>
												</div>

											</div>

										</div>



										
									</div>
										
									<div id="accordion-${index}" role="tablist" class="accordion mx-4 mt-3">
										<div class="card">
											<a data-toggle="collapse" aria-expanded="false" aria-controls="accordion-${index} .item-2" href="#accordion-${index} .item-2">
												<div class="card-header" role="tab">
													<h6 class="mb-0 text-dark d-inline">Pilihan Paket Langganan</h6>
													<span class="float-right">+</span>
												</div>
											</a>
											<div class="collapse item-2" role="tabpanel" data-parent="#accordion-${index}">
												<div class="card-body">
													
													${loopVariant(value.variant)}

												</div>
											</div>
										</div>
									</div>

								
								</div>
							</div>
						</div>

						<hr>
					`;
				});

				$('#list-card-ebook').html(list_card_ebook);

			}
		});
	}

	// get list ebook ajax
	$(document).ready(function() {

		// Kategori
		// ajax get all categories
		$.ajax({
			url: BASE_URL + 'kategori/get_all',
			type: 'GET',
			async: true,
			dataType: 'json',
			success: function(data) {
				// console.log(data);

				if (data.length !== 0) {



					// set tree
					const setTree = (data, id = null) => {
						return data.filter(items => +items.parent_category === +id)
							.map(x =>
								({
									'id': x.id,
									'title': x.category_name,
									'subs': setTree(data, +x.id)
								}));
					}

					const categoryTree = setTree(data);

					const category = $('#book-category').comboTree({
						source: categoryTree,
						isMultiple: true
					});

					category.onChange(() => {
						document.querySelector('#book-category-id').value = category.getSelectedIds().join(',');

						// search book
						let title = $('#search').val();
						let categoryIds = $('#book-category-id').val();
						getListEbook(title, categoryIds);
					});
				}

			}
		});

		// run get list ebook
		getListEbook('', '');

		// search ebook
		$('#search').keyup(function() {
			let title = $(this).val();
			let categoryIds = $('#book-category-id').val();
			getListEbook(title, categoryIds);
		});

	});


	//  image preview
	$('#foto_sampul').change(function() {

		let file = $(this)[0].files[0];
		let reader = new FileReader();
		reader.onload = function(e) {
			$('#foto_sampul_preview').attr('src', e.target.result);
		}
		reader.readAsDataURL(file);
	});

	// if foto sampul click show file input
	$('#foto_sampul_preview').click(function() {
		$('#foto_sampul').click();
	});


	// function loop variant
	function loopVariant(variant) {
		let variant_html = '';
		$.each(variant, function(index, value) {
			variant_html += `
				<div class="form-check-group d-flex pr-2 ml-2">
					<input class="form-check-input" type="checkbox" value="${value.id}" id="ebook-${value.id}">
					<label class="form-check
						label" for="ebook-${value.id}">${value.subscribe_periode}</label>
					<input type="hidden" name="variant_id[]" value="${value.id}">
					<input type="hidden" name="variant_name[]" value="${value.subscribe_periode}">
					<input type="hidden" name="harga_varian[]" value="${value.price}">
					<input type="hidden" name="stok[]" value="${value.stock}">
				</div>
			`;
		});

		return variant_html;
	}

	// remove d-none when bundling id is exist
	if ($('#bundling_id').val()) {
		$('#list-ebook-selected').removeClass('d-none');
	}

	// fill list ebook selected
	$(document).on('change', '.form-check-input', function() {
		// list-ebook-selected remove d-none
		$('#list-ebook-selected').removeClass('d-none');

		let ebook_id = $(this).val();
		let ebook_title = $(this).closest('.list-card-ebook').find('.ebook-title').text();
		let image = $(this).closest('.list-card-ebook').find('img').attr('src');
		let harga_normal = $(this).closest('.form-check-group').find('input[name="harga_varian[]"]').val();
		let stok = $(this).closest('.list-card-ebook').find('.ebook-qty').text();
		let variant_name = $(this).closest('.list-card-ebook').find('.form-check-input:checked').next().text();

		// check if checkbox is checked
		if ($(this).is(':checked')) {
			// check if list ebook selected is more than jumlah ebook
			if ($('#list-ebook-selected tbody tr').length >= $('#jumlah_ebook').val()) {
				alert('Jumlah Ebook yang dipilih melebihi jumlah Ebook yang diatur');
				$(this).prop('checked', false);
				return;
			}

			// append list ebook selected
			$('#list-ebook-selected tbody').append(`
				<tr>
					<td>
						<input type="hidden" name="ebook_id[]" value="${ebook_id}">

						<div class="d-flex">
							<div class="flex-item">
								<img class="rounded" src="${image}" alt="Cover Ebook" style="max-width: 100px; max-height: 100px;">
							</div>
							<div class="flex-item ml-3">
								<p>${ebook_title}</p>
							</div>
						</div>
					</td>
					<td>
						<input readonly class="form-control" name="variant_name[]" value="${variant_name}">
					</td>
					<td>
						<input readonly type="number" class="form-control" name="harga_normal[]" value="${harga_normal.replace('Rp ', '')}" placeholder="Harga Normal">
					</td>
					<td>
						<input type="number" class="form-control" name="harga_paket[]" placeholder="Harga Paket" value="0">
					</td>
					<td>
						<input type="text" class="form-control" name="diskon[]" placeholder="Diskon" value="0">
					</td>
					<td>
						<input type="number" class="form-control" name="stok[]" value="${stok}" placeholder="Stok">
					</td>
					<td>
						<button type="button" class="btn btn-danger btn-sm btn-hapus-ebook" onclick="deleteEbook(this)"><i class="fa fa-trash"></i></button>
					</td>
				</tr>
			`);


		} else {
			// remove list ebook selected
			$('#list-ebook-selected tbody tr').each(function() {
				if ($(this).find('input[name="ebook_id[]"]').val() == ebook_id) {
					$(this).remove();
				}
			});
		}
	});

	// harga paket keyup & calculate diskon
	$(document).on('keyup', 'input[name="harga_paket[]"]', function() {
		// calculate diskon
		let harga_normal = $(this).closest('tr').find('input[name="harga_normal[]"]').val();
		let harga_paket = $(this).val();
		let selisih = harga_normal - harga_paket;
		let diskon = (selisih / harga_normal) * 100;

		$(this).closest('tr').find('input[name="diskon[]"]').val(diskon);

		// calculate total harga paket
		let total_harga_normal = 0;
		let total_harga_paket = 0;
		$('#list-ebook-selected tbody tr').each(function() {
			total_harga_normal += parseInt($(this).find('input[name="harga_normal[]"]').val());
			total_harga_paket += parseInt($(this).find('input[name="harga_paket[]"]').val());
		});

		let total_diskon = total_harga_normal - total_harga_paket;
		$('#total-diskon').text('Rp ' + total_diskon);

		$('#total-harga-paket').text('Rp ' + total_harga_paket);
	});

	// diskon keyup & calculate harga paket
	$(document).on('keyup', 'input[name="diskon[]"]', function() {

		console.log(this);
		// calculate harga paket
		let harga_normal = $(this).closest('tr').find('input[name="harga_normal[]"]').val();
		let diskon = $(this).val();
		let harga_paket = harga_normal - (harga_normal * diskon / 100);

		$(this).closest('tr').find('input[name="harga_paket[]"]').val(harga_paket);

		// calculate total harga paket
		let total_harga_normal = 0;
		let total_harga_paket = 0;
		$('#list-ebook-selected tbody tr').each(function() {
			total_harga_normal += parseInt($(this).find('input[name="harga_normal[]"]').val());
			total_harga_paket += parseInt($(this).find('input[name="harga_paket[]"]').val());
		});

		let total_diskon = total_harga_normal - total_harga_paket;
		$('#total-diskon').text('Rp ' + total_diskon);

		$('#total-harga-paket').text('Rp ' + total_harga_paket);
	});

	// delete ebook from list ebook selected
	function deleteEbook(e) {
		$(e).closest('tr').remove();

		// calculate total harga paket
		let total_harga_paket = 0;
		$('#list-ebook-selected tbody tr').each(function() {
			total_harga_paket += parseInt($(this).find('input[name="harga_paket[]"]').val());
		});

		$('#total-harga-paket').text('Rp ' + total_harga_paket);

		// calculate total diskon
		let total_diskon = 0;
		$('#list-ebook-selected tbody tr').each(function() {
			total_diskon += parseInt($(this).find('input[name="diskon[]"]').val());
		});

		$('#total-diskon').text(total_diskon + '%');
	}

	// form bundling submit
	$('#form-bundling').submit(function(e) {
		e.preventDefault();

		// check if jumlah ebook selected is not same with jumlah ebook
		if ($('#list-ebook-selected tbody tr').length != $('#jumlah_ebook').val()) {
			alert('Jumlah Ebook yang dipilih tidak sesuai dengan jumlah Ebook yang diatur');
			return;
		}

		// check if harga paket is not same with harga normal
		let is_same = true;
		$('#list-ebook-selected tbody tr').each(function() {
			if ($(this).find('input[name="harga_normal[]"]').val() == $(this).find('input[name="harga_paket[]"]').val()) {
				is_same = false;
			}
		});

		if (!is_same) {
			alert('Harga Paket tidak boleh sama dengan Harga Normal');
			return;
		}

		// check if waktu start is not same with waktu end
		if ($('#waktu_start').val() >= $('#waktu_end').val()) {
			alert('Waktu Mulai tidak boleh lebih besar atau sama dengan Waktu Berakhir');
			return;
		}

		// check if nama paket is not empty
		if ($('#nama_paket').val() == '') {
			alert('Nama Paket tidak boleh kosong');
			return;
		}

		// check if deskripsi paket is not empty
		if ($('#deskripsi_paket').val() == '') {
			alert('Deskripsi Paket tidak boleh kosong');
			return;
		}

		// check if foto sampul is not empty
		// jika bundling id kosong maka input foto sampul tidak boleh kosong
		if ($('#bundling_id').val() == '') {
			if ($('#foto_sampul').val() == '') {
				alert('Foto Sampul Paket Bundling tidak boleh kosong');
				return;
			}
		}

		// check if kuota is not empty
		if ($('#kuota').val() == '') {
			alert('Kuota Paket Bundling tidak boleh kosong');
			return;
		}


		// submit form with ajax
		// url edit or store
		let url = '<?= base_url('bundling/store') ?>';
		// if bundling id is exist then edit
		if ($('#bundling_id').val()) {
			url = '<?= base_url('bundling/update') ?>';
		}
		$.ajax({
			url: url,
			type: 'POST',
			data: new FormData(this),
			dataType: 'json',
			contentType: false,
			cache: false,
			processData: false,
			success: function(data) {
				if (data.status == 'success') {
					// sweet alert
					Swal.fire({
						title: 'Berhasil',
						text: data.message,
						icon: 'success',
						showCancelButton: false,
						confirmButtonColor: '#3085d6',
						confirmButtonText: 'OK'
					});
					window.location.href = '<?= base_url('bundling') ?>';
				} else {
					// sweet alert
					Swal.fire({
						title: 'Gagal',
						text: data.message,
						icon: 'error',
						showCancelButton: false,
						confirmButtonColor: '#3085d6',
						confirmButtonText: 'OK'
					});
				}
			}
		});
	});
</script>
