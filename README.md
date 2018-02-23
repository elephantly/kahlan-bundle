[![Current Version](https://img.shields.io/packagist/v/elephantly/kahlan-bundle.svg)](https://packagist.org/packages/elephantly/kahlan-bundle) [![Build Status](https://travis-ci.org/elephantly/kahlan-bundle.svg?branch=master)](https://travis-ci.org/elephantly/kahlan-bundle) [![Downloads](https://img.shields.io/packagist/dt/elephantly/kahlan-bundle.svg)](https://packagist.org/packages/elephantly/kahlan-bundle) [![Licence](https://img.shields.io/packagist/l/elephantly/kahlan-bundle.svg)](https://packagist.org/packages/elephantly/kahlan-bundle)
# kahlan-bundle
A ToolBox to use kahlan with symfony easily.

## Requirements :
* php >=5.4
* symfony/console ~2.7|~3.0|~4.0
* symfony/framework-bundle ~2.7|~3.0|~4.0
* kriswallsmith/buzz ^0.15
* kahlan/kahlan ~3.1|~4.0

## Install :
Simply use `composer require --dev "elephantly/kahlan-bundle"` or add `"elephantly/kahlan-bundle": "0.9.6"` to your `composer.json` file.

## Configuration :
Simply register the bundle in Symfony's kernel like any other bundle:

```php
<?php
// app/AppKernel.php
    public function registerBundles()
    {
        [...]

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            ...
            $bundles[] = new Elephantly\KahlanBundle\KahlanBundle();
        }

        return $bundles;
    }
```

That's it!

*Full documentation: [Elephantly/Kahlan-Bundle](https://elephantly.github.io/kahlan-bundle/)*

*Documentation powered by [Couscous](http://couscous.io/) with [Elephantly Template](https://github.com/elephantly/ElephantlyCouscous)*
