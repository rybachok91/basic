<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\base\Exception;


/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            ['username', 'trim'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить меня',
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            try {
                $user = User::findByUsername($this->username);
                if (!empty($user)) {
                    $this->username = $user->username;
                }
            } catch (Exception $e) {
                $this->addError($attribute, 'Попробуйте позже: ' . $e->getMessage());
            }
            if (empty($user)) {
                $this->addError($attribute, 'Некорретный пароль');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if (!$this->hasErrors()) {

            $user = $this->user;
            if (!empty($user)) {
                if (Yii::$app->getSecurity()->validatePassword($this->password, $user->password_hush)) {
                    if ($this->validate()) {
                        return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
                    }
                }
            }
        }
        return  \Yii::$app->session->addFlash('danger', 'Неверный логин или пароль!');
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }
        return $this->_user;
    }
}
