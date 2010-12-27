/**
 * @author Livingstone
 */
(function($) {
	$(document).ready(function(){
		$("ul#tabs1publisher li:not(ul#tabs1publisher li:first)").each(function(i) {
            $(this).unbind('click').click(function(e) {
                e.preventDefault();
                $("ul#tabs1publisher li.current").removeClass('current');
                $(this).addClass('current');
                var pageEl = $(this).attr('type') ;
                $("div.pageEl").hide();
                $("div#"+pageEl).show();
            });
		});
		$('ul#tabs1publisher li:first').bind("mouseover", function(){
			$(this).find('.articleCatFilter').show();
			$('.articleCatFilter').unbind("mouseout").bind("mouseout",function(){
				$(this).hide();
			})
		});
		$(".wysiwyg").wysiwyg();
		$("#userActivityStream").TuiyoStream();
		$("#userActivityStream").TuiyoStreamLoad();	
	})
})(jQuery);