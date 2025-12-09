<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script defer src="<?=base_url('assets/node_modules/moment/min/moment-with-locales.min.js')?>"></script>
<script defer>
    'use strict';

    // create read more
    const descContent = document.querySelector('.desc-content'),
          openContent = document.getElementById('open-content'),
          closeContent = document.getElementById("close-content"),
          csrfName = document.querySelector("meta[name=\"csrf_name\"]"),
          csrfToken = document.querySelector("meta[name=\"csrf_token\"]"),
          btnDropdownRating = document.querySelector('#rating-dropdown > button'),
          cbDropdownRating = document.getElementById('list-rating'),
          filledStar = document.getElementById('filled-star');

    const splitDescContent = descContent.textContent.trim().split(" ");
    if(splitDescContent.length > 50)
    {
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

    // WISHLIST
    const btnWishlist = document.getElementById("btn-wishlist");
    let isLiked = false;
    async function getWishlist() {
        try
        {
            const f = await fetch("<?=html_escape(base_url("/wishlist/get?item_id={$book["id"]}"))?>");
            const j = await f.json();

            if(j.isLiked)
            {
                btnWishlist.ariaChecked = true;
                btnWishlist.classList.remove("btn-outline-primary");
                btnWishlist.classList.add("btn-primary");
            }
            
        }
        catch(err)
        {
            console.error(err);
        }
    }

    btnWishlist.addEventListener("click", async e => {
        const fData = new FormData();
        fData.append("ebook_id", <?=intval($book["id"])?>);
        fData.append("item_type", "ebook");
        fData.append(csrfName.content, csrfToken.content);
        const defaultText = "<i class=\"bi bi-heart\"></i>";
        const loader = '<div class="spinner-border spinner-border-sm text-secondary" role="status" height=>' +
                          '<span class="visually-hidden">Loading...</span>' +
                       '</div>';

        btnWishlist.innerHTML = loader;
        try
        {
            const f = await fetch("<?=html_escape(base_url("/wishlist/post"))?>", {
                method: "POST",
                body: fData
            });
            
            const j = await f.json();
            btnWishlist.innerHTML = defaultText;

            if(j.isLiked)
            {
                btnWishlist.ariaChecked = true;
                btnWishlist.classList.remove("btn-outline-primary");
                btnWishlist.classList.add("btn-primary");

				Swal.fire({
					type: 'success',
					title: "Berhasil!",
					text: j.message,
					icon: "success"
				});
            }
            else
            {
               btnWishlist.ariaChecked = false;
               btnWishlist.classList.add("btn-outline-primary");
               btnWishlist.classList.remove("btn-primary");

			   
				if(j.limit){
					$('#btn-wishlist')[0].classList.remove('active')
					Swal.fire({
						type: 'info',
						title: "<h4>Maaf, Wishlist anda sudah penuh!</h4>",
						text: j.message,
						iconHtml: '<img src="assets/images/icons/wishlist-round.png">',
						// icon: "info",
						showConfirmButton: false,
						footer: '<a class="btn btn-primary" href="user/index">Atur Wishlist</a>'
					});
					return
				}else{
					$('#btn-wishlist')[0].classList.remove('active')
					Swal.fire({
						type: 'info',
						title: "Peringatan!",
						text: j.message,
						// icon: "info"
						iconHtml: '<img src="assets/images/icons/wishlist-round.png">',
					});
				}
			  
            }

            csrfName.setAttribute("content", j.csrf_name);
            csrfToken.content = j.csrf_token;
        }
        catch(err)
        {
            console.error(err);
        }
    });

    // SHOPPING CART
    const btnCart = document.getElementById("btn-cart");

    async function getShopingCart() {
        try
        {
            const f = await fetch("<?=html_escape(base_url("/shopingCart/get?ebook_id={$book["id"]}"))?>");
            const j = await f.json();

            if(j.isLiked)
            {
                btnCart.ariaChecked = true;
                btnCart.classList.remove("btn-outline-primary");
                btnCart.classList.add("btn-primary");
            }
        }
        catch(err)
        {
            console.error(err);
        }
    }

    btnCart.addEventListener("click", async e => {
        const fData = new FormData();
        fData.append("ebook_id", <?=intval($book["id"])?>);
        fData.append(csrfName.content, csrfToken.content);

        const defaultText = "<i class=\"bi bi-basket2\"></i>";
        const loader = '<div class="spinner-border spinner-border-sm text-secondary" role="status" height=>' +
                          '<span class="visually-hidden">Loading...</span>' +
                       '</div>';

        btnCart.innerHTML = loader;
        try
        {
            const f = await fetch("<?=html_escape(base_url("/shopingCart/post"))?>", {
                method: "POST",
                body: fData
            });
            
            const j = await f.json();
            btnCart.innerHTML = defaultText;

            if(j.isLiked)
            {
                btnCart.ariaChecked = true;
                btnCart.classList.remove("btn-outline-primary");
                btnCart.classList.add("btn-primary");
            }
            else
            {
               btnCart.ariaChecked = false;
               btnCart.classList.add("btn-outline-primary");
               btnCart.classList.remove("btn-primary");
            }

            csrfName.setAttribute("content", j.csrf_name);
            csrfToken.content = j.csrf_token;
        }
        catch(err)
        {
            console.error(err);
        }
    });

    // RATING2 RATINGAN
    async function ratingData() {
        try
        {
            const f = await fetch(`<?=base_url('/rating/list?book='.$book['book_code'])?>`);
            const j = await f.json();

            return j;
        }
        catch(err)
        {

        }
    }

    const createStarRating = rating => {
	    let star = '';
	    for(let i=1; i<=5; i++){
	    	star += (i <= rating) ? '<i class="bi bi-star-fill text-warning"></i>' : '<i class="bi bi-star text-warning"></i>';
	    }
	    return star;
    }

    const ratingList = list => {
        
        const ul = document.getElementById('daftar-ulasan');

        Array.from(list, item => {
            const li = document.createElement('li');
            li.classList.add('list-group-item');

            // create elements
            const figure = document.createElement('figure');
            const img = new Image();
            const figcaption = document.createElement('figcaption');
            // img.
            img.src = item.photo ? item.photo : '<?=base_url('assets/images/user.png')?>';
            img.onerror = e => { e.target.src = '<?=base_url('assets/images/user.png')?>'; };
            img.classList.add('rounded-circle', 'img-fluid', 'shadow-sm');
            img.style.height = '40px';
            // figcaption
            const captionHead = document.createElement('div');
            moment.locale('id');
            const tanggal = moment(item.created_at.split(' ').join('T'));

            captionHead.innerHTML = `<h6 class="text-capitalize mb-1">${item.member_name}</h6>` + 
                                        `<span class="mx-2">&#x2022;</span>` + 
                                    `<small class="text-body-tertiary fw-semibold">${tanggal.format('DD MMM YYYY')}</small>`;
            captionHead.classList.add('d-inline-flex', 'w-100');
            figcaption.appendChild(captionHead);

            // rating
            const starRated = document.createElement('span');
            starRated.innerHTML = createStarRating(+item.rate);
            starRated.classList.add('mb-3');
            figcaption.appendChild(starRated);

            const comment = document.createElement('p');
            comment.innerText = item.komentar;
            comment.classList.add('mt-3');
            figcaption.appendChild(comment);
            
            figcaption.classList.add('d-block', 'ms-3');
            // append to figure
            figure.classList.add('d-flex', 'flex-nowrap');
            figure.appendChild(img);
            figure.appendChild(figcaption);
            // append to li
            li.appendChild(figure);

            ul.appendChild(li);
        });
    }

    // FILTER RATING
    Array.from(cbDropdownRating.querySelectorAll('li')).forEach(item => {
        
        item.addEventListener('click', function(e) {
            const child = this.querySelector('a');
            btnDropdownRating.innerHTML = child.innerHTML; 
            
        });

    }, false);


    const calculateAvg = data => {
        const totalComment = data.count;

        // get total rate by start number 1 by 1
        const totalRate1 = data.data.filter(x => x.rate == 1);
        const totalRate2 = data.data.filter(x => x.rate == 2);
        const totalRate3 = data.data.filter(x => x.rate == 3);
        const totalRate4 = data.data.filter(x => x.rate == 4);
        const totalRate5 = data.data.filter(x => x.rate == 5);

        const weight = (1*totalRate1.length) + (2*totalRate2.length) + (3*totalRate3.length) + (4*totalRate4.length) + (5*totalRate5.length);
        const sum = totalRate1.length + totalRate2.length + totalRate3.length + totalRate4.length + totalRate5.length;
        const avg = weight / sum;
        // average number
        return avg.toFixed(1);
    }

    const countAvgToPercent = val => (val*100) / 5;
    const toPercent = (sum,total) => (sum/total) * 100; 

(async () => {
    await getWishlist();
    await getShopingCart();

    // RATING
    const ratings = await ratingData();
    ratingList(ratings.data);
    document.getElementById('value-avg').innerText = +calculateAvg(ratings);
    filledStar.style.width = countAvgToPercent(calculateAvg(ratings)) + '%';
    
    const totalRate1 = ratings.data.filter(x => x.rate == 1);
    const totalRate2 = ratings.data.filter(x => x.rate == 2);
    const totalRate3 = ratings.data.filter(x => x.rate == 3);
    const totalRate4 = ratings.data.filter(x => x.rate == 4);
    const totalRate5 = ratings.data.filter(x => x.rate == 5);

    // akumulasi bintang 5
    document.getElementById('rate-5-prog').style.width = toPercent(totalRate5.length, ratings.count) + '%';
    document.getElementById('rate-5-percent').innerHTML = toPercent(totalRate5.length, ratings.count) + '%';
    // akumulasi bintang 4
    document.getElementById('rate-4-prog').style.width = toPercent(totalRate4.length, ratings.count) + '%';
    document.getElementById('rate-4-percent').innerHTML = toPercent(totalRate4.length, ratings.count) + '%';
    // akumulasi bintang 3
    document.getElementById('rate-3-prog').style.width = toPercent(totalRate3.length, ratings.count) + '%';
    document.getElementById('rate-3-percent').innerHTML = toPercent(totalRate3.length, ratings.count) + '%';
    // akumulasi bintang 2
    document.getElementById('rate-2-prog').style.width = toPercent(totalRate2.length, ratings.count) + '%';
    document.getElementById('rate-2-percent').innerHTML = toPercent(totalRate2.length, ratings.count) + '%';
    // akumulasi bintang 1
    document.getElementById('rate-1-prog').style.width = toPercent(totalRate1.length, ratings.count) + '%';
    document.getElementById('rate-1-percent').innerHTML = toPercent(totalRate1.length, ratings.count) + '%';
})()

</script>

