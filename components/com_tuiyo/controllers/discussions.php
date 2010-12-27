<?php
/**
 * ******************************************************************
 * Discussion controller object for the Tuiyo platform             *
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
jimport('joomla.application.component.controller');
/**
 * Tuiyo Controller
 */
TuiyoLoader::controller('core');
/**
 * TuiyoControllerApps
 * @package tuiyo
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoControllerDiscussions extends JController
{	
	/**
	 * Default task
	 * @see JController::display()
	 */
	public function display( $tpl  = null){
		
		global $API;
		
		$view = $this->getView("discussions", "html");
		$user = $API->get("user", null);
		
		$view->assignRef("user", $user);
		$view->setLayout("default");
		$view->SetLayoutExt("tpl");
		
		$view->display();		
	}
}