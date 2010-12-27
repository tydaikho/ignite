<?php
/**
 * ******************************************************************
 * Authentication view for Tuiyo application                               *
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
  * TuiyoAuthentication
  * 
  * @package tuiyo
  * @author Livingstone Fultang
  * @copyright 2009
  * @version $Id$
  * @access public
  */
 class TuiyoAuthentication{
 	
 	private $authType 	= '';
 	
 	private $_errors 	= array();
 	
 	private $referer   	= '';
 	
 	/**
 	 * TuiyoAuthentication::TuiyoAuthentication()
 	 * 
 	 * @param string $type
 	 * @return
 	 */
 	public function TuiyoAuthentication($type = 'DB')
    {	
 		if(in_array($type, array('DB','LDAP','OPENID'))){
 			self::setAuthType( $type );
 		}
 	}
 	
 	/**
 	 * TuiyoAuthentication::userIsGuest()
 	 * 
 	 * @param mixed $userID
 	 * @return
 	 */
 	public function userIsGuest( $userID = null)
	{ }
 	
 	/**
 	 * TuiyoAuthentication::createSession()
 	 * 
 	 * @param mixed $userID
 	 * @return
 	 */
 	public function createSession( $userID )
 	{ }
 	
 	/**
 	 * TuiyoAuthentication::getPermission()
 	 * 
 	 * @param mixed $userID
 	 * @return
 	 */
 	public function getPermission( $userID )
 	{ }
 	
 	/**
 	 * TuiyoAuthentication::login()
 	 * 
 	 * @param mixed $userDATA
 	 * @param mixed $password
 	 * @param string $authType
 	 * @return
 	 */
 	public function login($userDATA, $password , $authType = 'USERNAME')
 	{ }
 	
 	/**
 	 * TuiyoAuthentication::logout()
 	 * 
 	 * @param mixed $userDATA
 	 * @param mixed $sesionKEY
 	 * @param bool $saveSession
 	 * @return
 	 */
 	public function logout($userDATA, $sesionKEY, $saveSession = FALSE )
 	{ }
 	
 	/**
 	 * TuiyoAuthentication::setPermission()
 	 * 
 	 * @return
 	 */
 	public function setPermission( )
 	{ }
 	
 	/**
 	 * TuiyoAuthentication::hasPermission()
 	 * 
 	 * @param mixed $userID
 	 * @param string $permTYPE
 	 * @return
 	 */
 	public function hasPermission($userID, $permTYPE = '')
 	{ }
 	
 	/**
 	 * TuiyoAuthentication::changePassword()
 	 * 
 	 * @param mixed $userID
 	 * @param mixed $oldPass
 	 * @param mixed $newPass
 	 * @return
 	 */
 	public function changePassword($userID, $oldPass, $newPass )
 	{ }
 	
 	/**
 	 * TuiyoAuthentication::setAuthType()
 	 * 
 	 * @param mixed $type
 	 * @return
 	 */
 	public function setAuthType( $type )
	{ }
	
	/**
	 * TuiyoAuthentication::getErrors()
	 * 
	 * @return
	 */
	public function getErrors()
	{ }
	
	/**
	 * TuiyoAuthentication::getLastAuthDateTime()
	 * 
	 * @param mixed $userID
	 * @return
	 */
	public function getLastAuthDateTime($userID)
	{ }
	

 	/**
 	 * TuiyoAuthentication::getInstance()
 	 * Returns an instance of the authentication class
 	 * @param bool $ifNotExist
 	 * @return
 	 */
 	public function getInstance($ifNotExist = TRUE )
 	{ 
 		/** Creates new instance if none already exists ***/
		static $instance;
		
		if(is_object($instance)&&!$ifNotExist){
			return $instance;		
		}else{
			$instance = new TuiyoAuthentication()	;	
		}
		return $instance;	
  	}	
	

	/**
	 * TuiyoAuthentication::requireAuthentication()
	 * Force guest user authentication for required task
	 * @param string $method
	 * @return
	 */
	public function requireAuthentication($method = "post")
	{ 		
		$SERVER	    = &JRequest::get( 'server' );
		$user 		= &$GLOBALS['API']->get("user" );
		$mainframe  = &$GLOBALS['mainframe'];

		//if user is guest		 
		if($user->joomla->get('guest')){
//		    //Login the user?
//		    if( !JRequest::checkToken( ($method <> "post") ? "cookie" : $method ) ){
//				JError::raiseError(TUIYO_BAD_REQUEST, "Invalid token" );
//			}
		    session_start() ;
		    //if( !JRequest::checkToken( $method ) ) jexit( 'Invalid Token' );
		    if(isset($SERVER['PHP_AUTH_USER']) && isset($SERVER['PHP_AUTH_PW']) && !isset($SERVER['FORCE_AUTH'])) 
			{
				//Mainframe authentication		
				$options = array();
				$options['remember'] 	= false;
				$options['return'] 		= '';
		
				$credentials = array();
				$credentials['username'] = JRequest::getVar('PHP_AUTH_USER', '', 'server', 'username');
				$credentials['password'] = JRequest::getString('PHP_AUTH_PW','','server', JREQUEST_ALLOWRAW);
		
				//preform the login action
				$error = $mainframe->login($credentials, $options);
				if(!JError::isError($error))
				{
					// Redirect if the return url is not registration or login
					$user = &$GLOBALS['API']->get("user" );
					JRequest::setVar("userID", $user->id );
					
					unset( $SERVER['FORCE_AUTH'] );
					
					return true;
					
				}else{
                    header('WWW-Authenticate: Basic realm="TuiyoTimeline"');
				    header('HTTP/1.0 401 Unauthorized');
				    //$message = _('Authentication required for this method');
				    //$welcome = JRoute::_(TUIYO_INDEX.'&view=welcome&do=login');
				    JError::raiseError('Authentication required for this method', TUIYO_AUTH_REQUIRED);
				}
			}
			//Push authentication header
					header('WWW-Authenticate: Basic realm="TuiyoTimeline"');
				    header('HTTP/1.0 401 Unauthorized');
				    //$message = _('Authentication required for this method');
				    //$welcome = JRoute::_(TUIYO_INDEX.'&view=welcome&do=login');
				    JError::raiseError('Authentication required for this method',TUIYO_AUTH_REQUIRED);
				    //$mainframe->redirect($welcome , $message );
				    //jexit(0);
		    
		}else{
			return true;	
		}
	 }
	
	/**
	 * TuiyoAuthentication::onLoginComplete()
	 * Adds a call if login is complete, useful, for redirecting
	 * 
	 * @param mixed $callBack
	 * @return void
	 */
	public function onLoginComplete( $callBack )
	{ }
	
	
	
	/**
	 * TuiyoAuthentication::setReferer()
	 * 
	 * @param string $URL
	 * @param mixed $APIKey
	 * @return void
	 */
	public function setReferer($URL='', $APIKey=null)
	{	
	}
	
	/**
	 * TuiyoAuthentication::_setError()
	 * 
	 * @param mixed $error
	 * @return
	 */
	private function _setError( $error )
	{
		self::$_errors[] = $error;
	}
 }