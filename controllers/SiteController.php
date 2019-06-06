<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\VerbFilter;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Главная страница
     * Если пользователь - гость, переход на страницу входа
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionHandler() {
        $id = \Yii::$app->request->get('id');
        return 'OK! id = ' . $id ?? '-0';
    }

    /**
     * phpinfo
     */
    public function info()
    {
        return phpinfo();
    }
}
