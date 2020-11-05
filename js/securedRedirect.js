$(function(){
	$.extend({
		redirect: function(url){
			var site_url = '';
			$.get(site_url+'ajax/security.php?req=0', function(data) {
				token = $.parseJSON(data);		    		
				document.location.href= url+'&sm_token='+$.md5(token);
			});
		}
	});
});
	