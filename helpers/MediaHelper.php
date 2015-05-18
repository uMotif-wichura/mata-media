<?php
 
/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

namespace mata\media\helpers;

use matacms\widgets\videourl\helpers\VideoUrlHelper;
use yii\helpers\Json;

class MediaHelper
{
	public static function getType($mimeType)
	{
		return substr($mimeType,0, strrpos($mimeType,'/'));
	}

	public static function getPreview($media)
	{
		$type = self::getType($media->MimeType);
		switch($type) {
			case 'image':
				return '<img src="' . $media->URI . '" draggable="false">';
				break;
			case 'video':
				return '<img src="' . Json::decode($media->Extra, false)->thumbnailUrl . '" draggable="false">';
				break;
			default:
			break;
		}	
	}
}
