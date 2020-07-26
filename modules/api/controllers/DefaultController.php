<?php

namespace app\modules\api\controllers;

use app\modules\api\models\BaseModel;
use app\modules\api\models\CommissionModel;
use yii\rest\Controller;


class DefaultController extends Controller
{

    protected function verbs()
    {
        [
            'commission' => ['GET'],
        ];
    }

    public function actionCommission(): array
    {
        return $this->act($this->request->queryParams, CommissionModel::class);
    }

    protected function act(array $params, string $modelClass): array
    {
        try {
            /* @var $model \app\modules\api\models\BaseModel */
            $model = new $modelClass($params);
            return $model->response();
        } catch (\Throwable $e) {
            return [
                'status' => BaseModel::STATUS_EXCEPTION_ERRORS,
                'message' => $e->getMessage()
            ];
        }
    }
}
