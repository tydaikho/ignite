<?php  defined('TUIYO_EXECUTE' ) || die; ?>
<div id="w1" class="windowWrapperShadow">
    <div class="windowWrapper" id="systemConfig">
        <div class="window" style="background: #fff">
            <div class="windowTitleBar">
                <img src="<?php echo $iconPath ?>/icons/chart_16.png" alt="hpact16" style="cursor: pointer" />
                <strong>System Tools &bull; Site statistics</strong>
            </div>
            <div class="windowBody" style="padding: 18px; margin-top: 5px;">
            	<div class="tuiyoTable">
                	<div class="tuiyoTableRow">
                    	<div class="tuiyoTableCell" style="width: 66%;">
                            <div class="dashBoardWidget">
                            <div class="dashBoardWidgetBody">
                                <div id="adminPagePublisherTabs">
                                    <ul class="publisherTabItems">
                                        <li style="padding: 0 20px"><a href="#"><span>Page views</span></a></li>
                                        <li style="padding: 0 20px" class="current"><a href="#"><span>Traffic source</span></a></li>
                                        <li style="padding: 0 20px"><a href="#"><span>Bounce rates</span></a></li>  
                                        <li style="padding: 0 20px"><a href="#"><span>Visit by country</span></a></li>
                                    </ul>
                                    <div class="tuiyoClearFloat"></div>
                                </div>
                                <div id="adminPageTabContent" > mainbage  </div>
                            </div>
                            </div>                                                                         
                        </div>
                        <div class="tuiyoTableCell" style="width: 34%;">
                        
                            <div class="dashBoardSideBarWidget">
                            	<div class="dashBoardWidgetHeader">Configure Google Analytics</div>
                                <div class="dashBoardSideBarWidgetBody TuiyoTable" style="padding: 8px">
                                	<form name="configureStatistics" id="configureStatistics" method="post" class="TuiyoForm" action="index2.php" >
                                		<div class="tuiyoTableRow">
                                        	<div class="tuiyoTableCell" style="width: 35%">Username</div>
                                            <div class="tuiyoTableCell" style="width: 65%">
                                            	<input type="text" name="gausername" id="gausername" class="TuiyoFormText" />
                                            </div><div class="tuiyoClearFloat"></div>
                                        </div>
                                		<div class="tuiyoTableRow">
                                        	<div class="tuiyoTableCell" style="width: 35%">Password</div>
                                            <div class="tuiyoTableCell" style="width: 65%">
                                            	<input type="text" name="gapassword" id="gapass" class="TuiyoFormText" />
                                            </div><div class="tuiyoClearFloat"></div>
                                        </div> 
                                		<div class="tuiyoTableRow">
                                        	<div class="tuiyoTableCell" style="width: 35%">Table ID</div>
                                            <div class="tuiyoTableCell" style="width: 65%">
                                            	<input type="text" name="gatabid" id="gatabId" class="TuiyoFormText" />
                                            </div><div class="tuiyoClearFloat"></div>
                                        </div>   
                                		<div class="tuiyoTableRow">
                                        	<div class="tuiyoTableCell" style="width: 35%">&nbsp;</div>
                                            <div class="tuiyoTableCell" style="width: 65%">
                                            	<button type="submit" id="gasubmit" value="gasubmit">Save settings</button>
                                            </div><div class="tuiyoClearFloat"></div>
                                        </div>                                                                                                                    
                                  		<?php echo JHTML::_('form.token') ?>
                                        <input type="hidden" name="option" value="com_tuiyo" />
                                        <input type="hidden" name="context" value="systemTools" />
                                        <input type="hidden" name="do" value="statistics" />
                                        <input type="hidden" name="a" value="" />
                                    </form> 
                                </div>
                       		</div> 
                            
                            <div class="dashBoardSideBarWidget">
                            	<div class="dashBoardWidgetHeader">Other Satistics</div>
                                <div class="dashBoardSideBarWidgetBody"></div>
                       		</div> 
                            
                            <div class="dashBoardSideBarWidget">
                            	<div class="dashBoardWidgetHeader">Users online</div>
                                <div class="dashBoardSideBarWidgetBody"></div>
                       		</div>
                             
                       	</div>
                    	<div class="tuiyoClearFloat"></div>
                    </div>
                </div>
            </div>
       </div>
    </div>
</div>