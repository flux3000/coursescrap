$(document).ready(function() {
	init();
	/*Ashley: Implement the autocomplete functionality on the 'Search' box on the Search page and on the 'Add Tag' box on the 'Tag' page. This is achieved by using the autocomplete jQuery UI widget*/
	$('#search_query').autocomplete({source:'_php/autocomplete.php', minLength:2});	
	$('.addTagText').autocomplete({source:'_php/autocomplete.php', minLength:1});

	/* Ryan 
    $(".course-listing").on("click", ".course-listing-header.contracted", function() {
    	$(".course-listing-content").slideUp();
    	$(this).removeClass("contracted");
    	$(this).addClass("expanded");
    	$(this).siblings(".course-listing-content").slideDown();
    });    
    $(".course-listing").on("click", ".course-listing-header.expanded", function() {
    	$(".course-listing-content").slideUp();
    	$(this).removeClass("expanded");
    	$(this).addClass("contracted");
    });*/
	
	$(document).on( 'click', "#related-tags li", function(){
			var selectTag = $.trim($(this).attr("val"));
			var allTags = "";
			$("#searched-tags").append('<li>'+selectTag+'</li>');
			
			/*Ashley: Iterate through all the Searched Tags. Store the tags in the 'allTags' variable.*/
			$("#searched-tags li").each(function(){
				allTags += $.trim($(this).text()) + ",";
			})
			
			/*Ashley: Remove the last ',' from the last 'Searched Tag'*/
			allTags = allTags.slice(0,-1);
			
			/*Ashley: Make an AJAX POST request to the do_search.php file. Pass 'allTags' as data.*/
			$.ajax({
				url: "_php/do_search.php", 
				data:{query: allTags},
				dataType: 'json', 
				type: "POST",
				success: function(data){
					searchReturn(data);				
				},
				error: function(data){
					console.log("ERROR: " + data);
				}
			});
		});
	
	/*Ashley: Event Handler for 'click' event of the 'Add Tag' button in the 'Tag' page.*/
	$(".add-tag-form").on("click", ".addTagBtn", function(event){
		/*Ashley: Prevent Default Action*/
		event.preventDefault();
		
		/*Ashley: Check if the 'Select a Tag' field in the 'Tag' page has been populated*/
		if($(this).siblings(".addTagText").val()){
			/*Ashley: Capture the value from the 'Select a Tag' field as well as the value from the hidden 'Course Id' field.*/
			var tagName = $(this).siblings(".addTagText").val(),
				courseId = $(this).siblings(".courseId").val();
			
			/*Ashley: Make an AJAX POST request to the add_tag.php file. Send the tag name and the corresponding course id as data.*/
			$.ajax({
				url: "_php/add_tag.php",
				type: "POST",
				data:{tagName: tagName, courseId: courseId},
				success: function(data){
					/*Ashley: If the data was successfully inserted, reload the page*/
					if((data == "Tag inserted successfully." || data == "Tag updated successfully.")){
						location.reload(forceGet = true);
					}else{/*Ashley: Else display the message returned from the add_tag.php file back to the user.*/
						$("#resultsFeedback").text(data);
						$("#resultsFeedback").fadeIn(80);
					}
				},
				error: function(event){
					console.log("ERROR: " + event.message);			
				}
			});
		}
	});
	
	/*Ashley: Event Handler for the 'mouseover' event on the 'Searched Tags' list elements*/
    $("#searched-tags").on("mouseover", "li", function(event){
		/*Ashley: Prevent Default Action*/
		event.preventDefault();
		/*Ashley: Add the title attribute to the Hovered over Searched Tag.*/
		$(this).attr("title", "Click to remove");
		/*Ashley: Event Handler for the 'mouseleave' event on the 'Searched Tags' list elements*/
	}).on("mouseleave", "li", function(event){
		/*Ashley: Prevent Default Action*/
		event.preventDefault();
		/*Ashley: Remove the title attribute from the Searched Tag element which we are leaving.*/
		$(this).attr("title", "");
		/*Ashley: Event Handler for the 'click' event on the 'Searched Tags' list elements*/
	}).on("click", "li", function(){
		/*Ashley: Prevent Default Action*/
		event.preventDefault();
		/*Ashley: Remove the Searched Tag element.*/
		$(this).remove();
		/*Ashley: Create a new variable to capture the Tags that remain in the 'Searched Tags' list.*/
		var remainingTags = "";
		/*Ashley: Iterate through all the Searched Tags. Store the tags in the 'remaingingTags' variable.*/
		$("#searched-tags li").each(function(){
			remainingTags += $.trim($(this).text()) + ",";
		})
		/*Ashley: Remove the last ',' from the last 'Searched Tag'*/
		remainingTags = remainingTags.slice(0,-1);
		
		/*Ashley: Make an AJAX POST request to the do_search.php file. Pass 'remainingTags' as data.*/
		$.ajax({
			url: "_php/do_search.php", 
			data:{query: remainingTags},
			dataType: 'json', 
			type: "POST",
			success: function(data){
				searchReturn(data);				
			},
			error: function(data){
				console.log("ERROR: " + data.message);
			}
		});
	});
});


function init() {
	initSearch();

	//Backbutton clicked
	$(".back").click(function(){
		parent.showevent();
	})

}


function initSearch(){
	var searchval;
	/*Ashley: Event Handler for the 'click' event on the 'Search' button*/
	$("#searchBtn").click(function(e){
		/*Ashley: Prevent Default Action*/
		e.preventDefault();
		/*Ashley: Remove all the children from the 'Searched Tags' and 'Course Results' unordered lists.*/
		$("#course-results").empty();
		$("#searched-tags").empty();
		/*Ashley: Store the 'Search Tag' value entered by the user in the 'Search' field to the searchval variable.*/
		searchval = $('#search_query').val();
			
		/*Ashley: Make an AJAX POST request to the add_tag.php file. Send the tag name and the corresponding course id as data.*/
		$.ajax({
			url: "_php/add_tag.php",
			type: "POST",
			data:{query: searchval},
			success: function(data){
				/*Ashley: If the tag not found, inform the user of the same.*/
				if(data !== "OK to proceed"){
					$("#searchTagFeedback").text(data);
					return false;
				}else{
					$("#searchTagFeedback").text("");
					//Add search tag to searched tags list
					$("#searched-tags").append('<li>'+searchval+'</li>');
					/*Ashley: Make an AJAX POST request to the do_search.php file. Pass 'searchval' as data.*/
					$.ajax({
						url: "_php/do_search.php", 
						data: {query: searchval},
						dataType: "json", 
						type: "POST",
						success: function(data){
							searchReturn(data);				
						},
						error: function(data){
							console.log("ERROR" + data);			
						}
					});
				}
			},
			error: function(event){
				console.log("ERROR: " + event.message);			
			}
		});

	});
}

function searchReturn(data){
	/*Ashley: Create an array to hold the Searched Tags*/
	var searchTermsArray = [];
	/*Ashley: Iterate through all the Searched Tags. Store the tags in the 'searchTermsArray' array.*/
	$("#searched-tags li").each(function(){
		searchTermsArray.push($.trim($(this).text()));
	});
	
	/*Ashley: Check if there is 'related tags' data returned from the php file.*/
	if(data.related)
	{
		/*Ashley: Remove all the children from the 'Related Tags' unordered list.*/
		$("#related-tags").empty();
		/*Ashley: Iterate through 'related tags' object*/
		for (var tag in data.related) {
			/*Ashley: Check if the 'related tag' is present in the 'searchTermsArray', the array which contains the Searched Tags.*/
			/*Ashley: If the 'related tag' is not found in the 'searchTermsArray', add that tag to the 'Related Tags' unordered list.*/
			if($.inArray(tag, searchTermsArray) === -1)
				$("#related-tags").append('<li class="tagList" val="' + tag + '">' + tag + '\t' + data.related[tag] + '</li>');
		}
	/*Ashley: Else, remove all the children from the 'Related Tags' unordered list and append a List Item with a 'No Tags' value.*/
	}else{
		$("#related-tags").empty();
		$("#related-tags").append('<li>No Tags</li>');
	}
	/*------*/
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

			$("#course-results").append('<li><h4><a class="course" href="http://www.ischool.berkeley.edu/courses/' + sTag.resource_id + '" target="_new">' + sTag.resource_id + ' - ' + sTag.name + '</a></h4><div>Instructor: ' + sTag.instructor + '</div><p>' + sTag.description + '</p><ul>' + tagsHTML + '</ul></li>');
		}
	}
	else{
		$("#course-results").empty();
		$("#course-results").append('<li><h4>No results found</h4><div></li>');
			
	}
}