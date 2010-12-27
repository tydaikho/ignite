<?php
/**
 * ******************************************************************
 * Widgets Controller for Tuiyo application                               *
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
 * Tuiyo Controller
 */
TuiyoLoader::controller('core');
/**
 * TuiyoControllerWidgets
 * 
 * @package tuiyo
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoControllerWidgets extends JController
{
    /**
     * TuiyoControllerWidgets::__construct()
     * @return void
     */
    function __construct()
    {
        //Tuiyo controller
        TuiyoControllerCore::init("Widgets" , true);
        //Component only
        JRequest::setVar('tmpl' , 'component');
        //Joomla controller
        parent::__construct();
    }	
	
	/**
	 * Method to display the view
	 * Display Page
	 * @access	public
	 */
	function display()
	{
		global $API, $mainframe;
		
		$view = &$this->getView('widgets' , "html" );
		$user = &$API->get( 'user' );
		
		$view->assignRef("user" , $user );
		$view->setLayoutExt('tpl');
		
		$mainframe->addMetaTag( "pid" , $user->id );
		$mainframe->setPageTitle( $user->name." | Private widget page" );
		
		$view->display();

	}
	
	/**
	 * Gets the add content Panel of the widget page
	 * TuiyoControllerWidgets::addContentPanel()
	 * @return json
	 */
	public function addContentPanel()
	{
		$document 	= $GLOBALS['API']->get("document");
		$auth 		= $GLOBALS['API']->get( 'authentication' );
		
		$auth->requireAuthentication(); //Must be logged In	
				
		if($document->getDOCTYPE() !== "json"){
			$GLOBALS['mainframe']->redirect(TUIYO_PROFILE_INDEX);	
		}
		
		$view 	= $this->getView('widgets', "json" );
		$model  = $this->getModel('widgets' );
		
		$user 	= $GLOBALS['API']->get( 'user' );
		
		//Response Array
  		$resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => null, 
			"data" 	=> $model->getAllWidgets( ), 
			"extra" => null
		);
		
		//Get the HTML
		return $view->contentPanel( $resp );
	}
	
	public function addTabToPage()
	{

		$auth 		= $GLOBALS['API']->get( 'authentication' );
		$view 		= $this->getView("widgets", "json" );
		$model 		= $this->getModel("widgets");

		$auth->requireAuthentication(); //Must be logged In
		
		$user 		= $GLOBALS['API']->get( 'user' );
		
		$postData 	= JRequest::get( "post" );
		$savedData 	= $model->addTab( $postData , $user );

  		$resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => null, 
			"data" 	=> $savedData, 
			"extra" => null
		);
		
		return $view->encode( $resp  );
	}
	
	public function removeTabFromPage()
	{

		$view 		= $this->getView("widgets", "json" );
		$model 		= $this->getModel("widgets");
		$auth 		= $GLOBALS['API']->get( 'authentication' );
		
		$auth->requireAuthentication(); //Must be logged In			
		
		$user 		= $GLOBALS['API']->get( 'user' );
		
		$postData 	= JRequest::get( "post" );
		$removeData = $model->removeTab( $postData , $user );
		
		return $view->encode( array(
			"code" 	=> TUIYO_OK, 
			"error" => null, 
		) );
	}
	
	public function getWidgetPageLayout()
	{

		$view 		= $this->getView("widgets", "json" );
		$model 		= $this->getModel("widgets");
		$auth 		= $GLOBALS['API']->get( 'authentication' );
		
		$auth->requireAuthentication(); //Must be logged In	
		
		$user 		= $GLOBALS['API']->get( 'user' , NULL );
		
		$widgetPage = $model->getMyPage( $user->id );
		
		return $view->encode( array(
			"code" 	=> TUIYO_OK, 
			"error" => null, 
			"data"	=> $widgetPage
		) );
	}
	
	public function addWidgetToTab()
	{}
	
	public function removeWidgetFromTab()
	{}
	
	/**
	 * Authorises a user to run the given controller!
	 * TuiyoControllerCore::authorise()
	 * 
	 * @return void
	 */
	public function authorise()
	{	
		if(!JRequest::checkToken("request")){
			JError::raiseError( TUIYO_NAI , _("Invalid token") );		
		}		
	}	

}
