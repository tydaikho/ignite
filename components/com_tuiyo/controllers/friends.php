<?php
/**
 * Tuiyo Friends Controller
 *
 * @copyright  2008 Tuiyo Platform
 * @license    http://platform.Tuiyo.com/license   BSD License
 * @version    Release: $Id$
 * @link       http://platform.Tuiyo.com/
 * @author 	   livingstone[at]drstonyhills[dot]com 
 * @access 	   Public 
 * @since      1.0.0 alpha
 * @package    TuiYo
 */
/**
 * no direct access
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
 * TuiyoControllerFriends
 * 
 * @package tuiyo
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoControllerFriends extends JController
{
    /**
     * TuiyoControllerFriends::__construct()
     * 
     * @return void
     */
    function __construct()
    {
        //Tuiyo controller
        TuiyoControllerCore::init();
        //Joomla controller
        parent::__construct();
    }
    /**
     * Method to display the view
     * @access	public
     */
    function display($tpl = null)
    {
  		
		$view 	= $this->getView('friends' , "html");
		$model 	= $this->getModel('friends');
			
		$user	= $GLOBALS['API']->get("user", null);
        
        //get Friends lists;
        $friends= $model->getFriendLists( $user->id );
        $invites= $model->generateInviteHistory( $user->id );
        
		$view->assignRef("invites" , $invites );
		$view->assignRef("friends" , $friends );
		$view->assignRef("user" , $user );
		$view->setLayoutExt('tpl');
		
		$view->display($tpl);
    }
    
    /**
     * TuiyoControllerFriends::add()
     * Adds a user to your friends lists
     * @return void
     */
    public function add()
    {	
		$user	= TuiyoAPI::get( "user" );
		$pid	= JRequest::getVar( "pid" ); 
		$model	= $this->getModel( "friends" );
		
		$pid 	= (!empty($pid)) ? (int)$pid : JError::raiseError(500, "Invalid Profile ID");
		
		$rtrn 	= JRoute::_(TUIYO_INDEX."&view=profile&do=view&pid=".(int)$pid , false );			
		$msg	= _("We could not add this user as your friend, probably because another relationship already exists or is pending");
		$type	= "error";
		$state  = $model->addFriend((int)$pid, $user->id ) ;
		
		if( $state !== FALSE ){
			$cnfrm 	= ( intval( $state ) > 0 )? null : _("Confirmation is now pending") ;
			$msg	= _("Successfully added as friend. "). $cnfrm ;
			$type	= "notice";
		}
		
		$this->setRedirect( $rtrn, $msg, $type );
		$this->redirect();
    }
    
    public function removeInvite(){
		
		$user	= TuiyoAPI::get( "user" );
		$id		= JRequest::getVar( "ic" , NULL ); 
		$model	= $this->getModel( "friends" );
		
		$pid 	= (!empty($id)) ? (int)$id : JError::raiseError(500, "Invalid Invite ID");
		
		if(!$model->deleteInvite( $id )){
			JError::raiseError(TUIYO_SERVER_ERROR , _("Could not delete the Invitation") );
			return false;
		}
		$state 	= _("The invite code to this user has now been terminated and is henceforth unsuable");
		$rtrn 	= JRoute::_(TUIYO_INDEX."&amp;view=friends&amp;do=view" , false );
				
		$this->setRedirect( $rtrn, $state, "notice" );
		$this->redirect();
    }
    
    /**
     * TuiyoControllerFriends::inviteFriends()
     * Invites Friends to the community
     * @return
     */
    public function inviteFriends(){
  		
  		JRequest::checkToken() or jexit( 'Invalid Token' );
  		
    	$post 		= JRequest::get( 'post' );
    	$auth 		= TuiyoAPI::get( 'authentication' );
    	$validate   = TuiyoAPI::get( 'validate' );
    	$document 	= TuiyoAPI::get( 'document' );
    	
    	$invitees 	= JRequest::getVar("friendname", "", "post" );
    	$inviteeEms	= JRequest::getVar("friendemail", "", "post" );
    	$inviteMes 	= JRequest::getVar("incmessage", "", "post");
    	
    	$auth->requireAuthentication( "post" ); //Must be signed IN
    	
		$user		= TuiyoAPI::get( 'user' );
		
    	$model	 	= $this->getModel( "friends" );
    	$success 	= array(); 
    	$failures 	= array();
    	$i 		 	= 0;
    	
    	foreach( $inviteeEms as $email ):
    	
    		$invitee = new StdClass;
    		$invitee->name 	= $invitees[$i];
    		$invitee->email = $email ;
    		
    		if(!empty($invitee->email) && $validate->isEmail( $invitee->email )){
    			if($model->addInvite( $user, $invitee , $inviteMes )){
    				$success[] = $invitee->email;
					//Notify all Invitees by email;	
    			}else{
    				$failures[] = $invitee->email;
    			}
    		}
    		$i++ ;
    		
    	endforeach;
    	
    	if(sizeof($success)>0):
    		$sEmails 	= implode( ", " , $success );
    		$sMessage 	= sprintf(_("Invites have been sent to %s; Once the account(s) are active they/it will be added to your friend list. You will be notified") , $sEmails) ; 
    	else:
    		$sMessage 	= _("We could not send out any invites to any of the friends required");
    	endif;
    	
		if(sizeof($failures)>0):
    		$fEmails 	= implode( ", " , $failures );
    		$fMessage 	= sprintf( _("Invites could not be sent to %s; Probably because they/it already have/has an existing account(s)"), $fEmails) ; 
			$document->enqueMessage( $fMessage, "error" );
    	endif;
   		
        $document->enqueMessage($sMessage , "notice" );
		$rtrn 	= JRoute::_(TUIYO_INDEX."&amp;view=friends&amp;do=view" , false );
		$this->setRedirect( $rtrn );
		$this->redirect();
    }
    
    /**
     * TuiyoControllerFriends::remove()
     * Removes a user from your friends lists
     * @return void
     */
    public function remove()
    {
		$user	= TuiyoAPI::get( "user" );
		$pid	= JRequest::getVar( "pid" ); 
		$model	= $this->getModel( "friends" );
		
		$pid 	= (!empty($pid)) ? (int)$pid : JError::raiseError(500, "Invalid Profile ID");
		$state  = $model->removeFriend((int)$pid, $user->id ) ;
		$rtrn 	= JRoute::_(TUIYO_INDEX."&amp;view=friends&amp;do=view" , false );
				
		$this->setRedirect( $rtrn, $state, "notice" );
		$this->redirect();
		
    }
    
	/**
	 * Authorises a user to run the given controller!
	 * TuiyoControllerCore::authorise()
	 * 
	 * @return void
	 */
	public function authorise()
	{
		global $API;
		$user = $API->get( 'user' );
	}    
}
