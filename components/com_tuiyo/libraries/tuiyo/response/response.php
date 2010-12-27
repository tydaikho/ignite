<?php
/**
 * ******************************************************************
 * Resonse object for the Tuiyo platform                           *
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
  * TuiyoResponse
  * 
  * @package   
  * @author tuiyo
  * @copyright Livingstone Fultang
  * @version 2009
  * @access public
  */
 class TuiyoResponse{
 	
 	/**
 	 * Content Type 
 	 */
 	private $_contentType = 'text/html';
 	
 	/**
 	 * TuiyoResponse::TuiyoResponse()
 	 * 
 	 * @return
 	 */
 	public function TuiyoResponse()
 	{}
 	
 	/**
 	 * TuiyoResponse::setHeaders()
 	 * 
 	 * @return
 	 */
 	public function setHeaders()
 	{}
 	
 	/**
 	 * TuiyoResponse::getHeaders()
 	 * 
 	 * @return
 	 */
 	public function getHeaders()
 	{}
 	
 	/**
 	 * TuiyoResponse::getDocument()
 	 * 
 	 * @return
 	 */
 	public function getDocument()
 	{}
 	
 	/**
 	 * TuiyoResponse::getJSON()
 	 * 
 	 * @return
 	 */
 	public function getJSON()
 	{}
 	
 	/**
 	 * TuiyoResponse::getXML()
 	 * 
 	 * @return
 	 */
 	public function getXML()
 	{}
 	
 	/**
 	 * TuiyoResponse::setContentType()
 	 * 
 	 * @return
 	 */
 	public function setContentType()
 	{}
 	
 	/**
 	 * TuiyoResponse::flush()
 	 * 
 	 * @return
 	 */
 	public function flush()
 	{}
 }