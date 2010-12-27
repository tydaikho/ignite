/**
 * @author Livingstone
 */
(function($){
	$(document).ready(function(){
		$("ul.publisherTabItems li").each(function(i){
			$(this).click(function(e){
				e.preventDefault();
				$("ul.publisherTabItems li.current").removeClass('current');
				$(this).addClass('current');
			   ($("div."+$(this).attr('id'))).show().siblings("div.cfgElements").hide();
			});
		});
		$("#createBasicProfile").submit(function(e){
            //validation
			if($("#profileName").val() !== ""){
				$("#profileName").val() = $.trim( $("#profileName").val() );
			}else{
				$("#profileName").addClass('invalidf');
				$("#profileName").change(function(e){
					$(this).removeClass("invalidf");
				});
				return false;
			}
		});
		$("#userAvatarUpload").TuiyoAvatar();
		$("#tuiyoTermsRead").click(function(r){
			($(this).is(":checked")) ? $("#submitSetup").attr("disabled", false ): $("#submitSetup").attr("disabled", true );
		});
	});
})(jQuery);

(function($){
	var TuiyoAvatar = function(){
		return {
			init : function(){
				var AsyncPath = $.TuiyoDefines.get("interfaceIncPath") + "/asyncupload/";
				$.getScript( AsyncPath + 'swfupload.js', function(){
					$.getJSON('index.php?'+$.TuiyoDefines.get("token")+"=1",{
		                'option': 'com_tuiyo', 'do': 'getSessionId', 'controller': 'resources', 'format': 'json'
	            		},
	            		function(resource){
							$.getScript( AsyncPath + 'asyncupload.js', function(){
								$("#userAvatarUpload").makeAsyncUploader({
						            upload_url: $.TuiyoDefines.get("componentIndex")+"&controller=resources&do=uploadResources&format=json&resourceType=avatar",
									post_params: resource.post, 
									//debug: true,
									file_size_limit : "5 MB",
									file_types : "*.jpg; *.jpeg; *.png; *.gif;*.JPEG;*.PNG;*.GIF",																	
						            flash_url: AsyncPath+'swfupload.swf',
						            button_image_url: AsyncPath+'blankButton.png'
						        });
							})
						},
					"json");
				});
			}
		}
	}();
	$.fn.extend({
		TuiyoAvatar : TuiyoAvatar.init
	})
})(jQuery);
