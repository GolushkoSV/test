<?php

use yii\db\Migration;

/**
 * Class m180901_055316_create_table_payment
 */
class m180901_055316_create_table_payment extends Migration
{
    public function up()
    {
        $this->createTable('{{%payment}}', [
            'id' => $this->primaryKey(),
            'email' => $this->string()->notNull(),
            'score'=>$this->double()->defaultValue(0),
            'number_account'=>$this->string(),
            'date'=>$this->date(),
            'directing'=>$this->string(),
            'balance'=>$this->double(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%payment}}');
    }
}
