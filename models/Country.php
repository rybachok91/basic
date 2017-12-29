<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "COUNTRY".
 *
 * @property string $CODE
 * @property string $NAME
 * @property int $POPULATION
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'COUNTRY';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CODE', 'NAME', 'POPULATION'], 'required'],
            [['POPULATION'], 'integer'],
            [['CODE'], 'string', 'max' => 2],
            [['NAME'], 'string', 'max' => 52],
            [['CODE'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CODE' => \Yii::t('app', 'Код'),
            'NAME' => \Yii::t('app', 'Название страны'),
            'POPULATION' => \Yii::t('app', 'Население'),
        ];
    }
}
