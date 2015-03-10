<?php

namespace mata\media\models;

use Yii;
use mata\db\ActiveRecord;
use yii\db\ActiveQuery;

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
    public function rules() {
        return [
        [['Name', 'MimeType', 'DocumentId'], 'required'],
        [['URI'], 'string'],
        [['Width', 'Height'], 'integer'],
        [['Name'], 'string', 'max' => 255]
        ];
    }

    
     public static function find() {
       return new MediaQuery(get_called_class());
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


class MediaQuery extends ActiveQuery {

    public function forItem($item) {

        if (is_object($item))
            $item = $item->getDocumentId();

        $this->andWhere(['DocumentId' => $item]);
        return $this;
    }

}
