<?php

namespace BnplPartners\Factoring004Yii2;

use BnplPartners\Factoring004\Auth\BearerTokenAuth;
use BnplPartners\Factoring004\OAuth\CacheOAuthTokenManager;
use BnplPartners\Factoring004\OAuth\OAuthTokenManager;
use BnplPartners\Factoring004\Order\OrderManager;
use dvizh\order\models\Element;
use Yii;
use yii\helpers\Url;

class PaymentProcessor
{
    public static function getRedirectLink($order)
    {
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
                'itemSum' => (int) ceil($item->getPrice() * $item->getCount()),
            ];
        }, $order->getElements()));

        $authManager = new OAuthTokenManager($baseUri . '/users/api/v1',
            Yii::$app->params['factoring004']['authLogin'], Yii::$app->params['factoring004']['authPass']);

        $cacheAuthManager = new CacheOAuthTokenManager($authManager, CacheAdapter::init(Yii::$app->cache), 'bnpl.payment');

        $token = new BearerTokenAuth($cacheAuthManager->getAccessToken()->getAccess());

        $orderManager = OrderManager::create($baseUri, $token);

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

        return $response->getRedirectLink();
    }
}