<?php  defined('TUIYO_EXECUTE' ) || die; ?>

<div class="tuiyoTable">
	<form name="tuiyoMiniProfile" class="TuiyoForm" id="tuiyoMiniProfile" method="post">
	<div class="tuiyoTableRow">
    	<div class="tuiyoTableCell" style="width: 11%">
        	<img src="http://s3.amazonaws.com/twitter_production/profile_images/318612214/twitterProfilePhoto_bigger.jpg" width="67" />
            <a href="#">suspend</a>
            <a href="#">delete</a>
        </div>
        <div class="tuiyoTableCell" style="width: 45%;" >
        	<div class="tuiyoTable miniProfileData" >      
            	<div class="tuiyoTableRow">
                	<div class="tuiyoTableCell" style="width: 70%">
                    	<textarea name="newNote" id="newNote" class="TuiyoFormTextArea" style="margin: 5px 5px 5px 0px;"></textarea>
                    </div>
                	<div class="tuiyoTableCell" style="width: 30%">
                    	<button name="submitNote" id="submitNote" style="margin: 5px; padding: 10px; font-size: 13px;">Submit</button>
                    </div>
                	<div class="tuiyoClearFloat"></div>
                </div>
                                                                              
            </div>
        </div>
        <div class="tuiyoTableCell" style="width: 42%">
        
             <!--Note Item-->
            <div class="tuiyoTable profileNoticesItem">
                <div class="tuiyoTableRow" style="border-bottom: 1px dotted #ccc">
                    <div class="tuiyoTableCell" style="width: 90%; margin-left: 5px">
                        <a href="#w2" rel="facebox">Re: Important issues with my profile</a></div>
                    <div class="tuiyoTableCell" style="width: 4%; padding: 4px">
                        <a href="#w2" style="margin: auto" rel="facebox"><img alt="#"  src="<?php echo $iconPath?>/icons/file_txt_16.png"  /></a>
                    </div>
                    <div class="tuiyoClearFloat"></div>
                </div>
            </div>  
            <!--Note Item-->
            <div class="tuiyoTable profileNoticesItem">
                <div class="tuiyoTableRow" style="border-bottom: 1px dotted #ccc">
                    <div class="tuiyoTableCell" style="width: 90%; margin-left: 5px">
                        <a href="#w2" rel="facebox">Checking case  reports</a></div>
                    <div class="tuiyoTableCell" style="width: 4%; padding: 4px">
                        <a href="#w2" style="margin: auto" rel="facebox"><img alt="#"  src="<?php echo $iconPath?>/icons/file_txt_16.png"  /></a>
                    </div>
                    <div class="tuiyoClearFloat"></div>
                </div>
            </div> 
            
        </div>
        <div class="tuiyoClearFloat"></div>
    </div>
    </form>
</div>