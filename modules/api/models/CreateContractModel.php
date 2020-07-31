<?php
declare(strict_types=1);

namespace app\modules\api\models;


class CreateContractModel extends AgreementModel
{
    /**
     * @todo Should be replaced with real calculations
     */
    protected function calculate(): array
    {
        return [
            'status' => static::STATUS_SUCCESS,
            'type' => 'link',
            'message' => 'Link to confirmation page',
            'result' => 'ttps://my.moneymoo.com/api_get_payment/4e7450c74359b07010ba9d6790c410ae'
        ];
    }

}