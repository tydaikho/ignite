/**
 * @author Livingstone
 */
(function($){
	var TuiyoGroups = function(){
		var 
			settings = {},
			newGroupForm = function(){};
		return {
			create: function(){
				$(this).click(function(){
					if (typeof $(document).data('creategroupform') === 'undefined') {
						$.getJSON('index.php', {
							"option": "com_tuiyo", "view": "groups", "do": "newGroup","format": "json"
						}, function(inResponse){
							$(document).data('creategroupform', inResponse.html);
							$.facebox(inResponse.html);
							$("#facebox .ftitle").html($.gt.gettext("Create new group"));
						}, 'json');
					}
					else{
						$.facebox( $(document).data('creategroupform' ) );
						$("#facebox .ftitle").html($.gt.gettext("Create new group"));
					}
				});					
			}
		};
	}();
	$.fn.extend({ 
		TuiyoNewGroups : TuiyoGroups.create 
	});
	$(document).ready(function(){
	
		$("#createNewGroups").TuiyoNewGroups();
		$("#editGroup").bind("click",function(e){
			e.preventDefault();
			$.facebox({div:"#editGroupPage"});
		});
		
		if(typeof $("meta[name=gid]").attr("content") !== 'undefined'){
			//$("#userActivityStream").TuiyoStream();
			//$("#userActivityStream").TuiyoStreamLoad();
			$("input#groupAvatarUpload").TuiyoAvatar( $("meta[name=gid]").attr("content") );
		}
		$("ul.publisherTabItems li:not(:last)").each(function(i) {
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


(function($){
	var TuiyoAvatar = function(){
		return {
			init : function( groupID ){
				var self = $(this) ;
				var form = $(this).parent('form');
				var AsyncPath = $.TuiyoDefines.get("interfaceIncPath") + "/asyncupload/";
				
				//Hide the submit button if we are using ajax
				$("#uploadImage").hide();
				
				$.getScript( AsyncPath + 'swfupload.js', function(){
					$.getJSON('index.php?'+$.TuiyoDefines.get("token")+"=1",
		                {'option': 'com_tuiyo', 'do': 'getSessionId', 'controller': 'resources', 'format': 'json'},
	            		function(resource){
							$.getScript( AsyncPath + 'asyncupload.js', function(){
								$(self).makeAsyncUploader({
						            upload_url: $.TuiyoDefines.get("componentIndex")+"&controller=resources&do=uploadResources&format=json&resourceType=gavatar&groupID="+groupID,
									post_params: resource.post , 
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
