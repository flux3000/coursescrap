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
	
	//Iterate through search tags and add them to the interface
	for (var i = 0; i < data.search.length; i++) {
		var sTag = data.search[i];
		$(".usedTags").append('<li>' + sTag.name + '</li>');
	}
	
	//Iterate through related tags and add them to the interface
	for (var i = 0; i < data.related.length; i++) {
		var rTag = data.related[i];
		$(".relatedTags").append('<li>' + rTag.name + '<span>' + rTag.score + '</span></li>');
	}
	
	//Iterate through search results and add them to the interface
	for (var i = 0; i < data.results.length; i++) {
		var sTag = data.results[i];
		//Create all the tags
		var tagsHTML = "";
		for (var u = 0; u < data.results[i].tags.length; u++) {
			console.log(data.results[i].tags[u]);
			var tag = data.results[i].tags[u];
			tagsHTML += '<li>' + tag.name + '</li>';
		}
		//add the search results to the list
		$(".courseList").append('<li><h4>' + sTag.name + '</h4><div>Instructor: ' + sTag.instructor + '</div><p>' + sTag.description + '</p><ul>' + tagsHTML + '</ul></li>');
	}
}