<?php
/**
 * ******************************************************************
 * TuiyoVersion Class/Object for the Tuiyo platform                 *
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
 * TuiyoVersion
 * 
 * @package Tuiyo For Joomla
 * @copyright 2009
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
    var $RELEASE = '1.1';
    /**
     *  *  * @var string Development Status */
    var $DEV_STATUS = 'RC';
    /**
     *  *  * @var int Sub Release Level */
    var $DEV_LEVEL = '0';
    /**
     *  *  * @var int build Number */
    var $BUILD = '431';
    /**
     *  *  * @var string Codename */
    var $CODENAME = '';
    /**
     *  *  * @var string Date */
    var $RELDATE = '17-September-2009';
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
    var $URL = '<a href="http://www.tuiyo.co.uk">Tuiyo!</a> is Free community building component for joomla released under the GNU/GPLv2.1';
    /**
     *  *  * @var string Email to bug tracker */
    var $DEV_BUG = 'bugs@joomunity.org';


    /**
     * TuiyoVersion::getLongVersion()
     * @return
     */
    public function getLongVersion(){
        return $this->PRODUCT . ' ' 
			 . $this->RELEASE . '.' 
			 . $this->DEV_LEVEL . ' ' 
			 . $this->DEV_STATUS . ' revision-' 
			 . $this->BUILD . ' [ ' 
			 . $this->CODENAME . ' ] ' 
			 . $this->RELDATE . ' ' 
			 . $this->RELTIME . ' ' 
			 . $this->RELTZ;
    }

    /**
     * TuiyoVersion::getShortVersion()
     * @return
     */
    public function getShortVersion(){
        return $this->RELEASE . '.' . $this->DEV_LEVEL;
    }

    /**
     * TuiyoVersion::getShortVersionBuild()
     * @return
     */
    public function getShortVersionBuild(){
        return $this->RELEASE . '.' . $this->DEV_LEVEL . ' build.' . $this->BUILD;
    }

    /**
     * TuiyoVersion::isCompatible()
     * @param mixed $minimum
     * @return
     */
    public function isCompatible($minimum){
        return (version_compare(JVERSION, $minimum, 'eq') == 1);
    }
    
    /**
     * TuiyoVersion::checkNewer()
     * @return void
     */
    public function checkNewer(){}
    
    public function getPHPVersion(){}
    
 	public function getInstance($ifNotExist = TRUE ){ 
 		
 		/** Creates new instance if none already exists ***/
		static $instance;
		if(is_object($instance)&&!$ifNotExist){
			return $instance;		
		}else{
			$instance = new TuiyoVersion()	;	
		}
		return $instance;	
  	}
}