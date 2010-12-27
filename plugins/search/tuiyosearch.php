<?php
/**
 * ******************************************************************
 * Tuiyo application search                                         *
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
defined('_JEXEC') or die('Restricted access');

/**
 * Import the Joomla Plugin Library
 */
$mainframe->registerEvent('onSearch', 'plgSystemTuiyoSearch');
$mainframe->registerEvent('onSearchAreas', 'plgSystemTuiyoSearchAreas' );


function plgSystemTuiyoSearchAreas()
{
    static $areas = array(
		"profiles" 		=> "Profiles",
		"groups" 		=> "Groups",
		"resources" 	=> "Resources",
		"applications" 	=> "Applications",
		"photos" 		=> "Photos",
		"web"			=> "Web search"
	);
    return $areas;
}


function plgSystemTuiyoSearch($text, $phrase = '', $ordering = '', $areas = null)
{	
	//define('TUIYO_EXECUTE', 1);
	
	//require_once(JPATH_SITE.DS.'components'.DS.'com_tuiyo'.DS.'libraries'.DS.'tuiyo'.DS.'locale'.DS.'localize.php');
	require_once(JPATH_SITE.DS.'components'.DS.'com_tuiyo'.DS.'helpers'.DS.'search.php');
	
	//$localize 	= TuiyoLocalize::getInstance();
	$sector 	= JRequest::getVar("sector", null );
	$limitstart = JRequest::getVar('limitstart', 0);

 	$plugin 	=& JPluginHelper::getPlugin('search', 'tuiyosearch');
 	$pluginParams = new JParameter( $plugin->params );

	$limit 		= $pluginParams->get(  'search_limit'  );

	$searchText = trim( $text );
	
	//We don't like empty queries';
	if(empty($searchText)) return array();
	
	
	if (is_array( $areas )) {
		if (!array_intersect($areas, array_keys( plgSystemTuiyoSearchAreas() ) )) {
			return array();
		}
	}
	$newJArray = array();
	//switch area
	foreach((array)$areas as $area):
		switch($area):
		    case "profiles":
		    	//Get an instance of the search helper
				$searchOBJ  = TuiyoHelperSearch::getInstance();
				$profiles 	= $searchOBJ->searchProfiles( $searchText , $phrase , $ordering );
		    	foreach($profiles as $profile):
		    		 array_push($newJArray , $profile);
		    	endforeach;
		    break;
			case "web":
				//Because google allows only a maximum of 10 results per query 
				//if search limit is greater than 10 will need to repeat the query
				$maxResults 	= 10 ;
				$qTurns			= round((int)$limit / (int)$maxResults );	
				
				$googleResults 	= array();
				$gLimitStart 	= $limitstart ;
				
				for($i=0; $i<=$qTurns; $i++){
					$url 		= 'http://ajax.googleapis.com/ajax/services/search/web?rsz=large&v=1.0&q='
								. urlencode( $searchText )."&start=".$gLimitStart."&maxResults=".$maxResults;
					$getResults = getURL( $url );
					$gResults 	= json_decode( $getResults );
					$resultA    = isset($gResults->responseData) ? 
					              isset($gResults->responseData->results) ? $gResults->responseData->results : array() : array() ;
					
					//Push results into array              
					foreach((array)$resultA  as $result){
						array_push($googleResults, $result );
					};
					
					$gLimitStart= (int)($maxResults * ($i+1) );		
				}		
	
				foreach($googleResults as $row){
					
					$rowObject 			= new stdClass ;
					$rowObject->href 	= $row->url; 
					$rowObject->title 	= $row->titleNoFormatting ; 
					$rowObject->section = "Web search"; 
					$rowObject->created = "Powered by Google";
					$rowObject->text 	= $row->content ;
					$rowObject->browsernav = 1;
					
					$newJArray[] = $rowObject;	
				}
			break;
		endswitch;
	endforeach;
	
	return $newJArray ;
	
	//echo "searching here";
}

/**
 * getURL()
 * @param mixed $url
 * @return
 */
function getURL( $url ) 
{
    $parsed = parse_url($url);
    $host 	= $parsed["host"];
    $port 	= (!isset( $parsed["port"] ) || $port==0 ) ? 80 : $parsed["port"];
    $path 	= $parsed["path"];
    
    if ($parsed["query"] != "") $path .= "?".$parsed["query"];

    $out 	= "GET $path HTTP/1.0\r\nHost: $host\r\n\r\n";
    $fp 	= fsockopen($host, $port, $errno, $errstr, 30);
	$content= null;
	$body 	= false;
    
	$bytes 	= fwrite($fp, $out);
	    while (!feof($fp)) {
	        $lfp = fgets($fp, $bytes);
	        if ( $body ) $content.= $lfp;
	        if ( $lfp == "\r\n" ) $body = true;
	    }
    fclose($fp);
   
    return $content;
} 