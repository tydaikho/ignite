<?php
/**
 * ******************************************************************
 * Class/Object for the Tuiyo platform                              *
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
 * Event Listener Class
 * Plugins and Services extend this class ...
 * @author livingstonefultang
 *
 */
class TuiyoEventsListener{
	
	
	public function __construct( ){	
		
		global $events;
		/**
		 * Profile Events
		 */
		$events->onProfileCreate 		= new TuiyoDelegate( $this, "onProfileCreate" );		
		$events->onProfileBuild   		= new TuiyoDelegate( $this, "onProfileBuild" );		
		$events->onProfileSuspend 		= new TuiyoDelegate( $this, "onProfileSuspend" );		
		$events->onProfileDelete 		= new TuiyoDelegate( $this, "onProfileDelete" );		
		$events->onProfileUpdate 		= new TuiyoDelegate( $this, "onProfileUpdate" );		
		$events->onProfileWarn     		= new TuiyoDelegate( $this, "onProfileWarn" );		
		$events->onAfterProfileLogIn 	= new TuiyoDelegate( $this, "onAfterProfileLogin" );		
		$events->onBeforeProfileLogOut 	= new TuiyoDelegate( $this, "onBeforeProfileLogout" );
		$events->onProfileHomePageBuild = new TuiyoDelegate( $this, "onProfileHomePageBuild" );
		$events->onAfterProfileDraw 	= new TuiyoDelegate( $this, "onAfterProfileDraw" );
		
		/**
		 *	Admin Events
		 */	
		$events->onAdminStart 			= new TuiyoDelegate( $this, "onAdminStart" );
		
		/**
		 * Timeline Events
		 */
		$events->onTimelineLoad			= new TuiyoDelegate( $this, "onTimelineLoad" );
		$events->onAfterTimelineLoad	= new TuiyoDelegate( $this, "onAfterTimelineLoad" );
		$events->onBeforeTimelineLoad	= new TuiyoDelegate( $this, "onBeforeTimelineLoad" );
		$events->onAddTimelineComment	= new TuiyoDelegate( $this, "onAddTimelineComment" );
		$events->onAddTimelineVote		= new TuiyoDelegate( $this, "onAddTimelineVote" );
		
		/**
		 * Login Event Handlers		
		 */
		$events->onLogInDraw			= new TuiyoDelegate($this, "onLogInDraw");
		$events->onLoggedIn				= new TuiyoDelegate($this, "onLoggedIn");
		$events->onLoggedOut			= new TuiyoDelegate($this, "onLoggedOut");
		$events->onLogInFail			= new TuiyoDelegate($this, "onLogInFail");

		
		/**
		 * Messages
		 */
		$events->onNewMessage			= new TuiyoDelegate($this, "onNewMessage");
		
		/**
		 * Chat Messages
		 */
		$events->onNewChatMessage		= new TuiyoDelegate($this, "onNewChatMessage");
		$events->onChatRoomDraw			= new TuiyoDelegate($this, "onChatRoomDraw");
		$events->onChatRoomClose		= new TuiyoDelegate($this, "onChatRoomClose" );
		
	}
	
}