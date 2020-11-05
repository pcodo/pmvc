$(function(){
        var sms_server_full_address = '';
        // get sms_server_full_address througth ajax call
        $.get('ajax/sms_alert_request.php?req=0',function(data)
		{
			//var rep = $.parseJSON(data); // chrome arrête l'execution js à l'appel de cette fonction (non supporté par chrome) mais tout marche bien sur firefox
			var rep = [];
			if(rep['status']==1)
			{
				sms_server_full_address = rep['sms_server_full_address'];							
			}
		});
        
		/*function smsAlert()
		{
			$.get('ajax/sms_alert_request.php?req=1&id_alert_point=0',function(data)
			{
				var rep = $.parseJSON(data);
				//alert(rep['status']);				
			    setTimeout(load_alerte, 10000); 
			});
		}*/
		
		function getForsmsAlert()
		{
			$.get('ajax/sms_alert_request.php?req=2&id_alert_point=0',function(data)
			{
				//var rep = $.parseJSON(data);	
				var rep = [];			
				$.each(rep, function() {
					destinataire = this['telephone'];
					message = this['message'];
					request_url = sms_server_full_address+'&to='+destinataire+'&text='+message;
					//alert(request_url);	
					$.get(request_url, function(obj) {	
															
					});		    			    				
				});			
			    //setTimeout(load_alerte, 10000); 
			});		

		}

		function load_alerte()
		{
			getForsmsAlert();
		}
        
		setTimeout(load_alerte, 60000);// à chaque reload de page, attendre 60 secondes == 1 mn avant d'aller rechercher les alertes sms disponibles
});