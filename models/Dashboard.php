<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dashboard".
 *
 * @property integer $dashboard_id
 * @property string $dashboard_name
 * @property string $pbix_file
 * @property string $description
 * @property string $models
 * @property string $report_id
 * @property string $form_data
 * @property integer $workspace_id
 * @property string $prefix
 */
class Dashboard extends \yii\db\ActiveRecord
{
    public $collection_id;
	public $file;
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dashboard';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dashboard_name', 'workspace_id','prefix'], 'required'],
            [['dashboard_name', 'pbix_file', 'description', 'models', 'report_id', 'form_data','dataset_id', 'datasource_id', 'gateway_id'], 'string'],
			['prefix','validatePlain'],
			['prefix','unique','except'=>'clone'],
            [['workspace_id'], 'integer'],
			['file', 'file'],
			['dashboard_name','string','on'=>'clone'],
			
        ];
    }
	
	public function validatePlain($attribute, $params)
	{
		if(preg_match('/[^A-Za-z0-9]/',$this->$attribute))
		{
			$this->addError($attribute,'Only alpahanumeric characters are allowed.');
		}
	}
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dashboard_id' => 'Dashboard ID',
            'dashboard_name' => 'Dashboard Name',
            'pbix_file' => 'Pbix File',
            'description' => 'Description',
            'models' => 'Models',
            'report_id' => 'Report ID',
            'form_data' => 'Form Data',
            'workspace_id' => 'Workspace ID',
            'prefix' => 'Prefix',
			'file' => 'Upload File',
        ];
    }
    public function upload()
    {
        if ($this->validate()) {
            $this->file->saveAs('uploads/' . $this->file->baseName . '.' . $this->file->extension);
			$this->template = 'uploads/' . $this->file->baseName . '.' . $this->file->extension;
            return true;
        } else {
            return false;
        }
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkspace()
    {
        return $this->hasOne(Workspace::className(), ['w_id' => 'workspace_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReport()
    {
        return $this->hasOne(Reports::className(), ['r_id' => 'report_id']);
    }
}
