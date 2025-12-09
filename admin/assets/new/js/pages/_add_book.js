'use strict';
// const BASE_URL = document.querySelector('base').href;
const form = document.forms['frm-book'];

// Pilih Link atau file
const uploadLink = document.getElementById('ebook-link'); 
const uploadFile = document.getElementById('ebook-file');
const rdbFile = document.getElementById('rd-file');
const rdbLink = document.getElementById('rd-link');

rdbFile.addEventListener('click', e => {
    const inputFile = uploadFile.querySelector('input');
    const inputLink = uploadLink.querySelector('input');
    
    inputFile.removeAttribute('disabled');
    uploadFile.classList.remove('d-none');
    inputLink.setAttribute('disabled', 'disabled');
    uploadLink.classList.add('d-none');
});
rdbLink.addEventListener('click', e => {
    const inputFile = uploadFile.querySelector('input');
    const inputLink = uploadLink.querySelector('input');
    
    inputLink.removeAttribute('disabled');
    uploadLink.classList.remove('d-none');
    inputFile.setAttribute('disabled', 'disabled');
    uploadFile.classList.add('d-none');
});


// FOTO EBOOK
const cover = document.querySelectorAll('input.ebook-cover');

Array.from(cover).forEach((item, idx) => {
    
    item.addEventListener('change', e => {
        e.preventDefault();

        const parent = item.parentNode.closest('div.form-group');
        const textError = parent.querySelector('small.text-danger');
        const labelImg = document.querySelector('#file_sampul').previousElementSibling;

        if(item.name == "cover[]")
        {
            if(document.querySelector('#file_sampul').files.length == 0) {
                labelImg.style.borderColor = 'var(--danger)';
                textError.classList.remove('d-none');
                textError.innerHTML = '<i>Foto utama tidak boleh kosong !!!</i>';
                return false;
            }
        }

        if(item.files && item.files[0])
        {
            const reader = new FileReader();
    
            reader.addEventListener('load', evt => {
                evt.preventDefault();

                if(!textError.classList.contains('d-none'))
                {
                    labelImg.style.borderColor = '';
                    textError.classList.add('d-none');
                    textError.innerHTML = '';
                }

                const target = evt.target;
                const img = item.previousElementSibling.getElementsByTagName('img')[0];
                const imgRemove = item.previousElementSibling.getElementsByClassName('btn-remove-img')[0];
                img.classList.remove('d-none');
                imgRemove.classList.remove('d-none');
                img.style.zIndex = 10;
                imgRemove.style.zIndex = 12;
                img.src = target.result;
            });

            reader.readAsDataURL(item.files[0]);
        }

    });
});
// remove image
const btnRemoveImg = document.querySelectorAll('.btn-remove-img');

Array.from(btnRemoveImg).forEach(item => {
    
    item.addEventListener('click', e => {
        e.preventDefault();
        const formGroup = item.parentNode.closest('div.mr-3');
        const label = formGroup.querySelector('label.lbl-img');
        const inputFile = formGroup.querySelector('input.ebook-cover');
        const img = label.getElementsByTagName('img')[0];


        if(inputFile.getAttribute('name') == 'book-image')
        {
            const others = document.querySelectorAll('input[name="cover[]"]');
            const eArray = [];
            Array.from(others).forEach(i => {
                if(i.files && i.files[0])
                    eArray.push(1);
            });
            if(eArray.length > 0)
                return false;
        }

        img.removeAttribute('src');
        img.classList.add('d-none');
        item.classList.add('d-none');
        Object.entries(inputFile.files).splice(1, 0);
        inputFile.value=null;
    });

});

// Subcription clone
const addSubcribe = document.querySelector('#new-subscribe'),
      subsSection = document.getElementById('subscription-section'),
      subscriptionContainer = document.getElementsByClassName('subscription-container');

let selectionArr = [];

$('#subscription-0-benefit').selectize({
    plugins: ["restore_on_backspace", "clear_button"],
    onChange: e => {
        selectionArr = [...new Set(e)];
    }
});


addSubcribe.querySelector('button').addEventListener('click', e => {
    $('#subscription-0-benefit')[0].selectize.destroy();

    const node = subscriptionContainer[0].cloneNode(true);
    const formGroup = node.querySelectorAll('.form-group');


    Array.from(formGroup).forEach((item, idx) => {
        const label = item.querySelector('label');
        const input = item.querySelector('[name*="subscription[0]"]');

        const indexForm = input.id.split('-');
        const parseForId = indexForm[0] + '-' + (parseInt(subscriptionContainer.length)) + '-' + indexForm[2];
        label.setAttribute('for', parseForId);
        input.setAttribute('id', parseForId);
        input.name =  indexForm[0] + '[' + (parseInt(subscriptionContainer.length)) + '][' + indexForm[2] + ']';
       
    });
    if(node.querySelector('#subscription-' + parseInt(subscriptionContainer.length) + '-benefit').innerHTML ==  null)
        node.querySelector('#subscription-' + parseInt(subscriptionContainer.length) + '-benefit').innerHTML = document.querySelector('#subscription-0-benefit').innerHTML;

    node.querySelector('#subscription-' + parseInt(subscriptionContainer.length) + '-name').value = '';

    const selectBenefit0 = $('#subscription-0-benefit').selectize({
        plugins: ["restore_on_backspace", "clear_button"],
    });
    selectBenefit0[0].selectize.setValue(selectionArr);
    // di split lagi
    document.getElementById('subscription-section').insertBefore(node, addSubcribe);
    setTimeout(() => {
        $('#subscription-' + parseInt(subscriptionContainer.length - 1) + '-benefit').selectize({ 
            plugins: ["restore_on_backspace", "clear_button"],
        });
    }, 100);
     // buat selectize
    
    
});

// Subscriptio all
const selectSubscrinePeriode = () => {

    Array.from(subscriptionContainer).forEach((item, idx) => {
        const periode = item.querySelector('#subscription-'+idx+'-type');
        const name = item.querySelector('#subscription-'+idx+'-name');
    
        periode.addEventListener('change', e => { 
            if(e.target.value)
                name.value = e.target.options[e.target.selectedIndex].text;
            else
                name.value = '';
        });


        
    
    });
}

const setInvalidForm = () => {

}

selectSubscrinePeriode();

const mutObsrv = new MutationObserver(mut => {

    mut.forEach((elem, idx) => {
        selectSubscrinePeriode();

        if(form.querySelectorAll('.is-invalid'))
        {
            Array.from(form.querySelectorAll('.is-invalid')).forEach(inp => {

                inp.addEventListener('change', e => {
                    const target = e.target;
                    const parent = e.target.parentNode.closest('.form-group');
                    
                    Array.from(parent.querySelectorAll('.invalid-feedback'), i => {
                        i.remove()
                    });
                    target.classList.remove('is-invalid');
                });

            });
        }

    });
});

mutObsrv.observe(subsSection, {
    attributes: true,
    childList: true,
    subtree: true,
});


// Kategori
const setTree = (data, id = null) => {
    return data.filter(items => +items.parent_category === +id)
            .map(x  => 
                ({
                    'id': x.id,
                    'title': x.category_name, 
                    'subs': setTree(data, +x.id)
                }));  
}

const getAllCategories = async () => {
    try
    {
        const f = await fetch(`${BASE_URL}/kategori/get_all`);
        const j = await f.json();

        return j;
    } catch (error) 
    {
        console.log(error);
    }
}


// check if errors is exists



form.addEventListener('submit', async e => {
    e.preventDefault();
    
    Array.from(document.querySelectorAll('.form-group')).forEach(i => {
        
        if(i.querySelector('.is-invalid'))
        {
            i.querySelector('.is-invalid').classList.remove('is-invalid');
        }

        if(i.querySelector('.invalid-feedback'))
        {
            Array.from(i.querySelectorAll('.invalid-feedback'), item => {
                item.remove();
            });
        }
    });

    Swal.fire({
        html: 	'<div class="d-flex flex-column align-items-center">'
        + '<span class="spinner-border text-primary"></span>'
        + '<h3 class="mt-2">Loading...</h3>'
        + '<div>',
        showConfirmButton: false,
        width: '10rem'
    });

    const formData = new FormData(e.target);
    try
    {
        const f = await fetch(`${BASE_URL}/book/store`, {
            method: 'POST',
            body: formData
        });

        Swal.close();

        const j = await f.json();

        if(!f.ok)
        {

            Swal.fire({
                type: 'error',
                title: '<h5 class="text-danger text-uppercase">ERROR</h5>',
                html: `<span class="text-danger">${j.message}</span>`,
                timer: 3000
            }).then(t => {
                const errors = j.errors;

                for(const error in errors)
                {
                    let fieldName = error;
                    if(error.includes('subscription'))
                    {
                        const spl = error.split('.');
                        fieldName = 'subscription[' + spl[1] + '][' + spl[2] + ']';
                    }
    
                    if(error == 'book-image')
                    {
                        const sampul = document.querySelector('.lbl-img');
                        sampul.classList.add('border-danger');
                    }
                    
                    const field = document.querySelector('[name="'+fieldName+'"]');
                    const formGroup = field.parentNode.closest('.form-group');
                    // tambah small baru di bawah
                    const msg = document.createElement('small');
                    msg.classList.add('invalid-feedback');
                    msg.innerText = Object.values(errors[error])[0];
    
                    if(error == 'ebook-file' || error == 'ebook-link')
                    {
                        msg.classList.add('d-inline-block');
                    }
        
                    field.classList.add('is-invalid');
                    formGroup.appendChild(msg);
                }
    
            });

           
            return false;
        }

        Swal.fire({
            type: 'success',
            title: '<h5 class="text-success text-uppercase">sukses</h5>',
            html: `<span class="text-success">${j.message}</span>`,
            timer: 1200
        })
        .then(t => {
            window.location.href = BASE_URL + '/book';
        });
    }
    catch(err)
    {
        console.log(err);
    }
});


(async($) => {

    const categoryTree = setTree(await getAllCategories());

    const category = $('#book-category').comboTree({
        source: categoryTree,
        isMultiple: true
    });

    category.onChange(() => {
        document.querySelector('#book-category-id').value = category.getSelectedIds().join(',');

        console.log(category._input.removeClass('is-invalid'));
    });

})(jQuery)


