
        [view.head;strconv=no]

		[view.titreEdt;strconv=no;protect=no]
		<br>
		<div style=" display:inline-block;">                  
		<table class="border" style="width:130%;" >	
				<tr>
					<td>       </td>
					<td style="text-align:center;"><b>Matin</b></td>
					<td style="text-align:center;"><b>Après-midi</b></td>
					<td style="text-align:center;"><b>Matin</b></td>
					<td style="text-align:center;"><b>Midi</b></td>
					<td style="text-align:center;"><b>Après-midi</b></td>
					<td style="text-align:center;"><b>Soir</b></td>
				</tr>
				<tr>
					<td style="text-align:center;"><b>Lundi</b></td>
					
					<td style="text-align:center;">[planning.lundiam;strconv=no;protect=no]</td>
					<td style="text-align:center;">[planning.lundipm;strconv=no;protect=no]</td>
					<td style="text-align:center;"> [horaires.lundi_heuredam;strconv=no;protect=no]      </td>
					<td style="text-align:center;"> [horaires.lundi_heurefam;strconv=no;protect=no]     </td>
					<td style="text-align:center;"> [horaires.lundi_heuredpm;strconv=no;protect=no]    </td>
					<td style="text-align:center;"> [horaires.lundi_heurefpm;strconv=no;protect=no]    </td>
				</tr>
				<tr>
					<td style="text-align:center;"><b>Mardi</b></td>
					<td style="text-align:center;">[planning.mardiam;strconv=no;protect=no]</td>
					<td style="text-align:center;">[planning.mardipm;strconv=no;protect=no]</td>
					<td style="text-align:center;"> [horaires.mardi_heuredam;strconv=no;protect=no]      </td>
					<td style="text-align:center;"> [horaires.mardi_heurefam;strconv=no;protect=no]     </td>
					<td style="text-align:center;"> [horaires.mardi_heuredpm;strconv=no;protect=no]    </td>
					<td style="text-align:center;"> [horaires.mardi_heurefpm;strconv=no;protect=no]    </td>
				</tr>
				<tr>
					<td style="text-align:center;"><b>Mercredi</b></td>
					<td style="text-align:center;">[planning.mercrediam;strconv=no;protect=no]</td>
					<td style="text-align:center;">[planning.mercredipm;strconv=no;protect=no]</td>
					<td style="text-align:center;"> [horaires.mercredi_heuredam;strconv=no;protect=no]      </td>
					<td style="text-align:center;"> [horaires.mercredi_heurefam;strconv=no;protect=no]     </td>
					<td style="text-align:center;"> [horaires.mercredi_heuredpm;strconv=no;protect=no]    </td>
					<td style="text-align:center;"> [horaires.mercredi_heurefpm;strconv=no;protect=no]    </td>
				</tr>
				<tr>
					<td style="text-align:center;"><b>Jeudi</b></td>
					<td style="text-align:center;">[planning.jeudiam;strconv=no;protect=no]</td>
					<td style="text-align:center;">[planning.jeudipm;strconv=no;protect=no]</td>
					<td style="text-align:center;"> [horaires.jeudi_heuredam;strconv=no;protect=no]      </td>
					<td style="text-align:center;"> [horaires.jeudi_heurefam;strconv=no;protect=no]     </td>
					<td style="text-align:center;"> [horaires.jeudi_heuredpm;strconv=no;protect=no]    </td>
					<td style="text-align:center;"> [horaires.jeudi_heurefpm;strconv=no;protect=no]    </td>
				</tr>
				<tr>
					<td style="text-align:center;"><b>Vendredi</b></td>
					<td style="text-align:center;">[planning.vendrediam;strconv=no;protect=no]</td>
					<td style="text-align:center;">[planning.vendredipm;strconv=no;protect=no]</td>
					<td style="text-align:center;"> [horaires.vendredi_heuredam;strconv=no;protect=no]      </td>
					<td style="text-align:center;"> [horaires.vendredi_heurefam;strconv=no;protect=no]     </td>
					<td style="text-align:center;"> [horaires.vendredi_heuredpm;strconv=no;protect=no]    </td>
					<td style="text-align:center;"> [horaires.vendredi_heurefpm;strconv=no;protect=no]    </td>
				</tr>
				<tr>
					<td style="text-align:center;"><b>Samedi</b></td>
					<td style="text-align:center;">[planning.samediam;strconv=no;protect=no]</td>
					<td style="text-align:center;">[planning.samedipm;strconv=no;protect=no]</td>
					<td style="text-align:center;"> [horaires.samedi_heuredam;strconv=no;protect=no]      </td>
					<td style="text-align:center;"> [horaires.samedi_heurefam;strconv=no;protect=no]     </td>
					<td style="text-align:center;"> [horaires.samedi_heuredpm;strconv=no;protect=no]    </td>
					<td style="text-align:center;"> [horaires.samedi_heurefpm;strconv=no;protect=no]    </td>
				</tr>
				<tr>
					<td style="text-align:center;"><b>Dimanche</b></td>
					<td style="text-align:center;">[planning.dimancheam;strconv=no;protect=no]</td>
					<td style="text-align:center;">[planning.dimanchepm;strconv=no;protect=no]</td>
					<td style="text-align:center;"> [horaires.dimanche_heuredam;strconv=no;protect=no]      </td>
					<td style="text-align:center;"> [horaires.dimanche_heurefam;strconv=no;protect=no]     </td>
					<td style="text-align:center;"> [horaires.dimanche_heuredpm;strconv=no;protect=no]    </td>
					<td style="text-align:center;"> [horaires.dimanche_heurefpm;strconv=no;protect=no]    </td>
				</tr>
		</table>
		
		</div>
	[onshow;block=begin;when [view.mode]=='edit']
		<br/><br/>
		<b style="margin-left:320px;">Veuillez respecter le format HH:MM pour les horaires</b>
	[onshow;block=end]
	<br/><br/><br>
	Temps total de travail hebdomadaire : [userCourant.tempsHebdo;strconv=no;protect=no]h<br>
	Société : [entity.TEntity;strconv=no;protect=no]
	
	
	
	[onshow;block=begin;when [view.mode]=='edit']
		<div class="tabsAction" >
		<input type="submit" value="Enregistrer" name="save" class="button"  onclick="document.location.href='?id=[view.compteur_id]&action=view'">
		&nbsp; &nbsp; <input type="button" value="Annuler" name="cancel" class="button" onclick="document.location.href='?id=[view.compteur_id]&action=view'">
		</div>
	[onshow;block=end]
	
	[onshow;block=begin;when [view.mode]!='edit']
			[onshow;block=begin;when [droits.modifierEdt]=='1']
			<div class="tabsAction" >
				<a class="butAction"  href="?id=[view.compteur_id]&action=edit">Modifier</a>
			</div>
			[onshow;block=end]
	[onshow;block=end]
	

			
	<script>
		$(document).ready( function(){
			//on empêche que la date de début dépasse celle de fin
			$('#lundiam').change( 	function(){
				
				if($('#lundiam').attr('checked')!="checked"){
					$("#date_lundi_heuredam").val("00:00");
					$("#date_lundi_heurefam").val("00:00");
				}
				else{
					$("#date_lundi_heuredam").val("08:15");
					$("#date_lundi_heurefam").val("12:00");
				}
			});
			
			$('#lundipm').change( 	function(){
				
				if($('#lundipm').attr('checked')!="checked"){
					$("#date_lundi_heuredpm").val("00:00");
					$("#date_lundi_heurefpm").val("00:00");
				}
				else{
					$("#date_lundi_heuredpm").val("14:00");
					$("#date_lundi_heurefpm").val("17:45");
				}
			});
			
			 $('#mardiam').change( 	function(){
				
				if($('#mardiam').attr('checked')!="checked"){
					$("#date_mardi_heuredam").val("00:00");
					$("#date_mardi_heurefam").val("00:00");
				}
				else{
					$("#date_mardi_heuredam").val("08:15");
					$("#date_mardi_heurefam").val("12:00");
				}
			});
			
			$('#mardipm').change( 	function(){
				
				if($('#mardipm').attr('checked')!="checked"){
					$("#date_mardi_heuredpm").val("00:00");
					$("#date_mardi_heurefpm").val("00:00");
				}
				else{
					$("#date_mardi_heuredpm").val("14:00");
					$("#date_mardi_heurefpm").val("17:45");
				}
			});
			
			$('#mercrediam').change( 	function(){
				
				if($('#mercrediam').attr('checked')!="checked"){
					$("#date_mercredi_heuredam").val("00:00");
					$("#date_mercredi_heurefam").val("00:00");
				}
				else{
					$("#date_mercredi_heuredam").val("08:15");
					$("#date_mercredi_heurefam").val("12:00");
				}
			});
			
			$('#mercredipm').change( 	function(){
				
				if($('#mercredipm').attr('checked')!="checked"){
					$("#date_mercredi_heuredpm").val("00:00");
					$("#date_mercredi_heurefpm").val("00:00");
				}
				else{
					$("#date_mercredi_heuredpm").val("14:00");
					$("#date_mercredi_heurefpm").val("17:45");
				}
			});
			
			$('#jeudiam').change( 	function(){
				
				if($('#jeudiam').attr('checked')!="checked"){
					$("#date_jeudi_heuredam").val("00:00");
					$("#date_jeudi_heurefam").val("00:00");
				}
				else{
					$("#date_jeudi_heuredam").val("08:15");
					$("#date_jeudi_heurefam").val("12:00");
				}
			});
			
			$('#jeudipm').change( 	function(){
				
				if($('#jeudipm').attr('checked')!="checked"){
					$("#date_jeudi_heuredpm").val("00:00");
					$("#date_jeudi_heurefpm").val("00:00");
				}
				else{
					$("#date_jeudi_heuredpm").val("14:00");
					$("#date_jeudi_heurefpm").val("17:45");
				}
			});
			
			$('#vendrediam').change( 	function(){
				
				if($('#vendrediam').attr('checked')!="checked"){
					$("#date_vendredi_heuredam").val("00:00");
					$("#date_vendredi_heurefam").val("00:00");
				}
				else{
					$("#date_vendredi_heuredam").val("08:15");
					$("#date_vendredi_heurefam").val("12:00");
				}
			});
			
			$('#vendredipm').change( 	function(){
				
				if($('#vendredipm').attr('checked')!="checked"){
					$("#date_vendredi_heuredpm").val("00:00");
					$("#date_vendredi_heurefpm").val("00:00");
				}
				else{
					$("#date_vendredi_heuredpm").val("14:00");
					$("#date_vendredi_heurefpm").val("17:15");
				}
			});
			
			$('#samediam').change( 	function(){
				
				if($('#samediam').attr('checked')!="checked"){
					$("#date_samedi_heuredam").val("00:00");
					$("#date_samedi_heurefam").val("00:00");
				}
				else{
					$("#date_samedi_heuredam").val("08:15");
					$("#date_samedi_heurefam").val("12:00");
				}
			});
			
			$('#samedipm').change( 	function(){
				
				if($('#samedipm').attr('checked')!="checked"){
					$("#date_samedi_heuredpm").val("00:00");
					$("#date_samedi_heurefpm").val("00:00");
				}
				else{
					$("#date_samedi_heuredpm").val("14:00");
					$("#date_samedi_heurefpm").val("17:45");
				}
			});
			
			$('#dimancheam').change( 	function(){
				
				if($('#dimancheam').attr('checked')!="checked"){
					$("#date_dimanche_heuredam").val("00:00");
					$("#date_dimanche_heurefam").val("00:00");
				}
				else{
					$("#date_dimanche_heuredam").val("08:15");
					$("#date_dimanche_heurefam").val("12:00");
				}
			});
			
			$('#dimanchepm').change( 	function(){
				
				if($('#dimanchepm').attr('checked')!="checked"){
					$("#date_dimanche_heuredpm").val("00:00");
					$("#date_dimanche_heurefpm").val("00:00");
				}
				else{
					$("#date_dimanche_heuredpm").val("14:00");
					$("#date_dimanche_heurefpm").val("17:45");
				}
			});
			
    		
			
		});
	</script>


