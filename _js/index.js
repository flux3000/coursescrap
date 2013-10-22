$(document).ready(function() {
	init();
});


function init() {
	initIndexSliders();
}

function initIndexSliders(){
    $(".first").click(function(){
        $(".search_slider").click();
    })
    $(".second").click(function(){
        $(".add_slider").click();
    })
    $(".search_slider").click(function(){
        var aTime = 1000;
        $(this).animate({"width": "100%"}, aTime, function() {
            $(this).animate({"opacity": "0"}, aTime, function() {
				$(this).hide();
            });
        });
        $(".add_slider").animate({"width": "0%"}, aTime, function(){
			$(this).hide();
        });
        $("body").append('<iframe src="search.php"></iframe>');
        $('header').animate({
            'opacity':'0'
        }, aTime/3, function(){
			$(this).hide();
        });
    });
    $(".add_slider").click(function(){
        var aTime = 1000;
        $(this).animate({"width": "100%"}, aTime, function() {
            $(this).animate({"opacity": "0"}, aTime, function() {
				$(this).hide();
            });
        });
        $(".search_slider").animate({"width": "0%"}, aTime, function(){
			$(this).hide();
        });
        $("body").append('<iframe src="tag.php"></iframe>');
        $('header').animate({
            'opacity':'0'
        }, aTime/3, function(){
			$(this).hide();
        });
    });
}

function showevent(){
	//Show all elements again and reverse the hide animations
	$(".search_slider").show().css("opacity","1").animate({
		"width": "50%"
	});
	$(".add_slider").show().css("opacity","1").animate({
		"width": "50%"
	});
	$('header').show().animate({
        'opacity':'1'
    });
	$("iframe").remove(); //remove the old iframe
}