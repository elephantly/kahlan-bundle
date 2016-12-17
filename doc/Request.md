---
currentMenu: request
---
## RequestHelper :

Should you ever need a Request object to give to some particular function, you can ask RequestHelper service.
Simply call it via the shortcut `$this->getRequest()` in your spec files.

**Method signature :**

``` php
<?php
public function getRequest($method = null, $url = null, array $parameters = array());
```

- You can call it without arguments and it will return a blank new Request instance.
  You can then fill it with whatever you want.

- Or you can call it with arguments. First two are the only mandatory ones in this case.
  Request Helper will call the router to fill your Request object with proper attributes.

- When given, third argument will be automatically added to `$request->query` or `$request->request`, depending on the method passed as a first argument.

*/!\ The second parameter, `$url`, is used with a route, relative to your website root. Simply pass the path part of your routing.*

*Example :*

```php
<?php
[...] // Inside a spec function...
// Blank Request object
$request = $this->getRequest();

// Request object filled without parameters
$request = $this->getRequest('get', '/entity/tag/1');

// Request object with parameters, say for testing form
$request = $this->getRequest('post', '/save/entity', array('entity' => $entity));