/**
 * CHANGES
 * v.2.1.2 - Fixed bug in which nested fieldsets do not work correctly.
 * v.2.1.1 - Forgot to put the new filter from v.2.1 into the if (settings.closed)
 * v.2.1 - Changed $(this).parent().children().filter( ELEMENTS HERE) to $(this).parent().children().not('label').  Prevents you from having to guess what elements will be in the fieldset.
 * v.2.0 - Added settings to allow a fieldset to be initiated as closed.
 *
 * This script may be used by anyone, but please link back to me.
 *
 * Copyright 2009-2010.  Michael Irwin (http://michael.theirwinfamily.net)
 */
       
$.fn.collapse = function(options) {
	var defaults = {
		closed : false
	}
	settings = $.extend({}, defaults, options);

	return this.each(function() {
		var obj = $(this);
		obj.find("legend:first").addClass('collapsible').click(function() {
			if (obj.hasClass('collapsed'))
				obj.removeClass('collapsed').addClass('collapsible');
	
			$(this).removeClass('collapsed');
	
			obj.children().not('legend').slideToggle("fast", function() {
			 
				 if ($(this).is(":visible"))
					obj.find("legend:first").addClass('collapsible');
				 else
					obj.addClass('collapsed').find("legend").addClass('collapsed');
			 });
		});
		if (settings.closed) {
			obj.addClass('collapsed').find("legend:first").addClass('collapsed');
			obj.children().not("legend:first").css('display', 'none');
		}
	});
};


$(document).ready(function() {
	$("fieldset.collapsible").collapse();
	$("fieldset.collapsibleClosed").collapse( { closed: true } );
});