<?php
/**
 * ******************************************************************
 * Articles model object for the Tuiyo platform             *
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
 * joomla Controller
 */
jimport('joomla.application.component.model');

/**
 * TuiyoControllerApps
 * @package tuiyo
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoModelArticles extends JModel
{
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
    
	/**
	 * Constructor for the Joomla Controller ...
	 */
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
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $context
	 * @param unknown_type $userid
	 */
	public function getArticlesStream($userid = null ){
		
		$rUser		= TuiyoAPI::get("user", null);
		$model 		= TuiyoLoader::model("timeline", true);
		$options 	= array("filter"=>"article");
		
		$stream 	= array();
		
		if(empty($userid)){
			$stream = $model->getPublicTimeline($rUser->id , $options);
		}else{
			//$stream = $model->getUserTimeline((int)$userid, $rUser->id, $options);
		}
		
		return $stream;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $user
	 * @param unknown_type $data
	 */
	public function editSaveArticle($user, $data){
		
		$aTable 	= TuiyoLoader::table("posts", true);
		$aTimeline  = TuiyoAPI::get("activity");
		
		$aID 		= isset($data["ID"])?(int)$data["ID"]:null;
		
		$aTable->load( $aID );
		
		//$data['postcontent'] = $this->removeTags( $data['postcontent'] );
		
		$aTable->bind( $data );
		
		$date 		= date('Y-m-d H:i:s');
		
		$aTable->author		= $user->id;
		$aTable->createdate = empty($aTable->ID)? $date : "" ;
		$aTable->posttitle  = $this->removeTags( $aTable->posttitle , "" );
		$aTable->postcontent= $this->removeTags( $aTable->postcontent );
		$aTable->postexcerpt= $this->removeTags( $aTable->postexcerpt, "");
		$aTable->postexcerpt= !empty($aTable->postexcerpt)? substr($aTable->postexcerpt, 0 , 200 ) : substr($aTable->postcontent, 0 , 200 );
		
		$aTable->poststatus		  = 0;
		$aTable->postmodified = empty($aTable->ID)? "": $date ; 
		$aTable->postname 	  = str_replace(array(" ","(",")","-","&","%",",","#" ), "-", substr($aTable->posttitle, 0 , 100) );
		$aTable->commentCount = 0;
		
		if(empty($aTable->author)||empty($aTable->posttitle)||empty($aTable->postcontent)){
			JError::raiseError(TUIYO_SERVER_ERROR, _("Articles require at least a title, and some content"));
			return false;
		}
		
		//Store the Article if successful
		if(!$aTable->store()){
			JError::raiseError(TUIYO_SERVER_ERROR, $aTable->getError());
			return false;
		}
		
		//Map Categories
		foreach( $data['categories'] as $category){
			
			$aCatMaps 	= TuiyoLoader::table("categoriesmaps", true);
					
			$aCatMaps->load( null ); //make sure we are loading a new element
			$aCatMaps->resourceid = $aTable->ID ;
			$aCatMaps->maptype 	  = "article";
			$aCatMaps->ownerid	  = $aTable->author;
			$aCatMaps->categoryid = (int)$category ; 
			
			if(!$aCatMaps->store()){
				JError::raiseError(TUIYO_SERVER_ERROR, $aCatMaps->getError());
				return false;
			}
			
			unset($aCatMaps);
		}
		
		$aLink			= TUIYO_INDEX.'&view=articles&do=read&aid='.$aTable->ID;
		
		$aActivityTmpl  = '<div class="activityBodyTitle"><a href="'.$aLink.'">'.$aTable->posttitle.'</a></div>';
		$aActivityTmpl .= $aTable->postexcerpt;
		
		
		//Publish activity story
		$aTimeline->publishOneLineStory($user, $aActivityTmpl, "article".$aTable->ID , NULL, NULL, array(), "article" );
		
		return true;
	}
	
	public function getArticlesCategories()
	{
		$model = TuiyoLoader::model("categories" , true);
		$categories = $model->getCategories();

		return $categories;
	}
	
	private function removeTags( $posttext , $validTags="<a><b><i><p><h1><h2><h3><img><pre>" ){
		
		$regExp = '#\s*<(/?\w+)\s+(?:on\w+\s*=\s*(["\'\s])?.+?\(\1?.+?\1?\);?\1?|style=["\'].+?["\'])\s*>#is'; 
		
    	return preg_replace($regExp, '<${1}>', strip_tags( $posttext, $validTags));
    	
        //return strip_tags( $posttext );
	}
	
}