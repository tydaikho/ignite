(function($){
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
		$("#userActivityStream").TuiyoStream();
		$("#userActivityStream").TuiyoStreamLoad();	
	});
})(jQuery);