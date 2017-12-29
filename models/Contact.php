<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "CONTACT".
 *
 * @property string $NAME
 * @property string $EMAIL
 * @property string $SUBJECT
 * @property string $BODY
 * @property string $CREATED
 */
class Contact extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CONTACT';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['NAME', 'EMAIL', 'SUBJECT', 'BODY', 'CREATED'], 'required'],
            [['NAME', 'EMAIL', 'SUBJECT'], 'string', 'max' => 20],
            [['BODY'], 'string', 'max' => 300],
            [['CREATED'], 'date'],
            ['EMAIL','email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'NAME' => 'Name',
            'EMAIL' => 'Email',
            'SUBJECT' => 'Subject',
            'BODY' => 'Body',
            'CREATED' => 'Created',
        ];
    }
}
