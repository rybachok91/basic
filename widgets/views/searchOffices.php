<?php

/**
 * @var $this \yii\web\View
 * @var $model \app\models\SearchOfficesForm
 * @var $contact \app\models\Contacts
 */


use kartik\select2\Select2;
use timurmelnikov\widgets\LoadingOverlayPjax;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

$script = <<<JS
function chooseOffice(office_id, item) {

    $(item).text("Выбрано").removeClass('btn-primary').addClass('btn-warning');
    $("input.chosen-office").val(office_id);
    $("button.choose-office").each(function (index, item) {
        if($(item).val() === $("input.chosen-office").val()) {
            $(item).text("Выбрано").removeClass('btn-primary').addClass('btn-warning');
        } else {
            $(item).text("Выбрать").removeClass('btn-warning').addClass('btn-primary');
        }
    });
}
JS;


$this->registerJs($script);

Pjax::begin(['id' => 'offices-search-' . rand(0, 10000), 'enablePushState' => false]);

$form = ActiveForm::begin([
    'options' => ['data-pjax' => true],
    'action' => ['offices-search']
]);
?>

<?= $form->field($model, 'contact')->hiddenInput()->label(false) ?>
<?= $form->field($model, 'office')->hiddenInput(['class' => 'chosen-office'])->label(false) ?>
<?= $form->field($model, 'city')->widget(Select2::class,
    [
        'data' => [
                'Смоленск' => 'Смоленск',
                'Москва' => 'Москва',
                'Брянск' => 'Брянск',
                'Воронеж' => 'Воронеж',
                'Екатеринбург' => 'Екатеринбург'
        ],
        'options' => [
                'id' => 'offices-search-' . rand(0, 10000)
        ]
    ]
) ?>

<?php echo Html::submitButton('Найти офисы', ['class' => 'btn btn-success']); ?>

    <br><br>
    <table class="table table-bordered">
        <thead>
        <tr>
            <td><b>Тип офиса</b></td>
            <td><b>Название офиса</b></td>
            <td><b>Телефон</b></td>
            <td><b>Адрес</b></td>
            <td><b>Населенный пункт</b></td>
            <td></td>
        </tr>
        </thead>
        <tbody>
        <?php
        $offices = $model->offices;
        foreach ($model->offices as $office): ?>
            <tr>
                <td><?= $office['OFFICE_TYPE'] ?></td>
                <td><?= $office['ORGANIZATION_NM'] ?></td>
                <td><?= $office['PHONE_NO'] ?></td>
                <td><?= $office['ADDRESS_LINE_TXT'] ?></td>
                <td><?= $office['SETTLEMENT_NM'] ?></td>
                <td>
                    <button class="choose-office btn btn-primary"
                            value="<?= $office['OFFICE_ID'] ?>"
                            onclick="chooseOffice('<?= $office['OFFICE_ID'] ?>', this)">
                            Выбрать
                    </button>

                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php

ActiveForm::end();

Pjax::end();
