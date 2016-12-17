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
