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
class m280514_191711_renameDocumentIdColumn extends Migration
{
    public function safeUp() {
    	$this->dropIndex("UQ_DocumentId", "{{%mata_media}}");
        $this->renameColumn("{{%mata_media}}", "DocumentId", "For");
        $this->createIndex("UQ_For", "{{%mata_media}}", "DocumentId", true);
    }
}
