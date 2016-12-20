<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 09/12/16
 * Time: 10:34
 */

namespace Elephantly\KahlanBundle\Services;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router;

class RequestHelper
{
    /**
     * @var Router
     */
    private $router;

    /**
     * RequestHelper constructor.
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Returns a Request object, empty to be filled with custom parameters, or pre-filled with given method and route
     *
     * @param string|null $method
     * @param string|null $url
     * @param array|null $parameters
     * @return Request
     */
    public function getRequest($method = null, $url = null, array $parameters = array())
    {
        try {
            $object = new Request();
            $object->setMethod($method);
            $context = new RequestContext();
            $context->fromRequest($object);
            $this->router->setContext($context);

            $parsedUrl  = null !== $url ? parse_url($url) : array();
            $method     = null == $method ? 'GET' : strtoupper($method);

            $query      = array();
            if ('GET' == $method && !isset($parsedUrl['query'])) {
                $query = $parameters;
            } else if (isset($parsedUrl['query']) && null !== $parsedUrl['query']) {
                $query = null !== parse_str($parsedUrl['query']) ? : array();
            }

            $request    = ('POST' || 'PUT') == $method ? $parameters : array() ;

            $attributes = isset($parsedUrl['path']) && null !== $parsedUrl['path'] ? $this->router->match($parsedUrl['path']) : array();

            $cookies    = array();
            $files      = array();
            $server     = array();

            $object->query->add($query);
            $object->request->add($request);
            $object->attributes->add($attributes);
            $object->cookies->add($cookies);
            $object->files->add($files);
            $object->server->add($server);
        } catch (\Exception $e) {
            return null;
        }

        return $object;
    }
}