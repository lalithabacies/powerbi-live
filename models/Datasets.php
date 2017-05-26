<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "datasets".
 *
 * @property integer $s_id
 * @property string $dataset_name
 * @property string $dataset_id
 * @property integer $workspace_id
 * @property string $datasource_id
 * @property string $gateway_id
 *
 * @property Workspaces $workspace
 */
class Datasets extends \yii\db\ActiveRecord
{
	public $file;
	public $dataset_name;
	public $dataset_id;
	public $datasource_id;
	public $gateway_id;
	public $dashboard_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dataset_name', 'dataset_id', 'datasource_id', 'gateway_id'], 'string'],
			[['dashboard_id'], 'integer'],
			[['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'pbix'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            's_id' 			=> 'S ID',
            'dataset_name' 	=> 'Dataset Name',
            'dataset_id' 	=> 'Dataset ID',
            'datasource_id' => 'Datasource ID',
            'gateway_id' 	=> 'Gateway ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkspace()
    {
        return $this->hasOne(Workspace::className(), ['w_id' => 'workspace_id']);
    }
}
