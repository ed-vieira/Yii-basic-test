<?php

use yii\db\Migration;

/**
 * Class m240330_020162_customer_address
 */
class m240330_020162_customer_address extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $table = 'customer_address';
        $col = 'customer_id';
        $relatedTable = 'customers';
        $relatedCol = 'id';
        $this->createTable($table, [
            'id' => $this->bigPrimaryKey(),
            'customer_id' => $this->bigInteger()->notNull()->unique(),
            'post_code' => $this->string(10)->notNull(),
            'country' => $this->string(3)->notNull()->defaultValue('BRA'),
            'state' => $this->string(2)->notNull(),
            'city' => $this->string(60)->notNull(),
            'street' => $this->string(60)->notNull(),
            'number' => $this->string(12)->notNull(),
            'complement' => $this->string(200)->null(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('NOW()'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('NOW()'),
        ],  'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB');


        $this->createIndex('idx-'.$table.'-'.$col, $table, $col);

        $this->addForeignKey('fk-'.$table.'-'.$col,$table, $col, $relatedTable, $relatedCol,'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        try {
            $this->dropTable('customer_address');
            return true;
        } catch (Exception $e) {
            echo "m240330_025934_customer_address cannot be reverted.\n";
            echo $e->getMessage();
            return false;
        }
    }


}
