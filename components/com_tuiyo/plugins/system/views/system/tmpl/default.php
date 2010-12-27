<div class="tuiyoTable">
	<div class="tuiyoTableRow">
		<div class="tuiyoTableCell" style="width: 50%">
			<div class="tuiyoColumn" style="border-right: 1px solid #eee; position: relative">
				<div class="pagePublisherTabs">
	                <ul class="publisherTabItems" id="publisherHp1">	                    
	                  	<li style="padding: 0pt 20px;" class="current" id="timelineReload"><a href="#"><span><?php echo _('Public Room')?></span></a></li>
	                </ul>
	             	<div class="tuiyoClearFloat"></div>
	             </div>
				
				    <div id="TuiyoApiBarChatRoom" class="TuiyobarItemDiv">
				        <div class="boxTitle">
				        	<?php if($this->user->id <> (int)$this->chatroom['member']  ) : ?>
				        		<span>@<?php echo $GLOBALS['API']->get('user', (int)$this->chatroom['member']  )->username ?></span>
				            <?php else :?>
				            	<span>@<?php echo $GLOBALS['API']->get('user', (int)$this->chatroom['initiator']  )->username ?></span>
				            <?php endif; ?>
				            
				        </div>
				        <div class="boxChatContent">
				            <div class="tuiyoTable" style="width: 100%">
				                <div class="tuiyoTableRow">
				                    <div class="tuiyoTableCell chatWindow">
				                        <div class="tuiyoTable">
				                            <div class="tuiyoTableRow chatStream" id="chatArea"></div>
				                            <div style="position: absolute; bottom: 0; width:98%">
					                            <div class="tuiyoTableRow" style="width: 100%">
					                               <form action="<?php echo JRoute::_('index.php?format=json'); ?>" method="post" name="chatRoomForm" id="chatRoomForm" class="TuiyoForm" enctype="multipart/form-data">
												        <div class="homepagePublisherContainer">
												            <div class="tuiyoTable publisher">
												                <div class="tuiyoTableRow" style="padding-top: 2px" >            	    
												                    <div class="tuiyoTableCell" style="width: 87%" >
												                        <textarea name="ptext" id="ptext" class="ptextinput" style="margin-right: 4px; width: 99%"></textarea>
												                    </div>
												                    <div class="tuiyoTableCell" style="width: 9%" >
													            		<img alt="actTmpAt" width="50" height="50" src="avatar" class="TuiyoAvatar" style="margin-left: 8px;" />
													            	</div>               
												                    <div class="tuiyoClearFloat" ></div>
												                </div>               
												            </div>
															<div class="tuiyoTableRow" style="border-bottom: 1px solid #eee">
													        	<div class="tuiyoTableCell" style="width: 67%;">&nbsp;</div>
													            <div class="tuiyoTableCell" style="width: 20%" align="right">&nbsp;</div>
													            <div class="tuiyoTableCell" style="width: 13%; margin: 3px 0" align="right">
													            	<button type="submit"><?php echo _('Post')?></button>
													            </div>
													            <div class="tuiyoClearFloat" ></div>          
													        </div> 
												        </div>
					                            		<?php echo JHTML::_('form.token') ; ?>
													    <input type="hidden" name="format" value="json" />
													    <input type="hidden" name="option" value="com_tuiyo" />
													    <input type="hidden" name="controller" value="chat" />
													    <input type="hidden" name="do" value="postMessage" />
													    <input type="hidden" name="chatRoom" value="<?php echo $this->chatroom['roomID'] ?>" />
													    <input type="hidden" name="status" value="0<?php //echo $this->chatroom['status'] ?>" />
													    <input type="hidden" name="nickname" value="@<?php echo $this->user->username ?>" />    
													    <input type="hidden" name="userID" value="<?php echo (int)$this->user->id ?>" />     
													</form>
					                            </div>
				                            </div>
				                        </div>
				                    </div>
				                    <div class="tuiyoClearFloat"></div>
				                </div>
				            </div>
				        </div>
				    </div>
				
				<form  action="<?php echo JRoute::_('index.php?format=json'); ?>" method="post" name="chatRoomFormUpdater" id="chatRoomFormUpdater" >
				   <?php echo JHTML::_('form.token') ; ?>
				    <input type="hidden" name="format" value="json" />
				    <input type="hidden" name="option" value="com_tuiyo" />
				    <input type="hidden" name="controller" value="chat" />
				    <input type="hidden" name="do" value="autoUpdateChatRoom" />
				    <input type="hidden" name="chatRoom" value="<?php echo $this->chatroom['roomID'] ?>" />
				    <input type="hidden" name="status" id="updaterStatus" value="0" />
				    <input type="hidden" name="nickname" value="@<?php echo $this->user->username ?>" />    
				    <input type="hidden" name="userID" value="<?php echo (int)$this->user->id ?>" />    
				</form>	             
	             
			</div>
		</div>
		<div class="tuiyoTableCell" style="width: 50%">
			<div class="tuiyoColumn">
				<div class="pagePublisherTabs">
	                <ul class="publisherTabItems" id="publisherHp1">	                    
	                  	<li style="padding: 0pt 20px; margin-left: -5px"><a href="#"><span><?php echo _('Public Room')?></span></a></li>
	                </ul>
	                <div class="tuiyoClearFloat"></div>
                </div>				
			</div>
		</div>
		<div style="clear:both"></div>
	</div>
</div>
