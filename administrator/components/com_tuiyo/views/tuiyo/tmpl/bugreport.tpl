<?php  defined('TUIYO_EXECUTE' ) || die; ?>
<div id="w1" class="windowWrapperShadow">
    <div class="windowWrapper">
        <div class="window" style="background: #fff">
            <div class="windowTitleBar">
                <img src="<?php echo $iconPath ?>/icons/bug_16.png" alt="hpact16" style="cursor: pointer" />
                <strong>Report Bugs</strong>
            </div>
            <div class="windowBody" style="padding: 18px; margin-top: 5px;">
            	<div class="tuiyoTable">
                	<div class="tuiyoTableRow">
                    	<div class="tuiyoTableCell" style="width: 66%;">
                           <form name="bugReporting" id="bugReporting" action="index.php" method="post" class="TuiyoForm">
   								<div class="dashBoardWidget">
                                <div class="dashBoardWidgetBody" style="padding: 8px 8px 8px 20px;">
                                <div style="margin-top: 10px;">
                                    <div class="tuiyoTable">
                                        <div class="tuiyoTableRow">
                                            <div class="tuiyoTableCell" style="width: 25%;">Your Name</div>
                                            <div class="tuiyoTableCell" style="width: 75%;">
                                                <input type="text" name="username" id="username" style="width: 95%;" value="<?php echo $user->get('name')?>" class="TuiyoFormText" />
                                            </div>
                                            <div class="tuiyoClearFloat"></div>
                                            <div class="tuiyoTableCell" style="width: 25%;">Your company name</div>
                                            <div class="tuiyoTableCell" style="width: 75%;">
                                                <input type="text" name="companyName" id="companyName" style="width: 95%;" value="<?php echo $system['comunity_name']?>" 
                                                      class="TuiyoFormText" />
                                            </div>
                                            <div class="tuiyoClearFloat"></div>
                                            <div class="tuiyoTableCell" style="width: 25%;">Your contact number</div>
                                            <div class="tuiyoTableCell" style="width: 75%;">
                                                <input type="text" name="phoneNumber" id="phoneNumber" style="width: 95%;" value=""  class="TuiyoFormText"/>
                                            </div>
                                            <div class="tuiyoClearFloat"></div>        
                                            <div class="tuiyoTableCell" style="width: 25%;">Your contact email</div>
                                            <div class="tuiyoTableCell" style="width: 75%;">
                                                <input type="text" name="emailAddress" id="emailAddress" style="width: 95%;" value="<?php echo $user->get('email') ;?>"  
                                                      class="TuiyoFormText"/>
                                            </div>
                                            <div class="tuiyoClearFloat"></div>  
                                            <div class="tuiyoTableCell" style="width: 25%;"><b>Report title</b><em>*</em></div>
                                            <div class="tuiyoTableCell" style="width: 75%;">
                                                <input type="text" name="subject" id="subject" style="width: 95%;" value="" class="TuiyoFormText" />
                                            </div>
                                            <div class="tuiyoClearFloat"></div>
                                            
                                        </div>
                                        <div class="tuiyoTableRow">
                                            <div class="tuiyoTableCell" style="width: 25%;">Report summary</div>
                                            <div class="tuiyoTableCell" style="width: 75%;">
                                                <i>Keep it brief but include enough details</i>
                                            </div>
                                            <div class="tuiyoClearFloat"></div>
                                        
                                            <div class="tuiyoTableCell" style="width: 25%;">&nbsp;</div>
                                            <div class="tuiyoTableCell" style="width: 75%;">
                                                <textarea name="bugSummary" id="bugSummary" style="width: 95%" rows="5" class="TuiyoFormTextArea"></textarea>
                                            </div>
                                            <div class="tuiyoClearFloat"></div>
                                        </div>
                                         <div class="tuiyoTableRow">
                                            <div class="tuiyoTableCell" style="width: 25%;">Reporducintiy</div>
                                            <div class="tuiyoTableCell" style="width: 75%;">
                                                <i>Steps to reproduce this issue. This is important</i>
                                            </div>
                                            <div class="tuiyoClearFloat"></div>
                                        
                                            <div class="tuiyoTableCell" style="width: 25%;">&nbsp;</div>
                                            <div class="tuiyoTableCell" style="width: 75%;">
                                                <textarea name="bugRepoducibility" id="bugReproducibility" style="width: 95%" rows="5" class="TuiyoFormTextArea"></textarea>
                                            </div>
                                            <div class="tuiyoClearFloat"></div>
                                        </div>
                                        <div class="tuiyoTableRow">
                                            <div class="tuiyoTableCell" style="width: 25%;">Outcome</div>
                                            <div class="tuiyoTableCell" style="width: 75%;">
                                                <i>what is the outcome of the reproducibility steps above?</i>
                                            </div>
                                            <div class="tuiyoClearFloat"></div>
                                        
                                            <div class="tuiyoTableCell" style="width: 25%;">&nbsp;</div>
                                            <div class="tuiyoTableCell" style="width: 75%;">
                                                <textarea name="bugOutcome" id="bugOutcome" style="width: 95%" rows="5" class="TuiyoFormTextArea"></textarea>
                                            </div>
                                            <div class="tuiyoClearFloat"></div>
                                        </div>
                                        <div class="tuiyoTableRow">
                                            <div class="tuiyoTableCell" style="width: 25%;">Suggestions</div>
                                            <div class="tuiyoTableCell" style="width: 75%;">
                                                <i>Have you got any suggestions? we welcome your advice</i>
                                            </div>
                                            <div class="tuiyoClearFloat"></div>
                                        
                                            <div class="tuiyoTableCell" style="width: 25%;">&nbsp;</div>
                                            <div class="tuiyoTableCell" style="width: 75%;">
                                                <textarea name="bugSolutionSuggest" id="bugSolutionSuggest" style="width: 95%" rows="5" class="TuiyoFormTextArea"></textarea>
                                            </div>
                                            <div class="tuiyoClearFloat"></div>
                                        </div>
                                         <div class="tuiyoTableRow" style="margin-top: 5px">
                                            <div class="tuiyoTableCell" style="width: 25%;">Set a priority</div>
                                            <div class="tuiyoTableCell" style="width: 75%;">
                                                <select name="bug_priority" class="TuiyoFormDropDown">
                                                    <option value="Crucial">Very Crucial</option>
                                                    <option value="Important">This is important</option>
                                                    <option value="Annoying">Just an annoying issue</option>
                                                    <option value="Unimportant">Not urgent</option> 
                                              </select>
                                            </div>
                                            <div class="tuiyoClearFloat"></div>
                                            <div class="tuiyoTableCell" style="width: 25%;">Categoroy</div>
                                            <div class="tuiyoTableCell" style="width: 75%;">
                                                <select name="bug_type" class="TuiyoFormDropDown">
                                                    <option selected="selected" value="(1) Security">Security</option>
                                                    <option value="(2) Crash/Hang/Data Loss">Crash/Hang/Data Loss</option>
                                                    <option value="(3) Performance">Performance</option>
                                                    <option value="(4) UI/Usability">UI/Usability</option>
                                                    <option value="(5) Serious Bug">Serious Bug</option>
                                                    <option value="(6) Other Bug/Has Workaround">Other Bug/Has Workaround</option>
                                                    <option value="(7) Feature (New)">Feature (New)</option>
                                                    <option value="(8) Enhancement">Enhancement</option>
                                
                                              </select>
                                            </div>
                                            <div class="tuiyoClearFloat"></div>
                                        </div>
                                        <div class="tuiyoTableRow">
                                            <div class="tuiyoTableCell" style="width: 25%;"></div>
                                            <div class="tuiyoTableCell" style="width: 75%;">
                                                <button type="submit">Submit</button>
                                            </div>
                                            <div class="tuiyoClearFloat"></div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                </div>
                                <?php echo JHTML::_('form.token') ?>
                                <input type="hidden" name="option" value="com_tuiyo" />
                                <input type="hidden" name="cmd" value="submitbug" />
                       		</form>                                                                          
                        </div>
                        <div class="tuiyoTableCell" style="width: 34%;">
                        
                        	<!--Quick system statistics-->
                            <div class="dashBoardSideBarWidget">
                            	<div class="dashBoardSideBarWidgetHeader"><div  class="collapser" ><a href="#">&nbsp;</a></div> Already reported issues</div>
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