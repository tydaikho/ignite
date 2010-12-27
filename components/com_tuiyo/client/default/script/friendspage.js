/**
 * @author Livingstone
 */
(function($) {
	$(document).ready(function(){
		//Navigation Tabs
		$("ul#friendsMenu li:not(li.nomenu, li.link, li.noMenu)").each(function(k){
			$(this).click(function(f){
				f.preventDefault();
				$("ul#friendsMenu li.current").removeClass('current');
				$(this).addClass('current');
				$("div.friendsPageItem").hide();
				$("div#"+$(this).attr("rel") ).show();
			});
		});
		if(window.location.hash !== '' ){
			$("li#"+(window.location.hash).substring(1) ).trigger( "click" );
		}
		$( $("input.searchInputText").attr("value", "Search") ).css("color", "#ebebeb");
		$("#toSelectList, #toSelectedList").unbind('click').click(function(){
			selectBox = $("#widgetSettings").clone();
			$.facebox( $(selectBox).css('display' , 'block') );
			$("#facebox .ftitle").html( "Send Message to..." );
			$("input.searchInputText" ).unbind('focus').focus(function(){
				$( $( $(this).attr("value", "") ).css("color", "#777") ).unbind("blur").blur(function(){
					if( $(this).attr("value") === ""){
						$( $(this).attr("value", "Search") ).css("color", "#ebebeb");
					}
				})
			});
		});
	});
	/*
	 * Parses a passed function
	 * @param {Object} E
	 * @param {Object} hash
	 */
	function doFriendsFunction(E /*, hash */){
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
