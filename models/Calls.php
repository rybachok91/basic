<?php

namespace app\models;

use PDO;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class Calls
 * @package app\models
 *
 * @property integer $FID_CONTACT
 * @property integer $FID_PHONE
 *
 * @property Contacts $contact
 * @property Phones $phone
 * @property string|null $recallTimeByCallAmount
 * @property CallsResults $result
 */
class Calls extends \newcontact\corein\models\Calls
{

    /**
     * @return array
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),
            [
                [['FID_CONTACT', 'FID_PHONE'], 'integer']
            ]
        );
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(),
            [
                'FID_CONTACT' => 'ID контакта',
                'FID_PHONE' => 'ID телефона',
            ]
        );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResult()
    {
        return $this->hasOne(CallsResults::class, ['ID_CALL_STATUS' => 'FID_CALL_STATUS']);
    }

    /**
     * @param $result
     * @return bool
     * @throws ErrorException
     */
    public function setResult($result = null)
    {
        $params = [
            'IP_ID_CALL' => $this->ID_CALL,
            'IP_ID_RESULT' => $result,
            'IP_CALL_BACK_TIME' => $_POST['DingDongForm']['datetime'] ?? null,
            'IP_SESSION_ID' => $this->SESSION_ID,
            'IP_COMMENTS_CALL' => $_POST['CommentForm']['text'] ?? null,
            'IP_NEW_PHONE' => $_POST['DingDongForm']['phone'] ?? null,
            'IP_NEW_VISIT_OFFICE' => $_POST['MeetingForm']['chosen-office'] ?? ($_POST['AgreementForm']['office'] ?? null),
            'IP_MEETING_DATE' => $_POST['MeetingForm']['date'] ?? null
        ];

        Yii::info(implode('|', $params), 'callParams');

        try {

            $saveCallResultSql = Yii::$app->db->createCommand("
            BEGIN PKG_SCRIPT_RESULT.SP_SAVE_CALL_RESULT
                                          (
                                            :IP_ID_CALL,
                                            :IP_ID_RESULT,
                                            :IP_CALL_BACK_TIME,
                                            :IP_SESSION_ID,
                                            :IP_COMMENTS_CALL,
                                            :IP_NEW_PHONE,
                                            :IP_NEW_VISIT_OFFICE,
                                            :IP_MEETING_DATE
                                          ); END;"
            );

            $saveCallResultSql->bindValues($params);

            if ($saveCallResultSql->execute()) {
                Yii::info('Сохранено');
                return true;
            }
        } catch (Exception $e) {
            return VarDumper::dumpAsString($e);
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContact()
    {
        return $this->hasOne(Contacts::class, ['ID_CONTACT' => 'FID_CONTACT']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhone()
    {
        return $this->hasOne(Phones::class, ['ID_PHONE' => 'FID_PHONE']);
    }

    /**
     * @param $text
     * @return bool
     */
    public function assignComment($text)
    {
        $this->COMMENTS = $text;

        if ($this->validate()) {
            return $this->save();
        }

        return false;
    }

    /**
     * @param $contactId
     * @param $phoneId
     * @return false|string
     * @throws NotFoundHttpException
     * @throws BadRequestHttpException
     */
    public static function createNewOne($contactId, $phoneId)
    {
        // доступные get переменные (они продублированы в верхнем регистре)
        // session_id=tel_opersgate_reports_domain_1_nauss_0_1530290746_39
        // caller=Unknown
        // called=989646153170
        // direction=in
        // seance_id=4
        // project_id=corebofs000080000m6amn4nr2ehg8tg
        // ext_id=leto_bank.9366420.15170780
        // ivr-param=
        // id_project=
        // id_inquiry=
        // prefix=
        // options=
        // OGRN=
        // SP=
        // GZHI=
        // agent_login=c.baguza

        $contact = Contacts::findOne($contactId);

        if (empty($contact)) {
            throw new NotFoundHttpException('Контакт не найден: ' . $contactId);
        }

        $phone = Phones::findOne($phoneId);

        if (empty($phone)) {
            throw new NotFoundHttpException('Номер телефона не найден: ' . $phoneId);
        }

        $callId = null;
        $operator = Yii::$app->user->identity->LOGIN ?? null;
        $session = Yii::$app->request->get('session_id');

        Yii::$app->db->createCommand(
            "DECLARE
VOUT NUMBER;
BEGIN
    :VOUT := PKG_SCRIPT_RESULT.SP_CREATE_CALL(" . $phoneId . "," . $contactId . ", '" . $operator . "','" . $session . "');
END;")
            ->bindParam(':VOUT', $callId, PDO::PARAM_INT)
            ->execute();

        return $callId;

    }

    /**
     * Получаем время перезвона в зависимости от кол-ва звонков на номер.
     * @return string|null
     */
    protected function getRecallTimeByCallAmount()
    {
        $callAmount = $this->phone->getCallAmount();
        $deltaTime = ((int)$this->contact->TIMEZONE - 3) * 3600;
        switch ($callAmount) {
            case 1:
                return date('d.m.Y H:i', strtotime("+120 minutes") + $deltaTime);
            case 2:
                return date('d.m.Y H:i', strtotime("+240 minutes") + $deltaTime);
            case 3:
                return date('d.m.Y 09:00', strtotime('+1 day') + $deltaTime);
            case 4:
                return date('d.m.Y H:i', strtotime("+240 minutes") + $deltaTime);
            case 5:
                return date('d.m.Y 09:00', strtotime('+1 day') + $deltaTime);
            case 6:
                return $this->contact->RECALL_TIME;
            case 7:
                //TODO 8-я попытка дозвона(нет времени в ТЗ)
                return date('d.m.Y H:i', strtotime("+120 minutes") + $deltaTime);
            case 8:
            default:
                return null;
        }
    }

}