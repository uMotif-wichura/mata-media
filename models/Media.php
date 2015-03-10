<?php

namespace mata\media\models;

use Yii;
use mata\db\ActiveRecord;

/**
 * This is the model class for table "{{%mata_media}}".
 *
 * @property integer $Id
 * @property string $DateCreated
 * @property string $Name
 * @property string $URI
 * @property integer $Width
 * @property integer $Height
 */
class Media extends \mata\db\ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mata_media}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        [['Name', 'MimeType'], 'required'],
        [['URI'], 'string'],
        [['Width', 'Height'], 'integer'],
        [['Name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
        'Id' => 'ID',
        'Name' => 'Name',
        'URI' => 'Uri',
        'Width' => 'Width',
        'Height' => 'Height',
        ];
    }
}