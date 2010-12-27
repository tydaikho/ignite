<?php
/**
 * Session Class
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
 
 
 class TuiyoSession extends TuiyoUser{
 	
 	public function TuiyoSession($handler = 'DB')
	{
		parent::TuiyoUser();
		self::setHandler( $handler );
	}
 	
 	public function createNew()
 	{}
 	 
 	public function start()
 	{}
 	
 	public function end()
 	{}
 	
 	public function getSession()
 	{}
 	
 	public function getSessionID()
 	{}
 	
 	public function getDuration()
 	{}
 	
 	public function getData()
 	{}
 	
 	public function reStart()
 	{}
 	
 	public function destroy()
 	{}
 	
	public function setHandler( $hanler )
	{ }
	
	private function _setError( $error )
	{}
	
	private function _createKey( )
	{} 	
	
	private function _gc()
	{ /** garbage collection */ }
	
	private function _write()
	{}
	
	public function getOnlineLists()
	{}
	
	
 }