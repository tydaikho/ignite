/**
 * @author Livingstone
 */
(function($) {
	$(document).ready(function(){
		$("ul#pageNavigation li").each(function(k){
			$(this).click(function(f){
				f.preventDefault();
				$("ul#pageNavigation li.current").removeClass('current');
				$(this).addClass('current');
				exeCalFunc($(this));
			});
		});
		$("a#createNewEventAction").bind('click', function(e){
			e.preventDefault();
			$.facebox( $("div#createDivEvent").clone().show() );
			$("#facebox .ftitle").html( $.gt.gettext("Create a new Event") );
			$("input[name=startdate] , input[name=enddate]").datepicker({
				changeMonth: true,
				changeYear: true,
				dateFormat: 'yy-mm-dd',
				//minDate: '-70Y',
				numberOfMonths: 2,
				showButtonPanel: true

			});			
		});
		$("div.calendarDay:not(div.inactiveDay)").bind("click", function(){
			//alert('mouser in');
			$("div.calendarDayActive").remove();
			
			var self		= $(this),
				startdate 	= $(this).attr("rel"),
				template 	= [
				{tagName: 'div', className:'tuiyoTableRow', style:'padding: 0 10px; position: relative; z-index: 100; border-bottom: 2px dashed #ccc', childNodes:[
					{tagName:'div', className:'tuiyoTableCell', style:'width: 90%; font-weight: bold', innerHTML: $(this).attr('title') },
					{tagName:'div', className:'tuiyoTableCell', style:'width: 10%', childNodes:[
						{tagName:'a', href:'#', className:'closeDateDetails', innerHTML: $.gt.gettext('close'), click:function(e){
							e.preventDefault();
							$("div.calendarDayActive").remove();
						}}
					]},
					{tagName:'div', className:'tuiyoClearFloat'}
				]},
				{tagName: 'div', className:'tuiyoTableRow dayEventDetails', style:'padding: 10px', childNodes:[
					{tagName:'img', src:'components/com_tuiyo/client/default/images/loading.gif'}
				]}
			];
			
			$('<div class="calendarDayActive"></div>').appendDom(template).insertAfter( $(this) )
			.css({"position" : "absolute", "top" : $(this).offset().top+'px', "left" : $(this).offset().left+'px'});
			
			if (!$.data($(self), "daysItems")) {
				$.getJSON('index.php?' + $.TuiyoDefines.get("token") + '=1&format=json', {
					'day': startdate,
					'option': 'com_tuiyo',
					'controller': 'messages',
					'views': 'messages',
					'do': 'getDaysEvents'
				}, function(resp){
					var data = $('<span />').text($.gt.gettext("There are no events on this day"));
					
					if (resp.data.length > 0) {
						var eventlist = $('<ol />');
						$.each(resp.data, function(i, event){
							$('<li class="eventItem tpl' + event.privacy + '" />').appendDom([{
								tagName: 'div',
								childNodes: [{
									tagName: 'h5',
									innerHTML: '<a>'+event.title+'</a>'
								}, {
									tagName: 'div',
									childNodes: [{
										tagName: 'span',
										innerHTML: 'Starts: ' + event.starttime
									}, {
										tagName: 'br'
									}, {
										tagName: 'span',
										innerHTML: 'Ends: ' + event.endtime
									}]
								}]
							}]).appendTo($(eventlist));
						});
						
						data = $(eventlist);
					}
					$.data($(self),"daysItems", data);
					$(data).appendTo($("div.calendarDayActive").find("div.dayEventDetails").empty());
				}, 'json');
			}else{
				$( $.data($(self), "daysItems") ).appendTo($("div.calendarDayActive").find("div.dayEventDetails").empty());				
			}			
		});
	});
	
	function exeCalFunc(E /*, hash */){
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
