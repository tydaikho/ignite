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
TuiyoLoader::helper("parameter");

/**
 * TuiyoControllerSystemTools
 * 
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoControllerSystemTools extends JController
{
    /**
     * TuiyoControllerSystemTools::__construct()
     * @return
     */
    public function __construct()
    {
        //Set the View Intricately
        if (!JRequest::getCmd('view')) {
            JRequest::setVar('view', 'tuiyo');
        }
        $this->docu = &JFactory::getDocument();
        //Construct the parent now
        parent::__construct();
    }

    /**
     * TuiyoControllerSystemTools::editEmails()
     * A form to edit all outgoing system Emails
     * @return page
     */
    public function editEmails()
    {
        $view 			= $this->getView("tuiyo", "html");
		$emailParams 	= TuiyoParameter::load("emails");
		
        /** Do nothing majical **/
        $view->assignRef( "e" , $emailParams);

        $form 	= $view->showSystemEmailForm( );

        $view->display($form );
    }

    /**
     * TuiyoControllerSystemTools::statistics()
     * Display collected system statistics where needed
     * @return page
     */
    public function statistics()
    {
        $view 	= $this->getView("tuiyo", "html");
        $data 	= null;
        $stats 	= $view->showStatsWindow( $data );

        $view->display( $stats );
        $this->docu->addScript("components/com_tuiyo/style/script/config.js");
    }

    /**
     * TuiyoControllerSystemTools::userFields()
     * A form for managing and editing custom 
     * user fields
     * @return
     */
    public function userFields()
    {
        $view 	= $this->getView("tuiyo", "html");
        $view 	= $this->getView("tuiyo", "html");
        $data 	= null;
        $stats 	= $view->showFieldsManager( $data );
		$doc 	= $this->docu;
		
        $view->display( $stats );
        
		$doc->addScript( TUIYO_JS.'/includes/jqueryui/ui.core.js' );
		$doc->addScript( TUIYO_JS.'/includes/jqueryui/ui.draggable.js' );
		$doc->addScript( TUIYO_JS.'/includes/jqueryui/ui.sortable.js' );
        $doc->addScript("components/com_tuiyo/style/script/config.js");
    }

    /**
     * TuiyoControllerSystemTools::comTimeline()
     * Display an extensive view of community Timeline
     * With moderator tools
     * @return
     */
    public function comTimeline()
    {
        $view 	= $this->getView("tuiyo", "html");

        $view->display("community Timeline");
    }

    /**
     * TuiyoControllerSystemTools::globalConfig()
     * Global system configuration
     * @return
     */
    public function globalConfig()
    {
        $view 	= $this->getView("tuiyo", "html");
        //$model 	= $this->getModel("tuiyo");
		$MODEL 		= 	TuiyoLoader::model('applications', true );
        
        $data 	= array();

        /** Do something majical **/
        $data["APPS"] 			= $MODEL->getApplicationExtendedList(); 
		   
        $data["params"]			= TuiyoParameter::load("global");
        $data["photos_params"]	= TuiyoParameter::load("photos");
        $data["groups_params"]	= TuiyoParameter::load("groups");
		
        /** Do nothing majical **/
        $form 	= $view->showConfigWindow($data);

        $view->display($form);
        $this->docu->addScript("components/com_tuiyo/style/script/config.js");

    }
    
    /**
     * TuiyoControllerSystemTools::saveConfig()
     * Saves all back end parameters to the parameter table
     * User id = 0
     * @return void
     * OBSULATE, USE SAVE CONFIG INSTEAD, Stoney, 06th March 2010
     */
    public function saveParams(){
    	
    	$postData 	= JRequest::get( "post" );			//Form Data
    	$referer 	= JRoute::_( TUIYO_INDEX.'&amp;context=communityManagement&amp;do=getCategories' );
    	$model 	  	= $this->getModel( "tuiyo" );		//Model
    	$paramKey 	= $postData["paramKey"];
    	$document 	= TuiyoAPI::get('document');
    	$params 	= TuiyoAPI::get("params", $paramKey );

		
		if(!$params->storeSystemParams( $paramKey , $postData)){
			$msg 	= "Could not save $paramKey data";
			$mType	= "error";
		}
		//Notice messages;	
		$msg 		= "$paramKey data saved successfully";
		$mType		= "notice";
			
    	//$document->enqueMessage( $msg , $mType );
    	$this->setRedirect($referer, $msg, $mType );
    	$this->redirect();

    }
    
    /**
     * TuiyoControllerSystemTools::saveConfig()
     * Saves User aswell as profile configuration Data;
     * @return void
     */
    public function saveConfig(){
		
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$type		= JRequest::getVar( 'configType', null, 'post' , 'string' );
		$key		= JRequest::getVar( 'configKey' , null ,  'post' , 'string' );
		$referer 	= JRequest::getVar( "HTTP_REFERER" , null, "SERVER" );//Referer
		
		//We must know the type and name of the email
		if(empty($type) || empty($key)){
			JError::raiseError( TUIYO_SERVER_ERROR,  _('You did not specify the config key, or type') );
			return false;
		}
		
		$document 	= $GLOBALS['API']->get("document");
		$auth		= $GLOBALS['API']->get('authentication');
		
		$resp = array(
			"code" 	=> TUIYO_OK, 
			"msg"	=> _("Your template parameters have been saved"),
			"error" => null, 
		);
		//Request authentication
		$auth->requireAuthentication();
		
		$user 		= $GLOBALS['API']->get( 'user', null );
		$params		= JRequest::getVar('params', array(), 'post', 'array') ;
		$path 		= TUIYO_CONFIG;
		
		print_R( $params );
		
		switch($type):
		 	case "system":
		 		$return = TuiyoParameter::saveParams($params , $key ,"system");
		 	break;
		 	default:
		 		JError::raiseError( TUIYO_SERVER_ERROR,  _('Invalid config type') );
				return false;
		 	break;
		endswitch;

		//Notice messages;	
		$msg 		= "$key $type data saved successfully";
		$mType		= "notice";
				
		if(!$return){
			$msg 		= "$key $type could not be saved";
			$mType		= "error";
		}	
    	//$document->enqueMessage( $msg , $mType );
    	$this->setRedirect($referer, $msg, $mType );
    	$this->redirect();
    }
    
    /**
     * TuiyoControllerSystemTools::getCustomFields()
     * Returns a list of all custom fields in DB;
     * @return json
     */
    public function getCustomFields()
	{
		$model 	  	= $this->getModel( "tuiyo" );		//Model
    	$view 		= $this->getView("tuiyo", "json");
    	
   		$resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => false, 
			"data" 	=> $model->getAllCustomFields( ),
			"extra"	=> false 
		);
    	
    	return $view->encode( $resp );
	}
    
    /**
     * TuiyoControllerSystemTools::saveCustomFields()
     * @return json
     */
    public function saveCustomFields(){
   		
	    $postData 	= JRequest::get( "post" );			//Form Data
    	$model 	  	= $this->getModel( "tuiyo" );		//Model
    	$view 		= $this->getView("tuiyo", "json");
 
    	$document 	= TuiyoAPI::get('document');
    	$validate 	= TuiyoAPI::get('validate');
		 
    	//Validate Post Data;
    	$newFiedDATA= array(
    	
    		"name" 	=> $validate->alphaNumeric( $postData['name'] ),
    		"label"	=> $validate->string( $postData["label"] ),
    		"type"	=> strtolower( $validate->string( $postData["type"] ) ),
    		
    		"indexed"	=> $postData["indexed"],
    		"visible"	=> $postData["visible"],
    		"required"	=> $postData["required"],
		);
    	
    	$newField 	= $model->addCustomField( $newFiedDATA );
		
		$resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => false, 
			"data" 	=> $newField,
			"extra"	=> false 
		);
    	
    	//Return JSON
    	$view->encode( $resp ); 
    }
    
    /**
     * TuiyoControllerSystemTools::deleteCustomField()
     * Deletes a custom field from the database;
     * @return
     */
    public function deleteCustomField()
	{
		$auth		= TuiyoAPI::get("authentication");
		$postData 	= JRequest::get("post" );
		$model 		= $this->getModel("tuiyo" );
		$view 		= $this->getView("tuiyo", "json");
		
		//Require authentication;
		$auth->requireAuthentication();
		
		$resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => false, 
			"data" 	=> null,
			"extra"	=> false 
		);
		
		if(!$model->deleteField($postData['fid'])){
			JError::raiseError("Could not delete the field");
			return false;
		}
		
		//Return response;
		return $view->encode( $resp );
	}
	
    /**
     * TuiyoControllerSystemTools::getSocialForm()
     * Method to get the social form
     * @return
     */
    public function getSocialForm()
	{
		$auth		= TuiyoAPI::get("authentication");
		$postData 	= JRequest::get("post" );
		$model 		= TuiyoLoader::model("profile" , true );
		$view 		= $this->getView("tuiyo", "json");
		
		//Require authentication;
		$auth->requireAuthentication();
		
		$resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => false, 
			"data" 	=> $model->buildSocialBookForm( false ),
			"extra"	=> false 
		);
		
		//Return response;
		return $view->encode( $resp );
	}	

    /**
     * TuiyoControllerSystemTools::getConfigEl()
     * 
     * @return json page
     */
    public function getConfigEl()
    {
        $key 	= JRequest::getVar("key");
        $view 	= $this->getView("tuiyo", "json");
        //Response Array
        $resp 	= array("code" => TUIYO_OK, "data" => null, "element" => 0);
        
        $params = TuiyoAPI::get("params", $key);
        $resp["element"] = $params->getForm();


        //Return the JSON
        $view->encode($resp);
    }

    /**
     * TuiyoControllerSystemTools::reportBug()
     * Report Bugs to the team portal
     * @return
     */
    public function reportBug()
    {
        $view = $this->getView("tuiyo", "html");

        /** Do something majical **/

        $form = $view->showBugReportForm();

        $view->display($form);
    }

    /**
     * TuiyoControllerSystemTools::autoCenter()
     * Tuiyo Automation center
     * @return
     */
    public function autoCenter()
    {
        $view 	= $this->getView("tuiyo", "html");
		$macro 	= JRequest::getVar("run", null );
		
        /** Do something majical **/

        $form = $view->showAutoCenter( $macro );

        $view->display( $form );
    }

    /**
     * TuiyoControllerSystemTools::sysUpdater()
     * System Updater
     * @return
     */
    public function sysUpdater()
    {
        $view = $this->getView("tuiyo", "html");

        $view->display("system updater");
    }


}
