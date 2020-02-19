<?php

namespace exchanged;

class Router
{
    protected static $route = [];
    protected static $routes = [];

    public static function add($regex, $route = [])
    {
        self::$routes[$regex] = $route;
    }

    /**
     * @return array
     */
    public static function getRoutes()
    {
        return self::$routes;
    }

    /**
     * @return array
     */
    public static function getRoute()
    {
        return self::$route;
    }

    public static function dispatch($url)
    {
        $url = self::removeQueryString($url);
        if (self::matchRoute($url)) {
            $controller = 'app\controllers\\' . self::$route['controller'] . 'Controller';
            if (class_exists($controller)){
                $controllerObject = new $controller(self::getRoute());
                $action = self::loverCamelCase(self::$route['action']) . 'Action';
                if (method_exists($controllerObject, $action)){
                    $controllerObject->$action();
                    $controllerObject->getView();
                } else {
                    throw new \Exception("Method | $controller :: $action | not fount", 404);
                }
            } else {
                throw new \Exception("Controller | {$controller} | not fount", 404);
            }
        } else {
            throw new \Exception("Page not fount", 404);
        }
    }

    private static function matchRoute($url)
    {
        foreach (self::getRoutes() as $pattern => $route) {
            if (preg_match("#{$pattern}#", $url, $matches)) {
                foreach ($matches as $k => $v) {
                    if (is_string($k)) {
                        $route[$k] = $v;
                    }
                }
                if (empty($route['action'])) {
                    $route['action'] = 'index';
                }
                $route['controller'] = self::upperCamelCase($route['controller']);

                self::$route = $route;
                return true;
            }
        }
        return false;
    }

    private static function removeQueryString($url)
    {
        if ($url) {
            $params = explode('&', $url, 2);
            if (false === strpos($params[0], '=')) return rtrim($params[0], '/');
        }
        return '';
    }

    private static function upperCamelCase($str)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $str)));
    }

    private static function loverCamelCase($str)
    {
        return lcfirst(self::upperCamelCase($str));
    }

}