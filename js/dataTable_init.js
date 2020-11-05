$(function(){

	$('#dataTable_selling_view').dataTable({
		"bJQueryUI": true,
		"scrollY":        "400px",
        "scrollCollapse": true,
        "paging": false
	});
	/*plugin des tableaux jquery*/
	var oTable = $('.datatable').dataTable({
		/*dom: 'T<"clear">lfrtip',//Permet de créer des boutons pour l'exportation, l'impression des données des dataTable: Detruit l'affichage des dataTable UI*/
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",
		"pageLength": 50,
		
		"language": {
            "sProcessing":   "Traitement en cours...",
			"sLengthMenu":   "Afficher _MENU_ &eacute;l&eacute;ments",
			"sZeroRecords":  "Aucun &eacute;l&eacute;ment &agrave; afficher",
			"sInfo":         "Affichage de _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
			"sInfoEmpty":    "Affichage de 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
			"sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
			"sInfoPostFix":  "",
			"sSearch":       "Rechercher :",
			"sUrl":          "",
			"oPaginate": {
			"sFirst":    "Premier",
			"sPrevious": "Pr&eacute;c&eacute;dent",
			"sNext":     "Suivant",
			"sLast":     "Dernier"
			},
			"fnInfoCallback": null
        }
    });

    
	var dataTablePdfExportDialog = $('#data_table_pdf_export').dialog({
		modal:true,
		autoOpen: false,
		buttons:{
			"OK":function(){
				if($('#dataTableJsonDataPdf').val()!='')
				{
					$(this).submit();
				    $(this).dialog( "close" );
				}
				else
				{
					$(this).dialog( "close" );
				}
			}
		}
	});

	var dataTablePdfExportDialog_options = $('#data_table_pdf_export .options');
	// Création des boutons d'exportation ou d'impression des données dataTable en PDF
	// 1 ère code : marche bien en mettant en haut de chaque dataTable le bouton : Mais pour aller plus loin, j'ai besoin de gerer les clic du bouton et de traiter les données du dataTable concerné : d'où le second code
	// var ligne = tr_tds([td_content(label('','Exporter en PDF').button())]);
	// var tble = table_trs([ligne]).attr('align','right');
	// $(".display").before(tble);

	// 2nd code :: marche :
	/*
	$(".datatable").each(function(index){
		var t=$(this).DataTable();
		var current = $(this);
		var t_html = '';
		var ligne = tr_tds([td_content(label('','Exporter en PDF').button().click(function() {
		var ln_size = t.rows().nodes().length;
		var col_size = t.columns().nodes().length;
		var good_data_format = false;
		var data = [] ; // on peu l'initialiser avec data = {} . dans ce cas on fera data[i] = .. et non data.push :: Dans les deux cas, le tableau peut être sérialiser avec la fonction JSON.stringify
		var header_th = current.find('th'); // On recupère le nom des colonnes pour servir d'entête pour le tableau
		var header_to_export_checker_html = '<table style="text-align:left;">';
			if(header_th.length==col_size&&col_size!=0) // on vérifie bien que le nombre de colonnes recupéré est bien egal à ce que l'objet DataTable renvoit
			{
				var line = [];
				good_data_format = true;
				for(var j = 0; j < col_size; j++){
					line.push(header_th[j].textContent);
					header_to_export_checker_html+='<tr><td><select name="al_'+j+'"><option value="L">Left</option><option value="C">Center</option><option value="R">Right</option></select></td><td><input type="checkbox" checked="checked" name="col_'+j+'" id="col_'+j+'"/></td><td><label for="col_'+j+'">'+header_th[j].textContent+'</label></td></tr></label>';
				}
				data.push(line);
				header_to_export_checker_html+='</table>';
			}
			for(var i = 0; i < ln_size; i++){
				var line = [];
				if(t.rows().nodes()[i].childNodes.length==col_size){
					for(var j = 0; j < col_size; j++){
						line.push(t.rows().nodes()[i].childNodes[j].textContent);
					}
					data.push(line);
				} else good_data_format = false;
			}
			var tag = '``';
			$('#dataTableJsonDataPdf').val(JSON.stringify(data).replace(/"/g,tag)); // le g permet de tout remplacer
			// dataTablePdfExportDialog.append($(header_to_export_checker_html));
			dataTablePdfExportDialog_options.html('');
			dataTablePdfExportDialog_options.append($(header_to_export_checker_html));
			if(good_data_format) dataTablePdfExportDialog.dialog('open');
			else $.alert('Les données contenues dans le tableau ne sont pas adaptées pour être exportées sous forme d\'une liste de données!');
		}))]);
		var tble = table_trs([ligne]).attr('align','right');
		current.before(tble);
	});
	*/
// Pour les tableaux simples  ... l'objet dataTable n'est pas utilisé ici pour récupérér les données.	
	$(".exportableData").each(function(index){
		var current = $(this);
		var ligne = tr_tds([td_content(label('','Exporter en PDF').button().click(function() {
			var trs =  current.find('tr');
			var ln_size = trs.length;
			var col_size = $(trs[1]).find('td').length;
			var good_data_format = false;
			var data = [] ; // on peu l'initialiser avec data = {} . dans ce cas on fera data[i] = .. et non data.push :: Dans les deux cas, le tableau peut être sérialiser avec la fonction JSON.stringify
			var header_th =  $(trs[0]).find('th'); // On recupère le nom des colonnes pour servir d'entête pour le tableau
			if(header_th.length == 0) header_th =  $(trs[0]).find('td');
			var header_to_export_checker_html = '<table style="text-align:left;">';
			if(header_th.length==col_size&&col_size!=0) // on vérifie bien que le nombre de colonnes recupéré est bien egal à ce que l'objet DataTable renvoit
			{
				var line = [];
				good_data_format = true;
				for(var j = 0; j < col_size; j++){
					line.push(header_th[j].textContent);
					header_to_export_checker_html+='<tr><td><select name="al_'+j+'"><option value="L">Left</option><option value="C">Center</option><option value="R">Right</option></select></td><td><input type="checkbox" checked="checked" name="col_'+j+'" id="col_'+j+'"/></td><td><label for="col_'+j+'">'+header_th[j].textContent+'</label></td></tr></label>';
				}
				data.push(line);
				header_to_export_checker_html+='</table>';
			}
			for(var i = 1; i < ln_size; i++){ // On commence à partir de 1 ici car la première ligne a déjà été récupérée comme entete.
				var line = [];
				col_datas = $(trs[i]).find('td');
				if(col_datas.length==col_size) {
					for(var j = 0; j < col_size; j++){
						line.push(col_datas[j].textContent);
					}
					data.push(line);
				} else good_data_format = false;
			}
			var tag = '``';
			$('#dataTableJsonDataPdf').val(JSON.stringify(data).replace(/"/g,tag)); // le g permet de tout remplacer
			// dataTablePdfExportDialog.append($(header_to_export_checker_html));
			dataTablePdfExportDialog_options.html('');
			dataTablePdfExportDialog_options.append($(header_to_export_checker_html));
			if(good_data_format) dataTablePdfExportDialog.dialog('open');
			else $.alert('Les données contenues dans le tableau ne sont pas adaptées pour être exportées sous forme d\'une liste de données!');
			
		}))]);
		var tble = table_trs([ligne]).attr('align','right');
		current.before(tble);
	});
			
	
});