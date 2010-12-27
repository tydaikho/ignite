<?php
/**
 * ******************************************************************
 * Class/Object for the Tuiyo platform                           *
 * ******************************************************************
 * @copyright : 2008 tuiyo Platform                                 *
 * @license   : http://platform.tuiyo.com/license   BSD License     * 
 * @version   : Release: $Id$                                       * 
 * @link      : http://platform.tuiyo.com/                          * 
 * @author 	  : livingstone[at]drstonyhills[dot]com                 * 
 * @access 	  : Public                                              *
 * @since     : 1.0.0 alpha                                         *   
 * @package   : tuiyo                                               *
 * ******************************************************************
 */
defined('TUIYO_EXECUTE') || die('Restricted access');
/**
 * joomla Controller
 */
jimport('joomla.application.component.controller');
/**
 * Tuiyo Controller
 */
TuiyoLoader::controller('core');

/**
 * TuiyoControllerGroups
 * @package tuiyo
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoControllerGroups extends JController
{
    /**
     * TuiyoControllerGroups::__construct()
     * @return void
     */
    function __construct()
    {
        TuiyoControllerCore::init("Groups" , FALSE );
        parent::__construct();
    }

    /**
     * TuiyoControllerGroups::display()
     * @param mixed $tpl
     * @return void
     */
    function display($tpl = null)
    {
        global $API ;

        $view 	= $this->getView('groups', "html");
        $model	= $this->getModel('groups');
        $user 	= $API->get('user');
        
        //Assign Values
        $tmplData	= array( 
			"categories"	=>$model->getCategories(),
			"populargroups"	=>$model->getPopularGroups(),
			//"friendsgroups"	=>$model->getPopularGroupsAmongsFriends(),
			"recentgroups"	=>$model->getRecentGroups(),
			"mygroups"		=> (!$user->joomla->get('guest')) ? $model->getUserGroups( $user->id ) : array()
		); 
		//$model->getUserGroups( $user->id );
		$view->setLayoutExt('tpl');

       	$view->display( $tmplData );
    }
    
    public function getFeed(){
    	$view 	= $this->getView('groups', "feed");
    	$view->showFeed();
    }
    
    public function view(){
    	
    	$view 	= $this->getView('groups', "html");
        $model	= $this->getModel('groups');
        
        $group 	= JRequest::getInt('gid' , null );
        $user 	= TuiyoAPI::get('user'); 
		$gData 	= $model->getGroup( $group );

		//Load Data
		if(!$gData ){
			JError::raiseError(TUIYO_NOT_FOUND, _("The requested group does not exists or may have been deleted"));
			return false;
		}
		$view->assignRef("group" , $gData );
		$view->assignRef("user" , $user );
		
		//print_R($view->group );				
		$view->setLayoutExt('tpl');
    	
    	$view->groupHomepage();
    }
    
    /**
     * TuiyoControllerGroups::join()
     * Join a public group
     * @return void
     */
    public function join(){
    
		$model	= $this->getModel('groups');
        $group 	= JRequest::getInt('gid' , null );
        $referer= JRequest::getVar( "HTTP_REFERER", TUIYO_INDEX, 'server' );
        $user 	= TuiyoAPI::get('user');
        
		$gData 	= $model->getGroup( $group );
		
		//Load Data
		if(!$gData || empty($gData->groupID) || $gData->groupID < 1 ){
			JError::raiseError(TUIYO_NOT_FOUND, _("The requested group does not exists or may have been deleted"));
			return false;
		}	
		//If is already a member
		if($gData->isMember > 0){
			$msg 	 = null ;
			$msgtype = null ;	
			return $this->setRedirect( $referer , $msg , $msgtype );
		}
		//If is already a member
		if((string)$gData->gType <> "public"){
			$msg 	 = sprintf( _("You will need to be invited to join the %s group") , $gData->gName );
			$msgtype = "error" ;	
			return $this->setRedirect( $referer , $msg , $msgtype );
		}		
		//Else Join Group
		if($model->joinGroup( $gData->groupID )){
			$msg 	 = sprintf( _("You have successfully joined the %s group") , $gData->gName );
			$msgtype = "notice";	
			
			//4b. Publish Activity Story;
			$uActivity 	= TuiyoAPI::get("activity", null );
			$gLink		=  JURI::base().'index.php?option=com_tuiyo&view=groups&gid='.$gData->groupID;
			$uStoryLine = sprintf( _('%1s is now a member of <a href="%2s">%3s</a>'), "{*thisUser*}" , $gLink, $gData->gName   );
			$uActivity->publishOneLineStory( $user, $uStoryLine , "groups");
						
			return $this->setRedirect( $referer , $msg , $msgtype );
		}	
    }
    
    
    /**
     * TuiyoControllerGroups::leave()
     * Unsubscribe from a group;
     * @return
     */
    public function leave(){

		$model	= $this->getModel('groups');
        $group 	= JRequest::getInt('gid' , null );
        $referer= JRequest::getVar( "HTTP_REFERER", TUIYO_INDEX, 'server' );
        $user 	= TuiyoAPI::get('user');
        
		$gData 	= $model->getGroup( $group );
		
		//Load Data
		if(!$gData || empty($gData->groupID) || $gData->groupID < 1 ){
			JError::raiseError(TUIYO_NOT_FOUND, _("The requested group does not exists or may have been deleted"));
			return false;
		}	
		//If is already a member
		if($gData->isMember < 1){
			$msg 	 = _("You are not a member of this group");
			$msgtype = "notice" ;	
			return $this->setRedirect( $referer , $msg , $msgtype );
		}
		
		//If is already a member
		if((int)$gData->isAdmin > 0 ){
			$link 	 = JRoute::_( TUIYO_INDEX.'&view=groups&do=delete&'.JUtility::getToken().'=1&gid='.$gData->groupID );
			$delLink = "<a href=\"$link\" >Permanently delete this group?</a>";
			$msg 	 = sprintf( 
							_("You are the owner of the %1s group, are u sure you want to %2s Enteries shared with this group will not be deleted") , 
							$gData->gName , $delLink 
					 );
			$msgtype = "notice" ;	
			return $this->setRedirect( $referer , $msg , $msgtype );
		}else{
			//Else Join Group
			if($model->leaveGroup( $gData->groupID  , $gData->myMembershipID )){
				$msg 	 = sprintf( _("You are no longer a member of the %s group. Enteries shared with this group will not be deleted") , $gData->gName );
				$msgtype = "notice";	
				return $this->setRedirect( $referer , $msg , $msgtype );
			}				
		}		
	}
	
	/**
	 * TuiyoControllerGroups::delete()
	 * Delete group
	 * @return void
	 */
	public function delete(){
		
		$model	= $this->getModel('groups');
        $group 	= JRequest::getInt('gid' , null );
        $user 	= TuiyoAPI::get('user');
        $explore= JRoute::_( TUIYO_INDEX.'&view=groups&do=explore' );
		$gData 	= $model->getGroup( $group );
		
		//If is already a member
		if(!$gData || $gData->isMember < 1 || $gData->isAdmin < 1 || empty($gData->groupID) || $gData->groupID < 1 ){
			$msg 	 = _("You do not have the valid permission to delete this group");
			$msgtype = "error" ;	
			return $this->setRedirect( $explore , $msg , $msgtype );
		}
		
		//Delete the groups
		if($model->deleteGroup( $gData->groupID )){
			$msg 	 = sprintf( _("The %s group has now been deleted. Have fun") , $gData->gName );
			$msgtype = "notice";	
			
			return $this->setRedirect( $explore, $msg , $msgtype );
		}			
	}
    /**
     * Gets the form for the creation of a new group
     * TuiyoControllerGroups::newGroup
     * @return
     */
    public function newGroup()
	{
 		$document 	= $GLOBALS['API']->get("document");
		if($document->getDOCTYPE() !== "json"){
			$GLOBALS['mainframe']->redirect(TUIYO_PROFILE_INDEX);	
		}
		$view 	= $this->getView('groups', "json" );
		$model	= $this->getModel('groups');
		$user 	= $GLOBALS['API']->get( 'user' );
		//Response Array
  		$resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => null, 
			"data" 	=> null, 
			"extra" => null
		);
		//1. Check That user has permission to creat Groups
		//2. Get All parent Categories
		$categories = $model->getCategories( TRUE , FALSE );

		//Get the HTML
		return $view->showNewGroupForm( $resp , $categories );   
    }
    
    /**
     * TuiyoControllerGroups::saveGroup()
     * Saves a group to the database
     * @return
     */
    public function saveGroup()
	{
		//$document 	= $GLOBALS['API']->get("document");
		$user 		= $GLOBALS['API']->get( 'user' );
		
		$view 		= $this->getView('groups', "html" );
		$model		= $this->getModel('groups');
		
		$postData 	= JRequest::get('post');
		$groupID	= JRequest::getInt('groupID', null );
		
		if(isset($groupID)&&$groupID>0){
			return $this->saveEdit( $postData , $user->id );
		}
	
		$storedGroup= $model->storeGroup( $postData , $user->id );
		
		$message 	= sprintf( _('The group %s has now been created') , $storedGroup->gName );
		$url 		= JRoute::_(TUIYO_INDEX.'&amp;view=groups&amp;do=view&amp;gid='.$storedGroup->groupID, false );
		
		$this->setRedirect( $url, $message, "notice");
		$this->redirect();
	}
	
	/**
	 * TuiyoControllerGroups::saveEdit()
	 * Updates a group Settings
	 * @param mixed $postData
	 * @param mixed $userID
	 * @return
	 */
	private function saveEdit($postData , $userID ){
		
        $group 	= JRequest::getInt('groupID' , null );
        $user 	= TuiyoAPI::get('user' , $userID );
        $referer= JRequest::getVar( "HTTP_REFERER", TUIYO_INDEX, 'server' );
		
		$model	= $this->getModel('groups');
		$gData 	= $model->getGroup( $group );

		//If is already a member
		if(!$gData || $gData->isMember < 1 || $gData->isAdmin < 1 || empty($gData->groupID) || $gData->groupID < 1 ){
			$msg 	 = _("You do not have the valid permission to delete this group");
			$msgtype = "error" ;	
			return $this->setRedirect( $explore , $msg , $msgtype );
		}
		
		$stored  = $model->storeGroup( $postData , $user->id, false  );

		$msg 	 = sprintf( _("The %s group has now been updated") , $gData->gName );
		$msgtype = "notice";	
			
		$this->setRedirect( $referer, $msg , $msgtype );
		$this->redirect();		
	}

    /**
     * Authorises a user to run the given controller!
     * TuiyoControllerCore::authorise()
     * @return void
     */
    public function authorise()
    {
        global $API;
        $user = $API->get('user');
    }
}
