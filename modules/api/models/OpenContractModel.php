<?php


namespace app\modules\api\models;


class OpenContractModel extends ContractModel
{
    public $contract_hash;

    protected $controlParams = [
        'contract_hash',
        'partner_contract_id',
        'user_email',
    ];

    public function rules(): array
    {
        $rules = [
            [['contract_hash'], 'required'],
            ['contract_hash', 'string', 'max' => 16],
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
            'type' => 'link',
            'message' => 'Contract link',
            'result' => 'https://my.moneymoo.com/show_contract/4e7450c74359b07010ba9d6790c410ae?user_email=test1@gmail.com&control=cd79735f377b82fe9e540c4441bae7d4'
        ];
    }
}