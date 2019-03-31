<?php

use yii\db\{Migration, Schema};
use app\models\User;

/**
 * Class m190331_174222_create_user_table
 */
class m190331_174222_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        $pk = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
            $pk = Schema::TYPE_STRING . '(36) PRIMARY KEY';
        }

        if ($this->db->driverName === 'pgsql') {
            $pk = 'UUID PRIMARY KEY';
        }

        $this->createTable(
            '{{%user}}',
            [
                'id' => $pk,
                'username' => $this->string()->notNull()->unique(),
                'auth_key' => $this->string(32)->notNull(),
                'password_hash' => $this->string()->notNull(),
                'password_reset_token' => $this->string()->unique(),
                'role' => $this->string(32)->notNull(),
                'full_name' => $this->string()->notNull(),
                'phone' => $this->string(32)->notNull(),
                'email' => $this->string()->notNull()->unique(),
                'status' => $this->string(1)->notNull()->defaultValue(User::STATUS_ACTIVE),
                'created_at' => $this->integer()->notNull(),
                'updated_at' => $this->integer()->notNull(),
            ],
            $tableOptions
        );

        $this->createIndex('idx-user-id', '{{%user}}', 'id');
        $this->createIndex('idx-user-password-hash', '{{%user}}', 'password_hash');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
