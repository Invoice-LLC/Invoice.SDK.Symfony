<h1>Invoice Payment Module</h1>

<h3>Установка</h3>

Установите пакет через Composer:
```
composer require invoice-llc/payment-symfony:dev-master
```

<h3>Создание контроллера уведомлений</h3>

1.Создайте контроллер и унаследуйте класс AbstractNotificationController

```php
<?php

class InvoiceController extends AbstractNotificationController {

        //orderID - ID заказа в вашей системе

        function onPay($orderId, $amount)
        {
    
            //При успешной оплате
        }
    
        function onFail($orderId)
        {
            //При неудачной оплате
        }
    
        function onRefund($orderId)
        {
            //При возврате средств
        }
        
        function getApiKey() {
            return "Ваш API ключ";
        }   

}
```
2.Добавьте маршрут:
```php
notify:
    path: /notify
    controller: App\Controller\InvoiceController::notify
```

3.В личном кабинете Invoice(Настройки->Уведомления->Добавить) добавьте уведомление с типом **WebHook**
и адресом, который вы задали в конфиге(например: %url%/notify)

<h3>Создание платежей</h3>

```php
<?php

$invoice = new InvoicePaymentProcessor("Ваш API ключ","Ваш логин от ЛК Invoice");

$items = [
    //Название, цена за 1шт, кол-во, итоговая цена
    new ITEM('Какой-то предмет',10,1,10)
];
//ID заказа, цена, товары
$payment = $invoice->createPayment('ID заказа в вашей системе', 10, $items);

echo($payment->payment_url);
```

<h3>Поулчение статуса платежа</h3>

```php
<?php

$invoice = new InvoicePaymentProcessor("Ваш API ключ","Ваш логин от ЛК Invoice"));

$payment = $invoice->getPayment('ID заказа в вашей системе');

echo($payment->payment_url);
```

<h3>Создание возврата</h3>

```php
<?php

$invoice = new InvoicePaymentProcessor("Ваш API ключ","Ваш логин от ЛК Invoice"));

//ID заказа в вашей системе, сумма возврата, причина
$refundInfo = $invoice->createRefund('ID заказа в вашей системе', 10, 'Причина');

```
