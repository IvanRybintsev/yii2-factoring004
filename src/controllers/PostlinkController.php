<?php

namespace BnplPartners\Factoring004Yii2\controllers;

use BnplPartners\Factoring004\Signature\PostLinkSignatureValidator;
use Exception;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;

class PostlinkController extends Controller
{
    const STATUS_PREAPPROVED = 'preapproved';
    const STATUS_COMPLETED = 'completed';
    const STATUS_DECLINED = 'declined';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['POST'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if ($action->id === 'index') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        try {
            $data = Json::decode(Yii::$app->request->getRawBody(), true);

            $this->validateSignature($data);

            if ($data['status'] == self::STATUS_PREAPPROVED) {
                echo '{"response":"preapproved"}';
            }

            if ($data['status'] == self::STATUS_DECLINED) {
                echo '{"response":"declined"}';
            }

            if ($data['status'] == self::STATUS_COMPLETED) {
                echo '{"response":"ok"}';
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    private function validateSignature($data)
    {
        $secretKey = Yii::$app->params['factoring004']['partnerCode'];
        $validator = new PostLinkSignatureValidator($secretKey);

        $validator->validateData($data);
    }
}