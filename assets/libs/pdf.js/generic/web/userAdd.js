import { PDFViewerApplication, PDFViewerApplicationOptions }  from './viewer.js';
const configElement = PDFViewerApplication.appConfig,
      statusDialog = document.getElementById('successStatusDialog'),
      loadingDialog = document.getElementById('saveToServerLoadingDialog'),
      statusMessage = document.getElementById('statusMessage'),
      bookmarkPage = document.getElementById('bookmarkPage'),
      dialogUserNote = document.getElementById('userNote'),
      uploadToServer = document.getElementById('saveToServer'),
      secondaryUpload = document.getElementById('secondaryUpload'),
      btnClose = document.getElementsByClassName('btn-close')[0],
      btnCloseBook = document.getElementById('closeBookButton'),
      bookmarkSidebar = document.getElementById('sidebarBookmark'),
      formUserNote = document.forms['frm-bookmarkPage'];


const url = new URL(parent.document.querySelector('base').href);
const urlParams = appInfo;
const pdfFile = urlParams['file'].split('/');

async function saveToServer(e) {
  document.getElementById('secondaryToolbarToggle').classList.remove('toggled');
  document.getElementById('secondaryToolbar').classList.add('hidden');

  const opt = {
    book: urlParams['id'],
    serverUrl: `${url.href}/ebook/save_book`,
    filename: urlParams['file']
  }
  await PDFViewerApplication.saveToServer(opt);
}

uploadToServer.addEventListener('click', async e => await saveToServer(e));
secondaryUpload.addEventListener('click', async e => await saveToServer(e));

document.getElementById('buttonEditTools').addEventListener('click', e => {
  const toolbar = document.getElementById('sidebarTextTools');

  if(toolbar.classList.contains('hidden'))
    toolbar.classList.remove('hidden');
  else
    toolbar.classList.add('hidden');
});

// USER NOTE

bookmarkPage.addEventListener('click', e => {
//   const outerContainer = configElement.sidebar.outerContainer;

//   if(outerContainer.classList.contains('sidebarOpen') && bookmarkSidebar.classList.contains('open')) {
//     outerContainer.classList.remove('sidebarOpen');
//     bookmarkSidebar.classList.remove('open');
//   }
//   else {
//     outerContainer.classList.remove('sidebarOpen');
//     bookmarkSidebar.classList.remove('open');
//   }
  formUserNote['bookId'].value = urlParams['id'];
  formUserNote['pageIndex'].value = PDFViewerApplication.page;
  formUserNote['pageText'].value = document.getElementById('pageNumber').value;
  dialogUserNote.show();
});


btnClose.addEventListener('click', e => {
  dialogUserNote.close();
});

formUserNote.addEventListener('submit', async e => {
  e.preventDefault();

  loadingDialog.show();

  const formData = new FormData(e.target);
  const entries = Object.fromEntries(formData.entries());

  try {
    const f = await fetch(`${url.href}/ebook/bookmark`, {
      method: 'POST',
      body: new URLSearchParams(entries).toString(),
      mode: 'no-cors',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      }
    });

    const j = await f.json();

    loadingDialog.close();

    if(!f.ok) {
      statusDialog.show();
      statusDialog.dataset.error = "error";
      statusMessage.innerHTML = j.message;
      return;
    }

    if(j.message) {
      statusDialog.show();
      statusDialog.dataset.error = j.err_status;
      statusMessage.innerHTML = j.message;
    }


  } catch (error) {
      console.log(error);
      statusDialog.show();
      statusDialog.dataset.error = "error";
      statusMessage.innerHTML = j.message;
  } finally {
    setTimeout(() => {
      statusDialog.close();
      window.location.reload();
    }, 1800)
  }
});

btnCloseBook.addEventListener('click', e => {

		let read_status = 1;
		if(PDFViewerApplication.page == PDFViewerApplication.pdfViewer._pages.length) {
			read_status = 2;
		}
   
		// update read status
		let xhr = new XMLHttpRequest();
		// xhr.open('GET', `${url.href}/ebook/close_book?id=${urlParams['id']}&last_page=${PDFViewerApplication.page}`, false);
		xhr.open('GET', `${url.href}/api/Api_my_ebook/updateReadStatus?ebook_id=${urlParams['my_ebook_id']}&read_status=${read_status}`, false);
		xhr.send();

		// update last page
		xhr = new XMLHttpRequest();
		xhr.open('GET', `${url.href}/api/Api_my_ebook/updateLastPage?ebook_id=${urlParams['my_ebook_id']}&last_page=${PDFViewerApplication.page}`, false);
		xhr.send();
		
		window.parent.location.href = `${url.href}/ebook/close_book?id=${urlParams['id']}&last_page=${PDFViewerApplication.page}`;
});


// need to load when page is ready
PDFViewerApplication.eventBus.on('pagesloaded', e => {
    PDFViewerApplication.page = parseInt(urlParams.lastPage);
});

window.onbeforeunload = function(event) {
  let read_status = 1;
		if(PDFViewerApplication.page == PDFViewerApplication.pdfViewer._pages.length) {
			read_status = 2;
		}
   
		// update read status
		let xhr = new XMLHttpRequest();
		// xhr.open('GET', `${url.href}/ebook/close_book?id=${urlParams['id']}&last_page=${PDFViewerApplication.page}`, false);
		xhr.open('GET', `${url.href}/api/Api_my_ebook/updateReadStatus?ebook_id=${urlParams['my_ebook_id']}&read_status=${read_status}`, false);
		xhr.send();

		// update last page
		xhr = new XMLHttpRequest();
		xhr.open('GET', `${url.href}/api/Api_my_ebook/updateLastPage?ebook_id=${urlParams['my_ebook_id']}&last_page=${PDFViewerApplication.page}`, false);
		xhr.send();

		window.parent.location.href = `${url.href}/ebook/close_book?id=${urlParams['id']}&last_page=${PDFViewerApplication.page}`;
};

document.addEventListener("keydown", function (e) {
    // Cegah Ctrl + Alt + F4
    if (e.ctrlKey && e.altKey && e.key === "F4") {
        e.preventDefault();
        alert("⚠️ Kombinasi Ctrl + Alt + F4 diblokir!");
    }

    // Cegah PrintScreen (PrtSc)
    if (e.key === "PrintScreen") {
        e.preventDefault();
        alert("⚠️ Screenshot dengan PrintScreen diblokir!");
    }

		// Cek kalau user menekan CTRL + S
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === "s") {
        e.preventDefault(); // blokir aksi default (save page)
        alert("Fitur save page diblokir!");
    }

});
