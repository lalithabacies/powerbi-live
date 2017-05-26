<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "collections".
 *
 * @property integer $collection_id
 * @property string $collection_name
 * @property string $AppKey
 *
 * @property Workspaces[] $workspaces
 */
class Collection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'collections';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['collection_name', 'AppKey'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'collection_id' 	=> 'Collection ID',
            'collection_name' 	=> 'Collection Name',
            'AppKey' 		=> 'App Key',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkspaces()
    {
        return $this->hasMany(Workspaces::className(), ['collection_id' => 'collection_id']);
    }
    
}
