$(document).ready(function() {
	init();
	$('#search_query').autocomplete({source:'_php/autocomplete.php', minLength:2});

	//Clickable related-tags list
	// $('#related-tags li').on("click", (function() {
 //           alert('Clicked list. ' + $(this).text())
 //   }));
	
	$(document).on( 'click', "#related-tags li", function(){
			//alert('Clicked list. ' + $(this).text());
			var selectTag = $.trim($(this).text());
			var allTags = "";
			$("#searched-tags").append('<li>'+selectTag+'</li>');

			$("#searched-tags li").each(function(){
				allTags += $.trim($(this).text()) + ",";
			})

			allTags = allTags.slice(0,-1);

			$.ajax({
			url: "_php/do_search.php", 
			/*data: {query: selectTag},*/
			data:{query: allTags},
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
		$("#course-results").empty();

		searchval = $('#search_query').val();
		console.log(searchval);
		//Add search tag to searched tags list
		$("#searched-tags").append('<li>'+searchval+'</li>');

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

	console.log(data);

	//Iterate through search tags and add them to the interface
	if(!(data.search.length==0))
	{
		for (var i = 0; i < data.search.length; i++) {
			var sTag = data.search[i];
			$("#searched-tags").append('<li>' + sTag.name + '</li>');
		}
	}
	else{
		$("#searched-tags").append('<li>No Tags</li>');
	}
	
	//Iterate through related tags and add them to the interface
	if(!(data.related.length == 0))
	{
		console.log("Related Tags");
		console.log(data.related);
		$("#related-tags").empty();
		for (var i = 0; i < data.related.length; i++) {
			var rTag = data.related[i];
			console.log("Here", data.related[i]);
			$("#related-tags").append('<li class="tagList">' + rTag.name + '<span>' + rTag.count + '</span></li>');
		}
	}
	else{
		$("#related-tags").empty();
		$("#related-tags").append('<li>No Tags</li>');
	}
	
	//Iterate through search results and add them to the interface
	if(!data.results.length==0)
	{
		var hash = {};
		var rTags="";
		$("#course-results").empty();
		for (var i = 0; i < data.results.length; i++) {
			var sTag = data.results[i];
			//Create all the tags
			var tagsHTML = "";
			for (var u = 0; u < data.results[i].tags.length; u++) {
				console.log(data.results[i].tags[u]);
				var tag = data.results[i].tags[u];
				tagsHTML += '<li>' + tag.name + '<sup>'+tag.count+'</sup></li>';

				//check for duplicate tags
				if(!(tag.name in hash)){
					hash[tag.name] = true;
					rTags +='<li>'+tag.name+'<li>';
				}
			}
			//add the search results to the list
			
			$("#course-results").append('<li><h4>' + sTag.name + '</h4><div>Instructor: ' + sTag.instructor + '</div><p>' + sTag.description + '</p><ul>' + tagsHTML + '</ul></li>');
			//$("#course-results").append('<li><div class="courseNam"><h4>' + sTag.name + '</h4></div><div class="instructor">Instructor: ' + sTag.instructor + '</div><div class="courseDesc"><p>' + sTag.description + '</p></div><ul class="otherTags">' + tagsHTML + '</ul></li>');

			//Add related tags to the list
			//$("#related-tags").append(tagsHTML);
		
		}
		$("#related-tags").empty();
		$("#related-tags").append(rTags);
	}
	else{
		$("#course-results").empty();
		$("#course-results").append('<li><h4>No results found</h4><div></li>');
			
	}
}