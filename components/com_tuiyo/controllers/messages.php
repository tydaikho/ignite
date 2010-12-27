<?php
/**
 * ******************************************************************
 * Messages controller                                              *
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
 * TuiyoControllerMessages
 * 
 * @package tuiyo
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoControllerMessages extends JController
{
    /**
     * TuiyoControllerMessages::__construct()
     * 
     * @return void
     */
    public function __construct()
    {
        //Tuiyo controller
        TuiyoControllerCore::init("Messages");
        //Joomla controller
        parent::__construct();
    }	
	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	public function display()
	{
		$user 	= TuiyoAPI::get('user', null );

		$view 	= $this->getView('messages' , "html" );
		$model 	= $this->getModel('messages');
		//Default bc;
  		$msgs 	= &$model->getMessages($user->id );	
		
		$view->setLayoutExt('tpl');
		$view->assignRef("messages", $msgs);
		$view->display( );
		
	}
	
	/**
	 * TuiyoControllerMessages::createEvent()
	 * 
	 * @return
	 */
	public function createEvent()
	{
		
		$user 	= TuiyoAPI::get( 'user' );
		$doc  	= TuiyoAPI::get( 'document' );
		$post 	= JRequest::get( 'post' );
		
		$model 	= $this->getModel( 'events' );
		
		//Report
		if($model->addEvent( $user->id, $post )){	
			$doc->enqueMessage( _("The event has now been created. ") , "notice" );
			}else{
				$doc->enqueMessage( _("We could not create the event. An error must have occured") , "error" );	
			}
		
		//Return to default page
		return $this->calendar();
		
	}
	
	/**
	 * TuiyoControllerMessages::sendMessage()
	 * Sends a message to the requested user,
	 * @return Redirects to incoming messages list
	 */
	public function sendMessage(){
		
		$user 	= TuiyoAPI::get( 'user' );
		$doc  	= TuiyoAPI::get( 'document' );
		$post 	= JRequest::get( 'post' );
		
		$model 	= $this->getModel( 'messages' );
		
		//Report
		if($model->addMessage( $user->id, $post )){	
			$doc->enqueMessage( _("Your message has been sent") , "notice" );
			}else{
				$doc->enqueMessage( _("We could not send the message") , "error" );	
			}
		
		//Return to default page
		return $this->display();
	}
	
	/**
	 * TuiyoControllerMessages::deleteMessages()
	 * Delete Messages
	 * @return void
	 */
	public function deleteMessage()
	{
		$user 	= TuiyoAPI::get( 'user' , null );
		$doc  	= TuiyoAPI::get( 'document' );
		$mid 	= JRequest::getVar( 'mid' , null );
		$model 	= &$this->getModel( 'messages' );
		
		if($user->joomla->get('guest') || empty($mid)){
			JError::raiseError(TUIYO_SERVER_ERROR, 'Invalid Request');
			return false;
		}

		//Delete the message
		if($model->deleteMessage((int)$mid, (int)$user->id)){
			$doc->enqueMessage(_("The message has been deleted"), "notice");
			$this->setRedirect(JRoute::_('index.php?option=com_tuiyo&view=messages'));
			return $this->redirect();
		}
		
		//Else
		JError::raiseError(TUIYO_SERVER_ERROR, _('Could not delete the message'));
		return false;
	}

	
	/**
	 * TuiyoControllerMessages::markAs()
	 * Marks a message as
	 * @return void
	 */
	public function markAs(){
		
		$auth 	= TuiyoAPI::get( 'authentication' );		
		
		//Required authentication
		$auth->requireAuthentication();
		
	    $user 	= TuiyoAPI::get( 'user' );
		$doc  	= TuiyoAPI::get( 'document' );
		$post 	= JRequest::get( 'post' );
		$view 	= $this->getView('messages', 'json');
		$model 	= $this->getModel('messages');
		//Response Array
  		$resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => null, 
			"data" 	=> null, 
			"extra" => null
		);	
		//Method format JSON only	
		if($doc->getDOCTYPE() !== "json"){
			$resp["code"] = TUIYO_BAD_REQUEST;
			$resp["error"]= _("Invalid Request format");
			//dump
			$view->encode( $resp );
			return false;	
		}

		$mid  	= (int)$post['mid'];
		$status = (int)$post['state'];
		
		//Change the message status
		if(!$model->setMessageStatus($mid, $status, $user->id)){
			JError::raiseError(TUIYO_SERVER_ERROR, _('Could not change the message status'));
			return false;
		}		
		//JSON
		$view->encode( $resp );
	}
	
	/**
	 * TuiyoControllerMessages::getDaysEvents()
	 * Get days events
	 * @return
	 */
	public function getDaysEvents(){
		
		$auth 	= TuiyoAPI::get( 'authentication' );		
		
		//Required authentication
		$auth->requireAuthentication();
		
	    $user 	= TuiyoAPI::get( 'user' );
		$doc  	= TuiyoAPI::get( 'document' );
		$date	= JRequest::getVar( 'day' , null  );
		
		$view 	= $this->getView('messages', 'json');
		$model 	= $this->getModel('events');
		
		//Response Array
  		$resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => null, 
			"data" 	=> null, 
			"extra" => null
		);	
		//Method format JSON only	
		if($doc->getDOCTYPE() !== "json"){
			$resp["code"] = TUIYO_BAD_REQUEST;
			$resp["error"]= _("Invalid Request format");
			//dump
			$view->encode( $resp );
			return false;	
		}
		
		$resp["data"] = $model->getUserDayEvents( $user->id , date( 'Y-m-d', strtotime($date)  ) );
		
		return $view->encode( $resp );				
	}	
	
	
	/**
	 * TuiyoControllerMessages::addressBook()
	 * @return void
	 */
	public function notifications(){
		return $this->display();
	}		
	
	/**
	 * TuiyoControllerMessages::calendar()
	 * @return void
	 */
	public function calendar(){
		
		//JRequest::setVar("tmpl", "component");
		//Ready the view
		$view = $this->getView('messages' , "html" );
		//Display the view!
		$view->calendar( );
	}
	
	/**
	 * TuiyoControllerMessages::calendar()
	 * @return void
	 */
	public function agenda(){
		//Ready the view
		$view = $this->getView('messages' , "html" );
		//Display the view!
		$view->agenda( );
	}	
	
	/**
	 * TuiyoControllerCore::authorise()
	 * Authorises a user to run the given controller! 
	 * @return void
	 */
	public function authorise()
	{
		global $API;

		$auth  	= $API->get( 'authentication' );
		$user 	= $API->get( 'user' );
		
	}	
}
