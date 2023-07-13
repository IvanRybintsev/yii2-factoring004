Factoring 004 Payment module for Yii2
======
Модуль для добавления способа оплаты __Рассрочка 0-0-4__ для Yii 2

Установка и настройка
------
1. Добавьте модуль через composer
```shell
php composer require bnplpartners/yii2-factoring004
```
2. Подключите модуль в файле конфигурации
```php
...
'modules' => [
    'factoring004' => [
        'class' => 'BnplPartners\Factoring004Yii2'
    ],
],
...
```
3. Добавить параметры модуля в params.php
```php
    'factoring004' => [
        'baseUri' => 'https://dev.bnpl.kz/api',
        'authLogin' => 'userLogin',
        'authPass' => 'userPassword',
        'partnerName' => 'example.shop',
        'partnerCode' => 'example.shop',
        'pointCode' => '01-1111-L1',
        'clientRoute' => 'redirect',
        'order_paid_status' => 'done'
    ]
```
4. Добавить способ оплаты
```shell
php yii migrate/up --migrationPath=vendor/bnpl-partners/yii2-factoring004/src/migrations
```
Виджеты
------
Добавление кнопки оплатить
```php
use \BnplPartners\Factoring004Yii2\widgets\PaymentWidget;
echo PaymentWidget::widget(['orderId' => 123, 'buttonClass' => 'btn btn-success']);
```
Виджет кнопки оплаты принимает параметры:
- __orderId__ - _обязательный_ -  номер заказа по которому нужно сформировать ссылку оплату, без него кнопка выводиться не будет
- __buttonClass__ - _опциональный_ - css класс кнопки оплаты, чтобы кнопка соответствовала стилю магазина, по-умолчанию класс кнопки пустой

------
Добавление графика платежей
```php
use \BnplPartners\Factoring004Yii2\widgets\PaymentSchedule;
echo PaymentSchedule::widget(['amount' => 10000, 'blockId' => 'block_id', 'styles' => 'margin-left:50px']);
```
Виджет графика платежей принимает параметры:
- __amount__ - _обязательный_ -  сумма заказа, без него график выводиться не будет
- __blockId__ - _опциональный_ - идентификатор html блока в котором будет находиться график платежей, по-умолчанию имеет значение - factoring004-schedule
- __styles__ - _опциональный_ - строка с набором css стилей для блока в котором будет находиться график платежей, по-умолчанию имеет пустое значение
