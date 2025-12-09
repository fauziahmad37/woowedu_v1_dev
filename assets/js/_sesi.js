let gabungSesi = document.getElementsByClassName('gabung-sesi')[0];
let sesiId = document.getElementsByName('sesi_id')[0];
let studentId = document.getElementsByName('student_id')[0];
let token = document.getElementsByName('csrf_token_name')[0].value;

gabungSesi.addEventListener('click', e => {
	postSesi();
});

const postSesi = async () => {
	const url = BASE_URL + 'sesi/gabung_sesi';
	const settings = {
		body: new FormData(document.getElementById("form-sesi")),
		method: "POST",
	};

	try{
		const fetchResponse = await fetch(url, settings);
		const data = await fetchResponse.json();

		document.getElementsByName('csrf_token_name')[0].value = data.token;

		if(data.success){
			Swal.fire({
				type: 'success',
				title:`<h5 class="text-uppercase">Sukses</h5>`,
				html: data.message
			});
			gabungSesi.innerHTML = 'Akhiri Sesi';
		} else {
			Swal.fire({
				type: 'error',
				title:`<h5 class="text-uppercase">Error</h5>`,
				html: data.message
			});
		}

		return data;
	} catch (e) {
		return console.log(e)
	}
}
