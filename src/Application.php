<?php declare(strict_types=1);

namespace OneMustCode\ApiFramework;

use DI\ContainerBuilder;
use OneMustCode\ApiFramework\Config\ConfigInterface;
use OneMustCode\ApiFramework\Providers\ConfigProvider;
use OneMustCode\ApiFramework\Providers\RequestProvider;
use OneMustCode\ApiFramework\Providers\RouterProvider;
use OneMustCode\ApiFramework\Router\RouterInterface;

class Application
{
    /** @var bool */
    protected $started = false;

    /** @var ContainerBuilder $container */
    protected $container;

    /** @var string */
    protected $basePath;

    /** @var array */
    protected $defaultProviders = [];

    /** @var array */
    protected $customProviders = [];

    /**
     * @param string $basePath
     */
    public function __construct(
        string $basePath
    )
    {
        $this->basePath = $basePath;
        $this->container = (new ContainerBuilder())->build();
    }

    /**
     * @param string|null $path
     * @return string
     */
    public function getBasePath(string $path = ''): string
    {
        return $this->basePath . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * @param string $path
     * @return string
     */
    public function getAppPath(string $path = ''): string
    {
        return $this->getBasePath('app' . ($path ? DIRECTORY_SEPARATOR . $path : $path));
    }

    /**
     * @param string $path
     * @return string
     */
    public function getControllersPath(string $path = ''): string
    {
        return $this->getBasePath('Controllers' . ($path ? DIRECTORY_SEPARATOR . $path : $path));
    }

    /**
     * @param string $path
     * @return string
     */
    public function getProvidersPath(string $path = ''): string
    {
        return $this->getAppPath('Providers' . ($path ? DIRECTORY_SEPARATOR . $path : $path));
    }

    /**
     * @param string $name
     * @param $value
     */
    public function bind(string $name, $value): void
    {
        $this->container->set($name, $value);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function get(string $name)
    {
        return $this->container->get($name);
    }

    /**
     * @param $callable
     * @param array $parameters
     * @return mixed
     */
    public function call($callable, array $parameters = [])
    {
        return $this->container->call($callable, $parameters);
    }

    /**
     * @return string
     */
    public function getEnvironment(): string
    {
        return $this->get(ConfigInterface::class)->get('environment');
    }

    /**
     * Loads the default application providers
     */
    private function loadDefaultProviders(): void
    {
        foreach ($this->defaultProviders as $provider) {
            $this->loadProvider($provider);
        }
    }

    /**
     * Loads the custom providers
     */
    private function loadCustomProviders(): void
    {
        $this->customProviders = (require $this->getAppPath('config.php'))['providers'];

        foreach ($this->customProviders as $provider) {
            $this->loadProvider($provider);
        }
    }

    /**
     * @param string $provider
     */
    public function loadProvider(string $provider): void
    {
        (new $provider($this))->load();
    }

    /**
     * Starts the application
     */
    public function start(): void
    {
        if ($this->started === true) {
            return;
        }

        $this->started = true;

        $this->bind(Application::class, $this);
        $this->bind('app', $this);

        $this->loadDefaultProviders();

        $this->loadCustomProviders();

        $this->get('router')->match();
    }
}