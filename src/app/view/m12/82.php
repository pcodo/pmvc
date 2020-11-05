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
			echo '<li><a  '.(($state_menu==''||$state_menu=='0')?'class="current_state_menu"':'').' href="index.php?m='.$_GET['m'].(isset($_GET['sm'])?'&sm='.$_GET['sm']:'').'&stat_menu=0">Position des demandes</a></li>';
			echo '<li><a  '.(($state_menu=='1')?'class="current_state_menu"':'').' href="index.php?m='.$_GET['m'].(isset($_GET['sm'])?'&sm='.$_GET['sm']:'').'&stat_menu=1">Demandes validées</a></li>';			
			echo '<li><a  '.(($state_menu=='2')?'class="current_state_menu"':'').' href="index.php?m='.$_GET['m'].(isset($_GET['sm'])?'&sm='.$_GET['sm']:'').'&stat_menu=2">Demandes rejetées</a></li>';
											
		?>
	</ul>

</div>


<?php
	
		
		switch($state_menu)
		{
			case 0:
				include('82_suivi_demandes.php');
			break;	
			case 1:
				include('82_validated_demandes.php');
			break;
			case 2:
				include('82_rejected_demandes.php');
			break;					
			default:
		}
	
?>
