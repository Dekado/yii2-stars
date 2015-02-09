<?php

namespace rico\yii2stars;

use Yii;

/**
 * This is the model class for table "rico_rating".
 *
 * @property integer $id
 * @property integer $itemId
 * @property integer $userId
 * @property integer $value
 * @property string $itemClass
 * @property string $ip
 * @property string $created
 * @property string $changed
 */
class RicoRating extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rico_rating';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['itemId', 'userId', 'value'], 'integer'],
            [['created', 'changed'], 'safe'],
            [['itemClass', 'ip'], 'string', 'max' => 300]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'itemId' => 'Item ID',
            'userId' => 'User ID',
            'value' => 'Value',
            'itemClass' => 'Item Class',
            'ip' => 'Ip',
            'created' => 'Created',
            'changed' => 'Changed',
        ];
    }
}
