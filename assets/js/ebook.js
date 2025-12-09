'use strict';
import PaginationSystem from '../node_modules/pagination-system/dist/pagination-system.esm.min.js';

const grid = document.querySelector('#grid');
const paginationContainer = document.querySelector('.pagination');
const $ = jQuery;
const frmSearch = document.forms['frm-search'];

/**
 * @descsription add new grid for a book for list
 * @date 8/28/2023 - 2:50:46 PM
 *
 * @param {*} e
 * @returns {HTMLDivElement}
 */
const createBookGrid = e => {
  
    const column = document.createElement('div');
    const figure = document.createElement('a');
    const img = new Image(imgWidth, imgHeight);
    const figcaption = document.createElement('div');
    const title = document.createElement('p');
    const content = document.createElement('p');
    // add to child
    column.appendChild(figure);
    figure.appendChild(img);
    figure.appendChild(figcaption);
    figcaption.appendChild(title);
    figcaption.appendChild(content);
    // styles and attr
    column.classList.add('col-4', 'p-2');
    figure.classList.add('card', 'flex-row', 'flex-nowrap', 'justify-content-around', 'border-0', 'shadow-sm', 'text-decoration-none');
    figure.style.cursor = 'pointer';
    figcaption.classList.add('card-body', 'flex-grow-1');
    img.classList.add('my-2', 'ms-2');
    title.classList.add('title', 'p-0');
    // figure
    figure.onmouseover = e => { 
        figure.classList.remove('shadow-sm');
        figure.classList.add('shadow'); 
    };
    figure.onmouseout = e => { 
        figure.classList.remove('shadow'); 
        figure.classList.add('shadow-sm'); 
    };
    // image
    const imgFileName = ('assets/images/ebooks/cover/' + e.cover_img).split('.');
    img.src = imgFileName[0] + '_thumb.jpg';
    img.onerror = () => {
        img.src = 'assets/images/ebooks/cover/default.png';
    };
    // title
    const judul = document.createTextNode(e.title);
    const kategori = document.createTextNode(e.category_name);
    title.appendChild(judul);
    content.appendChild(kategori);
    console.log(column);
    return column;
}

/**
 * @description Get All Books
 * @date 8/28/2023 - 2:58:56 PM
 *
 * @async
 * @returns {Object}
 */
const getBooks = async (page, count) => {
    try
    {

        const url = new URL("ebook/list", BASE_URL);
        url.searchParams.append('count', count);
        url.searchParams.append('page', page);
        const f = await fetch(url.href);

        return await f.json();
    }
    catch(err)
    {
        console.error(err);
    }
}


/**
 * Description placeholder
 * @date 9/21/2023 - 2:45:56 PM
 *
 * @async
 * @returns {unknown}
 */
const getCategory = async () => {
    try {
        const adminUri = ADMIN_URL + 'kategori/get_all';
        const f = await fetch(adminUri);

        return await f.json();
    } catch (error) {
        console.log(error);
    }
}


/**
 * 

 * @date 9/21/2023 - 9:55:55 AM
 *
 * @async
 * @param {*} $
 * @returns {*}
 */
(async ($) => {
    
    const pageOption = {
        url: new URL("ebook/list", BASE_URL).href,
        urlParams: {
            limit: 'count',
            pageNumber: 'page'
        },
        dataContainer: grid,
        dataRenderFn: item => item.data.map(e => { 
            const imgWidth = 78 * 1.5;
            const imgHeight = 105 * 1.5;
            const url = window.location.href + '/detail/' + e.id;
            const img = BASE_URL + (('assets/images/ebooks/cover/' + e.cover_img).split('.'))[0] + '_thumb.jpg';
			console.log(url);
			
            return `<div class="col-12 col-md-6 col-lg-4 p-2">
                <a class="card ebook-card flex-row flex-nowrap justify-content-around shadow-sm text-decoration-none" href="${ url }"
                    onmouseover="this.classList.remove('shadow-sm'); this.classList.add('shadow')"
                    onmouseout="this.classList.remove('shadow'); this.classList.add('shadow-sm')"
                    style="height: 223px"
                >
                    <img width="${imgWidth}" 
                         height="${imgHeight}" 
                         src="${ img }"
                         class="ms-2 my-2" 
                         onerror="this.src = 'assets/images/ebooks/cover/default.png';"/>
                    <div class="card-body flex-grow-1">
                        <p class="title p-0 text-justify">${e.title}</p>
                        <p class="fs-14 py-2 text-justify">${e.category_name}</p>
                    </div>
                </a>
            </div>
        `}).join(''),
        perPage: 9,
        pagingContainer: paginationContainer
    }
    
    const paging = new PaginationSystem(pageOption);

    /**
     * *********************************************
     *                SEARCHING
     * *********************************************
     */

    const kategori = [...await getCategory()].map(x => ({ id: x.category_code, text: x.category_name }));

    $('select[name="filter[category]"]').prepend('<option selected></option>').select2({
        theme: "bootstrap-5",
        data: kategori,
        placeholder: '-- Pilih Kategori --',
        allowClear: false
    });

    $('select[name="filter[penerbit]"]').prepend('<option selected></option>').select2({
        theme: "bootstrap-5",
        placeholder: '-- Pilih Penerbit --',
        allowClear: false
    });

    $('select[name="filter[category]"]').val('').trigger('change.select2');
    $('select[name="filter[penerbit]"]').val('').trigger('change.select2');

    frmSearch.addEventListener('submit', async e => {
        e.preventDefault();

        const entries = new FormData(e.target).entries();
        const obj = Object.fromEntries(entries);

        await paging.filterData(obj);
    });

    frmSearch.addEventListener('reset', async e => {
        document.getElementsByTagName('filter[title]').value = '';
        $('select[name="filter[penerbit]"]').val('').trigger('change.select2');
        $('select[name="filter[category]"]').val('').trigger('change.select2');
        await paging.restoreData();
    });

})(jQuery);




// /**
//  * @description show list of books
//  * @date 8/30/2023 - 2:09:10 PM
//  *
//  * @async
//  * @returns {*}
//  */
// const viewBookList = async (page, limit) => {
//     const ebooks = await getBooks(page, limit);

//     if(ebooks.data.length)
//     {
//         grid.innerHTML = null;
//         Array.from(ebooks.data, props => {
//             grid.appendChild(createBookGrid(props));
//         });
//     }

//     await pagination(ebooks.totalData, limit);
// }

// // pagination

// let currentPage = 1;
// let totalPage = (countData, limit) => Math.ceil(countData / limit);


// /**
//  * nexPage
//  * @date 8/30/2023 - 10:37:24 AM
//  *
//  * @async
//  * @returns {*}
//  */
// const nextPage = async () => {
//     currentPage = (currentPage >= totalPage) ? (currentPage = totalPage) : currentPage++;
//     await viewBookList(currentPage, 9);
// }


// /**
//  * previous Page
//  * @date 8/30/2023 - 10:37:36 AM
//  *
//  * @async
//  * @returns {*}
//  */
// const prevPage = async () => {
//     currentPage = currentPage <= 1 ? (currentPage = 1) : currentPage--;
//     await viewBookList(currentPage, 9);
// } 


// const currPage = async idx => {
//     currentPage = idx;
//     await viewBookList(currentPage, 9);
// }


// /**
//  * Pagination Logic
//  * @date 8/31/2023 - 10:57:08 AM
//  *
//  * @async
//  * @param {*} countData
//  * @param {*} limit
//  * @returns {*}
//  */
// const pagination = async (countData, limit) => {
//     // page
//     const liNext = document.createElement('li');
//     const liPrev = document.createElement('li');

//     const aNext = document.createElement('a');
//     const aPrev = document.createElement('a');
//     // previos page
//     liPrev.classList.add('page-item');
//     aPrev.classList.add('page-link');
//     aPrev.innerHTML = '<';
//     aPrev.onclick = async e => await prevPage(); 
//     liPrev.appendChild(aPrev);
//     paginationContainer.appendChild(liPrev);
//     // perpages
//     let pages = totalPage(countData, limit);

//     const createPaging = (linkText, link, idx) => {
//         const li = document.createElement('li');
//         const a = document.createElement('a');
//         li.classList.add('page-item');
//         a.classList.add('page-link');
//         a.innerHTML = linkText;
//         a.onclick = async e => await currPage(idx);
//         li.appendChild(a);
//         paginationContainer.appendChild(li);
//     }
    
//     if(currentPage > 1 && currentPage < totaPage)
//     {
//         // jika halaman lebih dari 3
//         if(currentPage > 3)
//         {
//             createPaging();
//         }
//         // jika halaman kurang dari total - 3
//     }
   
//     // next page
//     liNext.classList.add('page-item');
//     aNext.classList.add('page-link');
//     aNext.innerHTML = '>';
//     aNext.onclick = async e => await nextPage(); 
//     liNext.appendChild(aNext);

//     paginationContainer.appendChild(liNext);

//     await viewBookList(currentPage, limit);
// }
