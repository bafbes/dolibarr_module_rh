<?php
	require('config.php');
	require('./class/competence.class.php');
	
	require_once DOL_DOCUMENT_ROOT.'/core/lib/usergroups.lib.php';
	require_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';
	require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';
	
	$langs->load('competence@competence');
	$langs->load("users");
	
	$ATMdb=new Tdb;
	$remuneration=new TRH_remuneration;

	if(isset($_REQUEST['action'])) {
		switch($_REQUEST['action']) {
			case 'add':
			case 'new':
				//$ATMdb->db->debug=true;
				$remuneration->set_values($_REQUEST);
				_fiche($ATMdb, $remuneration, 'edit');
				break;
				
			case 'edit'	:
				//$ATMdb->db->debug=true;
				$remuneration->load($ATMdb, $_REQUEST['id']);
				_fiche($ATMdb, $remuneration,'edit');
				break;
				
			case 'save':
				$remuneration->load($ATMdb, $_REQUEST['id']);
				$remuneration->set_values($_REQUEST);
				$mesg = '<div class="ok">La ligne de rémunération a bien été enregistrée</div>';
				$remuneration->save($ATMdb);
				$remuneration->load($ATMdb, $_REQUEST['id']);
				_fiche($ATMdb, $remuneration, 'view');
				break;
				
			case 'view':
				$remuneration->load($ATMdb, $_REQUEST['id']);
				_fiche($ATMdb, $remuneration, 'view');
				break;
				
			case 'delete':
				//$ATMdb->db->debug=true;
				$remuneration->load($ATMdb, $_REQUEST['id']);
				$remuneration->delete($ATMdb, $_REQUEST['id']);
				$mesg = '<div class="ok">La ligne de rémunération a bien été supprimée</div>';
				_liste($ATMdb, $remuneration);
				break;
				
		}
	}
	elseif(isset($_REQUEST['id'])) {
		$remuneration->load($ATMdb, $_REQUEST['id']);
		_liste($ATMdb, $remuneration);	
	}
	else {
		
		//$ATMdb->db->debug=true;
		$remuneration->load($ATMdb, $_REQUEST['id']);
		_liste($ATMdb,$remuneration);
	}
	
	$ATMdb->close();
	
	llxFooter();
	
	
function _liste(&$ATMdb, $remuneration) {
	global $langs, $conf, $db, $user;	
	llxHeader('','Liste de vos rémunérations');
	
	$fuser = new User($db);
	$fuser->fetch($_REQUEST['fk_user']);
	$fuser->getrights();

	$head = user_prepare_head($fuser);
	dol_fiche_head($head, 'remuneration', $langs->trans('Utilisateur'),0, 'user');
	
	////////////AFFICHAGE DES LIGNES DE REMUNERATION
	$r = new TSSRenderControler($remuneration);
	$sql="SELECT r.rowid as 'ID', r.date_cre as 'DateCre', r.anneeRemuneration, CONCAT(u.firstname,' ',u.name) as 'Utilisateur' ,
			  CONCAT( ROUND(r.bruteAnnuelle,2),' €') as 'Rémunération brute annuelle',  CONCAT( ROUND(r.salaireMensuel,2),' €') as 'Salaire mensuel', r.fk_user, '' as 'Supprimer'
		FROM   ".MAIN_DB_PREFIX."rh_remuneration as r, ".MAIN_DB_PREFIX."user as u
		WHERE r.fk_user=".$user->id." AND r.entity=".$conf->entity." AND u.rowid=r.fk_user";

	$TOrder = array('anneeRemuneration'=>'ASC');
	if(isset($_REQUEST['orderDown']))$TOrder = array($_REQUEST['orderDown']=>'DESC');
	if(isset($_REQUEST['orderUp']))$TOrder = array($_REQUEST['orderUp']=>'ASC');
				
	$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;			
	$form=new TFormCore($_SERVER['PHP_SELF'],'formtranslateList','GET');
	
	$r->liste($ATMdb, $sql, array(
		'limit'=>array(
			'page'=>$page
			,'nbLine'=>'30'
		)
		,'link'=>array(
			'anneeRemuneration'=>'<a href="?id=@ID@&action=view">@val@</a>'
			,'Supprimer'=>'<a href="?id=@ID@&action=delete&fk_user='.$fuser->id.'"><img src="./img/delete.png"></a>'
		)
		,'translate'=>array(
			
		)
		,'hide'=>array('DateCre', 'fk_user')
		,'type'=>array()
		,'liste'=>array(
			'titre'=>'VISUALISATION DE VOS REMUNERATIONS'
			,'image'=>img_picto('','title.png', '', 0)
			,'picto_precedent'=>img_picto('','back.png', '', 0)
			,'picto_suivant'=>img_picto('','next.png', '', 0)
			,'noheader'=> (int)isset($_REQUEST['socid'])
			,'messageNothing'=>"Aucune rémunération enregistrée"
			,'order_down'=>img_picto('','1downarrow.png', '', 0)
			,'order_up'=>img_picto('','1uparrow.png', '', 0)
			,'picto_search'=>'<img src="../../theme/rh/img/search.png">'
		)
		,'title'=>array(
			'anneeRemuneration'=>'Année de rémunération'
		)
		,'search'=>array(
		)
		,'orderBy'=>$TOrder
		
	));

		?>
		<a class="butAction" href="?&action=new&fk_user=<?=$fuser->id?>">Ajouter une rémunération</a><div style="clear:both"></div>
		<br/><br/>
		<?
	$form->end();
	
	llxFooter();
}	

	
function _fiche(&$ATMdb, $remuneration,  $mode) {
	global $db,$user,$langs,$conf;
	llxHeader('','Vos Rémunérations');
	
	$fuser = new User($db);
	$fuser->fetch(isset($_REQUEST['fk_user']) ? $_REQUEST['fk_user'] : $remuneration->fk_user);
	$fuser->getrights();
	
	$head = user_prepare_head($fuser);
	$current_head = 'remuneration';
	dol_fiche_head($head, $current_head, $langs->trans('Utilisateur'),0, 'user');
	
	$form=new TFormCore($_SERVER['PHP_SELF'],'form1','POST');
	$form->Set_typeaff($mode);
	echo $form->hidden('id', $remuneration->getId());
	echo $form->hidden('fk_user', $user->id);
	echo $form->hidden('entity', $conf->entity);
	echo $form->hidden('action', 'save');

	
	$TBS=new TTemplateTBS();
	print $TBS->render('./tpl/remuneration.tpl.php'
		,array(
		)
		,array(
			'remuneration'=>array(
				'id'=>$remuneration->getId()
				,'date_entreeEntreprise'=>$form->calendrier('', 'date_entreeEntreprise', $remuneration->get_date('date_entreeEntreprise'), 10)
				,'anneeRemuneration'=>$form->texte('','anneeRemuneration',$remuneration->anneeRemuneration, 30,100,'','','-')
				,'bruteAnnuelle'=>$form->texte('','bruteAnnuelle',$remuneration->bruteAnnuelle, 30,100,'','','-')
				,'salaireMensuel'=>$form->texte('','salaireMensuel',$remuneration->salaireMensuel, 30,100,'','','-')
				,'primeAnciennete'=>$form->texte('','primeAnciennete',$remuneration->primeAnciennete, 30,100,'','','-')
				,'primeSemestrielle'=>$form->texte('','primeSemestrielle',$remuneration->primeSemestrielle, 30,100,'','','-')
				,'primeExceptionnelle'=>$form->texte('','primeExceptionnelle',$remuneration->primeExceptionnelle, 30,100,'','','-')
				,'prevoyancePartSalariale'=>$form->texte('','prevoyancePartSalariale',$remuneration->prevoyancePartSalariale, 30,100,'','','-')
				,'prevoyancePartPatronale'=>$form->texte('','prevoyancePartPatronale',$remuneration->prevoyancePartPatronale, 30,100,'','','-')
				,'urssafPartSalariale'=>$form->texte('','urssafPartSalariale',$remuneration->urssafPartSalariale, 30,100,'','','-')
				,'urssafPartPatronale'=>$form->texte('','urssafPartPatronale',$remuneration->urssafPartPatronale, 30,100,'','','-')
				,'retraitePartSalariale'=>$form->texte('','retraitePartSalariale',$remuneration->retraitePartSalariale, 30,100,'','','-')
				,'retraitePartPatronale'=>$form->texte('','retraitePartPatronale',$remuneration->retraitePartPatronale, 30,100,'','','-')
				,'totalRemPatronale'=>$remuneration->retraitePartPatronale+$remuneration->urssafPartPatronale+$remuneration->prevoyancePartPatronale
				,'totalRemSalariale'=>$remuneration->retraitePartSalariale+$remuneration->urssafPartSalariale+$remuneration->prevoyancePartSalariale
				,'commentaire'=>$form->texte('','commentaire',$remuneration->commentaire, 30,100,'','','-')
				,'fk_user'=>$remuneration->fk_user
				,'lieuExperience'=>$form->texte('','lieuExperience',$remuneration->lieuExperience, 30,100,'','','-')
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