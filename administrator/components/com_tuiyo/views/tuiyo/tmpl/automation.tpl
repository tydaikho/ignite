<?php  defined('TUIYO_EXECUTE' ) || die; ?>

<div id="w1" class="windowWrapperShadow">
    <div class="windowWrapper">
        <div class="window" style="background: #fff">
        
            <div class="windowTitleBar">
                <img src="<?php echo $iconPath ?>/icons/repost_16.png" alt="hpact16" style="cursor: pointer" />
                <strong>Automation center</strong>
            </div>
            
            <div class="windowBody" style="padding: 18px; margin-top: 5px;">
            	
                <div class="tuiyoTable">
                	<div class="tuiyoTableRow">                    	
                        <div class="tuiyoTableCell" style="width: 66%;">
							<div class="dashBoardWidget">
                                <div class="dashBoardWidgetBody" >
                                    <div id="adminPagePublisherTabs">
                                        <ul class="publisherTabItems">
                                            <li id="em" style="padding: 0 20px" class="current"><a href="#"><span>Execute Macros</span></a></li>
                                            <li id="mm" style="padding: 0 20px" ><a href="#"><span>Manage Macros</span></a></li>
                                        </ul>
                                        <div class="tuiyoClearFloat"></div>
                                    </div>  
                                    <div id="adminPageTabContent">  
                                        <div class="em childTab">
											<?php  ?>                            
                                            <form class="TuiyoForm">  
											                                
                                                <textarea class="TuiyoFormTextArea" id="automationPrompt" style="width: 98%; min-height: 700px" cols="15"><?php if(isset($macro)&&is_object($macro) && method_exists($macro , "run")): $macro->run();   endif; ?></textarea>
                                            </form>
                                        </div>
                                        <div class="mm childTab" style="display: none">Manage installed Macros</div>
                                    </div>
                                    <div class="tuiyoClearFloat"></div>
                                </div>
                            </div>                                   	
                       	</div>
                        <div class="tuiyoTableCell" style="width: 34%">
                        <!--Update System-->
                            <div class="tuiyoTableRow">                            
                            	<div class="dashBoardWidgetBodySubHead">Update system</div>
                                <div class="tuiyoTableCell" style="width: 80%">                                    
                                    <p style="padding: 8px">Automatically Updates tuiyo, Click here to check and upgrade now</p>
                                </div>
                                <div class="tuiyoTableCell" style="width: 20%">
                                    <div style="float: left;">
                                        <div class="icon" align="center">
                                            <a href="<?php echo JRoute::_( TUIYO_INDEX.'&amp;context=SystemTools&amp;do=autoCenter&amp;run=systemupdate' ); ?>">
                                            	<img alt="#"  src="<?php echo $iconPath ?>/images/run.png ?>" align="middle"  />            
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="tuiyoClearFloat"></div>
                            </div>                                                        
                        </div>
                    	<div class="tuiyoClearFloat"></div>
                    </div>
                </div>                
           	</div>            
       	</div>
    </div>
</div>