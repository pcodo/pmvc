$(function(){
	/*plugin des tableaux jquery*/
	var oTable = $('.display').dataTable({
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
	
	// Création des boutons d'exportation ou d'impression des données dataTable en PDF
	// 1 ère code : marche bien en mettant en haut de chaque dataTable le bouton : Mais pour aller plus loin, j'ai besoin de gerer les clic du bouton et de traiter les données du dataTable concerné : d'où le second code
	// var ligne = tr_tds([td_content(label('','Exporter en PDF').button())]);
	// var tble = table_trs([ligne]).attr('align','right');
	// $(".display").before(tble);

	// 2nd code :: marche :
	$(".display").each(function(index){
		var t=$(this).DataTable();
		var current = $(this);
		var t_html = '';
		var ligne = tr_tds([td_content(label('','Exporter en PDF').button().click(function() {
		    var ln_size = t.rows().nodes().length;
			var col_size = t.columns().nodes().length;
			var data = [] ; // on peu l'initialiser avec data = {} . dans ce cas on fera data[i] = .. et non data.push :: Dans les deux cas, le tableau peut être sérialiser avec la fonction JSON.stringify
			for(var i = 0; i < ln_size; i++){
				var line = [];
				for(var j = 0; j < col_size; j++){
					line.push(t.rows().nodes()[i].childNodes[j].textContent);
				}
				data.push(line);
			}
			var tag = '``';
			$('#dataTableJsonDataPdf').val(JSON.stringify(data).replace(/"/g,tag)); // le g permet de tout remplacer
			dataTablePdfExportDialog.dialog('open');
		}))]);
		var tble = table_trs([ligne]).attr('align','right');
		current.before(tble);
	});
			
	
});