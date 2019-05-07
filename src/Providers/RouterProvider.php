<?php declare(strict_types=1);

namespace OneMustCode\ApiFramework\Providers;

use OneMustCode\ApiFramework\Router\Router;
use OneMustCode\ApiFramework\Router\RouterInterface;

class RouterProvider extends AbstractProvider
{
    /**
     * @inheritdoc
     */
    public function load(): void
    {
        $router = new Router(
            $this->app
        );

        $router->load(
            $this->app->getAppPath('routes.php')
        );

        $this->app->bind('router', $router);
        $this->app->bind(RouterInterface::class, $router);
    }
}