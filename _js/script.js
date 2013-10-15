$(document).ready(function() {
	init();
	$('#search_box').autocomplete({source:'_php/autocomplete.php', minLength:2});
    
});

function init() {
	initIndexSliders();
	initSearch();
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

function initSearch(){
	$("#searchBtn").click(function(e){
		e.preventDefault();
		$.ajax({
			url: "_php/do_search.php",
			success: searchReturn
		})
	});
}
function searchReturn(input){
    data = JSON.parse(input);
	//console.log(data);
	
	var search = data.search;
	var related = data.related;
	var results = data.results;
	
	$(".usedTags").append();
	$(".tagCloud").append();
	$(".courseList").append();
}