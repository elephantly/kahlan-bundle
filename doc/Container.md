## Service Container :

Symfony's service container is available in every spec file with `$this->container`.
No `use` statement needed, no clas to extend from.



### Services shortcuts :

Every service registered in your Symfony application is available in spec files with these shortcuts :

```php
<?php
$this->has('my.awesome.service'); // To check is service is available
$this->get('my.awesome.service'); // To retrieve service
```


### Parameters shortcuts :

Every parameter registered in your Symfony application is available in spec files with these shortcuts :

```php
<?php
$this->hasParameter('my.parameter'); // To check is parameter exists
$this->getParameter('my.parameter'); // To retrieve parameter
```

#### Tip :

If you want to test a service, or if you need to use it multiple times in your spec file,
register it as a global attribute of your spec by means of a `beforeAll()` at the beginning of your topmost context.

*Example :*
```php
<?php

describe('ClassToBeTested', function() {
    beforeAll(function () {
        $this->service = $this->get('my.awesome_service');
    });
    [...]
});
```