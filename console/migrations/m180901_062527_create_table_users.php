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
            'password' => 'd34f2ac91e613516d99ff76a396e04ea589e16bf',
            'type' => 'admin',
            'score' => 0,
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }

}
