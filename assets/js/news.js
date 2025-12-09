var arrPage = [];
	$(document).ready(function () {
		load_data(1,10);
		// ########################## PAGINATION JS ##############################
		$('#demo').pagination({
			// dataSource: [1, 2, 3, 4, 5, 6, 7, 8,9,10,11,12,13,14,15,16,17,18,19,20],
			dataSource: arrPage,
			className: 'paginationjs-theme-blue paginationjs-big',
			callback: function(data, pagination) {
				// template method of yourself
				// var html = template(data);
				// dataContainer.html(html);
				load_data(pagination.pageNumber);
			}
		})
	});

	// KETIKA BUTTON CARI DI KLIK
	$('#search').on('click', function(e){
		load_data();
	});

	$('#judul').keyup(function(event) {
		if (event.keyCode === 13) $("#search").click();
	});

	// create function load data
	function load_data(page = 1, limit = 10){
		let title = $('#judul').val();
		// let startDate = $('#start-date').val();
		// let endDate = $('#end-date').val();

		$.ajax({
			type: "GET",
			url: BASE_URL+"news/history",
			async: false,
			data: {
				page: page,
				limit: limit,
				title: title,
				// startDate: startDate,
				// endDate: endDate
			},
			success: function (response) {
				$('#news-content').html('');
				$.each(response.news, function (key, value){
					
					let desc = value.isi;

					if(desc.length > 100) desc = desc.substring(0, 100) + ' ...'

					$('#news-content').append(`
						<div class="border rounded-5 border-primary border-opacity-50 mb-3 me-4 p-4" style="width: 320px; margin-top: 40px;">
							<img width="100%" height="175" class="rounded-4 mb-3" src="${(value.image) ? BASE_URL+'assets/images/news/'+value.image : BASE_URL+'assets/images/news/pengumuman.png'}" />
							<div class="card-body">
								<a class="text-decoration-none" href="news/detail/${value.id}"><h5 class="mb-2 fw-bold">${value.judul}</h5></a>
								<p style="font-size: 12px;">${desc}</p>
							</div>
							<div class="card-footer border-top-0 bg-light">
								<a class="text-decoration-none text-primary fw-bold" href="${BASE_URL+'news/detail/'+value.id}">Baca selengkapnya</a>
								<br>
								<br>
								${buttonGroup(value.user_id, $('input[name="user_level"]').val(), value.id)}
							</div>
						</div>
					`);
				});

				// ########################## PAGINATION JS ##############################
				for(let i=1; i<=response.total_records; i++){
					arrPage.push(i);
				}
			}
		});
	}

	// BUTTON GROUP EDIT & DELETE
	function buttonGroup(user_id, user_level, id){
		let buttonGroup = `<a href="${BASE_URL+'news/create/'+id}" class="btn btn-clear border d-inline me-1 rounded-5"><i class="bi bi-pencil-square"></i></a>
							<a class="btn btn-clear border d-inline rounded-5" onclick="deleteNews(${id})"><i class="bi bi-trash3-fill"></i></a>`;
		if(user_id == $('input[name="user_id"]').val() || user_level == 6){
			return buttonGroup;
		}
		return '';
	}

	// ############################## DELETE ##############################

	function deleteNews(id){
		Swal.fire({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			// icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
			if (result.value) {
				$.ajax({
					type: "POST",
					url: BASE_URL+"news/delete",
					data: {
						id: id,
						csrf_token_name: $('input[name="csrf_token_name"]').val()
					},
					dataType: "JSON",
					success: function (response) {
						if(response.success == true){
							Swal.fire('Deleted!', response.message, 'success');
							setTimeout(()=>{
								window.location.href = BASE_URL+'news';
							}, 1500);
						}
					}
				});
			}
		})
	}
