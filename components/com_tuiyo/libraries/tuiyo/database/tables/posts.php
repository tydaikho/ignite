<?php
/**
 * ******************************************************************
 * TuiyoTableposts Class/Object for the Tuiyo platform             *
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
defined('TUIYO_EXECUTE') || die('Restricted access');


/**
 * @package Tuiyo For Joomla
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoTablePosts extends JTable{

  var	$ID 					= null;
  //`author` bigint(20) unsigned NOT NULL DEFAULT '0',
  var	$author 				= null;
  //`createdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  var	$createdate			= null;
  //`postcontent` longtext NOT NULL,
  var	$postcontent 			= null;
  //`posttitle` text NOT NULL,
  var	$posttitle 			= null;
  //`postexcerpt` text NOT NULL,
  var	$postexcerpt			= null;
  //`poststatus` varchar(20) NOT NULL DEFAULT 'publish',
  var	$poststatus 			= null;
  //`commentstatus` varchar(20) NOT NULL DEFAULT 'open',
  var	$commentstatus		= null;
  //`pingstatus` varchar(20) NOT NULL DEFAULT 'open',
  var	$pingstatus			= null;
  //`postpassword` varchar(20) NOT NULL DEFAULT '',
  var	$postpassword			= null;
  //`postname` varchar(200) NOT NULL DEFAULT '',
  var	$postname				= null;
  //`toping` text NOT NULL,
  var	$toping				= null;
  //`pinged` text NOT NULL,
  var	$pinged				= null;
  //`postmodified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  var	$postmodified			= null;
  //`postmodifiedgmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  var	$postmodifiedgmt		= null;
  //`postcontentfiltered` text NOT NULL,
  var	$postcontentfiltered	= null;
  //`postparent` bigint(20) unsigned NOT NULL DEFAULT '0',
  var	$postparent			= null;
  //`posttype` varchar(20) NOT NULL DEFAULT 'post',
  var	$posttype				= null;
  //`postmimetype` varchar(100) NOT NULL DEFAULT '',
  var	$postmimetype			= null;
  //`commentcount` bigint(20) NOT NULL DEFAULT '0',
  var	$commentcount			= null;
 
	/**
	 * TuiyoTableResources::__construct()
	 * @param mixed $db
	 * @return
	 */
	public function __construct($db)
	{
		parent::__construct("#__tuiyo_posts", "ID", $db);
	}
	
    /**
     * TuiyoTableResources::getInstance()
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
                TuiyoTablePosts::getInstance($db, $ifNotExist);
            }
        } else {
            $instance = new TuiyoTablePosts($db);
        }
        return $instance;
    }	
}