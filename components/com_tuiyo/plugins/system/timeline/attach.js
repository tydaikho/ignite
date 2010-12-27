/**
 * @author Livingstone
 */
(function($){
	$("#TuiyoStreamUpdate").unbind("attach").bind("attach" , function(){
		$("#psubmit").attr('disabled' , true );
		
		alert('attach file');
		
		$("#psubmit").attr('disabled' , false );
	});
})(jQuery)
