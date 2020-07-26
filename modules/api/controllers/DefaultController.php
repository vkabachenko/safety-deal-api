<?php
declare(strict_types=1);

namespace app\modules\api\controllers;

use app\models\User;
use app\modules\api\models\BaseModel;
use app\modules\api\models\CommissionModel;
use yii\filters\AccessControl;
use yii\rest\Controller;
use yii\filters\auth\HttpBasicAuth;

class DefaultController extends Controller
{
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::class,
            'auth' => [$this, 'auth']
        ];
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@']
                ],
            ],
        ];
        return $behaviors;
    }

    protected function verbs(): array
    {
        return [
            'commission' => ['GET'],
        ];
    }

    public function actionCommission(): array
    {
        return $this->act($this->request->queryParams, CommissionModel::class);
    }

    public function auth(string $login, string $password): ?User
    {
        $user = User::findByUsername($login);
        if ($user && $user->validatePassword($password)) {
            return $user;
        } else {
            return null;
        }
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
