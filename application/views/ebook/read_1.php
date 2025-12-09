<!doctype html>
<html lang="en">
<head>
    <base href="<?=base_url()?>"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0">
    <title>Read Book</title>
    <!--<link rel="stylesheet" src="<?=html_escape('assets/node_modules/pdfjs-dist/web/pdf_viewer.css')?>"/>-->
   
    <style>

        *, *::before, *::after {
           box-sizing: border-box;
        }

        html {
            min-height: 100vh;
            width: 100vw;
        }
        body {
            height: 100vh;
            width: 100%;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
            display: block;
            background-color: whitesmoke;
        }

        #main-content {
            display: block;
            margin-left: auto;
            margin-right: auto;
            margin-top: 75px;
            max-width: 80vh;
            background-color: whitesmoke;
        }

        nav {
            position: fixed;
            width: 100%;
            padding: .5rem 1rem;
            top: 0;
            left: 0;
            background-color: rgba(255, 255, 255, .4);
            box-shadow: 0px 1px 3px 0px rgba(0,0,0,0.12), 0px 1px 2px 0px rgba(0,0,0,0.24);
            display: flex;
            flex-wrap: nowrap;
            flex-grow: 1;
            justify-content: start;
            align-items: center;
        }

        button#previous,
        button#next,
        a#last-page {
            padding: .25rem .5rem;
            display: inline-block;
            vertical-align: baseline;
            border-radius: 4px;
            border: none;
            outline: 1px;
            background-color: #21BA45;
            color: white;
            cursor: pointer;
        }

        #page-jumper  {
            margin-left: auto; 
            margin-right: auto;
        }

        #page-jumper > input[type="number"] {
            width: 2.8rem;
            border: 1px solid silver;
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
            margin: 0;
            padding: .25rem;
            outline: none;
        }

        #page-jumper > button {
            border: 1px solid silver;
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
            margin: 0;
            margin-left: -4px;
            padding: .25rem;
            cursor: pointer;
            background-color: #21BA45;
            color: white;
        }

        #current-page {
            margin-left: .25rem;
            margin-right: .25rem;
            padding: .25rem;
            background-color: white;
        }
    </style>
</head>
<body>
<nav>
    <a id="last-page">Kembali</a>
    <span id="page-jumper">
        <input type="number" id="current-page-text">
        <button type="button" id="btn-jump">Lompati</button>
    </span>
    <div style="margin-left: auto">
        <button id="previous">&lt;</button>
        <span id="current-page"></span>
        <button id="next">&gt;</button>
    </div>
</nav>
<div id="main-content">
</div>
    <!-- async -->

    <script src="<?=html_escape('assets/node_modules/pdfjs-dist/build/pdf.min.js')?>"></script>
    <script src="<?=html_escape('assets/node_modules/pdfjs-dist/web/pdf_viewer.js')?>"></script>
    <script defer>
        const main = document.getElementById('main-content'),
              BASE_URL = document.querySelector('base').href,
              currentPageText = document.querySelector('#current-page-text'),
              jumpPage = document.querySelector('#btn-jump');
        let pdf = null,
            cookies = {};

        // get cookies
        Array.from(document.cookie.split(';'), item => {
            const keyVal = item.split('=');
            Object.assign(cookies, {[keyVal[0].trim()]: decodeURIComponent(keyVal[1])});
        });
        const config = JSON.parse(atob(cookies.read_book));
        const expired = new Date(config.expired.split(' ').join('T'));

         // check back button
         window.onhashchange = () => {
            window.localStorage.setItem('load', 'Load At: ' + Date.now());
            //window.location.href =  BASE_URL + 'book/close_book?id=<?=$_GET['id']?>';;
        };
        

        var canvas = document.createElement('canvas');
        
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'assets/node_modules/pdfjs-dist/build/pdf.worker.min.js';

        const pdfLoad = pdfjsLib.getDocument({ 
            url: "<?=html_escape(base_url('assets/files/ebooks/'.$book['file_1']))?>",
            withCredentials: true
        });

        // render pdf by pages
        const PdfPage = numPage => {
            var context = canvas.getContext('2d');

            pdf.getPage(numPage).then(page => {

                var scale = 1;
                var viewport = page.getViewport({scale: scale});

                canvas.height = viewport.height;
                canvas.width = viewport.width;

                var renderContext = {
                    canvasContext: context,
                    viewport: viewport
                };
                var renderTask = page.render(renderContext);

                renderTask.promise.then(function () {
                    
                });
            });
        }

        // render text current page / total pages
        const navPages = (curr, total) => {
            var container = document.getElementById('current-page');
            container.innerText = curr + '/' + total;
            currentPageText.value = curr;
        }

        pdfLoad.promise.then(_pdf => {
            pdf = _pdf;
            numPage = 1;
            PdfPage(1);
            navPages(1, pdf.numPages);

            // set current page to 1
            currentPageText.value = numPage;

            // previous page button
            document.getElementById('previous').addEventListener('click', e => {
                // if page <= 1 then current page = 1
                if(numPage <= 1) 
                {
                    numPage = 1;
                    return;
                }
                numPage--;
                PdfPage(numPage);
                navPages(numPage, pdf.numPages);
                currentPageText.value = numPage;
            });

            // next button
            document.getElementById('next').addEventListener('click', e => {
                    // if page >= total pages then current page = total pages
                if(numPage >= pdf.numPages) 
                {
                    numPage = pdf.numPages;
                    return;
                }
                numPage++;
                PdfPage(numPage);
                navPages(numPage, pdf.numPages);
                
            });

           // jump page
           jumpPage.addEventListener('click', e => {
                numPage = +currentPageText.value;
                PdfPage(numPage);
                navPages(numPage, pdf.numPages);
           });
        });

        // arrow listeer
        window.addEventListener('keydown', e => {
            const event = new Event('click');
            if(e.keyCode === 39)
            {
                document.getElementById('next').dispatchEvent(event);
            }
            if(e.keyCode === 37)
            {
                document.getElementById('previous').dispatchEvent(event);
            }
        });
          
        main.appendChild(canvas);

        // timer
        const idleLogout = () => {
            let time;
            let timeUnit = "<?=$setting['limit_idle_unit']?>";
            let timeValue = <?=$setting['limit_idle_value']?>;
            let seconds = 0;
            // convert to secnds
            switch(timeUnit)
            {
                case 'minutes':
                    seconds = timeValue * 60;
                    break;
                case 'hours':
                    seconds = timeValue * 60 * 60;
                case 'week':
                    seconds = timeValue * 7 * 24 * 60 * 60;
                    break;
            }
            // reset time 
            window.addEventListener('load', resetTimer, true);
            var events = ['mousedown', 'mousemove', 'keypress', 'keydown', 'scroll', 'touchstart'];
            events.forEach(function(name) {
                document.addEventListener(name, resetTimer, true);
            });

            function resetTimer() {
                document.activeElement.focus();
                clearTimeout(time);
                time = setTimeout(() => {
                    window.location.href = BASE_URL + 'ebook/close_book?id=<?=$_GET['id']?>';
                    
                }, 86400);
            }
        }

        window.addEventListener('load', e => { 
            document.activeElement.focus();
            // set current page to 1
           
            // check history
            if (window.history && window.history.pushState)
                window.history.pushState('forward', null, './ebook/read_book?id=<?=$_GET['id']?>');
            // redirect after cookie has expired
            // setTimeout(() => {
            //     window.location.href = BASE_URL + 'ebook/close_book?id=<?=$_GET['id']?>&last-page=' + currentPageText.value;
            // }, (expired - Date.now()));
            // idle time 
            //idleLogout();
        });

        // back to page before
        document.getElementById('last-page').addEventListener('click', e => {
            e.target.href =  window.location.href = BASE_URL + 'ebook/close_book?id=<?=$_GET['id']?>&last-page=' + currentPageText.value;
        });
       
    </script>
</body>
</html>