# kahlan-bundle
A ToolBox to use kahlan with symfony easily.

## Configuration :
Simply register the bundle in Symfony's kernel like any other bundle:

```php
<?php
// app/AppKernel.php
    public function registerBundles()
    {
        $bundles = array(
            [...]
            new Elephantly\KahlanBundle\KahlanBundle(),
        );
        [...]

        return $bundles;
    }
```

## Usage :
Check [Kahlan's documentation](https://kahlan.github.io/docs/) for basic spec writing principles.

Once your spec files are written, simply call `php app/console kahlan:run`. All options mentioned in Kahlan's documentation  are available.

### Added features :
**Service Container :**

Symfony's service container is available in every spec file with `$this->container`.



**Services shortcuts :**

Every service registered in your Symfony application is available in spec files with these shortcuts :

```php
<?php
$this->has('my.awesome.service'); // To check is service is available
$this->get('my.awesome.service'); // To retrieve service
```


**Parameters shortcuts :**

Every parameter registered in your Symfony application is available in spec files with these shortcuts :

```php
<?php
$this->hasParameter('my.parameter'); // To check is parameter exists
$this->getParameter('my.parameter'); // To retrieve parameter
```


**RequestHelper :**

Should you ever need a Request object to give to some particular function, you can ask RequestHelper service.
Simply call it via `$this->getRequest()`.

``` php
<?php
public function getRequest($method = null, $url = null, array $parameters = array());
```

- You can call it without arguments and it will return a blank new Request instance.
- Or you can call it with arguments. First two are the only mandatory ones in this case.
  Request Helper will call the router to fill your Request object with proper attributes.
- When given, third argument will be automatically added to `$request->query` or `$request->request`, depending on the method

*/!\ The second parameter, `$url`, is used with a route, relative to your website root.*



**Buzz Client**

`Buzz\Browser` is included as a shortcut in every spec file via `$this->client`. You can then make use of it's http-based methods like `get()`, `post()`...

Check [Buzz Documentation](https://github.com/kriswallsmith/Buzz) for more details.
