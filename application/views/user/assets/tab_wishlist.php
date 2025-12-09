<style>
	.splide__slide img {
		width: 100%;
		height: 100%;
		object-fit: cover;
	}

	.book-title-card {
		line-height: 18px;
		display: -webkit-box;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
		overflow: hidden;
	}

	.book-publiher-card {
		color: #74788D;
		text-overflow: ellipsis;
		overflow: hidden;
		white-space: nowrap;
	}
</style>

<div class="row mt-5">
	<div class="col-6">
		<h5 class="fw-bold">Wishlist</h5>
	</div>
	<div class="col-6 text-end">
		<span class="wishlist-count">
	</div>
</div>


<section id="thumbnail-carousel-wishlists" class="splide" aria-label="The carousel with thumbnails. Selecting a thumbnail will change the Beautiful Gallery carousel.">
	<div class="splide__track">
		<ul class="splide__list" id="list-wishlist">
			<?php foreach ($wishlists as $val) : ?>
				<li class="splide__slide">
					<a href="<?=($val['item_type'] == 'ebook') ? 'Ebook/detail/'.$val['id'] : 'BundlingPackage/detail/'.$val['id'] ?> " class="text-decoration-none">
						<div class="card rounded-3 border-light-subtle p-3 m-1">
							<?php
							// buat image menjadi thumbnail jika gambar ebook
							if(($val['item_type'] == 'ebook')){
								$img = explode('.', $val['cover_img']);
								$img[0] = $img[0] . '_thumb';
								$img = implode('.', $img);
							}
							?>
							<img class="rounded-3" src="<?=($val['item_type'] == 'bundling') ? 'assets/images/bundlings/'.$val['cover_img'] : 'assets/images/ebooks/cover/'.$img?>" alt="" width="128" height="172">
							<p class="fs-12 lh-1 mt-3 mb-2 book-publiher-card">Penerbit: <?= $val['publisher_name'] ?></p>
							<p class="fs-14 mb-0 book-title-card"><?= $val['title'] ?></p>
						</div>
					</a>
				</li>
			<?php endforeach ?>
		</ul>
	</div>
</section>
