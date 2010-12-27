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
 * TuiyoEventLoader
 * 
 * @version $Id$
 * @access public
 */
class TuiyoEventLoader
{
    /**
     * @var string   class name
     */
    protected $class = null;
    /**
     * @var array    class constructor arguments
     */
    protected $args = array();
    /**
     * @var string   class definition file
     */
    protected $file = null;

    /**
     * @param       string class name
     * @param       array  class constructor arguments
     * @param       string class definition filename, optional
     */
    public function __construct($class, $args, $file = null)
    {
        $this->class = (string )$class;
        $this->args = (array )$args;
        $this->file = (string )$file;
    }

    /**
     * Resolves a Handle; replaces a Handle instance with its identified class
     * @param       object passed by reference
     */
    static public function resolve(&$handle)
    {
        if ($handle instanceof self) {
            $class = $handle->getClass();
            $file = $handle->getFile();

            if (!class_exists($class) && !empty($file) && is_readable($file)) {
            	
            	//TODO: Complete implementation
                require_once ($file);
            }

            $handle = call_user_func_array(array(new ReflectionClass($class), 'newInstance'), $handle->getArgs() );
        }
    }
    
    public function preparePlugins( $eventGroup, $parameters = null ){
    	
    	global $PLUGIN_GROUPS;
    	
    	//Load all Event Group
    	$pGroup = $PLUGIN_GROUPS[$eventGroup];
    	
    	foreach($pGroup as $plugin=>$pFile){
    		
    		require_once($pFile);

    		$pClassName = "TuiyoPlugin".ucfirst($plugin);
    		
    		$plugClass =  new $pClassName();
    		
    		//$PLUGIN_GROUPS[$eventGroup][$plugin] = $plugClass;
    		
    		
    	}
    	
    	//print_R($PLUGIN_GROUPS);

    }

    public function getClass()
    {
        return $this->class;
    }
    
    public function getFile()
    {
        return $this->file;
    }
    
    public function getArgs()
    {
        return $this->args;
    }

}
