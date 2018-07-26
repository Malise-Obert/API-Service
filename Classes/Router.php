<?php

namespace Classes;

class Router
{
    /**
     * @var array
     */
    protected $routes = [];

    /**
     * @param $routes
     */
    public function define($routes)
    {
        $this->routes = $routes;
    }

    /**
     * @param $request_method
     *
     * @return mixed
     * @throws \Exception
     */
    public function direct($request_method)
    {
        if (array_key_exists($request_method, $this->routes)) {
            return $this->callAction(
                ...explode('@', $this->routes[$request_method])
            );
        }
    }

    /**
     * @param $controller
     * @param $action
     *
     * @return mixed
     * @throws \Exception
     */
    protected function callAction($controller, $action)
    {
        if (! method_exists($controller, $action)) {

            throw new \Exception(
                "{$controller} does not respond to the ($action) action."
            );
        }

        return (new $controller)->$action();
    }
}
