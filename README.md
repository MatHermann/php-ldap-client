# My-CoolPay PHP SDK

[![Latest version on Packagist](https://img.shields.io/packagist/v/mathermann/php-ldap-client?logo=packagist)][packagist]
[![Total downloads](https://img.shields.io/packagist/dt/mathermann/php-ldap-client?logo=packagist)][packagist]
[![Licence](https://img.shields.io/github/license/mathermann/php-ldap-client?logo=github)][repo]
[![PHP version](https://img.shields.io/packagist/php-v/mathermann/php-ldap-client?logo=php)][composer-file]  
Easily authenticate users and deal with LDAP server in PHP

## Usage
```php
<?php

require_once 'vendor/autoload.php';

use MatHermann\Ldap\Exception\LdapException;
use MatHermann\Ldap\LdapClient;

try {
    $host = '127.0.0.1';
    $port = 389;
    $dn = 'DC=yourDomain,DC=com';
    $OU = 'yourOrganizationalUnit';

    $username = 'yourUsername';
    $password = 'yourPassword';

    $client = new LdapClient($host, $port, $dn);

    $user = $client->signInUser($username, $password, "OU=$OU");

    var_dump($user); // Access user properties with $user->get('property')

} catch (LdapException $exception) {
    var_dump($exception); // Handle LDAP exceptions
}
```

[packagist]: https://packagist.org/packages/mathermann/php-ldap-client
[repo]: https://github.com/mathermann/php-ldap-client
[composer-file]: https://github.com/mathermann/php-ldap-client/blob/master/composer.json
