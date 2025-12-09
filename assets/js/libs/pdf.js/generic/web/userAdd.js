import { PDFViewerApplication, PDFViewerApplicationOptions }  from './viewer.js';
const configElement = PDFViewerApplication.appConfig,
      statusDialog = document.getElementById('successStatusDialog'),
      loadingDialog = document.getElementById('saveToServerLoadingDialog'),
      statusMessage = document.getElementById('statusMessage'),
      bookmarkPage = document.getElementById('bookmarkPage'),
      dialogUserNote = document.getElementById('userNote'),
      uploadToServer = document.getElementById('saveToServer'),
      btnClose = document.getElementsByClassName('btn-close')[0],
      btnCloseBook = document.getElementById('closeBookButton'),
      bookmarkSidebar = document.getElementById('sidebarBookmark'),
      formUserNote = document.forms['frm-bookmarkPage'];

const url = new URL(window.location.href);
const urlParams = Object.fromEntries(url.searchParams.entries());
const pdfFile = urlParams['file'].split('/');

uploadToServer.addEventListener('click', async e => {
  const opt = {
    book: urlParams['id'],
    serverUrl: `${url.origin}/ebook/save_book`,
    filename: urlParams['file']
  }
  await PDFViewerApplication.saveToServer(opt);
 
});

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
    const f = await fetch(`${url.origin}/ebook/bookmark`, {
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
    window.parent.location.href = `${url.origin}/ebook/close_book?id=${urlParams['id']}&last_page=${PDFViewerApplication.page}`;
});


// need to load when page is ready
PDFViewerApplication.eventBus.on('pagesloaded', e => {
    PDFViewerApplication.page = parseInt(urlParams.lastPage);
});