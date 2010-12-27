<?php
/**
 * ******************************************************************
 *  Validation class for the Tuiyo platform                               *
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


defined('TUIYO_EXECUTE') or die('Restricted Access');

/**
 * TuiyoValidate
 * 
 * @package Joomla
 * @author stoney
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoValidate
{

    /**
     * TuiyoValidate::TuiyoValidate()
     * Constructs the validation class
     * @return void
     */
    public function TuiyoValidate()
    {}
    
    /**
     * TuiyoValidate::string()
     * @param mixed $string
     * @return
     */
    public function string( $string ){
    	return strval( $string );
    }
    
    /**
     * TuiyoValidate::isEmail()
     * Checks if email is valid
     * @param mixed $string
     * @return bool true if valid, false if not
     */
    public function isEmail( $string ){
    	
		if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $string)) {
		  return TRUE;
		}
		return FALSE;
    }
    
    /**
     * TuiyoValidate::link()
     * Validates a string
     * @param mixed $string
     * @return
     */
    public function link( $string )
	{	//TODO complete link validation her;
		return $string;
	}
    
    /**
     * TuiyoValidate::alphaNumeric()
     * Alpha numeric string validation, 
     * @param mixed $string
     * @param bool $allowSpace, spaces within string
     * @return void
     */
    public function alphaNumeric( $string , $allowSpace = FALSE )
	{
		//TODO: check for words and if allowspace true, validate seperately
		$string = !($allowSpace) ? str_replace(" ", "", $string ) : $string;
		
		preg_match('/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/', $string);
		
		return (string)$string;
	}
    
    /**
     * TuiyoValidate::bool()
     * @param mixed $boolean
     * @return
     */
    public function boolean( $boolean ){
    	return (bool)$boolean ;
    }
    
    /**
     * TuiyoValidate::int()
     * @param mixed $interger
     * @return
     */
    public function interger( $interger ){
    	return intval( $interger ) ;
    }

    /**
     * TuiyoValidate::getInstance()
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
            $instance = new TuiyoValidate();
        }
        return $instance;
    }

}
