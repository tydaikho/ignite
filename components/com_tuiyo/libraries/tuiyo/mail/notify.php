<?php
/**
 * ******************************************************************
 * TuiyoTableUsers  Class/Object for the Tuiyo platform                              *
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
 * TuiyoActivity
 * 
 * @package Tuiyo For Joomla
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoNotify
{
	/**
	 * TuiyoNotify::_()
	 * 
	 * @param mixed $userIdTo
	 * @param mixed $title
	 * @param mixed $actionLink
	 * @param mixed $actionTitle
	 * @param string $application
	 * @param mixed $template
	 * @param mixed $templateVars
	 * @return void
	 */
	public function _($userIdTo, $title, $actionLink, $actionTitle, $application = 'system', $template = NULL, $templateVars = NULL){
	
		global $mainframe ; TuiyoLoader::helper("parameter");
		
		//1. Check App can send Mail
		//2. Load the user message is being sent to
		$document		= TuiyoApi::get("document" );
		$userFrom		= TuiyoApi::get("user" , null );
		$user			= TuiyoApi::get("user", (int)$userIdTo );
		$notifyTable	= TuiyoLoader::table("notifications" , true );
		$notifyParams	= TuiyoParameter::load("emails");
		
		if($userIdTo < 1){
			$document->enqueMessage( _("Could not notify the user due to a UserID({$userIdTo}) error") , "error");
			return false;
		}		
		//3. Add Notification Title to database;		
		$notifyTable->title 	= $title;
		$notifyTable->userid	= $userIdTo;
		$notifyTable->link 		= $actionLink;
		$notifyTable->linktitle = $actionTitle ;
		$notifyTable->application = $application ;
		$notifyTable->status	= 0 ;
		$notifyTable->type		= $template ;
		$notifyTable->template  = json_encode( $templateVars );
		
		if(!$notifyTable->store()){
			$document->enqueMessage( sprintf( _("Could not notify the user due to the following error: %s"), $notifyTable->getError() ) , "error");
			return false;
		}		
		
		
		
		if( !empty($template) ) :
		
			$eTitle 		= $notifyParams->get( $template."Title" );
			$eBody 			= $notifyParams->get( $template."Body" );
			
			$subject 		= html_entity_decode($eTitle, ENT_QUOTES);
			$message 		= html_entity_decode($eBody, ENT_QUOTES);
	
			TuiyoNotify::sendMail( $user->joomla->get('email') , $subject , $message );
			
		endif;
	}
	
	/**
	 * TuiyoNotify::sendMail()
	 * Sending a direct Email
	 * @param mixed $recipientEmail
	 * @param mixed $mailSubject
	 * @param mixed $mailBody
	 * @return
	 */
	public function sendMail($recipientEmail , $mailSubject, $mailBody  ){
		
		$mailer =& JFactory::getMailer();
		$config =& JFactory::getConfig();
		$documt =& TuiyoApi::get("document" );
		
		//Sender and Recipient

		$sitename 		= $config->getValue( 'config.sitename' );
		$mailfrom 		= $config->getValue( 'config.mailfrom' );
		$fromname 		= $config->getValue( 'config.fromname' );
		$siteURL		= JURI::base();		
				
		//$mailer->addAttachment( PATH_COMPONENT.DS.'assets'.DS.'document.pdf' );
		
		$send = &JUtility::sendMail($mailfrom, $fromname, $recipientEmail, $mailSubject, $mailBody);
		
		if ( $send !== true ) {		
			echo $send->getError();
			$documt->enqueMessage( _("Could not send the notification email") , "error");
			return false;
		}
		
		return $send;						
	}
	
	private function parseMailTemplate(){}
	
	private function getTemplate(){}
	
	private function canSendNotice(){}
	
	private function canReceiveNotice(){}
	
 	public function getInstance($ifNotExist = TRUE )
 	{ 
 		/** Creates new instance if none already exists ***/
		static $instance;
		
		if(is_object($instance)&&!$ifNotExist){
			return $instance;		
		}else{
			$instance = new TuiyoNotify()	;	
		}		
		return $instance;	
  	}	
}