<?php declare(strict_types=1);

namespace OneMustCode\ApiFramework\Providers;

use OneMustCode\ApiFramework\Router\Router;

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

        $router->match();
    }
}