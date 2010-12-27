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


class TuiyoControllerCommunityManagement extends JController{

	var $docu 	= null;

	public function __construct(){
		//Set the View Intricately
		if (!JRequest::getCmd( 'view') ) {
         	JRequest::setVar('view', 'tuiyo' );
      	}
      	$this->docu	=& JFactory::getDocument();
      	//Construct parent;
		parent::__construct();
	}
	
	/**
	 * Default community managment action
	 * TuiyoControllerCommunityManagement::display()
	 * @return void
	 */
	public function display(){
		
		$adminView 		= $this->getView("tuiyo", "html");
		$communityView 	= $this->getView("community" , "html");
		
		$userMgmnt 		= $communityView->display( );
		
		$adminView->display( $userMgmnt );
		$this->docu->addScript( "components/com_tuiyo/style/script/community.js" ); 
	}
	
	public function getUserList(){
		
		$adminView 	= $this->getView("tuiyo" , "json");
		$cmtyView 	= $this->getView("community" , "html" );
		$cmtyModel	= $this->getModel("communityManagement" );
		
		$resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => false, 
			"data" 	=> false,
			"extra"	=> false 
		);	
		/*Do Some Plugin Majical Stuff Here */
		$fields 		= array( 
			"u"=>array( "id", "name", "email", "username",  "gid",  "lastVisitDate"),
		   	"p"=>array("profileId", "dateCreated", "sex",  "suspended")
		);
		$userList		= $cmtyModel->getUsers( $fields , true );
		$resp["data"]	= $userList;
		$resp["extra"] 	= $cmtyView->buildUserList( $userList  );
		
		$adminView->encode( $resp );
	}
	
	public function getCategories(){
		
		$adminView 	= $this->getView("tuiyo" , "html");
		$adminWindow= $adminView->categoryManager();
		
		return $adminView->display( $adminWindow );
	}
	
	
	public function getProfile(){
		
		$adminView 	= $this->getView("tuiyo" , "json");
		$cmtyView 	= $this->getView("community" , "html" );
		$cmtyModel	= $this->getModel("communityManagement" );
		$userID		= JRequest::getVar("userid");
		
		$resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => false, 
			"data" 	=> false,
			"extra"	=> false 
		);
		
		//$userNotes 	= $this->getUserReports();
		//$userDetails= $this->getUserProfile();
		
		$userProfile 	= array();
		$resp["extra"] 	= $cmtyView->buildUserMiniProfile( $userProfile );
		
		return $adminView->encode( $resp );
	}
	
	public function getGroups(){
		
		$adminView 	= $this->getView("tuiyo" , "html");
		$cmtyView 	= $this->getView("community" , "html" );
        /** Do nothing majical **/

        $groups = $cmtyView->showGroupWindow( );

        $adminView->display( $groups );
        //Add JS
		$this->docu->addScript( "components/com_tuiyo/style/script/community.js" ); 
	}
	
	
	public function getSuspendedProfile(){
		
		$adminView 	= $this->getView("tuiyo" , "json");
		$cmtyView 	= $this->getView("community" , "html" );
		$cmtyModel	= $this->getModel("communityManagement" );
		
		$resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => false, 
			"data" 	=> false,
			"extra"	=> false 
		);	
		/*Do Some Plugin Majical Stuff Here */
		$fields 		= array( 
			"u"=>array( "id", "name", "email", "username",  "gid",  "lastVisitDate"),
		   	"p"=>array("profileId", "dateCreated", "sex",  "suspended")
		);
		$userList		= $cmtyModel->getUsers( $fields , true );
		$resp["data"]	= $userList;
		$resp["extra"] 	= $cmtyView->buildUserList( $userList  );
		
		$adminView->encode( $resp );

	}
	
	public function moderationPanel(){
		
		$adminView 	= $this->getView("tuiyo" , "json");
		$cmtyView 	= $this->getView("community" , "html" );
		$cmtyModel	= $this->getModel( "communityManagement" );
		
		$resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => false, 
			"data" 	=> false,
			"extra"	=> false 
		);	
		/*Do Some Plugin Majical Stuff Here */
		
		$userReportList	= $cmtyModel->getUsersReports();
		$resp["extra"] 	= $cmtyView->buildUserReportList( $userReportList );
		
		$adminView->encode( $resp );
				
	}
	
}