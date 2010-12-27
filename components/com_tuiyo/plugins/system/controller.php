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
 /**
 * no direct access
 */
defined('TUIYO_EXECUTE') || die('Restricted access');

/**
 * 
 * System Controller.
 * @author livingstonefultang
 */
class SystemServiceController Extends TuiyoControllerServices{
	
	/**
	 * System Service for Tuiyo 
	 * @var object
	 */
	public function __construct(){
		//parent::__construct();
		//SET MODEL/VIEW PATH
		$this->_setPath('view', TUIYO_PLUGINS.DS.'system'.DS.'views');
		$this->addModelPath(TUIYO_PLUGINS.DS.'system'.DS.'models');
	}
	
	public function chatBox(){
			
		//1. Get Pre-requisites;
		$participant= JRequest::getVar("participant" , null );
		$server 	= JRequest::get("server");
		$method 	= strtolower( $server['REQUEST_METHOD'] );

		$model		= &TuiyoLoader::model("messages", true);
		$view 		= $this->getView("chat", "html");
		
		$document 	= &$GLOBALS['API']->get("document" );
		$user 		= &$GLOBALS['API']->get("user" , null );
		
		
		/** Check we have a valid token and Check we have a valid token***/
		if( empty($user->id) || $user->joomla->get('guest') ){
			JError::raiseError( TUIYO_BAD_REQUEST, _("Invalid user ID") );
		}
		
		$chatRoom  = array(); //$model->initiateChatRoom( $user->id, $participant );

		$view->assignRef("user", $user);
		$view->assignRef("chatroom" , $chatRoom);
		
		
		$view->showChatBox();
		
	}
}