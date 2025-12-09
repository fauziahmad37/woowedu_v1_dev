'use strict';

let ebookId = document.getElementById('ebook_id').value;
let btnBeriUlasan = document.getElementById('beri-ulasan');

// create read more
const descContent = document.querySelector('.desc-content'),
	openContent = document.getElementById('open-content'),
	closeContent = document.getElementById("close-content"),
	csrfName = document.querySelector("meta[name=\"csrf_name\"]"),
	csrfToken = document.querySelector("meta[name=\"csrf_token\"]");

const splitDescContent = descContent.textContent.trim().split(" ");
if (splitDescContent.length > 50) {
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

Vue.component('image-item', {
	// Komponen image-item sekarang bisa menerima
	// "prop", yang mana ini adalah atribut kustom.
	// Prop ini kita namakan image.
	props: {
		image: { type: Object, required: true },
		index: { type: Number, required: true }
	},
	computed: {
		coverUrl() {
			if (this.index == 1) {
				return this.image.cover_img ? this.image.cover_img : 'assets/images/images-placeholder.png';
			}

			const val = this.image['cover_' + this.index];
			return val ? val : 'assets/images/images-placeholder.png';
		}
	},
	// template: '<li>{{ image.text }}</li>',
	template: `<label :for="'thumbnail-select-' + index" class="lbl-thumbnail-select overflow-hidden d-inline-block rounded shadow" @click="$emit('select', coverUrl)">
						<img class="thumbnail-select" :id="'thumbnail-select-' + index + '-img'" :src="coverUrl" />
						<input type="radio" class="d-none position-absolute input-thumbnail-select" :id="'thumbnail-select-' + index" />
					</label>`
})

// VUE JS
let mainSection = new Vue({
	el: '#section_2',
	data: {
		ebook: {},
		currentCover: ''
	},
	mounted() {
		this.getEbookDetail();
	},
	methods: {
		getEbookDetail() {
			const xhr = new XMLHttpRequest();
			xhr.open('GET', 'api/api_my_ebook/getDetailMyEbook?ebook_id=' + ebookId, true);
			xhr.onload = () => {
				if (xhr.status === 200) {
					let response = JSON.parse(xhr.responseText);
					if (response.status === true) {
						this.ebook = response.data;
						this.ebook.cover_img = (response.data.cover_img.includes('http')) ? response.data.cover_img : 'assets/images/ebooks/cover/' + response.data.cover_img;

						// set currentCover dari nilai awal ebook.cover_img
						this.currentCover = this.ebook.cover_img;

						// jika reading progressnya sudah 50% maka tampilkan tombol beri ulasan
						if (this.ebook.reading_progress >= 50) {
							btnBeriUlasan.classList.remove("d-none");
						}
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
					} else {
						Swal.fire({
							icon: 'error',
							title: 'Gagal Mengirim Ulasan',
							text: response.message,
						});
					}

					// update token
					console.log(response.csrf_token)
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
