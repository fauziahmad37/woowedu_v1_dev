'use strict';
//import PaginationSystem from '../../node_modules/pagination-system/dist/pagination-system.esm.min.js';

const formSearch = document.forms['form-search'];
const form = document.forms['form-input'],
display = document.querySelector('#ul-display'),
imgCover = document.getElementById('img-cover');
// const BASE_URL = document.querySelector('base').href;
const url = new URL(BASE_URL);

// get all Categories
const getCategories = async () => {
    try
    {
        const f = await fetch(BASE_URL + 'kategori/get_all');
        const j = await f.json();

        return j;
    }   
    catch(err)
    {
        console.log(err);
    }    
}

// get all publisher
const getPublisher = async () => {
    try
    {
        const f = await fetch(BASE_URL + 'publisher/get_all');
        const j = await f.json();

        return j;
    }   
    catch(err)
    {
        console.log(err);
    }  
}

const getBooks = async () => {

    try
    {
        const f = await fetch(`${BASE_URL}/book/get_all`);
        const j = await f.json();

        return j;
    }
    catch(err)
    {

    }
}

(async ($, window) => {

    // category tree
    const categories = [...(await getCategories())].map(x => ({id: x.id, text: x.category_name, parent: x.parent_category == null ? '#' : x.parent_category }));

    $('#category-tree').jstree({
        core: {
            multiple: false,
            data: categories
        },
        checkbox: {
            'three_state': false,
            'tie_selection': true
        },
        plugins: ['checkbox']
    })
    .bind('select_node.jstree', (e, data) => {
        form["book-category"].value = data.node.id;
        form["book-category_text"].value = data.node.text;
    })
    .bind('deselect_node.jstree', (e, data) => {
        form["book-category"].value = '';
        form["book-category_text"].value = '';
    })
    .bind('loaded.jstree', (e, data) => {
        if(form["book-category"].value)
            $('#category-tree').jstree(true).select_node(form["book-category"].value);
    });

    $('#category-tree').on('click', e => {
        e.stopPropagation();
    });

    // penerbit
    const publisher = [...(await getPublisher())].map(x => ({'id': x.id, 'text': x.publisher_name}));
    var $select = $('select[name="book-publisher"]').selectize({
        create: false,
        valueField: 'id',
        labelField: 'text',
        options: publisher
    });

    var selectize = $select[0].selectize;
    selectize.load(e => {
        if(form['book-publisher'].getAttribute('value'))
           selectize.setValue(form['book-publisher'].getAttribute('value'));
    });
    
    // file upload
    const imgCover = document.querySelector('#img-cover'),
          fileInput = document.getElementById('book-image');

    fileInput.addEventListener('change', e => {
        if(e.target.files && e.target.files[0]) {
            const fReader = new FileReader();
            const file = e.target.files;

            fReader.addEventListener('load', e => {
                imgCover.src = e.target.result;
                console.log(file);
            });

            fReader.readAsDataURL(e.target.files[0]);
        }
    });

    // yearpicker
    const thisYear =  (new Date()).getFullYear();
    form['book-year'].max = thisYear;
    if(!form['book-year'].getAttribute('value'))
        form['book-year'].value = thisYear;
    
		 // Datatable
		 var table = $('#table-main').DataTable({
			serverSide: true,
			processing: true,
			ajax: {
				url: BASE_URL + 'book/get_all_paginated'
			},
			columns: [
				{
					data: 'id',
					visible: false
				},
				{
					data: 'cover_img',
					className: 'dt-nowrap align-middle',
					width: '8%',
					render: (data, type, row, _meta) => {
                        const url = new URL(BASE_URL.replace('admin/', ''));
                        let img = '<img src="'+url+'/assets/images/ebooks/cover/default.png" height="'+(165 - 50)+'" width="'+(128 - 50)+'">';

                        if(data)
                        {
                            if(row.from_api)
                                img = '<img src="'+data+'" height="'+(165 - 50)+'" width="'+(128 - 50)+'">';
                            else
                                img = '<img src="'+url+'/assets/images/ebooks/cover/'+data+'" height="'+(165 - 50)+'" width="'+(128 - 50)+'">';
                        }
						
                        return img;
					}
				},
				{
					data: 'title',
					className:'align-middle',
					createdCell: cell => {
						cell.classList.add('text-dark');
					}
				},
				{
					data: 'category_id',
					visible: false
				},
				{
					data: 'book_code',
					visible: false
				},
				{
					data: 'category_name',
					className: 'align-middle'
				},
				{
					data: 'publisher_id',
					visible: false
				},
				{
					data: 'publisher_name',
					className: 'align-middle'
				},
				{
					data: 'author',
					className: 'align-middle'
				},
				{
					data: 'isbn',
					className: 'align-middle'
				},
				{
					data: 'qty',
					className: 'text-center align-middle'
				},
				{
					data: 'created_at',
					className: 'text-center align-middle',
                    visible: false
				},
				{
					data: null,
					className: 'align-middle',
					render(data, type, row, _meta)
					{
						const btn = '<span class="d-flex flex-nowrap">' +
									'<button role="button" class="btn btn-sm btn-info rounded-circle border-0 show_data"><i class="fas fa-eye"></i></button>' + 
									'<button role="button" class="btn btn-sm btn-success rounded-circle border-0 edit_data"><i class="fas fa-edit"></i></button>' + 
									`<a role="button" class="btn btn-sm btn-danger rounded-circle border-0 delete_data"><i class="fas fa-trash text-white"></i></a>` + 
									'</span>';
	
						return btn;
					}
				}
			]
		});

    // show one
    $('#table-main tbody').on('click', 'button.show_data', e => {
        var row = table.row(e.target.parentNode.closest('tr')).data();
        var sets = document.querySelectorAll('[data-item]');
       

        for(var set of sets)
        {
            if(set.dataset.item === 'cover_img')
            {
                if(row[set.dataset.item])
                {
                    if(row.from_api)
                       set.src = row[set.dataset.item];
                    else
                        set.src = url.origin + '/assets/images/ebooks/cover/' + row[set.dataset.item];
                }
                    
                else
                    set.src = url.origin  + '/assets/images/ebooks/cover/default.png';
            }

            set.innerText = row[set.dataset.item];
        }

        $('#modal-show').modal('show');
    });

    // file
    document.getElementById('input-file1').addEventListener('change', e => {
        const berkas = new Object(e.target.files[0]);
        
        document.querySelector('label[for="input-file1"]').innerText = berkas.name;
    });

    // reset
    form.addEventListener('reset', e => {
        e.preventDefault();
        resetForm();
    });

    // add data
    document.getElementById('btn-add').addEventListener('click', e => {
         // reset form
         form.action = BASE_URL + 'book/store';
         resetForm();
    });

    // edit data
    $('#table-main tbody').on('click', 'button.edit_data', e => {
        var row = table.row(e.target.parentNode.closest('tr')).data();

        // reset form
        form.action = BASE_URL + 'book/edit';
        resetForm();

        form['book-id'].value = row.id;
        form['book-code'].value = row.book_code;
        form['book-title'].value = row.title;
        form['book-category'].value = row.category_id;
        form['book-category_text'].value = row.category_name;
        form['book-publisher'].value = row.publisher_id;
        form['book-author'].value = row.author;
        form['book-isbn'].value = row.isbn;
        form['book-year'].value = row.publish_year;
        form['book-qty'].value = row.qty;
        form['book-description'].value = row.description;
        form['book-img_name'].value = row.cover_img;

        // imagge
        const url = new URL(BASE_URL.replace('admin/', ''));
        let img = url.origin + '/assets/images/ebooks/cover/default.png';

        if(row.cover_img)
        {
            if(row.from_api == 1)
                img = row.cover_img;
            else
                img = url.origin + '/assets/images/ebooks/cover/' + row.cover_img;
        }
        imgCover.src = img;

        // tree
        $('#category-tree').jstree(true).select_node(form["book-category"].value);

        // select
        selectize.setValue(row.publisher_id);

        $('#modal-input').modal('show');
    });

     // reset form action
    function resetForm()
    {
        const formData = new FormData(form);
        const fields = Object.fromEntries(formData.entries());
        
        for(const field in fields) 
        {
            form[field].value = null;
            form[field].classList.remove('is-invalid');
            if(document.querySelector('small[data-error="'+field+'"]'))
                document.querySelector('small[data-error="'+field+'"]').innerHTML = null;
        }

        $('#category-tree').jstree(true).refresh();
        imgCover.src = url.origin + '/assets/images/ebooks/cover/default.png';;
        selectize.clear();
        form['book-year'].value = thisYear;
        
       
    }

	// Delete Data
	$('#table-main tbody').on('click', 'a.delete_data', e => {
		Swal.fire({
            icon: 'warning',
            title: '<h4 class="text-warning">Apakah anda yakin !?</h4>',
            html: '<h5 class="text-warning">Data yang di hapus tidak dapat di kembalikan.</h5>',
            showConfirmButton: true,
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        })
        .then(t => {
          
            if(!t.value) 
                return;

            let row = table.row($(e.target).parents('tr')[0]).data();
            loading();
            window.location.href = BASE_URL + 'book/erase/' + row.id;
        });
	});

	// Form Submit
	form.addEventListener('submit', e => {
		loading();
	});

	// Search submit
    formSearch.addEventListener('submit', e => {
        e.preventDefault();
        // if(formSearch['s_member_name'].value)
		table.columns(1).search(formSearch['s_book_name'].value).draw();
		table.columns(2).search(formSearch['s_book_author'].value).draw();
		table.columns(3).search(formSearch['s_book_publisher'].value).draw();
    });
})(jQuery, window);

const loading = () => {
    Swal.fire({
        html: 	'<div class="d-flex flex-column align-items-center">'
        + '<span class="spinner-border text-primary"></span>'
        + '<h3 class="mt-2">Loading...</h3>'
        + '<div>',
        showConfirmButton: false,
        width: '10rem'
    });
}

// import data from excel
$('#import-data').on('click', e => {
	$('#modal-import').modal('show');
});

// Prevent Automatic Submit when press enter
window.addEventListener('keypress', e => {
    const el = e.target;

    if(e.code.toLowerCase() == 'enter')
        e.preventDefault();
});
