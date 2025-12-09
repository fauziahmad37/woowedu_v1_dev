'use strict';
import PaginationSystem from '../node_modules/pagination-system/dist/pagination-system.esm.min.js';


(async () => {

    const pageOptions = {
        url: BASE_URL + 'materi/listGrid',
        urlParams: {
            limit: 'length',
            pageNumber: 'page'
        }
        dataContainer: document.querySelector('#grid'),
    }

})();