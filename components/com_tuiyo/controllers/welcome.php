<?php
/**
 * ******************************************************************
 * Welcome Class/Object for the Tuiyo platform                           *
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
 * TuiyoControllerWelcome
 * 
 * @package tuiyo
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoControllerWelcome extends JController{
	
	/**
	 * Welcome view object
	 */
	private $view 	= null;
	
	/**
	 * TuiyoControllerWelcome::__construct()
	 * Constructor method
	 * 
	 * @return void
	 */
	public function __construct(){
		
		//construct JController
		parent::__construct();
		//construct Tuiyo Controller;
		TuiyoControllerCore::init('welcome' , false );
		//get the view	
		$this->view = $this->getView( 'welcome' , 'html' );

	}
	
	/**
	 * TuiyoControllerWelcome::display()
	 * Displays the default welcome task/ref
	 * @param mixed $tpl
	 * @return void
	 */
	public function display($tpl=null)
	{
		$user		= $GLOBALS['API']->get('user' , null);
		$mainframe  = $GLOBALS['mainframe'];
		
		$view 		= $this->getView('welcome', 'html');
		
		$livestyle 	= TUIYO_LIVE_PATH.'/client/default/';
		
		$GLOBALS['mainframe']->setPageTitle( sprintf(  _("Welcome %s"), $user->name ) );

		$view->assignRef('loggedIn' , $user->isUserLoggedIn() );
		$view->assignRef('livestyle', $livestyle );
		$view->assignRef('user' , $user );
		
		$view->setLayoutExt( 'tpl' );		
		
		$view->display( $tpl );
	}
	
	public function auth()
	{
		JRequest::setVar("tmpl", "component");
		
		$user		= $GLOBALS['API']->get('user' , null);
		$mainframe  = $GLOBALS['mainframe'];
		
		//If user is not guest send back to homepage
		if(!$user->joomla->get('guest')){
			$mainframe->redirect( JRoute::_(TUIYO_INDEX."&amp;view=welcome", FALSE ) );
			return false;
		}		
		
		$view 		= $this->getView('welcome', 'html');
		
		$invite 	= JRequest::getString("ic", NULL , 'request' );
		$inviteeN	= NULL;
	 	$inviteeE   = NULL ;
		
		if(!empty($invite)):
		
			$iTable 	= TuiyoLoader::table( "invites" );
			$iObject 	= $iTable->findInvite( $invite );
			
			if(!empty($iObject) && is_object($iObject)){
				$inviteeN = $iObject->name ;
				$inviteeE = $iObject->email ;
			
			}
			$view->assignRef( 	"inviteCode", $invite );
			$view->assignRef(   "inviteeName" , $inviteeN );
			$view->assignRef(   "inviteeEmail" ,  $inviteeE  );
			
		endif;
		
		$view->showAuthPage($invite, $inviteeN );
	}
	
	/**
	 * TuiyoControllerWelcome::authorise()
	 * Authorises who uses the application
	 * 
	 * @return void
	 */
	public function authorise(){
			
	}
	
}