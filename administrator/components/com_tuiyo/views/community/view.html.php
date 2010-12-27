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


jimport('joomla.application.component.view');


class TuiyoViewCommunity extends JView{
	
	
	public function display( $data = null ){
		
		$TMPL = $GLOBALS["API"]->get("document");
		
		$tmplVars 		= array(
			"styleDir"	=>$livestyle,
			"livePath"	=>TUIYO_LIVE_PATH,
			"iconPath" 	=>TUIYO_LIVE_PATH.'/client/default/',
			"user"		=>JFactory::getUser(),
			"data"		=>(!is_null($data))? $data : "" 
		);
		$tmplPath 		= JPATH_COMPONENT_ADMINISTRATOR.DS."views".DS."community".DS."tmpl" ;
		$tmplData 	    = $TMPL->parseTmpl("default" , $tmplPath , $tmplVars);
		
		return $tmplData;
	}
	
	public function buildUserList( $userListData ){
		
		$TMPL = $GLOBALS["API"]->get("document");

		$tmplVars 		= array(
			"styleDir"	=>$livestyle,
			"livePath"	=>TUIYO_LIVE_PATH,
			"iconPath" 	=>TUIYO_LIVE_PATH.'/client/default/',
			"users"		=>$userListData 
		);
		
		$tmplPath 		= JPATH_COMPONENT_ADMINISTRATOR.DS."views".DS."community".DS."tmpl" ;
		$tmplData 	    = $TMPL->parseTmpl("list" , $tmplPath , $tmplVars);	
		
		return $tmplData;
		
	}
	
	public function buildUserReportList( $userReports ){
		
		$TMPL = $GLOBALS["API"]->get("document");

		$tmplVars 		= array(
			"styleDir"	=>$livestyle,
			"livePath"	=>TUIYO_LIVE_PATH,
			"iconPath" 	=>TUIYO_LIVE_PATH.'/client/default/',
			"users"		=>$userListData 
		);
		
		$tmplPath 		= JPATH_COMPONENT_ADMINISTRATOR.DS."views".DS."community".DS."tmpl" ;
		$tmplData 	    = $TMPL->parseTmpl("reportlist" , $tmplPath , $tmplVars);	
		
		return $tmplData;
	}
	
	public function buildUserMiniProfile( $userData ){
		
		$TMPL = $GLOBALS["API"]->get("document");

		$tmplVars 		=  array(
			"styleDir"	=> $livestyle,
			"livePath"	=> TUIYO_LIVE_PATH,
			"iconPath" 	=> TUIYO_LIVE_PATH.'/client/default/',
			"users"		=> $userData
		);
		
		$tmplPath 		= JPATH_COMPONENT_ADMINISTRATOR.DS."views".DS."community".DS."tmpl" ;
		$tmplData 	    = $TMPL->parseTmpl("miniprofile" , $tmplPath , $tmplVars);	
		
		return $tmplData;
	}
	
	public function buildCategoryDirectory( $categories ){
		
		$TMPL = $GLOBALS["API"]->get("document");

		$tmplVars 		=  array(
			"styleDir"	=> $livestyle,
			"livePath"	=> TUIYO_LIVE_PATH,
			"iconPath" 	=> TUIYO_LIVE_PATH.'/client/default/',
			"categories"=> $categories
		);
		
		$tmplPath 		= JPATH_COMPONENT_ADMINISTRATOR.DS."views".DS."community".DS."tmpl" ;
		$tmplData 	    = $TMPL->parseTmpl("groupscats" , $tmplPath , $tmplVars);	
		
		return $tmplData;
	}
	
	public function showGroupWindow($data = NULL){
		
		$TMPL = $GLOBALS["API"]->get("document");
		
		$tmplVars 		= array(
			"styleDir"	=>$livestyle,
			"livePath"	=>TUIYO_LIVE_PATH,
			"iconPath" 	=>TUIYO_LIVE_PATH.'/client/default/',
			"user"		=>JFactory::getUser()
		);
		$tmplPath 		= JPATH_COMPONENT_ADMINISTRATOR.DS."views".DS."community".DS."tmpl" ;
		$tmplData 	    = $TMPL->parseTmpl("groups" , $tmplPath , $tmplVars);
		
		return $tmplData;
	}
}