# Добавление сделок
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

    $lead = new \ddlzz\AmoAPI\Entities\Amo\Lead();

    // Заполним модель данными. Формат заполнения такой:
    $lead['name'] = 'New lead';
    $lead['created_at'] = time(); // Обязательные поля created_at и modified_at будут заполнены
    // автоматически, если не указывать их явно
    $lead['sale'] = 150000; // Аналог из старого АПИ - price. Вы можете использовать как старые,
    // так и новые варианты названия поля. Старые будут преобразованы в новые далее при валидации.
    $lead['tags'] = 'tag 1, tag 2, tag 3';

    // Из-за того, что для добавления и редактирования разные поля будут являться обязательными,
    // валидация и заполнение сущности данными происходит позже, в методе клиента add либо update.
    $result = $request->add($lead);
    echo $result;
 } catch (Exception $e) {
     echo $e->getFile() . ': ' . $e->getMessage();
 }
 ```