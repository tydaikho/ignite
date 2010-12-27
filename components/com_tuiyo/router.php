<?php
/**
* @version		$Id: router.php 14401 2010-01-26 14:10:00Z louis $
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

//View=>Service=>Group=>User=>action

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

function TuiyoBuildRoute(&$query)
{
	$segments = array();

	if(isset($query['view']))
	{
		$segments[] = $query['view'];
		unset($query['view']);
	}
	
	if(isset($query['controller'])){
		$segments[] = $query['controller'];
		unset($query['controller']);
	}
	
	if(isset($query['service'])){
		$segments[] = $query['service'];
		unset($query['service']);
	}
	
	//Must be the last element;
	if(isset($query['do'])){
		$segments[] = $query['do'];
		unset($query['do']);
	}
	
	return $segments;
}

function TuiyoParseRoute($segments)
{
	$vars 	= array();
	$i 		= 0;
	$count 	= count($segments);
	
	$views =  TuiyoGetViews();
	$controllers = TuiyoGetControllers();
	
	//Will never have a view + controller in Tuiyo
	if(!empty($count)) {
		if(in_array($segments[0], $views)){
			$vars['view'] = $segments[0];
			$i++;
		}elseif(in_array($segments[0], $controllers)){
			$vars['controller'] = $segments[0];
			$i++;
		}
	}
	switch((string)$segments[0]):
		case "services": 
			$vars['service'] = $segments[1];
			$i++;
			//$vars['do']		 = $segments[$count-1]; //the last is always the action;	
			break; 
	endswitch;

	if(($i+1)===$count){
		$vars['do'] = $segments[$i]; //the last element;
	}

	return $vars;
}

function TuiyoGetControllers(){
	
	$controllers = JFolder::files( JPATH_BASE.DS.'components'.DS.'com_tuiyo'.DS.'controllers'.DS, '.', false, false );
	
	foreach($controllers as $key=>$controller){
		$controllers[$key] = str_replace( ".php", "" , $controller );
	}
	
	return $controllers;
	
}

function TuiyoGetViews(){
	$views = JFolder::folders( JPATH_BASE.DS.'components'.DS.'com_tuiyo'.DS.'views'.DS );
	return $views;
}
