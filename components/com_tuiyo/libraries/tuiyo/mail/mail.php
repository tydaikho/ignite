<?php
/**
 * Handles Mails / Emails  
 *
 * @copyright  2008 tuiyo Platform
 * @license    http://platform.tuiyo.com/license   BSD License
 * @version    Release: $Id$
 * @link       http://platform.tuiyo.com/
 * @author 	   livingstone[at]drstonyhills[dot]com 
 * @access 	   Public 
 * @since      1.0.0 alpha
 * @package    tuiyo
 */
 
 class TuiyoMail{
 	
 	private $_errors 		= '';
 	static  $_recipients 	= '';
 	static  $_subject		= '';
 	static  $_default 		= 'text/html';
 	static  $_body          = '';
 	static  $_sender		= '';
 	static  $_sentStatus    = FALSE ;
 	static  $_attachement   = TRUE;
 	static  $_engine 		= 'PHPMail';
 	
 	/**
 	 * TuiyoMail::getSender()
 	 * 
 	 * @return
 	 */
 	public function getSender()
 	{}
 	
 	/**
 	 * TuiyoMail::getSubject()
 	 * 
 	 * @return
 	 */
 	public function getSubject()
 	{}
 	
 	/**
 	 * TuiyoMail::getRecipeints()
 	 * 
 	 * @return
 	 */
 	public function getRecipeints()
 	{}
 	
 	/**
 	 * TuiyoMail::addRecipients()
 	 * 
 	 * @param mixed $recipientEmails
 	 * @param bool $BCC
 	 * @return
 	 */
 	public function addRecipients($recipientEmails = array() , $BCC = TRUE )
 	{}
 	
 	/**
 	 * TuiyoMail::body()
 	 * 
 	 * @param mixed $mailBody
 	 * @param bool $noHTML
 	 * @return
 	 */
 	public function body($mailBody, $noHTML = FALSE )
 	{}
 	
 	/**
 	 * TuiyoMail::getMail()
 	 * 
 	 * @return
 	 */
 	public function getMail()
 	{}
 	
 	/**
 	 * TuiyoMail::setSender()
 	 * 
 	 * @param mixed $senderEmail
 	 * @param bool $noReply
 	 * @return
 	 */
 	public function setSender($senderEmail, $noReply = FALSE )
 	{}
 	
 	/**
 	 * TuiyoMail::setSubject()
 	 * 
 	 * @return
 	 */
 	public function setSubject()
 	{}
 	
 	/**
 	 * TuiyoMail::setMail()
 	 * 
 	 * @param mixed $senderEmail
 	 * @param mixed $recipients
 	 * @param mixed $subject
 	 * @param mixed $body
 	 * @param bool $noHTML
 	 * @return
 	 */
 	public function setMail($senderEmail, $recipients = array(), $subject, $body,  $noHTML = FALSE )
 	{}
 	
 	/**
 	 * TuiyoMail::send()
 	 * 
 	 * @return
 	 */
 	public function send()
	{}
	
	/**
	 * TuiyoMail::addAttachement()
	 * 
	 * @param mixed $fileSource
	 * @param mixed $MIMEtype
	 * @return
	 */
	public function addAttachement($fileSource, $MIMEtype)
	{}
	
	/**
	 * TuiyoMail::replyTo()
	 * 
	 * @param mixed $email
	 * @return
	 */
	public function replyTo($email)
	{}
	
	/**
	 * TuiyoMail::getErrors()
	 * 
	 * @return
	 */
	public function getErrors()
	{}
	
	/**
	 * TuiyoMail::_isRecipient()
	 * 
	 * @param mixed $Email
	 * @return
	 */
	private function _isRecipient($Email)
	{}
	
	/**
	 * TuiyoMail::_isValidEmail()
	 * 
	 * @param mixed $Email
	 * @return
	 */
	private function _isValidEmail($Email)
	{}
	
	/**
	 * TuiyoMail::_setMailEngine()
	 * 
	 * @param mixed $engineType
	 * @return
	 */
	private function _setMailEngine($engineType)
	{}
	
	/**
	 * TuiyoMail::_setError()
	 * 
	 * @param mixed $error
	 * @param string $handler
	 * @return
	 */
	private function _setError( $error, $handler = '')
	{}
	
 }