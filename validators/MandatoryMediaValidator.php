<?php

namespace mata\media\validators;

use Yii;
use yii\validators\Validator;
use yii\web\JsExpression;
use yii\helpers\Json;
use yii\helpers\Inflector;
use mata\media\validators\MandatoryMediaValidationAsset;
use mata\helpers\StringHelper;
use mata\media\models\Media;

class MandatoryMediaValidator extends Validator
{

    public function init()
    {
        parent::init();
        if ($this->message === null)
            $this->message = Yii::t('yii', '{attribute} cannot be blank.');
        
    }

    public function validateAttribute($model, $attribute)
    {
        $media = \Yii::$app->request->post('Media');

        if(!empty($media)) {
            $hasAttributeInMedia = false;
            foreach($media as $mediaEntity) {
                if(StringHelper::endsWith($mediaEntity, '::' . $attribute))
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
            'id' => \yii\helpers\Html::getInputId($model, $attribute), 
            'message' => Yii::$app->getI18n()->format($this->message, [
                'attribute' => Inflector::camel2words($attribute),
            ], Yii::$app->language),
        ];

        MandatoryMediaValidationAsset::register($view);
        return 'matamedia.validation.mandatory($form, value, messages, ' . Json::encode($options) . ');';
    }

    // protected function identifyVideoServiceProvider($attribute) {
    //     $url = preg_replace('#\#.*$#', '', trim($attribute));
    //     $services_regexp = [
    //         $this->vimeoPattern       => 'vimeo',
    //         $this->youtubePattern     => 'youtube'
    //     ];

    //     foreach ($services_regexp as $pattern => $service) {
    //         if(preg_match($pattern, $attribute, $matches)) {
    //             return $service;
    //         }
    //     }

    //     return false;
    // }

    protected function prepareJsPattern($pattern) {
        $pattern = preg_replace('/\\\\x\{?([0-9a-fA-F]+)\}?/', '\u$1', $pattern);
        $deliminator = substr($pattern, 0, 1);
        $pos = strrpos($pattern, $deliminator, 1);
        $flag = substr($pattern, $pos + 1);
        if ($deliminator !== '/') {
            $pattern = '/' . str_replace('/', '\\/', substr($pattern, 1, $pos - 1)) . '/';
        } else {
            $pattern = substr($pattern, 0, $pos + 1);
        }
        if (!empty($flag)) {
            $pattern .= preg_replace('/[^igm]/', '', $flag);
        }
        return $pattern;
    }
}