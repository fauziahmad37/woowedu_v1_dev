$(function() {
	$("#login").on("submit", function(e) {
		var block = $("#page_login").parent();
		$(block).block({
			message: '<span class="text-semibold"><i class="icon-spinner4 spinner position-left"></i>&nbsp; Mohon tunggu. Login...</span>',
			timeout: 3000, //unblock after 2 seconds
			overlayCSS: {
				backgroundColor: "#37474F",
				opacity: 0.9,
				cursor: "wait"
			},
			css: {
				border: 0,
				padding: "10px 15px",
				color: "#fff",
				width: "auto",
				"-webkit-border-radius": 2,
				"-moz-border-radius": 2,
				backgroundColor: "#333"
			}
		});

		var a = "?page=home&login=sukses";
		$.ajax({
			url: "auth/login", //nama action script php
			data: $(this).serialize(),
			type: "POST",
			success: function(data) {
				console.log(data);
				if (data > 0) {
					Swal.fire(
						{
							title: "Berhasil !",
							text: "Login Berhasil!",
							confirmButtonColor: "#66BB6A",
							type: "success"
						},
						function(isConfirm) {
							if (isConfirm) {
								window.location = "auth";
							}
						}
					);
				} else {
					Swal.fire({
						title: "Gagal !",
						text: "Login Gagal !",
						confirmButtonColor: "#EF5350",
						type: "warning",
						confirmButtonText: "Coba Lagi"
					});
				}
			},
			error: function(data) {
				Swal.fire({
					title: "Gagal !",
					text: "Login Gagal!",
					confirmButtonColor: "#EF5350",
					type: "error"
				});
			}
		});
		e.preventDefault();
	});
});
