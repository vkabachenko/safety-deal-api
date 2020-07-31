<?php


namespace app\modules\api\models;


class ContractStatusModel extends ContractModel
{
    /**
     * @todo Should be replaced with real calculations
     */
    protected function calculate(): array
    {
        return [
            'status' => static::STATUS_SUCCESS,
            'type' => 'object',
            'message' => 'Contract status info',
            'result' => [
                'partner_contract_id' => '124',
		        'status' => 'Выполняется',
		        'status_id' => '3',
		        'next_status' => 'Завершена',
		        'next_status_id' => '6',
		        'next_status_url' => 'https =>//api.moneymoo.com/set_status',
                'next_status_params' => [
                    'contract_hash' => '4e7450c74359b07010ba9d6790c410ae',
			        'partner_contract_id' => '124',
			        'user_email' => 'test1@gmail.com',
			        'status' => '6'
		        ],
                'widget' => '<div class="class"></div>'
            ]
        ];
    }

}