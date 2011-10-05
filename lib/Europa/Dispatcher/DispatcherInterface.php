<?php

namespace Europa\Dispatcher;
use Europa\Request\RequestInterface;
use Europa\Response\ResponseInterface;
use Europa\Router\RequestRouterInterface;

/**
 * Represents a basic implementation of a dispatcher.
 *
 * @category Dispatcher
 * @package  Europa
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2011 Trey Shugart http://europaphp.org/license
 */
interface DispatcherInterface
{
    /**
     * Renders, sends all headers and outputs the result.
     * 
     * @return void
     */
    public function dispatch(RequestInterface $request, ResponseInterface $response, RequestRouterInterface $router = null);
}