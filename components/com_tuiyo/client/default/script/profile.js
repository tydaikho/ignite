(function($) {
	$(document).ready(function(){
		$("ul.publisherTabItems li").each(function(i) {
            $(this).unbind('click').click(function(e) {
                e.preventDefault();
                $("ul.publisherTabItems li.current").removeClass('current');
                $(this).addClass('current');
                var updateType = $(this).attr('type') ;
                $("#TuiyoStreamUpdate").find("input[name=type]").val( updateType );
                $("#userActivityStream").html('<img src="components/com_tuiyo/client/default/images/loading2.gif" style="margin-top: 8px" />' );
                $("#userActivityStream").TuiyoStreamLoad({'filter': updateType });
                $("#streamPagination").remove();
            });
        });
		$(".wysiwyg").wysiwyg();
		$("#sendPMS").bind("click", function(e){
			e.preventDefault();
			return Messages.sendNew();
		});
		$("form#profileRatingEl").children().not(":radio").hide();
		$("form#profileRatingEl").stars({
			oneVoteOnly: true,
			callback: function(ui, type, value){
				$.post($("form#profileRatingEl").attr("action"), $("form#profileRatingEl").serialize(), 
				function(json){
				    alert('Thanks for your vote');
				}, 'json');
			}
		});
		$("#userActivityStream").TuiyoStream();
		$("#userActivityStream").TuiyoStreamLoad();
		$(".slideshowLink").bind("click",function(){
			$.facebox({div:"#slideShowProfileBox"})
		});
		$("<div />").bind('click', function(){
			var albumid = $(this).attr('aid');
			$.getJSON('index.php',{
						"option":"com_tuiyo", "view":"photos","do":"slideShow", "format":"json", "aid":(!albumid || typeof albumid == 'undefined')? '' : albumid
					}, function(inResponse){					
						$.facebox( inResponse.html );	
							var photos	 	= inResponse.photos,
								slideLength = photos.length,
								currPos		= -1,
								slideshowUL = $('<ul id="slideshowWrapUL"/>'),						
								slideTmpl = function( photo ){
									return  [{tagName: 'li',className :'slideshowLi', title : photo.caption,style: "width: 600px; height: auto; min-height: 400px", childNodes: [
											{tagName: 'img',className:'slideImage',src: photo.src_original,style:'max-width: 600px'}
										]}
									]}
							;
							$( slideshowUL ).appendTo( $("#currImg") ) ;
							$.each(inResponse.photos, function(i, photo){
								var slideLi = slideTmpl( photo );								
									$("#slideshowWrapUL" ).appendDom( slideLi )	;																	
								var slideLiImg = $("#slideshowWrapUL").find("li:last").find("img"),								
									slideLiImgWidth = $(slideLiImg).width(),
									slideLiImgHeight = $(slideLiImg).height() ;								
								if(slideLiImgHeight >  400 ){																		
									var percentile = (slideLiImgHeight - 400) / slideLiImgHeight ,
										newHeight = slideLiImgHeight - ( slideLiImgHeight  * percentile ),
										newWidth = slideLiImgWidth - ( slideLiImgWidth  * percentile );									
									$(slideLiImg).attr("width" , newWidth );
									$(slideLiImg).attr("height" , newHeight );
									$(slideLiImg).parent("li").css("height" , newHeight+"px" );	
								}
							});
							$.getScript($.TuiyoDefines.get("interfaceIncPath") + '/easing/easing.js',function() {
								$.getScript($.TuiyoDefines.get("interfaceIncPath") + '/carousel/carousel.js',function() {
									$("#currImg").jCarouselLite({
										btnNext: "a[rel=next]",
										btnPrev: "a[rel=previous]",
										visible:1,
										auto: false,			
										easing: 'easeInOutExpo',
										speed:1000,
										beforeStart : null
									});								
								});
							});										
					}, 'json');
			});		
	});
	var Messages = function(){
		return {
			sendNew: function(){
				var msgBox = $("div#profilePMS").clone();
				$.facebox( $(msgBox).show() );
				$("#facebox .ftitle").html( "Sending a Private Message" );
			}
		};
	}();
	function doProfileFunction(E /*, hash */){
		if (typeof hash == 'undefined') {
			hashF 	= ( E.find("a")[0].hash ).substring(1);
			hashFF 	= hashF.split('-');
			hashFFF = eval(hashFF[0]);
			
			if ($.isFunction(hashFFF)) {
				eval(hashFF[0])($.grep(hashFF, function(a){
					return a = hashFF[0];
				}));
			}
		}
	};	
})(jQuery);
