<?php
/**
 * ******************************************************************
 * Tuiyo application                     *
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
 * TuiyoRestRequest
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoRestRequest{
	
	private $request_vars;
	private $data;
	private $http_accept;
	private $method;

	/**
	 * TuiyoRestRequest::__construct()
	 * Constructs the request
	 * @return
	 */
	public function __construct()
	{
		$this->requestVars		= array();
		$this->data				= '';
		$this->httpAccept		= (strpos($_SERVER['HTTP_ACCEPT'], 'json')) ? 'json' : 'xml';
		$this->method			= 'get';
		
		
	}

	/**
	 * TuiyoRestRequest::setData()
	 * Sets all data passed with Request
	 * @param mixed $data
	 * @return
	 */
	public function setData($data)
	{
		$this->data = $data;
	}

	/**
	 * TuiyoRestRequest::setMethod()
	 * Sets the Request Method
	 * @param mixed $method
	 * @return
	 */
	public function setMethod($method)
	{
		$this->method = $method;
	}

	/**
	 * TuiyoRestRequest::setRequestVars()
	 * Sets the Request variables
	 * @param mixed $requestVars
	 * @return
	 */
	public function setRequestVars($requestVars)
	{
		$this->requestVars = $requestVars;
	}

	/**
	 * TuiyoRestRequest::getData()
	 * Gets all stored data from Request
	 * @return
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * TuiyoRestRequest::getMethod()
	 * Gets the stored Method Requested;
	 * @return
	 */
	public function getMethod()
	{
		return $this->method;
	}

	/**
	 * TuiyoRestRequest::getHttpAccept()
	 * HTTP Accept status
	 * @return
	 */
	public function getHttpAccept()
	{
		return $this->httpAccept;
	}

	/**
	 * TuiyoRestRequest::getRequestVars()
	 * Returns all request variables in the request
	 * @return
	 */
	public function getRequestVars()
	{
		return $this->requestVars;
	}
	
	
	public function getInstance(){}
	
}
