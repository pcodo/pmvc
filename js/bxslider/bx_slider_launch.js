$(function(){
    var main_slider = $('.bxslider').bxSlider(
	{
		 auto: true,
		 pause:5000, /*5s de pause entre deux slides*/
		 mode: 'horizontal',
		 /*mode: 'fade',*/
		 autoHover:true,
		 useCSS: true,
		 adaptiveHeight:false,
		 captions: true,
		 controls:false,
		 autoControls: false, // enleve les boutons de contôle play/pause des slides
		 pager:false, // Enleve les point de pagination des différents slides
		 autoControlsCombine:true,
		 onSliderLoad:function(){
			if(/Microsoft Internet Explorer|Internet Explorer/.test(navigator.appName))
			{
			  $('#page img').show();
			}
		 },
		onSlideNext: function($slideElement, oldIndex, newIndex){
		  
		}
	});


	$('#miniature td').click(
	  function()
	  {
		main_slider.goToSlide(parseInt($(this).attr('id'))); 
	  }
	);

	var main_slider = $('.bxslider-fade').bxSlider(
	{
		 auto: true,
		 pause:5000, /*5s de pause entre deux slides*/
		 mode: 'fade',
		 autoHover:true,
		 useCSS: true,
		 adaptiveHeight:false,
		 captions: false,
		 controls:false,
		 autoControls: false, // enleve les boutons de contôle play/pause des slides
		 pager:false, // Enleve les point de pagination des différents slides
		 autoControlsCombine:true,
		 onSliderLoad:function(){
			if(/Microsoft Internet Explorer|Internet Explorer/.test(navigator.appName))
			{
			  $('#page img').show();
			}
		 },
		onSlideNext: function($slideElement, oldIndex, newIndex){
		  
		}
	});
	
	$('.banner_slide').bxSlider(
	{
		auto: true,
		pause:5000, /*5s de pause entre deux slides*/
		mode: 'fade',
		autoHover:true,
		pager:false,
		captions: true,
		adaptiveHeight:false,
		controls:false,
		autoControls: false,
		onSlideNext: function($slideElement, oldIndex, newIndex){
		   
		}
	});
});
			


 