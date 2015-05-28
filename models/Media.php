<?php

namespace mata\media\models;

use Yii;
use mata\db\ActiveRecord;
<<<<<<< Updated upstream
use yii\db\ActiveQuery;
use mata\arhistory\behaviors\HistoryBehavior;
=======
use mata\db\ActiveQuery;
use mata\arhistory\behaviors\HistoryBehavior;
use matacms\environment\behaviors\EnvironmentBehavior;
>>>>>>> Stashed changes

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
class Media extends \matacms\db\ActiveRecord {

    public function behaviors() {
        return [
<<<<<<< Updated upstream
            HistoryBehavior::className()
=======
            HistoryBehavior::className(),
            EnvironmentBehavior::className()
>>>>>>> Stashed changes
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%media2}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
<<<<<<< Updated upstream
        [['Name', 'MimeType', 'DocumentId'], 'required'],
        [['URI'], 'string'],
=======
        [['Name', 'MimeType', 'For'], 'required'],
        [['URI', 'Extra'], 'string'],
>>>>>>> Stashed changes
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

    public function forItem($item, $attribute = null) {

        if (is_object($item))
            $item = $item->getDocumentId();

        if ($attribute != null)
            $item .= "::" . $attribute;

        $this->andWhere(['For' => $item]);
        return $this;
    }

<<<<<<< Updated upstream
=======
    // public function one($db = null) {
    //     return $this->cachedOne($db);
    // }
>>>>>>> Stashed changes
}
