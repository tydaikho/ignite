(function($){
	$(document).ready(function(){
		$("li#globalConfiguration").trigger( "click" );
		TuiyoCustomFields.initialize();
	    $("ul.publisherTabItems li").each(function(i) {
            $(this).click(function(e) {
                e.preventDefault();
                $("div.childTab").hide();
                $("div."+$(this).attr("id") ).show();
            });
        });
	});	
	var TuiyoCustomFields = function(){
		var 
			settings = {},
			newFieldTableRow = function( field ){
				return $('<div class="tuiyoTableRow fieldListItem" id="00'+field.id+'"></div>').appendDom([
					{'tagName':'div','className':'tuiyoTableCell clickToMove iText', style:'width: 6%', innerHTML: field.id },
					{'tagName':'div','className':'tuiyoTableCell', style:'width: 23%', innerHTML: field.name },
					{'tagName':'div','className':'tuiyoTableCell', style:'width: 25%', innerHTML: field.label },
					{'tagName':'div','className':'tuiyoTableCell', style:'width: 16%', innerHTML: field.type },
					{'tagName':'div','className':'tuiyoTableCell iText '+( (field.indexed > 0)? 'tick':'notick'), style:'width: 8%', innerHTML: ' a' },
					{'tagName':'div','className':'tuiyoTableCell iText '+( (field.required > 0)? 'tick':'notick'), style:'width: 8%', innerHTML: 'a ' },
					{'tagName':'div','className':'tuiyoTableCell iText '+( (field.visible > 0)? 'tick': 'notick'), style:'width: 8%', innerHTML: ' a' },
					{'tagName':'div','className':'tuiyoTableCell clickToRemove iText', style:'width: 6%', innerHTML: 'a', 'click':function(){
						var fieldDiv = $(this);
						$.post('index.php?format=json&option=com_tuiyo', 
							{'context':'systemTools', 'do':'deleteCustomField', 'fid': field.id } , function(){
							fieldDiv.parent().fadeOut("slow");
							showReport("The field has been deleted successfully. Note however that some user data has not been affected" , "notice");
						}, 'json');
						
					}},
					{'tagName':'div','className':'tuiyoClearFloat' }
				]);
			},
			showReport 	= function(report, reportType){
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
					$(this).empty();
				}).css("cursor", "pointer");
			}
		;
		return {
			initialize : function(){
				$.getJSON('index.php?option=com_tuiyo&context=systemTools&do=getCustomFields&format=json', 
					function(inResponse){
						$.each(inResponse.data, function(i, fData){
							$(newFieldTableRow({
								id: fData.id, name:fData.fn, label: fData.fl, type: fData.ft,
								indexed: parseInt(fData.fs), required: parseInt(fData.fr), visible: parseInt(fData.fv)
							})).hide().insertBefore("#newFieldForm").slideDown("fast");
						})
					},
				'json');
				//Get OutPut
				$("a[rel=getOutPut]").click(function(){
					$.post('index.php?option=com_tuiyo&format=json',
						{'context':'systemTools','do':'getSocialForm'} ,
						function(socialform){
							$("div#ouputForm").empty();
							$("div#ouputForm").appendDom( [socialform.data] );
						} , 'json'
					);
				})
				
				//Page Controls
				$("#clickToAdd").toggle(function (){
						$("#newFieldForm").slideDown('fast');
						$("#fl, #fn, #ft").val( "" );
				        $(this).attr('id', 'clickToSave');
						$(this).text('Save');
				    },function () {
						if( $("#fl").val() !== ""){
							$.post('index.php?option=com_tuiyo&context=systemTools&do=saveCustomFields&format=json', 
							{name: $("#fl").val(), label: $("#fl").val(), type: $("#ft").val(), 
							indexed: $("#fs").attr("checked") ? 1 : 0, required:$("#fr").attr("checked") ? 1 : 0, visible: $("#fv").attr("checked") ? 1 : 0}, 
								function(inResponse){
									var data = inResponse.data;
									$(newFieldTableRow({
										id: data.id.toString(), name:data.fn, label: data.fl, type: data.ft,
										indexed: parseInt(data.fs), required: parseInt(data.fr), visible: parseInt(data.fv)
									})).hide().insertBefore("#newFieldForm").slideDown("fast");
									showReport("Field added Successfully, and social form updated", "notice" );
							}, 'json');
						}
						$("#newFieldForm").slideUp('fast');
				        $(this).attr('id', 'clickToAdd');
						$(this).text('Add a new field');
				});
			}
		}
	}();
})(jQuery);
