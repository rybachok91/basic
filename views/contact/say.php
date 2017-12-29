<?php

use yii\helpers\Html;

$this->title = 'Сохранить';
$this->params['breadcrumbs'][] = ['label' => 'Контакты', 'url' => ['contact/index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<p class="alert alert-success"><?= Html::encode($message) ?></p>
