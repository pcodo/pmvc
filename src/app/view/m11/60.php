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
			echo '<li><a  '.(($state_menu==''||$state_menu=='0')?'class="current_state_menu"':'').' href="index.php?m='.$_GET['m'].(isset($_GET['sm'])?'&sm='.$_GET['sm']:'').'&stat_menu=0">Mes publications</a></li>';
			//echo '<li><a  '.(($state_menu=='1')?'class="current_state_menu"':'').' href="index.php?m='.$_GET['m'].(isset($_GET['sm'])?'&sm='.$_GET['sm']:'').'&stat_menu=1">Toutes les publications</a></li>';
			
		?>
	</ul>

</div>


<?php
	switch($state_menu)
	{
		case 0:
			include('60_post.php');
		break;				
		default:
	}
	
?>
