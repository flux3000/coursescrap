$(document).ready(function() {
	init();
	//$('#search_query').autocomplete({source:'_php/autocomplete.php', minLength:2});
    
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
	var searchval;
	$("#searchBtn").click(function(e){
		e.preventDefault();
		searchval = $('#search_query').val();
		//console.log($('#search_query').val());
		$.ajax({
			url: "_php/do_search.php", 
			data: {query: searchval},
			dataType: 'json', 
			type: "POST",
			success: function(data){
				searchReturn(data);				
			},
			error: function(data){
				console.log("ERROR");
				console.log(data);			
			}
		})
	});
}

function searchReturn(data){

    data = JSON.parse(data);
	console.log("PARSED DATA:");
	console.log(data);

	
	//Iterate through search tags and add them to the interface
	for (var i = 0; i < data.search.length; i++) {
		var sTag = data.search[i];
		$("#searched-tags").append('<li>' + sTag.name + '</li>');
	}
	
	//Iterate through related tags and add them to the interface
	for (var i = 0; i < data.related.length; i++) {
		var rTag = data.related[i];
		$("#related-tags").append('<li>' + rTag.name + '<span>' + rTag.count + '</span></li>');
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
		$("#course-results").append('<li><h4>' + sTag.name + '</h4><div>Instructor: ' + sTag.instructor + '</div><p>' + sTag.description + '</p><ul>' + tagsHTML + '</ul></li>');
	}
}