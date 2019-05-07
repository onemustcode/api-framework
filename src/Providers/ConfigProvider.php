<?php declare(strict_types=1);

namespace OneMustCode\ApiFramework\Providers;

use Dotenv\Dotenv;
use OneMustCode\ApiFramework\Config\Config;
use OneMustCode\ApiFramework\Config\ConfigInterface;

class ConfigProvider extends AbstractProvider
{
    /**
     * @inheritdoc
     */
    public function load(): void
    {
        $this->loadDotenv();

        $config = new Config(
            require_once $this->app->getAppPath('config.php')
        );

        $this->app->bind(ConfigInterface::class, $config);
        $this->app->bind('config', $config);
    }

    /**
     * Loads the dotenv if a .env file exists
     */
    private function loadDotenv(): void
    {
        if (file_exists($this->app->getBasePath('.env'))) {
            Dotenv::create(
                $this->app->getBasePath()
            )->load();
        }
    }
}