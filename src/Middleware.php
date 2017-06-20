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
use Zend\Diactoros\Response;

/**
 * Class Middleware.
 *
 * @package JsonApiPsr7Middleware
 * @author  Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 * @link    https://github.com/lorenzomar/json-api-psr7-middleware
 */
class Middleware implements MiddlewareInterface
{
    const MEDIA_TYPE = 'application/vnd.api+json';

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        if (!$this->isValidMediaType($request)) {
            return new Response('php://memory', 415);
        }

        if (!$this->containsValidAccept($request)) {
            return new Response('php://memory', 406);
        }

        $response = $delegate->process($request);

        return $response;
    }

    /**
     * isValidMediaType.
     * Check if the first item in content-type header strictly match with allowed JSON API media type, without any extra
     * parameter.
     *
     * @param ServerRequestInterface $request
     *
     * @return bool
     */
    private function isValidMediaType(ServerRequestInterface $request)
    {
        $header = array_shift($request->getHeader('content-type'));

        return strtolower($header) === static::MEDIA_TYPE;
    }

    /**
     * containsValidAccept.
     * Check if the request contains at least once the JSON API allowed media type.
     *
     * @param ServerRequestInterface $request
     *
     * @return bool
     */
    private function containsValidAccept(ServerRequestInterface $request)
    {
        $hasMediaType = false;
        $accepts = $request->getHeader('accept');

        foreach ($accepts as $accept) {
            $accept = strtolower($accept);

            if (strpos($accept, static::MEDIA_TYPE) === 0) {
                $hasMediaType = true;
            }

            if ($accept === static::MEDIA_TYPE) {
                return true;
            }
        }

        return !$hasMediaType;
    }
}