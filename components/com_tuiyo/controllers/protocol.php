<?php
/**
 * ******************************************************************
 * Main TuiyoAPI Protocol                                           *
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
 * TuiyoProtocol
 * @package tuiyo
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoControllerProtocol extends JController
{
	/**
	 * TuiyoControllerProtocol::__construct()
	 * @return void
	 */
	public function __construct()
	{
		//a. Construct the parent
		parent::__construct();
		//a. Component output
    	JRequest::setVar('tmpl', 'component');
		
		//1. Check request method!
		//2. Parse the request
		//4. Register Task
		//5. Action
		$this->registerDefaultTask( 'parseRequest' );
	}    
	
	/**
     * TuiyoControllerProtocol::parseRequest()
     * Parses a protocol request
     * @return void
     */
    public function parseRequest()
    {	
   		//If no format is specified the whole page is returned text/html
    	TuiyoLoader::import("rest.request");
    	TuiyoLoader::import("rest.proxy");
    	//TuiyoLoader::helper('reflection');
    	
    	
    	$this->returnResponse( TUIYO_OK );
    }

    /**
     * TuiyoControllerProtocol::returnResponse()
     * Returns a response from the recent request
     * @return void
     */
    private function returnResponse($status = 200, $body = '', $format = 'json')
    {
    	$doc  		=& TuiyoAPI::get("document");
    	$docType 	=& $doc->getDOCTYPE();
    	$view 		=& $this->getView("protocol", (empty($docType)||$docType == "html")? $format : $docType  );
    	
    	/** Response Formulator **/
    	TuiyoLoader::import("rest.response");
    	TuiyoLoader::import("rest.utility");
    	
    	
    	
    	$view->display();
    }	
	


    /**
     * TuiyoControllerProtocol::getStatus()
     * Determines the status of the request
     * @return void
     */
    private function getStatus( $status )
    {}
}
