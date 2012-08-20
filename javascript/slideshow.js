(function($){
	$(function(){
		$('#slides').slides({
			preload: true,
			effect: 'fade',
			play: 3000,
			fadeSpeed: 2000,
			generatePagination: false,
			crossfade: true
		});
	});
})(jQuery);