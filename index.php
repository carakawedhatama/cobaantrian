<?php include 'konfig.php'; ?>
<h2>mysql LIMIT itu ngambilnya urutan data paling atas atau paling bawah dari tabel ?</h2>


<?php
/*
//=========D A F T A R=========//

$tanggalsekarang = date("d/m/Y");
$cekantrian = mysql_query("SELECT * FROM antrian WHERE tanggal='$tanggalsekarang' ORDER BY id_antrian DESC LIMIT 1");
if(mysql_num_rows($cekantrian==0)){
	mysql_query("INSERT INTO antrian(no_antrian,tanggal,kd_status) VALUES('1','$tanggalsekarang','1')");
	header("location:index.php");
} else {
	$h = mysql_fetch_array($cekantrian);
	$nomorbaru = $h['no_antrian']+1;
	mysql_query("INSERT INTO antrian(no_antrian,tanggal,kd_status) VALUES('$nomorbaru','$tanggalsekarang','1')");
	header("location:index.php");
}

//=========P A N G G I L A N   N O M O R   P E N D A F T A R A N=========//

$cekstatus = mysql_query("SELECT * FROM antrian WHERE tanggal='$tanggalsekarang' AND kd_status='2' ORDER BY id_antrian DESC LIMIT 1");
if(mysql_num_rows($cekstatus)==0){
	mysql_query("UPDATE antrian SET kd_status='2' WHERE no_antrian='nomornya' AND tanggal='$tanggalsekarang'");
} else {
	$i = mysql_fetch_array($cekstatus);
	$nomorsebelum = $i['no_antrian'];
	$nomorsesudah = $i['no_antrian']+1;
	mysql_query("UPDATE antrian SET kd_status='3' WHERE no_antrian='$nomorsebelum' AND tanggal='$tanggalsekarang'");
	mysql_query("UPDATE antrian SET kd_status='2' WHERE no_antrian='$nomorsekarang' AND tanggal='$tanggalsekarang'");
}

//after update antrian, ++jumlah di tabel jumlah yg kd_status = 2 atau 3 ??

//=========L O K E T=========//

//kalo milih tombol BP
if($_POST['btnBP']){
	$cekloket = mysql_query("SELECT * FROM antrian WHERE tanggal='$tanggalsekarang' AND kd_tujuan='2' ORDER BY id_antrian DESC LIMIT 1");
	if(mysql_num_rows($cekloket)==0){
		mysql_query("UPDATE antrian SET kd_tujuan='2', no_loket='1' WHERE no_antrian='nomornya' AND tanggal='$tanggalsekarang'");
	} else {
		$j = mysql_fetch_array($cekloket);
		$nomorloket = $j['no_loket']+1;
		mysql_query("UPDATE antrian SET kd_tujuan='2', no_loket='$nomorloket' WHERE no_antrian='nomornya' AND tanggal='$tanggalsekarang'");
	}
//kalo milih tombol Gigi
} else if($_POST['btnGigi']){
	$cekloket = mysql_query("SELECT * FROM antrian WHERE tanggal='$tanggalsekarang' AND kd_tujuan='3' ORDER BY id_antrian DESC LIMIT 1");
	if(mysql_num_rows($cekloket)==0){
		mysql_query("UPDATE antrian SET kd_tujuan='3', no_loket='1' WHERE no_antrian='nomornya' AND tanggal='$tanggalsekarang'");
	} else {
		$j = mysql_fetch_array($cekloket);
		$nomorloket = $j['no_loket']+1;
		mysql_query("UPDATE antrian SET kd_tujuan='3', no_loket='$nomorloket' WHERE no_antrian='nomornya' AND tanggal='$tanggalsekarang'");
	}
//kalo milih tombol KIA
} else if($_POST['btnKIA']){
	$cekloket = mysql_query("SELECT * FROM antrian WHERE tanggal='$tanggalsekarang' AND kd_tujuan='4' ORDER BY id_antrian DESC LIMIT 1");
	if(mysql_num_rows($cekloket)==0){
		mysql_query("UPDATE antrian SET kd_tujuan='4', no_loket='1' WHERE no_antrian='nomornya' AND tanggal='$tanggalsekarang'");
	} else {
		$j = mysql_fetch_array($cekloket);
		$nomorloket = $j['no_loket']+1;
		mysql_query("UPDATE antrian SET kd_tujuan='4', no_loket='$nomorloket' WHERE no_antrian='nomornya' AND tanggal='$tanggalsekarang'");
	}
}

//=========P A N G G I L A N   N O M O R   L O K E T=========//

//update jumlah

*/
?>