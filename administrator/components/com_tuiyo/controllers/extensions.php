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


jimport('joomla.application.component.controller');

/**
 * TuiyoControllerExtensions
 * 
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoControllerExtensions extends JController{
	
	var $docu 	= null;

	/**
	 * TuiyoControllerExtensions::__construct()
	 * Constructor
	 * @return
	 */
	public function __construct(){
		
		//Set the View Intricately
		if (!JRequest::getCmd( 'view') ) {
         	JRequest::setVar('view', 'extensions' );
      	}
      	$this->docu	=& JFactory::getDocument();
      	
      	//Construct parent;
		parent::__construct();
		
	}	

	/**
	 * TuiyoControllerExtensions::display()
	 * Default extension context display
	 * @return
	 */
	public function display(){
		
		$adminView 	= $this->getView("tuiyo" , "html");
		$extView 	= $this->getView("extensions" , "html" );
	
		/*Do Some Plugin Majical Stuff Here */	
		$plugins 	= $extView->showExtensions( );
		
		$adminView->display( $plugins );
		$this->docu->addScript( "components/com_tuiyo/style/script/extensions.js" ); 
		
		//Events?
	}
	

	/**
	 * TuiyoControllerExtensions::getWidgets()
	 * Ajax display all widgetes installed
	 * @return
	 */
	public function getWidgets(){
		
		$adminView 	= $this->getView("tuiyo" , "json");
		$extView 	= $this->getView("extensions" , "html" );

		/*Do Some Plugin Majical Stuff Here */
		$resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => false, 
			"data" 	=> false,
			"extra"	=> false 
		);	
				
		$adminView->encode( $resp );
	}
	

	/**
	 * TuiyoControllerExtensions::getDesigns()
	 * Get all system designs via AJAX
	 * @return json
	 */
	public function getDesigns(){
		
		$adminView 	= $this->getView("tuiyo" , "json");
		$extView 	= $this->getView("extensions" , "html" );

		/*Do Some Plugin Majical Stuff Here */
		$resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => false, 
			"data" 	=> false,
			"extra"	=> false 
		);	
				
		$adminView->encode( $resp );
	}

	/**
	 * TuiyoControllerExtensions::getPlugins()
	 * Gets a list of all system plug-ins via AJAX
	 * @return json
	 */
	public function getPlugins(){
		
		$adminView 	= $this->getView("tuiyo" , "json");
		$extView 	= $this->getView("extensions" , "html" );

		/*Do Some Plugin Majical Stuff Here */
		$resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => false, 
			"data" 	=> false,
			"extra"	=> false 
		);	
				
		$adminView->encode( $resp );
	}

	/**
	 * TuiyoControllerExtensions::getLanguages()
	 * Gets all installed Languages via ajax
	 * @return json
	 */
	public function getLanguages(){
		
		$adminView 	= $this->getView("tuiyo" , "json");
		$extView 	= $this->getView("extensions" , "html" );
		$resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => false, 
			"data" 	=> false,
			"extra"	=> false 
		);	
				
		$adminView->encode( $resp );	
	}	
	

	/**
	 * TuiyoControllerExtensions::getApplications()
	 * Gets all installed applications via AJAX
	 * @return json
	 */
	public function getApplications(){
		
		$adminView 	= $this->getView("tuiyo" , "json");
		$extView 	= $this->getView("extensions" , "html" );
		$extModel	= $this->getModel("tuiyo" );
		
		$resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => false, 
			"data" 	=> false,
			"extra"	=> false 
		);	
		
		/*Do Some Plugin Majical Stuff Here */
		$applications	= $extModel->getApplications();
		$resp["data"]	= $applications ;
		$resp["extra"] 	= $extView->getApplicationList( $applications );
		
		$adminView->encode( $resp );	
	}
	

	/**
	 * TuiyoControllerExtensions::doInstall()
	 * Installs an extension
	 * @return
	 */
	public function doInstall(){

		$adminView 	= $this->getView("tuiyo" , "html");
		$extView 	= $this->getView("extensions" , "html" );
	
		/*Do Some Plugin Majical Stuff Here */
		
	}	
	
	/**
	 * TuiyoControllerExtensions::editApplication()
	 * Edits an application
	 * @return void
	 */
	public function editApplication(){
		
		$adminView 	= &$this->getView("tuiyo" , "html");
		$extView 	= &$this->getView("extensions" , "html" );
		$extModel 	= &$this->getModel("extensions");
		$appModel 	= &TuiyoLoader::model("applications" , true );
		$aName 		= JRequest::getVar('a', null );
		$childDo 	= Jrequest::getVar('childDo', null );
		
		
		$aName 		= (!empty($aName)) ? strval(strtolower($aName)) 
					: JError::raiseError(TUIYO_SERVER_ERROR, 'unspecified application');
		
		$pData 		= array(
			"params"=>	"",
			"data"	=> 	$appModel->getSingleApplication( $aName )
		); 					
		
  		$pageView	= $extView->appEditpage(  $pData );

        $adminView->display( $pageView );
        $this->docu->addScript("components/com_tuiyo/style/script/applications.js");
		
	}	

}