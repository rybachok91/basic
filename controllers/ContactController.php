<?php

namespace app\controllers;

use Yii;
use app\models\Contact;
use yii\web\Controller;
use yii\web\Response;

class ContactController extends Controller
{
    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionIndex()
    {
        $model = new Contact();
        if ($model->load(Yii::$app->request->post())) {

            $model->CREATED = date('d-M-y');
            if ($model->save()) {
                return $this->render('say', ['message' => 'Сохранено!']);
            }
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

}
