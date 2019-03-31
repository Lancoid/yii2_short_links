<?php

use yii\db\{Migration, Schema};

/**
 * Class m190331_175612_create_short_url_tables
 */
class m190331_175612_create_short_url_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        $pk = null;
        $foreignKey = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
            $pk = Schema::TYPE_STRING . '(36) PRIMARY KEY';
            $foreignKey = Schema::TYPE_STRING . '(36)';
        }

        if ($this->db->driverName === 'pgsql') {
            $pk = 'UUID PRIMARY KEY';
            $foreignKey = 'UUID';
        }

        $this->createTable(
            '{{%short_url}}',
            [
                'id' => $pk,
                'long_url' => $this->string()->notNull()->unique(),
                'short_code' => $this->string()->notNull()->unique(),
                'counter' => $this->integer()->notNull()->defaultValue(0),
                'created_at' => $this->integer()->notNull(),
                'updated_at' => $this->integer()->notNull(),
            ],
            $tableOptions
        );

        $this->createIndex('idx-short_url-id', '{{%short_url}}', 'id');
        $this->createIndex('idx-short_url-short_code', '{{%short_url}}', 'short_code');

        $this->createTable(
            '{{%short_url_log}}',
            [
                'id' => $pk,
                'short_url_id' => $foreignKey . ' NOT NULL',
                'user_platform' => $this->string()->null(),
                'user_agent' => $this->string()->null(),
                'user_refer' => $this->string()->null(),
                'user_ip' => $this->string()->notNull(),
                'user_country' => $this->string()->null(),
                'user_city' => $this->string()->null(),
                'created_at' => $this->integer()->notNull(),
            ],
            $tableOptions
        );

        $this->createIndex('idx-short_url_log-id', '{{%short_url_log}}', 'id');
        $this->createIndex('idx-short_url_log-short_url_id', '{{%short_url_log}}', 'short_url_id');
        $this->addForeignKey(
            'fk-short_url_log-short_url_id',
            '{{%short_url_log}}', 'short_url_id',
            '{{%short_url}}', 'id', 'CASCADE', 'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%short_url_log}}');
        $this->dropTable('{{%short_url}}');
    }
}
