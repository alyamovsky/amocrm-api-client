# Обновление сделок
 ```php
 <?php
 require __DIR__ . '/../vendor/autoload.php';
 
 $domain = 'testdomain';
 $login = 'test@test.com';
 $hash = md5('test');
 
 try {
    $credentials = new \ddlzz\AmoAPI\CredentialsManager($domain, $login, $hash);

    /** @var \ddlzz\AmoAPI\Client $client */
    $client = \ddlzz\AmoAPI\ClientFactory::create($credentials);

    /** @var \ddlzz\AmoAPI\Entities\Amo\Lead $lead */
    $lead = $client->findById('lead', 4095565); // Получаем сделку со всеми заполненными полями

    $lead['name'] = 'New name lead' . rand();
    $lead['price'] = 20000;
    $lead['updated_at'] = time(); // Если вы явно не укажете время обновления, оно обновится автоматически
    $lead['tags'] = 'tag 1, tag 2, tag 3';

    $result = $client->update($lead);
    echo $result;
 } catch (Exception $e) {
     echo $e->getFile() . ': ' . $e->getMessage();
 }
 ```