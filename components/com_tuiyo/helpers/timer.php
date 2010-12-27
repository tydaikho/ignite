<?php
/**
 * ******************************************************************
 * Tuiyo Application entry                                          *
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
defined('TUIYO_EXECUTE') or die('NotAuthorised');


/**
 * TuiyoTimer
 * @package Tuiyo For Joomla
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoTimer
{

    /**
     * TuiyoTimer::diff()
     * Computes time difference then and now
     * @param mixed $time use PHP strtotime to force conversion to Time
     * @param mixed $opt
     * @return string ex. 10 mins ago
     */
    public function diff($time, $opt = array())
    {
        $defOptions = array(
			'to' 	=> 0, 
			'parts' => 1, 
			'precision' => 'sec', 
			'distance' 	=> true,
            'separator' => ', '
		);
        $opt 		= array_merge($defOptions, $opt); (!$opt['to']) && ($opt['to'] = time());
        $str 		= '';
        $diff 		= ($opt['to'] > $time) ? $opt['to'] - $time : $time - $opt['to'];
        $periods 	= array(
			'decade'=> 315569260, 
			'year' 	=> 31556926, 
			'month' => 2629744,
            'week' 	=> 604800, 
			'day' 	=> 86400, 
			'hour' 	=> 3600, 
			'min' 	=> 60, 
			'sec' 	=> 1);
			
        if ($opt['precision'] != 'second') {
            $diff 	= round(($diff / $periods[$opt['precision']])) * $periods[$opt['precision']];
        }
        (0 == $diff) && ($str = 'less than 1 ' . $opt['precision']);
        foreach ($periods as $label => $value) {
            (($x = floor($diff / $value)) && $opt['parts']--) && $str .= ($str ? $opt['separator'] :
                '') . ($x . ' ' . $label . ($x > 1 ? 's' : ''));
            if ($opt['parts'] == 0 || $label == $opt['precision']) {
                break;
            }
            $diff -= $x * $value;
        }
        $opt['distance'] && $str .= ($str && $opt['to'] > $time) ? ' ago' : ' away';

        return $str;
    }    
	
	public function TuiyoTimer()
    {}

    public function startTimer()
    {}

    public function stopTimer()
    {}

    public function getInstance()
    {}
}
