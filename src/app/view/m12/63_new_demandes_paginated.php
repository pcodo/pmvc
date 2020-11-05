<?php
	$search_params = array();
	if(isset($_POST['search_mode']))
	{
		$nom = $db->escape($_POST['nom']);
	  	$date_reception_from = $db->escape($_POST['date_reception_from']);
	  	$date_reception_to = $db->escape($_POST['date_reception_to']);
	  	$search_params = array(
	  		'nom'=>$nom,
	  		'date_reception_from'=>$date_reception_from,
	  		'date_reception_to'=>$date_reception_to	  		
	  	);	  	
	}
?>
<script>
	$(function(){
		$('input[name=search]').bind('click',function(e){
			e.preventDefault();
			$('input[name=jqPageNumber]').val(1);
			$('input[name=search_mode]').attr('checked',true);
			$('#dossier_type_filter').submit();
		});

		$('#searchResultTextZone').html($('input[name=search_result_text]').val())
		                          .css('background-color','green')
		                          .css('color','white')
		                          .css('font-weight','bold')
		                          .css('clear','both');

		var jqListTotalItems = parseInt($('#jqListTotalItems').val());
		var pdfExportSize = parseInt($('#pdfExportSize').val());
		var dataTablePdfExportDialog = $('#data_table_tcpdf_export').dialog({
			modal:true,
			autoOpen: false,
			buttons:{
				"OK":function(){
					var export_title = $(this).find('input[name=export_title]').val();
					var export_page = 0;
					selected_radio_page = $(this).find('input[name=export_page]:checked');
					if(selected_radio_page.length)
					{
						export_page = selected_radio_page.val();
					}

					var export_href = $(this).find('.link').attr('href')+'&export_title='+export_title+'&export_page='+export_page;
					if( (jqListTotalItems > pdfExportSize) && export_page>0 || (jqListTotalItems <= pdfExportSize) )
					{
						document.location.href = export_href;
					}
					
					$(this).dialog( "close" );					
				}
			}
		});		
		var export_href = $('input[name=export_href]').val();
		var export_params_json = $('input[name=export_params_json]').val();
		export_link_obj = $('<a class="buttonLink" href="'+export_href+'">Exporter</a>');
		export_link_obj.bind('click', function(e){
			e.preventDefault();		
			if(jqListTotalItems > pdfExportSize)
			{	
				page_count = Math.ceil(jqListTotalItems/pdfExportSize);
				dataTablePdfExportDialog.find('.main-title').html('Impossible d\'exporter plus de '+pdfExportSize+' lignes à la fois. Le document sera scindé en '+page_count+' pages.');
				dataTablePdfExportDialog.find('input[name=export_title]').val('Liste des dossiers non affectés');
				var str_html = '<ul style="list-style-type:none;">';
				
				for(var i=0;i<page_count;i++)
				{
					var page = i+1;
					str_html+='<li><input type="radio" name="export_page" value="'+page+'" /> Page '+page+'</li>';
				}
				str_html+='</ul>';
				dataTablePdfExportDialog.find('.options').html(str_html);
				dataTablePdfExportDialog.find('.link').attr('href',export_href);
				dataTablePdfExportDialog.dialog('open');				
			}
			else
			{
				//$(this).unbind('click').trigger('click');
				dataTablePdfExportDialog.find('.main-title').html('Veuillez spécifier le titre du document');
				dataTablePdfExportDialog.find('input[name=export_title]').val('Liste des dossiers non affectés');
				dataTablePdfExportDialog.find('.link').attr('href',export_href);
				dataTablePdfExportDialog.dialog('open');
			}								
		});
		if(jqListTotalItems>0)
		{
			$('.jqListPagination').find('ul').append(export_link_obj);
		}
		
		
	});
</script>
<div align="center">
    <form  name="dossier_type_filter" id="dossier_type_filter" method="post" action="">
		<table align="center">
		    <tr><td><input type="hidden" name="type_dossier" value="<?php if(isset($_POST['type_dossier'])) echo $_POST['type_dossier']; ?>"/></td></tr>
			<tr><td id="stat_menu_list">
				<ul class="action_button_list">
				    <?php
				      $type_dossier = PensionEntityManager::typeDossiers();
				      $type_count_records = DossierAssign::notAssignedCountByTypeAsRecords();
					  $totalItems = 0;
					  $tpdcounts = array();
					  
					  foreach($type_dossier as $tpd)
					  {
					    $tpdcounts[$tpd['id']] = Systeme::array_key_macth_data_index($type_count_records,'id',$tpd['id'],'nbr');					    
					    if( (isset($_POST['type_dossier'])&&$_POST['type_dossier']==$tpd['id']) )
							echo '<li class="dossier_type_'.$tpd['id'].' inner-center current_state_menu"><a href="" value="'.$tpd['id'].'"><span class="radius">'.$tpdcounts[$tpd['id']].' '.$tpd['nom'].'</span> </a></li>';
						else
							echo '<li class="dossier_type_'.$tpd['id'].' inner-center"><a href="" value="'.$tpd['id'].'"><span class="radius">'.$tpdcounts[$tpd['id']].' '.$tpd['nom'].'</span> </a></li>';
					  }					 

					?>					
				</ul>
				<ul class="action_button_list" style="clear:both;text-align:center;">
					<?php
					echo '<p style="color:blue;font-weight:bold;"> '.Systeme::sum_numeric_array_tab($tpdcounts).' enregistrement(s) non encore affecté(s).</p>';
					?>
				</ul>
			</td></tr>
			
			<tr>
				<td>
					<?php
						echo '<input type="hidden" name="jqPageNumber" id="jqPageNumber" value="'.(isset($_POST['jqPageNumber'])?$_POST['jqPageNumber']:'').'"/>';
					?>					
				</td>
			</tr>
		</table>	
		<table style="border:thin inset;border-top:thin outset;border-left:thin outset;background-color:rgb(200,200,200)">
			<tr style="" title="Numéro d'enregistrement!">
				<td><label for="numero">Numéro</label></td>
				<td><input type="text" size="30" name="numero" id="numero" value="<?php if(isset($_POST['numero'])) echo $_POST['numero'];?>"/></td>
				<td colspan="2">						    	
			</tr>			
			<tr style="">
				<td><label for="nom">Nom/Sigle Structure</label></td>
				<td><input type="text" size="30" name="nom" id="nom" value="<?php if(isset($_POST['nom'])) echo $_POST['nom'];?>"/></td>										    	
			</tr>
			<tr style="">
				<td><label for="num_reception">Numéro de réception</label></td>
				<td><input type="text" size="30" name="num_reception" id="num_reception" value="<?php if(isset($_POST['num_reception'])) echo $_POST['num_reception'];?>"/></td>
				<td colspan="2">
					<select name="year_reception" title="Année de réception">	
						<option value="">--</option>				
						<?php
							$years = Systeme::getRecordYears();
							foreach ($years as $key => $year) {
								if(isset($_POST['year_reception'])&&$_POST['year_reception']==$year)
									echo '<option selected="selected" value="'.$year.'">'.$year.'</option>';
								else
									echo '<option value="'.$year.'">'.$year.'</option>';
							}
						?>
					</select>
				</td>						    	
			</tr>
			
			<tr style="">
				<td>Date réception : <label for="date_reception_from">Du</label></td>
				<td><input type="text" class="dateField" name="date_reception_from" id="date_reception_from" value="<?php if(isset($_POST['date_reception_from'])) echo $_POST['date_reception_from'];?>"/></td>
				<td><label for="date_reception_to">Au</label></td>
				<td><input type="text"  class="dateField" name="date_reception_to" id="date_reception_to" value="<?php if(isset($_POST['date_reception_to'])) echo $_POST['date_reception_to'];?>"/></td>							    	
			</tr>			
			<tr>
				<td colspan="4" align="right"><input type="checkbox" name="search_mode" <?php if(isset($_POST['search_mode'])) echo 'checked = "checked"';?>/><input type="submit" name="search" value="Rechercher"/></td>		
			</tr>
		</table>		
	</form>
	<div align="left" class="action_button_list">
		<ul>
			<li><a class="buttonLink fancybox fancybox.iframe" href="<?php echo Systeme::sm_path('88_searchDossier_form',array('localization_source_file'=>'88_d_add_new','localization_type_dossier'=>(isset($_POST['type_dossier'])?$_POST['type_dossier']:0) ))?>"> Rechercher</a></li>
			<li><a class="buttonLink fancybox fancybox.iframe" href="<?php echo Systeme::sm_path('88_addDossier_form',array('id_section'=>0))?>"> Créer</a></li>
			<li><a class="buttonLink fancybox fancybox.iframe single_action_link action_link" id="update_link" href="<?php echo Systeme::sm_path('88_addDossier_form',array('id_section'=>0))?>"> Modifier</a></li>
			<li><a class="buttonLink fancybox fancybox.iframe group_action_link action_link" id="sendtocda_link" href="<?php echo Systeme::sm_path('88_assign_form')?>">Envoyer</a></li>
		</ul>
	</div>
	<div id="searchResultTextZone" style="clear:both;margin-bottom:10px;">
		
	</div>
	
	<div class="jqListPagination" style="clear:both;float:right;">
		
	</div>
	
	<?php

	?>
	<table border="0" id="dataTable" class="display datatable">
        <thead>
            <tr height="30" align="center">
				<th></th>
				<th>N°</th>
				<th>N° Enreg</th>
				<th>Type</th>
                <th>Matricule</th>
                <th>Nom</th>
				<th>Prénoms</th>
				<th>Genre</th>
				<th>Jeune fille</th>
				<th title="Bordereau fonction publique">Bordereau FP</th>
				<th>Date Reception</th>
				<th>Enregistrement</th>
				<th></th>
		    </tr>
        </thead>
        <?php
			  $type_dossier = 0;
			  $jqPageNumber = 1;
			  if(isset($_POST['jqPageNumber']) and $_POST['jqPageNumber']>0)
			  {
			  	$jqPageNumber = $_POST['jqPageNumber'];
			  }
			  if(isset($_POST['type_dossier']) and $_POST['type_dossier']>0)
			  {
			  	$type_dossier = $db->escape($_POST['type_dossier']);
			  	$totalItems = $tpdcounts[$type_dossier];
			  }
			  else
			  {
			  	 $type_dossier = 0;
			  	 $totalItems = Systeme::sum_numeric_array_tab($tpdcounts);
			  } 
			  $i = JQPAGESIZE*($jqPageNumber-1);
			  if(isset($_POST['search_mode']))
			  {
			  	$totalItems = count(DossierEntityManager::searchAsRecords($search_params,'88_d_add_new',$type_dossier));
			  	
			  	$search_result_text_suffix = '';
			  	if($type_dossier==0)
			  	{
			  		$tpds = PensionEntityManager::typeDossiers();
			  		foreach($tpds as $tpd)
			  		{
			  			$search_params['type_dossier'] = $tpd['id'];
			  			$nbr = count(DossierEntityManager::searchAsRecords($search_params,'88_d_add_new',$tpd['id']));
			  			
			  			if($nbr>0)
			  			{
			  				if($search_result_text_suffix=='') $search_result_text_suffix = ': ';
			  				$search_result_text_suffix.=' '.$nbr.' '.$tpd['nom'].'(s),';
			  			}
			  		}
			  		$search_params['type_dossier'] = 0;
			  		$search_result_text_suffix = substr($search_result_text_suffix, 0,strlen($search_result_text_suffix)-1);
			  	}
			  	echo '<input type="hidden" name="search_result_text" value="'.$totalItems.' résultat(s) trouvé(s) pour la recherche'.$search_result_text_suffix.'">';
	  			
	  			$search_params['type_dossier'] = $type_dossier;
	  			$dossiers = DossierEntityManager::search($search_params,'88_d_add_new',$type_dossier,0,JQPAGESIZE,$jqPageNumber);
			  }
			  else
			  {
			  	$dossiers = DossierAssign::notAssigned($type_dossier,0,JQPAGESIZE,$jqPageNumber);	
			  }
			  	

			  foreach($dossiers as $d)
			  {
			  	if(trim($d->numReceptionUnique())=='')
			  	{
			  		$d->buildNumReceptionUnique();
			  		$d->dbSave($intervenant->id());
			  	}
			  	
			  	$count_assignment = 0;
			  	if($count_assignment == 0)
			  	{
			  	echo '<tr class="line">';
					echo '<td><input type="checkbox" name="dossier_'.$d->id().'" value="'.$d->id().'" '.($count_assignment>0? 'disabled="disabled"':'').' /></td>';
					echo '<td>'.(++$i).'</td>';
					echo '<td>'.$d->numReceptionUnique().'</td>';
					echo '<td>'.strtoupper($d->typeDossier()->nom()).'</td>';
					echo '<td>'.$d->usager()->matricule().'</td>';
					echo '<td>'.$d->usager()->nom().'</td>';
					echo '<td>'.$d->usager()->prenom().'</td>';
					echo '<td>'.$d->usager()->genreValue().'</td>';
					echo '<td>'.$d->usager()->nomJeuneFille().'</td>';
					$b_fp = $d->bordereau(BORDEREAU_FONCTION_PUBLIQUE);
					echo '<td>';
					 if($b_fp->id()>0)
					 {
					 	echo 'N° '.$b_fp->numero().' du '.Systeme::dateToFrench($b_fp->dateSignature());
					 }					 
					echo '</td>';
					echo '<td>'.Systeme::dateToFrench($d->dateReception()).'</td>';
					echo '<td>'.Systeme::dateTimeToFrench($d->insertDate()).' par '.$d->insertUser()->fullName().'</td>';

					$currentAssignment = $d->currentAssignment();
					echo '<td>';
						if($currentAssignment->observation()!='')
						{
							echo '<div class="thin-notifier fancy-show-on-click radius" title="Une observation pour ce dossier">M<span class="hidden"> <h3>Observation</h3><p class="fancy-standalone-popup-content">'.$currentAssignment->observation().' <br/> <i> adressée à '.$currentAssignment->userAssigned()->fullname().'</i></p><br/> Par '.$currentAssignment->insertUser()->fullname().' le '.Systeme::dateTimeToFrench($currentAssignment->insertDate()).'</span></div>';							
						}
						
						if(UserPrinterInstallation::checkUserInstalled($intervenant->id()))
						{
							echo '<div class="thin-notifier radius zone-color-yellow" title="Imprimer un récipissé de dépôt pour ce dossier."><a class="r_printer" href="#" id_dossier = '.$d->id().'><span class="zone-color-yellow">R</span></a></div>';
						}
						$complement_count = count($d->dossierComplements());
						if($complement_count>0)
						{
							echo '<div class="thin-notifier radius zone-color-red" title="'.$complement_count.' complément(s) pour ce dossier!"><a  class="fancybox fancybox.iframe" href="'.Systeme::sm_path('60_listDossierComplement_form',array('id_dossier'=>$d->id())).'"><span class="zone-color-red">'.$complement_count.'</span></a></div>';
						}
						
					echo '</td>';
				echo '</tr>';
				}
			  }
			 
			  			  
		?>
    </table>

</div>

<?php
	echo '<input type="hidden" name="jqListPageSize" id="jqListPageSize" value="'.JQPAGESIZE.'" />';
	echo '<input type="hidden" name="jqListTotalItems" id="jqListTotalItems" value="'.$totalItems.'" />';
	echo '<input type="hidden" name="pdfExportSize" id="pdfExportSize" value="'.PDFEXPORTSIZE.'" />';

	$export_params = array(
		'search_params' => $search_params,
		'type_dossier' => $type_dossier
	);
	$tag = '``';
	$export_params_json = preg_replace('#"#',$tag,json_encode($export_params));
	$href = 'includes/sm/epension/pdf/88_d_allnotassigned_export.php?export_params='.$export_params_json;
	echo '<input type="hidden" name="export_params_json" value="'.$export_params_json.'" />';
	echo '<input type="hidden" name="export_href" value="'.$href.'" />';
?>