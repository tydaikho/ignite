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
 * joomla MOdel
 */
jimport('joomla.application.component.model');

/**
 * TuiyoModelProfile
 * 
 * @package tuiyo
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoModelProfile extends JModel
{
    /**
     * Total number of items
     * @var integer
     */
    public $_total = null;

    /**
     * The Pagination object
     * @var object
     */
    public $_pagination = null;
    /**
     * Method to store the user data
     * Borrowed from the J! Framework
     * @access	public
     * @return	boolean	True on success
     * @since	1.5
     */
    public function storeJoomlaUser($data)
    {
        $user = JFactory::getUser();
        $username = $user->get('username');

        // Bind the form fields to the user table
        if (!$user->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        // Store the web link table to the database
        if (!$user->save()) {
            $this->setError($user->getError());
            return false;
        }
        $session = &JFactory::getSession();
        $session->set('user', $user);

        // check if username has been changed
        if ($username != $user->get('username')) {
            $table = $this->getTable('session', 'JTable');
            $table->load($session->getId());
            $table->username = $user->get('username');
            $table->store();
        }

        return true;
    }
    
    /**
     * TuiyoModelProfile::setProfileRating()
     * 
     * @param mixed $profile
     * @param mixed $data
     * @return void
     */
    public function setProfileRating( $data )
	{
		$user 		= TuiyoAPI::get('user', NULL );
		$profile 	= TuiyoAPI::get('user', (int)$data["pid"] );
		$uPrivacy 	= TuiyoAPI::get("privacy" );
		$uTable 	= TuiyoLoader::table("users", true );
		
    	//***First check that the user has not been ***/
    	if(!$uPrivacy->canRateUser( $profile->id, $user->id )){
  			JError::raiseError(TUIYO_NOT_MODIFIED , "Cannot rate this profile");
			return NULL;	
    	}
    	
    	$uTable->load( $profile->id );
    	$uTable->totalVotes 	= (int)$uTable->totalVotes + 1 ;
    	$uTable->profileRatings = (int)$uTable->profileRatings + (int)$data["rating"];
    	
    	if(!$uTable->store()){
    		JError::raiseError(TUIYO_NOT_MODIFIED , $uTable->getError() );
			return NULL;
    	}
    	
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.path');
    	
    	$IP   	= (is_null($IP)||empty($IP))? getenv('REMOTE_ADDR') : $IP;
		$IPfile	= TUIYO_FILES.DS."logs".DS.strval($profile->id).DS."rating.log";
		
		$rateLogKey = $user->id."@".$IP ;
		$rateLog 	= fopen($IPfile, "a+"); 
		fwrite($rateLog,$rateLogKey.'='.$data['rating']."\n");
		fclose($rateLog);
		
    	return array( 
    		"rating" => round((int)$uTable->profileRatings / (int)$uTable->totalVotes , 0 ),
    		"total"	 => $uTable->totalVotes
		);		
    }

    /**
     * TuiyoModelProfile::storeTuiyoUser()
     * Store the tuiyo user!
     * @param mixed $data
     * @return void
     */
    public function storeTuiyoUser($data)
    {
        $users = TuiyoLoader::table("users");
        $users->load((int)$data["jid"]);
        //Bind User Data
        if (!$users->bind($data)) {
            trigger_error(_("Can not bind user data"), E_USER_ERROR);
            return false;
        }
        $users->sex = (int)$users->sex;

        if (!$users->storeObj()) {
            trigger_error(_("Cannot save user data"), E_USER_ERROR);
            return false;
        }
        //Pofile Update events
        $GLOBALS["events"]->trigger("onProfileUpdate");

        //Success
        return true;
    }
    
    /**
     * TuiyoModelProfile::getProfileRecentVisitors()
     * Gets a list of the recent profile visitors
     * @param mixed $profileID
     * @param integer $limit
     * @return array
     */
    public function getProfileRecentVisitors( $userID , $limit = 30 ){
		
		$visitors 	= array();		
		$viewsFile	= TUIYO_FILES.DS."logs".DS.strval($userID).DS."view.log";
		
		//Who viewed
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.path');
		
		if(!JFile::exists($viewsFile)):
			JFile::write( $viewsFile , "" );
		endif ;
		
		$views 		= file($viewsFile, FILE_SKIP_EMPTY_LINES);
		$views 		= array_reverse( $views );
		$inc 		= 0;
		
		//Gets all the recent view data into an array;
		foreach($views as $lineNo=>$viewData):			
			if( ($inc+1)>30 ): break; endif;
			$whoViewed 	= json_decode( (string)$viewData );
			
			if(!array_key_exists($whoViewed->whoID , $visitors)):
				$visitors[$whoViewed->whoID] = $whoViewed;
				$inc++ ;	
			endif;		
		endforeach;
		
		return $visitors ;
    }
    
    /**
     * TuiyoModelProfile::incrementViews()
     * Number of times a profile is viewed
     * @param mixed $profileID
     * @param integer $increment
     * @return
     */
    public function incrementViews( $profileID, $increment = +1 ){
    	
    	$uTable = TuiyoLoader::table("users", true);
    	$user 	= TuiyoAPI::get("user", null);
    	
    	if($user->id <> (int)$profileID){
	    	if($uTable->load( (int)$profileID )){ 	
		    	$uTable->profileView = (int)$uTable->profileView+(int)$increment;
		    	if(!is_null($uTable->profileID)){
					if(!$uTable->store()){
			    		return false;
			    	}
    				//Who viewed
					jimport('joomla.filesystem.file');
					jimport('joomla.filesystem.path');
			    				    	
					$viewsFile	= TUIYO_FILES.DS."logs".DS.strval($uTable->userID).DS."view.log";
					
					if(!JFile::exists($viewsFile)):
						JFile::write( $viewsFile , "" );
					endif ;
					
					$viewLogKey 	= $user->id;
					$viewLogData 	= array(
						"date"		=> date('Y-m-d H:i:s'),
						"whoID"		=> $user->id,
						"whoUsername" => $user->username,
					);
					$viewLogDataString = json_encode( $viewLogData );
					$viewLog 	= fopen($viewsFile, "a+"); 
					fwrite($viewLog, $viewLogDataString."\n");
					fclose($rateLog);
													    	
		    	}
			}
		}
		
		
    	return true;
    }

    /**
     * TuiyoModelProfile::getUserAvatars()
     * [OBSULATE!!]
     * @return
     */
    public function getUserAvatars()
    {
        $user = JFactory::getUser();
        $dbo = JFactory::getDbO();

        $query = "SELECT r.resourceID as id, r.url, r.fileTitle as title" . "\nFROM #__tuiyo_resources r" .
            "\nWHERE r.contentType='AVATAR' AND r.userID =" . $dbo->Quote((int)$user->id);
        $dbo->setQuery($query);

        //echo $dbo->getQuery();

        return (array )$dbo->loadAssocList();
    }

    /**
     * TuiyoModelProfile::getUserBackgrounds()
     * Gets available user backgrounds;
     * @return
     */
    public function getUserBackgrounds()
    {

        $userData = $GLOBALS['API']->get("user");
        $resourceTable = TuiyoLoader::table("resources");
        $userWallpapers = $resourceTable->getUserWallpapers($userData->id);

        return (array )$userWallpapers;
    }

    /**
     * TuiyoModelProfile::getDesigns()
     * @return
     */
    public function getTemplateParams()
    {
    	global $mainframe , $API ; TuiyoLoader::helper("parameter");
        
		$params = array(
			'template' 	=> $mainframe->getTemplate(),
			'file'		=> "userparams.xml",
			'directory'	=> JPATH_THEMES
		);		
		
		$user 		= $API->get("user");
		// check
		$directory	= isset($params['directory']) ? $params['directory'] : 'templates';
		$template	= JFilterInput::clean($params['template'], 'cmd');
		$userparams	= JFilterInput::clean($params['file'], 'cmd');
		$userparams	= $directory.DS.$template.DS."html".DS."com_tuiyo".DS.$userparams;
		
		$userdata 	= TUIYO_STYLES.DS.$user->id.DS.$template.".ini" ; 
		
		if (!file_exists($userparams) || !is_file($userparams) ) {
			//If theres no userparams.xml file forget it
			return null ;
		}
		if (!file_exists( $userdata ) || !is_file( $userdata ) || !is_readable( $userdata ) ) {
			
			jimport('joomla.filesystem.file');
			
			JFile::write( $userdata , "");
		}
		
		$content 	= file_get_contents($directory.DS.$template.DS.'params.ini');		
		$params 	= new TuiyoParameter($content, $userparams);
		
		
		
		return $params;
    }

    /**
     * TuiyoModelProfile::buildSocialBookForm()
     * Builds social form
     * @return void
     */
    public function buildSocialBookForm($submitFormButton)
    {
        $user = TuiyoAPI::get("user");
        $params = TuiyoAPI::get("params", "user.social");

        $form = $params->getSocialBook($user->id, "user.social", true, $submitFormButton);

        return $form;
    }

    /**
     * TuiyoModelProfile::suspendProfile()
     * Suspends a user profile
     * @param mixed $data
     * @return
     */
    public function suspendProfile($data)
    {

        //Pofile Update events
        trigger_error(_("now suspend profile"), E_USER_ERROR);
        return false;

        $GLOBALS["events"]->trigger("onProfileSuspend");

        //Success
        return true;
    }

    /**
     * TuiyoModelProfile::deleteProfile()
     * Deletes a user profile and user account from the system
     * @param mixed $data
     * @return
     */
    public function deleteProfile($data)
    {
        //Pofile Update events
        $GLOBALS["events"]->trigger("onProfileDelete");

        //Success
        return true;

    }

}
