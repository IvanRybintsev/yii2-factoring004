<?php

namespace BnplPartners\Factoring004Yii2;

use PhpOffice\PhpSpreadsheet\Calculation\Exception;
use Yii;

class Validator
{
    /**
     * @throws \yii\db\Exception
     * @throws Exception
     */
    public static function validateOrder($order)
    {
        if (!self::validatePayment($order->payment_type_id)) {
            throw new Exception('Invalid payment method');
        }

        if ($order->payment != 'no') {
            throw new Exception('Order already paid');
        }
        if (!self::validateParams()) {
            throw new Exception('Invalid factoring004 configurations');
        }
    }

    public static function validateParams()
    {
        return array_key_exists('factoring004', Yii::$app->params);
    }

    public static function validatePayment($paymentTypeId)
    {
        $paymentMethod = Yii::$app->db->createCommand('SELECT id FROM order_payment_type WHERE slug=:slug')
            ->bindValue('slug','factoring004-payment')
            ->queryOne();
        return $paymentMethod['id'] == $paymentTypeId;
    }
}