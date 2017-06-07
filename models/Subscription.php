<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "subscription".
 *
 * @property integer $subscription_id
 * @property integer $eq_customer_id 
 * @property integer $dashboard_id
 * @property string $created_at
 * @property string $status
 */
class Subscription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'subscription';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['eq_customer_id', 'dashboard_id', 'created_at'], 'required'],
            [['subscription_id', 'eq_customer_id', 'dashboard_id'], 'integer'],
            [['created_at'], 'safe'],
            [['status'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'subscription_id' => 'Subscription ID',
            'eq_customer_id' => 'Eq Customer ID',
            'dashboard_id' => 'Dashboard ID',
            'created_at' => 'Created At',
            'status' => 'Status',
        ];
    }
}
