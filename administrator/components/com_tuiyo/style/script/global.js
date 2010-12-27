(function($){
	var systemConfig = function(){
		var
			buildMenu = function(){
				
			},
			showReport 	= function(report, reportType){
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
				]).bind("click", function(){
					$(this).empty();
				}).css("cursor", "pointer");
			};
		return {
			init : function(){
				//alert( $(this) );
				$('li.configLinkItemApp').click(function(){
					key = $( $(this).find("a")[0] ).attr("href");
					title = $( $(this).find("a")[0] ).attr("title");
					$.getJSON( key, {'option':'com_tuiyo','do': 'getConfigEl','context': 'systemTools','format': 'json'}, 
						function(config){
							if(!config.element ){
							 	showReport("A call to "+key+" failed. check that the config file exists", "error");
							}else{
								$("div.reporter").empty();
								$("#adminPageTabContent").empty();
								$("#adminPageTabContent").appendDom( [config.element] );
							}
					},'json');
				});
			}
		}
	}();
	$.fn.extend({
		systemConfig : systemConfig.init
	});
	$(document).ready(function(){
		$("div#systemCofig").systemConfig();
	})
})(jQuery);

/**
 * @author Livingstone
 */
(function($){
	$(document).ready(function(){
		$("ul#TuiyoAdminMenu li.level1 ul.level2").hide();
		$("li.level1").unbind("click").click(function(e){
			if ($(this).attr("title") !== "") {
				e.preventDefault();
				window.location.replace( $(this).attr("title") );
			}
		})		
		$("ul#TuiyoAdminMenu li.level1 a").click(function(e){			
			$("ul#TuiyoAdminMenu li.level1 ul.level2").hide();
			$("ul#TuiyoAdminMenu li.level1 a").removeClass("active");
			$(this).addClass("active");
			level1 	= $(this).parent(".level1");
			$(level1).children('ul.level2').css('left', $(level1).position().left-12 ).slideDown("fast");
			$("ul.level2 li").unbind("click").click(function(){
				if ($(this).attr("class") !== "") {
					window.location.replace( $(this).attr("class") );
				}
			})
		});
		$("#userActivityStream").TuiyoStream();
		$("#userActivityStream").TuiyoStreamLoad();
		$(document).click(function(f){
			$("ul#TuiyoAdminMenu li.level1 ul.level2").hide();
		});
		$("li.dTABody:not(:first)" ).hide();
		$("li.aTAHead").click(function(e){
			e.preventDefault();
			$("li.aTAHead").removeClass("current");
			$(this).addClass("current");
			$(this).next("li.dTABody").slideDown("fast").siblings("li.dTABody").hide();
		});
		$("ul.publisherTabItems li").click(function(a){
			a.preventDefault();
            $("ul.publisherTabItems li").removeClass('current');
            $(this).addClass('current');
            $("div.pageEl").hide();
            $("div."+$(this).attr("id") ).show();
		});
		$("li.dTABody ul li").unbind('click').bind('click', function(a){
			a.preventDefault();
			if(typeof $(this).attr('rel') !== "undefined"){
				window.location.replace( $(this).attr('rel') );
			}			
		});		
		$("div.configLinkItemApp").click(function(f){
			f.preventDefault();
            $("div.configLinkItemApp").removeClass('currentLink');
            $(this).addClass('currentLink');
		});
        //AJAX Events
        $("#TuiyoAjaxLoading").ajaxStart(function() {
            $(this).show();
        });		
        $("#TuiyoAjaxLoading").unbind('ajaxStop').ajaxStop(function() {
            $(this).hide();
		 	$('a[rel*=facebox]').unbind('facebox').facebox();
			$('input.childSelector').change(function(){
				if(this.checked)
					$( $(this).parent()).parent().addClass( "selectedRow");
				else	
					$( $(this).parent() ).parent().removeClass("selectedRow");
			});
			$("input#masterCheckBox").click(function(){
				var checked_status = this.checked;
				$('input.childSelector').each(function(){
					this.checked = checked_status;
					if(this.checked)
						$( $(this).parent()).parent().addClass( "selectedRow");
					else	
						$( $(this).parent() ).parent().removeClass("selectedRow");
				});
			});	
        });		
		//Trigger Hash Events;
		if(window.location.hash !== '' ){
			//alert( $("a[rel='"+(window.location.hash).substring(1)+"']").parent()  );
			$( $("a[rel='a"+(window.location.hash).substring(1)+"']").parent() ).trigger( "click" );
		}
	});
	$(window).unload( function (){ 
		$("li.dTABody ul li").unbind('click').bind('click', function(a){
			a.preventDefault();
			if(typeof $(this).attr('rel') !== "undefined"){
				window.location.replace( $(this).attr('rel') );
			}			
		});
	});	
})(jQuery);