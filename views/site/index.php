<?php

/* @var $this yii\web\View */

use app\models\Contacts;
use app\widgets\OfficesSearch;

$this->title = 'Главная';

$C = new Contacts();

?>

<div class="root-page">
    <div class="container">
        <img src="nc-logo.gif" alt="logo" class="nc-logo">
        <img src="otp-bank.png" alt="otp" class="otp-logo">
        <div class="jumbotron text-center">
            <button type="button" class="changeStatusModal">
                Завершить работу
            </button>
        </div>
    </div>
</div>
