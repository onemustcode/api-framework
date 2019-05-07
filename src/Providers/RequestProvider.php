<?php declare(strict_types=1);

namespace OneMustCode\ApiFramework\Providers;

use Symfony\Component\HttpFoundation\Request;

class RequestProvider extends AbstractProvider
{
    /**
     * @inheritdoc
     */
    public function load(): void
    {
        $request = Request::createFromGlobals();

        $this->app->bind(Request::class, $request);
        $this->app->bind('request', $request);
    }
}