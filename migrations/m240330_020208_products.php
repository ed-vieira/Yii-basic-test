<?php

use yii\db\Migration;

/**
 * Class m240330_020208_produtos
 */
class m240330_020208_products extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $table = 'products';
        $col = 'customer_id';
        $relatedTable = 'customers';
        $relatedCol = 'id';
        $this->createTable($table, [
            'id' => $this->bigPrimaryKey(),
            'name' => $this->string(60)->notNull(),
            'price' => $this->integer()->notNull(),
            'photo' => $this->string(200)->null(),
            'customer_id' => $this->bigInteger()->notNull(),
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
            $this->dropTable('products');
            return true;
        } catch (Exception $e) {
            echo "m240330_020208_products cannot be reverted.\n";
            echo $e->getMessage();
            return false;
        }
    }


}
