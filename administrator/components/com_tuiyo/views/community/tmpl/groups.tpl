<?php  defined('TUIYO_EXECUTE' ) || die; ?>
<div id="w1" class="windowWrapperShadow">
    <div class="windowWrapper" id="systemConfig">
        <div class="window" style="background: #fff">
            <div class="windowTitleBar">
                <img src="<?php echo $iconPath ?>/icons/groups_16.png" alt="hpds16" style="cursor: pointer" />
                <strong>Community Management &bull; User Groups</strong>
            </div>
            <div class="windowBody" style="padding: 18px; margin-top: 5px;">
            	<div class="tuiyoTable">
                	<div class="tuiyoTableRow">
                    	<div class="tuiyoTableCell" style="width: 66%;">
                            <div class="dashBoardWidget">
                            <div class="dashBoardWidgetBody">
                                <div id="adminPagePublisherTabs">
                                    <ul class="publisherTabItems">
                                        <li id="viewGroupDirs" style="padding: 0 20px"><a href="#"><span>Directory</span></a></li>
                                        <li id="viewGroupCats" style="padding: 0 20px" class="current"><a href="#"><span>Categories </span></a></li>
                                        <li id="viewGroupActivity" style="padding: 0 20px"><a href="#"><span>Timeline</span></a></li>  
                                        <li id="viewGroupReports" style="padding: 0 20px"><a href="#"><span>Moderation</span></a></li>
                                    </ul>
                                    <div class="tuiyoClearFloat"></div>
                                </div>
                                <div id="adminPageTabContent" > mainpage  </div>
                            </div>
                            </div>                                                                         
                        </div>
                        <div class="tuiyoTableCell" style="width: 34%;">
                        
                            <div class="dashBoardSideBarWidget">
                            	<div class="dashBoardWidgetHeader">Create new group Category or Sub Category</div>
                                <div class="dashBoardSideBarWidgetBody TuiyoTable" style="padding: 8px">
                                	<form name="newCategory" id="newCategory" method="post" class="TuiyoForm" action="index2.php" />
                                		<div class="tuiyoTableRow">
                                        	<div class="tuiyoTableCell" style="width: 35%">Category name</div>
                                            <div class="tuiyoTableCell" style="width: 65%">
                                            	<input type="text" name="catName" id="catName" class="TuiyoFormText" />
                                            </div><div class="tuiyoClearFloat"></div>
                                        </div>
                                		<div class="tuiyoTableRow">
                                        	<div class="tuiyoTableCell" style="width: 35%">Description</div>
                                            <div class="tuiyoTableCell" style="width: 65%">
                                            	<textarea name="catDescription" id="catDescription" class="TuiyoFormTextArea"></textarea>
                                            </div><div class="tuiyoClearFloat"></div>
                                        </div> 
                                		<div class="tuiyoTableRow">
                                        	<div class="tuiyoTableCell" style="width: 35%; padding-top: 11px;">Parent Category</div>
                                            <div class="tuiyoTableCell" style="width: 65%">
                                            	<select id="parentCat" class="TuiyoFormDropDown" style="width: 100%">
                                                	<option value="0">--Chose parent category--</option>
                                                </select>
                                            </div><div class="tuiyoClearFloat"></div>
                                        </div>   
                                		<div class="tuiyoTableRow" style="margin: 8px 0">
                                        	<div class="tuiyoTableCell" style="width: 35%">Enable Category?</div>
                                            <div class="tuiyoTableCell" style="width: 65%">
                                            	<input type="radio" name="enableCat" value="0" /> No
                                                <input type="radio" name="enableCat" value="1" checked="checked" /> Yes
                                            </div><div class="tuiyoClearFloat"></div>
                                        </div>   
                                        <div class="tuiyoTableRow">
                                        	<div class="tuiyoTableCell" style="width: 35%">&nbsp;</div>
                                            <div class="tuiyoTableCell" style="width: 65%">
                                                <button type="submit" id="createCat">Create category</button>
                                            </div><div class="tuiyoClearFloat"></div>
                                        </div>                                                                                                                  
                                  		<?php echo JHTML::_('form.token') ?>
                                        <input type="hidden" name="option" value="com_tuiyo" />
                                        <input type="hidden" name="context" value="communityManagement" />
                                        <input type="hidden" name="do" value="newGroupCategory" />
                                        <input type="hidden" name="a" value="" />
                                    </form> 
                                </div>
                       		</div> 
                            
                            <div class="dashBoardSideBarWidget">
                            	<div class="dashBoardWidgetHeader">Create new group</div>
                                <div class="dashBoardSideBarWidgetBody">
									<form name="newGroup" id="newGroup" method="post" class="TuiyoForm" action="index2.php" />
                                		<div class="tuiyoTableRow">
                                        	<div class="tuiyoTableCell" style="width: 35%">Group name</div>
                                            <div class="tuiyoTableCell" style="width: 65%">
                                            	<input type="text" name="groupName" id="groupName" class="TuiyoFormText" />
                                            </div><div class="tuiyoClearFloat"></div>
                                        </div>
                                		<div class="tuiyoTableRow">
                                        	<div class="tuiyoTableCell" style="width: 35%">Description (short)</div>
                                            <div class="tuiyoTableCell" style="width: 65%">
                                            	<textarea name="groupShortDescription" id="groupShortDescription" class="TuiyoFormTextArea"></textarea>
                                            </div><div class="tuiyoClearFloat"></div>
                                        </div> 
                                		<div class="tuiyoTableRow">
                                        	<div class="tuiyoTableCell" style="width: 35%">Description (long)</div>
                                            <div class="tuiyoTableCell" style="width: 65%">
                                            	<textarea name="groupLongDescription" id="groupLongDescription" class="TuiyoFormTextArea" rows="5"></textarea>
                                            </div><div class="tuiyoClearFloat"></div>
                                        </div>                                        
                                		<div class="tuiyoTableRow">
                                        	<div class="tuiyoTableCell" style="width: 35%; padding-top: 11px;">Group Category</div>
                                            <div class="tuiyoTableCell" style="width: 65%">
                                            	<select id="parentCat" class="TuiyoFormDropDown" style="width: 100%">
                                                	<option value="0">--Chose group category--</option>
                                                </select>
                                            </div><div class="tuiyoClearFloat"></div>
                                        </div>   
                                		<div class="tuiyoTableRow" style="margin: 8px 0">
                                        	<div class="tuiyoTableCell" style="width: 35%">Enable Group?</div>
                                            <div class="tuiyoTableCell" style="width: 65%">
                                            	<input type="radio" name="enableCat" value="0" /> No
                                                <input type="radio" name="enableCat" value="1" checked="checked" /> Yes
                                            </div><div class="tuiyoClearFloat"></div>
                                        </div> 
                                		<div class="tuiyoTableRow" style="margin: 8px 0">
                                        	<div class="tuiyoTableCell" style="width: 35%">Is group Public?</div>
                                            <div class="tuiyoTableCell" style="width: 65%">
                                            	<input type="radio" name="enableCat" value="0" /> No
                                                <input type="radio" name="enableCat" value="1" checked="checked" /> Yes
                                            </div><div class="tuiyoClearFloat"></div>
                                        </div>                                           
                                        <div class="tuiyoTableRow">
                                        	<div class="tuiyoTableCell" style="width: 35%">&nbsp;</div>
                                            <div class="tuiyoTableCell" style="width: 65%">
                                                <button type="submit" id="createCat">Create new Group</button>
                                            </div><div class="tuiyoClearFloat"></div>
                                        </div>                                                                                                                  
                                  		<?php echo JHTML::_('form.token') ?>
                                        <input type="hidden" name="option" value="com_tuiyo" />
                                        <input type="hidden" name="context" value="communityManagement" />
                                        <input type="hidden" name="do" value="newGroup" />
                                        <input type="hidden" name="a" value="" />
                                    </form>                                 
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