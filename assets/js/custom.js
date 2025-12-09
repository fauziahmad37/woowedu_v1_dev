
  (function ($) {
  
  "use strict";

    // MENU
    $('.navbar-collapse a').on('click',function(){
      $(".navbar-collapse").collapse('hide');
    });

	// $('.icon-menu').on('click', ()=>{
	// 	$('.l-navbar').addClass('d-block');
	// 	$('.l-navbar').removeClass('d-none');

	// 	$('.l-navbar .nav').addClass('d-block');
	// 	$('.l-navbar .nav').removeClass('d-none');
	// });

	// $('.close-menu').on('click', ()=>{
	// 	console.log('tes')
	// 	$('.l-navbar').removeClass('d-block');
	// 	$('.l-navbar').addClass('d-none');

	// 	$('.l-navbar .nav').removeClass('d-block');
	// 	$('.l-navbar .nav').addClass('d-none');
	// });
	
	// if(window.matchMedia("(max-width: 576px)")){
	// 	document.querySelectorAll('.profile-desktop-section')[0].classList.add("d-none");
	// }
	
    
    // CUSTOM LINK
    $('.smoothscroll').click(function(){
      var el = $(this).attr('href');
      var elWrapped = $(el);
      var header_height = $('.navbar').height();
  
      scrollToDiv(elWrapped,header_height);
      return false;
  
      function scrollToDiv(element,navheight){
        var offset = element.offset();
        var offsetTop = offset.top;
        var totalScroll = offsetTop-navheight;

      }
    });


	// KETIKA NOTIF ICON DI KLIK
	$('.notif-group').on('click', function(){
		let notif = $('.notif-content-container');
		if(notif.hasClass('d-block')){
			notif.addClass('d-none');
			notif.removeClass('d-block');
		}else{
			notif.addClass('d-block');
			notif.removeClass('d-none')
		}

		// JALANKAN AJAX UNTUK AMBIL DATA NOTIF
		$.ajax({
			type: "GET",
			url: BASE_URL+"notif/notif_data",
			dataType: "JSON",
			success: function (response) {
				if(response.success == true){
					$('.notif-content').html('');
					$.each(response.data, function (i, val) {
						const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "Nopember", "Desember"];
						let date = new Date(val.created_at);
						
						$('.notif-content').append(`<div class="notif-list" onclick="notifList(${val.notif_id}, '${val.link}')">
									<div class="row d-flex align-items-center">
										<div class="col-2 text-center">
											<i class="${iconNotif(val.type)}"></i>
										</div>
										<div class="col-10 d-flex flex-column justify-content-center">
											<div class="notif-title">
												<p class="text-break fs-12 fw-bold m-0">${val.title.substring(0, 100).replace(/(<([^>]+)>)/ig, '')}</p>
												<p class="text-break fs-12 m-0">${(val.description) ? val.description.substring(0, 100).replace(/(<([^>]+)>)/ig, '') : ''}</p>
												<span class="fs-12 notif-date">${date.getDate()} ${monthNames[date.getMonth()]} ${date.getFullYear()} ${ (date.getHours() < 10 ? '0' : '') + date.getHours() }:${ (date.getMinutes() < 10 ? '0' : '') + date.getMinutes()  }</span>
											</div>
											
											<span class="${(val.seen == false) ? `dotted-notif` :  ``}"></span>
										</div>
									</div>
								</div>`);
					});
				}
			}
		});

	});

	// CHANGE BADGE TOTAL TOTIF
	const badgeTotalNotif = function(){
		$.ajax({
			type: "GET",
			url: BASE_URL+"notif/notif",
			dataType: "JSON",
			success: function (response) {
				if(response.success == true){
					$('.notif-number').html(response.total);
				}
			}
		});
	}

	badgeTotalNotif();

	setInterval(function(){
		badgeTotalNotif();
		
	}, 5000);

	// setInterval(function(){
	// 	$.ajax({
	// 		type: "GET",
	// 		url: BASE_URL+"notif/sync_notif",
	// 		success: function (response) {
	// 			badgeTotalNotif();
	// 		}
	// 	});
	// }, 30000);

	function iconNotif(type){
		let icon;
		if(type == 'TASK'){
			icon = 'bi bi-list-task';
		}else if(type == 'SESI'){
			icon = 'bi bi-calendar';
		}else if(type == 'NEWS'){
			icon = 'bi bi-newspaper'
		}else if(type == 'ASESMEN'){
			icon = 'bi bi-file-font-fill';
		}else if(type == 'LOGIN'){
			icon = 'bi bi-person-check-fill';
		}
		return icon
	}
  
  })(window.jQuery);

// notif-list DI KLIK
function notifList(id, link){
	$.ajax({
		type: "GET",
		url: BASE_URL+"notif/notif_update",
		data: {
			notif_id: id
		},
		dataType: "JSON",
		success: function (response) {
			if(response.success == true){
				window.location.href = BASE_URL+link;
			}
		}
	});
}

// nav bar
const links = document.querySelectorAll('.l-navbar .nav-link');
const firstSegment = word => {
	const uri = window.location.href.replace(word, '');
	const newUri = uri.split('/');
	return newUri[0];
};

links.forEach(link => {
	const uri = BASE_URL + firstSegment(BASE_URL);

	link.classList.remove('active');
	if(link.href == uri) link.classList.add('active');
});

function logoutConfirm(){
	Swal.fire({
		title: "Anda Yakin Keluar Akun?",
		// text: "Data yang dihapus tidak dapat dikembalikan",
		type: "warning",
		showCancelButton: true,
		confirmButtonClass: "btn btn-success mt-2",
		cancelButtonColor: "#f46a6a",
		confirmButtonText: "Ya",
		cancelButtonText: "Tidak" 
	}).then(reslt => {
		if(!reslt.value)
			return false;
		
		window.location.href = BASE_URL+'auth/logout';
	});
}
	
function checkFileExists(url) { 
	// cek apakah file ada jika tidak ada tampilkan popup modal file tidak ditemukan
	$.ajax({
		url: url,
		type: 'HEAD',
		success: function() {
			window.open(url, '_blank');
		},
		error: function() {
			// tampilkan popup modal file tidak ditemukan
			$('#modal-file-not-found').modal('show');
		}
	});
}
