$(document).ready(function() {
	init();
	$('#search_box').autocomplete({source:'_php/autocomplete.php', minLength:2});
    
});

function init() {
	initIndexSliders();
}

function initIndexSliders(){
	$(".search_slider").click(function(){
		var aTime = 1000;
		$(this).animate({"width": "100%"}, aTime, function() {
			$(this).animate({"opacity": "0"}, aTime, function() {
				$(this).remove();
			});
		});
		$(".add_slider").css("overflow", "hidden").animate({"width": "0%"}, aTime, function(){
			$(this).remove();
		});
		$("body").append('<iframe src="search.php"></iframe>');
	});
	$(".add_slider").click(function(){
		var aTime = 1000;
		$(this).animate({"width": "100%"}, aTime, function() {
			$(this).animate({"opacity": "0"}, aTime, function() {
				$(this).remove();
			});
		});
		$(".search_slider").css("overflow", "hidden").animate({"width": "0%"}, aTime, function(){
			$(this).remove();
		});
		$("body").append('<iframe src="add.php"></iframe>');
	});
}
