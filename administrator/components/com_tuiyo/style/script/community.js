(function($){
	var
	showMessage = function(report, reportType){
		if(typeof reportType ==='undefined') reportType = 'message';
		$("div.reporter").empty();
		$("div.reporter").appendDom([
			{tagName:'dl',id:'system-message',childNodes:[
				{tagName:'dt',className:reportType,innerHTML:reportType},
				{tagName:'dd',className:reportType,childNodes:[
					{tagName:'ul',childNodes:[
						{tagName:'li',innerHTML:report}
					]}
				]}
			]}
		]);
	};
	$(document).ready(function(){
		$("li#manageCommunity").trigger( "click" );
		$("ul#userManagementMenu li").unbind("click").click(function(){
			doTask = $( $(this).find("a")[0] ).attr("href");
			$.getJSON(doTask, {'option':'com_tuiyo','context': 'communityManagement','format': 'json'}, 
				function(ext){
					if(!ext.extra ){
						$("#cmtyAdminPage").empty();
					 	showMessage("An unknown error occured", "error");
					}else{
						$("div.reporter").empty();
						$("#cmtyAdminPage").empty();
						$("#cmtyAdminPage").html( ext.extra );
						
						$(".profileView").click(function(){
							var parent = $(this).parent(); 
							$.getJSON('index.php?userid='+parent.attr("id"),
								{'option':'com_tuiyo','context':'communityManagement','format':'json','do':'getProfile'},
								function( profile ){
									$(".profileBox").remove();
									$(".selectedRow").removeClass("selectedRow");
									parent.addClass("selectedRow");
									$($('<div class="profileBox">').appendDom([
										{tagName:'div',innerHTML: profile.extra }
									])).insertAfter( parent );
								},
							'json');
						});
					}
				},
			'json');
			
		});	
		$("ul#userManagementMenu li:first").trigger( "click" );
	});
})(jQuery);