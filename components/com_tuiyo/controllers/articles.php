<?php
/**
 * ******************************************************************
 * Articles controller object for the Tuiyo platform             *
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

defined('TUIYO_EXECUTE') || die;
/**
 * joomla Controller
 */
jimport('joomla.application.component.controller');
/**
 * Tuiyo Controller
 */
TuiyoLoader::controller('core');
/**
 * TuiyoControllerApps
 * @package tuiyo
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoControllerArticles extends JController
{
    /**
     * TuiyoControllerGroups::__construct()
     * @return void
     */
    function __construct()
    {
        TuiyoControllerCore::init("Articles" , FALSE );
        parent::__construct();
    }
    
    /**
     * Default method for the articles view
     * @see JController::display()
     */
	public function display( $tpl = null ){
		
		global $API;
		
		$view 	= $this->getView('articles', 'html');
		$user	= $API->get( "user" , null); 
		
		$view->assignRef("user" , $user );		
		$view->setLayout("default");
		$view->setLayoutExt("tpl");
		
		$view->display( $tpl );
	}
	
	public function read(){
		
		$aid 	= JRequest::getInt("aid", null);
		$view 	= $this->getView("articles", "html");
		
		//if we are not reading
		if(empty($aid)){
			return $this->display();
		}
		
		$view->readArticle( $aid );
	}
	
	public function saveArticle(){
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$auth 	= TuiyoAPI::get("authentication");
		$auth->requireAuthentication();
		
		$user 	= TuiyoAPI::get("user");
		$data 	= JRequest::get("post", JREQUEST_ALLOWRAW);
		
		$aModel  = TuiyoLoader::model("articles", true); 
		$msg 	 = _('The article has been saved successfully');
		
		if(!$aModel->editSaveArticle($user, $data)){
			$msg = $aModel->getError();
		}
		
		//Redirect on success;
		$referer = JRoute::_(TUIYO_INDEX.'&view=articles');
		
		$this->setRedirect($referer, $msg);	
		
	}
}