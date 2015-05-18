<?php
 
/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

namespace mata\media\validators;

use yii\web\AssetBundle;

class MandatoryMediaValidationAsset extends AssetBundle
{
    public $sourcePath = '@vendor/mata/mata-media/assets';
    public $js = [
        'js/matamedia.validation.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
