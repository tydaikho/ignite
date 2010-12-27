<?php  defined('TUIYO_EXECUTE' ) || die; ?>
<div id="w1" class="windowWrapperShadow">
    <div class="windowWrapper">
        <div class="window" style="background: #fff">
            <div class="windowTitleBar">
                <img src="<?php echo $iconPath ?>/icons/plugins_16.png" alt="hpact16" style="cursor: pointer" />
                <strong>System plugins</strong> <a href="<?php echo JRoute::_( TUIYO_INDEX.'&amp;context=SystemTools&amp;do=globalConfig' ); ?>"> [view Settings]</a>
            </div>
            <div class="windowBody" style="padding: 18px; margin-top: 5px;">
            	<div class="tuiyoTable">
                	<div class="tuiyoTableRow">
                    	<div class="tuiyoTableCell" style="width: 66%;">
                           <form name="extensionManageLists" id="extensionManageLists" action="index.php" method="post" class="TuiyoForm">
   								<div class="dashBoardWidget">
                                <div class="dashBoardWidgetBody">
                                	<div id="adminPagePublisherTabs">
                                        <ul class="publisherTabItems">
                                            <li class="current"><!--<img alt="#"  src="<?php echo $iconPath ?>/icons/applications_16.png" />-->
                                            	<a rel="aApplications" href="index.php?do=getApplications"></a>
                                            	<a href="#">Applications</a>
                                            </li><li><!--<img alt="#"  src="<?php echo $iconPath ?>/icons/plugins_16.png" />-->
                                            	<a rel="aPlugins" href="index.php?do=getPlugins"></a>
                                            	<a href="#">Plug-ins</a>
                                            </li><li><!--<img alt="#"  src="<?php echo $iconPath ?>/icons/widgets16.png"  />-->
                                            	<a rel="aWidgets" href="index.php?do=getWidgets"></a>
                                            	<a href="#">Widgets</a>
                                            </li><li><!--<img alt="#"  src="<?php echo $iconPath ?>/icons/sbook_16.png"  />-->
                                            	<a rel="aLanguages" href="index.php?do=getLanguages"></a>
                                            	<a href="#">Languages</a>
                                            </li>
                                        </ul>
                                        <div class="tuiyoClearFloat"></div>
                                    </div>
                                    <div id="adminPageTabContent" class="adminPageTabContentList" >&nbsp;</div>
                                </div>
                                </div>
                                <?php echo JHTML::_('form.token') ?>
                                <input type="hidden" name="option" value="com_tuiyo" />
                       		</form>                                                                          
                        </div>
                        <div class="tuiyoTableCell" style="width: 34%;">
                        
                        	<!--Quick Installer-->
                            <div class="dashBoardSideBarWidget">
                            	<div class="dashBoardSideBarWidgetHeader"><div  class="collapser" ><a href="#">&nbsp;</a></div>Install new Extensions</div>
                                <div class="dashBoardSideBarWidgetBody" style="padding: 10px">
                                    <form name="installer" id="installer" action="index2.php" class="TuiyoForm" method="post" enctype="multipart/form-data">
                                        <div class="tuiyoTable">
                                            <div class="tuiyoTableRow" style="margin: 0pt 0pt 10px 0pt;">
                                                <div class="tuiyoTableCell" style="width: 35%;">Archive</div>
                                                <div class="tuiyoTableCell" style="width: 64%;">
                                                  <input type="file" name="applicationfile" id="applicationfile"  value=""
                                                        style="border: 1px solid #ccc; padding: 4px; " />
                                                </div>
                                                <div class="tuiyoClearFloat"></div>
                                            </div>
                                             <div class="tuiyoTableRow" style="margin: 7px 0pt;">
                                                <div class="tuiyoTableCell" style="width: 35%;">In Directory</div>
                                                <div class="tuiyoTableCell" style="width: 64%;">
                                                    <input type="text" name="applicationfiledir" 
                                                            id="applicationfiledir" class="TuiyoFormText" disabled  value="" />
                                                </div>
                                                <div class="tuiyoClearFloat"></div>
                                            </div>
                                             <div class="tuiyoTableRow" style="margin: 7px 0pt;">
                                                <div class="tuiyoTableCell" style="width: 35%;">Extension URL</div>
                                                <div class="tuiyoTableCell" style="width: 64%;">
                                                  <input type="text" name="applicationfiledirurl" 
                                                           id="applicationfiledirurl" class="TuiyoFormText" disabled  value="" />
                                                </div>
                                                <div class="tuiyoClearFloat"></div>
                                            </div>        
                                            <div class="tuiyoTableRow" style="margin: 7px 0pt;">
                                                <div class="tuiyoTableCell" style="width: 35%;">&nbsp;</div>
                                                <div class="tuiyoTableCell" style="width: 64%;">
                                                    <button type="submit" class="button">Install extensions</button>
                                                </div>
                                                <div class="tuiyoClearFloat"></div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="option" value="com_tuiyo" />
                                        <input type="hidden" name="cmd" value="doInstall" />
                                        <input type="hidden" name="token" value="" />
                                        <?php echo JHTML::_('form.token'); ?>
                                    </form>                                	
                                </div>
                       		</div> 
                       	</div>
                    	<div class="tuiyotuiyoClearFloat"></div>
                    </div>
                </div>
            </div>
       </div>
    </div>
</div>