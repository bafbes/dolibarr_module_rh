<?


require('config.php');

if(!empty($_REQUEST['export_csv'])){
	//On récupère  les données sous forme d'un tableau bien comme il faut
	$TRecap=json_decode($_REQUEST['url'],true);
	
	$filename="Export_stats_absence_".date('d-m-Y').".csv";
	
	header("Content-disposition: attachment; filename=$filename");
	header("Content-Type: application/force-download");
	header("Content-Transfer-Encoding: application/octet-stream");
	header("Pragma: no-cache");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0, public");
	header("Expires: 0");
	
	$arraysize=count($TRecap);
	
	print utf8_decode("Trigramme;Nom Prénom;Type absence;Libellé type absence;Durée en jours;Durée en heures;Date de début;Date de fin;\r\n");
	
	for($k=0;$k<$arraysize;$k++){
		
		print $TRecap[$k]['trigramme'].";";
		print utf8_decode($TRecap[$k]['nom']).";";
		print $TRecap[$k]['type_absence'].";";
		print utf8_decode($TRecap[$k]['libelle_absence']).";";
		print $TRecap[$k]['dureeJour'].";";
		print $TRecap[$k]['dureeHeure'].";";
		print $TRecap[$k]['date_debut'].";";
		print $TRecap[$k]['date_fin'].";\r\n";
	}
}