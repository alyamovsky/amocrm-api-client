# Обновление контактов
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

    /** @var \ddlzz\AmoAPI\Model\Amo\Contact $contact */
    $contact = $client->findById('contact', 4095565); // Получаем контакт со всеми заполненными полями

    $contact['name'] = 'New name contact' . rand();
    $contact['company_id'] = 10378507;
    $contact['leads_id'] = 6089435;
    $contact['updated_at'] = time(); // Если вы явно не укажете время обновления, оно обновится автоматически
    $contact['tags'] = 'tag 1, tag 2, tag 3';

    $result = $client->update($contact);
    echo $result;
 } catch (Exception $e) {
     echo $e->getFile() . ': ' . $e->getMessage();
 }
 ```