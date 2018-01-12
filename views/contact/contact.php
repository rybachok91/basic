<?php

/* @var $this yii\web\View */

/* @var $form yii\bootstrap\ActiveForm */

use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Контакты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

        <div class="alert alert-success">
            Thank you for contacting us. We will respond to you as soon as possible.
        </div>

        <p>
            Note that if you turn on the Yii debugger, you should be able
            to view the mail message on the mail panel of the debugger.
            <?php if (Yii::$app->mailer->useFileTransport): ?>
                Because the application is in development mode, the email is not sent but saved as
                a file under <code><?= Yii::getAlias(Yii::$app->mailer->fileTransportPath) ?></code>.
                                                                                                    Please configure the
                <code>useFileTransport</code> property of the <code>mail</code>
                application component to be false to enable email sending.
            <?php endif; ?>
        </p>

    <?php else: ?>

        <p>
            If you have business inquiries or other questions, please fill out the following form to contact us.
            Thank you.
        </p>

        <div class="row">
            <div class="col-lg-5">

                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                <?= $form->field($model, 'NAME')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'EMAIL') ?>

                <?= $form->field($model, 'SUBJECT') ?>

                <?= $form->field($model, 'BODY')->textarea(['rows' => 6]) ?>

                <?= $form->field($model, 'CREATED')->widget(DatePicker::className(), [
                    'options' => ['placeholder' => 'Выберите дату ...'],
                    'language' => 'ru',
                    'pluginOptions' => [
                        'todayHighlight' => true,
                        'format' => 'dd-M-yyyy',
                        'autoclose' => true,
                    ]
                ]);

                ?>
                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>

    <?php endif; ?>
</div>
