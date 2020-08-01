<?php


namespace app\modules\api\models;


class ListContractsModel extends BaseModel
{
    public const PAGE_SIZE = 10;

    public $buyer_email;
    public $seller_email;
    public $currency_id = self::DEFAULT_CURRENCY_ID;
    public $date_end_from;
    public $date_end_to;
    public $offset = 0;
    public $limit = self::PAGE_SIZE;

    protected $controlParams = [
        'buyer_email',
        'seller_email',
        'currency_id',
        'date_end_from',
        'date_end_to'
    ];

    /**
     * @return array
     * @todo currency_id - relation with Currency model
     */
    public function rules(): array
    {
        $rules = [
            [['buyer_email', 'seller_email'], 'email'],
            [['buyer_email', 'seller_email'], 'validateRequiredEmail'],
            ['currency_id', 'integer'],
            //[['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::class, 'targetAttribute' => ['currency_id' => 'id']],
            [['date_end_from', 'date_end_to'], 'date', 'format' => 'php:Y-m-d'],
            [['offset', 'limit'], 'integer', 'min' => 0]
        ];
        return array_merge($rules, parent::rules());
    }

    public function validateRequiredEmail($attribute, $params)
    {
        if (empty($this->buyer_email) && empty($this->seller_email)) {
            $this->addError($attribute, 'buyer_email or seller_email is required');
        }
        if (!empty($this->buyer_email) && !empty($this->seller_email)) {
            $this->addError($attribute, 'both buyer_email and seller_email can\'t be filled');
        }
    }

    /**
     * @todo Should be replaced with real calculations
     */
    protected function calculate(): array
    {
        return [
            'status' => static::STATUS_SUCCESS,
            'type' => 'array',
            'message' => 'Array of deals',
            'result' => [
                [
                    'partner_contract_id' => '124'
                ],
                [
                    'partner_contract_id' => '123'
                ],
            ]
        ];
    }

}