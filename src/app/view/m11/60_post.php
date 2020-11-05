<?php
	
?>
<script>
	$(function(){
			
	});
</script>
<div align="center" style="margin-top:50px;">
	
	<div align="left" class="action_button_list">
		<ul>
			<li><a class="buttonLink fancybox fancybox.iframe" href="<?php echo Systeme::sm_path('60_addPost_form')?>"> Ajouter</a></li>
			
			<li><a class="buttonLink fancybox fancybox.iframe single_action_link action_link" id="update_link" href="<?php echo Systeme::sm_path('60_addPost_form')?>"> Modifier</a></li>

			<li><a class="buttonLink fancybox fancybox.iframe" href="<?php echo Systeme::sm_path('60_disableAllPostSlide_form')?>"> Désactiver tous les défilements</a></li>			
		</ul>
	</div>
	<table border="0" id="dataTable" class="display datatable">
        <thead>
            <tr height="30" align="center">
				<th></th>
				<th>N°</th>
				<th>Sujet</th>
                <th>Message</th>
                <th>Url</th>
                <th>Image</th>
				<th>Enregistrement</th>				
		    </tr>
        </thead>
        <?php
			  $i = 0;		
			  $enabled_state = -1;	  
			  $posts = Post::allCreated($enabled_state, $intervenant->id());	
			  foreach($posts as $post)
			  {
			  	echo '<tr class="line">';
					echo '<td><input type="checkbox" name="dossier_'.$post->id().'" value="'.$post->id().'" /></td>';
					echo '<td>'.(++$i).'</td>';
					echo '<td>'.$post->subject().'</td>';
					echo '<td>'.$post->message().'</td>';
					echo '<td>'.$post->file()->url().'</td>';
					echo '<td><img src="'.$post->file()->url().'" class="thin-image"></td>';				
					echo '<td>'.Systeme::dateTimeToFrench($post->insertDate()).' par '.$post->insertUser()->fullName().'</td>';					
				echo '</tr>';
			  }
		?>
    </table>
</div>