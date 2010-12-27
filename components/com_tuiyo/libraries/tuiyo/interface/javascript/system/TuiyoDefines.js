(function($){
	$.TuiyoDefines = function() {};	  
	$.extend($.TuiyoDefines, {
		params: {
			"siteName" 			: "defaultName",
			"siteDomain" 		: "<?php echo JURI::root(); ?>",
			"siteIndex"			: "<?php echo JURI::root().'index.php'; ?>",
			"livePath" 			: "<?php echo $livePath ?>",
			"interfaceIncPath" 	: "<?php echo $livePath ?>/libraries/tuiyo/interface/javascript/includes",
			"streamjs"			: "<?php echo $livePath ?>/libraries/tuiyo/interface/javascript/system/TuiyoStream.js",
			"ajaxImg_16" 		: "<?php echo $ajaxIMG_16 ?>",
			"token"				: $('meta[name="_token"]').attr("content") ,
			"userid"			: "<?php echo JFactory::getUser()->id ?>",
			"profilelink"		: "<?php echo JROUTE::_(TUIYO_INDEX.'&amp;view=profile&amp;do=view');  ?>",
			"statuslink"		: "<?php echo TUIYO_INDEX.'&amp;view=profile&amp;do=viewStatus'; ?>",
			"componentIndex" 	: "<?php echo TUIYO_INDEX ?>",
			"faceboxLoading"	: "<?php echo $livePath ?>/libraries/tuiyo/interface/javascript/includes/facebox/loading.gif",
			"faceboxClose"		: "<?php echo $livePath ?>/libraries/tuiyo/interface/javascript/includes/facebox/closelabel.gif"
		},
		get: function(param){
			return $.TuiyoDefines.params[param];
		}
	});	
	$(document).ready(function(){

		$("ul.helpElementsLinks li").click(function(){
			$("div.helpElements").hide();
			$("div."+$(this).attr("id") ).show();
			$("ul.helpElementsLinks li.current").removeClass("current");
			$(this).addClass("current");
		});
	});
})(jQuery);