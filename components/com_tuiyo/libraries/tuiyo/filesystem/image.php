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
 * TuiyoImages
 * @package tuiyo
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoImage
{

    /**
     * TuiyoImage::TuiyoImage()
     * @param mixed $imgSrc
     * @param mixed $imgWidth
     * @param mixed $imgHieght
     * @param integer $Quality
     * @return void
     */
    public function TuiyoImage($imgSrc, $imgWidth, $imgHieght, $imgQuality = 85)
    {

        $imgType = JFile::getExt(basename($imgSrc));
        $imgErr = TUIYO_FILES . DS . 'noimage.jpg';
        //Image Parameters
        list($width, $height) = getimagesize($imgSrc);

        if ($width > $imgWidth || $height > $imgHieght) {
            if ((int)$imgWidth <> (int)$imgHieght) {
                $ratio = ($width > $height) ? $imgWidth / $width : $imgHieght / $height;
                $newWidth = $width * $ratio;
                $newHeight = $height * $ratio;
            } else {
                $tWidth = $imgWidth;
                $tHeight = $imgHeight;
                $imgdata = getimagesize($imgSrc);
                $widthOrig = $imgdata[0];
                $heightOrig = $imgdata[1];

                //Make Square
                if ($widthOrig < $heightOrig) {
                    $newHeight = ($tWidth / $widthOrig) * $heightOrig;
                } else {
                    $newWidth = ($tHeight / $heightOrig) * $widthOrig;
                }
                //Adjust width
                if ($newWidth < $tWidth) {
                    $newWidth = $tWidth;
                    $newHeight = ($tWidth / $widthOrig) * $heightOrig;
                }
                if ($newHeight < $tHeight) {
                    $newHeight = $tHeight;
                    $newWidth = ($tHeight / $heightOrig) * $widthOrig;
                }
                //$imgSrc = $imageR1;
            }
        } else {
            list($width, $height) = getimagesize($imgSrc);

            $ratio = ($width > $height) ? $imgWidth / $width : $imgHieght / $height;
            $newWidth = $imgWidth;
            $newHeight = ($imgWidth / $width) * $height;

        }

        //Type based
        switch ($imgType):
                //PNG
            case "png":
                header('Content-type: image/png');
                $imageX = imagecreatetruecolor($newWidth, $newHeight);
                $image = imagecreatefrompng($imgSrc);
                if ($width > $height) {
                    $w1 = ($width - $height) / 2;
                    $w1 = ceil($w1);
                    $h1 = 0;
                } else
                    if ($height > $width) {
                        $w1 = 0;
                        $h1 = ($height - $width) / 2;
                        $h1 = ceil($h1);
                    }
                imagecopyresampled($imageX, $image, 0, 0, $w1, $h1, $newWidth, $newHeight, $width,
                    $height);
                imagepng($imageX, null, $imgQuality);
                imagedestroy($imageX);
                break;
                //JPEG
            case "jpg":
            case "jpeg":
                header('Content-type: image/jpeg');
                $image = imagecreatefromjpeg($imgSrc);

                $imageX = imagecreatetruecolor($imgWidth, $imgHieght);

                if ($width > $height) {
                    $w1 = ($width - $height) / 2;
                    $w1 = ceil($w1);
                    $h1 = 0;
                } else
                    if ($height > $width) {
                        $w1 = 0;
                        $h1 = ($height - $width) / 2;
                        $h1 = ceil($h1);
                    }

                //exit(0);
                imagecopyresampled($imageX, $image, 0, 0, $w1, $h1, $imgWidth, $imgHieght, $imgWidth,
                    $imgHieght);
                imagejpeg($imageX, null, $imgQuality);
                imagedestroy($imageX);

                break;
                //GIF
            case "gif":
                header('Content-type: image/gif');
                $imageX = imagecreatetruecolor($newWidth, $newHeight);
                $image = imagecreatefromgif($imgSrc);
                if ($width > $height) {
                    $w1 = ($width - $height) / 2;
                    $w1 = ceil($w1);
                    $h1 = 0;
                } else
                    if ($height > $width) {
                        $w1 = 0;
                        $h1 = ($height - $width) / 2;
                        $h1 = ceil($h1);
                    }
                imagecopyresampled($imageX, $image, 0, 0, $w1, $h1, $newWidth, $newHeight, $width,
                    $height);
                imagegif($imageX, null, $imgQuality);
                imagedestroy($imageX);
                break;
        endswitch;

        exit(0);
    }

    /**
     * TuiyoImage::getInstance()
     * @param bool  $ifNotExist
     * @param array $params
     * @return
     */
    public function getInstance($params = array(), $ifNotExist = true)
    {
        /** Creates new instance if none already exists ***/
        static $instance = array();

        if (isset($instance[$params["s"]]) && !empty($instance[$params["s"]]) && $ifNotExist) {
            if (is_object($instance)) {
                return $instance[$params["s"]];
            } else {
                unset($instance[$params["s"]]);
                TuiyoImage::getInstance($ifNotExist, $params);
            }
        } else {
            $args = array("source" => (isset($params["s"])) ? $params["s"] : "", "width" =>
                (isset($params["h"])) ? $params["h"] : "", "height" => (isset($params["w"])) ? $params["w"] :
                "", "quality" => (isset($params["q"])) ? $params["q"] : 85, );
            $instance[$params["s"]] = new TuiyoImage($args["source"], $args["width"], $args["height"],
                $args["quality"]);
        }
        return $instance[$params["s"]];
    }
}
