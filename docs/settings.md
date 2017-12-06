# Изменение настроек
 ```php
 <?php
 require __DIR__ . '/../vendor/autoload.php';
 
 $domain = 'testdomain';
 $login = 'test@test.com';
 $hash = md5('test');

 $credentials = new \ddlzz\AmoAPI\CredentialsManager($domain, $login, $hash);

 $settings = new \ddlzz\AmoAPI\SettingsStorage();
 
 $settings->setScheme('http');
 $settings->setDomain('amocrm.saas');
 $settings->setUserAgent('Custom userAgent v0.3.1');
 $settings->setCookiePath(__DIR__ . '/../../files_dir/cookie.txt');

    /** @var \ddlzz\AmoAPI\Client $request */
    $request = \ddlzz\AmoAPI\ClientFactory::create($credentials, $settings);
 ```