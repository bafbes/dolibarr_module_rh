<?php
	require('config.php');
	require('./class/competence.class.php');
		require('./lib/competence.lib.php');
	
	$langs->load('competence@competence');
	$langs->load("users");
	
	$ATMdb=new Tdb;
	$lignecv=new TRH_ligne_cv;
	$tagCompetence=new TRH_competence_cv;

	
	if(isset($_REQUEST['action'])) {
		switch($_REQUEST['action']) {
			
			case 'view':
				
				break;
			case 'edit':
				
				break;
		}
	}
	else if(isset($_REQUEST['valider'])){
		_ficheResult($ATMdb,$tagCompetence, 'edit');
	}
	else{
		_fiche($ATMdb,$tagCompetence, 'edit');
	}
	
	$ATMdb->close();
	
	llxFooter();
	
	

function _fiche(&$ATMdb, $tagCompetence,  $mode) {
	global $db,$user, $langs, $conf;
	llxHeader('','Formations');
	
	print dol_get_fiche_head(competencePrepareHead($tagCompetence, '')  , '', 'Statistiques');
	
	$form=new TFormCore($_SERVER['PHP_SELF'],'form1','POST');
	$form->Set_typeaff($mode);
	echo $form->hidden('fk_user', $user->id);
	echo $form->hidden('entity', $conf->entity);
	$fuser = new User($db);
	$fuser->fetch(isset($_REQUEST['fk_user']) ? $_REQUEST['fk_user'] : $user->id);
	$fuser->getrights();
	
	$idTagRecherche=isset($_REQUEST['libelle']) ? $_REQUEST['libelle'] : 0;
	$idGroupeRecherche=isset($_REQUEST['groupe']) ? $_REQUEST['groupe'] : 0;
	$idUserRecherche=isset($_REQUEST['user']) ? $_REQUEST['user'] : 0;

	//tableau pour la combobox des tags de compétences
	$sql="SELECT c.rowid, c.libelleCompetence FROM ".MAIN_DB_PREFIX."rh_competence_cv as c
	WHERE c.entity=".$conf->entity;
	$ATMdb->Execute($sql);
	$TTagCompetence=array();
	$TTagCompetence[0]='Tous';
	while($ATMdb->Get_line()) {
		$TTagCompetence[$ATMdb->Get_field('rowid')]=$ATMdb->Get_field('libelleCompetence');
	}
	
	
	//tableau pour la combobox des groupes
	$TGroupe  = array();
	$TGroupe[0]='Tous';
	$sqlReq="SELECT rowid, nom FROM ".MAIN_DB_PREFIX."usergroup WHERE entity=".$conf->entity;
	$ATMdb->Execute($sqlReq);
	while($ATMdb->Get_line()) {
		$TGroupe[$ATMdb->Get_field('rowid')] = htmlentities($ATMdb->Get_field('nom'), ENT_COMPAT , 'ISO8859-1');
	}
	
	//tableau pour la combobox des utilisateurs
	$TUser=array();
	$TUser[0]='Tous';
	$sqlReqUser="SELECT u.rowid, u.name,  u.firstname FROM `".MAIN_DB_PREFIX."user` as u, ".MAIN_DB_PREFIX."usergroup_user as g
	 WHERE u.entity=".$conf->entity;
	if($idGroupeRecherche!=0){
		$sqlReqUser.=" AND g.fk_user=u.rowid AND g.fk_usergroup=".$idGroupeRecherche;
	}
	$ATMdb->Execute($sqlReqUser);
	while($ATMdb->Get_line()) {
		$TUser[$ATMdb->Get_field('rowid')]=htmlentities($ATMdb->Get_field('firstname'), ENT_COMPAT , 'ISO8859-1')." ".htmlentities($ATMdb->Get_field('name'), ENT_COMPAT , 'ISO8859-1');
	}
	
	
	
	$TBS=new TTemplateTBS();
	print $TBS->render('./tpl/statCompetence.tpl.php'
		,array(
			
		)
		,array(
			'competence'=>array(
				'Tlibelle'=>$form->combo('','libelle',$TTagCompetence,$idTagRecherche)
				,'TGroupe'=>$form->combo('','groupe',$TGroupe,$idGroupeRecherche)
				,'TUser'=>$form->combo('','user',$TUser,$idUserRecherche)
				,'btValider'=>$form->btsubmit('Valider', 'valider')
			)
			,'userCourant'=>array(
				'id'=>$fuser->id
				,'nom'=>$fuser->lastname
				,'prenom'=>$fuser->firstname
				,'droitRecherche'=>$user->rights->curriculumvitae->myactions->rechercheProfil?1:0
			)
			,'view'=>array(
				'mode'=>$mode
				,'head'=>dol_get_fiche_head(competencePrepareHead($tagCompetence, '')  , '', 'Compétences')
			)
		)	
	);
	
	echo $form->end_form();
	
	global $mesg, $error;
	dol_htmloutput_mesg($mesg, '', ($error ? 'error' : 'ok'));
	llxFooter();
}


function _ficheResult(&$ATMdb, $tagCompetence,  $mode) {
	global $db,$user, $langs, $conf;
	llxHeader('','Formations');
	
	print dol_get_fiche_head(competencePrepareHead($tagCompetence, '')  , '', 'Statistiques');
	
	$form=new TFormCore($_SERVER['PHP_SELF'],'form1','POST');
	$form->Set_typeaff($mode);
	echo $form->hidden('fk_user', $user->id);
	echo $form->hidden('entity', $conf->entity);
	$fuser = new User($db);
	$fuser->fetch(isset($_REQUEST['fk_user']) ? $_REQUEST['fk_user'] : $user->id);
	$fuser->getrights();
	
	
	$idTagRecherche=isset($_REQUEST['libelle']) ? $_REQUEST['libelle'] : 0;
	$idGroupeRecherche=isset($_REQUEST['groupe']) ? $_REQUEST['groupe'] : 0;
	$idUserRecherche=isset($_REQUEST['user']) ? $_REQUEST['user'] : 0;
	
	if($idGroupeRecherche!=0){	//on recherche le nom du groupe
		//echo $idGroupeRecherche;exit;
		$sql="SELECT nom FROM ".MAIN_DB_PREFIX."usergroup
		WHERE rowid =".$idGroupeRecherche." AND entity=".$conf->entity;
		$ATMdb->Execute($sql);
		while($ATMdb->Get_line()) {
			$nomGroupeRecherche=$ATMdb->Get_field('nom');
		}
	}else{
		$nomGroupeRecherche='Tous';
	}

	
	if($idTagRecherche!=0){	//on recherche le nom du tag
		$sql="SELECT libelleCompetence FROM ".MAIN_DB_PREFIX."rh_competence_cv
		WHERE rowid =".$idTagRecherche." AND entity=".$conf->entity;
		$ATMdb->Execute($sql);
		while($ATMdb->Get_line()) {
			$nomTagRecherche=$ATMdb->Get_field('libelleCompetence');
		}
	}else{
		$nomTagRecherche='Tous';
	}

	
	if($idUserRecherche!=0){	//on recherche le nom de l'utilisateur
		$sql="SELECT name,  firstname FROM ".MAIN_DB_PREFIX."user
		WHERE rowid =".$idUserRecherche." AND entity=".$conf->entity;
		$ATMdb->Execute($sql);
		while($ATMdb->Get_line()) {
			$nomUserRecherche=htmlentities($ATMdb->Get_field('firstname'), ENT_COMPAT , 'ISO8859-1')." ".htmlentities($ATMdb->Get_field('name'), ENT_COMPAT , 'ISO8859-1');
		}
	}else{
		$nomUserRecherche='Tous';
	}
	
	//on va obtenir un tableau permettant d'avoir les stats des compétences suivant la recherche
	$requeteRecherche=$tagCompetence->requeteStatistique($ATMdb, $idGroupeRecherche, $idTagRecherche, $idUserRecherche);


	$TBS=new TTemplateTBS();
	print $TBS->render('./tpl/statCompetenceResult.tpl.php'
		,array(
		)
		,array(
			'demande'=>array(
				'idTagRecherche'=>$idTagRecherche
				,'idGroupeRecherche'=>$idGroupeRecherche
				,'idUserRecherche'=>$idUserRecherche
				,'nomTagRecherche'=>$nomTagRecherche
				,'nomGroupeRecherche'=>$nomGroupeRecherche
				,'nomUserRecherche'=>$nomUserRecherche
			)
			,'resultat'=>array(
				'total'=>$requeteRecherche['nbUser']
				,'faible'=>$requeteRecherche['nbUserFaible']*100/$requeteRecherche['nbUser']
				,'moyen'=>$requeteRecherche['nbUserMoyen']*100/$requeteRecherche['nbUser']
				,'bon'=>$requeteRecherche['nbUserBon']*100/$requeteRecherche['nbUser']
				,'excellent'=>$requeteRecherche['nbUserExcellent']*100/$requeteRecherche['nbUser']
			)
			,'userCourant'=>array(
				'id'=>$fuser->id
				,'nom'=>$fuser->lastname
				,'prenom'=>$fuser->firstname
			)
			,'view'=>array(
				'mode'=>$mode
				,'head'=>dol_get_fiche_head(competencePrepareHead($tagCompetence, '')  , '', 'Compétences')
			)
		)	
	);

	echo $form->end_form();
	
	global $mesg, $error;
	dol_htmloutput_mesg($mesg, '', ($error ? 'error' : 'ok'));
	llxFooter();
}
