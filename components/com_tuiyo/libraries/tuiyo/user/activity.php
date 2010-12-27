<?php
/**
 * ******************************************************************
 * TuiyoTableUsers  Class/Object for the Tuiyo platform                              *
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
 * TuiyoActivity
 * 
 * @package Tuiyo For Joomla
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoActivity
{

    /**
     * TuiyoActivity::TuiyoActivity()
     */
    public function TuiyoActivity()
    {}

    /**
     * TuiyoActivity::deleteUserAction()
     * 
     * @param mixed $activityID
     * @return
     */
    public function deleteUserActivty( $activityID )
    {}
    
    
    public function publishMultiLineStory($thisUser, $storyTitle, $storyBody, $source, $thatUser=NULL, $tmplID = NULL, $tmplVars = array(), $rURLs = array() )
	{
		$mTimeline 		= &TuiyoLoader::model("timeline", TRUE );
		$tTemplate		= &TuiyoLoader::table("timelinetmpl", TRUE );
		
		//Whose activity?
		if(!is_object($thisUser) || empty($thisUser)){
			$thisUser = TuiyoAPI::get("user", null ) ;
		}
	
		//ThatUser must be an object;
		if(!is_null( $thatUser ) && !is_object($thatUser )){ 
		  JError::raiseError(_("ThatUser Must be an object"));
		  return false;
		}
		
		//ThatUser
		$thatUser 	   = (object)$thatUser ;
		
		if(empty($tmplID)):
			//First Save the template
			$tTemplate->load( null );
			$tTemplate->appName 	= strval( $source );
			$tTemplate->identifier 	= $tTemplate->appName;
			$tTemplate->title		= $storyTitle ;			
			$tTemplate->body		= $storyBody;
			$tTemplate->type		= 1;
			$tTemplate->resources 	= !empty($rURLs) 	? json_encode( (array) $rURLs ) : NULL;
			$tTemplate->variables 	= !empty($tmplVars)	? json_encode( (array) $tmplVars) : NULL;
			$tTemplate->thisUserID	= $thisUser->id;
			$tTemplate->thatUserID 	= $thatUser->id;
			
			if(!$tTemplate->store()){
				trigger_error($tTemplate->getError(), E_USER_ERROR);
				return false;
			}
			$tmplID = $tTemplate->ID ;

		endif;
		
		//Then save a parsed story in the timeline.
		$activity = array(
			"ptext" 	=> $storyTitle,
			"source" 	=> strval( $source ),
			"template"	=> $tmplID,
			"sharewith" => array( "p00" ) //@TODO check user privacy specifications
		);
		if(($story = $mTimeline->setStatus($thisUser->id, $activity, "activity" )) === FALSE ){
			JError::raiseError(_("Tuiyo could not save the activity") );
			return false;
		}
		//Compete
		return array(
			"code" 	=> TUIYO_OK, 
			"error" => null, 
			"tmpl" 	=> $tmplID,
			"story" => $story 
		);		
	}
    
    /**
     * TuiyoActivity::publishOneLineStory()
     * Application source
     * @param mixed $thisUser
     * @param mixed $storyLine
     * @param object $thatUser
     * @param mixed $tmplVars
     * @return void
     */
    public function publishOneLineStory($thisUser, $storyLine, $source, $thatUser=NULL, $tmplID= NULL, $tmplVars = array(), $type="activity")
	{
		$mTimeline		= &TuiyoLoader::model("timeline", true );
		$tTemplate		= &TuiyoLoader::table("timelinetmpl", true);
		
		//Whose activity?
		if(!is_object($thisUser) || empty($thisUser)){
			$thisUser = TuiyoAPI::get("user", null ) ;
		}
	
		//ThatUser must be an object;
		if(!is_null( $thatUser ) && !is_object($thatUser )){ 
		  JError::raiseError(_("ThatUser Must be an object") );
		  return false;
		}
		//ThatUser
		$thatUser 	   = (object)$thatUser ;
		
		if(empty($tmplID)):
			//First Save the template
			$tTemplate->load( null );
			$tTemplate->appName 	= strval( $source );
			$tTemplate->identifier 	= $tTemplate->appName;
			$tTemplate->title		= $storyLine ;
			$tTemplate->type		= 0;
			$tTemplate->resources 	= null;
			$tTemplate->body		= null;
			$tTemplate->variables 	= json_encode( (array) $tmplVars);
			$tTemplate->thisUserID	= $thisUser->id;
			$tTemplate->thatUserID 	= $thatUser->id;
			
			if(!$tTemplate->store()){
				trigger_error($tTemplate->getError(), E_USER_ERROR);
				return false;
			}
			$tmplID = $tTemplate->ID ;

		endif;
		
		//Then save a parsed story in the timeline.
		$activity = array(
			"ptext" 	=> $storyLine,
			"source" 	=> strval( $source ),
			"template"	=> $tmplID,
			"sharewith" => array( "p00" ) //@TODO check user privacy specifications 
		);
		if(($story = $mTimeline->setStatus($thisUser->id, $activity, $type )) === FALSE ){
			JError::raiseError(_("Tuiyo could not save the activity"));
			return false;
		}
		//Compete
		return array(
			"code" 	=> TUIYO_OK, 
			"error" => null, 
			"tmpl" 	=> $tmplID,
			"story" => $story
		);
	}
	
	/**
	 * TuiyoActivity::parseActivityStory()
	 * Builds an activity story with template data
	 * @param object $story
	 * @return object
	 */
	public function parseActivityStory( $story )
	{
		//Whose activity?
		$thisUser 	= TuiyoAPI::get("user", (int)$story->thisUserID );
		$thatUser 	= TuiyoAPI::get("user", (int)$story->thatUserID );
		
		//Check that we have the object ready
		if(!is_object($thisUser) || empty($thisUser)){
			$thisUser = TuiyoAPI::get("user", null ) ;
		}
		if(!is_object($thatUser) || empty($thatUser)){
			$thatUser = TuiyoAPI::get("user", null ) ;
		}
		
		$validVars     =  array(
			"{*thisUserID*}",	"{*thatUserID*}",	"{*thisUser*}",
			"{*thatUser*}",		"{*thisGSP1a*}",	"{*thisGSP2b*}",
			"{*thisGSP1c*}",	"{*thisGSP2d*}",	"{*thatGSP1e*}",
			"{*thatGSP2f*}",	"{*thatGSP1g*}",	"{*thatGSP2h*}"					
		);
		
		$thisProfileLink 	=  JRoute::_( TUIYO_INDEX.'&view=profile&do=view&user='.$thisUser->username );
		$thatProfileLink 	=  JRoute::_( TUIYO_INDEX.'&view=profile&do=view&user='.$thatUser->username );
		
		$validVarsData    	=  array(
			$thisUser->id, 	
			$thatUser->id, 
			!empty($thisUser)? '@'.$thisUser->username : null,
			!empty($thatUser)? '@'.$thatUser->username : null,
			((int)$thisUser->get("sex", 1) > 0) ? "his": "her" ,   //Gender specific pronoun
			((int)$thisUser->get("sex", 1) > 0) ? "he" : "she" ,   //Gender specific pronoun
			((int)$thisUser->get("sex", 1) > 0)? "His": "Her" ,   //Gender specific pronoun
			((int)$thisUser->get("sex", 1) > 0)? "He" : "She" ,   //Gender specific pronoun	
			((int)$thatUser->get("sex", 1) > 0) ? "his": "her" ,   //Gender specific pronoun
			((int)$thatUser->get("sex", 1) > 0) ? "he" : "she" ,   //Gender specific pronoun
			((int)$thatUser->get("sex", 1) > 0)? "His": "Her" ,   //Gender specific pronoun
			((int)$thatUser->get("sex", 1) > 0)? "He" : "She" ,   //Gender specific pronoun						
		);
		
		
		$story->bodytext 	= str_replace( $validVars , $validVarsData, $story->bodytext) ;
		$story->body 		= str_replace( $validVars , $validVarsData, $story->body ) ;
		$story->title 		= str_replace( $validVars , $validVarsData, $story->title ) ;
		$story->source		= sprintf( _("via %s") , strtolower( $story->source ) );
		
		//Parse Resources
		if(!empty($story->resources)) :
			
			$resources 	= json_decode( $story->resources );
			$attachment = '<div class="itemResources">';
			$count		= 0;
			$actionurl 	= !(empty($story->url)) ? $story->url : '#' ;
			
			foreach($resources as $r ):
				if($count >=6 ) break;
				switch($r->type):
					case "image":
						$actionurl   = substr( $r->furl , 1 )  ; 
						$attachment .= '<a href="'.$actionurl.'" rel="facebox"><img class="rImg" src="'.$r->url.'" width="70" /></a>';
					break;
					case "embedable":
						$attachment .= '<a href="#" rel="embedPlaceHolder"><img class="rImg" src="'.$r->url.'" width="150" /></a>';
					break;
				endswitch;
				$count++;
			endforeach;
			
			$story->body = $attachment."</div>".$story->body;
			
		endif;
		
		return (object)$story;		
	}
    
 	/**
 	 * TuiyoActivity::getInstance()
 	 * 
 	 * @param bool $ifNotExist
 	 * @return
 	 */
 	public function getInstance($ifNotExist = TRUE )
 	{ 
 		/** Creates new instance if none already exists ***/
		static $instance;
		
		if(is_object($instance)&&!$ifNotExist){
			return $instance;		
		}else{
			$instance = new TuiyoActivity()	;	
		}
		
		return $instance;	
  	}	    
}
