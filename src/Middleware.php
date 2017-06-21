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
        if (!$this->isValidContentType($request)) {
            //return new Response('php://memory', 415);
        }

        if (!$this->containsValidAccept($request)) {
            return new Response('php://memory', 406);
        }

        $response = $delegate->process($request);

        return $response;
    }

    /**
     * isValidContentType.
     * Check if the first item in content-type header strictly match with allowed JSON API media type, without any extra
     * parameter.
     *
     * @param ServerRequestInterface $request
     *
     * @return bool
     */
    private function isValidContentType(ServerRequestInterface $request)
    {
        return array_shift($this->parseHeader($request, 'content-type')) === static::MEDIA_TYPE;
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

    /**
     * parseHeader.
     * Parse the header with the given name according to the specification:
     * https://tools.ietf.org/html/rfc7230#section-3.2
     *
     * @param ServerRequestInterface $request
     * @param string $headerName
     *
     * @return array
     */
    private function parseHeader(ServerRequestInterface $request, $headerName)
    {
        $values = [];

        foreach ($request->getHeader($headerName) as $value) {
            $values = array_merge($values, array_map('strtolower', array_map('trim', explode(',', $value))));
        }

        return $values;
    }
}