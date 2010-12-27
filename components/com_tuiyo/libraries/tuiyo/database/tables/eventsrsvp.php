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


/**
 * TuiyoTableEventsRsvp
 * @copyright 2010
 * @version $Id$
 * @access public
 */
class TuiyoTableEventsRsvp extends JTable{
	
	//`rsvpid` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	var $rsvpid 	= null ;
	//`eventid` INTEGER UNSIGNED NOT NULL,
	var $eventid 	= null ;
	//`userid` INTEGER UNSIGNED NOT NULL,
	var $userid		= null;	
	//`role` VARCHAR(45) DEFAULT 'guest',
	var $role		= null;
	//`notes` TEXT,
	var $notes		= null;
	//`approved` ENUM('0','1') NOT NULL DEFAULT 0,
	var $approved 	= null;
	
    /**
     * TuiyoTableEventsRsvp::__construct()
     * Constructor
     * @param mixed $db
     * @return
     */
    public function __construct($db = null)
    {
        return parent::__construct("#__tuiyo_events_rsvp", "rsvpid", $db );
    }
    	

    /**
     * TuiyoTableEventsRsvp::getInstance()
     * Gets an instance of the Tuiyo Events RSVP table
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
                TuiyoTableEventsRsvp::getInstance($db, $ifNotExist);
            }
        } else {
            $instance = new TuiyoTableEventsRsvp($db);
        }
        return $instance;
    }	
}