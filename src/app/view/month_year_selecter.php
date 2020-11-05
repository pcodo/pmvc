<script>
	
		function move_year(right)
		{
			var select = document.getElementById('selected_year');
			var start = parseInt((right)?select.options[select.options.length-1].value:select.options[0].value);
			var html = '';
			for(var i=start-5;i<start+5;i++)
			{
				html+='<option>'+i+'</option>';
			}
			select.innerHTML=html;		
		}
	
</script>

<table align="center" style="margin:0px;padding:0px;display:inline-block;">
		<tr>
			<td>
				<select name="selected_month" style="display:inline;">
					<?php
					$months = Systeme::monthlist();
					foreach ($months as $key => $name) {
						if(isset($_POST['selected_month']) && $_POST['selected_month']==$key)
							echo '<option value="'.$key.'" selected="selected">'.$name.'</option>';
						else
							echo '<option value="'.$key.'">'.$name.'</option>';
					}
					?>
				</select>
			</td>
			<td style="padding:0px;text-align:right;"><img class="img_button" src="img/left.png" width="50%" height="50%" onclick="move_year(false);"/></td>
			<td>
				<select id="selected_year" name="selected_year">
					<?php
						$init = (isset($_POST['selected_year']))?$_POST['selected_year']:date('Y');
						for($i=$init-5;$i<$init+5;$i++)
						{
							if((isset($_POST['selected_year'])&&$_POST['selected_year']==$i)||(((isset($_POST['selected_year'])&&$_POST['selected_year']<$i)||!isset($_POST['selected_year']))&&$i==$init))
							echo '<option selected="selected">'.$i.'</option>';
							else
								echo '<option>'.$i.'</option>';
						}
					?>
				</select>
			</td>
			<td><img class="img_button" src="img/right.png" width="50%" height="50%" onclick="move_year(true);"/></td>
		</tr>
</table>