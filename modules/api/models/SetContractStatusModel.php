<?php


namespace app\modules\api\models;


class SetContractStatusModel extends ContractModel
{
    public $contract_hash;
    public $status;

    protected $controlParams = [
        'contract_hash',
        'partner_contract_id',
        'user_email',
        'status'
    ];

    public function rules(): array
    {
        $rules = [
            [['contract_hash', 'status'], 'required'],
            ['contract_hash', 'string', 'max' => 16],
            ['status', 'integer']
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
            'type' => 'object',
            'message' => 'Status has been changed',
            'result' => [
                'partner_contract_id' => '124',
                'status' => 'Выполнена',
		        'status_id' => '6',
            ]
        ];
    }

}