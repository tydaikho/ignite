<?php
/**
 * ******************************************************************
 * TuiyoTableCategories Class/Object for the Tuiyo platform   *
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
 * TuiyoTableGroupsCategories
 * @package Tuiyo For Joomla
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoTableCategories extends JTable{
	
	//  `catID` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	var $id 			= null;
	//	`parentID` INTEGER UNSIGNED,
	var $parent			= 0;
	//	`cName` VARCHAR(100) NOT NULL,
	var $title			= null;
	//left
	var $lft			= null;
	//right
	var $rgt			= null;
	//	`thumb48` VARCHAR(200),
	var $thumb			= null;
	//	`description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
	var $description 	= null;
	//	`isPublished` BOOLEAN NOT NULL DEFAULT 1,
	var $status			= null;
	//	`creatorID` INTEGER UNSIGNED NOT NULL,
	var $creator		= null;
	//	`dateAdded` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	var $dateadded		= null;
	//	`groupCount` INTEGER UNSIGNED NOT NULL DEFAULT 0,
	var $slug			= null;
	//Stores the category parameters
	var $params 		= null;
	//Stores the category attributes
	var $attributes  	= null;
	
	/**
	 * TuiyoTableGroupsCategories::__construct()
	 * @param mixed $db
	 * @return
	 */
	public function __construct($db)
	{
		parent::__construct("#__tuiyo_categories" , "id", $db );
	}
	
	public function deteleCategory( $catID ){
		
		
	}
	
	
	public function restructureTree($level=null, $lft=1){
		
		$dbo	=& $this->_db;
   	
   		$level	= ( !is_null( $level ) && (int)$level > 0 )? $level : 0;
   		
   		$sql1	= "SELECT node.* FROM #__tuiyo_categories node WHERE parent=".$dbo->Quote( (int)$level );
   		$update = "";	
   		
   		$dbo->setQuery( $sql1 );
   		$nodes 	= $dbo->loadAssocList();
   		
   		//check if parents have childen
   		foreach($nodes as $key=>$node){ 
   			$i++;
   			//echo "%".$node['title']." lft = '$lft'%";
   			$node_count = 0;
   			$sql2   	= "SELECT count(*) as children FROM #__tuiyo_categories node WHERE parent=".$dbo->Quote( (int)$node['id'] ); 
   				
   			$dbo->setQuery( $sql2 );
   			$node_count = $node_count + (int)$dbo->loadResult();
   				
   			if($node_count > 0):
   				$node_count2 = $node_count + $this->restructureTree( (int)$node["id"] , $lft+1 );   				
   				$rgt 		= (2*$node_count2)+$lft+1 ;
   				
   				$update .= "\nUPDATE #__tuiyo_categories SET lft=".$lft.", rgt=".$rgt."WHERE id=".$node['id'] ;
   				
   				//echo $node['title']." has $node_count children lft=$lft and right=$rgt<br/>";
   				
   			else:
   				$rgt = 2*($node_count)+$lft+1 ;   					
   				//echo $node['title']." has $node_count children lft=$lft and right=$rgt <br/>"; 
   				$update .= "\nUPDATE #__tuiyo_categories SET lft=".$lft.", rgt=".$rgt." WHERE id=".$node['id'] ;
   								
   			endif;
   					
   			$lft = $rgt+1;
   			//echo "%/".$node['title']. " rgt ='$rgti' %<br />";
   		} 

   		//echo $update ;
   		//Close Statement
   		//$dbo->setQuery(  $update );
   		//$dbo->query();
   		
   		return isset($node_count2) ? $node_count2 : $node_count;
	}
	
	public function getCategoriesTree( $level = null){
    	
		$dbo 		=& $this->_db;
		$level		= ( !is_null( $level ) && (int)$level > 0 )? $level : 0;
		
		//If no root node, select all root nodes
		$sql1		= "SELECT node.*, '' as children, '' as indent FROM #__tuiyo_categories node WHERE parent=".$dbo->Quote( (int)$level );
    	
    	//Get the first node;
    	$dbo->setQuery( $sql1 );    	
    	$nodes		= $dbo->loadAssocList();
    	
    	//Check if each of the parent nodes have children
    	//if rgt-lft is greater than 1 then we have children
    	//set this node as parent node and call this function recursively
    	foreach($nodes as $key=>$node){ 
    		$node['indent']			= 0;   	
    		$node_count = 0;
   			$sql2   	= "SELECT count(*) as children FROM #__tuiyo_categories node WHERE parent=".$dbo->Quote( (int)$node['id'] ); 
   				
   			$dbo->setQuery( $sql2 );
   			$node_count = (int)$dbo->loadResult();
   				
    		if( $node_count > 0 ) :
    			//if we have kids, 
    			//select all of them and add to the children array!
    			$level 			   = $node['id'];
    			$nodes[$key]['children'] = $this->getCategoriesTree( (int)$level );     		
    		else:
    			$node['children']  = array();
    			$node['indent']	   = 0;
    			continue;
    		endif;    	
    	}    	
  		return $nodes;
    }
    
	
	/**
	 * TuiyoTableCategories::getCategories()
	 * @param mixed $fields
	 * @param bool $order  if true orders by name
	 * @param bool $group  if true groups per parent id
	 * @param bool $published
	 * @return void
	 */
	public function getCategories($fields = array(), $published=TRUE, $order=TRUE )
	{
		$SELECT = "SELECT *";
		$db		= &$this->_db;
		//Fields
		if(!empty($fields) && is_array($fields)){
			$fieldsVar = "SELECT ";
			$fields    = (array)$fields;
			$fieldCount= count($fields);
			for($i=0; $i<$fieldCount; $i++){
				if($i == ($fieldCount-1) ){
					$fieldsVar .= "c.".$fields[$i];
					break;
				}
				$fieldsVar .= "c.".$fields[$i].", ";
			}
		}
		//Start Building the Query
		$query = ( isset($fieldsVar)&&!empty($fieldsVar) ) ? $fieldsVar : $SELECT;
		$query.= "\nFROM #__tuiyo__categories as c";
		$query.= "\nWHERE c.status=".$db->Quote( (bool)$published );
		$query.= ($order)? "\nORDER BY c.parent, c.cName ASC"		: NULL;
		
		$db->setQuery( $query );
	
		return (array)$db->loadAssocList();
	}
	
	public function displayTree($node = null){
		
		$query ="SELECT node.name
				 FROM nested_category AS node,
				 nested_category AS parent
				 WHERE node.lft BETWEEN parent.lft AND parent.rgt
				 AND parent.name = 'ELECTRONICS'
				 ORDER BY node.lft;"
		;
		
		
	}
	
    /**
     * TuiyoTableGroupsCategories::getInstance()
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
                TuiyoTableCategories::getInstance($db, $ifNotExist);
            }
        } else {
            $instance = new TuiyoTableCategories($db);
        }
        return $instance;
    }
}