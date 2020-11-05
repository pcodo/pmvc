<?php
/*Rôle : Index du projet VS
  Auteur: CODO Paterne, ingénieur en réseaux informatiques et Internet 
  Date de création:19/02/2013
*/
?>
<div style="display:none;">
	<form id="data_table_pdf_export" action="includes/sm/data_table_pdf_export.php" method="post">
		<div style="background-color:rgb(128,0,0);width:100%;color:white;"> Veuillez sélectionner les colonnes concernées. </div>
		<table>
			<tr>
				<td><input type="text" name="export_title" id="export_title" size="35"/></td>
			</tr>
		</table>
		<table class="options">
			<!-- auto filled from js/dataTable_init -->
		</table>
		<input type="hidden" name="dataTableJsonDataPdf" id="dataTableJsonDataPdf"/>
	</form>
</div>


<div id="data_table_tcpdf_export">
	<div class="main-title" style="background-color:rgb(128,0,0);width:100%;color:white;"></div>
	<table>
		<tr>
			<td><input type="text" name="export_title" id="export_title" size="35"/></td>
		</tr>
	</table>
	<div class="options" style="text-align:left;">
		<!-- auto filled -->
	</div>
	<a class="link"></a>
</div>



	