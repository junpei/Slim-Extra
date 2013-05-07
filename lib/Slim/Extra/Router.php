<?php

namespace Slim\Extra;

class Router {
    public function route($route, $params = array()) {
        $app = \Slim\Slim::getInstance();
        //$keys = array_map(function ($key) {
        //    return ucfirst($key);
        //}, explode('/', ltrim($route, '/')));
        //$method = lcfirst(array_pop($keys));
        $keys = explode('/', ltrim($route, '/'));
        $method = array_pop($keys);
        $Class = $keys[count($keys) - 1];
        $namespace = "\Slim\Extra\Router\\" . implode('\\', $keys);

        require_once $app->config('routes.path') . '/' . implode('/', $keys) . '.php';

        return call_user_func_array("$namespace::$method", $params);
    }
}
