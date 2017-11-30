# amoCRM PHP API Client
Библиотека для работы с АПИ [amoCRM](https://amocrm.ru/).
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ddlzz/amocrm-api-client/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/ddlzz/amocrm-api-client/?branch=develop) [![Code Coverage](https://scrutinizer-ci.com/g/ddlzz/amocrm-api-client/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/ddlzz/amocrm-api-client/?branch=develop) [![Build Status](https://scrutinizer-ci.com/g/ddlzz/amocrm-api-client/badges/build.png?b=develop)](https://scrutinizer-ci.com/g/ddlzz/amocrm-api-client/build-status/develop)
## Установка
Введите в командной строке:
```
$ composer require ddlzz/amocrm-api-client
```

Или пропишите в `composer.json`:
```
{
     "require": {
         "ddlzz/amocrm-api-client": "@stable"
     }
}
```
 
## Пример
 ```php
 <?php
 require __DIR__ . '/../vendor/autoload.php';
 
 $domain = 'testdomain';
 $login = 'test@test.com';
 $hash = md5('test');
 
 try {
    $credentials = new \ddlzz\AmoAPI\CredentialsManager($domain, $login, $hash);

    /** @var \ddlzz\AmoAPI\Client $request */
    $request = \ddlzz\AmoAPI\ClientFactory::create($credentials);

    // Если вы используете другой домен, например amocrm.com, или протокол http, например работая с dev-сервером amocrm,
    // можете указать эти параметры в настройках:

    // $settings = new \ddlzz\AmoAPI\SettingsStorage();
    // $settings->setScheme('http');
    // $settings->setDomain('amocrm.saas');

    // И передать объект SettingsStorage нашему клиенту
    // $request = \ddlzz\AmoAPI\ClientFactory::create($credentials, $settings);

    // Создадим модель сущности
    $lead = new \ddlzz\AmoAPI\Entities\Amo\Lead();

    // Заполним модель данными. Формат заполнения такой:
    $lead['name'] = 'testlol';
    $lead['created_at'] = time(); // Обязательные поля created_at и modified_at будут заполнены автоматически, если не указывать их явно
    $lead['sale'] = 150000; // Аналог из старого АПИ - price. Вы можете использовать как старые, так и новые варианты
    // названия поля. Старые будут преобразованы в новые далее при валидации.
    // Валидация и заполнение сущности данными происходит позже, в методе клиента add либо update. Это связано с тем,
    // что для добавления и редактирования разные поля будут являться обязательными.

    $result = $request->add($lead);
    echo $result;
 } catch (Exception $e) {
     echo $e->getFile() . ': ' . $e->getMessage();
 }
 ```
## Возможности
* Библиотека работает с [новым](https://www.amocrm.ru/developers/content/406/abilities/) API, но понимает также названия полей из старой документации.
* По умолчанию используется домен amocrm.ru, также вы можете [указать](*) домен amocrm.com или dev-сервер amocrm в настройках.
* Пауза между запросами в рамках одного обращения к клиенту.

Сущности, с которыми на данный момент работает библиотека:
* Сделки (Добавление, редактирование)
* Контакты (Добавление, редактирование)
* Компании (Добавление, редактирование)