let teacherId = document.getElementById('teacher_id').value;
let classId = document.getElementById('class_id').value;

/**
 * Fetch the ebook list for a specific teacher.
 * @return {void}
 */
function getEbookListGuru() {
	let url;
	if(teacherId){
		url = "Ebook/getEbookListGuru?teacher_id=" + teacherId;
	} else {
		url = "api/Api_ebook/ebookListRekomendasiGuru?class_id=" + classId;
	}

	let xhr = new XMLHttpRequest();
	xhr.open("GET", url, true);
	xhr.onload = function () {
		if (this.status == 200) {
			let response = JSON.parse(this.responseText);
			let data = response.data || [];
			// Process the response
			let cardEbookTeachers = '';
			data.forEach(item => {
				cardEbookTeachers += `
					<div class="col-sm-12 col-md-6 col-lg-4 col-xl-4 mb-3">
						<a class="text-decoration-none" href="Ebook/detail_ebook_list_guru/${item.id}">
							<div class="card-item border-primary rounded-4 bg-primary-subtle border p-3 text-primary" style="" data-id="${item.id}">
								<p class="fw-bold" style="font-size: 18px;">${item.title}</p>
								<p>${item.description}</p>
								<div class="d-flex">
									<div class="me-3">
										<img class="me-2" src="assets/images/icons/book-marked-fill.png" alt="icon ebook" width="14"><span>${item.ebooks.length}</span> Ebook
									</div>
									<div>
										${(teacherId) ? `<img class="me-2" src="assets/images/icons/class-scene-svgrepo-com 1.png" alt="icon class" width="14">Kelas <span>${item.class_levels.join(', ')}</span>` : ''}
										${(classId) ? `<img class="me-2" src="assets/images/icons/class-scene-svgrepo-com 1.png" alt="icon class" width="14">Guru <span>${item.teacher_name}</span>` : ''}
									</div>
								</div>
							</div>
						</a>
					</div>
				`;
			});

			document.getElementById('container_grid_ebook_list_guru').innerHTML = cardEbookTeachers;
		}
	}
	xhr.send();
}

getEbookListGuru();

new Sortable(container_grid_ebook_list_guru, {
	swapThreshold: 1,
	animation: 150,
	onEnd: function (evt) {
		console.log('Banner order changed:', evt);
		// Here you can add code to save the new order if needed
		let newOrder = [];

		$('#container_grid_ebook_list_guru .card-item').each(function (index) {
			let bannerId = $(this).data('id'); // Assuming each li has a data-id attribute
			newOrder.push(bannerId);
		});
		console.log('New order:', newOrder);

		// update the order in the database
		updateOrder(newOrder);
	}
});

/**
 * Update the order of ebooks for a specific teacher.
 * @param {*} order 
 */
function updateOrder(order) {
	let form = new FormData();
	form.append('order', JSON.stringify(order));
	form.append('csrf_token_name', $('meta[name="csrf_token"]').attr('content'));

	$.ajax({
		type: "POST",
		url: BASE_URL + "ebook/updateOrderEbookGuru",
		data: form,
		processData: false,
		contentType: false,
		dataType: "JSON",
		success: function (res) {
			if (res.success) {
				swal.fire({
					icon: 'success',
					type: 'success',
					title: 'Success',
					text: res.message
				});

				// update token
				$('meta[name="csrf_token"]').attr('content', res.token);
			} else {
				swal.fire({
					icon: 'error',
					type: 'error',
					title: 'Error',
					text: res.message
				});

				// update token
				$('meta[name="csrf_token"]').attr('content', res.token);
			}
		},
		error: function (xhr, status, error) {
			console.error('Error updating order:', error);
		}
	});
}
