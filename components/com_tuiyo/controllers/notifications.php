<?php
/**
 * ******************************************************************
 * Notifications controller for the tuiyo application                     *
 * ******************************************************************
 * @copyright : 2008 tuiyo Platform                                 *
 * @license   : http://platform.tuiyo.com/license   BSD License     * 
 * @version   : Release: $Id$                                       * 
 * @link      : http://platform.tuiyo.com/                          * 
 * @author 	  : livingstone[at]drstonyhills[dot]com                 * 
 * @access 	  : Public                                              *
 * @since     : 1.0.0 alpha                                         *   
 * @package   : tuiyo    yyeSyV0kysKV2oqpLUES5                                           *
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
 * TuiyoControllerNotifications
 * 
 * @package Joomla
 * @author stoney
 * @copyright 2010
 * @version $Id$
 * @access public
 */
class TuiyoControllerNotifications extends JController
{
	 public function __construct()
	 {
 		TuiyoControllerCore::init("Notifications", false);
		TuiyoEventLoader::preparePlugins("notifications" );
		parent::__construct();
			
	}
		
	public function display()
	{
		global $mainframe;
		
		//Must be LoggedIN to view Notification
		$auth 		= $GLOBALS["API"]->get("authentication");
		$auth->requireAuthentication();
		
		$user 		= $GLOBALS["API"]->get("user" , NULL );
		$nID		= JRequest::getVar("id", null);
		
		$model 		= $this->getModel("notifications");
		$notice 	= $model->loadNotice( (int)$nID , $user->id );
		$redirect 	= !empty($notice->link) ? JRoute::_($notice->link) : JRoute::_( TUIYO_INDEX.'&view=profile&do=homepage');
			
		//Redirect to the profile LINK
		$mainframe->redirect( $redirect   );	
	}
	
	public function remove()
	{
		global $mainframe;
			
		//Must be LoggedIN to view Notification
		$auth 		= $GLOBALS["API"]->get("authentication");
		$auth->requireAuthentication();
		
		$user 		= $GLOBALS["API"]->get("user" , NULL );
		$nID		= JRequest::getVar("id", null);
		
		$model 		= $this->getModel("notifications");
		$notice 	= $model->removeNotice( (int)$nID , $user->id );
		
		//die;

		$redirect 	= JRoute::_(TUIYO_INDEX.'&view=profile&do=homepage');
			
		//Redirect to the profile LINK
		$mainframe->redirect(  $redirect );				
	}
	
}