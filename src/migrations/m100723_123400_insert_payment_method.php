<?php

use yii\db\Schema;

class m100723_123400_insert_payment_method extends \yii\db\Migration
{
    public function up()
    {
        $this->insert('order_payment_type', [
            'slug' => 'yii2-factoring004-payment',
            'name' => 'Рассрочка 0-0-4',
            'description' => '<p>Купи сейчас, плати потом! Быстрое и удобное оформление рассрочки на 4 месяца. Моментальное одобрение, без комиссий и процентов. Для заказов суммой от 6 000 до 200 000 тг.</p>',
            'name_kz' => 'Рассрочка 0-0-4',
            'name_en' => 'Factoring 0-0-4',
            'description_kz' => '',
            'description_en' => '',
            'status' => 1,
            'is_visible_delivery' => 1
        ]);
    }

    public function down()
    {
        return false;
    }
}
