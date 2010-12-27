<?php  defined('TUIYO_EXECUTE' ) || die; ?>
<div id="w1" class="windowWrapperShadow">
    <div class="windowWrapper">
        <div class="window" style="background: #fff">
            <div class="windowTitleBar">
                <img src="<?php echo $iconPath ?>/icons/inbox_16.png" alt="hpemail16" style="cursor: pointer" />
                <strong>System Email alerts</strong>
            </div>
            <div class="windowBody" style="padding: 18px; margin-top: 5px;">
            	<div class="tuiyoTable">
                	<div class="tuiyoTableRow">
                    	<div class="tuiyoTableCell" style="width: 66%;">
                        	
                           <!--#start:welcome--->
                           <form name="systemEmailTemplates" id="systemEmailTemplates" action="index.php" method="post" class="TuiyoForm">
   								<div class="dashBoardWidget">
                                	<div class="dashBoardSideBarWidgetHeader">
                                    	<div  class="collapser" ><a href="#">&nbsp;</a></div> Welcome Email, sent to newly registered
                                    </div>
                                    <div class="dashBoardWidgetBody" style="padding: 8px 8px 8px 20px;">
                                        <div style="margin-top: 10px;">
                                            <div class="tuiyoTable">
                                                <div class="tuiyoTableRow">
                                                    <div class="tuiyoTableCell" style="width: 25%;">Message Title</div>
                                                    <div class="tuiyoTableCell" style="width: 75%;">
                                                        <input type="text" name="params[welcomeEmailTitle]"  id="paramswelcomeEmailTitle" style="width: 95%;" 
                                                              value="<?php echo $e->get('welcomeEmailTitle') ?>" class="TuiyoFormText" />
                                                        <br/><span class="formTip">Available Variables:[username],[name],[email],[message],[password],[link]</span>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>
                                                <div class="tuiyoTableRow">
                                                    <div class="tuiyoTableCell" style="width: 25%;">Message Body.</div>
                                                    <div class="tuiyoTableCell" style="width: 75%;">
                                                        <textarea name="params[welcomeEmailBody]"  id="paramswelcomeEmailBody" style="width: 95%" rows="5" class="TuiyoFormTextArea"><?php echo $e->get('welcomeEmailBody') ?></textarea>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


   								<div class="dashBoardWidget">
                                	<div class="dashBoardSideBarWidgetHeader">
                                    	<div  class="collapser" ><a href="#">&nbsp;</a></div> Invitation email
                                    </div>
                                    <div class="dashBoardWidgetBody" style="padding: 8px 8px 8px 20px;">
                                        <div style="margin-top: 10px;">
                                            <div class="tuiyoTable">
                                                <div class="tuiyoTableRow">
                                                    <div class="tuiyoTableCell" style="width: 25%;">Message Title</div>
                                                    <div class="tuiyoTableCell" style="width: 75%;">
                                                        <input type="text" name="params[inviteEmailTitle]"  id="paramsinviteEmailTitle" style="width: 95%;" 
                                                        	   value="<?php echo $e->get('inviteEmailTitle') ?>" class="TuiyoFormText" />
                                                        <br/><span class="formTip">Available Variables:[username],[name],[email],[message],[link]</span>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>
                                                <div class="tuiyoTableRow">
                                                    <div class="tuiyoTableCell" style="width: 25%;">Message Body.</div>
                                                    <div class="tuiyoTableCell" style="width: 75%;">
                                                        <textarea name="params[inviteEmailBody]"  id="paramsinviteEmailBody" style="width: 95%" rows="5" class="TuiyoFormTextArea"><?php echo $e->get('inviteEmailBody') ?></textarea>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

   								<div class="dashBoardWidget">
                                	<div class="dashBoardSideBarWidgetHeader">
                                    	<div  class="collapser" ><a href="#">&nbsp;</a></div> New user created group
                                    </div>
                                    <div class="dashBoardWidgetBody" style="padding: 8px 8px 8px 20px;">
                                        <div style="margin-top: 10px;">
                                            <div class="tuiyoTable">
                                                <div class="tuiyoTableRow">
                                                    <div class="tuiyoTableCell" style="width: 25%;">Message Title</div>
                                                    <div class="tuiyoTableCell" style="width: 75%;">
                                                        <input type="text" name="params[newUserGroupEmailTitle]"  id="paramsnewUserGroupEmailTitle" style="width: 95%;" 
                                                              value="<?php echo $e->get('newUserGroupEmailTitle') ?>" class="TuiyoFormText" />
                                                        <br/><span class="formTip">Available Variables:[thisuser],[thatuser],[email],[message],[type],[link]</span>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>
                                                <div class="tuiyoTableRow">
                                                    <div class="tuiyoTableCell" style="width: 25%;">Message Body.</div>
                                                    <div class="tuiyoTableCell" style="width: 75%;">
                                                        <textarea name="params[newUserGroupEmailBody]"  id="paramsnewUserGroupEmailBody" style="width: 95%" rows="5" class="TuiyoFormTextArea"><?php echo $e->get('newUserGroupEmailBody') ?></textarea>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

   								<div class="dashBoardWidget">
                                	<div class="dashBoardSideBarWidgetHeader">
                                    	<div  class="collapser" ><a href="#">&nbsp;</a></div> New Group member
                                    </div>
                                    <div class="dashBoardWidgetBody" style="padding: 8px 8px 8px 20px;">
                                        <div style="margin-top: 10px;">
                                            <div class="tuiyoTable">
                                                <div class="tuiyoTableRow">
                                                    <div class="tuiyoTableCell" style="width: 25%;">Message Title</div>
                                                    <div class="tuiyoTableCell" style="width: 75%;">
                                                        <input type="text" name="params[newGroupMemberEmailTitle]"  id="paramsnewGroupMemberEmailTitle" 
                                                              style="width: 95%;" value="<?php echo $e->get('newGroupMemberEmailTitle') ?>" class="TuiyoFormText" />
                                                        <br/><span class="formTip">Available Variables:[thisuser],[group],[email],[message],[type],[link]</span>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>
                                                <div class="tuiyoTableRow">
                                                    <div class="tuiyoTableCell" style="width: 25%;">Message Body.</div>
                                                    <div class="tuiyoTableCell" style="width: 75%;">
                                                        <textarea name="params[newGroupMemberEmailBody]"  id="paramsnewGroupMemberEmailBody" style="width: 95%" rows="5" class="TuiyoFormTextArea"><?php echo $e->get('newGroupMemberEmailBody') ?></textarea>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

   								<div class="dashBoardWidget">
                                	<div class="dashBoardSideBarWidgetHeader">
                                    	<div  class="collapser" ><a href="#">&nbsp;</a></div> New user report to moderator
                                    </div>
                                    <div class="dashBoardWidgetBody" style="padding: 8px 8px 8px 20px;">
                                        <div style="margin-top: 10px;">
                                            <div class="tuiyoTable">
                                                <div class="tuiyoTableRow">
                                                    <div class="tuiyoTableCell" style="width: 25%;">Message Title</div>
                                                    <div class="tuiyoTableCell" style="width: 75%;">
                                                        <input type="text" name="params[newUserReportEmailTitle]"  id="paramsnewUserReportE,ao;Title" style="width: 95%;" 
                                                              value="<?php echo $e->get('newUserReportEmailTitle') ?>" class="TuiyoFormText" />
                                                        <br/><span class="formTip">Available Variables:[thisuser],[resource],[email],[message],[type],[link]</span>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>
                                                <div class="tuiyoTableRow">
                                                    <div class="tuiyoTableCell" style="width: 25%;">Message Body.</div>
                                                    <div class="tuiyoTableCell" style="width: 75%;">
                                                        <textarea name="params[newUserReportEmailBody]"  id="paramsnewUserReportEmailBody" style="width: 95%" rows="5" class="TuiyoFormTextArea"><?php echo $e->get('newUserReportBody') ?></textarea>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

   								<div class="dashBoardWidget">
                                	<div class="dashBoardSideBarWidgetHeader">
                                    	<div  class="collapser" ><a href="#">&nbsp;</a></div> Friendship request email
                                    </div>
                                    <div class="dashBoardWidgetBody" style="padding: 8px 8px 8px 20px;">
                                        <div style="margin-top: 10px;">
                                            <div class="tuiyoTable">
                                                <div class="tuiyoTableRow">
                                                    <div class="tuiyoTableCell" style="width: 25%;">Message Title</div>
                                                    <div class="tuiyoTableCell" style="width: 75%;">
                                                        <input type="text" name="params[connectionRequestEmailTitle]"  id="paramsconnectionRequestEmailTitle" 
                                                        style="width: 95%;" value="<?php echo $e->get('connectionRequestEmailTitle') ?>" class="TuiyoFormText" />
                                                        <br/><span class="formTip">Available Variables:[thisuser],[thatuser],[email],[message],[type],[link]</span>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>
                                                <div class="tuiyoTableRow">
                                                    <div class="tuiyoTableCell" style="width: 25%;">Message Body.</div>
                                                    <div class="tuiyoTableCell" style="width: 75%;">
                                                        <textarea name="params[connectionRequestEmailBody]"  id="paramsconnectionRequestEmailBody" style="width: 95%" rows="5" class="TuiyoFormTextArea"><?php echo $e->get('connectionRequestEmailBody') ?></textarea>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

   								<div class="dashBoardWidget">
                                	<div class="dashBoardSideBarWidgetHeader">
                                    	<div  class="collapser" ><a href="#">&nbsp;</a></div> New profile ("wall") comment
                                    </div>
                                    <div class="dashBoardWidgetBody" style="padding: 8px 8px 8px 20px;">
                                        <div style="margin-top: 10px;">
                                            <div class="tuiyoTable">
                                                <div class="tuiyoTableRow">
                                                    <div class="tuiyoTableCell" style="width: 25%;">Message Title</div>
                                                    <div class="tuiyoTableCell" style="width: 75%;">
                                                        <input type="text" name="params[wallCommentEmailTitle]"  id="paramswallCommentEmailTitle" style="width: 95%;" value="<?php echo $e->get('wallCommentEmailTitle') ?>" class="TuiyoFormText" />
                                                        <br/><span class="formTip">Available Variables:[thisuser],[thatuser],[email],[message],[type],[link]</span>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>
                                                <div class="tuiyoTableRow">
                                                    <div class="tuiyoTableCell" style="width: 25%;">Message Body.</div>
                                                    <div class="tuiyoTableCell" style="width: 75%;">
                                                        <textarea name="params[wallCommentEmailBody]"  id="paramswallCommentEmailBody" style="width: 95%" rows="5" class="TuiyoFormTextArea"><?php echo $e->get('wallCommentEmailBody') ?></textarea>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

   								<div class="dashBoardWidget">
                                	<div class="dashBoardSideBarWidgetHeader">
                                    	<div  class="collapser" ><a href="#">&nbsp;</a></div> Miscellanoues Email
                                    </div>
                                    <div class="dashBoardWidgetBody" style="padding: 8px 8px 8px 20px;">
                                        <div style="margin-top: 10px;">
                                            <div class="tuiyoTable">
                                                <div class="tuiyoTableRow">
                                                    <div class="tuiyoTableCell" style="width: 25%;">Message Title</div>
                                                    <div class="tuiyoTableCell" style="width: 75%;">
                                                        <input type="text" name="params[newActionEmailTitle]"  id="paramsnewActionEmailTitle" style="width: 95%;" 
                                                              value="<?php echo $e->get('newActionEmailTitle') ?>" class="TuiyoFormText" />
                                                        <br/><span class="formTip">Available Variables:[thisuser],[moderator],[email],[message],[type],[link]</span>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>
                                                <div class="tuiyoTableRow">
                                                    <div class="tuiyoTableCell" style="width: 25%;">Message Body.</div>
                                                    <div class="tuiyoTableCell" style="width: 75%;">
                                                        <textarea name="params[newActionEmailBody]"  id="paramsnewActionEmailBody" style="width: 95%" rows="5" class="TuiyoFormTextArea"><?php echo $e->get('newActionEmailBody') ?></textarea>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
								<div class="dashBoardWidget">
                                	<div class="dashBoardSideBarWidgetHeader">
                                    	<div  class="collapser" ><a href="#">&nbsp;</a></div> New Notification Recieved
                                    </div>
                                    <div class="dashBoardWidgetBody" style="padding: 8px 8px 8px 20px;">
                                        <div style="margin-top: 10px;">
                                            <div class="tuiyoTable">
                                                <div class="tuiyoTableRow">
                                                    <div class="tuiyoTableCell" style="width: 25%;">Message Title</div>
                                                    <div class="tuiyoTableCell" style="width: 75%;">
                                                        <input type="text" name="params[newNotificationEmailTitle]"  id="paramsnewNotificationEmailTitle" 
                                                              style="width: 95%;" value="<?php echo $e->get('newNotificationEmailTitle') ?>" class="TuiyoFormText" />
                                                        <br/><span class="formTip">Available Variables:[thisuser],[thatuser],[email],[message],[type],[link]</span>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>
                                                <div class="tuiyoTableRow">
                                                    <div class="tuiyoTableCell" style="width: 25%;">Message Body.</div>
                                                    <div class="tuiyoTableCell" style="width: 75%;">
                                                        <textarea name="params[newNotificationEmailBody]"  id="paramsnewNotificationEmailBody" style="width: 95%" rows="5" class="TuiyoFormTextArea"><?php echo $e->get('newNotificationEmailBody') ?></textarea>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>
                                                <div class="tuiyoTableRow">
                                                    <div class="tuiyoTableCell" style="width: 25%;">&nbsp;</div>
                                                    <div class="tuiyoTableCell" style="width: 75%;">
                                                        <button type="submit" class="button">Submit</button>
                                                    </div>
                                                    <div class="tuiyoClearFloat"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php echo JHTML::_('form.token') ?>
                                <input type="hidden" name="option" value="com_tuiyo" />
                                <input type="hidden" name="context" value="systemTools" />
								<input type="hidden" name="do" value="saveConfig" />
                                <input type="hidden" name="configType" value="system" />
                                <input type="hidden" name="configKey" value="emails" />
                       		</form> 
                            <!--@end:newpms--->                                                                       
                                                                                                     
                        </div>
                        
                        
                        <div class="tuiyoTableCell" style="width: 34%;">
                        	<!--Quick system statistics-->
                            <div class="dashBoardSideBarWidget">
                            	<div class="dashBoardSideBarWidgetHeader"><div  class="collapser" ><a href="#">&nbsp;</a></div> Global system.email configuration</div>
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