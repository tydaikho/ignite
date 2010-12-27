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
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoViewTuiyo extends JView
{
	/**
	 * TuiyoViewTuiyo::categoryManager
	 * Default function to display the category manager
	 * @param mixed $tpl
	 * @return html
	 */
	public function categoryManager($tpl=null){
		
		$TMPL 	= 	$GLOBALS["API"]->get("document");
		$MODEL 	=	TuiyoLoader::model("categories", true);	
		$USER	= 	TuiyoAPI::get("user");
		$ACL 	=   JFactory::getACL();
	
		//Get the Categories		
		$gtree 	= $ACL->get_group_children_tree( null, 'USERS', false );
		$aroGrps= JHTML::_('select.genericlist',   $gtree, 'gid', 'class="TuiyoFormDropDown"', 'value', 'text', $USER->joomla->get('gid') );
			
		$tmplVars 		= array(
			"styleDir"	=>$livestyle,
			"livePath"	=>TUIYO_LIVE_PATH,
			"iconPath" 	=>TUIYO_LIVE_PATH.'/client/default/',
			"user"		=>JFactory::getUser(),
			"nodes"		=>$MODEL->getCategories(),
			"arogrps"	=>$aroGrps
		);
	
		$tmplPath 		= JPATH_COMPONENT_ADMINISTRATOR.DS."views".DS."tuiyo".DS."tmpl" ;
		$tmplData 	    = $TMPL->parseTmpl("categories" , $tmplPath , $tmplVars);
		
		return $tmplData;		
		
	}
	/**
	 * TuiyoViewTuiyo::display()
	 * Default welcome page for admin view
	 * @param mixed $tpl
	 * @return
	 */
	public function display( $tpl = null )
	{

		$TMPL 		=   $GLOBALS['API']->get( 'document' );
		$USER		= 	$GLOBALS['API']->get('user', null);
		$DOCU 		=   JFactory::getDocument();
		$MODEL 		= 	TuiyoLoader::model('applications', true );
		$APPS		=   $MODEL->getApplicationExtendedList();
		
		$DOCU->addStyleSheet('components/com_tuiyo/style/css/common.css' );
		$DOCU->addScript( 'components/com_tuiyo/style/script/global.js' );
		$DOCU->addScript( TUIYO_OEMBED );
		$DOCU->addScript( TUIYO_STREAM );
		
		$version 		= TuiyoLoader::helper("version");		
		$document 		= $GLOBALS['API']->get("document");
		
		if(!is_a($version, 'TuiyoVersion')){
			$version 	=& new TuiyoVersion() ;
		}
		$longVersion	= $version->getLongVersion();
		
		$plugins		= $MODEL->getAllSystemPlugins("services", false); 
		
		$tmplPath2 		= TUIYO_VIEWS.DS."profile".DS."tmpl" ;
		$tmplVars2 		= array(
			"styleDir"	=>TUIYO_STYLEDIR,
			"user"		=>$USER,
			"sharewith" =>array("p00"=>"@everyone"),
		    "plugins"   => $plugins,
			"canPost"	=> 0 			
		);
		$activity 		= $TMPL->parseTmpl("activity" , $tmplPath2 , $tmplVars2);
		

		$tmplVars 		= array(
			"apps"		=>$APPS,
			"activity"	=>$activity,
			"styleDir"	=>$livestyle,
			"livePath"	=>TUIYO_LIVE_PATH,
			"iconPath" 	=>TUIYO_LIVE_PATH.'/client/default/'
		);		
		
		$tmplVars["version"]	=	$longVersion;
		
		$tmplPath 		= JPATH_COMPONENT_ADMINISTRATOR.DS."views".DS."tuiyo".DS."tmpl" ;
		$tplData		= (!is_null($tpl)) ? $tpl : $TMPL->parseTmpl( "default", $tmplPath, $tmplVars );
		
		$tmplVars["adminPage"]	=	$tplData;
		
		$content 	    = $TMPL->parseTmpl("admin" , $tmplPath , $tmplVars);
		
		echo $content;
	}
	
	/**
	 * TuiyoViewTuiyo::showStatsWindow()
	 * Gets google analytics statistics + tuiyo stats
	 * @param mixed $data
	 * @return
	 */
	public function showStatsWindow( $data ){
		
		$TMPL = $GLOBALS["API"]->get("document");
		$TMPL->IconPath = $iconPath;
		
		//Google Analytics stats
		$GAPI = TuiyoLoader::loadAPI( );
		
		
		
		$tmplVars 		= array(
			"styleDir"	=>$livestyle,
			"livePath"	=>TUIYO_LIVE_PATH,
			"iconPath" 	=>TUIYO_LIVE_PATH.'/client/default/',
			"user"		=>JFactory::getUser()
		);
		$tmplPath 		= JPATH_COMPONENT_ADMINISTRATOR.DS."views".DS."tuiyo".DS."tmpl" ;
		$tmplData 	    = $TMPL->parseTmpl("statistics" , $tmplPath , $tmplVars);
		
		return $tmplData;
	}
	
	/**
	 * TuiyoViewTuiyo::showFieldsManager()
	 * Shows a custom fields manager
	 * @param mixed $data
	 * @return
	 */
	public function showFieldsManager( $data = null ){
			
		$TMPL = $GLOBALS["API"]->get("document");
		$TMPL->IconPath = $iconPath;
		
		//Google Analytics stats
		$GAPI = TuiyoLoader::loadAPI( );
		
		$tmplVars 		= array(
			"styleDir"	=>$livestyle,
			"livePath"	=>TUIYO_LIVE_PATH,
			"iconPath" 	=>TUIYO_LIVE_PATH.'/client/default/',
			"user"		=>JFactory::getUser()
		);
		$tmplPath 		= JPATH_COMPONENT_ADMINISTRATOR.DS."views".DS."tuiyo".DS."tmpl" ;
		$tmplData 	    = $TMPL->parseTmpl("customfields" , $tmplPath , $tmplVars);
		
		return $tmplData;
		
	}

	/**
	 * TuiyoViewTuiyo::showBugReportForm()
	 * Bug reporting form
	 * @param mixed $data
	 * @return html
	 */
	public function showBugReportForm( $data  = null){
		
		$TMPL = $GLOBALS["API"]->get("document");
		$TMPL->IconPath = $iconPath;
		
		
		$tmplVars 		= array(
			"styleDir"	=>$livestyle,
			"livePath"	=>TUIYO_LIVE_PATH,
			"iconPath" 	=>TUIYO_LIVE_PATH.'/client/default/',
			"user"		=>JFactory::getUser()
		);
		$tmplPath 		= JPATH_COMPONENT_ADMINISTRATOR.DS."views".DS."tuiyo".DS."tmpl" ;
		$tmplData 	    = $TMPL->parseTmpl("bugreport" , $tmplPath , $tmplVars);
		
		return $tmplData;
	}
	
	/**
	 * TuiyoViewTuiyo::showAutoCenter()
	 * Automation center view
	 * @param mixed $data
	 * @return
	 */
	public function showAutoCenter( $macro = null){
		
		$TMPL = $GLOBALS["API"]->get("document");
		$TMPL->IconPath = $iconPath;			
		
		$tmplVars 		= array(
			"styleDir"	=>$livestyle,
			"livePath"	=>TUIYO_LIVE_PATH,
			"iconPath" 	=>TUIYO_LIVE_PATH.'/client/default/',
			"user"		=>JFactory::getUser()
		);		
		
		if(!empty($macro)):			
			$macroObj 	= TuiyoLoader::macro( (string)$macro , true );
			if(is_object($macroObj)):	
				$tmplVars["macro"] = $macroObj;
			endif; 			
		endif ;
		
		$tmplPath 		= JPATH_COMPONENT_ADMINISTRATOR.DS."views".DS."tuiyo".DS."tmpl" ;
		$tmplData 	    = $TMPL->parseTmpl("automation" , $tmplPath , $tmplVars);
		
		return $tmplData;
	}
	
	
	/**
	 * TuiyoViewTuiyo::showConfigWindow()
	 * Global Configuration elements view
	 * @param mixed $data
	 * @return html
	 */
	public function showConfigWindow( $data = null ){
		
		$TMPL = $GLOBALS["API"]->get("document");
		$TMPL->IconPath = $iconPath;
		
		$tmplVars 		= array(
			"styleDir"	=>$livestyle,
			"livePath"	=>TUIYO_LIVE_PATH,
			"iconPath" 	=>TUIYO_LIVE_PATH.'/client/default/',
			"user"		=>JFactory::getUser(),
			"apps"		=>$data["APPS"],
			"e"			=>$data["params"]
		);
		$tmplPath 		= JPATH_COMPONENT_ADMINISTRATOR.DS."views".DS."tuiyo".DS."tmpl" ;
		
		$tmplData 	    = $TMPL->parseTmpl("config" , $tmplPath , $tmplVars);
		
		return $tmplData;
		
	}
	
	/**
	 * TuiyoViewTuiyo::showSystemEmailForm()
	 * Shows a form to edit system emails
	 * @param mixed $data
	 * @return html
	 */
	public function showSystemEmailForm($data = null){

		$TMPL = $GLOBALS["API"]->get("document");
		$TMPL->IconPath = $iconPath;
		
		$tmplVars 		= array(
			"styleDir"	=>$livestyle,
			"livePath"	=>TUIYO_LIVE_PATH,
			"iconPath" 	=>TUIYO_LIVE_PATH.'/client/default/',
			"user"		=>JFactory::getUser(),
			"apps"		=>$data["APPS"] ,
			"e"			=>$this->e 
		);
		$tmplPath 		= JPATH_COMPONENT_ADMINISTRATOR.DS."views".DS."tuiyo".DS."tmpl" ;
		$tmplData 	    = $TMPL->parseTmpl("emails" , $tmplPath , $tmplVars);
		
		return $tmplData;		
		
	}	
}