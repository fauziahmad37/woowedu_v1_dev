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

	// create function load data
	function load_data(page = 1, limit = 10){
		 
		let startDate = $('#start-date').val();
		let endDate = $('#end-date').val();
		let id = $('#student_id').val();

		$.ajax({
			type: "GET",
			url: BASE_URL+"student/history",
			async: false,
			data: {
				id: id,
				page: page,
				limit: limit, 
				startDate: startDate,
				endDate: endDate
			},
			success: function (response) {
				$('#news-content').html('');
				$.each(response.news, function (key, value){
					let desc = '';  

					$('#news-content').append(`
						<div class="container border rounded-4 bg-clear p-3 mb-3 news-item">
							<div class="d-flex justify-content-between">
								<h6 class="mb-2">${value.tgl_submit}</h6> 
								
							</div>  
							<p style="font-size: 14px;">${value.type}</p>
							<div class="container d-flex justify-content-end"> </div>
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

 
 