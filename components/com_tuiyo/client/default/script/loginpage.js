/**
 * @author Livingstone
 */
(function($){
	$(document).ready(function(){
		$("ul#pageNavigation li:not(li.nomenu, li.link)").each(function(k){
			$(this).click(function(f){
				f.preventDefault();
				$("ul#pageNavigation li.current").removeClass('current');
				$(this).addClass('current');
				$("div.pageItem").hide();
				$("div#"+$(this).attr("rel") ).show();
			});
		});
		if(window.location.hash !== '' ){
			$("li#"+(window.location.hash).substring(1) ).trigger( "click" );
		}
	})
})(jQuery);
