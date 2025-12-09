<?php
$dbconn = pg_connect("host=127.0.0.1 port=5432 dbname=woowedu user=postgres password=woow3du##");
if($dbconn)
echo 'suksess';
else
echo 'gagal';
?>