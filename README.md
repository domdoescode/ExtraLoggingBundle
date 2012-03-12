# Extra Logging Bundle

This [Symfony2](http://symfony2.com) bundle provides additional Symfony2 processors for the [Monolog](https://github.com/Seldaek/monolog) PHP logging library.

At present, the additional processors included are:

* Session - Provides the current session token
* User - Provides user details from Symfony2`s AdvancedUserInterface (customisable)
* Request - Provides details of the request made at the point of logging

## Installation

### Update Deps

Add the following lines to your ``deps`` file:

```
[DomUdallExtraLoggingBundle]
    git=https://github.com/domudall/ExtraLoggingBundle.git
    target=bundles/DomUdall/ExtraLoggingBundle
```

Update your vendors in the usual way:

``` bash
$ ./bin/vendors install
```

### Configure Autoloader

Add the following to your autoloader:

``` php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    // ...

    'DomUdall'      => __DIR__.'/../vendor/bundles',
));
```

### Enable the Bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...

        new DomUdall\ExtraLoggingBundle\DomUdallExtraLoggingBundle(),
    );
}
```

### Wire Up DIC

Depending on which processor you want to use, you`ll need to define different services.

#### Session Processor

``` yml
    domudall_monolog.processor.session:
        class: DomUdall\ExtraLoggingBundle\Processor\SessionProcessor
        arguments:  [ @session ]
        tags:
            - { name: monolog.processor }
```

#### User Processor

``` yml
    domudall_monolog.processor.user:
        class: DomUdall\ExtraLoggingBundle\Processor\UserProcessor
        arguments:  [ @service_container ]
        tags:
            - { name: monolog.processor }
```

#### Request Processor

``` yml
    domudall_monolog.processor.request:
        class: DomUdall\ExtraLoggingBundle\Processor\RequestProcessor
        arguments:  [ @service_container ]
        tags:
            - { name: monolog.processor }
```

## Customising User Processor

It`s common for implementations of user systems to contain a lot of extra fields, which is why the user processor has been built to be super simple to extend:

### Extend the Processor

``` php
<?php
namespace Acme\UserBundle\Processor;

use DomUdall\ExtraLoggingBundle\Processor\UserProcessor as BaseUserProcessor;

class UserProcessor extends BaseUserProcessor
{
    public function setAdditionalFields()
    {
        $this->record['user']['favourite_dj'] = $this->user->getFavouriteDj();
    }
}
```

### Wire Up DIC

``` yml
    acme_monolog.processor.user:
        class: Acme\UserBundle\Processor\UserProcessor
        arguments:  [ @service_container ]
        tags:
            - { name: monolog.processor }
```

Done!

## To Do`s

* Write all the things to do...

## Credits

[Dom Udall](https://github.com/domudall/)

## Licence
Licenced under the [New BSD License](http://opensource.org/licenses/bsd-license.php)
