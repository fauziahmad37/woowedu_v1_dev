'use strict';

async function getByPublisher() {
    try
    {
        const f = await fetch(BASE_URL + '/shopingCart/getByPublisher');
        const j = await f.json();

        return j;

    }
    catch(err)
    {
        console.error(err);
    }
}


document.addEventListener('alpine:init',
() => {

    Alpine.data('ebooks', () => ({
        ebooks: {},
        async init() {
            this.ebooks = {...(await getByPublisher()).data}; 
        }
    }));

    
    Alpine.bind('SelectAll', () => {
        return {
            '@click': e => {
                const checkboxes = document.getElementsByClassName('form-check-input');
                
                if(e.target.checked)
                {
                    for(const cb of checkboxes)
                        cb.checked = true;
                }
                else
                {
                    for(const cb of checkboxes)
                        cb.checked = false;
                }
            }
        }
    });
 
    
    Alpine.bind('SelectAllPublisher', () => ({
        '@click': e => {
            const elem = e.target;
            const parentUl = elem.parentNode.closest('ul');
            
            const checkboxes = parentUl.getElementsByClassName('form-check-input');

            if(elem.checked)
            {
                for(const cb of checkboxes)
                    cb.checked = true;
            }
            else
            {
                for(const cb of checkboxes)
                    cb.checked = false;
            }
        }
    }));

})