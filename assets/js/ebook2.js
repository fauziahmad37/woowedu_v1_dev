document.addEventListener('DOMContentLoaded', function () {
	splideJs('#thumbnail-carousel-rekomendasi');
	splideJs('#thumbnail-carousel-terbaru');
	splideJs('#thumbnail-carousel-terbanyak-dibaca');

	new Splide('#thumbnail-carousel-bundling', {
		fixedWidth: 372,
		fixedHeight: 378,
		gap: 10,
		rewind: true,
		pagination: false,
	}).mount();
});

function splideJs($id) {
	new Splide($id, {
		fixedWidth: 160,
		fixedHeight: 280,
		gap: 10,
		rewind: true,
		pagination: false,
	}).mount();
}

var arrPage = [];

// SEARCH BUKU
$('form[name="frm-search"]').on('submit', e => {
	e.preventDefault();

	load_data();
	pagination();
});

// RESET FILTER
$('.reset-filter').on('click', e => {
	window.location.href = 'ebook';
});

$('#lihat-rekomendasi').on('click', e => {
	console.log('tes')
	e.preventDefault();

	load_data();
	pagination();
});

function load_data(page = 1, limit = 12) {
	let title = $('#title').val();
	let publisher = $('#publisher').val();
	let data = {};
	data.title = title;
	data.penerbit = publisher;
	data.classLevels = classLevels; // belum di pake di model list.

	$.ajax({
		type: "GET",
		url: BASE_URL + "ebook/list",
		dataType: 'JSON',
		async: false, // async nya di matika supaya arrPage nya terisi data dulu daru pagination nya di load
		data: {
			count: limit,
			page: page,
			filter: data
		},
		success: function (response) {
			$('#title-pencarian').html('');
			$('#container-hasil-pencarian').html('');
			$('#ebook-siswa-onboarding').html('');

			let cardBook = '';
			response.data.forEach(el => {
				let coverImg;

				//  cek apakah gambar nya ada atau tidak
				if (checkImage(`assets/images/ebooks/cover/${el.cover_img}`)) {
					coverImg = `assets/images/ebooks/cover/${el.cover_img}`;
				} else {
					coverImg = `assets/images/images-placeholder.png`;
				}

				cardBook += `<div class="col-xl-2 col-lg-3 col-md-4 col-sm-4 col-xs-6">
								<div class="card rounded-3 border-light-subtle p-3 m-1 mb-3" style="width:160px; height: 270px;">
									<img class="rounded-3" src="${coverImg}" alt="" width="128" height="172">
									<p class="fs-12 lh-1 mt-3 mb-2 book-publiher-card">Penerbit: ${el.publisher_name}</p>
									<p class="fs-14 mb-0 book-title-card">${el.title}</p>
								</div>
							</div>`;


			});

			$('#container-hasil-pencarian').html(cardBook);

			// ########################## PAGINATION JS ##############################
			arrPage = [];
			for (let i = 1; i <= response.totalData; i++) {
				arrPage.push(i);
			}

			// TITLE PENCARIAN
			$('#title-pencarian').html(`Menampilkan ${response.data.length} dari ${response.totalData} hasil pencarian untuk "${title}"`);
		}
	});
}


function pagination() {
	// ########################## PAGINATION JS ##############################
	$('#page').pagination({
		// dataSource: [1, 2, 3, 4, 5, 6, 7, 8,9,10,11,12,13,14,15,16,17,18,19,20],
		dataSource: arrPage,
		className: 'paginationjs-theme-blue paginationjs-big',
		pageSize: 12,
		callback: function (data, pagination) {
			// template method of yourself
			// var html = template(data);
			// dataContainer.html(html);
			load_data(pagination.pageNumber);
		}
	})
}

// check gambar ada atau tidak di server
function checkImage(url) {
	var http = new XMLHttpRequest();
	http.open('HEAD', url, false);
	http.send();
	return http.status != 400;
}

function autocomplete(inp, arr) {
	/*the autocomplete function takes two arguments,
	the text field element and an array of possible autocompleted values:*/
	var currentFocus;
	/*execute a function when someone writes in the text field:*/
	inp.addEventListener("input", function (e) {
		var a, b, i, val = this.value;
		/*close any already open lists of autocompleted values*/
		closeAllLists();
		if (!val) { return false; }
		currentFocus = -1;
		/*create a DIV element that will contain the items (values):*/
		a = document.createElement("DIV");
		a.setAttribute("id", this.id + "autocomplete-list");
		a.setAttribute("class", "autocomplete-items");
		/*append the DIV element as a child of the autocomplete container:*/
		this.parentNode.appendChild(a);
		/*for each item in the array...*/
		for (i = 0; i < arr.length; i++) {
			/*check if the item starts with the same letters as the text field value:*/
			if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
				/*create a DIV element for each matching element:*/
				b = document.createElement("DIV");
				/*make the matching letters bold:*/
				b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
				b.innerHTML += arr[i].substr(val.length);
				/*insert a input field that will hold the current array item's value:*/
				b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
				/*execute a function when someone clicks on the item value (DIV element):*/
				b.addEventListener("click", function (e) {
					/*insert the value for the autocomplete text field:*/
					inp.value = this.getElementsByTagName("input")[0].value;
					/*close the list of autocompleted values,
					(or any other open lists of autocompleted values:*/
					closeAllLists();
				});
				a.appendChild(b);
			}
		}
	});
	/*execute a function presses a key on the keyboard:*/
	inp.addEventListener("keydown", function (e) {
		var x = document.getElementById(this.id + "autocomplete-list");
		if (x) x = x.getElementsByTagName("div");
		if (e.keyCode == 40) {
			/*If the arrow DOWN key is pressed,
			increase the currentFocus variable:*/
			currentFocus++;
			/*and and make the current item more visible:*/
			addActive(x);
		} else if (e.keyCode == 38) { //up
			/*If the arrow UP key is pressed,
			decrease the currentFocus variable:*/
			currentFocus--;
			/*and and make the current item more visible:*/
			addActive(x);
		} else if (e.keyCode == 13) {
			/*If the ENTER key is pressed, prevent the form from being submitted,*/
			e.preventDefault();
			if (currentFocus > -1) {
				/*and simulate a click on the "active" item:*/
				if (x) x[currentFocus].click();
			}
		}
	});
	function addActive(x) {
		/*a function to classify an item as "active":*/
		if (!x) return false;
		/*start by removing the "active" class on all items:*/
		removeActive(x);
		if (currentFocus >= x.length) currentFocus = 0;
		if (currentFocus < 0) currentFocus = (x.length - 1);
		/*add class "autocomplete-active":*/
		x[currentFocus].classList.add("autocomplete-active");
	}
	function removeActive(x) {
		/*a function to remove the "active" class from all autocomplete items:*/
		for (var i = 0; i < x.length; i++) {
			x[i].classList.remove("autocomplete-active");
		}
	}
	function closeAllLists(elmnt) {
		/*close all autocomplete lists in the document,
		except the one passed as an argument:*/
		var x = document.getElementsByClassName("autocomplete-items");
		for (var i = 0; i < x.length; i++) {
			if (elmnt != x[i] && elmnt != inp) {
				x[i].parentNode.removeChild(x[i]);
			}
		}
	}
	/*execute a function when someone clicks in the document:*/
	document.addEventListener("click", function (e) {
		closeAllLists(e.target);
	});
}

/*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
let countries;

$('#title').on('keyup', function(e){
	$.ajax({
		type: "GET",
		url: "ebook/list",
		async: false,
		data: {
			count: 10,
			page: 1,
			filter: {
				title: e.currentTarget.value
			}
		},
		dataType: "JSON",
		success: function (response) {
			countries = response.data.map(function(item){
				return item.title
			});

			autocomplete(document.getElementById("title"), countries);
		}
	});
});

