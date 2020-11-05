// var site_ip = '10.10.10.104';
// var appName = 'E-vente_1.0';
// var site_url = 'http://'+site_ip+'//'+appName+'//';
var site_url = '';
$(function(){
	
    /*initialisation de la categorie courante*/
    var active_nav = parseInt($('#active_nav').val());
	active_nav = !isNaN(active_nav)?active_nav:0;
	$("#tabs" ).tabs({
	    active: active_nav 
	});
		
	$( ":submit,:reset,button,input[type=button],#left_menu a").button().click(function(event)
	{
		//event.preventDefault();
	});
	$('.buttonLink').button().css('color','red').css('text-decoration','none');//utilisé sur l'etat des vente 17.php
	
	/* la couleur du menu selectionné: */
	$('.current_m').css('background-color','#abcdef;');
	
		
	/* la couleur du sous_menu selectionné*/
	 $(".current_sm a").css('color','red');
	 
	/*Gestion de la déconnxion suivant le click sur l'option deconnexion*/
	$("#header select[name=sessionInfo]").change(function()
	{
		switch(parseInt($(this).val()))
		{
			case 0:
				// rien
			break;
			case 1:
				document.location.href="index.php?m=5&sm=8_changepswd";
			break;
			case 2:
				document.location.href="deconnexion.php";
			break;
		}
		
	});

	$("#header select[name=globalYear]").change(function(){
		globalYear = $(this).val();
		$.get(site_url+'ajax/request.php?req=13&globalYear='+globalYear,function(data){
			document.location.reload();// on se fout des donnée json envoyé :: on se contente de recharger la page
		});
	});


	function setGlobalMagasin(magasin)
	{
		$.get(site_url+'ajax/request.php?req=7&global_magasin='+magasin,function(data){
			document.location.reload();// on se fout des donnée json envoyé :: on se contente de recharger la page
		});
	}
	
	/* les toolstip*/
	$( document ).tooltip({
		open: function( event, ui ) {
			
		}
	});
	
	/* Les date picker*/
	jQuery.datepicker.setDefaults(jQuery.datepicker.regional['fr']);
	$( ".dateField" ).datepicker();
	$(".select2").select2();
	
	$('.required').css('color','red');
	/*les accordeons*/
	$( ".accordion" ).accordion();
	
	$('.fancybox').fancybox({
		width:1000
	});

	$('.fancy-show-on-click').bind('click',function(){
			data_obj = $(this).find('.hidden');
			$.fancybox( data_obj.html(), {
		        
		    });
	});
    
   var qListPageSize = $('#jqListPageSize').val();
   var jqListTotalItems = $('#jqListTotalItems').val();
   var jqPageNumber = $('#jqPageNumber').val();
   var type_dossier = $('input[name=type_dossier]').val();
   jqPageNumber = !isNaN(parseInt(jqPageNumber))?parseInt(jqPageNumber):1;
   jqPaginator = $('.jqListPagination').pagination({
	        items: jqListTotalItems,
	        itemsOnPage: qListPageSize,
	        currentPage: jqPageNumber,
	        cssStyle: 'compact-theme' ,
	        onPageClick: function(pageNumber, event){
	        	event.preventDefault();        	
	        	$('#jqPageNumber').val(pageNumber);
	        	$('#dossier_type_filter').submit();	        
	        }
	});
	
	// autres pour test
	$("#go").hover(function(){
			  $("#block").animate({
				  backgroundColor: "#abcdef"
			  }, 800 );
			});
			$("#sat").hover(function(){
			  $("#block").animate({
				  backgroundColor: $.Color({ saturation: 0 })
			  }, 800 );
	});
	//gestion des filtres sur les listes
	
		$('#filterEnabling').click( function(){
			if(!($(this).is(':checked')))
			{
			 $('#filtre .dateField').val('');
			 $('#filtre').submit(); 
			}
		});
		$('#filtre .dateField').change(function(){
			if($(this).val()!=''){
				$('#filterEnabling').attr('checked',false);
				$('#filterEnabling').trigger('click');
				var month_selecter = $('#filtre select[name=mois]');
				if(month_selecter.length&&month_selecter.val()>0)
				{
					$('#filtre select[name=mois]').val("0");
				}
			}
		});
		$('#filtre select[name=mois]').change(function(){
		    if($(this).val()>0)
		    {
				if($('#filtre .dateField').length){
					$('#filtre .dateField').val('');
					$('#filterEnabling').attr('checked',false);
				}
			}
		});
		
		// Gestion des select hide et show
		$('.hider_select').change(function(){
		   if($(this).val()==0||$(this).val()=="N")
		   {
			 $('.'+$(this).attr('name')).show();
		   }
		   else
		   {
			 $('.'+$(this).attr('name')).hide();
		   }
		});	

		$('#dossier_type_filter li a').bind('click', function(e){
			e.preventDefault();
			$('input[name=type_dossier]').val($(this).attr('value'));
			jqPageNumberobj = $('input[name=jqPageNumber]');
			if(jqPageNumberobj.length)
			{
				jqPageNumberobj.val(1);
			}			
			$('#dossier_type_filter').submit();
		});
		$('#dossier_type_filter select[name=id_nature_courrier]').bind('change',function(e){
			jqPageNumberobj = $('input[name=jqPageNumber]');
			if(jqPageNumberobj.length)
			{
				jqPageNumberobj.val(1);
			}	
			$('#dossier_type_filter').submit();
		});

		$('#stat_menu_list a').bind('click',function(e){
			/*parent = $(this).parent();*/
			var href = $(this).attr('href');
			if(href.indexOf('stat_menu')>=0)
			{
				e.preventDefault();
				$.redirect(href);
			}
	   });
		
		// for all <a class="redirect" href='index.php?..'
		$('.redirect').bind('click',function(e){
			e.preventDefault();
			$.redirect($(this).attr('href'));
		});
		
		$.extend({ alert: function (message, title) {
		  $("<div></div>").dialog( {
			buttons: { "Ok": function () { $(this).dialog("close"); } },
			close: function (event, ui) { $(this).remove(); },
			resizable: false,
			title: title,
			modal: true
		  }).text(message);
		}
		});


		// selection des dossiers par javascript et construction des liens pour les appel fancybox
		//18/12/2015 par Paterne CODO
		var tag = '``';
		$('.action_link').button('option','disabled',true);
		function getSelectedItems()
		{
			var selected_items = [];
			$('.line input[type=checkbox]').each(function(){
				if($(this).is(':checked'))
				{
					selected_items.push($(this).val());
				}
			});
			return selected_items;
		}
		
		$('.line input[type=checkbox]').bind('click', function(){
			var selected_items = getSelectedItems();
			selected_items_to_json = JSON.stringify(selected_items).replace(/"/g,tag); // le g permet de tout remplacer
			$('.action_link').each(function(){
				href = $(this).attr('href');
				action_path = $(this).attr('dhref');
				if(action_path==''||action_path==null) 
				{
					$(this).attr('dhref',href);
					action_path = href;
				}
				else{
					
				}
				final_url = ''; 
				if(action_path.indexOf('?')>=0)
					final_url = action_path+'&selected_items='+selected_items_to_json;
				else
					final_url = action_path+'?selected_items='+selected_items_to_json;
								
				$(this).attr('href',final_url);
			});
			var count_checked = selected_items.length;
			if(count_checked==0){
				$('.single_action_link').button('option','disabled',true);
				$('.group_action_link').button('option','disabled',true);
			}
			else if(count_checked==1){
				$('.single_action_link').button('option','disabled',false);
				$('.group_action_link').button('option','disabled',false);
			}
			else{
				$('.single_action_link').button('option','disabled',true);
				$('.group_action_link').button('option','disabled',false);
			}		
           
			$('.line input[type=checkbox]').each(function(){
				if($(this).attr('noAction')!=null && $(this).is(':checked')){
					$('.noAction').button('option','disabled',true);
				}
			});

		});		
    // fin fonction d'appel fancybox
    
    // gestion de la sélection multiple d'items
    $('input[name=all_checker]').bind('click', function(){
    	check_all = $(this).is(':checked');
		$('.line input[type=checkbox]').each(function(){
			if($(this).is(':checked')!=check_all){
				$(this).trigger('click');
			}
		});
	});
   
   // gestion d'impression de récépissé
   function check_printing()
	{
		var loading_area = $('#laoder_img_band');
		var job_id = loading_area.find('input[name=job_id]').val();
		$.get(site_url+'ajax/request.php?req=15&job_id='+job_id, function(data) {
			var rep = $.parseJSON(data);
			if(rep['status']==1)
			{
				parent.$.fancybox.close();
			}
			else
			{
				//alert(rep['message']);
				setTimeout(check_printing, 5000);
			}
		});	
	}		

    // creating dossier printing job
	$('.r_printer').bind('click', function(e){
		e.preventDefault();
		id_dossier = parseInt($(this).attr('id_dossier'));
		if(!isNaN(id_dossier) && id_dossier>0)
		{
			$.get(site_url+'ajax/request.php?req=14&id_dossier='+id_dossier, function(data) {
			    var obj = $.parseJSON(data);
			    if(obj['status']==1)
			    {
			    	var loading_area = $('#laoder_img_band');
			    	loading_area.find('.title').html('Impression en cours ...');
			    	$.fancybox( loading_area.html() , {
			    		
	        		});
	        		loading_area.append('<input type="hidden" name="job_id"  value="'+obj['job_id']+'"/>');
	        		setTimeout(check_printing, 5000);
			    }
			    else
			    {
			    	alert('Debug : '+obj['message']);	
			    }
						    	
			});
		}
		else
		{
			alert('id_dossier non valide!!!');
		}
		
	});	

	// creating courrier printing job
	$('.r_printerC').bind('click', function(e){
		e.preventDefault();
		id_courrier = parseInt($(this).attr('id_courrier'));
		if(!isNaN(id_courrier) && id_courrier>0)
		{
			$.get(site_url+'ajax/request.php?req=17&id_courrier='+id_courrier, function(data) {
			    var obj = $.parseJSON(data);
			    if(obj['status']==1)
			    {
			    	var loading_area = $('#laoder_img_band');
			    	loading_area.find('.title').html('Impression en cours ...');
			    	$.fancybox( loading_area.html() , {
			    		
	        		});
	        		loading_area.append('<input type="hidden" name="job_id"  value="'+obj['job_id']+'"/>');
	        		setTimeout(check_printing, 5000);
			    }
			    else
			    {
			    	alert('Debug : '+obj['message']);	
			    }
						    	
			});
		}
		else
		{
			alert('id_courrier non valide!!!');
		}
		
	});	

   // fin gestion impression de récépissé


   


});

// Des fonctions utiles
function label(v_for,text)
{
	var label = $('<label>',{
		id:v_for,
		html:text
	});
	label.attr('for',v_for);
	return label;	
}
function input(v_type,v_name,v_id,v_size,v_value)
{
	var input = $('<input>',{
		type:v_type,
		name:v_name,
		id:v_id
		
	});
	input.attr('size',v_size);
	if(v_type!="checkbox")
	input.attr('value',v_value);
	return input;
}
function select(nom,v_id,v_size,option_list)
{
   	var v_select = $('<select>',{
		name:nom,
		id:v_id,
		size:v_size
	});
	var text ='';
	for(var i = 0, j = option_list.length; i<j; i++)
	{
		text+='<option value="'+option_list[i].value+'">'+option_list[i].text+'</option>';
	}
	v_select.html(text);
	return v_select;
}
function img(source,w,h)
{
   var v_img = $('<img>',{
		src:source,
		width:w,
		height:h
   });
   return v_img;
}
function td_content(content)
{
	return $('<td>').append(content);
}
function tr_tds(td_array)
{
	var v_tr= $('<tr>');
	for(var i=0,j=td_array.length;i<j;i++)
	{
		v_tr.append(td_array[i]);
	}
	return v_tr;
}
function table_trs(tr_array)
{
	var v_table = $('<table>');
	for(var i=0,j=tr_array.length;i<j;i++)
	{
		v_table.append(tr_array[i]);
	}
	return v_table;
}
//verifie la duplication de ligne dans un formulaire à ajout dynamique de ligne
function hasDuplicationBefore(line_index)
{
	var retour = false;
	
	for(var i=0;i<line_index;i++)
	{
		var previous_tab = [];
		$('.ln_'+i+' select,.ln_'+i+' input').each(function(){
			previous_tab.push($(this).val());	
		});
		for(var j=i+1;j<line_index;j++)
		{
			// alert('j = '+j);
			var current_tab = [];
			$('.ln_'+j+' select,.ln_'+j+' input').each(function(){
			   current_tab.push($(this).val());	
			});
		  
			retour = false;
			if(previous_tab[0]==current_tab[0]) // l'index 0 determine un facteur important dans ce cas d'utilisation:: s'il est différent, c'est que les produit ne sont pas les même
			{
				retour = true; // limiter ou filtrer les index de cette boucle pour selectionner les champ sur les lesquelle verifier la duplication
				for(var k=1,size = previous_tab.length;k<size;k++)
				{
				  if(previous_tab[k] != current_tab[k])
				  {
					retour = false; break;
				  }
				}
			}
			
			if(retour)
			{
				break; // retour = true veut dire que tous les champs sont egaux
			}
		}
		if(retour)
		{
			break; // retour = true veut dire que tous les champs sont egaux
		}
	}
	return retour;
}

//fonction d'impression d'une zone donnée
function imprime_zone(titre, id_obj)
{			
	// Définie la zone à imprimer
	var headers = '<!DOCTYPE HTML PUBLIC ><HTML><HEAD><title>'+titre+'</title></HEAD><BODY style="color:black; background-color:white; padding:5px;" onload="window.print();">';
	var footers = "</body></html>";
	var content = headers+document.getElementById(id_obj).innerHTML+footers;
	// Ouvre une nouvelle fenetre
	var f = window.open("", "ZoneImpr", "height=500, width=600,toolbar=0, menubar=0, scrollbars=1, resizable=1,status=0, location=0, left=10, top=10");
	f.document.write(" " + content + " ");
	// Imprime et ferme la fenetre
	f.window.print();
	f.window.close();
	return true;
}  		