<?php
/*
 Rôle: liste des postes
 Auteur: CODO Paterne
 Date de création : 11/03/2013
*/   
	
?>
<div align="center">
    <h3> Liste des postes </h3>
	<table border="0" id="dataTable" class="display datatable" style="text-align:left">
        <thead>
            <tr height="30" align="center">
				<th>Désignation</th>
                <th>Direction</th>
                <th>Modifier</th>
				<th>Droits et sécurité</th>
			</tr>
        </thead>
        <?php
		$postes = $db->query('select p.*,d.id id_direction, d.designation direction from poste p left join direction d on p.id_direction=d.id');
        while ($poste = $postes->fetch()) {
			echo '<tr>';
			echo '<td title="'.$poste['description'].'">';
			echo $poste['designation'];
			echo '</td>';
			echo '<td>'.$poste['direction'].'</td>';
			echo '<td>';
			echo '<a href="index.php?m='.$_GET['m'].'&sm=9_update&id_poste='.$poste['id'].'">Modifier</a>';
			echo '</td>';
			echo '<td>';
			echo '<a href="index.php?m='.$_GET['m'].'&sm=10_configurer_poste&id_poste='.$poste['id'].'">Configurer</a>';
			echo '</td>';
			echo '</tr>';
		}
      	?>
    </table>
</div>
