<?php
/**
 * ******************************************************************
 * Services controller object for the Tuiyo platform             *
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

defined('TUIYO_EXECUTE') || die;
/**
 * joomla Controller
 */
jimport('joomla.application.component.controller');
/**
 * Tuiyo Controller
 */
TuiyoLoader::controller('core');
/**
 * TuiyoControllerServices
 * @package tuiyo
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoControllerServices extends JController
{
	private $_serviceController	= null;
	
	private $_servicePath		= null;
	
	/**
	 * 
	 * Constructor
	 */
	public function __construct( $config = array()){
		
		parent::__construct( $config );
		
		//Load the service controller
		$service	= JRequest::getString('service', null);
		$doTask 	= JRequest::getString('do' , null);
		
		if(empty($service)) throw new Exception(_('undefined service'));

		//Require service Controller
		$this->_serviceController = &TuiyoLoader::plugin( (string)$service, true );
		$this->_serviceView		  = &$this->getView("services", "html");
		
		//Check that the service has the requested method and register call!
		if(!empty($doTask) && method_exists($this->_serviceController, $doTask)){
			//echo "method exists";
			$this->_serviceController->$doTask();
			//call_user_func_array(array($this->_serviceController, $doTask) );
			return;
		}
	}
	
	public function saveSettings(){}
	
	/**
	 * This methods adds a service to a user profile
	 * @return json data
	 */
	public function add(){}
	
	/**
	 * Removes a service from a user profile
	 * TODO: Remove all left over data from system tables
	 */
	public function remove(){}
	
	/**
	 * Timely action, pull data from service 
	 * TODO: Cron Job calls? auto pulls?
	 */
	public function getData(){}
	
	/**
	 * Updates the service with data posted by user
	 * Call service API, post or put?
	 */
	public function setData(){}
	
	
	private function _loadAPI(){}
	private function _authenticateUser(){}
	private function _terminateSession(){}
	private function _determineServiceType(){} //Feeds (what type of feeds?), or what, etc?
	
}