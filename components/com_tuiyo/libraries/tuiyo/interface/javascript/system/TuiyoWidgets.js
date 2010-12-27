(function($){
	var TuiyoWidget = function(){
		var 
			layoutData = [], 
			settings = {
				widgetContext 	: ".widgetWallAColumns",
				widgetBox		: ".tuiyoWidget",
				widgetColumns 	: ".widgetColumn",
				widgetHandle 	: ".tuiyoWidgetHead",
				widgetDefColor 	: "red"
			},
			widgetTemplate = function( widget ){
				
				return $("<div class=\"tuiyoWidget\">").appendDom([
					{tagName:'div', className:'tuiyoWidgetHead', childNodes:[
						{tagName:'a', className:'collapse', href:'#', innerHTML:'collapse'},
						{tagName:'strong', style:'font-size:13px', innerHTML: widget.title },
						{tagName:'a', className:'remove', href:'#', innerHTML:'remove'},
						{tagName:'a', className:'edit', href:'#', innerHTML:'edit'},
						{tagName:'div', className:'tuiyoClearFloat'}
					]},
					{tagName:'div', className:'tuiyoWidgetEditbox'},
					{tagName:'div', className:'tuiyoWidgetContent' , innerHTML:'Loading...'}
				]);
			},
			buildWidgetConfigForm = function( wID, widgetParamsXML ){
				
				var paramXML  = $(widgetParamsXML).find("param");
				var paramForm = {tagName:'form', className:'TuiyoForm tuiyoTable', name:wID+'configForm',id:wID+'configForm', childNodes:[]};
				$.each(paramXML, function(i, param){
					var
						elName 		= $(param).attr("name"),
						elType 		= $(param).attr("type"),
						elDefault 	= $(param).attr("defaultValue"),
						elLabel 	= $(param).attr("label"),
						elOptions   = $(param).find("options"),
						el 			= {}
					;
					switch(elType){
						case 'text':
						default: 
							el = {tagName:'input', type:'text', className:'TuiyoFormText', name:elName, value:elDefault }
						break;
					}
					paramForm.childNodes[i] = widgetConfigFormElement( elLabel , el );
				});
				//Add a submit Button
				paramForm.childNodes[ parseInt(paramForm.childNodes.length)] = widgetConfigFormElement('',{
					tagName:'input', className:'TuiyoFormButton', type:'submit', value:'Save Settings'
				});
				//paramForm.name = wID+'configForm';
				//paramForm.id   = wID+'configForm';
				
				return $('<div style="padding: 10px 4px 4px;">').appendDom([ paramForm ]);
			},
			widgetConfigFormElement = function( label , element ){
				return {tagName:'div', className:'tuiyoTableRow', childNodes:[
					{tagName:'div', className:'tuiyoTableCell', style:'width: 33%; padding: 3px', align:'right', innerHTML: label },
					{tagName:'div', className:'tuiyoTableCell', style:'width: 65%', childNodes: [ element ]},
					{tagName:'div', className:'tuiyoClearFloat'}
				]}
			},
			getWidgetSetting = function( widgetID ){},
			makeWidgetSortable = function( settings ){
				//settings = TuiyoWidget.settings;
				$(settings.widgetBox).find(settings.widgetHandle).css({
					cursor: 'move'
				}).mousedown(function (e) {
					$(this).parent().css({
						width: $(this).parent().width() + 'px'
					});
				}).mouseup(function () {
					if(!$(this).parent().hasClass('dragging')) {
						$(this).parent().css({width:''});
					}
				});
			   $(settings.widgetColumns).sortable({	    
			        items: $(settings.widgetBox),
			        connectWith: $(settings.widgetColumns),
			        handle: settings.widgetHandle,
			        placeholder: 'widgetPlaceholder',
			        forcePlaceholderSize: true,
			        revert: 200,
			        delay: 150,
			        opacity: 0.8,
			        containment: 'document',
			        start: function (e,ui) {
			            $(ui.helper).addClass('dragging');
			        },
			        stop: function (e,ui) {
			            $(ui.item).css({width:''}).removeClass('dragging');
			            $(settings.widgetColumns).sortable('enable');
			        }
			    });			
			},
			saveSettings = function(){},
			addWidgetControls = function( settings ){
				$("div.tuiyoWidgetHead a.collapse").click(function(e){
					e.preventDefault();
					$( $( $(this).removeClass("collapse") ).addClass("expand") )
					.parent().parent().find("div.tuiyoWidgetContent").slideToggle('slow');
				});
				$("div.tuiyoWidgetHead a.expand").click(function(e){
					 e.preventDefault();
					 $( $( $(this).removeClass("expand") ).addClass("collapse") )
					 .parent().parent().find("div.tuiyoWidgetContent").slideToggle('slow');
				});
				$("div.tuiyoWidgetHead a.edit").toggle(function (){
				        $(this).css({backgroundPosition: '-66px 0', width: '55px'})
				            .parents(settings.widgetBox)
				                .find('.tuiyoWidgetEditbox').show();
								$(this).parents(settings.widgetBox).trigger( "widgetEdit" );
				        return false;
				    },function () {
				        $(this).css({backgroundPosition: '', width: ''})
				            .parents(settings.widgetBox)
				                .find('.tuiyoWidgetEditbox').hide();
				        return false;
				});
				$("div.tuiyoWidgetHead a.remove").click(function(e){
					 e.preventDefault();
					 if(confirm('Are u sure you wish to remove this widget?')) {
			            $(this).parents(settings.widgetBox).animate({
			                opacity: 0    
			            },function () {
			                $(this).wrap('<div/>').parent().slideUp(function () {
			                    $(this).remove();
			                });
			            });
			        }
				});			
			},
			widgetStartEvent = function( widget ){
				if (typeof $.data(document, widget.id) !== 'undefined') {
					return widgetRegisterEvents(widget, $.data(document, widget.id) );
				}
				else {
					//alert(widget.url);
					$.ajax({
						type: "GET",
						url: widget.url,
						success: function(xml){
							//alert( $(xml).find("onload").contents() );
							var wData = {
								wEvents: {
									widgetLoad: $(xml).find("onload").text(),
									widgetEdit: $(xml).find("onedit").text(),
									widgetClose: $(xml).find("onclose").text(),
									widgetRefresh: $(xml).find("onrefresh").text()
								},
								widgetBody: (typeof $(xml).find("widgetbody").text() !== null) ? $(xml).find("widgetbody").text() : 'l',
								widgetParams: $(xml).find("widgetparams"),
								widgetXML: xml
							};
							$.data(document, widget.id, wData );
							return widgetRegisterEvents( widget, wData );
						},
						error: function(query){
							alert('an error occured');
						}
					});
				}			
			},
			widgetOnEdit = function(wID, wData){
				
				var editBox  = $("div#"+wID).find('div.tuiyoWidgetEditbox:eq(0)')
				var editForm = buildWidgetConfigForm( wID, wData.widgetParams );
				
				$(editBox).empty();
				$(editBox).html( editForm );
				
				$("#"+wID+'configForm').unbind("submit").bind("submit",function(e){
					e.preventDefault();
					alert($(this).serialize());
					alert('form submitted');
					
					$("#"+wID).find("a.edit").trigger("click");
					//call widgetOnAfterEdit to save the form data;
				});
			},
			widgetOnAfterEdit = function(wID, wData){},			
			widgetOnRefresh = function(wID, wData){},
			widgetOnClose = function(wID, wData){},
			widgetOnLoad = function(wID, wData){},			
			widgetRegisterEvents = function(widget, wData){	
				$("div#"+widget.id).find('.tuiyoWidgetContent').html( wData.widgetBody );
				$.each( wData.wEvents , function(key, val){
					$("div#"+widget.id).bind(key, function(){
						//Prepare for the event
						switch(key){
							case 'widgetLoad': widgetOnLoad(widget.id, wData); break;
							case 'widgetEdit': widgetOnEdit(widget.id, wData); break;
							case 'widgetRefresh': widgetOnRefresh(widget.id, wData); break;
							case 'widgetClose': widgetOnClose(widget.id, wData); break;
						}
						//Execute the Event
						eval( val );						
					});
				});
				$("div#"+widget.id).trigger( "widgetLoad" );
			}
			addWidgetJqueryJs = function(){};
		return {
			initialize : function( options ){
				$.extend(settings, options);
				$(this).TuiyoWidgetPrepareLayout();
			},
			addContent : function( ){
				var loaded = false;
				$(this).click(function(e){ 
					e.preventDefault(); 
					if(layoutData.length < 1 ){
						alert($.gt.gettext('You need to first add a tab' ) );
						return $("#WidgetAddTab").trigger( "click" );
					}
					$("div.widgetWallsettingsBox").slideToggle(); 
					if(!loaded){
						$("#widgetSettingsTabsDiv").html( '<img style="margin: 125px auto auto;" src="components/com_tuiyo/client/default/images/loading.gif" />' ).attr("align", "center");
						$.getJSON('index.php', {'option':'com_tuiyo','view':'widgets','do':'addContentPanel','format':'json'}, 
							function( inResponse ){
								$("#widgetSettingsTabsDiv").html( inResponse.html ).attr("align", "");
								$("#closeContentPanel").unbind("click").bind("click", function(e){
									e.preventDefault(); $("div.widgetWallsettingsBox").slideUp( "slow" );
								});
								$("a.addWidget").unbind("click").bind("click", function(e){
									
									var selfA		= $(this);
									var widgetXML 	= $(selfA).attr("rel" );
									var widgetTitle = $(selfA).attr("title" ); 
									var widgetObj 	= {'id':"w12314", 'title':widgetTitle, 'color': settings.widgetDefColor , 'url': widgetXML , params:{} } ;
									
									//Add this widget to the first column
									layoutData[0].data[0].widgetData[layoutData[0].data[0].widgetData.length] = widgetObj ;	
									
									$( widgetTemplate( widgetObj ) )
									.appendTo( $("div.widgetColumn:first") )
									.addClass( widgetObj.color+"Widget" )
									.attr("id",  widgetObj.id )
									.bind("widgetStart", function(){
										widgetStartEvent(  widgetObj )
									}).trigger("widgetStart");
									TuiyoWidget.widgetTize();
									
									$("div.widgetWallsettingsBox").slideUp( "slow" );
								})								
								loaded = true;
							} 
						, 'json');
					}
					
				});
			},
			prepareLayout : function(){
				$.each(layoutData, function(t, tab){
					$("<li><span style=\"float:left\">"+tab.title+"</span></li>")
					.insertBefore("#widgetPageTabBar li.newTab").addClass( tab.id ).bind("click", function(e){
						e.preventDefault();
		                $("#widgetPageTabBar li.current").removeClass('current');		                
						$(this).addClass('current');
						$("#widgetWallAColumns").empty();
						$.each(tab.data, function(c, column){
							if(typeof column == 'undefined') return
							$('<div id="'+column.id+'"></div>').addClass("tuiyoTableCell widgetColumn")
							.appendTo( $("#widgetWallAColumns") ).css("width" , column.size );
							$.each(column.widgetData, function(w, widget){
								if(typeof widget == 'undefined') return
								$( widgetTemplate( widget ) ).appendTo( $("div#"+column.id) ).addClass( widget.color+"Widget" ).attr("id", widget.id )
								.bind("widgetStart", function(){
									widgetStartEvent(widget)
								});
							})
						});
						$('<div class="tuiyoClearFloat" >').appendTo($("#widgetWallAColumns"));
						$(".tuiyoWidget").trigger("widgetStart");
					
						TuiyoWidget.widgetTize();
					}).appendDom([
						{tagName:'a',className:'closeTab', innerHTML:'close Tab', click:function(){
							var selfA = $(this);
							if (confirm('Are u sure you wish to delete this Tab?')) {									
								var nextTab = $(selfA).parent().next("li");
									$(selfA).parent().remove();
									$("#widgetWallAColumns").empty();
									$(nextTab).trigger("click");
								$.post('index.php?option=com_tuiyo&controller=widgets&format=json&'+$.TuiyoDefines.get("token")+'=1', 
									{"tabID": parseInt( (tab.id).substring(1) ) , "do":"removeTabFromPage" }, 
									function(delResponse){
									}, 
								'json');
							}
						}},{tagName:'span',clear:'both'}
					]);	
				});
				
				$("#widgetPageTabBar li:eq(1)").trigger("click");
			},
			getLayoutData : function(){ return layoutData; },
			setLayoutData : function( data ){ layoutData = data },
			getSettings : function(){return settings; },
			widgetTize: function(){
				makeWidgetSortable( settings );
				addWidgetControls( settings );
				addWidgetJqueryJs( settings );
			}
		};
	}();
	$.fn.extend({
		TuiyoWidget : TuiyoWidget.initialize,
		TuiyoWidgetAddContent : TuiyoWidget.addContent,
		TuiyoWidgetPrepareLayout : TuiyoWidget.prepareLayout 
	});
	$(document).ready(function(e){
			
		$.post('index.php?option=com_tuiyo&controller=widgets&format=json&'+$.TuiyoDefines.get("token")+'=1', {"do":"getWidgetPageLayout" }, 
			function(inResponse){
				//alert(inResponse);
				
				TuiyoWidget.setLayoutData( inResponse.data );
				
				$().TuiyoWidget();
				
				$("#WidgetAddContent").TuiyoWidgetAddContent();
				$("#WidgetToggleAll").click(function(e){ 
					e.preventDefault(); 
					//$("a.expand").removeClass("expand").addClass("collapse"); 
					$("a.collapse").trigger("click"); 
					$("a.expand").trigger("click");  
				});
				$("#WidgetAddTab").unbind("click").bind("click", function(){
					var formDiv = $("#newTabFormDiv").clone();
					$(formDiv).find('form:eq(0)').unbind("submit").bind("submit", function(e){
						e.preventDefault();
						var self = $(this);
						if($(self).find("input#tTitle:eq(0)").val() === "" ){
							alert('Please provide a tab title');
							return;
						}
						$("#facebox .ftitle").appendDom([
							{tagName:'img', src:'components/com_tuiyo/client/default/images/loading.gif', style:'margin-left: 5px; float: right'}
						]);
						$.post( $(self).attr("action"), $(self).serialize(),function(inResponse){
							$.facebox.close();
							$("<li><span style=\"float:left\">"+ $(self).find("input#tTitle:eq(0)").val()+"</span></li>")
							.insertBefore("#widgetPageTabBar li.newTab").addClass( "t"+inResponse.data.tabID ).bind("click", function(e){  //inResponse.data.ID 
								e.preventDefault();
				                $("#widgetPageTabBar li.current").removeClass('current');		                
								$(this).addClass('current');
								$("#widgetWallAColumns").empty();
								
								//Get the existing layout Data
								var tlData = TuiyoWidget.getLayoutData();
								
								//Add this tab data
								tlData[tlData.length] = inResponse.data[0];
								
								//Set the layout Data
								TuiyoWidget.setLayoutData( tlData );
								
								$.each(inResponse.data.cols, function(c, column){
									$('<div id="c'+column.ID+'"></div>').addClass("tuiyoTableCell widgetColumn") 
									.appendTo( $("#widgetWallAColumns") ).css("width" , column.size+"%" );
								});
								
								//Save Tab Data
								
								
								$('<div class="tuiyoClearFloat" >').appendTo($("#widgetWallAColumns"));
								$(".tuiyoWidget").trigger("widgetStart");
								TuiyoWidget.widgetTize();
							}).appendDom([
								{tagName:'a',className:'closeTab', innerHTML:'close Tab', click:function(){
									var selfA = $(this);
									if (confirm('Are u sure you wish to delete this Tab?')) {
										var nextTab = $(selfA).parent().next("li");
											$(selfA).parent().remove();
											$("#widgetWallAColumns").empty();
											$(nextTab).trigger("click");
										$.post('index.php?option=com_tuiyo&controller=widgets&format=json&'+$.TuiyoDefines.get("token")+'=1', 
											{"tabID": inResponse.data.ID , "do":"removeTabFromPage" }, 
											function(delResponse){
											}, 
										'json');
									}
								}}
							]).trigger("click"); //go to this tab immediately
						},'json');
						
					})
					$.facebox( $(formDiv).show() );
					$("#facebox .ftitle").html( "Add a new Tab to your page" );
					
				});
			}, 
		'json');
	})
})(jQuery);



/* jFeed : jQuery feed parser plugin
 * Copyright (C) 2007 Jean-François Hovinne - http://www.hovinne.com/
 * Dual licensed under the MIT (MIT-license.txt)
 * and GPL (GPL-license.txt) licenses.
 */

(function($){	
	$.getFeed = function(options) {
	    options = $.extend({
	        url: null,
	        success: null
	    }, options);
	    if(options.url) {
	        $.ajax({
	            type: 'GET',
	            url: 'index.php?&url='+options.url,
	            data: {'option':'com_tuiyo','controller':'resources','do':'getFeedXML', 'format':'xml'},
	            dataType: 'xml',
	            success: function(xml) {
	                var feed = new JFeed(xml);
	                if($.isFunction(options.success)) options.success(feed);
	            }
	        });
	    }
	};
	function JFeed(xml) {
	    if(xml) this.parse(xml);
	};
	JFeed.prototype = {
	    type: '',
	    version: '',
	    title: '',
	    link: '',
	    description: '',
	    parse: function(xml) {    
	        if($('channel', xml).length == 1) {    
	            this.type = 'rss';
	            var feedClass = new JRss(xml);
	        } else if($('feed', xml).length == 1) {       
	            this.type = 'atom';
	            var feedClass = new JAtom(xml);
	        }
	        if(feedClass) $.extend(this, feedClass);
	    }
	};
	
	function JFeedItem() {};
	JFeedItem.prototype = {
	    title: '',
	    link: '',
	    description: '',
	    updated: '',
	    id: '',
		mcontent:'',
		mdescr:'',
		mcredit: '',
		dcreator:'',
		mthumbnail:'',
		store:''
	};
	
	function JAtom(xml) {
	    this._parse(xml);
	};
	JAtom.prototype = {  
	    _parse: function(xml) {    
	        var channel = $('feed', xml).eq(0);
	        this.version = '1.0';
	        this.title = $(channel).find('title:first').text();
	        this.link = $(channel).find('link:first').attr('href');
	        this.description = $(channel).find('subtitle:first').text();
	        this.language = $(channel).attr('xml:lang');
	        this.updated = $(channel).find('updated:first').text();
	        this.items = new Array();
	        var feed = this;
	        
	        $('entry', xml).each( function() {
	        
	            var item = new JFeedItem();
	            
	            item.title = $(this).find('title').eq(0).text();
	            item.link = $(this).find('link').eq(0).attr('href');
	            item.description = $(this).find('content').eq(0).text();
	            item.updated = $(this).find('updated').eq(0).text();
				item.store = $(this);
	            item.id = $(this).find('id').eq(0).text();
	            
	            feed.items.push(item);
	        });
	    }
	};
	
	function JRss(xml) {
	    this._parse(xml);
	};
	JRss.prototype  = {
	    _parse: function(xml) {
	    
	        if($('rss', xml).length == 0) this.version = '1.0';
	        else this.version = $('rss', xml).eq(0).attr('version');
	
	        var channel = $('channel', xml).eq(0);
	    
	        this.title = $(channel).find('title:first').text();
	        this.link = $(channel).find('link:first').text();
	        this.description = $(channel).find('description:first').text();
	        this.language = $(channel).find('language:first').text();
	        this.updated = $(channel).find('lastBuildDate:first').text();
	        this.items = new Array();
	        var feed = this;
	        
	        $('item', xml).each( function() {
	        
	            var item = new JFeedItem();
	            
	            item.title = $(this).find('title').eq(0).text();
	            item.link = $(this).find('link').eq(0).text();
	            item.description = $(this).find('description').eq(0).text();
	            item.updated = $(this).find('pubDate').eq(0).text();
				item.dcreator = $(this).find("dc\\:creator").eq(0).text();
				item.mcontent = $(this).find("media\\:content").eq(0);
				item.mdescr = $(this).find("media\\:description").eq(0).text();
				item.mcredit = $(this).find("media\\:creator").eq(0).text();
				item.mthumbnail = $(this).find("media\\:thumbnail").eq(0);
	            item.id = $(this).find('guid').eq(0).text();
				item.store = $(this);
	            
	            feed.items.push(item);
	        });
	    }
	};
})(jQuery);