<?php  defined('TUIYO_EXECUTE' ) || die; ?>
<div id="w1" class="windowWrapperShadow">
    <div class="windowWrapper">
        <div class="window" style="background: #fff">
            <div class="windowTitleBar">
                <img src="<?php echo $iconPath ?>/icons/appengine_16.png" alt="hpact16" style="cursor: pointer" />
                <strong><?php echo _('Welcome to the Tuiyo Administrator Panel'); ?></strong>
            </div>
            <div class="windowBody" style="padding: 18px; margin-top: 5px;">
        		<div class="tuiyoTable">
                    <div class="tuiyoTableRow">
                        <div class="tuiyoTableCell" style="width: 55%;">
                        
                        	<?php echo $activity; ?>
                                                                 
                        </div>
                        <div class="tuiyoTableCell" style="width: 45%;">
                        	<!--Control Panel-->
                            <div class="dashBoardSideBarWidget">
                                <div class="dashBoardSideBarWidgetBody">
                                    <div style="float: left;">
                                        <div class="icon" align="center">
                                            <a href="<?php echo JRoute::_( TUIYO_INDEX.'&amp;context=extensions' ); ?>">
                                            <img alt="#"  src="<?php echo $iconPath ?>/images/widgets.png" align="middle"  /> 
                                            <span><?php echo _('Installer') ; ?></span>
                                            </a>
                                        </div>
                                    </div>             
                                    <div style="float: left;">
                                        <div class="icon" align="center">
                                            <a href="<?php echo JRoute::_( TUIYO_INDEX.'&amp;context=extensions#Plugins' ); ?>">
                                            <img alt="#"  src="<?php echo $iconPath ?>/images/plugin.png" align="middle"  /> 
                                            <span><?php echo _('Plugins') ;?></span>
                                            </a>
                                        </div>
                                    </div>                                                                                                                    
                                    <div style="float: left;">
                                        <div class="icon" align="center">
                                            <a href="<?php echo JRoute::_( TUIYO_INDEX.'&amp;context=CommunityManagement&amp;do=userList' ); ?>">
                                                <img alt="#"  src="<?php echo $iconPath ?>/images/groups.png" align="middle"  /> 
                                                <span><?php echo _('Users'); ?></span>
                                            </a>
                                        </div>
                                    </div>
                                    <div style="float: left;">
                                        <div class="icon" align="center">
                                            <a href="<?php echo JRoute::_( TUIYO_INDEX.'&amp;context=SystemTools&amp;do=globalConfig#global' ); ?>">
                                                <img alt="#"  src="<?php echo $iconPath ?>/images/settings.png" align="middle"  /> 
                                                <span><?php echo _('Settings' ); ?></span>
                                            </a>
                                        </div>
                                    </div>                    
                                    <div style="float: left;">
                                        <div class="icon" align="center">
                                            <a href="<?php echo JRoute::_( TUIYO_INDEX.'&amp;context=SystemTools&amp;do=autoCenter' ); ?>">
                                            <img alt="#"  src="<?php echo $iconPath ?>/images/macros.png" align="middle"  /> 
                                            <span><?php echo _('AutoCenter'); ?></span>
                                            </a>
                                        </div>
                                    </div>
                                    <div style="float: left;">
                                        <div class="icon" align="center">
                                            <a href="<?php echo JRoute::_( TUIYO_INDEX.'&amp;context=SystemTools&amp;do=reportBug' ); ?>">
                                            <img alt="#"  src="<?php echo $iconPath ?>/images/pencil.png" align="middle"  /> 
                                            <span><?php echo _('Bug reports'); ?></span>
                                            </a>
                                        </div>
                                    </div>                                    
                                    <div class="tuiyoClearFloat"></div>                        
                                </div>
                            </div>                                                      
                            <!--Inbox-->
                            <div class="dashBoardSideBarWidget">
                                <div class="dashBoardSideBarWidgetHeader"><div  class="collapser" ><a href="#">&nbsp;</a></div> Inbox &amp; Notifications</div>
                                <div class="dashBoardSideBarWidgetBody">

                                    <!--Inbox Item-->
                                    <div class="tuiyoTable profileNoticesItem">
                                        <div class="tuiyoTableRow">
                                            <div class="tuiyoTableCell" style="width: 91%; margin-left: 5px">
                                            	<a href="#w2" rel="facebox">Re: Important bug notification</a></div>
                                            <div class="tuiyoTableCell" style="width: 4%; padding: 4px">
                                                <a href="#w2" style="margin: auto" rel="facebox"><img alt="#"  src="<?php echo $iconPath?>/icons/file_txt_16.png"  /></a>
                                            </div>
                                            <div class="tuiyoClearFloat"></div>
                                        </div>
                                    </div>  
                                    <!--Inbox Item-->
                                    <div class="tuiyoTable profileNoticesItem">
                                        <div class="tuiyoTableRow">
                                            <div class="tuiyoTableCell" style="width: 91%; margin-left: 5px">
                                            	<a href="#w2" rel="facebox">Having some problems loggin in</a></div>
                                            <div class="tuiyoTableCell" style="width: 4%; padding: 4px">
                                                <a href="#w2" style="margin: auto" rel="facebox"><img alt="#"  src="<?php echo $iconPath?>/icons/file_txt_16.png"  /></a>
                                            </div>
                                            <div class="tuiyoClearFloat"></div>
                                        </div>
                                    </div> 
                                                       
                                </div>
                            </div>
                            <!--Friends suggestion--> 
                            <div class="dashBoardSideBarWidget">
                                <div class="dashBoardSideBarWidgetHeader"><div  class="collapser" ><a href="#">&nbsp;</a></div>Users online now.</div>
                                <div class="dashBoardSideBarWidgetBody">There are no users online</div>
                            </div>                                       
                        </div>
                        <div class="tuiyoClearFloat"></div>
                    </div>
                </div>            
            </div>
        </div>   
    </div>
</div>