(function ( $ ) {
	"use strict";

	$(function () {

		$( document ).ready(function() {	
			var contheight = jQuery( ".enquiry-content" ).outerHeight()+2;      	
	      	jQuery( ".sliding-enquiry" ).css( "bottom", "-"+contheight+"px" );

	      	jQuery( ".sliding-enquiry" ).css( "visibility", "visible" );

	      	jQuery('.sliding-enquiry').addClass("open_sliding_enquiry");
	      	jQuery('.sliding-enquiry').addClass("enquiry-content-bounce-in-up");
	      	
	        jQuery( ".enquiry-header" ).click(function() {
	        	if(jQuery('.sliding-enquiry').hasClass("open"))
	        	{
	        		jQuery('.sliding-enquiry').removeClass("open");
	        		jQuery( ".sliding-enquiry" ).css( "bottom", "-"+contheight+"px" );
	        	}
	        	else
	        	{
	        		jQuery('.sliding-enquiry').addClass("open");
	          		jQuery( ".sliding-enquiry" ).css( "bottom", 0 );		
	        	}
	          
	        });		    
		});
	});

}(jQuery));