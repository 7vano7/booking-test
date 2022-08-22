<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class PaysystemMethods
 * @property integer $paysustem_id
 * @property integer $pay_method_id
 * @property string $country_list
 * @property integer $not_in_list
 * @property integer $android
 * @property integer $ios
 * @property string $image_url
 * @property string $pay_url
 * @property float $commission
 * @property integer $status
 * @property integer $position
 */
class PaysystemMethods extends ActiveRecord
{
    const POSITION_FIRST = 1;
    const POSITION_SECOND = 2;
}
