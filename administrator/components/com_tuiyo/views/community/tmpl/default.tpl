<?php  defined('TUIYO_EXECUTE' ) || die; ?>
<div id="w1" class="windowWrapperShadow">
    <div class="windowWrapper">
        <div class="window" style="background: #fff">
            <div class="windowTitleBar">
                <img src="<?php echo $iconPath ?>/icons/users_16.png" style="cursor: pointer" />
                <strong>Community Management</strong>
            </div>
            <div class="windowBody" style="padding: 18px; margin-top: 5px;">
            	
                <div class="tuiyoTable">
                	<div class="tuiyoTableRow">
                    	<div class="tuiyoTableCell" style="width: 66%;" >
                            <div class="dashBoardWidget">
                                <div class="dashBoardWidgetBody" id="cmtyAdminPage" >
                                    <?php echo $data ?>
                                </div>
                            </div>                                         
                        </div>
                        <div class="tuiyoTableCell" style="width: 34%;">
                        
                        	<!--Quick system Tools-->
                            <div class="dashBoardSideBarWidget">
                            	<div class="dashBoardWidgetHeader"><div  class="collapser" ><a href="#">&nbsp;</a></div>Create a new user</div>
                                <div class="dashBoardSideBarWidgetBody" style="padding: 10px">
                                	<div class="tuiyoTable" style="margin-top: 8px">                                       
                                        <form name="newUser" id="newUser" class="TuiyoForm" method="post" action="index.php">
                                            <div class="tuiyoTableRow">
                                            	<div class="tuiyoTableCell" style="width: 35%">Full Name</div>
                                                <div class="tuiyoTableCell" style="width: 65%">
                                                	<input type="text" name="name" id="name" class="TuiyoFormText" />
                                                </div>
                                                <div class="tuiyoClearFloat"></div>
                                            </div> 
                                            <div class="tuiyoTableRow">
                                            	<div class="tuiyoTableCell" style="width: 35%">UserName</div>
                                                <div class="tuiyoTableCell" style="width: 65%">
                                                	<input type="text" name="username" id="username" class="TuiyoFormText" />
                                                </div>
                                                <div class="tuiyoClearFloat"></div>
                                            </div>   
                                            <div class="tuiyoTableRow">
                                            	<div class="tuiyoTableCell" style="width: 35%">Password</div>
                                                <div class="tuiyoTableCell" style="width: 65%">
                                                	<input type="password" name="password" id="password" class="TuiyoFormText" />
                                                </div>
                                                <div class="tuiyoClearFloat"></div>
                                            </div>  
                                            <div class="tuiyoTableRow">
                                            	<div class="tuiyoTableCell" style="width: 35%">Verify Password</div>
                                                <div class="tuiyoTableCell" style="width: 65%">
                                                	<input type="password" name="password2" id="password2" class="TuiyoFormText" />
                                                </div>
                                                <div class="tuiyoClearFloat"></div>
                                            </div>
                                            <div class="tuiyoTableRow">
                                            	<div class="tuiyoTableCell" style="width: 35%">Password</div>
                                                <div class="tuiyoTableCell" style="width: 65%">
                                                	<input type="text" name="email" id="email" class="TuiyoFormText" />
                                                </div>
                                                <div class="tuiyoClearFloat"></div>
                                            </div>
                                            <div class="tuiyoTableRow">
                                            	<div class="tuiyoTableCell" style="width: 35%">User Group</div>
                                                <div class="tuiyoTableCell" style="width: 65%">
                                                	<select  name="access" id="access" class="TuiyoFormDropDown" >
                                                   		<option value="0">Registered</option>
                                                        <option value="0">System Administrator</option>
                                                    </select>
                                                </div>
                                                <div class="tuiyoClearFloat"></div>
                                            </div> 
                                            <div class="tuiyoTableRow">
                                            	<div class="tuiyoTableCell" style="width: 35%">&nbsp;</div>
                                                <div class="tuiyoTableCell" style="width: 65%">
                                                	<button name="submitNewUser" type="submit">Create new user</button>
                                                </div>
                                                <div class="tuiyoClearFloat"></div>
                                            </div>  
                                            <?php echo JHTML::_('form.token'); ?>
                                            <input type="hidden" name="format" value="json" />
                                            <input type="hidden" name="context" value="communityManagement" />
                                            <input type="hidden" name="option" value="com_tuiyo" />
                                            <input type="hidden" name="do" value="createNewUser" />                                                                   
                                        </form>
                                    </div>
                                </div>
                       		</div> 
                            
                           <!--Quick system Tools-->
                            <div class="dashBoardSideBarWidget">
                            	<div class="dashBoardWidgetHeader"><div  class="collapser" ><a href="#">&nbsp;</a></div>User search</div>
                                <div class="dashBoardSideBarWidgetBody" style="padding: 10px">
                                	<div class="tuiyoTable" style="margin-top: 8px">                                       
                                        <form name="searchUser" id="searchUser" class="TuiyoForm" method="post" action="index.php">
                                            <div class="tuiyoTableRow">
                                            	<div class="tuiyoTableCell" style="width: 83%">
                                                	<input type="text" name="query" id="query" class="TuiyoFormText" />
                                                </div>
                                                <div class="tuiyoTableCell" style="width: 15%">
                                                	<button name="submitNewUser" type="submit">Search</button>
                                                </div>
                                                <div class="tuiyoClearFloat"></div>
                                            </div>                                                                     
                                        </form>
                                    </div>
                                </div>
                       		</div>
                            
                           <!--Quick system Tools-->
                            <div class="dashBoardSideBarWidget">
                            	<div class="dashBoardWidgetHeader"><div  class="collapser" ><a href="#">&nbsp;</a></div>User online now</div>
                                <div class="dashBoardSideBarWidgetBody" style="padding: 10px">
                                	<div class="tuiyoTable" style="margin-top: 8px">                                       
										There are no users online
                                    </div>
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