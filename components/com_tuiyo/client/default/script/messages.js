/**
 * @author Livingstone
 */
(function($) {
	$(document).ready(function(){
		$("ul#messagesMenu li:not(li.link)").each(function(k){
			$(this).click(function(f){
				f.preventDefault();
				$("ul#messagesMenu li.current").removeClass('current');
				$(this).addClass('current');
				doMessageFunction($(this));
			});
		});
		$(".wysiwyg").wysiwyg();
		$('a[rel=submit]').bind('click',  function(){
			if($("#messageSubject, #newMessageText").val() === "") 
				return alert('Recipients, Subject or Body Cannot be empty');
			$("#TuiyoMessageSend").submit();
		});
		$('a[rel=reply]').bind('click',  function(){			
			$("ul#messagesMenu li:first").trigger('click');
			$("input#sendToUsers").val( $(this).attr('uname') );
			$("input#messageSubject").val( 'RE: '+$(this).attr('subject') );
			
			
		});
		$( $("input.searchInputText").attr("value", "Search") ).css("color", "#ebebeb");
		$("#tuiyoSuggestBoxInc").unbind('click').click(function(){
			selectBox = $("#sendToSuggestBox").clone();
			$.facebox( $(selectBox).css('display' , 'block').addClass("currentSuggestBox") );
			$("#facebox .ftitle").html( "Send Message to a friend..?" );
			$(selectBox).find("input[name=suggestSalt]").bind("keyup", function(e){
				e.preventDefault();
				var form= $(selectBox).find("form:first"),
					rslt= $("div.currentSuggestBox div#suggestResultBox")
				;
				$.post($(form).attr("action"),$(form).serialize(), function(response){
					var data = response.data ;
					$(rslt).empty();
					$.each(data, function(i, suggested){
						$('<div class="listItem"></div>').appendDom([
							{tagName:'div', className:'tuiyoTableCell', style:'width: 5%', childNodes:[
								{tagName:'input', type:'checkbox',style:'margin: 4px 10px 4px 4px ', name:'sharing[]', value: ((suggested.rType=='friend')?"p":"g")+suggested.rID, click: function(){
									if (this.checked) {
										$('div[rel='+$(this).val()+']').remove();
										$('<div class="sendTo" rel="'+ $(this).val()+'" title="'+ suggested.rName+'"></div>').appendDom([{
											tagName: 'a',className: 'remove', rel: 'removeParticipant', innerHTML: suggested.rName , click:function(e){
												e.preventDefault();
												var self 	= $(this),
												    prompt  = confirm( $.sprintf( $.gt.gettext("Are you sure you want to remove %s from recipients?") , $(self).parent("div.sendTo").attr("title") ) ) ;
												if (prompt) {
													$(self).parent("div.sendTo").remove();
												}
											}
										}, {
											tagName: 'input',type: 'hidden',name: 'sendTo[]', value: suggested.rID
										}]).prependTo( $("#tuiyoSuggestBoxInc") );
									}else{
										$('div[rel='+$(this).val()+']').remove();
									}
								}}
							]},
							{tagName:'div',className:'tuiyoTableCell', style:'width: 90%; padding-left: 5px', innerHTML: suggested.rName },
							{tagName:'div', className:'tuiyoClearFloat' }
						]).appendTo( $(rslt) );
						
					});
					
					
				},'json');
				
			});
		});
		$("div.messagesBoxMessageListItem").bind('click', function(){
			var id = $(this).attr('id');
			
			if($(this).hasClass('unread')){
				$(this).removeClass('unread');
				$.post('index.php?option=com_tuiyo&'+$.TuiyoDefines.get('token')+'=1',
					{'format':'json','view':'messages','do': 'markAs', 'mid': id, 'state': '1' }, 
					function(inResponse){
					}, 'json'
				);
			}
			var msgBody = $(this).next('div').clone();
			
			$(".messageBoxMessageListItemRead").slideUp("fast")
			$("div.navItemTemplate").hide();
			$("div#messageBoxReader").empty().show().html( $(msgBody).css({
				'display': 'block'
			}).show() );
			
			
			
		});
	});
	function doMessageFunction(E /*, hash */){
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
	var Messages = function(){
		var 
		 	settings = {}
		;	
		return {
			compose : function(){
				$("div.navItemTemplate").hide();
				$("div#messagesBoxNewMessage").show();
				$("#newMessageText").focus();
			},
			pms : function(){
				$("div.navItemTemplate").hide();
				$("div#messagesBoxMessageList").show();
			},
			sent : function(){
				$("div.navItemTemplate").hide();
				$("div#messagesBoxMessageSentList").show();
			},
			drafts : function(){
				$("div.navItemTemplate").hide();
				$("div#messagesBoxMessageDraftsList").show();
			},
			trash : function(){
				$("div.navItemTemplate").hide();
				$("div#messagesBoxMessageTrashList").show();
			},		
			archives : function(){
				$("div.navItemTemplate").hide();
				$("div#messagesBoxMessageArchivesList").show();
			}
		}
	}();
})(jQuery);
