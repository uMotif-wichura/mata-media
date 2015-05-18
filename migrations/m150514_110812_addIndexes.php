<?php
 
/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

use yii\db\Schema;
use mata\user\migrations\Migration;

/**
 * @author Dmitry Erofeev <dmeroff@gmail.com
 */
class m150514_110812_addIndexes extends Migration
{
    public function safeUp() {
        $this->createIndex("UQ_DocumentId", "{{%mata_media}}", "DocumentId", true);
    }
}
