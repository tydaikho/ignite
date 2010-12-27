<?php  defined('TUIYO_EXECUTE' ) || die; ?>
<div id="w1" class="windowWrapperShadow">
    <div class="windowWrapper" id="systemConfig">
        <div class="window" style="background: #fff">
            <div class="windowTitleBar">
                <img src="<?php echo $iconPath ?>/icons/control_16.png" alt="hpact16" style="cursor: pointer" />
                <strong>System Tools &bull; Configuration</strong>
            </div>
            <div class="windowBody" style="padding: 18px; margin-top: 5px;">
            	<div class="tuiyoTable">
                	<div class="tuiyoTableRow">
                    	<div class="tuiyoTableCell" style="width: 66%;"> 
                            <div class="dashBoardWidget">
                                <div class="dashBoardWidgetBody tuiyoTable">
                                    <div id="adminPagePublisherTabs">
                                        <ul class="publisherTabItems">
                                            <li id="ss" style="padding: 0 20px" class="current"><a href="#"><span>System</span></a></li>
                                            <li id="fcs" style="padding: 0 20px" ><a href="#"><span>Friends</span></a></li>
                                            <li id="gs" style="padding: 0 20px" ><a href="#"><span>Groups</span></a></li>
                                            <li id="ps" style="padding: 0 20px" ><a href="#"><span>Photos</span></a></li>
                                            <li id="cs" style="padding: 0 20px" ><a href="#"><span>Events</span></a></li>
                                            <li id="ms" style="padding: 0 20px" ><a href="#"><span>Messages</span></a></li>
                                        </ul>
                                        <div class="tuiyoClearFloat"></div>
                                    </div>
                                    <div id="adminPageTabContent">                                	
                                    	<div class="ss childTab">
                                        	<form name="system.global" id="system.global" action="index.php" method="post" class="TuiyoForm">
                                                <div class="tuiyoTableRow">
                                                    <div class="tuiyoTableCell" style="width: 30%;">Site Title</div>
                                                    <div class="tuiyoTableCell" style="width: 70%;">
                                                        <input type="text" name="params[siteName]"  id="paramssiteName" style="width: 95%;" 
                                                              value="<?php echo $e->get('siteName') ?>" class="TuiyoFormText" />
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div> 
                                                <div class="tuiyoTableRow">
                                                    <div class="tuiyoTableCell" style="width: 30%;">Slogan</div>
                                                    <div class="tuiyoTableCell" style="width: 70%;">
                                                        <input type="text" name="params[siteTagLine]"  id="paramssiteTagLine" style="width: 95%;" 
                                                              value="<?php echo $e->get('siteName') ?>" class="TuiyoFormText" />
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div> 
                                                <div class="tuiyoTableRow">
                                                    <div class="tuiyoTableCell" style="width: 30%;">Description</div>
                                                    <div class="tuiyoTableCell" style="width: 70%;">
                                                        <textarea name="params[siteDescription]"  id="paramssiteDescription" style="width: 95%;" rows="3"
                                                               class="TuiyoFormTextArea"><?php echo $e->get('siteDescription') ?></textarea>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div> 
                                                <div class="tuiyoTableRow" style="margin: 8px 0;">
                                                    <div class="tuiyoTableCell" style="width: 30%;">Public Access?</div>
                                                    <div class="tuiyoTableCell" style="width: 70%;">
                                                        <input type="radio" name="params[sitePublicAccess]"  id="paramssitePublicAccess" value="1" checked="checked"/>
                                                        <span>Yes</span>
                                                        <input type="radio" name="params[sitePublicAccess]"  id="paramssitePublicAccess" value="0" class="TuiyoFormRadio" />
                                                        <span>No</span>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>
                                                <div class="tuiyoTableRow" style="margin: 8px 0;">
                                                    <div class="tuiyoTableCell" style="width: 30%;">Use PHP CURl?</div>
                                                    <div class="tuiyoTableCell" style="width: 70%;">
                                                        <input type="radio" name="params[serverEnableCURL]"  id="paramssiteserverEnableCURL" value="1" checked="checked" />
                                                        <span>Yes</span>
                                                        <input type="radio" name="params[serverEnableCURL]"  id="paramssiteserverEnableCURL" value="0" class="TuiyoFormRadio" />
                                                        <span>No</span>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>                                        
                                                <div class="tuiyoTableRow">
                                                    <div class="tuiyoTableCell" style="width: 30%;">Proxy Name</div>
                                                    <div class="tuiyoTableCell" style="width: 70%;">
                                                        <input type="text" name="params[serverHttpProxyName]"  id="paramsserverHttpProxyName" style="width: 95%;" 
                                                              value="<?php echo $e->get('serverHttpProxyName') ?>" class="TuiyoFormText" />
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>  
                                                <div class="tuiyoTableRow">
                                                    <div class="tuiyoTableCell" style="width: 30%;">Proxy Port</div>
                                                    <div class="tuiyoTableCell" style="width: 70%;">
                                                        <input type="text" name="params[serverHttpProxyPort]"  id="paramsserverHttpProxyPort" style="width: 95%;" 
                                                              value="<?php echo $e->get('serverHttpProxyPort') ?>" class="TuiyoFormText" />
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>                                                                                
                                                <div class="tuiyoTableRow" style="margin: 8px 0;">
                                                    <div class="tuiyoTableCell" style="width: 30%;">Enable Reporting?</div>
                                                    <div class="tuiyoTableCell" style="width: 70%;">
                                                        <input type="radio" name="params[siteEnableReporting]"  id="paramssiteEnableReporting" value="1" class="TuiyoFormRadio" />
                                                        <span>Yes</span>
                                                        <input type="radio" name="params[siteEnableReporting]"  id="paramssiteEnableReporting" value="0" checked="checked" />
                                                        <span>No</span>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>
                                                <div class="tuiyoTableRow" style="margin: 8px 0;">
                                                    <div class="tuiyoTableCell" style="width: 30%;">Enable Services?</div>
                                                    <div class="tuiyoTableCell" style="width: 70%;">
                                                        <input type="radio" name="params[siteEnableServices]"  id="paramssiteEnableServices" value="1" checked="checked" />
                                                        <span>Yes</span>
                                                        <input type="radio" name="params[siteEnableServices]"  id="paramssiteEnableServices" value="0" class="TuiyoFormRadio" />
                                                        <span>No</span>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>									
                                                <div class="tuiyoTableRow">
                                                    <div class="tuiyoTableCell" style="width: 30%;">Status length</div>
                                                    <div class="tuiyoTableCell" style="width: 70%;">
                                                        <input type="text" name="params[siteUpdateMaxLength]"  id="paramssiteUpdateMaxLength" style="width: 95%;" 
                                                              value="<?php echo $e->get('siteUpdateMaxLength') ?>" class="TuiyoFormText" title="<?php echo _('Max status update char length') ?>" />
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>
                                                <div class="tuiyoTableRow">
                                                    <div class="tuiyoTableCell" style="width: 30%;">Storage Per user</div>
                                                    <div class="tuiyoTableCell" style="width: 70%;">
                                                        <input type="text" name="params[siteUploadMaxQuota]"  id="paramssiteUploadMaxQuota" style="width: 95%;" 
                                                              value="<?php echo $e->get('siteUploadMaxQuota') ?>" class="TuiyoFormText" title="<?php echo _('Max file upload quota') ?>" />
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div> 
                                                <div class="tuiyoTableRow">
                                                    <div class="tuiyoTableCell" style="width: 30%;">Facebook API key</div>
                                                    <div class="tuiyoTableCell" style="width: 70%;">
                                                        <input type="text" name="params[siteFBKey]"  id="paramssiteFBKey" style="width: 95%;" 
                                                              value="<?php echo $e->get('siteFBKey') ?>" class="TuiyoFormText" title="<?php echo _('The facebook API Key') ?>" />
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>   
                                                <div class="tuiyoTableRow">
                                                    <div class="tuiyoTableCell" style="width: 30%;">Facebook API Secret</div>
                                                    <div class="tuiyoTableCell" style="width: 70%;">
                                                        <input type="text" name="params[siteFBSecret]"  id="paramssiteFBSecret" style="width: 95%;" 
                                                              value="<?php echo $e->get('siteFBSecret') ?>" class="TuiyoFormText" title="<?php echo _('The facebook API Secret') ?>" />
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>  
                                                <div class="tuiyoTableRow" style="margin: 8px 0;">
                                                    <div class="tuiyoTableCell" style="width: 30%;">Enable gravatars?</div>
                                                    <div class="tuiyoTableCell" style="width: 70%;">
                                                        <input type="radio" name="params[siteEnableGravatars]"  id="paramssiteEG1" value="1" checked="checked"/>
                                                        <span>Yes</span>
                                                        <input type="radio" name="params[siteEnableGravatars]"  id="paramssiteEG2" value="0" class="TuiyoFormRadio" />
                                                        <span>No</span>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div> 
                                                <div class="tuiyoTableRow" style="margin: 8px 0;">
                                                    <div class="tuiyoTableCell" style="width: 30%;">Site Gravatar Max Rating?</div>
                                                    <div class="tuiyoTableCell" style="width: 70%;">
                                                        <input type="radio" name="params[siteGravatarMaxRating]"  id="paramssiteMR1" value="G" class="TuiyoFormRadio"/>
                                                        <span>G</span>
                                                        <input type="radio" name="params[siteGravatarMaxRating]"  id="paramssiteMR2" value="PG" class="TuiyoFormRadio" />
                                                        <span>PG</span>
                                                        <input type="radio" name="params[siteGravatarMaxRating]"  id="paramssiteMR3" value="R" class="TuiyoFormRadio"/>
                                                        <span>R</span>
                                                        <input type="radio" name="params[siteGravatarMaxRating]"  id="paramssiteMR4" value="X" checked="checked"  />
                                                        <span>X</span>                                                        
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>                                                 
                                                <div class="tuiyoTableRow" style="margin: 8px 0;">
                                                    <div class="tuiyoTableCell" style="width: 30%;">Enable template overides?</div>
                                                    <div class="tuiyoTableCell" style="width: 70%;">
                                                        <input type="radio" name="params[siteEnabledStyles]"  id="paramssiteEnabledStyles1" value="1" checked="checked"/>
                                                        <span>Yes</span>
                                                        <input type="radio" name="params[siteEnabledStyles]"  id="paramssiteEnabledStyles2" value="0" class="TuiyoFormRadio" />
                                                        <span>No</span>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>                                                
                                                </div>
                                                <div class="tuiyoTableRow">
                                                    <div class="tuiyoTableCell" style="width: 30%;">File Upload Max Size</div>
                                                    <div class="tuiyoTableCell" style="width: 70%;">
                                                        <input type="text" name="params[siteMaxFileSize]"  id="paramssiteMaxFileSize" style="width: 95%;" 
                                                              value="<?php echo $e->get('siteMaxFileSize') ?>" class="TuiyoFormText" title="<?php echo _('The maximum file upload size allowed') ?>" />
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div> 
                                                <div class="tuiyoTableRow">
                                                    <div class="tuiyoTableCell" style="width: 30%;">Banned upload file formats</div>
                                                    <div class="tuiyoTableCell" style="width: 70%;">
                                                        <textarea name="params[siteBannedFileFormat]"  id="paramssiteBannedFileFormat" style="width: 95%;" rows="3"
                                                               class="TuiyoFormTextArea"><?php echo $e->get('siteBannedFileFormat') ?></textarea>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div> 
                                                <div class="tuiyoTableRow" style="margin: 8px 0">
                                                    <div class="tuiyoTableCell" style="width: 30%;">&nbsp;</div>
                                                    <div class="tuiyoTableCell" style="width: 70%;">
                                                        <button type="submit" class="button">Update global configuration</button>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>
                                                <?php echo JHTML::_('form.token') ?>
                                                <input type="hidden" name="option" value="com_tuiyo" />
                                                <input type="hidden" name="context" value="systemTools" />
                                                <input type="hidden" name="do" value="saveConfig" />
                                                <input type="hidden" name="configType" value="system" />
                                                <input type="hidden" name="configKey" value="global" />
                                            </form>
                                    	</div>
                                      	<div class="fcs childTab" style="display: none"> 
                                        	<form name="system.friends" id="system.friends" action="index.php" method="post" class="TuiyoForm">
                                                <div class="tuiyoTableRow">
                                                    <div class="tuiyoTableCell" style="width: 30%;">Default permission</div>
                                                    <div class="tuiyoTableCell" style="width: 70%;">
                                                        <select class="TuiyoFormDropDown" id="paramsfriendsDefaultPrivacy" name="params[friendssDefaultPrivacy]">
                                                            <option value="640">Only Me can</option>
                                                            <option value="630">Only My Friends and Me</option>
                                                            <option selected="selected" value="620">Any registered member</option>
                                                            <option value="610">Make publicly available</option>
                                                        </select>                                            
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div> 
                                                <div class="tuiyoTableRow" style="margin: 8px 0;">
                                                    <div class="tuiyoTableCell" style="width: 30%;">Allow Connections?</div>
                                                    <div class="tuiyoTableCell" style="width: 70%;">
                                                        <input type="radio" name="params[friendsAllowConnections]"  id="paramsfriendsAllowConnections" value="1" checked="checked" />
                                                        <span>Yes</span>
                                                        <input type="radio" name="params[friendsAllowConnections]"  id="paramsfriendsAllowConnections" value="0" class="TuiyoFormRadio" />
                                                        <span>No</span>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>
                                                <div class="tuiyoTableRow" style="margin: 8px 0">
                                                    <div class="tuiyoTableCell" style="width: 30%;">&nbsp;</div>
                                                    <div class="tuiyoTableCell" style="width: 70%;">
                                                        <button type="submit" class="button">Update settings</button>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>                                                     
                                                <?php echo JHTML::_('form.token') ?>
                                                <input type="hidden" name="option" value="com_tuiyo" />
                                                <input type="hidden" name="context" value="systemTools" />
                                                <input type="hidden" name="do" value="saveConfig" />
                                                <input type="hidden" name="configType" value="system" />
                                                <input type="hidden" name="configKey" value="friends" />
                                            </form> 
                                         </div>
                                         <div class="gs childTab"  style="display: none">
                                         	<form name="system.groups" id="system.groups" action="index.php" method="post" class="TuiyoForm">
                                                <div class="tuiyoTableRow" style="margin: 8px 0;">
                                                    <div class="tuiyoTableCell" style="width: 30%;">Approve new groups?</div>
                                                    <div class="tuiyoTableCell" style="width: 70%;">
                                                        <input type="radio" name="params[groupsRequireAppr]"  id="paramsgroupsRequireAppr" value="1" checked="checked" />
                                                        <span>Yes</span>
                                                        <input type="radio" name="params[groupsRequireAppr]"  id="paramsgroupsRequireAppr" value="0" class="TuiyoFormRadio" />
                                                        <span>No</span>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>
                                                <div class="tuiyoTableRow" style="margin: 8px 0;">
                                                    <div class="tuiyoTableCell" style="width: 30%;">Admin Auto Join?</div>
                                                    <div class="tuiyoTableCell" style="width: 70%;">
                                                        <input type="radio" name="params[groupsAdminAutoJoin]"  id="paramsgroupsAdminAutoJoin" value="1" checked="checked" />
                                                        <span>Yes</span>
                                                        <input type="radio" name="params[groupsAdminAutoJoin]"  id="paramsgroupsAdminAutoJoin" value="0" class="TuiyoFormRadio" />
                                                        <span>No</span>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>                                        
                                                <div class="tuiyoTableRow" style="margin: 8px 0">
                                                    <div class="tuiyoTableCell" style="width: 30%;">&nbsp;</div>
                                                    <div class="tuiyoTableCell" style="width: 70%;">
                                                        <button type="submit" class="button">Update settings</button>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>                                      
                                                <?php echo JHTML::_('form.token') ?>
                                                <input type="hidden" name="option" value="com_tuiyo" />
                                                <input type="hidden" name="context" value="systemTools" />
                                                <input type="hidden" name="do" value="saveConfig" />
                                                <input type="hidden" name="configType" value="system" />
                                                <input type="hidden" name="configKey" value="groups" />
                                            </form> 
                                        </div>
                                        <div class="ps childTab"  style="display: none">
                                        	<form name="system.photos" id="system.photos" action="index.php" method="post" class="TuiyoForm">
                                                <div class="tuiyoTableRow">
                                                    <div class="tuiyoTableCell" style="width: 30%;">Default View permission</div>
                                                    <div class="tuiyoTableCell" style="width: 70%;">
                                                        <select class="TuiyoFormDropDown" id="paramsphotosDefaultPrivacy" name="params[photosDefaultPrivacy]">
                                                            <option value="640">Only Me can</option>
                                                            <option value="630">Only My Friends and Me</option>
                                                            <option selected="selected" value="620">Any registered member</option>
                                                            <option value="610">Make publicly available</option>
                                                        </select>                                            
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div> 
                                                <div class="tuiyoTableRow" style="margin: 8px 0;">
                                                    <div class="tuiyoTableCell" style="width: 30%;">Allow Public view?</div>
                                                    <div class="tuiyoTableCell" style="width: 70%;">
                                                        <input type="radio" name="params[photosAllowPublicView]"  id="photosAllowPublicView" value="1" checked="checked" />
                                                        <span>Yes</span>
                                                        <input type="radio" name="params[photosAllowPublicView]"  id="photosAllowPublicView" value="0" class="TuiyoFormRadio" />
                                                        <span>No</span>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>
                                                <div class="tuiyoTableRow" style="margin: 8px 0">
                                                    <div class="tuiyoTableCell" style="width: 30%;">&nbsp;</div>
                                                    <div class="tuiyoTableCell" style="width: 70%;">
                                                        <button type="submit" class="button">Update Photos Settings</button>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>                                        	                                     
                                                <?php echo JHTML::_('form.token') ?>
                                                <input type="hidden" name="option" value="com_tuiyo" />
                                                <input type="hidden" name="context" value="systemTools" />
                                                <input type="hidden" name="do" value="saveConfig" />
                                                <input type="hidden" name="configType" value="system" />
                                                <input type="hidden" name="configKey" value="photos" />
                                            </form>
                                        </div>
                                    	<div class="cs childTab"  style="display: none">
                                            <form name="system.global" id="system.events" action="index.php" method="post" class="TuiyoForm">
                                            	<?php echo JHTML::_('form.token') ?>
                                                <input type="hidden" name="option" value="com_tuiyo" />
                                                <input type="hidden" name="context" value="systemTools" />
                                                <input type="hidden" name="do" value="saveConfig" />
                                                <input type="hidden" name="configType" value="system" />
                                                <input type="hidden" name="configKey" value="events" />
                                            </form> 
                                        </div>
                                       	<div class="ms childTab"  style="display: none">
                                           	<form name="system.messages" id="system.messasges" action="index.php" method="post" class="TuiyoForm">
                                             
                                                <?php echo JHTML::_('form.token') ?>
                                                <input type="hidden" name="option" value="com_tuiyo" />
                                                <input type="hidden" name="context" value="systemTools" />
                                                <input type="hidden" name="do" value="saveConfig" />
                                                <input type="hidden" name="configType" value="system" />
                                                <input type="hidden" name="configKey" value="messages" />
                                            </form>
                                       	</div>
                                    </div>                                     
                                </div>
                            </div>                                                                        
                        </div>
                        <div class="tuiyoTableCell" style="width: 34%;">
                            <div class="dashBoardWidget" style="border: 1px solid #DDDDDD">
                                <div class="dashBoardSideBarWidgetHeader">
                                    <div  class="collapser" ><a href="#">&nbsp;</a></div> Photos View settings
                                </div>
                                <div class="dashBoardWidgetBody tuiyoTable" style="padding: 8px 8px 8px 10px;">
                                      
                                </div>
                            </div>                                                                        

                       	</div>                                                                                              
                    	<div class="tuiyoClearFloat"></div>
                    </div>
                </div>
            </div>
       </div>
    </div>
</div>