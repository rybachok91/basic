<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use \app\rbac\UserGroupRule;

class RbacController extends Controller
{
    // инициализация выполняется в консоле ./yii rbac/init
    public function actionInit()
    {
        $authManager = \Yii::$app->authManager;

        // удалить файлы app/rbac/items.php и app/rbac/rules.php, чтобы избежать конфликтов слияния
        $authManager->removeAll();

        // создать роли
        $guest = $authManager->createRole('guest');
        $user = $authManager->createRole('user');
        $admin = $authManager->createRole('admin');

        //добавить роли в БД
        $authManager->add($guest);
        $authManager->add($user);
        $authManager->add($admin);

        //создать разрешения
        $login = $authManager->createPermission('login');
        $logout = $authManager->createPermission('logout');
        $error = $authManager->createPermission('error');
        $signUp = $authManager->createPermission('sign-up');
        $index = $authManager->createPermission('index');
        $view = $authManager->createPermission('view');
        $update = $authManager->createPermission('update');
        $delete = $authManager->createPermission('delete');

        //добавить разрешения в БД
        $authManager->add($login);
        $authManager->add($logout);
        $authManager->add($error);
        $authManager->add($signUp);
        $authManager->add($index);
        $authManager->add($view);
        $authManager->add($update);
        $authManager->add($delete);

        //добавить правило, которое базируется на UserExt->group === #user->group
        $userGroupRule = new UserGroupRule();
        $authManager->add($userGroupRule);

        //добавить правило "UserGroupRule" созданным ролям
        $guest->ruleName=$userGroupRule->name;
        $user->ruleName=$userGroupRule->name;
        $admin->ruleName=$userGroupRule->name;


        //добавить сответствующие разрешения для каждой из ролей
        // guest
        $authManager->addChild($guest, $login);
        $authManager->addChild($guest, $logout);
        $authManager->addChild($guest, $error);
        $authManager->addChild($guest, $signUp);
        $authManager->addChild($guest, $index);
        $authManager->addChild($guest, $view);

        // user
        $authManager->addChild($user, $update);
        $authManager->addChild($user, $guest);

        // admin
        $authManager->addChild($admin, $delete);
        $authManager->addChild($admin, $user);

        // Назначаем роль admin пользователю с ID 1
        $authManager->assign($admin, 1);

        // Назначаем роль editor пользователю с ID 2
        $authManager->assign($user, 2);
    }
}