<?php
 
/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

use yii\db\Schema;
use yii\db\Migration;

class m150310_143912_init extends Migration {

	public function safeUp() {
		$this->createTable('{{%mata_media}}', [
			'Id' => Schema::TYPE_PK,
			'DocumentId' => Schema::TYPE_STRING . '(128) NOT NULL',
			'Name' => Schema::TYPE_TEXT . " NOT NULL",
			'URI' => Schema::TYPE_TEXT . " NOT NULL",
			'Width' => Schema::TYPE_STRING . "(255) NOT NULL",
			'Height' => Schema::TYPE_STRING . "(255) NOT NULL",
			'MimeType' => Schema::TYPE_TEXT . " NOT NULL",
			'Extra' => Schema::TYPE_TEXT
			]);
	}

	public function safeDown() {
		$this->dropTable('{{%mata_media}}');
	}
}
