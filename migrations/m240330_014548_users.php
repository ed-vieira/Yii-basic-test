<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m240330_014548_users
 */
class m240330_014548_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('users', [
            'id' => $this->bigPrimaryKey(),
            'name' => $this->string(60)->notNull(),
            'username' => $this->string(20)->notNull()->unique(),
            'auth_key' => $this->string(100)->notNull()->unique(),
            'access_token' => $this->string()->null()->unique(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string(120)->null()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('NOW()'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('NOW()'),
        ], 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        try {
            $this->dropTable('users');
            return true;
        } catch (Exception $e) {
            echo "m240330_014548_users cannot be reverted.\n";
            echo $e->getMessage();
            return false;
        }
    }

}
