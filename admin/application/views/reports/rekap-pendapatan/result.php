<html>
<head>
<base href="<?=base_url()?>">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!-- Meta, title, CSS, favicons, etc. -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="stylesheet" media="all" type="text/css" href="<?=html_escape('assets/new/css/bootstrap.min.css')?>">
</head>
    <body class="p-0 m-0" style="background-color: white">
        <header class="py-3" style="background-color: white">
            <h3 class="text-capitalize text-center">
						LAPORAN<br />
						REKAP PENDAPATAN KEBUN BINATANG BANDUNG<br />
						Hari , tanggal  :						
						</h3>
        </header>
        <main class="py-3" style="background-color: white">
            <table class="table table-striped" style="width: 100%">
                <thead class="bg-dark text-white">
                    <tr>
											<th class="bg-dark">No</th>
											<th class="bg-dark">Uraian</th>
											<th class="bg-dark">Harga Tiket</th>
											<th class="bg-dark">Jumlah Tiket Terjual</th>
											<th class="bg-dark">Jumlah Harga</th>
                    </tr>
                </thead>
                <tbody> 
										<tr>
												<td style="font-size: 12px;">1</td> 
												<td style="font-size: 12px;">Tiket Masuk Kebun Binatang Bandung</td> 
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;"></td> 
										</tr> 
										<tr>
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;">&nbsp;&nbsp;&nbsp;a. Tunai</td> 
												<td style="font-size: 12px;">Rp. <?php echo number_format($tiket_tunai,0,',','.'); ?></td> 
												<td style="font-size: 12px;"><?php echo number_format($tiket_tunai_jumlah,0,',','.'); ?></td> 
												<td style="font-size: 12px;">Rp. <?php echo number_format(($tiket_tunai*$tiket_tunai_jumlah),0,',','.'); ?></td> 
										</tr> 		
										<tr>
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;">&nbsp;&nbsp;&nbsp;b. QR</td> 
												<td style="font-size: 12px;">Rp. <?php echo number_format($tiket_qr,0,',','.'); ?></td> 
												<td style="font-size: 12px;"><?php echo number_format($tiket_qr_jumlah,0,',','.'); ?></td> 
												<td style="font-size: 12px;">Rp. <?php echo number_format(($tiket_qr*$tiket_qr_jumlah),0,',','.'); ?></td> 
										</tr> 		
										<tr>
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;">&nbsp;&nbsp;&nbsp;c. Debit GPN</td> 
												<td style="font-size: 12px;">Rp. <?php echo number_format($tiket_debit,0,',','.'); ?></td> 
												<td style="font-size: 12px;"><?php echo number_format($tiket_debit_jumlah,0,',','.'); ?></td> 
												<td style="font-size: 12px;">Rp. <?php echo number_format(($tiket_debit*$tiket_debit_jumlah),0,',','.'); ?></td> 
										</tr> 		
										<tr>
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;">&nbsp;&nbsp;&nbsp;d. Tiket Online</td> 
												<td style="font-size: 12px;">Rp. <?php echo number_format($tiket_online,0,',','.'); ?></td> 
												<td style="font-size: 12px;"><?php echo number_format($tiket_online_jumlah,0,',','.'); ?></td> 
												<td style="font-size: 12px;">Rp. <?php echo number_format(($tiket_online*$tiket_online_jumlah),0,',','.'); ?></td> 
										</tr> 				
										<tr>
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;">e. </td> 
												<td style="font-size: 12px;"> </td> 
												<td style="font-size: 12px;"> </td> 
												<td style="font-size: 12px;"> </td> 
										</tr> 												
										<tr>
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;">Subtotal</td> 
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;"></td> 
										</tr> 		
										<tr>
												<td style="font-size: 12px;">2</td> 
												<td style="font-size: 12px;">Tiket Masuk Kebun Binatang Rombongan</td> 
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;"></td> 
										</tr> 
										<tr>
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;">A. Umum/Dewasa</td> 
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;"></td> 
										</tr> 
										<tr>
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;">&nbsp;&nbsp;&nbsp;a. Tunai</td> 
												<td style="font-size: 12px;">Rp. <?php echo number_format($tiket_rombongan_tunai,0,',','.'); ?></td> 
												<td style="font-size: 12px;"><?php echo number_format($tiket_rombongan_tunai_jumlah,0,',','.'); ?></td> 
												<td style="font-size: 12px;">Rp. <?php echo number_format(($tiket_rombongan_tunai*$tiket_rombongan_tunai_jumlah),0,',','.'); ?></td> 
										</tr> 		
										<tr>
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;">&nbsp;&nbsp;&nbsp;b. QR</td> 
												<td style="font-size: 12px;">Rp. <?php echo number_format($tiket_rombongan_qr,0,',','.'); ?></td> 
												<td style="font-size: 12px;"><?php echo number_format($tiket_rombongan_qr_jumlah,0,',','.'); ?></td> 
												<td style="font-size: 12px;">Rp. <?php echo number_format(($tiket_rombongan_qr*$tiket_rombongan_qr_jumlah),0,',','.'); ?></td> 
										</tr> 		
										<tr>
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;">&nbsp;&nbsp;&nbsp;c. Debit GPN</td> 
												<td style="font-size: 12px;">Rp. <?php echo number_format($tiket_rombongan_debit,0,',','.'); ?></td> 
												<td style="font-size: 12px;"><?php echo number_format($tiket_rombongan_debit_jumlah,0,',','.'); ?></td> 
												<td style="font-size: 12px;">Rp. <?php echo number_format(($tiket_rombongan_debit*$tiket_rombongan_debit_jumlah),0,',','.'); ?></td> 
										</tr> 		
										<tr>
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;">B. Anak Sekolah</td> 
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;"></td> 
										</tr> 
										<tr>
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;">&nbsp;&nbsp;&nbsp;a. Tunai</td> 
												<td style="font-size: 12px;">Rp. <?php echo number_format($tiket_sekolah_tunai,0,',','.'); ?></td> 
												<td style="font-size: 12px;"><?php echo number_format($tiket_sekolah_tunai_jumlah,0,',','.'); ?></td> 
												<td style="font-size: 12px;">Rp. <?php echo number_format(($tiket_sekolah_tunai*$tiket_sekolah_tunai_jumlah),0,',','.'); ?></td> 
										</tr> 		
										<tr>
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;">&nbsp;&nbsp;&nbsp;b. QR</td> 
												<td style="font-size: 12px;">Rp. <?php echo number_format($tiket_sekolah_qr,0,',','.'); ?></td> 
												<td style="font-size: 12px;"><?php echo number_format($tiket_sekolah_qr_jumlah,0,',','.'); ?></td> 
												<td style="font-size: 12px;">Rp. <?php echo number_format(($tiket_sekolah_qr*$tiket_sekolah_qr_jumlah),0,',','.'); ?></td> 
										</tr> 		
										<tr>
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;">&nbsp;&nbsp;&nbsp;c. Debit GPN</td> 
												<td style="font-size: 12px;">Rp. <?php echo number_format($tiket_sekolah_debit,0,',','.'); ?></td> 
												<td style="font-size: 12px;"><?php echo number_format($tiket_sekolah_debit_jumlah,0,',','.'); ?></td> 
												<td style="font-size: 12px;">Rp. <?php echo number_format(($tiket_sekolah_debit*$tiket_sekolah_debit_jumlah),0,',','.'); ?></td> 
										</tr> 											
										<tr>
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;">Subtotal</td> 
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;">Rp.</td> 
										</tr> 			
										<?php 
										$w = 3; 
										if(!empty($data_wahana)){
										foreach($data_wahana as $rec){
										?>
										<tr>
												<td style="font-size: 12px;"><?php echo $w; ?></td> 
												<td style="font-size: 12px;"><?php echo $rec->nama; ?></td> 
												<td style="font-size: 12px;"><?php echo $rec->amount; ?></td> 
												<td style="font-size: 12px;"><?php echo $rec->total_transaction; ?></td> 
												<td style="font-size: 12px;">Rp. <?php echo number_format(($rec->amount*$rec->total_transaction),0,',','.'); ?></td> 
										</tr> 
										<?php 
										$w++;
										} 
										?>
										<tr>
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;">Subtotal</td> 
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;"></td> 
										</tr> 		
										<?php 
										}
										if(!empty($data_wahana)){
										foreach($data_bagi_hasil as $rec){
										?>
										<tr>
												<td style="font-size: 12px;"><?php echo $w; ?></td> 
												<td style="font-size: 12px;"><?php echo $rec->nama; ?></td> 
												<td style="font-size: 12px;"><?php echo $rec->amount; ?></td> 
												<td style="font-size: 12px;"><?php echo $rec->total_transaction; ?></td> 
												<td style="font-size: 12px;">Rp. <?php echo number_format(($rec->amount*$rec->total_transaction),0,',','.'); ?></td> 
										</tr> 
										<?php $w++; } ?>
										<tr>
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;">Subtotal</td> 
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;"></td> 
										</tr> 			
										<?php 
										}
										for($d=$w;$d<($w+10);$d++){
										?>
										<tr>
												<td style="font-size: 12px;"><?php echo $d; ?></td> 
												<td style="font-size: 12px;"> </td> 
												<td style="font-size: 12px;"> </td> 
												<td style="font-size: 12px;"> </td> 
												<td style="font-size: 12px;">Rp.  </td> 
										</tr> 
										<?php } ?>
										<tr>
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;">Subtotal</td> 
												<td style="font-size: 12px;">Rp. </td> 
										</tr> 			
										<tr>
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;" colspan="2" >TOTAL PENDAPATAN</td> 
												<td style="font-size: 12px;">Rp. </td> 
										</tr> 		
										<tr>
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;" colspan="2" >QR</td> 
												<td style="font-size: 12px;">Rp. </td> 
										</tr>
										<tr>
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;" colspan="2" >DEBIT GPN</td> 
												<td style="font-size: 12px;">Rp. </td> 
										</tr>  				
										<tr>
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;" colspan="2" >ONLINE</td> 
												<td style="font-size: 12px;">Rp. </td> 
										</tr> 			
										<tr>
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;"></td> 
												<td style="font-size: 12px;" colspan="2" >TOTAL PENDAPATAN TUNAI</td> 
												<td style="font-size: 12px;">Rp. </td> 
										</tr> 										
                </tbody>
            </table>
            <table class="table table-striped" style="width: 100%"> 
							<tr>
								<td>Terbilang</td> 
								<td colspan="3"></td>  
								<td></td> 
							</tr> 
							<tr>
								<td></td> 
								<td>Mengetahui</td> 
								<td></td> 
								<td>Penerima Setoran</td> 
								<td></td> 
							</tr>		
							<tr>
								<td></td> 
								<td>...................</td> 
								<td></td> 
								<td>...................</td> 
								<td></td> 
							</tr>									
						</table>
        </main>
    </body>
</html>
 