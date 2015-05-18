<?php

/*
 * This file is part of the mata project.
 *
 * (c) mata project <http://github.com/mata/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use yii\db\Schema;
use mata\user\migrations\Migration;

/**
 * @author Dmitry Erofeev <dmeroff@gmail.com
 */
class m150514_110812_addIndexes extends Migration
{
    public function up() {
        $this->createIndex("UQ_DocumentId", "{{%mata_media}}", "DocumentId", true);
    }

    public function down() {
    }
}