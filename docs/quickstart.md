```php
<?php
require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../autoload.php';

$domain = '';
$login = '';
$hash = '';

try {
    $credentials = new \ddlzz\AmoAPI\CredentialsManager($domain, $login, $hash);

    /** @var \ddlzz\AmoAPI\Client $request */
    $request = \ddlzz\AmoAPI\ClientFactory::create($credentials);

    // Если вы используете другой домен, например amocrm.com, или протокол http, например работая с dev-сервером amocrm,
    // можете указать эти параметры в настройках:

    // $settings = new \ddlzz\AmoAPI\SettingsStorage();
    // $settings->setScheme('http');
    // $settings->setDomain('amocrm.local');

    // И передать объект SettingsStorage нашему клиенту
    // $request = \ddlzz\AmoAPI\ClientFactory::create($credentials, $settings);


    // Создадим модель сущности
    $lead = new \ddlzz\AmoAPI\Entities\Amo\Lead();

    // Заполним модель данными. Формат заполнения такой:
    $lead['name'] = 'testlol';
    $lead['created_at'] = time();
    $lead['sale'] = 150000; // Аналог из старого АПИ - price. Вы можете использовать как старые, так и новые варианты
    // названия поля. Старые будут преобразованы в новые далее при валидации.
    // Валидация и заполнение сущности данными происходит позже, в методе клиента add либо update. Это связано с тем,
    // что для добавления и редактирования разные поля будут являться обязательными.

    $result = $request->set($lead, 'add');
    echo $result;
} catch (Exception $e) {
    echo $e->getFile() . ': ' . $e->getMessage();
}
```