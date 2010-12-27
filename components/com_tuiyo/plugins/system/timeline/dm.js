(function($){
	var 
		newMessageTmpl = function(to, body){
			return $('<div class="TuiyoForm tuiyoTable tuiyoStreamPlugin" id="directMessageForm">').appendDom([
				{tagName:'form', submit:function(e){
					e.preventDefault();
					alert('send Message');
					$(this)
				}, childNodes:[
					{tagName:'h3', innerHTML: 'direct message to '+to, style:'margin-bottom: 4px; margin-top: -10px;' },
					{tagName:'div', 'class':'tuiyoTableRow', childNodes:[
						{tagName:'div', 'class':'tuiyoTableCell', style:'width: 90%', childNodes:[
							{tagName:'textarea', 'class':'TuiyoFormTexArea', style:'width: 97%; font-size: 13px; font-family: segoe UI', innerHTML: body }
						]},
						{tagName:'div', 'class':'tuiyoTableCell', style:'width: 10%; text-align: center', childNodes:[
							{tagName:'button', innerHTML:'send' },
							{tagName:'a', style:'cursor: pointer', innerHTML:'cancel', click:function(e){
								e.preventDefault();
								$(this).parent().parent().parent().parent().fadeOut("slow");
								//Always re-enable
								 $("#psubmit").removeAttr('disabled');	
							}}
						]},
						{tagName:'div', 'class':'tuiyoClearFloat'}
					]}
				]}
			]);
		};
	$("#TuiyoStreamUpdate").unbind("dm").bind("dm" , function(){
		 
		 $("#psubmit").attr('disabled' , true );	
		 
		 var text = $("#ptext").val();
		 var toRegExp  = /[\@]+([A-Za-z0-9-_]+)/;
		 var cmdRegExp = /[\$]+([A-Za-z0-9-_]+)[:]/ ;
		 
		 var toAr   = text.match( toRegExp );
		 var to     = (toAr !== null)?  toAr[1] : "nobody? (Message cannot be sent)";
		 var body   = ( text.replace( cmdRegExp , 'Hi, ' ) ).replace( toRegExp , "$1");
		 
		 //Show the form
		 //$("#directMessageForm").remove();
		 $( newMessageTmpl(to, body) )
		  .hide().insertAfter(".homepagePublisherContainer, profilepagePublisher" )
		  .slideDown("slow");
	});
})(jQuery)
