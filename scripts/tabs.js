$(document).ready(function() {
	$(".i-tab-content").hide();  
	$("ul.i-tabs li:first").addClass("active-tab").show();  
	$(".i-tab-content:first").show();  
  
	$("ul.i-tabs li").click(function()  
	   {  
		$("ul.i-tabs li").removeClass("active-tab");  
		$(this).addClass("active-tab");  
		$(".i-tab-content").hide();  
  
		var activeTab = $(this).find("a").attr("href");  
		$(activeTab).fadeIn();  
		return false;  
	});  
});