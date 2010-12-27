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
jimport( 'joomla.application.component.model' );

/**
 * TuiyoModelTimeline
 * @package Tuiyo For Joomla
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoModelTimeline extends JModel{
	
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
	 * TuiyoModelTimeline::getUserTimeline()
	 * Gets the user timeline, Please check the documentation
	 * @param mixed $userID
	 * @param mixed $requestingUserID
	 * @param mixed $options
	 * @return void
	 */
	public function getUserTimeline($userID, $requestingUserID, $options = array()){
		
		$thatuser 	= TuiyoAPI::get("user", (int)$userID );
		$thisuser	= TuiyoAPI::get("user", (int)$requestingUserID );
		$uActivity 	= TuiyoAPI::get("activity", null );
		$table 		= TuiyoLoader::table( "timeline" );
		$stories 	= array();
		
		//a. MUST verify that this uer has permission to see that users status
		
		$limit		= $this->getState('limit');
		$limitstart = $this->getState('limitstart' );
		
		$stories 	= array();
		
		//1. Get the timelien data from DB
		$statusID 	= !empty($options["statusID"])&&intval($options["statusID"])> 0 ? (int)$options["statusID"] : NULL ;
		
		$activity   = $table->loadTimeline( $thatuser->id, null, null , null , $limitstart, $limit  , $statusID , $options['filter'], $options['source'] );
		
		//1b. Paginate?
		jimport('joomla.html.pagination');
		
		$dbo 				= $table->_db ;
		$this->_total		= $dbo->loadResult();
		
		//Set the total count
		$this->setState('total' , $this->_total );
		
		//2. Standardize
		foreach( (array)$activity as $story):
			
			$story->datetime = TuiyoTimer::diff( strtotime( $story->datetime ));
			$story->id		 = (int)$story->id ;
			$story->bodytext = strval( $story->bodytext ); //No HTML unless defined in template
			$story->userpic  = TuiyoUser::getUserAvatar($story->userid, "thumb70");
			$story->source 	 = (empty($story->source)) ? "web" : $story->source;
			
			//isPublic?
			$publicity 		 = json_decode( $story->sharewith );
			$story->isPublic = (in_array("%p00%", $publicity )) ? 1 : 0 ;
			
			
			//Get Votes
			$voterLikesA 	 = array();
			$voterDislikesA  = array();
			
			$voterLikes 	 = json_decode( $story->likes , TRUE );
			$voterDislikes 	 = json_decode( $story->dislikes , TRUE );
			
			foreach($voterLikes as $likeVote):
				$voteL = array(
					"userID"	=> (int)$likeVote,
					"userPic"	=> TuiyoUser::getUserAvatar( (int)$likeVote , "thumb35" )
				);
				$voterLikesA[] = $voteL ;
			endforeach;
			
			foreach($voterDislikes as $dislikeVote):
				$voteD = array(
					"userID"	=> (int)$dislikeVote,
					"userPic"	=> TuiyoUser::getUserAvatar( (int)$dislikeVote , "thumb35" )
				);
				$voterDislikesA[] = $voteD ;
			endforeach;
			
			$story->likes 	 = $voterLikesA;
			$story->dislikes = $voterDislikesA ;
			
			//get story comments
			$story->comments  = $this->getComments( $story->id );
			
			//Activity Icon
			$iconPath 		= 'components/com_tuiyo/client/default/icons/';
			$systemIcon16 	= array(
				"photos" 	=> $iconPath.'photos.png',
				"groups"	=> $iconPath.'world.png',
				"friends" 	=> $iconPath.'add.png',
				"events"	=> $iconPath.'calendar.png',				
			);
			$story->icon16	= isset($systemIcon16[$story->identifier])? $systemIcon16[$story->identifier] : $iconPath.'trans.gif';
			
			//If we still don't have a source icon
			if(!empty($story->extID) || (int)$story->extID > 0 && empty($story->icon16) ){
				$story->icon16 	  = 'components/com_tuiyo/applications/'.strtolower($story->identifier).'/favicon.png'; 
			} 
			
			//Story Permissions
			$story->candelete = ((int)$story->userid <> (int)$thisuser->id ) ? false : true ;
			$story->cancomment= (!$thisuser->joomla->get('guest') ) ? true : false ;
			$story->canvote   = (!$thisuser->joomla->get('guest') ) ? true : false ;
			
			
			if($story->itemType === "activity"):
				$story = $uActivity->parseActivityStory( $story );
			endif;
			
			//Unsets
			unset($story->sharewith);
			unset($story->thisUserID);
			unset($story->thatUserID);
			unset($story->template );
			unset($story->ID );
			unset($story->identifier);
			unset($story->variables);
			unset($story->resources);
			
			//Add to the stories array;
			$stories[]		 = $story ;
			
		endforeach;
		
		//3. Get Timeline data from third parties
		
		//4. Triger on timeline build events!
		
		return (array)$stories;		
		
	}
	
	public function getLastestUserStatus( $userID , $requestingID )
	{
		$thatuser 	= TuiyoAPI::get("user", (int)$userID );
		$thisuser	= TuiyoAPI::get("user", (int)$requestingUserID );
		$table 		= TuiyoLoader::table( "timeline" );
		$stories 	= array();
		
		
		return $table->loadLastTimelineItem( $userID );
	}
	
	
	/**
	 * TuiyoModelTimeline::deleteActivity()
	 * Deletes an activity from the user timeline
	 * @param mixed $userID
	 * @param mixed $activityID
	 * @param mixed $options
	 * @return void
	 */
	public function deleteActivity($userID, $activityID, $options)
	{
		$table 	= TuiyoLoader::table( "timeline" );
		$tTable = TuiyoLoader::table( "timelinetmpl");
		
		$table->load( (int)$activityID );
		
		if(empty($table->ID) || $table->userID <> $userID ){
			JError::raiseError(TUIYO_SERVER_ERROR, _("Could not load the activity") );
		}
		
		if(!empty($table->template) && (int)$table->template > 0){
  			//print_R($tTable);
	 		$ltTable = new TuiyoTableTimelinetmpl( $this->_db );
		    $ltTable->load( (int)$table->template );	
		    		
			if(!$ltTable->delete()){
				JError::raiseError(TUIYO_SERVER_ERROR, $table->getError() );
				return false;
			}
		}
		
		if(!$table->delete()){
			JError::raiseError(TUIYO_SERVER_ERROR, $table->getError() );
			return false;
		}
		return true;
	}
	
	/**
	 * TuiyoModelTimeline::getGroupTimeline()
	 * 
	 * @param mixed $groupID
	 * @param mixed $userID
	 * @return
	 */
	public function getGroupTimeline($groupID, $userID , $filterType = null , $sourceType = null){
		return $this->getPublicTimeline( $userID, array( 
			"group" => (int)$groupID,
			"filter"=> $filterType,
			"source"=> $sourceType
		));
	}
	
	/**
	 * TuiyoModelTimeline::getSuggestion()
	 * AutoSuggest User Friends or User Groups
	 * @param mixed $salt
	 * @param mixed $userID
	 * @param mixed $limit
	 * @return void
	 */
	public function getSuggestion( $salt , $userID , $limit =10 ){
		
		$rTable = TuiyoLoader::table("resources");
		return $rTable->suggestResource( (string)$salt, (int)$userID, (int)$limit );
	}
	
	/**
	 * TuiyoModelTimeline::getPublicTimeline()
	 * Gets all public user timeline data;
	 * Response should contatin
	 * $object->datetime, $object->username
	 * $object->id,  	  $object->userid
	 * $object->bodytext, $object->source
	 * $object->userpic,  $object->candelete (bool)
	 * $object->cancomment, $object->comments ( array ) see getComments method
	 * 
	 * @param mixed $requestingUserID
	 * @param mixed $options
	 * @return void
	 */
	public function getPublicTimeline($requestingUserID, $options = array())
	{	
        global $mainframe, $option;
        
		$user 		= TuiyoAPI::get("user", null );
		$uActivity 	= TuiyoAPI::get("activity", null );
		$table 		= TuiyoLoader::table( "timeline" );
		
		$limit		= $this->getState('limit');
		$limitstart = $this->getState('limitstart' );
		
		$stories 	= array();
		
		//echo $options['filter'];
		
		//1. Get the timelien data from DB
		if(array_key_exists("group", $options) && !empty($options["group"])){
			$activity   = $table->loadTimeline( null, $options["group"], null , null , $limitstart, $limit , null, $options['filter'] , $options['source']   );
		}else{
			$activity   = $table->loadTimeline( null, null, null , TRUE , $limitstart, $limit, null, $options['filter'] , $options['source'] );
		}
		//1b. Paginate?
		jimport('joomla.html.pagination');
		
		$dbo 				= $table->_db ;
		$this->_total		= $dbo->loadResult();
		
		//Set the total count
		$this->setState('total' , $this->_total );
		
		//2. Standardize
		foreach($activity as $story):
			
			$story->datetime = TuiyoTimer::diff( strtotime( $story->datetime ));
			$story->id		 = (int)$story->id ;
			$story->bodytext = strval( $story->bodytext ); //No HTML unless defined in template
			$story->userpic  = TuiyoUser::getUserAvatar($story->userid, "thumb70");
			$story->source 	 = (empty($story->source)) ? "web" : $story->source;
			
			//isPublic?
			$publicity 		 = json_decode( $story->sharewith );
			$story->isPublic = (in_array("%p00%", $publicity )) ? 1 : 0 ;
			
			
			//Get Votes
			$voterLikesA 	 = array();
			$voterDislikesA  = array();
			
			$voterLikes 	 = json_decode( $story->likes , TRUE );
			$voterDislikes 	 = json_decode( $story->dislikes , TRUE );
			
			foreach($voterLikes as $likeVote):
				$voteL = array(
					"userID"	=> (int)$likeVote,
					"userPic"	=> TuiyoUser::getUserAvatar( (int)$likeVote , "thumb35" )
				);
				$voterLikesA[] = $voteL ;
			endforeach;
			
			foreach($voterDislikes as $dislikeVote):
				$voteD = array(
					"userID"	=> (int)$dislikeVote,
					"userPic"	=> TuiyoUser::getUserAvatar( (int)$dislikeVote , "thumb35" )
				);
				$voterDislikesA[] = $voteD ;
			endforeach;
			
			$story->likes 	 = $voterLikesA;
			$story->dislikes = $voterDislikesA ;
			
			//get story comments
			$story->comments  = $this->getComments( $story->id );
			//Activity Icon
			$iconPath 		= 'components/com_tuiyo/client/default/icons/';
			
			$systemIcon16 	= array(
				"photos" 	=> $iconPath.'iupload_16.png',
				"groups"	=> $iconPath.'groups_16.png',
			);
			$story->icon16	= isset($systemIcon16[$story->identifier])? $systemIcon16[$story->identifier] : $iconPath.'trans.gif';
			
			//If we still don't have a source icon
			if(!empty($story->extID) || (int)$story->extID > 0 && empty($story->icon16) ){
				$story->icon16 	  = 'components/com_tuiyo/applications/'.strtolower($story->identifier).'/favicon.png'; 
			} 
			//Story Permissions
			$story->candelete = ((int)$story->userid <> (int)$user->id ) ? false : true ;
			$story->cancomment= (!$user->joomla->get('guest') ) ? true : false ;
			$story->canvote   = (!$user->joomla->get('guest') ) ? true : false ;
			
			//Parse Activity
			if($story->itemType === "activity"):
				$story = $uActivity->parseActivityStory( $story );
			endif;
			
			//Unsets
			unset($story->sharewith );			
			unset($story->thisUserID);
			unset($story->thatUserID);
			unset($story->template );
			unset($story->ID );
			unset($story->identifier );
			unset($story->variables);
			unset($story->resources);
			
			//Add to the stories array;
			$stories[]		 = $story ;
			
		endforeach;
		
		//3. Get Timeline data from third parties
		
		//4. Triger on timeline build events!
		
		return (array)$stories;
		
	}
	
	/**
	 * TuiyoModelTimeline::getReplies()
	 * Response should contatin
	 * $object->datetime, $object->username
	 * $object->id,  	  $object->userid
	 * $object->bodytext, $object->source
	 * $object->userpic,  $object->candelete (bool)
	 * $object->cancomment,
	 * @param mixed $storyID
	 * @return void
	 */
	public function getComments( $storyID ){
		
		$user 		= TuiyoAPI::get("user", null );
		$table 		= TuiyoLoader::table( "timeline" );
		$comments 	= array();
		
		//1. Get the timelien data from DB
		$replies    = $table->loadTimeline( null, null, (int)$storyID, null, 0 , null );
		
		//2. Standardize
		foreach($replies as $comment):
		
			$comment->datetime 	= TuiyoTimer::diff( strtotime( $comment->datetime ));
			$comment->id		= (int)$comment->id ;
			$comment->bodytext 	= strval( $comment->bodytext ); //No HTML unless defined in template
			$comment->userpic  	= TuiyoUser::getUserAvatar($comment->userid, "thumb35");
			$comment->source 	= (empty($comment->source)) ? "web" : $comment->source;
			
			//Story Permissions
			$comment->candelete = ((int)$comment->userid <> (int)$user->id ) ? false : true ;
			$comment->cancomment= (!$user->joomla->get('guest') ) ? true : false ;
			
			//Add to the stories array;
			$comments[]		 = $comment ;			
			
		endforeach;
		
		return (array)$comments ;		
		
	}
	
	
	/**
	 * TuiyoModelTimeline::getFriendsTimeline()
	 * Gets all friends Timeline
	 * @param mixed $options
	 * @return void
	 */
	public function getFriendsTimeline($options = array()){}
	
	/**
	 * TuiyoModelTimeline::getCurrentStatus()
	 * Gets the current Status of the specified userID
	 * @param mixed $userID
	 * @param mixed $options
	 * @return void
	 */
	public function getCurrentStatus($userID, $options = array()){}
	

	/**
	 * TuiyoModelTimeline::setStatus()
	 * Sets a status for the specified userID
	 * @param mixed $userID
	 * @param mixed $postData
	 * @param mixed $options
	 * @return void
	 */
	public function setStatus($userID, $postData, $type="status", $options = array()){
		
		$table 		=& TuiyoLoader::table("timeline" );
		$appTable 	=& TuiyoLoader::table("applications");
		$tTemplate  =& TuiyoLoader::table("timelinetmpl") ;
		
		$tNotify	=& TuiyoLoader::library("mail.notify");
		
		$table->load( null );
		
		//print_R($postData);
		$statusText = strval( $postData['ptext'] );
		$source 	= strval( $postData['source'] );
		$app 		= (array)$appTable->getSingleApplication( $source );
		$template 	= (isset($postData['template']) )? (int)$postData['template'] :  null;
		
		$sharewith 	= $postData["sharewith"];
		$publicity  = array();	
		$isPublic 	= 0 ;
		
		$embedable = $postData["embedable"];
		
		if(is_array($embedable) && isset($embedable['title']) && !empty($embedable["thumb"])){
						//First Save the template
			$tTemplate->load( null );
			$tTemplate->appName 	= strval( $source );
			$tTemplate->identifier 	= $tTemplate->appName;
			$tTemplate->title		= $embedable['title'] ;			
			$tTemplate->body		= '<i class="embedDescr">'.$embedable['description']."</i>";
			$tTemplate->type		= 1;
			
			$resources 				=  array( 
				array(
					"type"	=> "embedable",
					"url"	=> $embedable["thumb"]
				) 
			);
			
			$tTemplate->resources 	= json_encode( $resources ) ;
			$tTemplate->thisUserID	= $userID;
			
			if(!$tTemplate->store()){
				trigger_error($tTemplate->getError(), E_USER_ERROR);
				return false;
			}
			$template = $tTemplate->ID ;
			$type 	  = "activity";
		}		
		
		if(is_array($sharewith)){

			foreach($sharewith as $share):
				if(!empty($share)):
					$isPublic = (strval($share)=="p00") ? 1 : 0 ;
					$publicity[] = "%".trim($share)."%" ;
				endif; 
			endforeach;
			
			$table->sharewith = json_encode( $publicity ) ;
		}
		
		//get out all mentions in the update string
		preg_match_all('#@([\\d\\w]+)#', $statusText , $mentions );
		preg_match_all('/#([\\d\\w]+)/', $statusText , $hashTags );
		
		
		$table->tags 	 = (sizeof($hashTags[1])>0)? json_encode( $hashTags[1] ) : null;
		$table->mentions = (sizeof($mentions[1])>0)? json_encode( $mentions[1] ) : null;
		$table->userID	 = (int)$userID;
		$table->template = $template;
		$table->datetime = date('Y-m-d H:i:s');
		$table->data 	 = $statusText;
		$table->type 	 = $type;
		$table->appID	 = is_array($app)&&!empty($app) ? $app[0]["extID"] : null ;
		$table->source   = isset($source) ? $source : "web";
		$table->inreplyto= 0 ; 
		
		if(!$table->store()){
			trigger_error( $table->getError() , E_USER_ERROR );
			return false;
		}
		$table->isPublic = $isPublic ;
		
		//NOTIFY ALL SHAREWITH USERS // NOT GROUPS
		$poster			= TuiyoAPI::get("user", (int)$table->userID);
		$participants   = array();
		$groups			= array();
		
		foreach((array)$sharewith as $key=>$el):
			if( strpos( $el , "p" ) !== false ):
				$participants[] = str_replace( array("p", "%"), array("", ""), (string)$el );
			elseif( strpos( $el , "g" ) !== false ):
				$groups	[] 		= str_replace( array("g", "%"), array("", ""), (string)$el );					
			endif;			
		endforeach;
		
		
		foreach($participants as $notify):
			if((int)$notify > 0 && (int)$notify <> $userID ):
				$userTo 	= TuiyoAPI::get("user", $notify );
				$actionLink = JRoute::_(TUIYO_INDEX."&view=profile&do=viewStatus&user={$userTo->username}&id={$table->ID}");

				
		        $emailTitle = sprintf(_("%s shared something on your wall"), "@".$poster->username );
				$emailBody 	= "";//str_replace( $tSearch , $tVars , $notifyParams->get( "connectionRequestEmailBody" , null ) );
	   			
				//echo $notifyEmail ;
				TuiyoNotify::_( $userTo->id, $emailTitle , $actionLink , _("View Post") );			
			endif;
		endforeach;
		
		//Load the group MOdel;
		$gModel  	=& TuiyoLoader::model("groups", true) ;
		
		foreach($groups as $group):
			$groupData = $gModel->getGroup( (int)$group );
			foreach( (array)$groupData->members as $notify ):
				if((int)$notify->data["userID"] > 0 && (int)$notify->data["userID"] <> $userID ):
					$userTo 	= TuiyoAPI::get("user", $notify->data["userID"] );
					$actionLink = JRoute::_(TUIYO_INDEX."&view=groups&do=view&gid={$groupData->groupID}");
	
					
			        $emailTitle = sprintf(_("%s posted something to the '%2s' group"), "@".$poster->username , $groupData->gName );
					$emailBody 	= "";//str_replace( $tSearch , $tVars , $notifyParams->get( "connectionRequestEmailBody" , null ) );
		   			
					//echo $notifyEmail ;
					TuiyoNotify::_( $userTo->id, $emailTitle , $actionLink , _("View Group") );	
				endif;			
			endforeach;
		endforeach;
		
		return $table ;
	}
	
	/**
	 * TuiyoModelTimeline::storeVote()
	 * 
	 * @param mixed $userID
	 * @param mixed $statusID
	 * @param mixed $voteType
	 * @return
	 */
	public function storeVote( $userID, $statusID, $voteType )
	{
		$tTable = TuiyoLoader::table( "timeline" );
		
		if(!$tTable->load( (int)$statusID) ){
			JError::raiseError(TUIYO_SERVER_ERROR, $tTable->getError());
			return false;
		}
		
		$likeVotes 	= array();
		$dLikeVotes = array();
		$likeVotes 	= json_decode( $tTable->likes , TRUE );
		$dLikeVotes = json_decode( $tTable->dislikes, TRUE );
		
		if((int)$voteType > 0 ){
			if(!in_array((int)$userID, $likeVotes) && !in_array((int)$userID, $dLikeVotes)){
				$likeVotes[] 	= (int)$userID;
				$tTable->likes  = json_encode( $likeVotes );
				$thisUser		= TuiyoAPI::get("user", (int)$userID );
				$emailTitle 	= sprintf(_("%s likes your wall post"), "@".$thisUser->username );	
			}else{
				JError::raiseError(TUIYO_NOT_MODIFIED, _('Could not save the vote, because you already voted') );
				return false;	
			}
		}elseif( (int)$voteType < 0 ){
			if(!in_array((int)$userID, $dLikeVotes) && !in_array((int)$userID, $likeVotes)){
				$dLikeVotes[] 	= (int)$userID;
				$tTable->dislikes  = json_encode( $dLikeVotes );
				$thisUser		= TuiyoAPI::get("user", (int)$userID );
				$emailTitle 	= sprintf(_("%s does not like your wall post"), "@".$thisUser->username );					
			}else{
				JError::raiseError(TUIYO_NOT_MODIFIED, _('Could not save the vote because you already voted') );
				return false;	
			}
		}else{
			JError::raiseError(TUIYO_SERVER_ERROR, _("Invalid vote type" ) );
			return false;
		}
		
		if(!$tTable->store( ) ){
			JError::raiseError(TUIYO_SERVER_ERROR, $tTable->getError());
			return false;
		}
		//Notify Users
		$userTo 	= TuiyoAPI::get("user", $tTable->userID );
		$actionLink = JRoute::_(TUIYO_INDEX."&view=profile&do=viewStatus&user={$userTo->username}&id={$statusID}");
		
		//echo $notifyEmail ;
		if($userID <> $userTo->id):
			TuiyoLoader::library("mail.notify");
			TuiyoNotify::_( $userTo->id, $emailTitle , $actionLink , _("View Post") );
		endif;
				
		return TRUE;
	}
	
	/**
	 * TuiyoModelTimeline::deleteVote()
	 * Removes a vote from the timeline table
	 * @param mixed $userID
	 * @param mixed $statusID
	 * @param mixed $voteType
	 * @return
	 */
	public function deleteVote( $userID, $statusID, $voteType )
	{
		$tTable = TuiyoLoader::table( "timeline" );
		
		if(!$tTable->load( (int)$statusID) ){
			JError::raiseError(TUIYO_SERVER_ERROR, $tTable->getError());
			return false;
		}
		
		$likeVotes 	= array();
		$dLikeVotes = array();
		$likeVotes 	= json_decode( $tTable->likes , TRUE );
		$dLikeVotes = json_decode( $tTable->dislikes , TRUE);
		
		//Are we removing a like
		if(in_array($userID, $likeVotes)){
			$aKey = array_search( $userID , $likeVotes );
			unset($likeVotes[$aKey]);
			$tTable->likes = json_encode($likeVotes);
		}elseif(in_array($userID, $dLikeVotes)){
			$aKey = array_search( $userID , $dLikeVotes );
			unset($dLikeVotes[$aKey]);
			$tTable->dislikes = json_encode($dLikeVotes);
		}
		if(!$tTable->store( ) ){
			JError::raiseError(TUIYO_SERVER_ERROR, $tTable->getError());
			return false;
		}
		return TRUE;
	}
	
	
	/**
	 * TuiyoModelTimeline::setStatusComment()
	 * @param mixed $userID
	 * @param mixed $postData
	 * @param mixed $options
	 * @return void
	 */
	public function setStatusComment($userID, $postData, $options = array()){
		
		$commenter  = TuiyoApi::get("user", null );
		$table 		= TuiyoLoader::table( "timeline" );
		$table->load( null );
		
		//print_R($postData);
		$statusText = strval( $postData['commentbody'] );
		
		//get out all mentions in the update string
		preg_match_all('#@([\\d\\w]+)#', $statusText , $mentions );
		preg_match_all('/#([\\d\\w]+)/', $statusText , $hashTags );
		
		$table->tags 	 = (sizeof($hashTags[1])>0)? json_encode( $hashTags[1] ) : null;
		$table->mentions = (sizeof($mentions[1])>0)? json_encode( $mentions[1] ) : null;		
		
		$table->userID	 = (int)$userID;
		$table->template = null;
		$table->datetime = date('Y-m-d H:i:s');
		$table->data 	 = $statusText;
		$table->type 	 = "comment";
		$table->source   = isset( $postData['source'] ) ? $postData['source'] : "web";
		$table->source   = strval( $table->source  );
		$table->inreplyto= isset( $postData['inreplyto'] ) ? (int)$postData['inreplyto'] : JError::raiseError(403, _("Invalid activity ID") );
		
		if(!$table->store()){
			trigger_error( $table->getError() , E_USER_ERROR );
			return false;
		}
		
		//Send Notifications to All users participating in this discussion
		TuiyoLoader::library("mail.notify");
		
		$getParticipants = $table->getAllCommenters( $table->inreplyto );
		$firstAuthors	 = array();
		//Notify Authors
		foreach($getParticipants["author"] as $author ):
			$firstAuthors[] = $author ;
			if($userID <> $author ):
			
				$userTo 	= TuiyoAPI::get("user", $author );
				$actionLink = JRoute::_(TUIYO_INDEX."&view=profile&do=viewStatus&user={$userTo->username}&id={$table->inreplyto}");

				
		        $emailTitle = sprintf(_("%s commented on your wall post"), "@".$commenter->username );
				$emailBody 	= "";//str_replace( $tSearch , $tVars , $notifyParams->get( "connectionRequestEmailBody" , null ) );
	   			
				//echo $notifyEmail ;
				TuiyoNotify::_( $userTo->id, $emailTitle , $actionLink , _("View Status") );			
				TuiyoNotify::sendMail( $userTo->email, $emailTitle, $emailBody );					
				
			endif;
		endforeach;
		
		//Notify Participants
		foreach($getParticipants["participant"] as $tookpart):
			if($userID <> $tookpart ):
				
				$firstauthor= TuiyoAPI::get("user", (int)$firstAuthors[0] );
				$userTo 	= TuiyoAPI::get("user", $tookpart );
				$actionLink = JRoute::_(TUIYO_INDEX."&view=profile&do=viewStatus&user={$firstauthor->username}&id={$table->inreplyto}");

				
		        $emailTitle = sprintf(_("%s commented on %2s wall post"), "@".$commenter->username , "@".$firstauthor->username );
				$emailBody 	= "";//str_replace( $tSearch , $tVars , $notifyParams->get( "connectionRequestEmailBody" , null ) );
	   			
				//echo $notifyEmail ;
				TuiyoNotify::_( $userTo->id, $emailTitle , $actionLink , _("View Status") );			
				TuiyoNotify::sendMail( $userTo->email, $emailTitle, $emailBody );					
				
			endif;		
		endforeach;
		
		return $table ;		 		
	}
	
	
	/**
	 * TuiyoModelTimeline::__construct()
	 * Constructor for Timeline model
	 * @return void
	 */
	public function __construct()
	{
	    parent::__construct();
	
	    global $mainframe, $option;
	
	    // Get pagination request variables
	    $limit 		= $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
	    $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
	
	    // In case limit has been changed, adjust it
	    $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
	
	    $this->setState('limit', $limit ); //$limit
	    $this->setState('limitstart', $limitstart);
	    
	    $this->pageNav  = NULL ;
	}
		
    public function authorise()
    {
        global $API;
        $user = $API->get('user');
    }	
	
}