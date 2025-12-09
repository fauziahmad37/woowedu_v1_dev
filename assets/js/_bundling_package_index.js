let frmSearch = document.forms['frm-search'];

// ================================= FILTER START ==================================
function autocomplete(inp, arr) {
	/*the autocomplete function takes two arguments,
	the text field element and an array of possible autocompleted values:*/
	var currentFocus;
	/*execute a function when someone writes in the text field:*/
	inp.addEventListener("input", function (e) {

		var a, b, i, val = this.value;
		/*close any already open lists of autocompleted values*/
		closeAllLists();
		if (!val) {
			return false;
		}
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
			// if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
			if (arr[i].toUpperCase().includes(val.toUpperCase())) {
				/*create a DIV element for each matching element:*/
				b = document.createElement("DIV");
				/*make the matching letters bold:*/
				//b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
				b.innerHTML = arr[i].substr(0, val.length);
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
let timer;

$('#title').on('keyup', function (e) {
	// bersihkan timer sebelumnya
	clearTimeout(timer);

	// set ulang timer
	timer = setTimeout(function () {
		$.ajax({
			type: "GET",
			url: "api/api_ebook/getBundlingPackages",
			data: {
				per_page: 20,
				page: 1,
				title: e.currentTarget.value
			},
			dataType: "JSON",
			success: function (response) {
				countries = response.data.fetch.map(function (item) {
					return item.package_name;
				});

				autocomplete(document.getElementById("title"), countries);
			}
		});
	}, 300); // tunggu 300ms setelah user berhenti mengetik
});

async function fetchPublishers() {
	return new Promise((resolve, reject) => {
		fetch("api/api_ebook/getAllPublisher")
			.then(response => response.json())
			.then(data => resolve(data.data.fetch))
			.catch(error => reject(error));
	});
}

fetchPublishers().then(function (data) {
	const publisherSelect = document.getElementById("publisher");
	data.forEach(function (item) {
		const option = document.createElement("option");
		option.value = item.id;
		option.textContent = item.publisher_name;
		publisherSelect.appendChild(option);
	});
});


// ================================= FILTER END ===================================

let page = 1;
let per_page = 10;

function getBundlingPackages() {
	let title = document.getElementById("title").value;
	let publisher_id = document.getElementById("publisher").value;

	let xhr = new XMLHttpRequest();
	xhr.open("GET", "api/api_ebook/getBundlingPackages?per_page=" + per_page + "&page=" + page + "&title=" + title + "&publisher_id=" + publisher_id, true);
	xhr.setRequestHeader("Content-Type", "application/json");
	xhr.onreadystatechange = function () {
		if (xhr.readyState === 4 && xhr.status === 200) {
			let response = JSON.parse(xhr.responseText);
			let data = response.data.fetch;

			if (response.data.total_data == 0) {
				document.getElementById("bundling-package-list").innerHTML = '<p class="text-center mt-5">Tidak ada paket bundling ditemukan.</p>';
				document.getElementById("load-more").classList.add("d-none");
				return
			}

			let card = '';
			data.forEach(function (item) {
				card += `
					<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-xs-12 mb-2">
						<div class="card rounded-3 border-light-subtle p-3 m-1">
							<img loading="lazy" class="rounded-3" height="200" src="${(item.package_image.includes('http')) ? item.package_image : 'default-image.jpg'}" alt="">
							<p class="fs-12 lh-1 mt-3 mb-2 book-publiher-card">Penerbit: ${item.publisher.publisher_name}</p>
							<p class="fs-14 mb-0 book-title-card">${item.package_name}</p>
							<a class="btn btn-primary text-white mt-3" href="BundlingPackage/detail/${item.id}">Detail Paket Bundling</a>
						</div>
					</div>
				`;
			});
			document.getElementById("bundling-package-list").insertAdjacentHTML('beforeend', card);

			// Tampilkan tombol "Lihat Lebih Banyak" jika ada lebih banyak data
			if (data.length == response.data.per_page) {
				document.getElementById("load-more").classList.remove("d-none");
			} else {
				document.getElementById("load-more").classList.add("d-none");
			}
		}
	};
	xhr.send();
}

getBundlingPackages();

frmSearch.addEventListener('submit', function (e) {
	e.preventDefault();
	page = 1;
	document.getElementById("bundling-package-list").innerHTML = ''; // Kosongkan daftar sebelum memuat ulang
	document.getElementById("load-more").classList.add("d-none");
	getBundlingPackages();
});

document.getElementById("load-more").addEventListener("click", function () {
	page++;
	getBundlingPackages();
});
