/**
*
* appendDom - Extremely flexible tool for dynamic dom creation.
*   http://byron-adams.com/projects/jquery/appendDom
*
* Copyright (c) 2007 Byron Adams (byron.adams54@gmail.com)
* Dual licensed under the MIT (MIT-LICENSE.txt)
* and GPL (GPL-LICENSE.txt) licenses.
*
* 11/03/09 - Bugs fixed by Patricio Palladino pato89 (at) gmail (dot)com
*
*/
(function($){
	$.fn.appendDom = function(template) {
	  return this.each(function() {
	    for (element in template) {
	      var domel = (typeof(template[element].tagName) === 'string') ?
	        document.createElement(template[element].tagName) :
	        document.createTextNode('');
	      delete template[element].tagName;
	      for (attrib in template[element]){
	        if(attrib == 'className'){
	          $(domel).addClass(template[element][attrib]);
	          delete template[element].className;
	        }
			if(attrib == 'style'){
				$(domel).css(template[element][attrib]);
				delete template[element].className;
			}
	        switch ( typeof(template[element][attrib]) ) {
	          case 'string' :
	            if ( typeof(domel[attrib]) === 'string' ) {
	              domel[attrib] = template[element][attrib];
	            } else {
	              domel.setAttribute(attrib, template[element][attrib]);
	            }
	            break;
	          case 'function':
			    //alert( atrrib );
				$(domel).bind( attrib , template[element][attrib]  );
			  	//domel.attr(attrib, template[element][attrib] );
	            //domel[attrib] = template[element][attrib];
				//domel.setAttribute(attrib, template[element][attrib]);
	            break;
	          case 'object' :
	            if (attrib === 'childNodes') $(domel).appendDom(template[element][attrib]);
	            break;
	        }
	      }
	      this.appendChild(domel);
	    }
	  });
	};
})(jQuery)
