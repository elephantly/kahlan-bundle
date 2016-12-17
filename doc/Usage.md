---
currentMenu: usage
---
## Usage :

To write spec files, follow the indications provided in Kahlan documentation.
You first have to create a folder named `spec` at the root of your project, on the same level as your `src`.
You must then replicate your `src`'s folder architecture, and create a file named after each one of the original files you want to test, changing its extension to `.spec.php`

*Example adapted from Kahlan Documentation :*
```
├── spec
│   └── AppBundle
│       ├── Controller
│       │   └── AppController.spec.php
│       └── AppBundle.spec.php
├── src
│   └── AppBundle
│       ├── Controller
│       │   └── AppController.php
│       └── AppBundle.php
├── composer.json
└── README.md
```

In those spec files, you will write your specs using the DSL syntax. Kahlan being Behaviour Driven, it provides functions that allow you to clearly describe what you expect your code to do.
Simply declare a context for your class with a `describe()`, in which you will declare a subcontext for each function of your class.

*Example :*
```php
<?php

use Symfony\Component\HttpFoundation\Response;
use AppBundle\AppController;

describe('AppController', function () {
    describe('indexAction', function () {
        it('returns a Response object', function () {
            $controller = new AppController();
            $result     = $controller->indexAction();
            expect($result)->toBeAnInstanceOf(Response::class);
        });
    });
});
```

Check [Kahlan's documentation](https://kahlan.github.io/docs/) for more on spec writing principles.

Once your spec files are written, simply call `php app/console kahlan:run`. All options mentioned in Kahlan's documentation  are available.

