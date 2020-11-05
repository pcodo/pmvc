<?php
/*Rôle : Index du projet VS
  Auteur: CODO Paterne, ingénieur en réseaux informatiques et Internet 
  Date de création:19/02/2013
*/
?>
<div style="display:none;">
	<div id="sm_access_dialog">
			<input type="password" name="sm_mdp"/>
	</div>
</div>

<script>
$(function(){
	var dt = new Date();
    //var time = dt.getDate() + '--' + (dt.getMonth()+1) + '--'+ dt.getFullYear()+ ' ' + dt.getHours() + ":" + dt.getMinutes();
	var time = dt.getDate() + '--' + dt.getFullYear()+ ' ' + dt.getHours() + ":" + dt.getMinutes();
	/*Chargement de quelques parametres du système*/
	var sys_params = {};
	$.get(site_url+'ajax/request.php?req=10', function(data) {
		var obj = $.parseJSON(data);
		var sm_by_id =  {};
		var loaded_sm = obj.sm;
		for(var i=0,j=loaded_sm.length;i<j;i++){
			sm_by_id[loaded_sm[i]['id']] = loaded_sm[i];
		}
		sys_params['sm_by_id'] = sm_by_id;
		sys_params['static_params'] = obj.static_params;
		// alert(sys_params['sm_by_id']['4']['description']); // affichera la description du sous menu d'id = 4
	});
	
	/*Préparation du dialog*/
	 var global_current_m = 0, global_current_sm=0;
	 var smAccessConfirmDialog = $('#sm_access_dialog').dialog({
		modal:true,
		autoOpen: false,
		buttons:{
			"OK":function(){
				if(sys_params['static_params']['sm_access_psswd']==$('input[name=sm_mdp]').val())
				{
				    if(!isNaN(global_current_m)&&!isNaN(global_current_sm)&&global_current_m!=0&&global_current_sm!=0)
				    {
				    	$(this).dialog( "close" );
				    	$.redirect('index.php?m='+global_current_m+'&sm='+global_current_sm);				    	
				    }
				    else
				    {
				    	$('input[name=sm_mdp]').val('');
				    }
					
				}
				else
				{
					$('input[name=sm_mdp]').val('');
				}
			}
		}
	});
	 /* Le click sur un sous menu*/
	 $('#left_menu .sm,#left_menu .current_sm').click(function(event){
	    event.preventDefault();
		if(sys_params['static_params']['sm_access_psswd']!='')
		{
			var is_protected_sm = sys_params['sm_by_id'][$(this).attr('sm')]['on_passwd_access']==1;
			if(is_protected_sm)
			{
				event.preventDefault();
				global_current_m = parseInt($(this).attr('m'));
				global_current_sm = parseInt($(this).attr('sm'));
				smAccessConfirmDialog.dialog('open');
				return;
			}
			else
			{
				$.redirect('index.php?m='+$(this).attr('m')+'&sm='+$(this).attr('sm'));
			}
			
		}
		else
		{
			$.redirect('index.php?m='+$(this).attr('m')+'&sm='+$(this).attr('sm'));
		}
		
	});	 
});
</script>

	