<?php

/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

namespace mata\media\validators;

use Yii;
use yii\validators\Validator;
use yii\web\JsExpression;
use yii\helpers\Json;
use yii\helpers\Inflector;
use yii\helpers\Html;
use mata\media\validators\MandatoryMediaValidationAsset;
use mata\helpers\StringHelper;
use mata\media\models\Media;

class MandatoryMediaValidator extends Validator
{

    public function init()
    {
        parent::init();
        $this->skipOnEmpty = false;
        if ($this->message === null)
            $this->message = Yii::t('yii', '{attribute} cannot be blank.');

    }

    public function validateAttribute($model, $attribute)
    {
        $media = \Yii::$app->request->post('Media');

        if(!empty($media)) {
            $hasAttributeInMedia = false;
            foreach($media as $key => $mediaEntity) {

                $documentId = isset($mediaEntity["DocumentId"]) ? $mediaEntity["DocumentId"] : null;

                if ($documentId != null && StringHelper::endsWith($documentId, '::' . $attribute))
                    $hasAttributeInMedia = true;
            }

            if(!$hasAttributeInMedia)
                $model->addError($attribute, \Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => Inflector::camel2words($attribute)]));
        } else {
            $mediaModel = Media::find()->forItem($model, $attribute)->one();
            if(!$mediaModel)
                $model->addError($attribute, \Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => Inflector::camel2words($attribute)]));
        }
    }

    public function clientValidateAttribute($model, $attribute, $view) {

        $options = [
            'attribute' => $attribute,
            'name' => Inflector::camel2words($attribute),
            'id' => Html::getInputId($model, $attribute),
            'message' => Yii::$app->getI18n()->format($this->message, [
                'attribute' => $model->getAttributeLabel($attribute),
            ], Yii::$app->language),
        ];

        MandatoryMediaValidationAsset::register($view);
        return 'matamedia.validation.mandatory($form, value, messages, ' . Json::encode($options) . ');';
    }
}
