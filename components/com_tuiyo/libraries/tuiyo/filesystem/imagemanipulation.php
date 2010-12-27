<?php
/**
 * ******************************************************************
 * Image object for the Tuiyo platform                               *
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

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.path');


/**
 * TuiyoImageManipulation
 * @package Tuiyo For Joomla
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoImageManipulation
{
	
	/**
	 * TuiyoImageManipulation::TuiyoImageManipulation()
	 * @return void
	 */
	function TuiyoImageManipulation(){
		
		$this->dimensionX = NULL;
		$this->dimensionY = NULL;
		$this->width	  = NULL;
		$this->height	  = NULL;
		$this->target	  = NULL;
		
	}
	
 	/**
 	 * TuiyoImageManipulation::createImage()
 	 * @param mixed $image
 	 * @param mixed $ext
 	 * @return
 	 */
 	private function createImage($image, $ext){
          switch ( strtolower($ext) ):
              case "gif": $i = imagecreatefromgif($image); 	break;
              case "jpg":
              case "jpeg":$i = imagecreatefromjpeg($image); 	break;
              case "png": $i = imagecreatefrompng($image);	break;
          endswitch;
          
          return $i;
      }
      
    /**
     * TuiyoImageManipulation::resizeImage()
     * @param mixed $image
     * @param mixed $width
     * @param mixed $height
     * @param bool $square
     * @return
     */
    public function resizeImage($image, $target, $width=null, $height=null, $square=false)
    {
        $this->dimensionX = (!empty($width)) ? (int)$width : $this->dimensionX;
        $this->dimensionY = (!empty($height) && !$square) ? (int)$height : "";
		$this->target	  = $target;
		
        if (!$square) $this->dimensionY = "auto";
 
		$ext 			= JFile::getExt( basename( $image ) );
        $ourimage 		= $this->createImage($image, $ext);
        $currX 			= @imagesx($ourimage);
        $currY 			= @imagesy($ourimage);
        $newX  			= $this->dimensionX;
        $newY 			= $this->dimensionY;
        
        //Destroy the image
        @imagedestroy($image);

        if($square) return $this->createSquare($image, $this->target, $newX);

		$_x = $newX;
		$_y = ($newX/ $currX) * $currY;

		//Get True Color
		$truecolor = @imagecreatetruecolor($_x, $_y);
        if (!$truecolor) {
            trigger_error("could not create a true color image", E_USER_ERROR);
            return false;
        }
        if (!@imagecopyresampled($truecolor, $ourimage, 0, 0, 0, 0, $_x, $_y, $currX, $currY)) {
            trigger_error("could not create a true color image", E_USER_ERROR);
            return false;
        }
        if (!@imagejpeg($truecolor, $this->target, 85)) {
            trigger_error("svae the target jpg image" , E_USER_ERROR);
            return false;
        }
        return true;
    }


    /**
     * TuiyoImageManipulation::createSquare()
     * @param mixed $source
     * @param mixed $target
     * @param mixed $width
     * @return
     */
    public function createSquare($source, $target, $width)
    {
        $tWidth 		= $width;
        $tHeight 		= $width;
        $imgdata 		= getimagesize($source);
        $widthOrig 		= $imgdata[0];
        $heightOrig 	= $imgdata[1];
        $ext 			= JFile::getExt( basename($source) );
        $image 			= $this->createImage($source, $ext);
        
        //Proportions
        if ($widthOrig < $heightOrig) {
            $height 	= ($tWidth / $widthOrig) * $heightOrig;
        	} else {
            $width 		= ($tHeight / $heightOrig) * $widthOrig;
       	}
       	
       	//If square this does not really matter? but..
        if ($width < $tWidth) {
            $width 		= $tWidth;
            $height 	= ($tWidth / $widthOrig) * $heightOrig;
        }
        //If square this does not really matter? but..
        if ($height < $tHeight) {
            $height 	= $tHeight;
            $width 		= ($tHeight / $heightOrig) * $widthOrig;
        }
        
        //Create True Coolor 
        $thumb 		= imagecreatetruecolor($width, $height);
        
        if (!imagecopyresampled($thumb, $image, 0, 0, 0, 0, $width, $height, $widthOrig, $heightOrig)) {
            trigger_error("a problem errored", E_USER_ERROR);
            return false;
        }
        
        $w1 = ($width / 2) - ($tWidth / 2);
        $h1 = ($height / 2) - ($tHeight / 2);
        
        $thumb2 	= imagecreatetruecolor($tWidth, $tHeight);
        if (!imagecopyresampled($thumb2, $thumb, 0, 0, $w1, $h1, $tWidth, $tHeight, $tWidth, $tHeight)) {
            trigger_error( "a problem errored" , E_USER_ERROR );
            return false;
        }
        if (!imagejpeg($thumb2, $target, 85)) {
        	 trigger_error( "a problem errored" , E_USER_ERROR );
            return false;
        }
        
        imagedestroy($thumb);
        imagedestroy($thumb2);
        
        return true;
        
    }


    /**
     * TuiyoImageManipulation::getInstance()
     * @param mixed $db
     * @param bool $ifNotExist
     * @return
     */
    public function getInstance($ifNotExist = true)
    {
        /** Creates new instance if none already exists ***/
        static $instance = array();

        if (isset($instance) && !empty($instance) && $ifNotExist) {
            if (is_object($instance) && $instance instanceof self ) {
                return $instance;
            } else {
                unset($instance);
                TuiyoImageManipulation::getInstance();
            }
        } else {
            $instance = new TuiyoImageManipulation();
        }
        return $instance;
    }

}
