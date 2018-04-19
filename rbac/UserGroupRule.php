<?php

namespace app\rbac;

use Yii;
use yii\rbac\Rule;

/**
 * Class UserGroupRule
 * @package app\rbac
 *
 * Класс отвечает за проверку равенства роли текущего пользователя и роли, прописанной в массиве разрешений
 * (убираем необходимость назначать роли по ID пользователя)
 *
 */
class UserGroupRule extends Rule
{
    public $name = 'userGroup';

    public function execute($user, $item, $params)
    {
        if (!\Yii::$app->user->isGuest) {
            $group = \Yii::$app->user->identity->group;
            if ($item->name === 'admin') {
                return $group == 'admin';
            } elseif ($item->name === 'user') {
                return $group == 'admin' || $group == 'user';
            }

        }
        return true;
    }
}