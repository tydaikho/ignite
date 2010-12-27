<?php
/**
 * ******************************************************************
 * Class/Object for the Tuiyo platform                           *
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
 * TuiyoControllerPhotos
 * 
 * @package tuiyo
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoControllerPhotos extends JController
{
    /**
     * TuiyoControllerPhotos::__construct()
     * @return void
     */
    public function __construct()
    {
        TuiyoControllerCore::init("Photos" , false);
        parent::__construct();
    }	
    
	/**
	 * Method to display the view
	 * Photo Page Home
	 * @access	public
	 */
	public function display($tpl=null)
	{
		global $API ;
	
		$view 	= $this->getView('photos' , "html" );
		$model 	= $this->getModel('photos');
		
		$user 	= $API->get( 'user' );
		
		$photos 	= $model->getPhotos( $user->id );
		$albums 	= $model->getAlbums( $user->id );
		$pagelist 	= $model->getState("pagination");
		
		$view->setLayoutExt('tpl');
		$view->assignRef("user", $user );
		$view->assignRef("photos" , $photos );
		$view->assignRef("albums", $albums );
		$view->assignRef("pagelist", $pagelist );
		
		$view->display();
		
	}
	
	/**
	 * Gets the add content Panel for photo organization
	 * TuiyoControllerPhotos::organizePanel()
	 * @return
	 */
	public function organizePanel()
	{
		$document 	= $GLOBALS['API']->get("document");
		
		$auth 	=&$GLOBALS['API']->get( 'authentication' ); 
		
		//User must be logged in
		$auth->requireAuthentication();
		
		if($document->getDOCTYPE() !== "json"){
			$GLOBALS['mainframe']->redirect(TUIYO_PROFILE_INDEX);	
		}
		
		$model = $this->getModel("photos");
		$view  = $this->getView('photos', "json" );
		$user  = $GLOBALS['API']->get( 'user' );
		
		//Response Array
  		$resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => null,
		    "albums"=> $model->getAlbums( $user->id ),
			"photos"=> $model->getPhotos( $user->id, 0 , null, TRUE, FALSE ), 
			"extra" => null
		);
		//getWidgtData;
		
		//Get the HTML
		return $view->showOrganizePanel( $resp );
	}
	
	/**
	 * TuiyoControllerPhotos::getAlbumPhotos()
	 * Gets all the photos within an album
	 * @return
	 */
	public function getAlbumPhotos( ){
		
		$document 	= $GLOBALS['API']->get("document");
		$user  		= $GLOBALS['API']->get( 'user' );
		
		if($document->getDOCTYPE() !== "json"){
			$GLOBALS['mainframe']->redirect(TUIYO_PROFILE_INDEX);	
		}
		
		$model 		= $this->getModel("photos");
		$view  		= $this->getView('photos', "json" );
		
		$aid 		= JRequest::getInt("aid", null );
		
		//TODO: Check Privacy of album

		//Response Array
  		$resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => null,
			"album" => $model->getSingleAlbum( $userID , (int)$aid ),
			"photos"=> $model->getPhotos( $user->id, (int)$aid , null, TRUE, FALSE ), 
			"extra" => null
		);
		//getWidgtData;
		
		//Get the HTML
		return $view->encode( $resp );		
		
	}
	
	/**
	 * TuiyoControllerPhotos::slideShow()
	 * Universal system Slideshow
	 * @return
	 */
	public function slideShow()
	{
		$document 	= $GLOBALS['API']->get("document");
		$auth 		= $GLOBALS['API']->get("authentication");
		
		//JSON ONLY
		if($document->getDOCTYPE() !== "json"){
			$GLOBALS['mainframe']->redirect(TUIYO_PROFILE_INDEX);	
		}
		$auth->requireAuthentication();
		
		//REQUIREMENTS
		$model 	= $this->getModel( "photos" );
		$view 	= $this->getView('photos', "json" );
		
		$user 	= $GLOBALS['API']->get( 'user' );
		
		//Check if there is albumID
		$aid 	= JRequest::getVar( "aid", NULL );
		$album 	= ( !empty($aid) && (int)$aid > 0 )
			    ? $model->getSingleAlbum($user->id , (int)$aid ) : NULL; 
		
		//TODO,Check User has permission to return 
		$photos = $model->getPhotos( (isset($album->ownerid)&&!empty($album->ownerid) ) ? $album->ownerid : $user->id , $aid, NULL, TRUE , FALSE  );
		
		//Response Array
  		$resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => null, 
			"photos"=> $photos,
			"album" => $album,
			"extra" => null
		);
		return $view->showSlideShowPanel( $resp );
	}	
	
	/**
	 * TuiyoControllerPhotos::editAlbum()
	 * Create, Modifies or Deletes an Album
	 * @return
	 */
	public function editAlbum()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$auth 	=&$GLOBALS['API']->get( 'authentication' ); 
		
		//User must be logged in
		$auth->requireAuthentication();
		
		$model 	= $this->getModel("photos");
		$view	= $this->getView("photos", "json");
		
		$aid 	=&JRequest::getVar("aid" , NULL );
		$user 	=&$GLOBALS['API']->get( 'user' ); 
		
		//Edit the Album
		$album 	= $model->editAlbum( $user->id, $albumID  );
		
		$resp 	= array(
			"code" 	=> TUIYO_OK, 
			"error" => null, 
			"album" => $album,
			"extra" => null
		);
		return $view->encode( $resp );
	}
	
	public function removeAlbum()
	{	
		// Check for request forgeries
		JRequest::checkToken('request') or jexit( 'Invalid Token' );
		
		$auth 	=&$GLOBALS['API']->get( 'authentication' ); 
		
		//User must be logged in
		$auth->requireAuthentication();
				
		$model 	= $this->getModel("photos");
		$view	= $this->getView("photos", "json");
		
		$aid 	=&JRequest::getVar("aid" , NULL );
		$user 	=&$GLOBALS['API']->get( 'user' ); 
		
		//Edit the Album
		$album 	= $model->deleteAlbum( intval( $user->id ), intval($albumID)  );
		
		$resp 	= array(
			"code" 	=> TUIYO_OK, 
			"error" => null, 
			"album" => $album->name,
			"extra" => null
		);
		return $view->encode( $resp );
	}	
	
	public function saveAlbumContents()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$model 	= $this->getModel("photos");
		$view	= $this->getView("photos", "json");

		$auth 	=&$GLOBALS['API']->get( 'authentication' ); 
		
		//User must be logged in
		$auth->requireAuthentication();
		
		$user 	=&$GLOBALS['API']->get( 'user' ); 
		
		//Edit the Album
		$album 	= $model->addPhotosToAlbum( $user->id );
		
		$resp 	= array(
			"code" 	=> TUIYO_OK, 
			"error" => null, 
			"extra" => null
		);
		return $view->encode( $resp );
	}
	

	/**
	 * Authorises a user to run the given controller!
	 * TuiyoControllerCore::authorise()
	 * @return void
	 */
	public function authorise()
	{
		global $API;
		$user = $API->get( 'user' );
	}
}
