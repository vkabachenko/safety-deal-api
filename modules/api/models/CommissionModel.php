<?php
declare(strict_types=1);

namespace app\modules\api\models;

class CommissionModel extends BaseModel
{
    protected $controlParams = ['amount'];

    public  $amount;

    public function rules(): array
    {
        $rules = [
            ['amount', 'required'],
            ['amount', 'integer', 'min' => 0]
        ];
        return array_merge($rules, parent::rules());
    }

    /**
     * @todo Should be replaced with real calculations
     */
    protected function calculate(): array
    {
        return [
            'status' => static::STATUS_SUCCESS,
            'type' => 'string',
            'message' => 'Service commissian',
            'result' => '5500'
        ];
    }

}