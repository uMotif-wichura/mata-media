<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace mata\media\validators;

use yii\web\AssetBundle;

/**
 * This asset bundle provides the javascript files for client validation.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
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
