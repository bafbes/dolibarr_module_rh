<?php

require('../config.php');

//Interface qui renvoie les congés maladie (maintenue ou non) et les jours ancienneté acquis du collaborateur souhaité durant la période demandée
global $user,$conf;

$ATMdb=new Tdb;

$get = isset($_REQUEST['get'])?$_REQUEST['get']:'';

_get($get);

function _get($case) {
	switch ($case) {
		case 'jour_anciennete':
			__out(_jourAnciennete($_REQUEST['id']));	
			break;
		case 'maladie_maintenue':
			__out(_dureeMaladieMaintenue($_REQUEST['id'], $REQUEST['date_debut'], $REQUEST['date_fin']));	
			break;
		case 'maladie_non_maintenue':
			__out(_dureeMaladieNonMaintenue($_REQUEST['id'], $REQUEST['date_debut'], $REQUEST['date_fin']));	
			break;
	}
}


function _jourAnciennete($userId){
	$sql="SELECT a.acquisAncienneteNM1 
	FROM ".MAIN_DB_PREFIX."rh_compteur as a 
	WHERE a.entity=".$conf->entity."
	AND a.fk_user=".$userId;
	
	$ATMdb->Execute($sql);
	while($ATMdb->Get_line()) {
		$TabRecapConges[$userId]=$ATMdb->Get_field('acquisAncienneteNM1');
	}
}

function _dureeMaladieMaintenue($userId, $date_debut, $date_fin){
	
	$TabRecapMaladie=array();
		
	$sql="SELECT u.name, u.firstname, a.type, a.date_debut, a.date_fin, a.duree 
	FROM ".MAIN_DB_PREFIX."user as u, ".MAIN_DB_PREFIX."rh_absence as a 
	WHERE u.rowid=a.fk_user 
	AND a.type LIKE 'maladiemaintenue'
	AND a.entity=".$conf->entity."
	AND a.fk_user=".$userId."
	AND (a.date_debut>'".$date_debut."' AND a.date_fin<'".$date_fin."')";
	
	$ATMdb->Execute($sql);
	while($ATMdb->Get_line()) {
		$TabRecapMaladie[$userId]['maladiemaintenue']=$TabRecapMaladie[$user]['maladiemaintenue']+$ATMdb->Get_field('duree');
	}
	
	return $TabRecapMaladie;
}

function _dureeMaladieNonMaintenue($userId, $date_debut, $date_fin){
	
	$TabRecapMaladie=array();
		
	$sql="SELECT u.name, u.firstname, a.type, a.date_debut, a.date_fin, a.duree 
	FROM ".MAIN_DB_PREFIX."user as u, ".MAIN_DB_PREFIX."rh_absence as a 
	WHERE u.rowid=a.fk_user 
	AND a.type LIKE 'maladienonmaintenue'
	AND a.entity=".$conf->entity."
	AND a.fk_user=".$userId."
	AND (a.date_debut>'".$date_debut."' AND a.date_fin<'".$date_fin."')";
	
	$ATMdb->Execute($sql);
	while($ATMdb->Get_line()) {
		$TabRecapMaladie[$userId]['maladienonmaintenue']=$TabRecapMaladie[$user]['maladienonmaintenue']+$ATMdb->Get_field('duree');
	}
	
	return $TabRecapMaladie;
}