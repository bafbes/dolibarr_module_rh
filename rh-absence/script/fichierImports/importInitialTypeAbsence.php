<?php
/*
 * Script créant et vérifiant que les champs requis s'ajoutent bien
 * 
 */
 	define('INC_FROM_CRON_SCRIPT', true);
	
	require('../../config.php');
	require('../../class/absence.class.php');
	
	$ATMdb=new Tdb;
	$ATMdb->db->debug=true;

	$u=new TRH_TypeAbsence;
	$u->init_db_by_vars($ATMdb);
	global $conf;
	
	/*$this->TTypeAbsenceAdmin = array('rttcumule'=>'RTT cumulé','rttnoncumule'=>'RTT non cumulé', 
		'conges' => 'Absence congés', 'maladiemaintenue' => 'Absence maladie maintenue', 
		'maladienonmaintenue'=>'Absence maladie non maintenue','maternite'=>'Absence maternité', 'paternite'=>'Absence paternité', 
		'chomagepartiel'=>'Absence Chômage partiel','nonremuneree'=>'Absence non rémunérée','accidentdetravail'=>'Absence accident du travail',
		'maladieprofessionnelle'=>'Absence maladie professionnelle', 
		'congeparental'=>'Absence Congés parental', 'accidentdetrajet'=>'Absence Accident trajet',
		'mitempstherapeutique'=>'Absence Mi-temps thérapeutique', 'pathologie'=>'Absence pathologie','mariage'=>'Mariage',
		'deuil'=>'Deuil','naissanceadoption'=>'Naissance ou adoption', 'enfantmalade'=>'Enfant malade', 'demenagement'=>'Déménagement',
		'cours'=>'Cours', 'preavis'=>'Absence préavis','rechercheemploi'=>'Absence recherche emploi', 
		'miseapied'=>'Absence mise à pied', 'nonjustifiee'=>'Absence non justifiée'  
		);
		
		$this->TTypeAbsenceUser = array('rttcumule'=>'RTT cumulé','rttnoncumule'=>'RTT non cumulé', 
		'conges' => 'Absence congés', 'paternite'=>'Absence paternité', 
		'nonremuneree'=>'Absence non rémunérée', 'mariage'=>'Mariage',
		'deuil'=>'Deuil','naissanceadoption'=>'Naissance ou adoption', 'enfantmalade'=>'Enfant malade', 'demenagement'=>'Déménagement',
		 );*/

 	$sql="INSERT INTO ".MAIN_DB_PREFIX."rh_type_absence (rowid,typeAbsence, libelleAbsence, codeAbsence, admin, entity, unite)
	VALUES 
	
	(1,'rttcumule','RTT cumulé','930','0','".$conf->entity."', 'jour')
	,(2,'rttnoncumule','RTT non cumulé','940','0','".$conf->entity."', 'jour')
	,(3,'conges','Absence congés','950','0','".$conf->entity."', 'jour')
	,(4,'paternite','Absence paternité','963','0','".$conf->entity."', 'heure')
	,(5,'nonremuneree','Absence non rémunérée','980','0','".$conf->entity."', 'heure')
	,(6,'mariage','Mariage','2000','0','".$conf->entity."', 'jour')
	,(7,'deuil','Deuil','2010','0','".$conf->entity."', 'jour')
	,(8,'naissanceadoption','Naissance ou adoption','2020','0','".$conf->entity."', 'jour')
	,(9,'enfantmalade','Enfant malade','2030','0','".$conf->entity."', 'jour')
	,(10,'demenagement','Déménagement','2040','0','".$conf->entity."', 'jour')
	
	
	,(11,'maladiemaintenue','Absence maladie maintenue','960','1','".$conf->entity."', 'heure')
	,(12,'maladienonmaintenue','Absence maladie non maintenue','961','1','".$conf->entity."', 'heure')
	,(13,'maternite','Absence maternité','962','1','".$conf->entity."', 'heure')
	,(14,'chomagepartiel','Absence Chômage partiel','970','1','".$conf->entity."', 'heure')
	,(15,'accidentdetravail','Absence accident du travail','990','1','".$conf->entity."', 'heure')
	,(16,'maladieprofessionnelle','Absence maladie professionnelle','1000','1','".$conf->entity."', 'heure')
	,(17,'congeparental','Absence Congés parental','1010','1','".$conf->entity."', 'heure')
	,(18,'accidentdetrajet','Absence Accident trajet','1040','1','".$conf->entity."', 'heure')
	,(19,'mitempstherapeutique','Absence Mi-temps thérapeutique','1070','1','".$conf->entity."', 'heure')
	,(20,'pathologie','Absence pathologie','964','1','".$conf->entity."', 'heure')
	,(21,'cours','Cours','','1','".$conf->entity."', '')
	,(22,'preavis','Absence préavis','1020','1','".$conf->entity."', 'heure')
	,(23,'rechercheemploi','Absence recherche emploi','1030','1','".$conf->entity."', 'heure')
	,(24,'miseapied','Absence mise à pied','1050','1','".$conf->entity."', 'heure')
	,(55,'nonjustifiee','Absence non justifiée','1060','1','".$conf->entity."', 'heure')		
	
	";

	$ATMdb->Execute($sql);

	