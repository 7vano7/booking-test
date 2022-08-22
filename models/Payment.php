<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class Payment
 * @property string $name
 * @property integer $status
 */
class Payment extends ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    public static function tableName()
    {
        return '{{%paysystem}}';
    }
}