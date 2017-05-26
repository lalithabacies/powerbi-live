<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "eq_customers".
 *
 * @property integer $eqc_id
 * @property integer $eq_customer_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $access_token
 * @property string $status
 */
class Customer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'eq_customers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['eq_customer_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['access_token', 'status'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'eqc_id' => 'Eqc ID',
            'eq_customer_id' => 'Eq Customer ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'access_token' => 'Access Token',
            'status' => 'Status',
        ];
    }
}
