<?php
	require('config.php');
	require('./class/absence.class.php');
	
	$langs->load('absence@absence');
	
	$ATMdb=new Tdb;
	$compteur=new TRH_Compteur;

	
	if(isset($_REQUEST['action'])) {
		switch($_REQUEST['action']) {
			case 'add':
			case 'new':
				_fiche($ATMdb, $compteur,'edit');
				
				break;	
			case 'edit'	:
				$compteur->load($ATMdb, $_REQUEST['id']);
				_fiche($ATMdb, $compteur,'edit');
				break;
				
			case 'save':
				$ATMdb->db->debug=true;
				$compteur->load($ATMdb, $_REQUEST['id']);
				$compteur->set_values($_REQUEST);
				$compteur->save($ATMdb);
				$compteur->load($ATMdb, $_REQUEST['id']);
				$mesg = '<div class="ok">Modifications effectuées</div>';
				$mode = 'view';
				_fiche($ATMdb, $compteur,'view');
			
				break;
			
			case 'view':
				$compteur->load($ATMdb, $_REQUEST['id']);
				_fiche($ATMdb, $compteur,'view');
				break;

			case 'delete':
				
				break;
		}
	}
	elseif(isset($_REQUEST['id'])) {
		
	}
	else {
		$ATMdb->db->debug=true;
		_liste($ATMdb, $compteur);
	}
	
	
	$ATMdb->close();
	
	llxFooter();
	
	
function _liste(&$ATMdb, &$compteur) {
	global $langs, $conf, $db, $user;	
	llxHeader('','Liste de vos jours acquis');
	getStandartJS();
	
	$r = new TSSRenderControler($compteur);
	$sql="SELECT anneeN as 'annee', r.rowid as 'ID', r.date_cre as 'DateCre',r.acquisExerciceN as 'Congés acquis N', 
	r.acquisAncienneteN as 'Congés Ancienneté', r.acquisExerciceNM1 as 'Conges Acquis N-1', r.congesPrisNM1 as 'Conges Pris N-1',
			  r.rttAcquis as 'RttAcquis', r.rttPris as 'RttPris', r.fk_user as 'Utilisateur Courant'
		FROM llx_rh_compteur as r
		WHERE r.fk_user=".$user->id;
		
	
	$TOrder = array('DateCre'=>'ASC');
	if(isset($_REQUEST['orderDown']))$TOrder = array($_REQUEST['orderDown']=>'DESC');
	if(isset($_REQUEST['orderUp']))$TOrder = array($_REQUEST['orderUp']=>'ASC');
				
	$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;			
	//print $page;
	$r->liste($ATMdb, $sql, array(
		'limit'=>array(
			'page'=>$page
			,'nbLine'=>'30'
		)
		,'link'=>array(
			'ID'=>'<a href="?id=@ID@&action=view">@val@</a>'
		)
		,'translate'=>array()
		,'hide'=>array('DateCre')
		,'type'=>array()
		,'liste'=>array(
			'titre'=>'Liste de vos jours acquis'
			,'image'=>img_picto('','title.png', '', 0)
			,'picto_precedent'=>img_picto('','back.png', '', 0)
			,'picto_suivant'=>img_picto('','next.png', '', 0)
			,'noheader'=> (int)isset($_REQUEST['socid'])
			,'messageNothing'=>"Il n'y a aucun jour acquis à afficher"
			,'order_down'=>img_picto('','1downarrow.png', '', 0)
			,'order_up'=>img_picto('','1uparrow.png', '', 0)
			
		)
		,'orderBy'=>$TOrder
		
	));
	
	
	llxFooter();
}	
	
function _fiche(&$ATMdb, &$compteur, $mode) {
	global $db,$user;
	llxHeader('');

	$form=new TFormCore($_SERVER['PHP_SELF'],'form1','POST');
	$form->Set_typeaff($mode);
	echo $form->hidden('id', $compteur->getId());
	echo $form->hidden('action', 'save');
	
	
	$anneeCourante=date('Y');
	$anneePrec=$anneeCourante-1;
	//////////////////////récupération des informations des congés courants (N) de l'utilisateur courant : 
	$sqlReqUser="SELECT * FROM `llx_rh_compteur` where fk_user=".$user->id." AND anneeNM1=".$anneePrec;//."AND entity=".$conf->entity;
	$ATMdb->Execute($sqlReqUser);
	$Tab=array();
	while($ATMdb->Get_line()) {
				$congePrec=new User($db);
				$congePrec->id=$ATMdb->Get_field('rowid');
				$congePrec->acquisEx=$ATMdb->Get_field('acquisExerciceNM1');
				$congePrec->acquisAnc=$ATMdb->Get_field('acquisAncienneteNM1');
				$congePrec->acquisHorsPer=$ATMdb->Get_field('acquisHorsPeriodeNM1');
				$congePrec->reportConges=$ATMdb->Get_field('reportCongesNM1');
				$congePrec->congesPris=$ATMdb->Get_field('congesPrisNM1');
				$congePrec->annee=$ATMdb->Get_field('anneeNM1');
				$congePrec->fk_user=$ATMdb->Get_field('fk_user');
				$Tab[]=$congePrec;	
	}
	
	$congePrecTotal=$congePrec->acquisEx+$congePrec->acquisAnc+$congePrec->acquisHorsPer+$congePrec->reportConges;
	$congePrecReste=$congePrecTotal-$congePrec->congesPris;
	
	//////////////////////////récupération des informations des congés précédents (N-1) de l'utilisateur courant : 
	$sqlReqUser2="SELECT * FROM `llx_rh_compteur` where fk_user=".$user->id." AND anneeN=".$anneeCourante;//."AND entity=".$conf->entity;;
	$ATMdb=new Tdb;
	$ATMdb->Execute($sqlReqUser2);
	$Tab2=array();
	while($ATMdb->Get_line()) {
				$congeCourant=new User($db);
				$congeCourant->id=$ATMdb->Get_field('rowid');
				$congeCourant->acquisEx=$ATMdb->Get_field('acquisExerciceN');
				$congeCourant->acquisAnc=$ATMdb->Get_field('acquisAncienneteN');
				$congeCourant->acquisHorsPer=$ATMdb->Get_field('acquisHorsPeriodeN');
				$congeCourant->annee=$ATMdb->Get_field('anneeN');
				$congeCourant->fk_user=$ATMdb->Get_field('fk_user');
				$Tab2[]=$congeCourant;	
	}
	
	$congeCourantTotal=$congeCourant->acquisEx+$congeCourant->acquisAnc+$congeCourant->acquisHorsPer;
	
	//////////////////////////////récupération des informations des rtt courants (année N) de l'utilisateur courant : 
	$sqlRtt="SELECT * FROM `llx_rh_compteur` where fk_user=".$user->id;
	$ATMdb->Execute($sqlRtt);
	$Tab=array();
	while($ATMdb->Get_line()) {
				$rttCourant=new User($db);
				$rttCourant->id=$ATMdb->Get_field('rowid');
				$rttCourant->acquis=$ATMdb->Get_field('rttAcquisMensuel')+$ATMdb->Get_field('rttAcquisAnnuelCumule')+$ATMdb->Get_field('rttAcquisAnnuelNonCumule');
				$rttCourant->pris=$ATMdb->Get_field('rttPris');
				$rttCourant->mensuel=$ATMdb->Get_field('rttAcquisMensuel');
				$rttCourant->annuelCumule=$ATMdb->Get_field('rttAcquisAnnuelCumule');
				$rttCourant->annuelNonCumule=$ATMdb->Get_field('rttAcquisAnnuelNonCumule');
				$rttCourant->typeAcquisition=$ATMdb->Get_field('rttTypeAcquisition');
				$rttCourant->annee=substr($ATMdb->Get_field('anneertt'),0,4);
				$rttCourant->fk_user=$ATMdb->Get_field('fk_user');
				$Tab[]=$rttCourant;	
	}
	
	$rttCourantReste=$rttCourant->acquis-$rttCourant->pris;
	
	
	$TBS=new TTemplateTBS();
	print $TBS->render('./tpl/compteur.tpl.php'
		,array(
			
			
		)
		,array(
			'congesPrec'=>array(
				//texte($pLib,$pName,$pVal,$pTaille,$pTailleMax=0,$plus='',$class="text", $default='')
				'acquisEx'=>$form->texte('','acquisExerciceNM1',$congePrec->acquisEx,10,50,'',$class="text", $default='')
				,'acquisAnc'=>$form->texte('','acquisAncienneteNM1',$congePrec->acquisAnc,10,50,'',$class="text", $default='')
				,'acquisHorsPer'=>$form->texte('','acquisHorsPeriodeNM1',$congePrec->acquisHorsPer,10,50,'',$class="text", $default='')
				,'reportConges'=>$form->texte('','reportcongesNM1',$congePrec->reportConges,10,50,'',$class="text", $default='')
				,'congesPris'=>$form->texte('','congesprisNM1',$congePrec->congesPris,10,50,'',$class="text", $default='')
				,'anneePrec'=>$form->texte('','anneeNM1',$anneePrec,10,50,'',$class="text", $default='')
				,'total'=>$form->texte('','total',$congePrecTotal,10,50,'',$class="text", $default='')
				,'reste'=>$form->texte('','reste',$congePrecReste,10,50,'',$class="text", $default='')
				,'idUser'=>$congePrec->fk_user
			)
			,'congesCourant'=>array(
				//texte($pLib,$pName,$pVal,$pTaille,$pTailleMax=0,$plus='',$class="text", $default='')
				'acquisEx'=>$form->texte('','acquisExerciceN',$congeCourant->acquisEx,10,50,'',$class="text", $default='')
				,'acquisAnc'=>$form->texte('','acquisAncienneteN',$congeCourant->acquisAnc,10,50,'',$class="text", $default='')
				,'acquisHorsPer'=>$form->texte('','acquisHorsPeriodeN',$congeCourant->acquisHorsPer,10,50,'',$class="text", $default='')
				,'anneeCourante'=>$form->texte('','anneeN',$anneeCourante,10,50,'',$class="text", $default='')
				,'total'=>$form->texte('','total',$congeCourantTotal,10,50,'',$class="text", $default='')
				,'idUser'=>$congeCourant->fk_user
			)
			,'rttCourant'=>array(
				//texte($pLib,$pName,$pVal,$pTaille,$pTailleMax=0,$plus='',$class="text", $default='')
				'acquis'=>$form->texte('','rttAcquis',$rttCourant->acquis,10,50,'',$class="text", $default='')
				,'rowid'=>$form->texte('','rowid',$rttCourant->id,10,50,'',$class="text", $default='')
				,'pris'=>$form->texte('','rttPris',$rttCourant->pris,10,50,'',$class="text", $default='')
				,'mensuel'=>$form->texte('','rttAcquisMensuel',$rttCourant->mensuel,10,50,'',$class="text", $default='')
				,'annuelCumule'=>$form->texte('','rttAcquisAnnuelCumule',$rttCourant->annuelCumule,10,50,'',$class="text", $default='')
				,'annuelNonCumule'=>$form->texte('','rttAcquisAnnuelNonCumule',$rttCourant->annuelNonCumule,10,50,'',$class="text", $default='')
				,'typeAcquisition'=>$form->texte('','typeAcquisition',$rttCourant->typeAcquisition,10,50,'',$class="text", $default='')
				,'reste'=>$form->texte('','total',$rttCourantReste,10,50,'',$class="text", $default='')
				,'id'=>$compteur->getId()

				
			)
			,'userCourant'=>array(
				'id'=>$user->id
				,'lastname'=>$user->lastname
				,'firstname'=>$user->firstname
			)
			
			,'view'=>array(
				'mode'=>$mode
			
			
			)
			
			
		)	
		
	);
	
	echo $form->end_form();
	// End of page
	
	global $mesg, $error;
	dol_htmloutput_mesg($mesg, '', ($error ? 'error' : 'ok'));
	llxFooter();
}


	
	