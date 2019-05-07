<?php declare(strict_types=1);

namespace OneMustCode\ApiFramework\Router;

interface RouterInterface
{
    public function get(string $path, array $options): void;

    public function post(string $path, $options): void;

    public function patch(string $path, $options): void;

    public function put(string $path, $options): void;

    public function delete(string $path, $options): void;

    public function load($routes): void;
}