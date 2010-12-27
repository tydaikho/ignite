/**
 * @author Livingstone
 */
(function($) {
    $(document).ready(function(){
        //AJAX Events
        TuiyoHomepage.prepareBody();
		$("#userActivityStream").TuiyoStream();
		$("#userActivityStream").TuiyoStreamLoad();		
		$(document).data('dashboard' , $("#homepageContent").html() );
        
    });
    function doFunction(E
    /*, hash */
    ) {
        if (typeof hash == 'undefined') {
            hashF = (E.find("a")[0].hash).substring(1);
            hashFF = hashF.split('-');
            hashFFF = eval(hashFF[0]);
            if ($.isFunction(hashFFF)) {
				hashFFF();
            }
        }
    };
    var TuiyoHomepage = new Object({	
        content: new Array(),
        paths: new Array(),
		changeAvatar: function(){
			$.TuiyoUploader('avatar', false, {});
		},		
		prepareBody: function(){
			$("ul#publisherHp1 li").each(function(i) {
	            $(this).unbind('click').click(function(e) {
	                e.preventDefault();
	                $("ul#publisherHp1 li.current").removeClass('current');
	                $(this).addClass('current');
	                doFunction($(this));
	            });
	        });
			$("ul#publisherHp2 li").each(function(i) {
	            $(this).unbind('click').click(function(e) {
	                e.preventDefault();
	                $("ul#publisherHp2 li.current").removeClass('current');
	                $(this).addClass('current');
	                var pageEl = $(this).attr('id') ;
	                $("div.pageElLeft").hide();
	                $("div."+pageEl).show();
	                
	            });
	        });
		},
		showReport : function(report, reportType){
			if(typeof reportType ==='undefined') reportType = 'message';
			$("div.reporter").remove();
			$("div.homepageMainPage").prepend(
				$('<div class="reporter">').appendDom([
					{tagName:'dl',id:'system-message',childNodes:[
						{tagName:'dt',className:reportType,innerHTML:reportType},
						{tagName:'dd',className:reportType,childNodes:[
							{tagName:'ul',childNodes:[
								{tagName:'li',innerHTML:report}
							]}
						]}
					]}
				]).bind("click", function(){
					$(this).remove();
				}).css("cursor", "pointer") 
			);
		},
        _show: function(content) {
            $("#homepageContent .homepageMainPage").html(content);
        },
        _initAccordion: function() {
            $("div.dTABody:not(:first)").hide();
            $("div.aTAHead").unbind('click').click(function(e) {
                e.preventDefault();
                $(this).next("div.dTABody").slideToggle(10).siblings("div.dTABody").slideUp();
            });
            $("div.cfgElements:not(:first)").hide();
            $("ul.publisherTabItems li").each(function(i) {
                $(this).click(function(e) {
                    e.preventDefault();
                    $("ul.publisherTabItems li.current").removeClass('current');
                    $(this).addClass('current');
                    ($("div." + $(this).attr('id'))).show().siblings("div.cfgElements").hide()
                });
            });
			//IE is stupid I tell You
			if($.browser.msie){
				$("ul.publisherTabItems li:first").trigger('click');
			}
        }
    });
})(jQuery);