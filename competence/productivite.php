<?php
	require('config.php');
	require('./class/productivite.class.php');
	
	require_once DOL_DOCUMENT_ROOT.'/core/lib/usergroups.lib.php';
	require_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';
	require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';
	dol_include_once('/competence/lib/competence.lib.php');
	
	$langs->load('formulaire@formulaire');
	
	$ATMdb=new TPDOdb;
	$productivite = new TRH_productivite;
	
	if(isset($_REQUEST['action'])) {
		
		switch($_REQUEST['action']) {
			
			case 'save':
				
				$productivite->load($ATMdb, $_REQUEST['id']);
				$productivite->set_values($_REQUEST);
				
				$mesg = '<div class="ok">Indice de productivité enregistré avec succès</div>';
				
				$productivite->save($ATMdb);
				$productivite->load($ATMdb, $_REQUEST['id']);
				_fiche($ATMdb, $productivite, 'view');
				break;
			
			case 'delete':
				$productivite->load($ATMdb, $_REQUEST['id']);
				$productivite->delete($ATMdb, $_REQUEST['id']);
				
				?>
					<script>
					
						document.location.href="<?php echo dol_buildpath("/competence/productivite_liste.php", 2) ?>"
					
					</script>
				<?php

				break;
			
			case 'view':
				$productivite->load($ATMdb, $_REQUEST['id']);
				_fiche($ATMdb, $productivite, 'view');
				break;
			
			case 'edit':
				$productivite->load($ATMdb, $_REQUEST['id']);
				_fiche($ATMdb, $productivite);
				break;
			
			default:
				_fiche($ATMdb, $productivite);
				break;
			
		}
		
	}
	
	function _fiche(&$ATMdb, $productivite, $mode="edit") {
		
		global $db,$user,$langs,$conf;
		llxHeader('','Données de productivité');
		
		$fuser = new User($db);
		$fuser->fetch($_REQUEST['fk_user']);
		$fuser->getrights();
		
		$form=new TFormCore($_SERVER['PHP_SELF'],'form1','POST');
		$form->Set_typeaff($mode);
		
		echo $form->hidden('id', $productivite->getId());
		echo $form->hidden('action', 'save');
		echo $form->hidden('fk_user', $fuser->id);

		$TBS=new TTemplateTBS();

		print $TBS->render('./tpl/productivite.tpl.php'
			,array()
			,array(
				'user'=>array(
					'id'=>$fuser->id
					,'lastname'=>$fuser->lastname
					,'firstname'=>$fuser->firstname
				)
				,'productivite'=>array(
					'id'=>$productivite->getId()
					,'date_objectif'=>$form->calendrier('', 'date_objectif', $productivite->date_objectif, 12)
					,'indice'=>$form->texte('', 'indice', $productivite->indice, 20,255,'','','à saisir')
					,'label'=>$form->texte('', 'label', $productivite->label, 20,255,'','','à saisir')
					,'objectif'=>$form->texte('', 'objectif', $productivite->objectif, 20,255,'','','à saisir')
					//,'supprimable'=>$form->hidden('supprimable', 1)
				)
				,'view'=>array(
					'mode'=>$mode
					,'action'=>$_REQUEST['action']
					,'head'=>dol_get_fiche_head(competencePrepareHead($productivite, 'productivite'),'fiche','Productivité')
					,'onglet'=>dol_get_fiche_head(array(),'','Edition indice de productivité')
				)
				
			)	
			
		);
		
	}