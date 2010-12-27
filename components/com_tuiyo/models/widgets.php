<?php
/**
 * ******************************************************************
 * TuiyoWidgetModel for the Tuiyo   application                     *
 * ******************************************************************
 * @copyright : 2008 tuiyo Platform                                 *
 * @license   : http://platform.tuiyo.com/license   BSD License     * 
 * @version   : Release: $Id$                                       * 
 * @link      : http://platform.tuiyo.com/                          * 
 * @author 	  : livingstone[at]drstonyhills[dot]com                 * 
 * @access 	  : Public                                              *
 * @since     : 1.0.0 alpha                                         *   
 * @package   : tuiyo    yyeSyV0kysKV2oqpLUES5                                           *
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


class TuiyoModelWidgets extends JModel{
	
	/**
	 * TuiyoModelWidgets::addTab()
	 * Adds a Tab to the Tuiyo Widget Table;
	 * @param mixed $postData
	 * @param mixed $user
	 * @return
	 */
	public function addTab( $postData , $user )
	{	
		$wTable = TuiyoLoader::table("widgets", TRUE );
		$wTCols = intval($postData['tColumns']);		
		
		$wCols 		= array(
			1=>array( 50 , 49 ),
			2=>array( 35 , 64 ),
			3=>array( 33 , 33, 33),
			4=>array( 25 , 25, 24, 25)
		);
		//Now Add Columns
		$nCols = sizeof($wCols[$wTCols]);
		$wTab 	= array(
			"ID"	=> NULL,
			"title" => trim($postData["tTitle"]),
			"userID"=> $user->id ,
			"params"=> "{'cols':'".(int)$nCols."'}",
			"cols"	=> array(),
			"type"	=> "tab",
			"tabID"	=> NULL  //There seems to be a naming conflict
		);
		//Load an Empty 
		$wTable->load( NULL );
		
		if(!$wTable->bind( $wTab ) ){
			JError::raiseError(TUIYO_SERVER_ERROR, $wTable->getError());
			return false;
		}
		if(!$wTable->store( )){
			JError::raiseError(TUIYO_SERVER_ERROR, $wTable->getError());
			return false;
		}
		//Add Columns
		for($i=0; $i<$nCols; $i++):
			$wTab["cols"][] = $this->addTabColumn( $wTable->ID, $wCols[$wTCols][$i] , $user );
		endfor;
		
		$wTab["ID"]		= $wTable->ID ;
		$wTab["tabID"] 	= $wTable->ID ;
			
		return (array)$wTab;	
	}
	
	/**
	 * TuiyoModelWidgets::addTabColumn()
	 * Adds a column to a new Tab
	 * @param mixed $parent
	 * @param mixed $size
	 * @param mixed $user
	 * @return
	 */
	private function addTabColumn($parent, $size, $user )
	{
		$cTable = TuiyoLoader::table("widgets", TRUE );
		
		$cTab 	= array(
			"ID"		=> NULL,
			"parent" 	=> (int)$parent,
			"userID"	=> $user->id ,
			"size"		=> (int)$size,
			"type"		=> "column"
		);
		
		if(!$cTable->bind( $cTab ) ){
			JError::raiseError(TUIYO_SERVER_ERROR, $wTable->getError());
			return false;
		}
		if(!$cTable->store( )){
			JError::raiseError(TUIYO_SERVER_ERROR, $wTable->getError());
			return false;
		}
		
		$cTab["ID"] = $cTable->ID ;
		
		return (array)$cTab;
	}
	
	/**
	 * TuiyoModelWidgets::removeTab()
	 * Deletes a Tab and all child columns and widgets
	 * @param mixed $postData
	 * @param mixed $user
	 * @return TRUE
	 */
	public function removeTab( $postData , $user ){
		
		$cTable = TuiyoLoader::table("widgets", TRUE );
		
		if(!$cTable->deleteTab( (int)$postData["tabID"], $user->id ) ){
			JError::raiseError(TUIYO_SERVER_ERROR, _("Tuiyo could not delete the tab") );
			return false;
		}
		return true;
	}
	
	public function getMyPage( $userID ){
		
		$wTable 	= TuiyoLoader::table("widgets", TRUE );
		$columns 	= $wTable->getWidgets( (int)$userID );
		
		//Format into LayoutData;
		$layOutData = array();
		
		foreach($columns as $column):
			
			$tabID = $column->tabID ;
			$colID = $column->colID ;
			$widID = $column->widgetID ;
			$wItem = NULL;			
			$cItem = NULL;
			
			//ADD Tabs
			if(!is_array($layOutData[$tabID]) ):
				$layOutData[$tabID] = array(
					"id"	=> "t".$column->tabID,
					"title"	=> $column->tabTitle,
					"data"	=> array()
			 	);
			endif;
			//ADD columns
			if(!empty($colID) && !array_key_exists($colID, $layOutData[$tabID]["data"])):
				$cItem = array(
					"id" 	=> "c".$colID,
					"size"  => $column->colSize."%",
					"widgetData" => array()
				);
				//die;
				$layOutData[$tabID]["data"][$colID] = $cItem ;
			endif;
			//Add Widgets
			if(!empty($widID)):
				$wItem = array(
					"id" 	=> "w".$widID,
					"url"   => $column->widgetURL,
					"title" => $column->widgetTitle,
					"color" => "red",
					"params"=> json_decode( $column->widgetParams )
				);
				$layOutData[$tabID]["data"][$colID]["widgetData"][] = $wItem; 
			endif;
		endforeach;
		
		$layOut = array();
		
		foreach($layOutData as $tab ):
			 $tabItem = array(
			 	"id" 	=> $tab["id"],
			 	"title"	=> $tab["title"],
			 	"data"	=> array()
			 );
			 if(is_array($tab["data"])):
			 	foreach($tab["data"] as $column ):
			 		$tabItem["data"][] = $column ;
			 	endforeach;
			 endif;
		     $layOut[] = $tabItem;
		endforeach;
		
	    return $layOut ;
	}
	
	/**
	 * TuiyoModelWidgets::getAllWidgets()
	 * Gets all available widgets on this system
	 * @return
	 */
	public function getAllWidgets(){
		
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		
		$user 			= TuiyoAPI::get("user", null);
		
		$appModel 		= TuiyoLoader::model("applications", true);
		$sysPlugins		= $appModel->getAllUserPlugins($user->id, "services", false);
		
		$widgetData  	= array();
		$widgetFiles 	= array();
		
		foreach($sysPlugins as $plugin){
			//If the plugin has a widget file;
			$widgetFile = TUIYO_PLUGINS.DS.$plugin.DS.'widget.xml';
			if(file_exists($widgetFile)){
				$widgetFiles[] = $widgetFile;
			}
		}
		
		foreach($widgetFiles as $xmlFile ):
		
			$wdg 			= array();
			$xml 			= TuiyoAPI::get("xml", $xmlFile );
			$root 			= $xml->file->document;
			$wdg["version"]	= $root->attributes("version") ;
			$wdg["file"]	= JURI::root().str_replace( array( DS ), array("/")  , $xmlFile );
			$wdg["file"]	= str_replace(JPATH_ROOT,"", $wdg["file"]);	
				
			foreach((array)$root->widgetdata[0]->children() as $data):
				
				$dataName 	= $data->attributes("name");
				$dataValue 	= $data->attributes("content");
				
				if(empty($dataName) ||is_array($dataName)) continue;
				if(empty($dataValue)||is_array($dataValue)) continue ;
				
				$wdg[$dataName] = $dataValue ;	
			
			endforeach;			
			
			$widgetData[] = $wdg;
			
		endforeach;
		
		return (array)$widgetData;
	}	
}