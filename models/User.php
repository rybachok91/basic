<?php

namespace app\models;

use Yii;
use yii\base\Exception;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hush
 * @property string $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_USER = 0;
    const STATUS_ADMIN = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'USERS';
    }

    public function behaviors()
    {
        return [
            'TimestampBehavior' => [
                'class' => TimestampBehavior::className(),
                'value' => date('d-M-y h:i:s'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['username', 'password_hush', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['id', 'username'], 'unique'],
            [['created_at', 'updated_at'], 'safe'],
            ['status', 'default', 'value' => self::STATUS_ADMIN],
            ['status', 'in', 'range' => [self::STATUS_ADMIN, self::STATUS_USER]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Логин',
            'auth_key' => 'Ключ аутентификации',
            'password_hush' => 'Хеш пароля',
            'password_reset_token' => 'Токен сброса пароля',
            'email' => 'Email',
            'status' => 'Статус',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param $username
     * @param $password
     * @return null|static
     */

    public static function findByUsername($username)
    {
        return self::findOne(['username' => $username]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
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
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     * @throws \yii\base\Exception
     */
    public function setPassword($password)
    {
        if (!$this->password_hush = Yii::$app->security->generatePasswordHash($password)) {
            throw new Exception('Ошибка! Хеш пароля не сгенерирован.');
        };
    }

    /**
     * Generates an auth key
     *
     * @throws \yii\base\Exception
     */
    public function generateAuthKey()
    {
        if (!$this->auth_key = Yii::$app->security->generateRandomString()) {
            throw new Exception('Ошибка! Ключ авторизации не сгенерирован.');
        };
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            self::saveUserToSession($this);
            return Yii::$app->user->login($this, $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        return false;
    }

    public static function saveUserToSession(User $user)
    {
        $session = [];
        $session['username'] = $user->username;
        $session['password'] = $user->password;
        $session['name'] = $user->name;
        $session['permissions'] = $user->permissions;
        Yii::$app->session->set('RYBACHOK', $session);
    }
}
