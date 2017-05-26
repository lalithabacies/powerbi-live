<?php

use yii\db\Migration;

/**
 * Handles the creation of table `models`.
 */
class m170505_054238_create_models_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('models', [
            'm_id' => $this->primaryKey(),
            'model_name' => $this->string(15)->notnull()->unique(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('models');
    }
}
