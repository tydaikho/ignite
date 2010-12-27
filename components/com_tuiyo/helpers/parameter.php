<?php
/**
 * ******************************************************************
 * Tuiyo application                     *
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
 * TuiyoParameter
 * 
 * @package Joomla
 * @author stoney
 * @copyright 2010
 * @version $Id$
 * @access public
 */
class TuiyoParameter extends JParameter{
	
	/**
	 * Contstruct the Parent
	 */
	public function __construct($data, $path = '')
	{
		parent::__construct($data, $path);
	}
	
	/**
	 * TuiyoParameter::load()
	 * 
	 * @param mixed $data
	 * @param mixed $xmlFilePath
	 * @return
	 */
	public function load($key, $xmlFilePath = NULL)
	{
		$iniPath 	= TUIYO_CONFIG.DS.strtolower($key).".ini";
		$xmlFilePath= empty($xmlFilePath) ? TUIYO_CONFIG.DS.strtolower($key).".xml" : $xmlFilePath ;
		
		if (!file_exists($iniPath) || !is_file($xmlFilePath) ) {
			JError::raiserError(TUIYO_SERVER_ERROR, _('The required config element does not exists'));
			return false;
		}
		
		$content 	= file_get_contents( $iniPath );				 
		$params  	= new JParameter($content, $xmlFilePath);
		
		return $params;
	}
	
	/**
	 * TuiyoParameter::saveParams()
	 * 
	 * @param mixed $postParams
	 * @param mixed $key
	 * @param string $type
	 * @return
	 */
	public function saveParams( $postParams, $key, $type="system")
	{
		jimport('joomla.filesystem.file');
		jimport('joomla.client.helper');
		
		// Set FTP credentials, if given
		JClientHelper::setCredentialsFromRequest('ftp');
		
		$ftp 		= JClientHelper::getCredentials('ftp');	
		$file 		= TUIYO_CONFIG.DS.strtolower($key).".ini" ; 
	
		if(JFile::exists($file)){
			JFile::write($file);
		}
		
		if ( count( $postParams) )
		{
			$registry 	= new JRegistry();
			$registry->loadArray( $postParams );
			$iniTxt 	= $registry->toString();

			// Try to make the params file writeable
			if (!$ftp['enabled'] && JPath::isOwner($file) && !JPath::setPermissions($file, '0755')) {
				JError::raiseNotice('SOME_ERROR_CODE', _('Could not make the template parameter file writable'));
				return false;
			}
			
			//Write the file
			$return = JFile::write($file, $iniTxt );

			// Try to make the params file unwriteable
			if (!$ftp['enabled'] && JPath::isOwner($file) && !JPath::setPermissions($file, '0555')) {
				JError::raiseNotice('SOME_ERROR_CODE', _('Could not make the template parameter file unwritable'));
				return false;
			}

			if (!$return) {
				JError::raiseError(TUIYO_SERVER_ERROR, _("Could not save the template parameters"));
				return false;
			}
			
			return $return;
		}
		
	}
	
	public function saveUserParams()
	{}
	
	/**
	 * TuiyoParameter::renderHTML()
	 * 
	 * @param string $name
	 * @param string $group
	 * @return
	 */
	function renderHTML($name = 'params', $group = '_default')
	{
		if (!isset($this->_xml[$group])) {
			return false;
		}

		$params = $this->getParams($name, $group);
		$html = array ();
		$html[] = '<div class="paramlist admintable tuiyoTable">';

		if ($description = $this->_xml[$group]->attributes('description')) {
			// add the params description to the display
			$desc	= JText::_($description);
			$html[]	= '<div class="tuiyoTableRow"><div class="paramlist_description">'.$desc.'</div></div>';
		}

		foreach ($params as $param)
		{
			$html[] = '<div class="tuiyoTableRow" style="padding: 4px 0px">';

			if ($param[0]) {
				$html[] = '<div style="width: 35%" class="paramlist_key tuiyoTableCell"><span class="editlinktip">'.$param[0].'</span></div>';
				$html[] = '<div style="width: 65%" class="paramlist_value tuiyoTableCell">'.$param[1].'</div>';
			} else {
				$html[] = '<div class="paramlist_value tuiyoTableCell" style="width: 100%">'.$param[1].'</div>';
			}

			$html[] = '<div class="tuiyoClearFloat"></div>';
			$html[] = '</div>';
		}

		if (count($params) < 1) {
			$html[] = "<div class=\"TuiyoNoticeMsg\"><i>"._('There are no Parameters for this item')."</i></div>";
		}

		$html[] = '</div>';

		return implode("\n", $html);
	}
	
}