<?php
declare(strict_types=1);

namespace app\modules\api\controllers;

use app\models\User;
use app\modules\api\models\AgreementModel;
use app\modules\api\models\BaseModel;
use app\modules\api\models\CommissionModel;
use app\modules\api\models\ContractModel;
use app\modules\api\models\ContractStatusModel;
use app\modules\api\models\CreateContractModel;
use app\modules\api\models\ListContractsModel;
use app\modules\api\models\OpenContractModel;
use app\modules\api\models\SetContractStatusModel;
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
            'get-commission' => ['GET'],
            'agreement' => ['POST'],
            'create-contract' => ['POST'],
            'list-contracts' => ['GET'],
            'get-contract' => ['GET'],
            'get-contract-status' => ['GET'],
            'set-contract-status' => ['POST'],
            'open-contract' => ['POST'],
        ];
    }

    public function actionGetCommission(): array
    {
        return $this->act($this->request->queryParams, CommissionModel::class);
    }

    public function actionAgreement(): array
    {
        return $this->act($this->request->post(), AgreementModel::class);
    }

    public function actionCreateContract(): array
    {
        return $this->act($this->request->post(), CreateContractModel::class);
    }

    public function actionListContracts(): array
    {
        return $this->act($this->request->queryParams, ListContractsModel::class);
    }

    public function actionGetContract(): array
    {
        return $this->act($this->request->queryParams, ContractModel::class);
    }

    public function actionGetContractStatus(): array
    {
        return $this->act($this->request->queryParams, ContractStatusModel::class);
    }

    public function actionSetContractStatus(): array
    {
        return $this->act($this->request->post(), SetContractStatusModel::class);
    }

    public function actionOpenContract(): array
    {
        return $this->act($this->request->post(), OpenContractModel::class);
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
