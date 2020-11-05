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
			echo '<li><a  '.(($state_menu==''||$state_menu=='0')?'class="current_state_menu"':'').' href="index.php?m='.$_GET['m'].(isset($_GET['sm'])?'&sm='.$_GET['sm']:'').'&stat_menu=0">Départements</a></li>';
			echo '<li><a  '.(($state_menu=='1')?'class="current_state_menu"':'').' href="index.php?m='.$_GET['m'].(isset($_GET['sm'])?'&sm='.$_GET['sm']:'').'&stat_menu=1">Communes</a></li>';
			echo '<li><a  '.(($state_menu=='2')?'class="current_state_menu"':'').' href="index.php?m='.$_GET['m'].(isset($_GET['sm'])?'&sm='.$_GET['sm']:'').'&stat_menu=2">Structures</a></li>';
			echo '<li><a  '.(($state_menu=='3')?'class="current_state_menu"':'').' href="index.php?m='.$_GET['m'].(isset($_GET['sm'])?'&sm='.$_GET['sm']:'').'&stat_menu=3">Salles</a></li>';
			
		?>
	</ul>

</div>


<?php
	
		
		switch($state_menu)
		{
			case 0:
				include('85_departements.php');
			break;
			case 1:
				include('85_communes.php');
			break;
			case 2:
				include('85_structures.php');
			break;
			case 3:
				include('85_salles.php');
			break;
			default:
		}
	
?>
