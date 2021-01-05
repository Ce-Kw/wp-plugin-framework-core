<?php

namespace CEKW\WpPluginFramework\Core\RestRoute;

use CEKW\WpPluginFramework\Core\Package\AbstractPackage;

class RestRoutingPackage extends AbstractPackage
{
    private RestRouteCollector $restRouteCollector;

    public function load(): void
    {
        $this->restRouteCollector = new RestRouteCollector($this->injector);

        $this->loadConfig('routes/rest.php');
        do_action('cekw.wp_plugin_framework.rest_routes', $this->restRouteCollector);
        $this->registerRoutes();
    }

    protected function registerRoutes(): void
    {
        add_action('rest_api_init', function (): void {
            foreach ($this->restRouteCollector->getRoutes() as $route) {
                register_rest_route($route['namespace'], $route['route'], [
                    'methods' => $route['methods'],
                    'callback' => $route['callback'],
                    'permission_callback' => $route['permissionCallback'],
                    'args' => $route['args'],
                ]);
            }
        });
    }
}