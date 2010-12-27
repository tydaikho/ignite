<?php  defined('TUIYO_EXECUTE' ) || die; ?>
<div class="homepageContainer" id="pageContainer">
	<div id="homepageTop" style="display: none">&nbsp;</div>
    <div id="homepageLeftnav">
    	 <ul id="TuiyoMenuLogo" style="margin-top: 30px;">
           	<li style="display: inline;" >
            	<a href="index.php?option=com_tuiyo"><img src="<?php echo $iconPath ?>/images/logo2.png"  /></a>
            </li>
        </ul>
         <ul id="homepageNavigation" style="margin-top: 10px; margin-left: 10px;">
            <li id="globalConfiguration" class="aTAHead current"><a>Site Managment Tools</a></li>
            	<li class="dTABody">
                	<ul id="userManagementTools"> 
                    	<li rel="<?php echo JRoute::_( TUIYO_INDEX.'&amp;context=systemTools&do=userFields' ); ?>"><a>System fields</a></li> 
                    	<li rel="<?php echo JRoute::_( TUIYO_INDEX.'&amp;context=SystemTools&amp;do=globalConfig' ); ?>"><a>System settings</a></li>                        
                    	<li rel="<?php echo JRoute::_( TUIYO_INDEX.'&amp;context=systemTools&amp;do=editEmails' ); ?>"><a>System emails</a> </li>
                        <li rel="<?php echo JRoute::_( TUIYO_INDEX.'&amp;context=communityManagement&amp;do=getCategories' );?>"><a href="index.php?do=getUserGroups"></a><a>System categories</a></li>
                        <li rel="<?php echo JRoute::_( TUIYO_INDEX.'&amp;context=SystemTools&amp;do=reportBug' ); ?>"><a>Report bugs</a></li>
                        <li rel="<?php echo JRoute::_( TUIYO_INDEX.'&amp;context=SystemTools&amp;do=autoCenter' ); ?>"><a>Run macros</a></li>
                        <li rel="<?php echo JRoute::_( TUIYO_INDEX.'&amp;context=systemTools&do=statistics' ); ?>"><a>Site statistics</a></li>
                    </ul>                  
                </li>
                
                <li id="manageCommunity" class="aTAHead">
                	<a>Community Management</a></li>
                    <li class="dTABody">
                        <ul id="userManagementMenu">
                            <li rel="<?php echo JRoute::_( TUIYO_INDEX.'&amp;context=communityManagement' );?>">
                            	<a href="index.php?do=getUserList"></a><a>View all users</a></li>
                            <li rel="<?php echo JRoute::_( TUIYO_INDEX.'&amp;context=communityManagement&amp;do=getGroups' );?>">
                            	<a href="index.php?do=getUserGroups"></a><a>View user groups</a></li>                      
                            <li rel="<?php echo JRoute::_( TUIYO_INDEX.'&amp;context=communityManagement' );?>">
                            	<a href="index.php?do=moderationPanel">View moderation queue</a></li>
                            <li rel="<?php echo JRoute::_( TUIYO_INDEX.'&amp;context=communityManagement' );?>">
                            	<a href="index.php?do=getUserImportForm">Pending Invites</a></li>                                
                        </ul>
                    </li>
            
            <li class="aTAHead"><a>Manage user resources</a></li>
            	<li class="dTABody">
                	<ul id="userManagementResources">
                    	<li><a>Uploaded image files</a></li>
                        <li><a>Uploaded audio files</a></li>
                        <li><a>Uploaded video</a></li> 
                        <li><a>View resources by user</a></li>                        
                    </ul>                
                </li>
               

            <li class="aTAHead">
            	<a>Important Tuiyo RSS feeds</a></li>           	
				<li class="dTABody">
                	<ul id="userManagementFeeds">
                    	<li rel="http://www.blog.tuiyo.co.uk/?feed=rss2"><a>Tuiyo Developement Blog</a></li>
                        <li rel="http://apps.tuiyo.co.uk/rss/catalog/category/cid/8/store_id/1/"><a>Tuiyo New Applications</a></li>
                        <li rel="http://getsatisfaction.com/tuiyo/topics.rss?sort=recently_created"><a>Tuiyo Support Forum</a></li>                        
                    </ul>
                </li>
        </ul>
        <p style="font-size: 12px; margin-left: 11px; text-align: right; padding-right: 9px;">
        	<a href="<?php echo JRoute::_( 'index.php' ) ?>" style="color: #fff">Back to Joomla admin </a>
        </p>
	</div>
        
	<div id="homepageContent">
    	<div class="reporter"><jdoc:include type="message" /></div>
		<?php echo $adminPage ?> 
        <p style="font-size: 10px; color: #777">&copy; 2006, 2007, 2008, 2009 <a href="http://www.drstonyhills.com/">dr.stonyhills</a> &bull; Powered by Joomla &bull; Licensed as GPLv2.1 &bull; <?php echo $version ?></p>
	</div>
</div>

<div id="TuiyoAjaxLoading">
	<img src="components/com_tuiyo/style/images/ajaxactivity.gif" />
</div>



