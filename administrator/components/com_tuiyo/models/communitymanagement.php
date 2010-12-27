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
 * joomla MOdel
 */
jimport( 'joomla.application.component.model' );

/**
 * TuiyoModelCommunityManagement
 * 
 * @package Livingstone Workspace
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoModelCommunityManagement extends JModel{
	
	/**
	 * TuiyoModelCommunityManagement::getUsers()
	 * 
	 * @param mixed $limitTo
	 * @param bool $join
	 * @return
	 */
	public function getUsers( $limitTo = array() , $join = true ){
		
		$dbo	= &$this->_db;		
		$ltfu	= (empty($limitTo))? "*" : $this->_ltf( $limitTo , true );
		$joiner = ($join) ? "\nRIGHT JOIN #__tuiyo_users p on u.id = p.userID " : null; 
		
		$query 	= "SELECT $ltfu FROM #__users u"
				. $joiner
				;
				
		$dbo->setQuery( $query );
		$rows 	=  $dbo->loadAssocList( );
		
		return $rows;
		
	}
	
	/**
	 * TuiyoModelCommunityManagement::_ltf()
	 * 
	 * @param mixed $arrayFields
	 * @param bool $prefix
	 * @param mixed $prefixKey
	 * @return
	 */
	private function _ltf( $arrayFields, $prefix = false, $prefixKey = null){
		
		if($prefix){
			$array2string	= array();
			
			foreach($arrayFields as $key=>$field){
				if(!is_int($key)){
					if(is_array($field)){
						foreach($field as $k=>$f ){
							$array2string[] = $key.".".$f ;
						}
					}
				}
			}
			$arrayFields 	= $array2string ;	
		}
		$string = implode(",", $arrayFields );
		
		return $string;
	}
	
	
	public function getUniqueUser( $userID ){}
	
	public function getProfiles( $limitTo = array() ){}
	
	public function getUniqueProfile( ){}
	
	public function applyBlock( $userID ){}
	
	public function applyDelete( $userID ){}
	
	public function getUsersReports(){}
	
}