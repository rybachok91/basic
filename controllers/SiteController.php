<?php

namespace app\controllers;

use app\models\User;
use Yii;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\SearchOfficesForm;

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
//        if (Yii::$app->user->isGuest) {
//            return $this->actionLogin();
//        }
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $model = new User();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->render('index');
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout(true);

        return $this->redirect(['login']);
    }


    public function actionSay($message = 'Привет')
    {
        return $this->render('say', ['message' => $message]);
    }

    public function actionAddAdmin()
    {
        $model = User::find()->where(['username' => 'admin'])->one();
        if (empty($model)) {
            $user = new User();
            $user->username = 'admin';
//            $user->id = 1;
            $user->email = 'email@mail.ru';

            try {
                $user->setPassword('admin');
            } catch (Exception $exception) {
                return $this->actionSay('Выброшено исключение: ' . $exception->getMessage());
            }

            try {
                $user->generateAuthKey();
            } catch (Exception $exception) {
                return $this->actionSay('Выброшено исключение: ' . $exception->getMessage());
            }

            if ($user->save()) {
                return $this->actionSay('Пользователь добавлен!');
            } else {
                return $this->actionSay('Ошибка! Пользователь не добавлен.');
            }
        }
    }

    public function actionLoadFile() {

        $ftp = new \yii2mod\ftp\FtpClient();
        $host = 'ftp.127.0.0.1';
        $ftp->connect($host, false, 80);
        echo $ftp->help();

        $model = new User();

        if( isset($_FILES['csv_file']) ) {

            $handle = fopen($_FILES['csv_file']['tmp_name'], 'r');

            if ($handle) {
                while( ($line = fgetcsv($handle, 1000, ";")) != FALSE) {
                    $model->codigo          = $line[0];
                    $model->nome            = $line[1];
                    $model->descricao       = $line[2];
                    $model->stock           = $line[3];
                    $model->data_reposicao  = $line[4];

                    $model->save();
                }
            }
            fclose($handle);
        }
    }

    /**
     * @return string
     */
    public function actionOfficesSearch()
    {
        $model = new SearchOfficesForm();

        if ($model->load(Yii::$app->request->post()) and $model->validate()) {
            $model->offices = $model->searchOffices();
        }

        return $this->renderAjax('@app/widgets/views/searchOffices.php', ['model' => $model]);
    }

}
