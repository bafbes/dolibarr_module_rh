<?php

require('../config.php');

//Renvoie les congés maladie du collaborateur de l'année précédente
global $user,$conf;

$ATMdb=new Tdb;
	
$anneeCourante=date('Y');
$anneePrec=$anneeCourante-1;

$debutAnnee=$anneePrec.'-06-01 00:00:00';
$finAnnee=$anneeCourante.'-05-31 00:00:00';

$TUserID=array();
$sqlReqUser="SELECT rowid, name,  firstname FROM `".MAIN_DB_PREFIX."user` WHERE entity=".$conf->entity;
$ATMdb->Execute($sqlReqUser);

while($ATMdb->Get_line()) {
	$TUserID[]=$ATMdb->Get_field('rowid');
}

$TabRecapMaladie=array();
foreach($TUserID as $user){
	
	$sql="SELECT u.name, u.firstname, a.type, a.date_debut, a.date_fin, a.duree 
	FROM ".MAIN_DB_PREFIX."user as u, ".MAIN_DB_PREFIX."rh_absence as a 
	WHERE u.rowid=a.fk_user 
	AND a.type LIKE 'maladiemaintenue'
	AND a.entity=".$conf->entity."
	AND a.fk_user=".$user."
	AND (a.date_debut>'".$debutAnnee."' AND a.date_fin<'".$finAnnee."')";
	
	$ATMdb->Execute($sql);
	while($ATMdb->Get_line()) {
		$TabRecapMaladie['maladiemaintenue'][$user]=$TabRecap[$user]+$ATMdb->Get_field('duree');
	}
}

foreach($TUserID as $user){
	
	$sql="SELECT u.name, u.firstname, a.type, a.date_debut, a.date_fin, a.duree 
	FROM ".MAIN_DB_PREFIX."user as u, ".MAIN_DB_PREFIX."rh_absence as a 
	WHERE u.rowid=a.fk_user 
	AND a.type LIKE 'maladienonmaintenue'
	AND a.entity=".$conf->entity."
	AND a.fk_user=".$user."
	AND (a.date_debut>'".$debutAnnee."' AND a.date_fin<'".$finAnnee."')";
	
	$ATMdb->Execute($sql);
	while($ATMdb->Get_line()) {
		$TabRecapMaladie[$user]['maladienonmaintenue']=$TabRecap[$user]+$ATMdb->Get_field('duree');
	}
}
//print_r($TabRecap);
//echo "<br/>";
__out($TabRecapMaladie);
