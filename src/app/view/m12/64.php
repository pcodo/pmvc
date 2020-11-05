<?php
	$state_menu = 0;
	if(isset($_GET['stat_menu']))
	{
		$state_menu = $_GET['stat_menu'];
	}
?>

<div id="stat_menu_list">
	<ul>
		<?php
			echo '<li><a  '.(($state_menu==''||$state_menu=='0')?'class="current_state_menu"':'').' href="index.php?m='.$_GET['m'].(isset($_GET['sm'])?'&sm='.$_GET['sm']:'').'&stat_menu=0">Demandes à valider</a></li>';
			echo '<li><a  '.(($state_menu=='1')?'class="current_state_menu"':'').' href="index.php?m='.$_GET['m'].(isset($_GET['sm'])?'&sm='.$_GET['sm']:'').'&stat_menu=1">Demandes validées</a></li>';	
			echo '<li><a  '.(($state_menu=='2')?'class="current_state_menu"':'').' href="index.php?m='.$_GET['m'].(isset($_GET['sm'])?'&sm='.$_GET['sm']:'').'&stat_menu=2">Demandes rejetées</a></li>';								
		?>
	</ul>

</div>


<?php
	
		
		switch($state_menu)
		{
			case 0:
				include('64_new_demandes_for_validation.php');
			break;
			case 1:
				include('64_validated_demandes.php');
			break;	
			case 2:
				include('64_rejected_demandes.php');
			break;			
			default:
		}
	
?>
