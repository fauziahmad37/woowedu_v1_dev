<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e66465, #9198e5);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        .container {
            max-width: 500px;
        }

        h1 {
            font-size: 120px;
            margin: 0;
        }

        h2 {
            font-size: 28px;
            margin-top: 10px;
        }

        p {
            font-size: 16px;
            margin: 20px 0;
        }

        button {
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            background-color: #333;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #555;
        }

        @media (max-width: 600px) {
            h1 {
                font-size: 80px;
            }

            h2 {
                font-size: 22px;
            }

            p {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>404</h1>
        <h2>Halaman / File Tidak Ditemukan</h2>
        <p>Maaf, halaman / file yang Anda cari mungkin telah dipindahkan atau tidak tersedia.</p>
        <button class="back">Kembali</button>
    </div>

	<script>
		document.querySelector('.back').addEventListener('click', function(event) {
			event.preventDefault();

			console.log('Kembali ke halaman sebelumnya');

			// kembali ke halaman sebelumnya
			if (document.referrer) {
				window.location.href = document.referrer;
			} else {
				window.location.href = '<?= base_url() ?>'; // atau ganti dengan URL yang diinginkan
			}
		});
	</script>
</body>
</html>
