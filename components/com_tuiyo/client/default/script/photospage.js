/**
 * @author Livingstone
 */
(function($){
	var TuiyoPhotos = function(){
		var
		   photoData = {}, 
		   slideStart = function(){},
		   showReport = function(report, reportType){
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
		return {
			upload : function(){
				$(this).click(function(e){
					e.preventDefault();
					$.TuiyoUploader('photos', true);
				});
			},
			slideshow : function(albumid){
				
				//AjaxDisplay
				$("div.xhrOverlay").ajaxStart(function(){
					$(this).show();
				}).ajaxStop(function(){
					$(this).hide();
				})
				
				$(this).click(function(e, albumid){
		             e.preventDefault();
		             $("ul.publisherTabItems li.current").removeClass('current');
		             $(this).addClass('current');
		             
		             //Rescale Image
		             var thumbsState = 1,
		             	 maxHeight   = 540,
		             	 maxWidth 	 = $('div.slideshowScreenPad').width(),
		             	 newHeight   = 540,
		             	 newWidth    = maxWidth,
		             	 bottomA     = 410,
		             	 bottomB     = bottomA+100,
		             	 images 	 = [],
		             	 pointer 	 = 0,  
		             	 rescaleImage = function(){
		            	 	 var bgImg = $("img#slideshowImageSrc");
			            	 var imgwidth = bgImg.width();
			                 var imgheight = bgImg.height();

			                 if(imgwidth > maxWidth){
			                	 $(bgImg).css("width", maxWidth);
			                 }
		                	 $('div.slideshowScreenPad').height( $(bgImg).height() );
		                	 bottomA = $(bgImg).height() - 130;
		                	 bottomB = bottomA+100;
		                	 $('div.slideShowTools').css({'bottom': '-'+bottomB+'px'});
		                	 
		                	 $('div.thumbsToggleControl').unbind('click').bind('click',function(e){
				            	 e.preventDefault();
				            	 offsetBottom = $('div.slideShowTools');
				            	 if(thumbsState > 0){ 
				            		 $(offsetBottom).css({'bottom' : '-'+bottomB+'px'});
				            		 $(this).removeClass('closeThumbNails');
				            		 thumbsState = 0 
				            	 }else{ 
				            		 $(offsetBottom).css({'bottom' : '-'+bottomA+'px'});
				            		 $(this).addClass('closeThumbNails');
				            		 thumbsState = 1 
				            	 };
				             });
				          };
		             	 
		             //rescaleImage();	
		             
		             $.getJSON('index.php',
		            	 {"option":"com_tuiyo", "view":"photos","do":"slideShow", "format":"json", "aid":(!albumid || typeof albumid == 'undefined')? '' : albumid
		             },function(response){
                    	  images = response.photos;
                    	  $("div.previousControl").unbind("click").bind("click",function(){
                    		  var imgpoint = pointer-1;
                    		  if(imgpoint < 0){ 
                    			  imgpoint = images.length-1 
                    		  }
                    		  //alert(imgpoint);
                    		  $("#slideShowCount").text(imgpoint+1+'/'+images.length).show();
                    		  $("img#slideshowImageSrc").attr("src" , images[imgpoint].src_original).load(function(){ rescaleImage(); });
                    		  $("div.titleControl").find("span").text( images[imgpoint].caption);
                    		  $("a[rel=doDownload]").attr("href",images[imgpoint].src_original).attr("target","_blank");
                    		  pointer = imgpoint
                    	  });
                    	  $("div.nextControl").unbind("click").bind("click",function(){
                    		  var imgpoint = pointer+1;
                    		  if(imgpoint>=images.length){ 
                    			  imgpoint = 0;
                    		  }
                    		  //alert(imgpoint);
                    		  $("#slideShowCount").text(imgpoint+1+'/'+images.length).show();
                    		  $("img#slideshowImageSrc").attr("src" , images[imgpoint].src_original).load(function(){ rescaleImage(); });
                    		  $("div.titleControl").find("span").text( images[imgpoint].caption);
                    		  $("a[rel=doDownload]").attr("href",images[imgpoint].src_original).attr("target","_blank");
                    		  pointer = imgpoint
                    	  });
                    	  
                    	  //set up the carousel;
                    	  var $ulThumbs = $('<ul id="slideshowThumbs" />');
                    		  $.each(images, function(i, image){
                    			  $('<li />').append( $('<img src="'+image.src_thumb+'" />') ).prependTo( $ulThumbs ).bind('click',function(){
                    				  var imgpoint = i;
		                    		  //alert(imgpoint);
		                    		  $("#slideShowCount").text(imgpoint+1+'/'+images.length).show();
		                    		  $("img#slideshowImageSrc").attr("src" , images[imgpoint].src_original).load(function(){ rescaleImage(); });
		                    		  $("div.titleControl").find("span").text( images[imgpoint].caption);
		                    		  $("a[rel=doDownload]").attr("href",images[imgpoint].src_original).attr("target","_blank");
		                    		  pointer = imgpoint
                    			  });
                    		  });
                    	  
                    	  $ulThumbs.prependTo( $("div.slideShowThumbs").empty() );
                    	
							var $thumbsHolder = $("div.slideShowThumbs"),$ulThumbsPadding = 4;
							var $divWidth = $thumbsHolder.width();
							var $lastLi = $ulThumbs.find('li:last-child');
							
							$thumbsHolder.css({overflow: 'hidden'});
							
							$thumbsHolder.mousemove(function(e){
								var ulWidth = $lastLi[0].offsetLeft + $lastLi.outerWidth() + $ulThumbsPadding;
								var left = (e.pageX - $thumbsHolder.offset().left) * (ulWidth-$divWidth) / $divWidth;
							  
								$thumbsHolder.scrollLeft(left);
							});
                    	    
                    	  //load the firstimage
						$("#slideShowCount").text('1/'+images.length).show();
                    	  $("img#slideshowImageSrc").attr("src",images[0].src_original).load(function(){ rescaleImage(); });
                    	  $("div.titleControl").find("span").text( images[0].caption);
                    	  $("a[rel=doDownload]").attr("href",images[0].src_original).attr("target","_blank");
		             });
		             
		             
				});
			},
			refresh: function(){
				$.data(document, "home" , $("div#photosPageContainerBody").html() );
				$(this).click(function(){
					if(typeof $.data(document, "home") !== 'undefined'){
						$("div#photosPageContainerBody").empty();
						$("div#photosPageContainerBody").html( $.data(document, "home") );
						$('a[rel*=facebox]').facebox();
					}else{
						alert('refresh');
					}
				});
			},
			editAlbums : function(){
				$(this).click(function(e){
					e.preventDefault();
					$("div#photosPageContainerBody").empty();
					$.getJSON('index.php',{
						"option":"com_tuiyo", "view":"photos","do":"organizePanel", "format":"json"
					}, function(inResponse){
						$.facebox( inResponse.html );
						var photos = inResponse.photos;
						var albums = inResponse.albums;
						$.each(photos, function(i, photo){
							$("#thumbsList").appendDom([
								{tagName:'li', className:'photoThumb', childNodes:[
									{tagName:'img', src : photo.src_thumb},
									{tagName:'input', type:'hidden', name:'inAlbum[0][]', value:photo.pid }
								]}
							]);
						});
					  	var scroller = $('div.scrollThumbs');
					  	var ul = $('ul#thumbsList');
						var ulPadding = 5;
 				 		var divWidth = scroller.width();
  						var lastLi = ul.find('li:last-child');
						
								
						scroller.css({overflow: 'hidden'});
						scroller.mousemove(function(e){
							var ulWidth = lastLi[0].offsetLeft + lastLi.outerWidth() + ulPadding ;
							var left = (e.pageX - scroller.offset().left) * (ulWidth-divWidth) / divWidth;
							scroller.scrollLeft(left);
						});
						
						$("#selectAlbum").bind("change", function(e){
							var album_id = $(this).val(); $('input[name=aid]').val( album_id );
							
							$("ul.addedPhotos").remove();
							$.getJSON('index.php',{
								"option":"com_tuiyo", "view":"photos","do":"getAlbumPhotos", "format":"json", "aid":album_id
							}, function(inResponse){
								$("#photosAlbumDrop").html( inResponse.html );
								var photos = inResponse.photos;
								var albums = inResponse.albums;
								var dropItemsBox = $('ul', $('#photosAlbumDrop')).length ? $('ul', $('#photosAlbumDrop')) : $('<ul class="addedPhotos ui-helper-reset"/>').prependTo( $("#photosAlbumDrop") );
								$.each(photos, function(i, photo){
									if(photo.aid > 0){
										$(dropItemsBox).appendDom([
											{tagName:'li', className:'photoThumb', childNodes:[
												{tagName:'img', src : photo.src_thumb},
												{tagName:'input', type:'hidden', name:'inAlbum['+photo.aid+'][]', value:photo.pid }
											]}
										]);
										$('#photosAlbumDrop').find("span:first").remove();
										$("#photosAlbumDrop li").draggable({
											cancel: 'a.ui-icon',// clicking an icon won't initiate dragging
											revert: 'invalid', // when not dropped, the item will revert back to its initial position
											containment: 'document', // stick to demo-frame if present
											helper: 'clone',
											cursor: 'move'
										});
									}
								});
							});	
						});
						
						$("ul#thumbsList li").draggable({
							cancel: 'a.ui-icon',// clicking an icon won't initiate dragging
							revert: 'invalid', // when not dropped, the item will revert back to its initial position
							containment: 'document', // stick to demo-frame if present
							helper: 'clone',
							cursor: 'move'
						});
						$("#photosAlbumDrop").droppable({
							accept: '#thumbsList > li',
							activeClass: 'ui-state-highlight',
							drop: function(ev, ui) {
								//deleteImage(ui.draggable);
								var albumid = $("#selectAlbum").val();
								if( albumid < 1 ) {
									showReport($.gt.gettext('Chose an album to modify from the dropdown'), 'error');
									return;
								}
								var dropItemsBox = $('ul', $('#photosAlbumDrop')).length ? $('ul', $('#photosAlbumDrop')) : $('<ul class="addedPhotos ui-helper-reset"/>').prependTo( $("#photosAlbumDrop") );
								$('#photosAlbumDrop').find("span:first").remove();
								$(ui.draggable).appendTo( dropItemsBox );
								$( $(ui.draggable).find("input:first") ).attr("name" , "inAlbum["+albumid+"][]");
								
							}
						});
						$('div.scrollThumbs').droppable({
							accept: '#photosAlbumDrop li',
							activeClass: 'custom-state-active',
							drop: function(ev, ui) {
								$(ui.draggable).appendTo( $('ul#thumbsList') );
								$( $(ui.draggable).find("input:first") ).attr("name" , "inAlbum[0][]");
							}
						});
						
						$("#photoOrganizer").unbind("submit").bind("submit", function(e){
							e.preventDefault();
							var aid = $("#selectAlbum").val();
							if( aid < 1 ) {
								showReport($.gt.gettext('Please select an album from the dropdown'), 'error');
								return;
							}
							$.post($.TuiyoDefines.get("componentIndex")+'&format=json', $("#photoOrganizer").serialize(), 
								function(inResponse){
									showReport($.gt.gettext('The album has been updated'), 'notice');
									//$.facebox(inResponse);
								}, 
							'json');
						});
						
						$("#modifyAlbum").unbind("click").bind("click", function(a){
							var aid = $("#selectAlbum").val();
							if( aid < 1 ) {
								showReport($.gt.gettext('Chose an album to modify from the dropdown'), 'error');
								return;
							}
							$("#createAlbums").trigger("click");
						
							$.each(albums, function(a, album){
								if(parseInt(aid) === parseInt(album.aid) ){
									
									$("#newAlbumFormCreateActive input#name").val( album.name );
									$("#newAlbumFormCreateActive input#location" ).val( album.location );
									$("#newAlbumFormCreateActive textarea#description" ).val( album.description );
									$("#newAlbumFormCreateActive input#published" ).val( album.published );
									$("#newAlbumFormCreateActive select#privacy" ).val( album.privacy );
									$("#newAlbumFormCreateActive input#aid" ).val( album.aid );
									$("#newAlbumFormCreateActive input#ownerid" ).val( album.ownerid );
									$("#newAlbumFormCreateActive button#submitAlbumButton").text( $.gt.gettext('Update album details') );
									return ;
								}
							});
						});
						
						$("#deleteAlbum").unbind("click").bind("click",function(o){
							var aid = $("#selectAlbum").val();
							if( aid < 1 ) {
								showReport( $.gt.gettext('You did not select an album from the dropdown, to delete'), 'error');
								return;
							}
							var proceed = confirm($.gt.gettext("Are you sure you wish to delete this album?") );
							if(proceed){
								$.post($.TuiyoDefines.get("componentIndex")+'&format=json&'+$.TuiyoDefines.get("token")+'=1', 
									{'do':'removeAlbum', 'aid':aid ,'view':'photos','format':'json' }, 
									function(inResponse){
										$("#selectAlbum option[value="+aid+"]").remove();
										showReport( $.sprintf($.gt.gettext("The album %s has been deleted"), inResponse.album ), "notice");
										$("li[aid="+aid+"]").remove();
									}, 
								'json');
							}
						})
						
					}, 'json');
				});
			},
			viewAlbum : function(){
				$(this).click(function(e){
					e.preventDefault();
					$("div#photosPageContainerBody").empty();
					$("div#photosPageContainerBody").html($.gt.gettext('Loading album...'));
					
					var album_id = $(this).attr("aid")
							
					$.getJSON('index.php',{
						"option":"com_tuiyo", "view":"photos","do":"getAlbumPhotos", "format":"json", "aid":album_id }, 
						function(inResponse){
							var photos 		= inResponse.photos;
							var album 		= inResponse.album ;						
							if(album.photocount < 1){
								showReport( $.sprintf( $.gt.gettext("The album %s has no photos, add some now"), album.name), "notice");
								$("#organizePhotos").trigger("click");
								return;
							}							
							var template 	= $('<div class="photosPageBigScreen tuiyoTable"/>').appendDom([
								{tagName:'div', className:'infoGroupTitle', style:'margin-top: 0px', innerHTML:'<h3 style="padding: 0px">'+album.name+'</h3>'},
								{tagName:'div', style:'font-style:italic', innerHTML:album.description},
								{tagName:'div', className:'infoGroupTitle slideshowLink', style:'margin-top: 20px', childNodes:[
									{tagName:'a', href:'#', innerHTML:'<h3 style="padding: 0px">'+$.gt.gettext('Album Photos')+'</h3>' }
								]},
								{tagName:'div', className:'photosBigBlueScreen', childNodes:[
									{tagName:'div',className:'tuiyoClearFloat'}
								]}
							]);
							$.each(photos, function(i, photo){						
								$('<div class="photosPageBigScreenItem" />').appendDom([
									{tagName:'a', className:'imageItemWithDescr', href: photo.src_original, rel:'facebox', childNodes:[
										{tagName:'img', width:'100', src : photo.src_thumb},
									]}
								]).prependTo( $(template).find("div.photosBigBlueScreen"));
							});	
							$("div#photosPageContainerBody").html( $(template) );
							$("div.slideshowLink").TuiyoPhotosSlide( album_id );
							$('a[rel*=facebox]').facebox();					
					});
					
				});
			},
			newAlbum : function(){
				$(this).click(function(e){
					
					e.preventDefault();
					
					var newFormDiv = $("div.newPhotoAblbum").clone();
					var newForm    = $(newFormDiv).find("form");
										
					$.facebox( $(newFormDiv).show() );
					
					$(newForm).attr("id", "newAlbumFormCreateActive");
					$(newForm).attr("name", "newAlbumFormCreateActive");
					
					$("#facebox .ftitle").html( $.gt.gettext("Create a new or Modify an existing Album") );
					$(newForm).unbind("submit").bind("submit", function(s){
						s.preventDefault();
						$.post($.TuiyoDefines.get("componentIndex")+'&format=json', $(newForm).serialize(), 
							function(inResponse){
								$.facebox.close();
								$("#organizePhotos").trigger("click");
								showReport( $.sprintf( $.gt.gettext("The album %s has been saved"), inResponse.album.name), "notice");
							}, 'json'
						);
					});
				});
			}
		};
	}();
	$.fn.extend({
		TuiyoPhotosUpload : TuiyoPhotos.upload,
		TuiyoPhotosSlide : TuiyoPhotos.slideshow,
		TuiyoPhotosRefresh : TuiyoPhotos.refresh,
		TuiyoPhotosOrganize : TuiyoPhotos.editAlbums,
		TuiyoPhotosAlbumView : TuiyoPhotos.viewAlbum,
		TuiyoPhotosNewAlbum:TuiyoPhotos.newAlbum
	});
	$(document).ready(function(){
		
		$("#userActivityStream").TuiyoStream();
		$("#userActivityStream").TuiyoStreamLoad({source:'photos'});	
		$(".createAlbum").TuiyoPhotosNewAlbum();
		$(".organizeAlbums").TuiyoPhotosOrganize();
		$("ul.publisherTabItems li").each(function(i) {
            $(this).unbind('click').click(function(e) {
                e.preventDefault();
                $("ul.publisherTabItems li.current").removeClass('current');
                $(this).addClass('current');
                var pageEl = $(this).attr('id') ;
                $("div.pageEl").hide();
                $("div."+pageEl).show();
                
            });
        });
		$("#slideShow").TuiyoPhotosSlide();
		
		$("div.albumListItem a").bind('click',function(){
			var $aid = $(this).attr("rel");
			$("#slideShow").trigger("click", $aid );
		});
		$("a[rel=addComment]").bind("click",function(e){
			e.preventDefault();
			$("div.statusTool").hide();
			$("#commenterBox").find(".publisher").show();
		})
		
		$("#recentPhotos").TuiyoPhotosRefresh();
		$("li.albumLink").TuiyoPhotosAlbumView();
		
		//Kick Start any slideshow
		if( $.getUrlParam("album") ){
			$("#slideShow").trigger("click", $.getUrlParam("album") );
		}

	});
})(jQuery)
