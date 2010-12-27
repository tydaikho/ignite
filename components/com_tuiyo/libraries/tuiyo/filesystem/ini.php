<?php
/**
 * ******************************************************************
 * INI object for the Tuiyo platform                               *
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

defined('TUIYO_EXECUTE') || die;


/**
 * TuiyoIni
 * 
 * @package tuiyo
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoIni
{

    /**
     *The Current Filename
     */
    public $iniFile = null;

    private $cmtChars = array(";", "#", "//");

    private $values = array();

    private $sections = array();

    private $lines = null;

    /**
     * TuiyoIni::__construct()
     * 
     * @return
     */
    public function __construct($filename)
    {
        //Save the filename
        $this->iniFile = trim($filename);
        if (!file_exists($this->iniFile)) {
            trigger_error(sprintf( _("The required ini file %s was not found"), $filename), E_USER_ERROR);
        }
        $this->sections = array();
        $this->values = array();
        $this->lines = file($this->iniFile);
        $this->lineNo = 1;

        foreach ($this->lines as $line) {
            if (empty($line) || in_array(substr($line, 0, 1), $this->cmtChars)) {
                $this->lineNo++;
                continue;
            }
            list($key, $value) = explode('=', $line, 2);
            $this->values[trim($key)] = trim($value);
            $this->lineNo++;
        }
        return $this;
    }

    /**
     * TuiyoIni::getValue()
     * 
     * @return
     */
    public function getValue()
    {
    }

    /**
     * TuiyoIni::getLine()
     * 
     * @return void
     */
    public function getLine()
    {
    }

    /**
     * TuiyoIni::iniToArray()
     * 
     * @return
     */
    public function iniToArray()
    {
    }

    /**
     * TuiyoIni::arrayToIni()
     * 
     * @param mixed $array
     * @return
     */
    public function arrayToIni($array)
    {
    }

    /**
     * TuiyoIni::getInstance()
     * 
     * @param bool $IfNotExists
     * @return
     */
    public function getInstance($fileName, $IfNotExists = true)
    {
        static $instance = array();

        if (isset($instance['fn:' . $fileName]) && $ifNotExist) {
            if (is_object($instance['fn:' . $fileName])) {
                return $instance['fn:' . $fileName];
            } else {
                unset($instance['fn:' . $fileName]);
                TuiyoIni::getInstance($fileName, $ifNotExist);
            }
        } else {
            $instance['fn:' . $fileName] = new TuiyoIni($fileName);
        }
        return $instance['fn:' . $fileName];
    }

}
