<?php

use yii\db\Migration;

/**
 * Handles adding attributes to table `models`.
 */
class m170505_061235_add_attributes_column_to_models_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('models', 'attributes', $this->text());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('models', 'attributes');
    }
}
