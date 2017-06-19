<?php

/**
 * This file is part of the JsonApiPsr7Middleware package.
 *
 * (c) Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 */

namespace JsonApiPsr7Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Middleware.
 *
 * @package JsonApiPsr7Middleware
 * @autohr Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 * @link https://github.com/lorenzomar/json-api-psr7-middleware
 */
class Middleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {

        // TODO: Implement process() method.
    }

    private function check
}