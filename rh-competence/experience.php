<?php
	require('config.php');
	require('./class/competence.class.php');
	
	require_once DOL_DOCUMENT_ROOT.'/core/lib/usergroups.lib.php';
	require_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';
	require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';
	
	$langs->load('competence@competence');
	$langs->load("users");
	
	$ATMdb=new Tdb;
	$lignecv=new TRH_ligne_cv;
	$formation=new TRH_formation_cv;
	$tagCompetence=new TRH_competence_cv;

	if(isset($_REQUEST['action'])) {
		switch($_REQUEST['action']) {
			case 'add':
			case 'newlignecv':
				//$ATMdb->db->debug=true;
				$lignecv->load($ATMdb, $_REQUEST['id']);
				$lignecv->set_values($_REQUEST);
				_ficheCV($ATMdb, $lignecv, 'edit');
				break;
			case 'newformationcv':
				//$ATMdb->db->debug=true;
				$formation->load($ATMdb, $_REQUEST['id']);
				$formation->set_values($_REQUEST);
				_ficheFormation($ATMdb, $formation, $tagCompetence, 'edit');
				break;		
				
			case 'savecv':
				
				$lignecv->load($ATMdb, $_REQUEST['id']);
				$lignecv->set_values($_REQUEST);
				$mesg = '<div class="ok">Ligne de CV ajoutée</div>';
				$mode = 'view';

				$lignecv->save($ATMdb);
				_liste($ATMdb, $lignecv , $formation);
				//_ficheCV($ATMdb, $lignecv,$mode);
				break;
				
			case 'saveformation':
				
				$formation->load($ATMdb, $_REQUEST['id']);
				$formation->set_values($_REQUEST);
				$mesg = '<div class="ok">Nouvelle formation ajoutée</div>';
				$mode = 'view';

				$formation->save($ATMdb);
				_liste($ATMdb, $lignecv , $formation);
				//_ficheCV($ATMdb, $competence,$mode);
				break;
			
			case 'viewCV':
				$lignecv->load($ATMdb, $_REQUEST['id']);
				_ficheCV($ATMdb, $lignecv, 'view');
				break;
			case 'viewFormation':
				$formation->load($ATMdb, $_REQUEST['id']);
				_ficheFormation($ATMdb, $formation, $tagCompetence,'view');
				break;
				
			case 'deleteCV':
				//$ATMdb->db->debug=true;
				$lignecv->load($ATMdb, $_REQUEST['id']);
				$lignecv->delete($ATMdb, $_REQUEST['id']);
				$mesg = '<div class="ok">La ligne de CV a bien été supprimée</div>';
				$mode = 'edit';
				_liste($ATMdb, $lignecv , $formation);
				break;
				
			case 'deleteFormation':
				//$ATMdb->db->debug=true;
				$formation->load($ATMdb, $_REQUEST['id']);
				$formation->delete($ATMdb, $_REQUEST['id']);
				$mesg = '<div class="ok">La ligne de compétence a bien été supprimée</div>';
				$mode = 'edit';
				_liste($ATMdb, $lignecv , $formation);
				break;
		}
	}
	elseif(isset($_REQUEST['id'])) {
		$lignecv->load($ATMdb, $_REQUEST['id']);
		$formation->load($ATMdb, $_REQUEST['id']);
		_liste($ATMdb, $lignecv, $formation);	
	}
	else {
		//$ATMdb->db->debug=true;
		$lignecv->load($ATMdb, $_REQUEST['id']);
		$formation->load($ATMdb, $_REQUEST['id']);
		_liste($ATMdb, $lignecv, $formation);
	}
	
	$ATMdb->close();
	
	llxFooter();
	
	
function _liste(&$ATMdb, $lignecv, $formation ) {
	global $langs, $conf, $db, $user;	
	llxHeader('','Liste de vos expériences');
	
	$fuser = new User($db);
	$fuser->fetch($_REQUEST['fk_user']);
	$fuser->getrights();

	$head = user_prepare_head($fuser);
	dol_fiche_head($head, 'competence', $langs->trans('Utilisateur'),0, 'user');
	
	////////////AFFICHAGE DES LIGNES DE CV 
	$r = new TSSRenderControler($lignecv);
	$sql="SELECT rowid as 'ID', date_cre as 'DateCre', 
			  date_debut, date_fin, libelleExperience, descriptionExperience,lieuExperience, fk_user, '' as 'Supprimer'
		FROM   llx_rh_ligne_cv
		WHERE fk_user=".$user->id." AND entity=".$conf->entity;

	$TOrder = array('ID'=>'DESC');
	if(isset($_REQUEST['orderDown']))$TOrder = array($_REQUEST['orderDown']=>'DESC');
	if(isset($_REQUEST['orderUp']))$TOrder = array($_REQUEST['orderUp']=>'ASC');
				
	$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;			
	//print $page;
	$form=new TFormCore($_SERVER['PHP_SELF'],'formtranslateList','GET');
	
	$r->liste($ATMdb, $sql, array(
		'limit'=>array(
			'page'=>$page
			,'nbLine'=>'30'
		)
		,'link'=>array(
			'libelleExperience'=>'<a href="?id=@ID@&action=viewCV">@val@</a>'
			,'Supprimer'=>'<a href="?id=@ID@&action=deleteCV&fk_user='.$fuser->id.'"><img src="./img/delete.png"></a>'
		)
		,'translate'=>array(
		)
		,'hide'=>array('DateCre', 'fk_user')
		,'type'=>array('date_debut'=>'date', 'date_fin'=>'date')
		,'liste'=>array(
			'titre'=>'LISTE DE VOS EXPERIENCES PROFESSIONNELLES'
			,'image'=>img_picto('','title.png', '', 0)
			,'picto_precedent'=>img_picto('','back.png', '', 0)
			,'picto_suivant'=>img_picto('','next.png', '', 0)
			,'noheader'=> (int)isset($_REQUEST['socid'])
			,'messageNothing'=>"Aucune expérience professionnelle"
			,'order_down'=>img_picto('','1downarrow.png', '', 0)
			,'order_up'=>img_picto('','1uparrow.png', '', 0)
			,'picto_search'=>'<img src="../../theme/rh/img/search.png">'
			
		)
		,'title'=>array(
			'date_debut'=>'Date début'
			,'date_fin'=>'Date Fin'
			,'libelleExperience'=>'Libellé Expérience'
			,'descriptionExperience'=>'Description Expérience'
			,'lieuExperience'=>'Lieu'
		)
		,'search'=>array(
			'date_debut'=>array('recherche'=>'calendar')
			
		)
		,'orderBy'=>$TOrder
		
	));

		?>
		<a class="butAction" href="?id=<?=$lignecv->getId()?>&action=newlignecv&fk_user=<?=$fuser->id?>">Ajouter une expérience</a><div style="clear:both"></div>
		<br/><br/><br/><br/><br/>
		<?
	$form->end();
	
	
	////////////AFFICHAGE DES  FORMATIONS
	$r = new TSSRenderControler($formation);
	$sql="SELECT rowid as 'ID', date_cre as 'DateCre', 
			  date_debut, date_fin, libelleFormation, competenceFormation, commentaireFormation,lieuFormation, fk_user, '' as 'Supprimer'
		FROM   llx_rh_formation_cv
		WHERE fk_user=".$user->id." AND entity=".$conf->entity;

	$TOrder = array('ID'=>'DESC');
	if(isset($_REQUEST['orderDown']))$TOrder = array($_REQUEST['orderDown']=>'DESC');
	if(isset($_REQUEST['orderUp']))$TOrder = array($_REQUEST['orderUp']=>'ASC');
				
	$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;			
	//print $page;
	$form=new TFormCore($_SERVER['PHP_SELF'],'formtranslateList','GET');
	
	$r->liste($ATMdb, $sql, array(
		'limit'=>array(
			'page'=>$page
			,'nbLine'=>'30'
		)
		,'link'=>array(
			'libelleFormation'=>'<a href="?id=@ID@&action=viewFormation">@val@</a>'
			,'Supprimer'=>'<a href="?id=@ID@&action=deleteFormation&fk_user='.$fuser->id.'"><img src="./img/delete.png"></a>'
		)
		,'translate'=>array(
		)
		,'hide'=>array('DateCre','fk_user', 'commentaireFormation')
		,'type'=>array('date_debut'=>'date', 'date_fin'=>'date')
		,'liste'=>array(
			'titre'=>'LISTE DE VOS FORMATIONS'
			,'image'=>img_picto('','title.png', '', 0)
			,'picto_precedent'=>img_picto('','back.png', '', 0)
			,'picto_suivant'=>img_picto('','next.png', '', 0)
			,'noheader'=> (int)isset($_REQUEST['socid'])
			,'messageNothing'=>"Aucune formation suivie"
			,'order_down'=>img_picto('','1downarrow.png', '', 0)
			,'order_up'=>img_picto('','1uparrow.png', '', 0)
			,'picto_search'=>'<img src="../../theme/rh/img/search.png">'
			
		)
		,'title'=>array(
			'date_debut'=>'Date début'
			,'date_fin'=>'Date Fin'
			,'libelleFormation'=>'Libellé Formation'
			,'competenceFormation'=>'Compétences'
			,'commentaireFormation'=>'Commentaires'
			,'lieuFormation'=>'Lieu'
		)
		,'search'=>array(
			'date_debut'=>array('recherche'=>'calendar')
			
		)
		,'orderBy'=>$TOrder
		
	));
	?>
		<a class="butAction" href="?id=<?=$formation->getId()?>&action=newformationcv&fk_user=<?=$fuser->id?>">Ajouter une formation</a><div style="clear:both"></div>
	<?
	llxFooter();
}	

	
function _ficheCV(&$ATMdb, $lignecv,  $mode) {
	global $db,$user,$langs,$conf;
	llxHeader('','Expériences professionnelles');
	
	$fuser = new User($db);
	$fuser->fetch(isset($_REQUEST['fk_user']) ? $_REQUEST['fk_user'] : $formation->fk_user);
	$fuser->getrights();
	
	$head = user_prepare_head($fuser);
	$current_head = 'competence';
	dol_fiche_head($head, $current_head, $langs->trans('Utilisateur'),0, 'user');
	
	$form=new TFormCore($_SERVER['PHP_SELF'],'form1','POST');
	$form->Set_typeaff($mode);
	echo $form->hidden('id', $lignecv->getId());
	echo $form->hidden('fk_user', $user->id);
	echo $form->hidden('entity', $conf->entity);
	echo $form->hidden('action', 'savecv');

	
	$TBS=new TTemplateTBS();
	print $TBS->render('./tpl/cv.tpl.php'
		,array(
		)
		,array(
			'cv'=>array(
				'id'=>$lignecv->getId()
				,'date_debut'=>$form->calendrier('', 'date_debut', $lignecv->get_date('date_debut'), 10)
				,'date_fin'=>$form->calendrier('', 'date_fin', $lignecv->get_date('date_fin'), 10)
				,'libelleExperience'=>$form->texte('','libelleExperience',$lignecv->libelleExperience, 30,100,'','','-')
				,'descriptionExperience'=>$form->texte('','descriptionExperience',$lignecv->descriptionExperience, 50,300,'style="width:400px;height:80px;"','','-')
				,'lieuExperience'=>$form->texte('','lieuExperience',$lignecv->lieuExperience, 30,100,'','','-')
			)
			,'userCourant'=>array(
				'id'=>$user->id
			)
			,'view'=>array(
				'mode'=>$mode
			)
		)	
	);
	
	echo $form->end_form();
	
	global $mesg, $error;
	dol_htmloutput_mesg($mesg, '', ($error ? 'error' : 'ok'));
	llxFooter();
}


	
	
function _ficheFormation(&$ATMdb, $formation, $tagCompetence,  $mode) {
	global $db,$user, $langs, $conf;
	llxHeader('','Formations');

	$fuser = new User($db);
	$fuser->fetch(isset($_REQUEST['fk_user']) ? $_REQUEST['fk_user'] : $formation->fk_user);
	$fuser->getrights();
	
	$head = user_prepare_head($fuser);
	$current_head = 'competence';
	dol_fiche_head($head, $current_head, $langs->trans('Utilisateur'),0, 'user');
	
	$form=new TFormCore($_SERVER['PHP_SELF'],'form1','POST');
	$form->Set_typeaff($mode);
	echo $form->hidden('id', $formation->getId());
	echo $form->hidden('action', 'saveformation');
	echo $form->hidden('fk_user', $user->id);
	echo $form->hidden('entity', $conf->entity);

	$sql="SELECT libelleCompetence FROM llx_rh_competence_cv WHERE fk_user_formation=".$formation->getID();
	$ATMdb->Execute($sql);
	$TTagCompetence=array();
	while($ATMdb->Get_line()) {
			$TTagCompetence[]=$form->texte('','libelleCompetence',$tagCompetence->libelleCompetence, 30,100,'','','-');
	}
	
	/*$THoraire=array();
	foreach($emploiTemps->TJour as $jour) {
		foreach(array('dam','fam','dpm','fpm') as $pm) {
			$THoraire[$jour.'_heure'.$pm]=$form->texte('','date_'.$jour.'_heure'.$pm, date('H:i',$emploiTemps->{'date_'.$jour.'_heure'.$pm}) ,5,5);
		}
	} */
	
	
	$TBS=new TTemplateTBS();
	print $TBS->render('./tpl/formation.tpl.php'
		,array(
		)
		,array(
			'formation'=>array(
				'id'=>$formation->getId()
				,'date_debut'=>$form->calendrier('', 'date_debut', $formation->get_date('date_debut'), 10)
				,'date_fin'=>$form->calendrier('', 'date_fin', $formation->get_date('date_fin'), 10)
				,'libelleFormation'=>$form->texte('','libelleFormation',$formation->libelleFormation, 30,100,'','','-')
				,'competenceFormation'=>$form->texte('','competenceFormation',$formation->competenceFormation, 30,100,'','','-')
				,'commentaireFormation'=>$form->texte('','commentaireFormation',$formation->commentaireFormation, 50,300,'style="width:400px;height:80px;"','','-')
				,'lieuFormation'=>$form->texte('','lieuFormation',$formation->lieuFormation, 30,100,'','','-')
			)
			,'userCourant'=>array(
				'id'=>$user->id
			)
			,'view'=>array(
				'mode'=>$mode
			)
		)	
	);
	
	echo $form->end_form();
	
	global $mesg, $error;
	dol_htmloutput_mesg($mesg, '', ($error ? 'error' : 'ok'));
	llxFooter();
}
