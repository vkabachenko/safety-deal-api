<?php


namespace app\modules\api\models;


class ContractModel extends BaseModel
{
    public $partner_contract_id;
    public $user_email;

    protected $controlParams = [
        'partner_contract_id',
        'user_email',
    ];

    /**
     * @return array
     * @todo partner_contract_id - relation with PartnerContract model
     */
    public function rules(): array
    {
        $rules = [
            [['partner_contract_id', 'user_email'], 'required'],
            [['user_email'], 'email'],
            ['partner_contract_id', 'string', 'max' => 100],
            //[['partner_contract_id'], 'exist', 'skipOnError' => true, 'targetClass' => PartnerContact::class, 'targetAttribute' => ['partner_contract_id' => 'id']],
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
            'message' => 'Contract info',
            'result' => [
                'partner_contract_id' => '124'
            ]
        ];
    }

}