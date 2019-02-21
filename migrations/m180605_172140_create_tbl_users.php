<?php

use yii\db\Migration;

/**
 * Class m180605_172140_create_tbl_users
 */
class m180605_172140_create_tbl_users extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('D_USER_STATUSES', [
            'ID_STATUS' => $this->primaryKey()->comment('_autoincremented'),
            'STATUS_NAME' => $this->string('50 CHAR')
        ]);

        $this->createTable('USERS', [
            'ID_USER' => $this->primaryKey()->comment('_autoincremented'),
            'AUTH_KEY' => $this->string('150 CHAR')->notNull()->comment('Ключ аутентификации'),
            'LOGIN' => $this->string('50 CHAR')->notNull()->comment('Логин'),
            'NAME' => $this->string('50 CHAR')->comment('Имя'),
            'SURNAME' => $this->string('50 CHAR')->comment('Фамилия'),
            'PATRONYMIC' => $this->string('50 CHAR')->comment('Отчество'),
            'BIRTH_DATE' => $this->date()->comment('Дата рождения'),
            'PASSWORD_HUSH' => $this->string('150 CHAR')->notNull()->comment('Хэш пароля'),
            'PASSWORD_RESET_TOKEN' => $this->string('150 CHAR')->unique()->comment('Токен для сброса пароля'),
            'EMAIL' => $this->string('50 CHAR')->notNull()->unique()->comment('Email'),
            'FID_STATUS' => $this->integer(2)->defaultValue(1)->comment('Статус активности'),
            'CREATED_AT' => $this->timestamp()->notNull()->comment('Дата создания'),
            'UPDATED_AT' => $this->timestamp()->notNull()->comment('Дата изменения'),
        ]);

        $this->addForeignKey(
            'FK_USER_STATUS',
            'USERS',
            'FID_STATUS',
            'D_USER_STATUSES',
            'ID_STATUS',
            'RESTRICT',
            'RESTRICT'
            );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('users');
    }
}
