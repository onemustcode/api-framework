<?php declare(strict_types=1);

namespace OneMustCode\ApiFramework\Router;

use OneMustCode\ApiFramework\Application;
use OneMustCode\ApiFramework\Exceptions\LogicException;
use OneMustCode\ApiFramework\Exceptions\RouteNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class Router implements RouterInterface
{
    /** @var RouteCollection */
    protected $routes;

    /** @var Application */
    protected $app;

    /**
     * @param Application $app
     */
    public function __construct(
        Application $app
    )
    {
        $this->app = $app;
        $this->routes = new RouteCollection();
    }

    public function load($routes): void
    {
        $router = $this;
        require $routes;
    }

    public function get(string $path, array $options): void
    {
        $this->registerRoute(['GET'], $path, $options);
    }

    public function post(string $path, $options): void
    {
        $this->registerRoute(['POST'], $path, $options);
    }

    public function patch(string $path, $options): void
    {
        $this->registerRoute(['PATCH'], $path, $options);
    }

    public function put(string $path, $options): void
    {
        $this->registerRoute(['PUT'], $path, $options);
    }

    public function delete(string $path, $options): void
    {
        $this->registerRoute(['DELETE'], $path, $options);
    }

    /**
     * @param array $methods
     * @param string $path
     * @param array $options
     */
    private function registerRoute(array $methods, string $path, array $options): void
    {
        $route = new Route(
            $path,
            [
                'controller' => $options['class'],
                'method' => $options['method'] ?? '__invoke',
                'params' => $options['params'] ?? [],
            ]
        );

        $route->setMethods($methods);

        $this->routes->add($path, $route);
    }

    public function match(): void
    {
        try {
            $context = new RequestContext();
            $context->fromRequest(Request::createFromGlobals());

            $matcher = new UrlMatcher($this->routes, $context);

            $match = $matcher->match($context->getPathInfo());

            $parameters = [];

            foreach ($match['params'] as $key => $value) {
                if (array_key_exists($key, $match) === true) {
                    $parameters[$key] = $match[$key];
                }
            }

            $this->app->call([$match['controller'], $match['method']], $parameters);
        } catch (\Symfony\Component\Routing\Exception\RouteNotFoundException $e) {
            throw new RouteNotFoundException(
                $e->getMessage()
            );
        } catch (\Exception $e) {
            throw new LogicException($e->getMessage());
        }
    }
}