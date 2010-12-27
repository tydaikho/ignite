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
 
/**
 * no direct access
 */
defined('TUIYO_EXECUTE') || die('Restricted access');


class TuiyoTableInvites extends JTable{
	
	//CREATE TABLE `joomla`.`jos_tuiyo_invites` (
	//`ID` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	var $ID			= NULL;
	//`userid` INTEGER UNSIGNED NOT NULL,
	var $userid 	= NULL;
	//`datetime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	var $datetime 	= NULL;
	//`email` VARCHAR(150) NOT NULL,
	var $email 		= NULL;
	//`name` TEXT NOT NULL,
	var $name 		= NULL;
	//`state` ENUM('0','1') NOT NULL COMMENT '0 pendng, 1=activated',
	var $state		= NULL;
	//`acceptdate` TIMESTAMP,
	var $acceptdate = NULL;
	//`code` TEXT NOT NULL,
	var $code 		= NULL;
	//PRIMARY KEY (`ID`)
	//)
	//ENGINE = MyISAM
	//CHARACTER SET utf8 COLLATE utf8_general_ci;
	
	/**
	 * TuiyoTableInvites::__construct()
	 * @param mixed $db
	 * @return void
	 */
	public function __construct($db = null)
    {
        parent::__construct("#__tuiyo_invites","ID", $db);
    }
    
    /**
     * TuiyoTableInvites::generateCode()
     * Generates a random Invite Code
     * @return
     */
    public function generateCode()
	{
		return TuiyoAPI::random( 32 );
	}
	
	/**
	 * TuiyoTableInvites::findInvite()
	 * Finds an Invite from within the database
	 * @param mixed $code
	 * @return
	 */
	public function findInvite( $code )
	{
		$dbo 	= $this->_db;
		$query 	= "SELECT i.* FROM #__tuiyo_invites AS i"
				. "\nWHERE i.code = ".$dbo->Quote( strval( $code ) )
				. "\nAND i.state =".$dbo->quote( intval( 0 ) );
				
		$dbo->setQuery( $query );
		$invite = $dbo->loadOBject();
		
		if(!empty($invite) && is_object($invite) && isset($invite->code)){
			return $invite;
		}else{
			return array();
		}
	}
	
	/**
	 * TuiyoTableInvites::findAllMyInvites()
	 * Returns an array of all invite objects sent by UserID
	 * @param mixed $userID
	 * @return
	 */
	public function findAllMyInvites( $userID )
	{
		$dbo 	= $this->_db;
		$query 	= "SELECT i.* FROM #__tuiyo_invites AS i"
				. "\nWHERE i.userid = ".$dbo->Quote( intval( $userID ) )
				;
				
		$dbo->setQuery( $query );
		$invites= $dbo->loadOBjectList();
		
		return (array)$invites;
	}
	
	/**
	 * TuiyoTableInvites::hasExistingAccount()
	 * Checks an email does not have an existing account
	 * @param mixed $email
	 * @return
	 */
	public function hasExistingAccount( $email ){
		
		$dbo 	= $this->_db;
		$query 	= "SELECT COUNT(1) FROM #__users AS u"
				. "\nWHERE u.email = ".$dbo->Quote( $email );
		$dbo->setQuery( $query );
		$count 	= $dbo->loadResult();
		
		if((int)$count > 0 ){ return TRUE ; }else{ return FALSE; }
	}
    
    /**
     * TuiyoTableGroups::getInstance()
     * @param mixed $db
     * @param bool $ifNotExist
     * @return
     */
    public function getInstance($db = null, $ifNotExist = true)
    {
        /** Creates new instance if none already exists ***/
        static $instance = array();

        if (isset($instance) && !empty($instance) && $ifNotExist) {
            if (is_object($instance)) {
                return $instance;
            } else {
                unset($instance);
                TuiyoTableInvites::getInstance($db, $ifNotExist);
            }
        } else {
            $instance = new TuiyoTableInvites($db);
        }
        return $instance;
    }

}