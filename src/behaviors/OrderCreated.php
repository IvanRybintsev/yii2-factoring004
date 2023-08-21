<?php

namespace BnplPartners\Factoring004Yii2\behaviors;

use BnplPartners\Factoring004\Exception\ValidationException;
use BnplPartners\Factoring004Yii2\PaymentProcessor;
use BnplPartners\Factoring004Yii2\Validator;
use dvizh\order\models\Payment;
use Yii;
use yii\base\Behavior;

class OrderCreated extends Behavior
{
    public function events()
    {
        return [
            'create' => 'checkPaymentMethod'
        ];
    }

    public static function checkPaymentMethod($event)
    {
        if (!Validator::validatePayment($event->model->payment_type_id)) {
            return;
        }

        if (!Validator::validateParams()) {
            return;
        }

        try {
            $redirectLink = PaymentProcessor::getRedirectLink($event->model);
            Yii::$app->response->redirect($redirectLink)->send();
        } catch (\Exception $e) {
            Yii::$app->response->redirect('factoring004/error')->send();
        }
    }
}