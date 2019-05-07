<?php declare(strict_types=1);

namespace OneMustCode\ApiFramework\Config;

class Config implements ConfigInterface
{
    /** @var array */
    protected $config;

    /**
     * @param array $config
     */
    public function __construct(
        array $config
    )
    {
        $this->config = $config;
    }

    /**
     * @inheritdoc
     */
    public function get(string $key, $default = null)
    {
        if (strpos($key, '.') === false) {
            return $this->config[$key] ?? $default;
        }

        foreach (explode('.', $key) as $segment) {
            if (is_array($this->config) && array_key_exists($segment, $this->config)) {
                $this->config = $this->config[$segment];
            } else {
                return $default;
            }
        }

        return $this->config;
    }
}