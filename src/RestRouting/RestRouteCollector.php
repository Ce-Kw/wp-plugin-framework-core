<?php

namespace CEKW\WpPluginFramework\Core\RestRouting;

use Auryn\Injector;
use Exception;
use WP_REST_Request;
use WP_REST_Server;

class RestRouteCollector
{
    private string $namespace = '';
    private string $currentKey;
    private ?Injector $injector = null;
    private array $routes = [];
    private $controllerClassInstance;

    public function __construct(Injector $injector)
    {
        $this->injector = $injector;
    }

    public function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }

    public function add(string $route, string $name = ''): RestRouteCollector
    {
        $this->currentKey = md5($route . $name);
        $this->routes[$this->currentKey] = [
            'namespace' => $this->namespace,
            'route' => $route,
            'methods' => [WP_REST_Server::READABLE],
            'permissionCallback' => '__return_true',
            'args' => []
        ];

        return $this;
    }

    public function setMethods(array $methods): RestRouteCollector
    {
        $this->routes[$this->currentKey]['methods'] = $methods;

        return $this;
    }

    public function setController(array $controller): RestRouteCollector
    {
        $this->routes[$this->currentKey]['callback'] = function (WP_REST_Request $request) use ($controller) {
            $args = [':request' => $request];
            foreach ($request->get_params() as $key => $value) {
                $args[':' . $key] = $value;
            }

            return $this->injector->execute($this->getControllerClassCallback($controller), $args);
        };

        return $this;
    }

    /**
     * @param callable|array $callback
     */
    public function setPermissionCallback($callback): RestRouteCollector
    {
        if (is_array($callback)) {
            $callback = $this->getControllerClassCallback($callback);
        }

        $this->routes[$this->currentKey]['permissionCallback'] = $callback;

        return $this;
    }

    /**
     * @param callable|array $callback
     */
    public function setArgsCallback($callback): RestRouteCollector
    {
        if (is_array($callback)) {
            $callback = $this->getControllerClassCallback($callback);
        }

        $this->routes[$this->currentKey]['args'] = call_user_func($callback);

        return $this;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    private function getControllerClassCallback(array $controller): callable
    {
        list($class, $method) = $controller;
        $classInstance = is_a($this->controllerClassInstance, $class) ? $this->controllerClassInstance : new $class();

        if (!method_exists($classInstance, $method)) {
            throw new Exception("{$class} should implement {$method} method.");
        }

        $this->controllerClassInstance = $classInstance;

        return [$classInstance, $method];
    }
}