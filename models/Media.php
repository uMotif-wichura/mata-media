<?php

namespace mata\media\models;

use Yii;
use mata\db\ActiveRecord;
use yii\db\ActiveQuery;
use mata\arhistory\behaviors\HistoryBehavior;

/**
 * This is the model class for table "{{%mata_media}}".
 *
 * @property integer $Id
 * @property string $DateCreated
 * @property string $Name
 * @property string $URI
 * @property integer $Width
 * @property integer $Height
 * @property string $MimeType
 * @property string $Extra
 */
class Media extends \mata\db\ActiveRecord {

    public function behaviors() {
        return [
        ];
    }

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
        [['URI', 'Extra'], 'string'],
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
        'Extra' => 'Extra',
        ];
    }

}


class MediaQuery extends ActiveQuery {

    public function forItem($item, $attribute = null) {

        if (is_object($item))
            $item = $item->getDocumentId()->getId();



        if ($attribute != null)
            $item .= "::" . $attribute;

        $this->andWhere(['DocumentId' => $item]);
        return $this;
    }

}
