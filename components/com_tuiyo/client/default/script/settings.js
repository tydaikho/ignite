(function($) {
	var showReport = function(report, reportType){
		$.facebox.close();
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
			//$(this).empty();
			//$("div.reporter").empty();
		}).css("cursor", "pointer");
		//Close the facebox
		
	};
	$(document).ready(function(){
		$("ul#settingsTabs li").each(function(k){
			$(this).click(function(f){
				f.preventDefault();
				$("ul#settingsTabs li.current").removeClass('current');
				$(this).addClass('current');
				$("div.cfgElements").hide();
				$("div."+$(this).attr("id") ).show();
			});
		});
		$("ul#servicesTabs li").each(function(k){
			$(this).click(function(f){
				f.preventDefault();
				$("ul#servicesTabs li.current").removeClass('current');
				$(this).addClass('current');
				$("div.cfgPages").hide();
				$("div."+$(this).attr("id") ).show();
			});
		});
		$("#userAvatarUpload").TuiyoAvatar();
		$('form.addServiceForm').bind('submit',function(e){
			e.preventDefault();
			alert('default submit Event');
		});
		$("div.servicePluginPublisher").find("a.addService").bind("click",function(e){
			e.preventDefault();
			var $box   = $(this),
				$boxID = $(this).attr("href"),
				$boxEl = $(this).attr("el"),
				$boxJS = $(this).attr("pluginjs");
			$.facebox( {'div': $boxID } );
			//alert($boxJS);
			$.getScript($boxJS,function(){
				$.globalEval( '$(this).'+$boxEl+'("install")' );
			});
		});
		
		$("div.servicePluginPublisher").find("a.removeService").bind("click",function(e){
			e.preventDefault();
			var $box   = $(this),
				$boxID = $(this).attr("href"),
				$boxEl = $(this).attr("el"),
				$boxJS = $(this).attr("pluginjs"),
				$confirm = confirm('Are you sure you wish to remove this service?');
			//$.facebox( {'div': $boxID } );
			//alert($boxJS);
			if($confirm){
				$.getScript($boxJS,function(){
					$.globalEval( '$(this).'+$boxEl+'("uninstall")' );
				});	
			}
		});
		var $paths = [];
		$paths['jqueryui'] = $.TuiyoDefines.get("interfaceIncPath")+"/jqueryui/";
        $paths['colorpicker'] = $.TuiyoDefines.get("interfaceIncPath") + "/colorpicker/";
		
		//Get DatePicker
		$.getScript($paths['jqueryui']+'ui.core.js',function(){
			$.getScript($paths['jqueryui']+'ui.datepicker.js',function(){
				$("input.TuiyoDatePicker").datepicker({
					changeMonth: true,
					changeYear: true,
					dateFormat: 'yy-mm-dd',
					minDate: '-70Y',
					numberOfMonths: 2,
					showButtonPanel: true

				});
			});
		});				
		//Get ColorPicker
        $.getScript($paths['colorpicker'] + 'js/colorpicker.js',function() {
            $("input.colorPick").ColorPicker({
                'livePreview': true, onSubmit: function(hsb, hex, rgb, el) {
                    $(el).val('#' + hex);
                    $(el).css('backgroundColor', '#' + hex);
					$(el).trigger("setcolor");
                    $(el).ColorPickerHide();
                }, onBeforeShow: function() {
                    $(this).ColorPickerSetColor(this.value);
                }, onShow: function(colpkr) {
                    $(colpkr).fadeIn( "fast" );
                    return false;
                },onHide: function(colpkr) {
                    $(colpkr).fadeOut( "fast" );
                    return false;
                }
            });
            $(document.createElement('link')).attr({
                type: 'text/css',
                href: $paths['colorpicker'] + 'css/colorpicker.css',
                rel: 'stylesheet',
                media: 'screen'
            }).appendTo($("head"));
        });
        
        $("#socialProfile").unbind("click").bind("click",function(f){
        	var $self = $(this);
        	f.preventDefault();
			$.post('index.php?option=com_tuiyo&format=json&'+$.TuiyoDefines.get('token')+'=1',
				{'view':'profile','do':'getSocialForm'},
				function(socialform){
					$("div.socialProfile").empty();
					$("div.socialProfile").appendDom( [ socialform.data ] );
					$("ul#settingsTabs li.current").removeClass('current');
					$($self).addClass('current');
					$("div.cfgElements").hide();
					$("div."+$($self).attr("id") ).show();
				},
				'json'
			);
		});
		$("form#profileStyleForm").submit(function(e){
			e.preventDefault();
			var form = $(this);
			$.post('index.php?option=com_tuiyo&format=json&',
				$(form).serialize(),
				function(inResponse){
					//Show us a message
					showReport(inResponse.msg, (parseInt(inResponse.code)!==200 )?"error":"notice" );
				},
				'json'
			);
		});
		
		$("#userAccountInfo, #contactInfo, #userAccountPrivacySettings").submit( function(e){
			e.preventDefault();
		    $.post($(this).attr('action'), 
				$(this).serialize(),
				function(inResponse){
					showReport("Your Settings have been saved", "notice" );
			}, "json");
			
		});
		
	});
})(jQuery);
/**
 * ProfilePicture Uploader
 */
(function($){
	$.fn.TuiyoAvatar = function(){
		return this.each(function(){
			var
			$token 			= $.TuiyoDefines.get("token") ,
			$tuiyoAyncPath 	= $.TuiyoDefines.get("interfaceIncPath") + "/asyncupload/";
		
			$.getScript( $tuiyoAyncPath+'swfupload.js', function(){
				$.getJSON('index.php?'+$token+"=1",{'option': 'com_tuiyo', 'do': 'getSessionId', 'controller': 'resources', 'format': 'json'},function(resource){
					$.getScript( $tuiyoAyncPath+'asyncupload.js', function(){		
						$("#userAvatarUpload").makeAsyncUploader({
				            upload_url: "http://localhost/joomla/"+$.TuiyoDefines.get("componentIndex")+"&controller=resources&do=uploadResources&format=json&resourceType=avatar&jsid="+resource.sid+"&jsname="+resource.sname,
							post_params: resource.post, 
							debug: false,
							file_size_limit : "15 MB",
							file_types : "*.jpg; *.jpeg; *.png; *.gif;*.JPEG;*.PNG;*.GIF",																	
				            flash_url: $tuiyoAyncPath+'swfupload.swf',
				            button_image_url: $tuiyoAyncPath+'blankButton.png'
				        });
					});
				});
			});
		});
	}
})(jQuery);
		