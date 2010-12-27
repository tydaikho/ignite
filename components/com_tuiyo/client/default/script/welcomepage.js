/**
 * @author Livingstone
 */
(function($) {
	$(document).ready(function(){
		$('a[rel*=doFunction]').click(function(a){
			doFunction($(this).parent());
			a.preventDefault();
		});
		$('#TuiyoLoginForm').submit(function(event){
			TuiyoLogin.submit(event, $(this))
		});
		$("ul.publisherTabItems li").each(function(i){
			$(this).click(function(e){
				e.preventDefault();
				$("ul.publisherTabItems li.current").removeClass('current');
				$(this).addClass('current');
				
				doFunction($(this));
			});
		});
		$("ul#publisherTabItems li:eq(0)").addClass('current');
		$("ul#pageNavigation li").each(function(k){
			$(this).click(function(f){
				f.preventDefault();
				$("ul#pageNavigation li.current").removeClass('current');
				$(this).addClass('current');
				
				doFunction($(this));
			});
		});
		$("#signInBtn").bind('click',function(){
			$.facebox({div:"#loginPageSignIn"})
		});
		//Reset Toos
		$("#signUpBtn").bind('click',function(){
			$.facebox({div:"#loginPageCreate"})
		});
		$("#userActivityStream").TuiyoStream();
		$("#userActivityStream").TuiyoStreamLoad();	
	});
	function doFunction(E /*, hash */){
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
	var TuiyoLogin = new Object({
		forgotPassword : function(){
			$.facebox('forgot password');
		},
		createNewAccount : function(){
			formDiv 	= $("div#CreateNewAccountFormDiv").clone();
			$.facebox(formDiv.css({
				'display'   :'block',
				'min-width' : '500px'
			}));
			$('#facebox form').submit(function(e){
				alert('Ajax.Step.1');
				$.post('index.php',$(this).serializeArray(),function(data){
					alert(data.code);
				} , "json" );
				e.preventDefault();
			})
			$("#facebox .ftitle").html("Step 1 : User Details");
		},
		loadExternals : function(){
			$.facebox('External logins');
		},
		submit : function(event, form){
			$(form).find("input.TuiyoFormButton1").val( $.gt.gettext("Processing...") );
			$.post('index.php',form.serializeArray(),function(resp){
				switch(resp.code){
					case 200:
						form.html('<div class="TuiyoSuccessMsg">'+resp.data+'</div>');
						window.location.reload(true);
					break;
					default:
						form.prepend( '<div class="TuiyoErrorMsg">'+resp.code+' : '+resp.error+'</div>' );	
					break;
				}
			} , "json" );
			event.preventDefault();
		}
	});
	var TuiyoWelcome = new Object({
		start: function(){alert('start');},
		getNewMembers : function(){alert('getNewMembers');},
		whatsNew : function(){alert('whatsNew');},
		featured: function(){ alert('featured');}
	});
})(jQuery)
