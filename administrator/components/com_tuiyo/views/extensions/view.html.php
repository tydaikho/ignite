<?php
/**
 * ******************************************************************
 * Tuiyo Application entry                                          *
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
defined('_JEXEC') || die('Restricted access');

jimport( 'joomla.application.component.view' );


/**
 * TuiyoViewTuiyo
 * 
 * @package Livingstone Workspace
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoViewExtensions extends JView
{
	

	/**
	 * TuiyoViewExtensions::showExtensions()
	 * Default Extension view page
	 * @return
	 */
	public function showExtensions(){
		
		$TMPL = $GLOBALS["API"]->get("document");
		$TMPL->IconPath = $iconPath;
		
		
		$tmplVars 		= array(
			"styleDir"	=>$livestyle,
			"livePath"	=>TUIYO_LIVE_PATH,
			"iconPath" 	=>TUIYO_LIVE_PATH.'/client/default/'
		);
		
		$tmplPath 		= JPATH_COMPONENT_ADMINISTRATOR.DS."views".DS."extensions".DS."tmpl" ;
		$tmplData 	    = $TMPL->parseTmpl("default" , $tmplPath , $tmplVars);
		
		return $tmplData;
	}	
	
	/**
	 * TuiyoViewExtensions::getApplicationList()
	 * Gets a list of all installed applications
	 * @param mixed $data
	 * @return
	 */
	public function getApplicationList( $data ){
			
		$TMPL = $GLOBALS["API"]->get("document");
		$TMPL->IconPath = $iconPath;
		
		$tmplVars 		= array(
			"apps"		=>$data, 
			"styleDir"	=>$livestyle,
			"livePath"	=>TUIYO_LIVE_PATH,
			"iconPath" 	=>TUIYO_LIVE_PATH.'/client/default/'
		);
		
		$tmplPath 		= JPATH_COMPONENT_ADMINISTRATOR.DS."views".DS."extensions".DS."tmpl" ;
		$tmplData 	    = $TMPL->parseTmpl("list" , $tmplPath , $tmplVars);
		
		return $tmplData;	
		
	}
	
	
	/**
	 * TuiyoViewExtensions::appEditPage()
	 * Application editing page
	 * @param mixed $data
	 * @return void
	 */
	public function appEditPage( $data = null )
	{
		$TMPL = $GLOBALS["API"]->get("document");
		$TMPL->IconPath = $iconPath;
		
		$tmplVars 		= array(
			"appData"	=>$data, 
			"styleDir"	=>$livestyle,
			"livePath"	=>TUIYO_LIVE_PATH,
			"iconPath" 	=>TUIYO_LIVE_PATH.'/client/default/'
		);
		
		$tmplPath 		= JPATH_COMPONENT_ADMINISTRATOR.DS."views".DS."extensions".DS."tmpl" ;
		$tmplData 	    = $TMPL->parseTmpl("application" , $tmplPath , $tmplVars);
		
		return $tmplData;	
	}
}