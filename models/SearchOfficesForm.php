<?php

namespace app\models;

use yii\base\Model;


/**
 * Class SearchOfficesForm
 * @package app\models
 */
class SearchOfficesForm extends Model
{
    /**
     * @var string
     */
    public $contact;

    /**
     * @var string
     */
    public $city;

    /**
     * @var array
     */
    public $offices = [];


    public $office;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['city', 'contact'], 'string', 'max' => 100],
            [['offices'], 'safe'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'city' => 'Город',
        ];
    }

    /**
     * Поиск города
     * @return array
     */
    public function searchOffices()
    {
        return [
            0 => [
                'ID_CONTACT_CENTER' => '1',
                'OFFICE_ID' => '1-HOW-34',
                'OFFICE_TYPE' => 'ККО',
                'ORGANIZATION_NM' => 'Office_kko',
                'ADDRESS_LINE_TXT' => 'Street, house',
                'SETTLEMENT_NM' => 'Settlement_1',
                'PHONE_NO' => '88004560098',
            ],
            1 => [
                'ID_CONTACT_CENTER' => '2',
                'OFFICE_ID' => '1-HOW-28',
                'OFFICE_TYPE' => 'ККО',
                'ORGANIZATION_NM' => 'Office_kko',
                'ADDRESS_LINE_TXT' => 'Street, house',
                'SETTLEMENT_NM' => 'Settlement_1',
                'PHONE_NO' => '88004560098',
            ],
            3 => [
                'ID_CONTACT_CENTER' => '3',
                'OFFICE_ID' => '1-HOW-44',
                'OFFICE_TYPE' => 'ККО',
                'ORGANIZATION_NM' => 'Office_kko',
                'ADDRESS_LINE_TXT' => 'Street, house',
                'SETTLEMENT_NM' => 'Settlement_1',
                'PHONE_NO' => '88004560098',
            ]
        ];
    }
}