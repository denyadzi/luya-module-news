<?php

use yii\db\Migration;

/**
 * Class m190204_081749_add_parent_cat_field
 */
class m190204_081749_add_parent_cat_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('news_cat', 'parent_cat_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('news_cat', 'parent_cat_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190204_081749_add_parent_cat_field cannot be reverted.\n";

        return false;
    }
    */
}
