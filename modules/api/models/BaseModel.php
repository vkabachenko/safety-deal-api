<?php
declare(strict_types=1);

namespace app\modules\api\models;

use yii\base\Model;
use yii\helpers\Json;

abstract class BaseModel extends Model
{
    public const STATUS_SUCCESS = 0;
    public const STATUS_VALIDATION_ERRORS = 1;
    public const STATUS_EXCEPTION_ERRORS = 2;

    protected $controlParams = [];

    public $control;

    public function rules(): array
    {
        return [
            ['control', 'required'],
            ['control', 'validateControl']
        ];
    }

    public function validateControl($attribute, $params)
    {
        $paramsString = array_reduce(
            $this->controlParams,
            function($carry, $item) {
                $carry .= strval($this->$item);
                return $carry;
            },
            ''
        );
        $paramsString .= $this->getApiKey();
        //$paramsString = md5($paramsString);

        if ($this->control !== $paramsString) {
            $this->addError('control', 'Control check fail');
        }
    }

    public function response(): array
    {
        if (!self::validate()) {
            return [
                'status' => self::STATUS_VALIDATION_ERRORS,
                'message' => Json::encode(self::getErrors())
            ];
        } else {
            $result = $this->calculate();
            return [
                'status' => self::STATUS_SUCCESS,
                'message' => $result['message'] ?? null,
                'type' => $result['type'] ?? null,
                'result' => $result['result'] ?? null
            ];
        }
    }

    protected abstract function calculate(): array;

    protected function getApiKey(): string
    {
        return \Yii::$app->user ? \Yii::$app->user->identity->api_key : '';
    }

}