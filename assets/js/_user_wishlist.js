document.addEventListener('DOMContentLoaded', function () {
	new Splide('#thumbnail-carousel-wishlists', {
		fixedWidth: 175,
		fixedHeight: 350,
		gap: 10,
		rewind: true,
		pagination: false,
	}).mount();
});
