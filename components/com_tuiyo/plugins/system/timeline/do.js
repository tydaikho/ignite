(function($){
	var 
		uploadPhotos = function(){},
		newGroup = function(){
			alert('create a new group')	
		};
	$("#TuiyoStreamUpdate").unbind("do").bind("do" , function(){
		
		alert( $("#ptext").val() );
		
		//Always re-enable
		$("psubmit").disabled = false;
	});
})(jQuery)
