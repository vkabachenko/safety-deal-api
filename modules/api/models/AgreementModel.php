<?php
declare(strict_types=1);

namespace app\modules\api\models;

class AgreementModel extends BaseModel
{
    public const INDIVIDUAL = 1; //Физлицо
    public const LEGAL_ENTITY = 2; //Юрлицо

    protected $controlParams = [
        'amount',
        'currency_id',
        'date_end',
        'partner_contract_id',
        'buyer_type',
        'buyer_email',
        'buyer_first_name',
        'buyer_last_name',
        'buyer_inn',
        'seller_type',
        'seller_email',
        'seller_first_name',
        'seller_last_name',
    ];

    // Сделка
    public  $name;
    public  $description;
    public  $amount;
    public  $currency_id = self::DEFAULT_CURRENCY_ID;
    public  $date_end;
    public  $partner_contract_id;
    public  $partner_product_link;

    //Покупатель
    public  $buyer_type;
    public  $buyer_email;
    public  $buyer_first_name;
    public  $buyer_last_name;
    public  $buyer_country_name;
    public  $buyer_city_name;
    public  $buyer_time_zone;
    public  $buyer_phone;
    public  $partner_buyer_link;

    //Компания покупателя
    public  $buyer_ul_country_name;
    public  $buyer_ul_city_name;
    public  $buyer_company_name;
    public  $buyer_inn;
    public  $buyer_kpp;
    public  $buyer_ogrn;

    //Продавец
    public  $seller_type;
    public  $seller_email;
    public  $seller_first_name;
    public  $seller_last_name;
    public  $seller_country_name;
    public  $seller_city_name;
    public  $seller_time_zone;
    public  $seller_phone;
    public  $partner_seller_link;

    //Компания продавца
    public  $seller_ul_country_name;
    public  $seller_ul_city_name;
    public  $seller_company_name;
    public  $seller_inn;
    public  $seller_kpp;
    public  $seller_ogrn;

    //Платежные реквизиты продавца - физлицо - Расчетный счет
    public  $bank_receiver_name;
    public  $bank_inn;
    public  $bank_bic;
    public  $bank_account;

    //Платежные реквизиты продавца - физлицо - Карта
    public  $card_receiver_name;
    public  $card_inn;
    public  $card_bic;
    public  $card_account;
    public  $card_number;

    //Платежные реквизиты продавца - физлицо - Яндекс
    public  $yandex_account;

    //Платежные реквизиты продавца - юрлицо - Расчетный счет
    public  $bank_ul_receiver_name;
    public  $bank_ul_inn;
    public  $bank_ul_kpp;
    public  $bank_ul_bic;
    public  $bank_ul_account;

    /**
     * @return array
     * @todo currency_id - relation with Currency model
     * @todo partner_contract_id - relation with  model
     */
    public function rules(): array
    {
        $rules = [
            [
                [
                    'name', 'amount', 'date_end', 'buyer_type', 'buyer_email', 'buyer_first_name', 'buyer_last_name',
                    'seller_type', 'seller_email', 'seller_first_name', 'seller_last_name'
                ],
                'required'
            ],
            [
                [
                    'name', 'buyer_first_name', 'buyer_last_name', 'buyer_country_name', 'buyer_city_name',
                    'buyer_ul_country_name', 'buyer_ul_city_name', 'seller_first_name', 'seller_last_name',
                    'seller_country_name', 'seller_city_name', 'seller_ul_country_name', 'seller_ul_city_name',
                ],
                'string', 'max' => 150
            ],
            ['amount', 'integer', 'min' => 0],
            ['description', 'string', 'max' => 350],
            ['currency_id', 'integer'],
            //[['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::class, 'targetAttribute' => ['currency_id' => 'id']],
            ['date_end', 'date', 'format' => 'php:Y-m-d'],
            ['partner_contract_id', 'string', 'max' => 100],
            //[['partner_contract_id'], 'exist', 'skipOnError' => true, 'targetClass' => PartnerContact::class, 'targetAttribute' => ['partner_contract_id' => 'id']],
            [['partner_product_link', 'partner_buyer_link', 'partner_seller_link'], 'string', 'max' => 255],
            [['partner_product_link', 'partner_buyer_link', 'partner_seller_link'], 'url'],
            [['buyer_type', 'seller_type'], 'in', 'range' => [self::INDIVIDUAL, self::LEGAL_ENTITY]],
            [['buyer_email', 'seller_email'], 'email'],
            [['buyer_time_zone', 'seller_time_zone'], 'integer'],
            [['buyer_phone', 'seller_phone'], 'match', 'pattern' => '/^\+\d{11}$/'],
            [['buyer_company_name', 'seller_company_name'], 'string', 'max' => 2000],
            ['buyer_inn', 'required', 'when' => function(AgreementModel  $model) {return $model->buyer_type == self::LEGAL_ENTITY;}],
            [['buyer_inn', 'buyer_kpp', 'buyer_ogrn', 'seller_inn', 'seller_kpp', 'seller_ogrn'], 'string', 'max' => 15],
            ['seller_inn', 'required', 'when' => function(AgreementModel  $model) {return $model->seller_type == self::LEGAL_ENTITY;}],
            [
                [
                'bank_receiver_name', 'card_receiver_name', 'bank_ul_receiver_name'
                ],
                'string', 'max' => 100
            ],
            [
                ['bank_inn', 'bank_bic', 'bank_account', 'card_inn', 'card_bic', 'card_account', 'card_number',
                'yandex_account', 'bank_ul_inn', 'bank_ul_kpp', 'bank_ul_bic', 'bank_ul_account'
                ],
                'string', 'max' => 20
            ],
            [['bank_receiver_name', 'bank_inn', 'bank_bic', 'bank_account'], 'validateSellerBankForIndividual'],
            [['card_receiver_name', 'card_inn', 'card_bic', 'card_account', 'card_number'], 'validateSellerCardForIndividual'],
            [['bank_ul_receiver_name', 'bank_ul_inn', 'bank_ul_kpp', 'bank_ul_bic', 'bank_ul_account'], 'validateSellerBankForLegalEntity'],
        ];
        return array_merge($rules, parent::rules());
    }


    public function validateSellerBankForIndividual($attribute, $params)
    {
        if ($this->seller_type == self::LEGAL_ENTITY && !empty($this->$attribute)) {
            $this->addError($attribute, sprintf('%s can\'t be filled for a legal entity', $attribute));
        }

        if (
            $this->seller_type == self::INDIVIDUAL
            && !empty($this->$attribute)
            && (
                empty($this->bank_receiver_name)
                || empty($this->bank_inn)
                || empty($this->bank_bic)
                || empty($this->bank_account)
            )) {
            $this->addError($attribute, sprintf('The bank details of the seller must all be filled in', $attribute));
            }
    }

    public function validateSellerCardForIndividual($attribute, $params)
    {
        if ($this->seller_type == self::LEGAL_ENTITY && !empty($this->$attribute)) {
            $this->addError($attribute, sprintf('%s can\'t be filled for a legal entity', $attribute));
        }

        if (
            $this->seller_type == self::INDIVIDUAL
            && !empty($this->$attribute)
            && (
                empty($this->card_receiver_name)
                || empty($this->card_inn)
                || empty($this->card_bic)
                || empty($this->card_account)
                || empty($this->card_number)
            )) {
            $this->addError($attribute, sprintf('The card details of the seller must all be filled in', $attribute));
        }
    }

    public function validateSellerBankForLegalEntity($attribute, $params)
    {
        if ($this->seller_type == self::INDIVIDUAL && !empty($this->$attribute)) {
            $this->addError($attribute, sprintf('%s can\'t be filled for an individual', $attribute));
        }

        if (
            $this->seller_type == self::LEGAL_ENTITY
            && !empty($this->$attribute)
            && (
                empty($this->bank_ul_receiver_name)
                || empty($this->bank_ul_inn)
                || empty($this->bank_ul_kpp)
                || empty($this->bank_ul_bic)
                || empty($this->bank_ul_account)
            )) {
            $this->addError($attribute, sprintf('The bank details of the seller must all be filled in', $attribute));
        }
    }


    /**
     * @todo Should be replaced with real calculations
     */
    protected function calculate(): array
    {
        return [
            'status' => static::STATUS_SUCCESS,
            'type' => 'link',
            'message' => 'Link to confirmation page',
            'result' => 'https://my.moneymoo.com/api_agreement/4e7450c74359b07010ba9d6790c410ae'
        ];
    }

}