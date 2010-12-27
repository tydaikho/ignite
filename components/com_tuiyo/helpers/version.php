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
 * TuiyoVersion
 * 
 * @package Joomla
 * @copyright 2010
 * @version $Id$
 * @access public
 */
class TuiyoVersion
{
    /**
     *  *  * @var string Product */
    var $PRODUCT = 'Tuiyo';
    /**
     *  *  * @var int Main Release Level */
    var $RELEASE = '2.0';
    /**
     *  *  * @var string Development Status */
    var $DEV_STATUS = 'alpha';
    /**
     *  *  * @var int Sub Release Level */
    var $DEV_LEVEL = '0';
    /**
     *  *  * @var int build Number */
    var $BUILD = '18';
    /**
     *  *  * @var string Codename */
    var $CODENAME = 'Zanya';
    /**
     *  *  * @var string Date */
    var $RELDATE = '04-Januar-2011';
    /**
     *  *  * @var string Time */
    var $RELTIME = '00:00';
    /**
     *  *  * @var string Timezone */
    var $RELTZ = 'GMT';
    /**
     *  *  * @var string Copyright Text */
    var $COPYRIGHT = 'Copyright (C) 2006 , 2007, 2008 , 2009 , 2010 Doctor Stonyhills. All rights reserved.';
    /**
     *  *  * @var string URL */
    var $URL = '<a href="http://www.tuiyo.co.uk">Tuiyo</a> is Free community building component for joomla released under the GNU General Public License v3';
    /**
     *  *  * @var string Email to bug tracker */
    var $DEV_BUG = 'bugs@tuiyo.co.uk';

    /**
     *
     *
     * @return string Long format version
     */
    function getLongVersion()
    {
        return $this->PRODUCT . ' ' . $this->RELEASE . '.' . $this->DEV_LEVEL . ' ' . $this->
            DEV_STATUS . ' build ' . $this->BUILD . ' [ ' . $this->CODENAME . ' ] ' . $this->
            RELDATE . ' ' . $this->RELTIME . ' ' . $this->RELTZ;
    }

    /**
     *
     *
     * @return string Short version format
     */
    function getShortVersion()
    {
        return $this->RELEASE . '.' . $this->DEV_LEVEL. '.' . $this->DEV_STATUS;
    }

    /**
     * @return string Short version format
     */
    function getShortVersionBuild()
    {
        return $this->RELEASE . '.' . $this->DEV_LEVEL . '.' . $this->BUILD;
    }

    /**
     * Compares two "A PHP standardized" version number against the current Joomla! version
     * @return boolean
     * @see http://www.php.net/version_compare
     */
    function isCompatible($minimum)
    {
    	$current = $this->getShortVersion();
    	
        return (version_compare($current, $minimum, 'eq') == 1);
    }
    
    /**
     * TuiyoVersion::isOutDated()
     * Checks if the current version of Tuiyo is out of date
     * @param mixed $latest
     * @return
     */
    function isOutDated($latest)
    {
    	$current = $this->getShortVersion();
    	
        return (version_compare($current, $latest, '<') == 1);
    }    

    /**
     * TuiyoVersion::getInstance()
     * Creates an Instance of this Class if none exists
     * @param bool $ifNotExist
     * @return
     */
    public function getInstance($ifNotExist = true)
    {
        /** Creates new instance if none already exists ***/
        static $instance;

        if (is_object($instance) && !$ifNotExist) {
            return $instance;
        } else {
            $instance = new TuiyoVersion();
        }
        return $instance;
    }
}
