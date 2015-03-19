<?php

/*
 * This file is part of the mata project.
 *
 * (c) mata project <http://github.com/qi-interactive/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use yii\db\Schema;
use yii\db\Migration;

/**
 * @author Dmitry Erofeev <dmeroff@gmail.com
 */
class m150310_143912_init extends Migration {

	public function up() {
		$this->createTable('{{%mata_media}}', [
			'Id' => Schema::TYPE_PK,
			'DocumentId' => Schema::TYPE_STRING . '(64) NOT NULL',
			'Name' => Schema::TYPE_TEXT . " NOT NULL",
			'URI' => Schema::TYPE_TEXT . " NOT NULL",
			'Width' => Schema::TYPE_STRING . "(255) NOT NULL",
			'Height' => Schema::TYPE_STRING . "(255) NOT NULL",
			'MimeType' => Schema::TYPE_TEXT . " NOT NULL",
			'Extra' => Schema::TYPE_TEXT
			]);
	}

	public function down() {
		$this->dropTable('{{%mata_media}}');
	}
}