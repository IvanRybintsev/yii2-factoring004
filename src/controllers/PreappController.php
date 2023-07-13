<?php

namespace BnplPartners\Factoring004Yii2\controllers;

use BnplPartners\Factoring004\Auth\BearerTokenAuth;
use BnplPartners\Factoring004\Exception\ValidationException;
use BnplPartners\Factoring004\OAuth\CacheOAuthTokenManager;
use BnplPartners\Factoring004\OAuth\OAuthTokenManager;
use BnplPartners\Factoring004\Order\OrderManager;
use BnplPartners\Factoring004\Transport\GuzzleTransport;
use BnplPartners\Factoring004Yii2\CacheAdapter;
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

        $paymentMethod = Yii::$app->db->createCommand('SELECT id FROM order_payment_type WHERE slug=:slug')
            ->bindValue('slug','factoring004-payment')
            ->queryOne();

        if ($paymentMethod['id'] != $order->payment_type_id) {
            return 'Invalid payment method';
        }

        if ($order->payment != 'no') {
            return 'Order already paid';
        }
        if (!array_key_exists('factoring004', Yii::$app->params)) {
            return 'Invalid factoring004 configurations';
        }

        $responseType = Yii::$app->params['factoring004']['clientRoute'];
        $baseUri = Yii::$app->params['factoring004']['baseUri'];

        $itemsCount = (int) array_sum(array_map(function ($item) {
                return $item->count;
        }, $order->getElements()));

        $items = array_values(array_map(function (Element $item) {
            return [
                'itemId' => (string) $item->getId(),
                'itemName' => (string) $item->getName(),
                'itemCategory' => '',
                'itemQuantity' => (int) $item->getCount(),
                'itemPrice' => (int) ceil($item->getPrice()),
                'itemSum' => (int) ceil($item->getCost()),
            ];
        }, $order->getElements()));

        $authManager = new OAuthTokenManager($baseUri . '/users/api/v1',
            Yii::$app->params['factoring004']['authLogin'], Yii::$app->params['factoring004']['authPass']);

        $cacheAuthManager = new CacheOAuthTokenManager($authManager, CacheAdapter::init(Yii::$app->cache), 'bnpl.payment');

        $token = new BearerTokenAuth($cacheAuthManager->getAccessToken()->getAccess());

        $orderManager = OrderManager::create($baseUri, $token);


        try {
            $preAppData = [
                'partnerData' => [
                    'partnerName' => Yii::$app->params['factoring004']['partnerName'],
                    'partnerCode' => Yii::$app->params['factoring004']['partnerCode'],
                    'pointCode' => Yii::$app->params['factoring004']['pointCode'],
                ],
                'billNumber' => (string) $order->getId(),
                'billAmount' => (int) $order->getTotal(),
                'itemsQuantity' => $itemsCount,
                'successRedirect' => Url::to('', true),
                'postLink' => Url::to('factoring004/postlink', true),
                'items' => $items,
                'phoneNumber' => str_replace(['(',')','-','+',' '], '', $order->phone),
            ];

            $response = $orderManager->preApp($preAppData);

            if ($responseType == 'modal') {
                return '{"redirectLink":"' . $response->getRedirectLink() . '"}';
            } else {
                $this->redirect($response->getRedirectLink());
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