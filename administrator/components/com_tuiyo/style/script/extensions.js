(function($){
	var systemExtensions = function(){
		var
			showMessage 	= function(report, reportType){
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
		return {
			init : function(){
				//alert( $(this) );
				$('ul.publisherTabItems li').click(function(){
					doTask = $( $(this).find("a")[0] ).attr("href");
					$.getJSON(doTask, {'option':'com_tuiyo','context': 'extensions','format': 'json'}, 
					function(ext){
						if(!ext.extra ){
							$("#adminPageTabContent").empty();
						 	showMessage("An unknown error occured", "error");
						}else{
							$("div.reporter").empty();
							$("#adminPageTabContent").empty();
							$("#adminPageTabContent").html( ext.extra );
						}
					},'json');
				});
			},
		}
	}();
	$.fn.extend({
		systemExtensions : systemExtensions.init,
	});
	$(document).ready(function(){
		$("div#systemCofig").systemExtensions();
	})
})(jQuery);


(function($){
	$(document).ready(function(){
		$("li#manageExtensions").trigger( "click" );
		//Trigger Events;
		if(window.location.hash !== '' ){
			//alert( $("a[rel='"+(window.location.hash).substring(1)+"']").parent()  );
			$( $("a[rel='a"+(window.location.hash).substring(1)+"']").parent() ).trigger( "click" );
		}
		
	});
})(jQuery)
