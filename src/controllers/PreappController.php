<?php

namespace BnplPartners\Factoring004Yii2\controllers;

use BnplPartners\Factoring004\Auth\BearerTokenAuth;
use BnplPartners\Factoring004\Exception\ValidationException;
use BnplPartners\Factoring004\OAuth\CacheOAuthTokenManager;
use BnplPartners\Factoring004\OAuth\OAuthTokenManager;
use BnplPartners\Factoring004\Order\OrderManager;
use BnplPartners\Factoring004\Transport\GuzzleTransport;
use BnplPartners\Factoring004Yii2\PaymentProcessor;
use BnplPartners\Factoring004Yii2\Validator;
use dvizh\order\models\Element;
use dvizh\order\models\Order;
use Yii;
use yii\caching\Cache;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;

class PreappController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['POST', 'GET'],
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
        $orderId = Yii::$app->request->get('orderId');
        /**
         * @var $order Order
         */
        $order = Yii::$app->order->get($orderId);

        Validator::validateOrder($order);

        $responseType = Yii::$app->params['factoring004']['clientRoute'];

        try {
            $redirectLink = PaymentProcessor::getRedirectLink($order);

            if ($responseType == 'modal') {
                return '{"redirectLink":"' . $redirectLink . '"}';
            } else {
                $this->redirect($redirectLink);
            }
        } catch (ValidationException $e) {
            if ($responseType == 'modal') {
                return '{"redirectLink":"' . Url::to('factoring004/error',true) . '", "details": "' . print_r($e->getResponse()->getDetails(), true) . '"}';
            } else {
                $this->redirect('error');
            }
        }
    }
}