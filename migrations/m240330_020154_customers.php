<?php

use yii\db\Migration;

/**
 * Class m240330_020154_customers
 */
class m240330_020154_customers extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $table = 'customers';
        $this->createTable($table, [
            'id' => $this->bigPrimaryKey(),
            'cpf' => $this->string(14)->notNull()->unique(),
            'cpf_number' => $this->string(11)->notNull()->unique(),
            'name' => $this->string(60)->notNull(),
            'gender' => $this->string(1)->notNull(),
            'photo' => $this->string(120)->null(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('NOW()'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('NOW()'),
        ],  'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB');

        $this->createIndex('idx-'.$table.'-cpf_number', $table, 'cpf_number');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        try {
            $this->dropTable('customers');
            return true;
        } catch (Exception $e) {
            echo "m240330_020154_customers cannot be reverted.\n";
            echo $e->getMessage();
            return false;
        }
    }

}
