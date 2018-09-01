<?php

use yii\db\Migration;

class m180901_062527_create_table_users extends Migration
{

    public function up()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'email' => $this->string()->notNull()->unique(),
            'password' => $this->string()->notNull(),
            'type' => $this->string()->notNull()->defaultValue('agent'),
            'score'=>$this->double()->defaultValue(0),
        ]);
        $this->insert('user', [
            'email' => 'goluschko.sergey@yandex.ru',
            'password' => '50e1fb0e5e390e496754b1f797623dd329d75324',
            'type' => 'admin',
            'score' => 0,
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }

}
