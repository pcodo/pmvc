<?php
/*
 Auteur: CODO Paterne
 Date de création : 11/03/2013
*/
	
?>
<div align="center">
    <h3> Liste des membres du personnel </h3>
	<table border="0" id="dataTable" class="display datatable">
        <thead>
            <tr height="30" align="center">
				<th>N°</th>
                <th>Login</th>
                <th>Nom</th>
                <th>Prénoms</th>
				<th>Direction</th>
				<th>Poste</th>
				<th>Détails</th>
				<th>Modifier</th>
				<th>Enregistrement</th>
				<th>Supprimer</th>
				<th>ID (db)</th>
            </tr>
        </thead>
        <?php
		$i=0;
        $intervenants = Systeme::userList();
        foreach($intervenants as $membre) {
			echo '<tr>';
			echo '<td>'.(++$i).'</td>';
                        echo '<td>'.$membre['login'].'</td>';
			echo '<td>'.$membre['nom'].'</td>';
			echo '<td>'.$membre['prenom'].'</td>';
			echo '<td>'.$membre['direction'].'</td>';
			echo '<td>'.$membre['poste'].'</td>';
			echo '<td><a href="index.php?m='.$_GET['m'].'&sm=8_fiche&id_membre='.$membre['id'].'">Fiche</a></td>';
			echo '<td><a href="index.php?m='.$_GET['m'].'&sm=8_update&action_source=user_plus&id_membre='.$membre['id'].'">Modifier</a></td>';
			echo '<td>'.Systeme::dateTimeToFrench($membre['date_enregistrement']).'</td>';
			echo '<td><a style="color:red;" href="index.php?m='.$_GET['m'].'&sm=8_delete&action_source=user_plus&id_membre='.$membre['id'].'">Supprimer</a></td>';
			echo '<td>'.$membre['id'].'</td>';
			echo '</tr>';
        }
      	
		?>
    </table>
</div>
