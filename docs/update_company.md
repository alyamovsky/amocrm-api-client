# Обновление компаний
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

    /** @var \ddlzz\AmoAPI\Model\Amo\Company $company */
    $company = $client->findById('company', 4095565); // Получаем контакт со всеми заполненными полями

    $company['name'] = 'New name company' . rand();
    $company['contacts_id'] = '10378507, 10378508';
    $company['leads_id'] = 6089435;
    $company['updated_at'] = time(); // Если вы явно не укажете время обновления, оно обновится автоматически
    $company['tags'] = 'tag 1, tag 2, tag 3';

    $result = $client->update($company);
    echo $result;
 } catch (Exception $e) {
     echo $e->getFile() . ': ' . $e->getMessage();
 }
 ```