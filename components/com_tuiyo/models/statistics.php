<?php
/**
 * ******************************************************************
 * Tuiyo application                     *
 * ******************************************************************
 * @copyright : 2008 tuiyo Platform                                 *
 * @license   : http://platform.tuiyo.com/license   BSD License     * 
 * @version   : Release: $Id$                                       * 
 * @link      : http://platform.tuiyo.com/                          * 
 * @author 	  : livingstone[at]drstonyhills[dot]com                 * 
 * @access 	  : Public                                              *
 * @since     : 1.0.0 alpha                                         *   
 * @package   : tuiyo    yyeSyV0kysKV2oqpLUES5                                           *
 * ******************************************************************
 */

defined('TUIYO_EXECUTE') || die('Restricted access');

/**
 * joomla Model
 */
jimport('joomla.application.component.model');


class TuiyoModelStatistics extends JModel
{
	
	public function getUserStatusCount( $userID )
	{
		$tModel = TuiyoLoader::table('timeline');
		$tCount = $tModel->countUserActivities( $userID );
		
		return (int)$tCount;
	}
	
	public function getUserFriendsCount()
	{}
	
	public function getUserViewCount()
	{}
	
	public function getUserComments( $userID )
	{
		$tModel = TuiyoLoader::table('timeline');
		$tCount = $tModel->countUserActivities( $userID, NULL, NULL, TRUE );
		
		return (int)$tCount;		
	}
	
	public function getUserPhotoCount()
	{}
	
	public function getUserGroupCount()
	{}
	
	/**
	 * TuiyoModelStatistics::getUserStatistics()
	 * Returns an array with specific profile item counts
	 * @param mixed $userID
	 * @return void
	 */
	public function getUserStatistics( $userID )
	{
		$sCounts = array( 
			"updates"	=>	$this->getUserStatusCount( (int)$userID ),
			"replies"	=>  $this->getUserComments( (int)$userID ),
		);
		
		return (array)$sCounts ;
	}
		
}