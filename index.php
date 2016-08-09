<?php include 'konfig.php'; $tanggalsekarang = date("d/m/Y"); ?>
<script type="text/javascript" src="jquery-3.1.0.min.js"></script>

<h2>update jumlah untuk loket itu pas milih loket, atau panggilan di poli ?</h2>
<h3>Valid 1 : bisa dapet nomor loket kalau nomor antrian sudah dipanggil (status = Sedang)</h3>
<h3>Valid 2 : bisa dapet nomor loket kalau nomor antrian tsb belum daftar loket manapun (tujuan = Pendaftaran)</h3>

<audio id="suarabel" src="Airport_Bell.mp3"></audio>
<audio id="suarabelopen" src="opening.mp3"></audio>
<audio id="suarabelend" src="ending.mp3"></audio>
<audio id="suarabelnomorurut" src="rekaman/nomor-urut.wav"></audio>
<audio id="suarabelsuarabelloket" src="rekaman/loket.wav"></audio>
<audio id="belas" src="rekaman/belas.wav"></audio>
<audio id="sebelas" src="rekaman/sebelas.wav"></audio>
<audio id="puluh" src="rekaman/puluh.wav"></audio>
<audio id="sepuluh" src="rekaman/sepuluh.wav"></audio>
<audio id="ratus" src="rekaman/ratus.wav"></audio>
<audio id="seratus" src="rekaman/seratus.wav"></audio>
<audio id="suarabelloket1" src="rekaman/1.wav"></audio>
<audio id="suarabelloket2" src="rekaman/2.wav"></audio>
<audio id="suarabelloket3" src="rekaman/3.wav"></audio>
<audio id="suarabelloket4" src="rekaman/4.wav"></audio>

<?php
	$select = mysql_query("SELECT * FROM antrian WHERE tanggal='$tanggalsekarang' AND kd_status='2' ORDER BY id_antrian DESC LIMIT 1");
	if ($select) $countSelect = mysql_num_rows($select); else $countSelect = 0;
	if ($countSelect > 0) {
		$h = mysql_fetch_array($select);
		$panjang = strlen($h['no_antrian']);
		$nomorantri = $h['no_antrian'];
		for ($i=0; $i < $panjang; $i++) { ?>
			<audio id="suarabel<?php echo $i; ?>" src="rekaman/<?php echo substr($nomorantri,$i,1); ?>.wav"></audio>
<?php 	}
	}
?>

<form action="" method="POST">
	<input type="submit" name="btnDaftar" value="Daftar" /><br /><br />
	<input type="button" name="btnPanggil" value="Panggil" onclick="panggilLoket()" /><br /><br />
	<input type="submit" name="btnPanggilUlang" value="Panggil Ulang" /><br /><br />
	<input type="text" name="no_antri_BP" />
	<input type="submit" name="btnBP" value="BP" /><br /><br />
	<input type="text" name="no_antri_Gigi" />
	<input type="submit" name="btnGigi" value="Gigi" /><br /><br />
	<input type="text" name="no_antri_KIA" />
	<input type="submit" name="btnKIA" value="KIA" /><br /><br />
	<input type="submit" name="btnPanggilBP" value="Panggil BP" /><br /><br />
	<input type="submit" name="btnPanggilUlangBP" value="Panggil Ulang BP" /><br /><br />
	<input type="submit" name="btnPanggilGigi" value="Panggil Gigi" /><br /><br />
	<input type="submit" name="btnPanggilUlangGigi" value="Panggil Ulang Gigi" /><br /><br />
	<input type="submit" name="btnPanggilKIA" value="Panggil KIA" /><br /><br />
	<input type="submit" name="btnPanggilUlangKIA" value="Panggil Ulang KIA" /><br /><br />
</form>


<?php

//=========D A F T A R=========//

if (isset($_POST['btnDaftar'])) {
	$cekantrian = mysql_query("SELECT * FROM antrian WHERE tanggal='$tanggalsekarang' ORDER BY id_antrian DESC LIMIT 1");
	if ($cekantrian) {
		$count = mysql_num_rows($cekantrian);
	} else $count = 0;
	if($count==0){
		mysql_query("INSERT INTO antrian(no_antrian,tanggal,kd_status,kd_tujuan,no_loket) VALUES('1','$tanggalsekarang','1','1','0')");
		header("location:index.php");
	} else {
		$h = mysql_fetch_array($cekantrian);
		$nomorbaru = $h['no_antrian']+1;
		mysql_query("INSERT INTO antrian(no_antrian,tanggal,kd_status,kd_tujuan,no_loket) VALUES('$nomorbaru','$tanggalsekarang','1','1','0')");
		header("location:index.php");
	}
}

//=========P A N G G I L A N   N O M O R   P E N D A F T A R A N=========//

if (isset($_POST['btnPanggil'])) {
	$cekstatus = mysql_query("SELECT * FROM antrian WHERE tanggal='$tanggalsekarang' AND kd_status='2' ORDER BY id_antrian DESC LIMIT 1");
	if ($cekstatus) {
		$count = mysql_num_rows($cekstatus);
	} else $count = 0;
	if($count==0){
		$nomor = 1;
		mysql_query("UPDATE antrian SET kd_status='2' WHERE no_antrian='$nomor' AND tanggal='$tanggalsekarang'");
		mysql_query("INSERT INTO jumlah(kd_tujuan,tanggal,jumlah) VALUES('1','$tanggalsekarang','$nomor')");
	} else {
		$cekjumlah = mysql_query("SELECT * FROM jumlah WHERE kd_tujuan='1' AND tanggal='$tanggalsekarang' ORDER BY id_jumlah DESC LIMIT 1");
		$jum = mysql_fetch_array($cekjumlah);
		$jum_now = $jum['jumlah']+1;
		$i = mysql_fetch_array($cekstatus);
		$nomorsebelum = $i['no_antrian'];
		$nomorsesudah = $i['no_antrian']+1;
		mysql_query("UPDATE antrian SET kd_status='3' WHERE no_antrian='$nomorsebelum' AND tanggal='$tanggalsekarang'");
		mysql_query("UPDATE antrian SET kd_status='2' WHERE no_antrian='$nomorsesudah' AND tanggal='$tanggalsekarang'");
		mysql_query("UPDATE jumlah SET jumlah='$jum_now' WHERE kd_tujuan='1' AND tanggal='$tanggalsekarang'");
	}
}

//=========L O K E T=========//

//kalo milih tombol BP
if(isset($_POST['btnBP'])){
	$no_antrianBP = $_POST['no_antri_BP'];
	$valid1BP = mysql_query("SELECT * FROM antrian WHERE tanggal='$tanggalsekarang' AND kd_status='2' AND no_antrian='$no_antrianBP'");
	if ($valid1BP) $countValid1BP = mysql_num_rows($valid1BP); else $countValid1BP = 0;
	if ($countValid1BP > 0) {
		$valid2BP = mysql_query("SELECT * FROM antrian WHERE tanggal='$tanggalsekarang' AND kd_tujuan='1' AND no_antrian='$no_antrianBP'");
		if ($valid2BP) $countValid2BP = mysql_num_rows($valid2BP); else $countValid2BP = 0;
		if ($countValid2BP > 0) {
			$cekloketBP = mysql_query("SELECT * FROM antrian WHERE tanggal='$tanggalsekarang' AND kd_tujuan='2' ORDER BY id_antrian DESC LIMIT 1");
			if ($cekloketBP) {
				$count = mysql_num_rows($cekloketBP);
			} else $count = 0;
			if($count==0){
				mysql_query("UPDATE antrian SET kd_tujuan='2', no_loket='1' WHERE no_antrian='$no_antrianBP' AND tanggal='$tanggalsekarang'");
			} else {
				$j = mysql_fetch_array($cekloketBP);
				$nomorloketBP = $j['no_loket']+1;
				mysql_query("UPDATE antrian SET kd_tujuan='2', no_loket='$nomorloketBP' WHERE no_antrian='$no_antrianBP' AND tanggal='$tanggalsekarang'");
			}
		} else echo "Nomor Antrian tersebut sudah terdaftar di poli";
	} else echo "Nomor Antrian belum terdaftar, atau belum dipanggil";
	
//kalo milih tombol Gigi
} else if(isset($_POST['btnGigi'])){
	$no_antrianGigi = $_POST['no_antri_Gigi'];
	$valid1Gigi = mysql_query("SELECT * FROM antrian WHERE tanggal='$tanggalsekarang' AND kd_status='2' AND no_antrian='$no_antrianGigi'");
	if ($valid1Gigi) $countValid1Gigi = mysql_num_rows($valid1Gigi); else $countValid1Gigi = 0;
	if ($countValid1Gigi > 0) {
		$valid2Gigi = mysql_query("SELECT * FROM antrian WHERE tanggal='$tanggalsekarang' AND kd_tujuan='1' AND no_antrian='$no_antrianGigi'");
		if ($valid2Gigi) $countValid2Gigi = mysql_num_rows($valid2Gigi); else $countValid2Gigi = 0;
		if ($countValid2Gigi > 0) {
			$cekloketGigi = mysql_query("SELECT * FROM antrian WHERE tanggal='$tanggalsekarang' AND kd_tujuan='3' ORDER BY id_antrian DESC LIMIT 1");
			if ($cekloketGigi) {
				$count = mysql_num_rows($cekloketGigi);
			} else $count = 0;
			if($count==0){
				mysql_query("UPDATE antrian SET kd_tujuan='3', no_loket='1' WHERE no_antrian='$no_antrianGigi' AND tanggal='$tanggalsekarang'");
			} else {
				$j = mysql_fetch_array($cekloketGigi);
				$nomorloketGigi = $j['no_loket']+1;
				mysql_query("UPDATE antrian SET kd_tujuan='3', no_loket='$nomorloketGigi' WHERE no_antrian='$no_antrianGigi' AND tanggal='$tanggalsekarang'");
			}
		} else echo "Nomor Antrian tersebut sudah terdaftar di poli";
	} else echo "Nomor Antrian belum terdaftar, atau belum dipanggil";
	
//kalo milih tombol KIA
} else if(isset($_POST['btnKIA'])){
	$no_antrianKIA = $_POST['no_antri_KIA'];
	$valid1KIA = mysql_query("SELECT * FROM antrian WHERE tanggal='$tanggalsekarang' AND kd_status='2' AND no_antrian='$no_antrianKIA'");
	if ($valid1KIA) $countValid1KIA = mysql_num_rows($valid1KIA); else $countValid1KIA = 0;
	if ($countValid1KIA > 0) {
		$valid2KIA = mysql_query("SELECT * FROM antrian WHERE tanggal='$tanggalsekarang' AND kd_tujuan='1' AND no_antrian='$no_antrianKIA'");
		if ($valid2KIA) $countValid2KIA = mysql_num_rows($valid2KIA); else $countValid2KIA = 0;
		if ($countValid2KIA > 0) {
			$cekloketKIA = mysql_query("SELECT * FROM antrian WHERE tanggal='$tanggalsekarang' AND kd_tujuan='4' ORDER BY id_antrian DESC LIMIT 1");
			if ($cekloketKIA) {
				$count = mysql_num_rows($cekloketKIA);
			} else $count = 0;
			if($count==0){
				mysql_query("UPDATE antrian SET kd_tujuan='4', no_loket='1' WHERE no_antrian='$no_antrianKIA' AND tanggal='$tanggalsekarang'");
			} else {
				$j = mysql_fetch_array($cekloketKIA);
				$nomorloketKIA = $j['no_loket']+1;
				mysql_query("UPDATE antrian SET kd_tujuan='4', no_loket='$nomorloketKIA' WHERE no_antrian='$no_antrianKIA' AND tanggal='$tanggalsekarang'");
			}
		} else echo "Nomor Antrian tersebut sudah terdaftar di poli";
	} else echo "Nomor Antrian belum terdaftar, atau belum dipanggil";
}

//=========P A N G G I L A N   N O M O R   L O K E T=========//

$cekantriBP = mysql_query("SELECT * FROM antrian WHERE kd_tujuan='2' AND tanggal='$tanggalsekarang'");
if ($cekantriBP) $countAntriBP = mysql_num_rows($cekantriBP); else $countAntriBP = 0;
if ($countAntriBP==0) {
	echo "Belum ada yang mendaftar loket BP";
} else {
	$cekjmlBP = mysql_query("SELECT * FROM jumlah WHERE tanggal='$tanggalsekarang' AND kd_tujuan='2'");
	if ($cekjmlBP) $countBP = mysql_num_rows($cekjmlBP); else $countBP = 0;
	if ($cekjmlBP==0) {
		mysql_query("INSERT INTO jumlah(kd_tujuan,tanggal,jumlah) VALUES('2','$tanggalsekarang','1')");
	} else {
		$h = mysql_fetch_array($cekjmlBP);
		$jumlahbaru = $h['jumlah']+1;
		mysql_query("UPDATE jumlah SET jumlah='$jumlahbaru' WHERE tanggal='$tanggalsekarang' AND kd_tujuan='2'");
	}
}

$cekantriGigi = mysql_query("SELECT * FROM antrian WHERE kd_tujuan='3' AND tanggal='$tanggalsekarang'");
if ($cekantriGigi) $countAntriGigi = mysql_num_rows($cekantriGigi); else $countAntriGigi = 0;
if ($countAntriGigi==0) {
	echo "Belum ada yang mendaftar loket Gigi";
} else {
	$cekjmlGigi = mysql_query("SELECT * FROM jumlah WHERE tanggal='$tanggalsekarang' AND kd_tujuan='3'");
	if ($cekjmlGigi) $countGigi = mysql_num_rows($cekjmlGigi); else $countGigi = 0;
	if ($cekjmlGigi==0) {
		mysql_query("INSERT INTO jumlah(kd_tujuan,tanggal,jumlah) VALUES('3','$tanggalsekarang','1')");
	} else {
		$h = mysql_fetch_array($cekjmlGigi);
		$jumlahbaru = $h['jumlah']+1;
		mysql_query("UPDATE jumlah SET jumlah='$jumlahbaru' WHERE tanggal='$tanggalsekarang' AND kd_tujuan='3'");
	}
}

$cekantriKIA = mysql_query("SELECT * FROM antrian WHERE kd_tujuan='4' AND tanggal='$tanggalsekarang'");
if ($cekantriKIA) $countAntriKIA = mysql_num_rows($cekantriKIA); else $countAntriKIA = 0;
if ($countAntriKIA==0) {
	echo "Belum ada yang mendaftar loket KIA";
} else {
	$cekjmlKIA = mysql_query("SELECT * FROM jumlah WHERE tanggal='$tanggalsekarang' AND kd_tujuan='4'");
	if ($cekjmlKIA) $countKIA = mysql_num_rows($cekjmlKIA); else $countKIA = 0;
	if ($cekjmlKIA==0) {
		mysql_query("INSERT INTO jumlah(kd_tujuan,tanggal,jumlah) VALUES('4','$tanggalsekarang','1')");
	} else {
		$h = mysql_fetch_array($cekjmlKIA);
		$jumlahbaru = $h['jumlah']+1;
		mysql_query("UPDATE jumlah SET jumlah='$jumlahbaru' WHERE tanggal='$tanggalsekarang' AND kd_tujuan='4'");
	}
}KIA

<script type="text/javascript">
	function panggilLoket(){
		//MAINKAN SUARA BEL PADA SAAT AWAL
	document.getElementById('suarabelopen').pause();
	document.getElementById('suarabelopen').currentTime=0;
	document.getElementById('suarabelopen').play();
			
	//SET DELAY UNTUK MEMAINKAN REKAMAN NOMOR URUT		
	totalwaktu=document.getElementById('suarabelopen').duration*1000;	

	//MAINKAN SUARA NOMOR URUT		
	setTimeout(function() {
			document.getElementById('suarabelnomorurut').pause();
			document.getElementById('suarabelnomorurut').currentTime=0;
			document.getElementById('suarabelnomorurut').play();
	}, totalwaktu);
	totalwaktu=totalwaktu+2000;
	
	<?php
		//JIKA KURANG DARI 10 MAKA MAIKAN SUARA ANGKA1
		if($nomorantri<10){
	?>
			
			setTimeout(function() {
					document.getElementById('suarabel0').pause();
					document.getElementById('suarabel0').currentTime=0;
					document.getElementById('suarabel0').play();
				}, totalwaktu);
			
			totalwaktu=totalwaktu+1000;
	<?php		
		} else if($nomorantri ==10){
			//JIKA 10 MAKA MAIKAN SUARA SEPULUH
	?>  
				setTimeout(function() {
						document.getElementById('sepuluh').pause();
						document.getElementById('sepuluh').currentTime=0;
						document.getElementById('sepuluh').play();
					}, totalwaktu);
				totalwaktu=totalwaktu+1000;
		<?php		
			} else if($nomorantri ==11){
				//JIKA 11 MAKA MAIKAN SUARA SEBELAS
		?>  
				setTimeout(function() {
						document.getElementById('sebelas').pause();
						document.getElementById('sebelas').currentTime=0;
						document.getElementById('sebelas').play();
					}, totalwaktu);
				totalwaktu=totalwaktu+1000;
		<?php		
			} else if($nomorantri < 20){
				//JIKA 12-20 MAKA MAIKAN SUARA ANGKA2+"BELAS"
		?>  				
				setTimeout(function() {
						document.getElementById('suarabel1').pause();
						document.getElementById('suarabel1').currentTime=0;
						document.getElementById('suarabel1').play();
					}, totalwaktu);
				totalwaktu=totalwaktu+1000;
				setTimeout(function() {
						document.getElementById('belas').pause();
						document.getElementById('belas').currentTime=0;
						document.getElementById('belas').play();
					}, totalwaktu);
				totalwaktu=totalwaktu+1000;
		<?php		
			} else if($nomorantri < 100){				
				//JIKA PULUHAN MAKA MAINKAN SUARA ANGKA1+PULUH+AKNGKA2
		?>  
				setTimeout(function() {
						document.getElementById('suarabel0').pause();
						document.getElementById('suarabel0').currentTime=0;
						document.getElementById('suarabel0').play();
					}, totalwaktu);
				totalwaktu=totalwaktu+1000;
				setTimeout(function() {
						document.getElementById('puluh').pause();
						document.getElementById('puluh').currentTime=0;
						document.getElementById('puluh').play();
					}, totalwaktu);
				totalwaktu=totalwaktu+1000;
				setTimeout(function() {
						document.getElementById('suarabel1').pause();
						document.getElementById('suarabel1').currentTime=0;
						document.getElementById('suarabel1').play();
					}, totalwaktu);
				totalwaktu=totalwaktu+1000;
				
		<?php
			}else{
				//JIKA LEBIH DARI 100 
				//Karena aplikasi ini masih sederhana maka logina konversi hanya sampai 100
				//Selebihnya akan langsung disebutkan angkanya saja 
				//tanpa kata "RATUS", "PULUH", maupun "BELAS"
		?>
		
		<?php 
			for($i=0;$i<$panjang;$i++){
		?>
		
		totalwaktu=totalwaktu+1000;
		setTimeout(function() {
						document.getElementById('suarabel<?php echo $i; ?>').pause();
						document.getElementById('suarabel<?php echo $i; ?>').currentTime=0;
						document.getElementById('suarabel<?php echo $i; ?>').play();
					}, totalwaktu);
		<?php
			}
			}
		?>
		
		
		totalwaktu=totalwaktu+1000;
		setTimeout(function() {
						document.getElementById('suarabelsuarabelloket').pause();
						document.getElementById('suarabelsuarabelloket').currentTime=0;
						document.getElementById('suarabelsuarabelloket').play();
					}, totalwaktu);
		
		totalwaktu=totalwaktu+1000;
		setTimeout(function() {
						document.getElementById('suarabelloket1').pause();
						document.getElementById('suarabelloket1').currentTime=0;
						document.getElementById('suarabelloket1').play();
					}, totalwaktu);

		totalwaktu=totalwaktu+1500;
		setTimeout(function() {
			document.getElementById('suarabelend').pause();
			document.getElementById('suarabelend').currentTime=0;
			document.getElementById('suarabelend').play();
		}, totalwaktu);
	}
</script>