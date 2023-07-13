<?php

namespace BnplPartners\Factoring004Yii2\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;
use yii\web\View;

class PaymentWidget extends Widget
{

    public $orderId = '';
    public $buttonClass = '';
    private $action = '';
    private $clientRoute = 'redirect';

    public function init()
    {
        parent::init();
        $this->action = '/factoring004/preapp?orderId=' . $this->orderId;
        if (array_key_exists('factoring004', Yii::$app->params)) {
            $this->clientRoute = Yii::$app->params['factoring004']['clientRoute'];
            if ($this->clientRoute == 'modal') {
                $domain = 'bnpl.kz';
                if (stripos(Yii::$app->params['factoring004']['baseUri'], 'dev')) {
                    $domain = 'dev.bnpl.kz';
                }
                $this->getView()->registerJsFile("https://$domain/widget/index_bundle.js");
            }
        }

    }

    public function run()
    {
        parent::run();
        if (!empty($this->orderId)) {
            if ($this->clientRoute == 'modal') {
                Yii::$app->getView()->on(View::EVENT_END_BODY, function() {
                    echo $this->render('widget');
                });
            }
            return $this->render('payment-button',['action' => $this->action, 'clientRoute' => $this->clientRoute, 'buttonClass' => $this->buttonClass]);
        } else {
            return '';
        }
    }

}