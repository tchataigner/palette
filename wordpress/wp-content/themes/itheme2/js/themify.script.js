;// Themify Theme Scripts - http://themify.me/

jQuery(document).ready(function($){

	/////////////////////////////////////////////
	// Scroll to top 							
	/////////////////////////////////////////////
	$('.back-top a').click(function () {
            $('body,html').animate({scrollTop: 0}, 800);
            return false;
	});

	/////////////////////////////////////////////
	// Toggle menu on mobile 							
	/////////////////////////////////////////////
	$("#menu-icon").click(function(){
            $("#headerwrap #main-nav").fadeToggle();
            $("#headerwrap #searchform").hide();
            $(this).toggleClass("active");
	});

	/////////////////////////////////////////////
	// Toggle searchform on mobile 							
	/////////////////////////////////////////////
	$("#search-icon").click(function(){
            $("#headerwrap #searchform").fadeToggle();
            $("#headerwrap #main-nav").hide();
            $('#headerwrap #s').focus();
            $(this).toggleClass("active");
	});
});