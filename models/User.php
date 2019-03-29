<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\IdentityInterface;

/**

 * @property string $username
 * @property string $password
 * @property boolean $rememberMe
 * @property string $authKey
 */
class User extends Model implements IdentityInterface
{
    public $username;
    public $password;
    public $rememberMe = true;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
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

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return true;
    }

    /**
     * @param User $user
     */
    public static function saveUserToSession(User $user)
    {
        $session = [];
        $session['username'] = $user->username;
        $session['password'] = $user->password;
        Yii::$app->session->set('RYBACHOK', $session);
    }

    /**
     * Загружаем пользователя из сессии, если он там есть
     * @return User|null
     */
    public static function loadUserFromSession()
    {
        $session = Yii::$app->session->get('RYBACHOK');
        if (is_array($session) && !empty($session)) {
            $user = new User();
            $user->username = $session['username'];
            $user->password = $session['password'];
            return $user;
        }
        return null;
    }

    public function validatePassword($attribute, $params)
    {
        return true;
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        return true;
    }

}
