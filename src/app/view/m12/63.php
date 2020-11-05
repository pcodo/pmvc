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
			//echo '<li><a  '.(($state_menu==''||$state_menu=='0')?'class="current_state_menu"':'').' href="index.php?m='.$_GET['m'].(isset($_GET['sm'])?'&sm='.$_GET['sm']:'').'&stat_menu=0">Ajout de Demandes</a></li>';
			echo '<li><a  '.(($state_menu==''||$state_menu=='0')?'class="current_state_menu"':'').' href="index.php?m='.$_GET['m'].(isset($_GET['sm'])?'&sm='.$_GET['sm']:'').'&stat_menu=0">Nouvelles demandes</a></li>';
			//echo '<li><a  '.(($state_menu=='1')?'class="current_state_menu"':'').' href="index.php?m='.$_GET['m'].(isset($_GET['sm'])?'&sm='.$_GET['sm']:'').'&stat_menu=1">Demandes validées</a></li>';			
						
					
		?>
	</ul>

</div>


<?php
	
		
		switch($state_menu)
		{
			/*case 0:
				include('63_add_demande.php');
			break;*/
			case 0:
				include('63_new_demandes.php');
			break;
			
			default:
		}
	
?>
