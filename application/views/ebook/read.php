<?php 
    if(!isset($_GET['id'])) 
        die('Buku tidak teridentifikasi');

    $queryString = [
        'file' => html_escape(base_url(str_replace('\\', '/', $folderpath).'/'.str_replace('.pdf', '.wpdf', $book['file_1']))),
        'id'   => $_GET['id'],
        'ebook_id' => $book['id'],
        'lastPage' => $_GET['lastPage'] ?? 1,
        'my_ebook_id' => $my_ebook_id ?? 0
    ];

    $qsBuilder = http_build_query($queryString);
	
	function get_browsername() {
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE){
		$browser = 'Microsoft Internet Explorer';
		}elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE) {
		$browser = 'Google Chrome';
		}elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE) {
		$browser = 'Mozilla Firefox';
		}elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== FALSE) {
		$browser = 'Opera';
		}elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE) {
		$browser = 'Apple Safari';
		}else {
		$browser = 'error'; //<-- Browser not found.
		}
		return $browser;
	}
?>
<!doctype html>
<html lang="en">
<head>
    <base href="<?=base_url()?>"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0">
    <title>Read Book</title>
	<script>
		let hasExtension = false;
	</script>
	<?php if(get_browsername() == 'Google Chrome'): ?>
	<script>
		 var img; 
		  img = new Image(); 
		  img.src = 'chrome-extension://ngpampappnmepgilojfohadhhmbhlaek/_generated_background_page.html'; 
		  img.onload = function() { 
			hasExtension = true; 
		  }; 
		  img.onerror = function() { 
			hasExtension = false; 
		  };
	</script>
	<?php endif; ?>
	
    <script>
       if(hasExtension) {
		   alert('Extensi "Internet Download Manager" tidak di izinkan berjalan ketika membuka ebook');
		   window.location.href = "<?=base_url('Ebook/detail/'.$book['id'])?>";
	   }
    </script>
    <!--<link rel="stylesheet" src="<?=html_escape('assets/node_modules/pdfjs-dist/web/pdf_viewer.css')?>"/>-->
    <link rel="stylesheet" href="<?=base_url('assets/css/bootstrap.min.css')?>">
    <style>
        #page-jumper {
            width: 10rem;
        }
    </style>
</head>
<body class="bg-body-tertiary overflow-hidden mt-0 pt-0">
    <script id="info-script" type="application/json"><?=json_encode($queryString, JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG)?></script>
    <iframe class="w-100 vh-100" id="main-content" src="<?=base_url()?>assets/libs/pdf.js/generic/web/viewer.html"></iframe>
</body>
</html>
