<?php declare(strict_types=1);

namespace OneMustCode\ApiFramework\Providers;

interface ProviderInterface
{
    /**
     * Loads the provider
     */
    public function load(): void;
}