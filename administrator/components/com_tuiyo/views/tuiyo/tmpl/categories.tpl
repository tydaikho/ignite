<?php  defined('TUIYO_EXECUTE' ) || die; ?>
<div id="w1" class="windowWrapperShadow">
    <div class="windowWrapper">
        <div class="window" style="background: #fff">
            <div class="windowTitleBar">
                <img src="<?php echo $iconPath ?>/icons/category.png" alt="hpact16" style="cursor: pointer" />
                <strong><?php echo _('Global System Categories'); ?></strong>
            </div>
            <div class="windowBody" style="padding: 18px; margin-top: 5px;">
            	<div class="tuiyoTable">
                	<div class="tuiyoTableRow">
                    	<div class="tuiyoTableCell" style="width: 100%;">
                            <div class="dashBoardWidget">
                            <div class="dashBoardWidgetBody">
                                <div id="adminPagePublisherTabs">
                                    <ul class="publisherTabItems">
                                    	<li id="viewCatNew" style="padding: 0 20px"><a href="#"><span>Add new category</span></a></li>
                                        <li id="viewCatDirs" class="current" style="padding: 0 20px"><a href="#"><span>Categories</span></a></li>
                                        <li id="viewCatAttr" style="padding: 0 20px"><a href="#"><span>Attributes</span></a></li>  
                                        <li id="viewCatExplorer" style="padding: 0 20px"><a href="#"><span>Explorer</span></a></li>
                                    </ul>
                                    <div class="tuiyoClearFloat"></div>
                                </div>
                                <div id="adminPageTabContent" >
                                	<div class="viewCatNew pageEl tuiyoTable" style="display: none">
                                		
                                		<!-- Create a new  form-->
                                		<form name="configureStatistics" id="configureStatistics" method="post" class="TuiyoForm" style="width: 55%" action="index2.php" >
                                		<div class="tuiyoTableRow">
                                        	<div class="tuiyoTableCell" style="width: 35%">Name</div>
                                            <div class="tuiyoTableCell" style="width: 65%">
                                            	<input type="text" name="cattitle" id="cattitle" class="TuiyoFormText" />
                                            	<i>Short descriptive name for your category</i>
                                            </div><div class="tuiyoClearFloat"></div>
                                        </div>
                                		<div class="tuiyoTableRow">
                                        	<div class="tuiyoTableCell" style="width: 35%">Slug</div>
                                            <div class="tuiyoTableCell" style="width: 65%">
                                            	<input type="text" name="catslug" id="catslug" class="TuiyoFormText" />
                                            	<i>Short alias for your category, used in url and search</i>
                                            </div><div class="tuiyoClearFloat"></div>
                                        </div>
                                        <div class="tuiyoTableRow">
                                        	<div class="tuiyoTableCell" style="width: 35%">Status</div>
                                            <div class="tuiyoTableCell" style="width: 65%">
                                            	<select name="catstatus" id="catstatus" class="TuiyoFormDropDown" >
                                            		<option value="0">Not-publish  </option>
                                            		<option value="1">Publish </option>
                                            	</select><br />
                                            	<i>Publish on submit</i>
                                            </div><div class="tuiyoClearFloat"></div>
                                        </div>
                                		<div class="tuiyoTableRow">
                                        	<div class="tuiyoTableCell" style="width: 35%">Parent</div>
                                            <div class="tuiyoTableCell" style="width: 65%">
                                            	<select name="catpid" id="catpid" class="TuiyoFormDropDown" >
                                            		<option value="0">No parent</option>
                                            		<?php displayNodeSelectOptions( $nodes )?>
                                            	</select><br />
                                            	<i>Parent for the current category item</i>
                                            </div><div class="tuiyoClearFloat"></div>
                                        </div>
                                        <div class="tuiyoTableRow">
                                        	<div class="tuiyoTableCell" style="width: 35%">Description</div>
                                            <div class="tuiyoTableCell" style="width: 65%">
                                            	<textarea  name="catdescription" id="catdescription" class="TuiyoFormTextArea"></textarea>
                                            	<i>Describe your category in one line</i>
                                            </div><div class="tuiyoClearFloat"></div>
                                        </div>
                                        <div class="tuiyoTableRow">
                                        	<div class="tuiyoTableCell" style="width: 35%">Permission</div>
                                            <div class="tuiyoTableCell" style="width: 65%">
                                            	<?php echo $arogrps ; ?><br />
                                            	<i>What are the minimal user permission required</i>
                                            </div><div class="tuiyoClearFloat"></div>
                                        </div>
                                                                                
                                		<div class="tuiyoTableRow">
                                        	<div class="tuiyoTableCell" style="width: 35%">&nbsp;</div>
                                            <div class="tuiyoTableCell" style="width: 65%">
                                            	<button type="submit" id="gasubmit" value="gasubmit">Save categories</button>
                                            </div><div class="tuiyoClearFloat"></div>
                                        </div>
                                  		<?php echo JHTML::_('form.token') ?>
                                        <input type="hidden" name="option" value="com_tuiyo" />
                                        <input type="hidden" name="context" value="tuiyo" />
                                        <input type="hidden" name="do" value="addNewCategory" />
                                        <input type="hidden" name="a" value="" />
                                    </form>
                                		<!-- end of new category form -->
									</div>
                                	<div class="viewCatDirs pageEl tuiyoTable">
                                		<div style="padding: 5px 0pt;" class="tuiyoTableHeaderRow">
                                        	<div style="width: 4%;" class="tuiyoTableCell">&nbsp;</div> <!---//user id -->
                                       		<div style="width: 40%;" class="tuiyoTableCell">Name</div> <!---//user name -->
                                      		<div style="width: 10%;" class="tuiyoTableCell">Slug</div> <!---//user email -->
                           					<div style="width: 25%;" class="tuiyoTableCell">Description</div> <!---//permission level -->
                                          	<div style="width: 5%;" class="tuiyoTableCell" align="center">Status</div> <!---//Edit -->
                                         	<div style="width: 5%;" class="tuiyoTableCell" align="center">ID</div> <!---//delete -->
                                        	<div style="width: 5%;" class="tuiyoTableCell" align="center">Creator</div> <!---//Active -->
                                        	<div style="width: 6%;" class="tuiyoTableCell">&nbsp;</div>
                                        	<div class="tuiyoClearFloat"></div>
                                    	</div> 
                                    	<!-- Lists Items -->
										<?php displayNodeRow( $nodes ) ?>
	                                </div> 
	                                <div class="viewCatAttr pageEl tuiyoTable" style="display: none">
                                		<div style="padding: 5px 0pt;" class="tuiyoTableHeaderRow">
                                        	<div style="width: 4%;" class="tuiyoTableCell">&nbsp;</div> <!---//user id -->
                                       		<div style="width: 40%;" class="tuiyoTableCell">Name</div> <!---//user name -->
                                      		<div style="width: 10%;" class="tuiyoTableCell">Slug</div> <!---//user email -->
                                      		<div style="width: 10%;" class="tuiyoTableCell">Permission</div>
                                          	<div style="width: 6%;" class="tuiyoTableCell" align="center">Profile</div> <!---//Edit -->
                                         	<div style="width: 6%;" class="tuiyoTableCell" align="center">Groups</div> <!---//delete -->
                                        	<div style="width: 6%;" class="tuiyoTableCell" align="center">Photos</div> <!--//Active -->
                                        	<div style="width: 6%;" class="tuiyoTableCell" align="center">Events</div> <!---//Active -->
                                        	<div style="width: 6%;" class="tuiyoTableCell" align="center">Files</div> <!---//Active -->
                                        	<div style="width: 6%;" class="tuiyoTableCell" align="center">Articles</div> <!---//Active -->
                              
                                        	<div class="tuiyoClearFloat"></div>
                                    	</div> 
                                    	<!-- Lists Items -->
										<?php displayAttributeNodeRow( $nodes, 0 ) ?>
	                                </div>   	                                                                	
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

<?php 

function displayNodeRow( $nodes, $index = 0 ){
	
	foreach($nodes as $node) :?>
													
       	<div style="padding: 5px 0pt;" class="tuiyoTableRow fieldListItem">
      		<div style="width: 4%;" class="tuiyoTableCell clickToMove iText">&nbsp;</div> <!---//user id -->
            <div style="width: 40%;" class="tuiyoTableCell">
            	<?php for($i=0; $i<$index+1; $i++) :?><a class="indenter">a</a><a href="#"><?php endfor; echo $node['title']; ?></a> 
            </div> <!---//user name -->
        	<div style="width: 10%;" class="tuiyoTableCell"><a href="#"><?php echo $node['slug']?></a></div> <!---//user email -->
          	<div style="width: 25%; overflow: hidden" class="tuiyoTableCell"><nobr><?php echo $node['description']?></nobr></div> <!---//permission level -->
      		<div style="width: 5%;" class="tuiyoTableCell iText <?php if((int)$node["status"] > 0):?> tick <?php else:?> notick <?php endif; ?>" align="center">&nbsp;</div> <!---//Edit -->
         	<div style="width: 5%;" class="tuiyoTableCell" align="center"><?php echo $node['id']?></div> <!---//delete -->
          	<div style="width: 5%;" class="tuiyoTableCell" align="center"><?php echo $node['creator']?></div> <!---//Active -->
         	<div style="width: 6%;" class="tuiyoTableCell clickToRemove iText">&nbsp;</div>
         	<div class="tuiyoClearFloat"></div>
        </div>
        
        <?php if(count((array)$node['children']) > 0) displayNodeRow($node['children'], $index+1)?>
         
	<?php endforeach;	
}

function displayAttributeNodeRow( $nodes, $index = 0 ){
	
	foreach($nodes as $node) :?>
													
       	<div style="padding: 5px 0pt;" class="tuiyoTableRow fieldListItem">
      		<div style="width: 4%;" class="tuiyoTableCell clickToMove iText">&nbsp;</div> <!---//user id -->
            <div style="width: 40%;" class="tuiyoTableCell">
            	<?php for($i=0; $i<$index+1; $i++) :?><a class="indenter">a</a><a href="#"><?php endfor; echo $node['title']; ?></a> 
            </div> <!---//user name -->
        	<div style="width: 10%;" class="tuiyoTableCell"><a href="#"><?php echo $node['slug']?></a></div> <!---//user email -->
			<div style="width: 10%;" class="tuiyoTableCell">Permission</div>
			<div style="width: 6%;" class="tuiyoTableCell" align="center"><input type="checkbox" /></div> <!---//Edit -->
        	<div style="width: 6%;" class="tuiyoTableCell" align="center"><input type="checkbox" /></div> <!---//delete -->
           	<div style="width: 6%;" class="tuiyoTableCell" align="center"><input type="checkbox" /></div> <!--//Active -->
            <div style="width: 6%;" class="tuiyoTableCell" align="center"><input type="checkbox" /></div> <!---//Active -->
        	<div style="width: 6%;" class="tuiyoTableCell" align="center"><input type="checkbox" /></div> <!---//Active -->
           	<div style="width: 6%;" class="tuiyoTableCell" align="center"><input type="checkbox" /></div> <!---//Active -->
           	
         	<div class="tuiyoClearFloat"></div>
        </div>
        
        <?php if(count((array)$node['children']) > 0) displayAttributeNodeRow($node['children'], $index+1)?>
         
	<?php endforeach;	
}

function displayNodeSelectOptions( $nodes , $index=0){
		foreach($nodes as $node) :?>													
       	<option value="<?php echo $node["id"] ?>" style="padding-left:<?php echo (($index)*20) ?>px"><?php echo $node['title']; ?> </option>        
        <?php if(count((array)$node['children']) > 0) displayNodeSelectOptions($node['children'], $index+1);
        endforeach;
}