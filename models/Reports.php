<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "reports".
 *
 * @property integer $r_id
 * @property string $report_guid
 * @property string $report_name
 * @property string $web_url
 * @property string $embed_url
 * @property integer $workspace_id
 *
 * @property Workspaces $workspace
 */
class Reports extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reports';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['report_guid', 'report_name', 'web_url', 'embed_url'], 'string'],
            [['workspace_id','dataset_id'], 'integer'],
			[['report_guid'],'required'],
            [['workspace_id'], 'exist', 'skipOnError' => true, 'targetClass' => Workspace::className(), 'targetAttribute' => ['workspace_id' => 'w_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'r_id' => 'R ID',
            'report_guid' => 'Report Guid',
            'report_name' => 'Report Name',
            'web_url' => 'Web Url',
            'embed_url' => 'Embed Url',
            'workspace_id' => 'Workspace ID',
			'dataset_id' => 'Dataset ID',
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
