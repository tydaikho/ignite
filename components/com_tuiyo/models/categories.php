<?php
/**
 * ******************************************************************
 * Catefories model Class/Object for the Tuiyo platform               *
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
 * TuiyoModelCategories
 * @author stoney
 * @copyright 2010
 * @version $Id$
 * @access public
 */
class TuiyoModelCategories extends JModel{
	
    /**
     * Total number of items
     * @var integer
     */
    public $_total = null;

    /**
     * The Pagination object
     * @var object
     */
    public $_pagination = null;
    
   
  	public function getCategories($node = null){
   	
   		$catTable = TuiyoLoader::table("categories", true);
   		$catTree  = $catTable->getCategoriesTree();
   		
   		return $catTree;
   		   	
   	}
   	
   	public function addCategory( $data ){
   		
   		$catCreator		= TuiyoAPI::get("user", null );
   		$catTree 		= TuiyoLoader::table("categories" , true);
   		$catTree->load( null );
   		
   		$catTree->title			= trim( $data['cattitle'] );
   		$catTree->parent		= (int)$data['catpid'];
   		$catTree->slug			= trim( $data['catslug'] );
   		$catTree->creator		= (int)$catCreator->id ;
   		$catTree->description 	= trim($data['catdescription']);
   		$catTree->dateadded		= date('Y-m-d H:is');
   		$catTree->status		= (int)$data['catstatus'] ;
   		
   		//print_r( $catTree );

		if(!$catTree->store()){
			JError::raiseError(TUIYO_SERVER_ERROR, $catTree->getError());
			return false;
		}
   		
		//Restructure the Tree;
		$catTree->restructureTree();
   		
   		return true;
   	}
    
	
    public function __construct()
	{
	    parent::__construct();
	
	    global $mainframe, $option;
	
	    // Get pagination request variables
	    $limit 		= $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
	    $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
	
	    // In case limit has been changed, adjust it
	    $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
	
	    $this->setState('limit', $limit ); //$limit
	    $this->setState('limitstart', $limitstart);
	    
	    $this->pageNav  = NULL ;
	}    

}