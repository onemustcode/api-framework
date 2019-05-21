<?php declare(strict_types=1);

namespace OneMustCode\ApiFramework\Providers;

use OneMustCode\ApiFramework\Router\Exceptions\RouteNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

class ErrorHandlerProvider extends AbstractProvider
{
    /**
     * @inheritdoc
     */
    public function load(): void
    {
        error_reporting(-1);

        set_exception_handler([$this, 'handleException']);
    }

    /**
     * Handles the exception
     *
     * @param Throwable $exception
     * @return void
     */
    public function handleException(Throwable $exception): void
    {
        $this->renderException($exception);
    }

    /**
     * Renders the exception
     *
     * @param Throwable $exception
     * @return void
     */
    public function renderException(Throwable $exception): void
    {
        $response = new JsonResponse();

        if ($exception instanceof RouteNotFoundException) {
            $response
                ->setStatusCode(404)
                ->send();
        }

        $response
            ->setStatusCode(500)
            ->send();
    }
}