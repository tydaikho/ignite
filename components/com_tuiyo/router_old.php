<?php
/**
 * ******************************************************************
 * Core controller object for the Tuiyo platform                           *
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

// no direct access
defined('_JEXEC') or die('Restricted access');


/**
 * Function to convert a system URL to a SEF URL
 * TuiyoBuildRoute()
 * 
 * @param mixed $query
 * @return
 */
function TuiyoBuildRoute(&$query)
{
    //tuiyo.co.uk/username/view/action/format
    $segments = array();
    $menu = &JSite::getMenu();

    if (isset($query['view'])) {

        if (!empty($query['Itemid'])) {
            $menuItem = &$menu->getItem($query['Itemid']);
            if (!isset($menuItem->query['view']) || $menuItem->query['view'] != $query['view']) {
                $segments[0] = $query['view'];
            }
        } else {
            $itemId = TuiyoComponentID();
            JRequest::setVar("Itemid", $itemId);
            //$segments[0] = $query['view'];
        }
        unset($query['view']);
    }

    //swap userIds for username
    if (isset($query["userid"])) {
        $query["user"] = $query["userid"];
    }

    $count = count($query);
    $special_keys = array(1 => "user");
    foreach ($query as $key => $name) {
        if (in_array($key, $special_keys)) {
            $segments[array_search($key)] = $query[$name];
            unset($query[$name]);
        }
    }

    return $segments;
}

/**
 * Function to convert a SEF URL back to a system URL
 * TuiyoParseRoute()
 * 
 * @param mixed $segments
 * @return
 */
function TuiyoParseRoute($segments)
{
    $vars = array();
    //Get the active menu item
    $menu = &JSite::getMenu();
    $item = &$menu->getActive();

    $count = count($segments);
    //segment zero Always view!
    if (!empty($count)) {
        if (isset($segments[0]) && isset($item->query['view'])) {
            $vars['view'] = $segments[0];
        }
    }

    //If do isset the last element will always be the do!
    if ($count > 1 || isset($item->query["redirect"])) {
        if (isset($item->query["redirect"])) {
            $vars["redirect"] = $segments[$count - 1];
        } else {
            if ($count > 1) {
                if (isset($item->query["user"])) {
                    $vars["user"] = $segments[1];
                }
                if (isset($item->query["redirect"])) {
                    $vars["redirect"] = $segments[2];
                }
            }
        }
    }
    return $vars;
}

/**
 * Obtains the component ID from the user table
 * TuiyoComponentID()
 * 
 * @return
 */
function TuiyoComponentID()
{

    static $STATIC = null;

    if (!empty($STATIC)) {
        return $STATIC;
    }
    $db = &JFactory::getDBO();
    $query = "SELECT m.id FROM #__menu m" . "\n WHERE m.name = 'Tuiyo'" . "\n OR m.link = 'index.php?option=com_tuiyo' ";
    $db->setQuery($query);
    $cid = $db->loadResult();
    //TODO: error handling?
    $STATIC = $cid;

    return $cid;
}


/**
 * Determines the current user from request
 * TuiyoGetUserNameFromRequest()
 * 
 * @return
 */
function TuiyoGetUserNameFromRequest()
{

    $mainframe 	= $GLOBALS['mainframe'];
    $thatuser 	= null;

    //2. Identify the profile ID;
    $userID 	= JRequest::getVar('pid', null);
    $username 	= JRequest::getVar('user', null);

    if (!empty($userID)):

        $profileID 	= (int)$userID;
        $thatuser 	= &JFactory::getUser( empty($profileID) ? null : (int)$profileID);

    elseif (empty($userID) && !empty($username)):

        $username 	= strval($username);
        $thatuser 	= TuiyoGetUserID( $username );
        if (!is_object($thatuser)):
            $thatuser = &JFactory::getUser();;
        endif;

    else:

        $thatuser = &JFactory::getUser();

    endif;

        return (object)$thatuser;

}


/**
 * Returns a userobject from username 
 * TuiyoGetUserID()
 * 
 * @param mixed $username
 * @return
 */
function TuiyoGetUserID($username)
{

    $dbo = &JFactory::getDBO();
    $query = "SELECT u.id FROM #__users u " . "\nWHERE u.username=" . $dbo->Quote($username) .
        "\nLIMIT 1";
    $dbo->setQuery($query);
    $userID = $dbo->loadResult();

    //Return the userID
    return  JFactory::getUser( empty($userID) ? null : (int)$userID);
}
