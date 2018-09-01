<?php

use yii\db\Migration;

/**
 * Class m180901_055109_create_table_bank
 */
class m180901_055109_create_table_bank extends Migration
{
    public function up()
    {
        $this->createTable('{{%bank}}', [
            'id' => $this->primaryKey(),
            'account_number' => $this->string()->notNull()->unique(),
            'balance'=>$this->double()->defaultValue(0),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%bank}}');
    }
}
