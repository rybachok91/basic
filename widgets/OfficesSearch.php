<?php

namespace app\widgets;

use app\models\Contacts;
use app\models\SearchOfficesForm;
use yii\base\Widget;

/**
 * Class Meeting
 * @package app\widgets
 */
class OfficesSearch extends Widget
{
    /**
     * @var Contacts
     */
    public $contact = 1;

    /**
     * @return string
     */
    public function run()
    {
        $model = new SearchOfficesForm(['contact' => $this->contact]);
        return $this->render('searchOffices', ['model' => $model]);
    }
}