---
currentMenu: home
---
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

That's it!


**Buzz Client**

`Buzz\Browser` is included as a shortcut in every spec file via `$this->client`. You can then make use of it's http-based methods like `get()`, `post()`...

Check [Buzz Documentation](https://github.com/kriswallsmith/Buzz) for more details.
