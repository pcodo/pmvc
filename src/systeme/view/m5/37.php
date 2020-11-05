<?php
/*
 Auteur: CODO Paterne
 Date de création : 11/03/2013
*/
	
?>
<div align="center">
    <h3> Liste des ex membres utilisateurs du système </h3>
	<table border="0" id="dataTable" class="display datatable">
        <thead>
            <tr height="30" align="center">
				<th>N°</th>
                <th>Nom</th>
                <th>Prénoms</th>
				<th>Direction</th>
				<th>Poste</th>
				<th>Détails</th>
				<th>Modifier</th>
				<th>Supprimer</th>
            </tr>
        </thead>
        <?php
		$i=0;
        $intervenants = Systeme::adminlist();
        foreach($intervenants as $membre) {
			echo '<tr>';
			echo '<td>'.(++$i).'</td>';
			echo '<td>'.$membre['nom'].'</td>';
			echo '<td>'.$membre['prenom'].'</td>';
			echo '<td>'.$membre['direction'].'</td>';
			echo '<td>'.$membre['poste'].'</td>';
			echo '<td><a href="index.php?m='.$_GET['m'].'&sm=8_fiche&id_membre='.$membre['id'].'">Fiche</a></td>';
			echo '<td><a href="index.php?m='.$_GET['m'].'&sm=8_update&id_membre='.$membre['id'].'">Modifier</a></td>';
			echo '<td><a style="color:red;" href="index.php?m='.$_GET['m'].'&sm=8_delete&id_membre='.$membre['id'].'">Supprimer</a></td>';
			echo '</tr>';
        }
      	
		?>
    </table>
</div>
