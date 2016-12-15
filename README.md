# kahlan-bundle
A ToolBox to use kahlan with symfony easily.

### Configuration :
Simply register the bundle in Symfony's kernel like any other bundle:
```php
// app/AppKernel.php
    public function registerBundles()
    {
        [...]

        if (in_array($this->getEnvironment(), array('dev', 'test'), true)) {
            [...]
            $bundles[] = new Elphtly\KahlanBundle\KahlanBundle();
        }

        return $bundles;
    }
```

### Usage :
Check [Kahlan's documentation](https://kahlan.github.io/docs/) for basic usage and principles.

#### Added features :
**Service Container :**
Symfony's service container is available in every spec file with `$this->container`.

**Services shortcuts :**
Every service registered in your Symfony application is available in spec files with these shortcuts :
```php
$this->has('my.awesome.service'); // To check is service is available
$this->get('my.awesome.service'); // To retrieve service
```

**Parameters shortcuts :**
Every parameter registered in your Symfony application is available in spec files with these shortcuts :
```php
$this->hasParameter('my.parameter'); // To check is parameter exists
$this->getParameter('my.parameter'); // To retrieve parameter
```

**Buzz Client**
`Buzz\Browser` is included as a shortcut in every spec file via `$this->client`.
Check [Buzz Documentation](https://github.com/kriswallsmith/Buzz) for usage.