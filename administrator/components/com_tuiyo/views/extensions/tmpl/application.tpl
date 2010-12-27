<?php  defined('TUIYO_EXECUTE' ) || die; ?>
<div id="w1" class="windowWrapperShadow">
    <div class="windowWrapper">
        <div class="window" style="background: #fff">
            <div class="windowTitleBar">
                <img src="../components/com_tuiyo/applications/<?php echo $appData['data']->name ?>/favicon.png" alt="f16" style="cursor: pointer" />
                <strong><?php echo $appData['data']->title ?></strong>
            </div>
            <div class="windowBody" style="padding: 18px; margin-top: 5px;">
                <div class="tuiyoTable">
                	<div class="tuiyoTableRow">
                    	<div class="tuiyoTableCell" style="width: 66%;">                         	
 							<form name="manageApplication" id="manageApplication" action="index.php" method="post" class="TuiyoForm">
   								<div class="dashBoardWidget">
                                <div class="dashBoardWidgetBody">
                                	<div id="adminPagePublisherTabs">
                                        <ul class="publisherTabItems">
                                            <li class="current">
                                            	<a rel="aApplications" href="index.php?childDo=getAppDashboard"></a>
                                            	<a href="#">Manage application</a>
                                            </li>
                                            <li>
                                            	<a rel="aPlugins" href="index.php?childDo=getAppConfig"></a>
                                            	<a href="#">Configure <?php echo $appData['data']->title ?></a>
                                            </li>
                                            <li>
                                            	<a rel="aWidgets" href="index.php?childDo=getAppLanguage"></a>
                                            	<a href="#">Available languages</a>
                                            </li>
                                        </ul>
                                        <div class="tuiyoClearFloat"></div>
                                    </div>
                                    <div id="adminPageTabContent">&nbsp;</div>
                                </div>
                                </div>
                                <?php echo JHTML::_('form.token') ?>
                                <input type="hidden" name="option" value="com_tuiyo" />
                                <input type="hidden" name="context" value="extensions" />
                                <input type="hidden" name="do" value="editApplication" />
                                <input type="hidden" name="a" value="<?php echo $appData['data']->name ?>" />
                                <input type="hidden" name="childDo" value="" />
                       		</form>                                     
                        </div>
                        <div class="tuiyoTableCell" style="width: 34%;">
                        	<!--Quick system Tools-->
                            <div class="dashBoardSideBarWidget">
                            	<div class="dashBoardWidgetHeader"><div  class="collapser" ><a href="#">&nbsp;</a></div>Application information</div>
                                <div class="dashBoardSideBarWidgetBody">
                                    <div class="systemViews" style="margin-bottom: 10px">
                                        <div class="dashBoardWidgetBodySubHead">Author 
                                        	<span style="color: green"><?php echo $appData['data']->author ?></span>
                                   		</div>
                                    </div>    
                                    <div class="systemViews" style="margin-bottom: 10px">
                                        <div class="dashBoardWidgetBodySubHead">Version
                                        	<span style="color: green"><?php echo $appData['data']->version ?></span> Installed 
                                            <span style="color: green">
                                            	<?php echo TuiyoTimer::diff( strtotime( $appData['data']->installedDate ) ) ?></span>
                                   		</div>
                                    </div> 
                                    <div class="systemViews" style="margin-bottom: 10px">
                                        <div class="dashBoardWidgetBodySubHead">Author Name
                                        	<span style="color: green"><?php echo $appData['data']->author ?></span>
                                   		</div>
                                    </div>
                                    <div class="systemViews" style="margin-bottom: 10px">
                                        <div class="dashBoardWidgetBodySubHead">Author's Website 
                                        <a href="<?php echo $appData['data']->website ?>"><?php echo $appData['data']->website ?></a> 
                                    	</div>
                                    </div>
                                    <div class="systemViews" style="margin-bottom: 10px">
                                        <div class="dashBoardWidgetBodySubHead">Authors e-mail
                                        	<a href="mailto:<?php echo $appData['data']->email ?>"><?php echo $appData['data']->email ?></a> 
                                        </div>
                                    </div> 
                                    <div class="systemViews" style="margin-bottom: 10px">
                                        <div class="dashBoardWidgetBodySubHead">Currently in use by
                                        	<span style="color: green"><?php echo $appData['data']->usersCount ?></span> user/s
                                   		</div>
                                    </div>                                    
                                    <div class="systemViews" style="margin-bottom: 10px">
                                        <div class="dashBoardWidgetBodySubHead">Author's Description</div>
                                        <p style="margin: 6px"><?php echo $appData['data']->description ?></p>
                                    </div>                                                                         
                                </div>
                       		</div> 
                       	</div><div class="tuiyoClearFloat"></div>
                    </div>
                </div>
            </div>            
       </div>
    </div>
</div>