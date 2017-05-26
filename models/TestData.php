<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "garments".
 *
 * @property integer $garment_id
 * @property string $garment_type
 * @property string $image_url
 * @property integer $x_position
 * @property integer $y_position
 * @property string $vendor
 */
class TestData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'guest.test_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['data_id','x', 'y'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'x' => 'X',
            'y' => 'Y',
        ];
    }
}
