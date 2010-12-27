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

class TuiyoDelegate implements iDelegate
{
    protected $subordinate = null;
    protected $method = null;

    public function __construct($subordinate, $method)
    {
        $this->subordinate 	= $subordinate;
        $this->method 		= $method;
    }

    public function invoke($args = null)
    {

        TuiyoEventLoader::resolve( $this->subordinate );
        return call_user_func_array(array($this->subordinate, $this->method), array( $this->arguments ) );
    }
}
