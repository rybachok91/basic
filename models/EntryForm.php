<?php
/**
 * Created by PhpStorm.
 * User: tanya
 * Date: 12/27/17
 * Time: 9:23 AM
 */

namespace app\models;

use yii\base\Model;

class EntryForm extends Model
{
    public $name;
    public $email;

    public function rules() {
        return [
            [['name', 'email'], 'required'],
            ['email', 'email'],
        ];
    }
}