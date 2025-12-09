<div class="row mt-4 mb-3">
	<div class="col">
		<h5 class="fw-bold">Riwayat Pembelian</h5>
	</div>
	<div class="col text-end">
		<h6>(6 Ebook)</h6>
	</div>
</div>

<div class="list-card">
	<?php foreach ($transactionPayments as $key) : ?>

		<div class="card p-3 mt-4 rounded-4 border-light-subtle border-2">

			<input type="hidden" name="transaction_number" value="<?= $key['transaction_number'] ?>">

			<?php if ($key['status'] == 'pending') : ?>
				<span class="badge bg-warning-subtle text-warning p-2" style="width: fit-content;">Menunggu Pembayaran</span>
			<?php elseif ($key['status'] == 'settlement') : ?>
				<span class="badge bg-success-subtle text-success p-2" style="width: fit-content;">Berhasil</span>
			<?php elseif ($key['status'] == 'expire') : ?>
				<span class="badge bg-danger-subtle text-danger p-2" style="width: fit-content;">Kadaluarsa</span>
			<?php else : ?>
				<span class="badge bg-danger-subtle text-danger p-2" style="width: fit-content;">Gagal</span>
			<?php endif ?>


			<div class="row mt-4 mb-3">
				<div class="col-8 d-flex">
					<img class="" src="assets/images/bundlings/<?= $key['package_image'] ?>" alt="ebook-cover" width="80" height="120" style="border-radius: 12px;">
					<div class="flex-row ms-3">
						<p class=""><?= $key['publisher_name'] ?></p>
						<h5 class=""><?= $key['package_name'] ?></h5>
					</div>
				</div>
				<div class="col-4">
					<p class="text-end">Jumlah Bayar</p>
					<p class="text-end fw-bold">Rp <?= str_replace(',', '.', number_format($key['total_payment'])) ?></p>
				</div>
			</div>

			<div class="border border-light-subtle border-1"></div>

			<div class="row">
				<div class="col">
					<?php if ($key['status'] == 'pending' || $key['status'] == 'expire') : ?>
						<p class="mt-4" style="font-size: 12px;">Batas waktu pembayaran, <span class="text-danger fw-bold"> <?= date('d M, H:i', strtotime($key['expiry_time'])) ?> </span> </p>
					<?php endif ?>

				</div>
				<div class="col text-end pt-3">

					<?php if ($key['status'] == 'settlement') : ?>
						<!-- jika paket bundle arahkan ke sini -->
						<?php if ($key['field_table'] == 'bundle') : ?>
							<a href="bundlingpackage/checkout/<?= $key['bundling_package_id'] ?>" class="btn btn-primary">Beli Lagi</a>
						<?php endif ?>
					<?php elseif ($key['status'] == 'pending') : ?>
						<a href="<?= $key['payment_link'] ?>" class="btn btn-primary">Lanjutkan Pembayaran</a>
					<?php elseif ($key['status'] == 'expire') : ?>
						<button class="btn bg-dark-subtle border-light-subtle">Kadaluarsa</button>
					<?php endif ?>

					<div class="dropdown d-inline-block">
						<button class="btn btn-clear border-primary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
							<img src="<?= base_url('assets/images/icons/three-dots-icon.svg') ?>" alt="">
						</button>
						<ul class="dropdown-menu">
							<li><a class="dropdown-item" href="<?=base_url('checkout/statusPembayaran?transaction_number='.$key['transaction_number'])?>">Lihat Status</a></li>
							<li><a class="dropdown-item" href="#">Another action</a></li>
							<li><a class="dropdown-item" href="#">Something else here</a></li>
						</ul>
					</div>
				</div>
			</div>

		</div>
	<?php endforeach ?>
</div>
