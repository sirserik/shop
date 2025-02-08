<?php

namespace Core;

class Router
{
    private $routes = [];
    private $notFoundCallBack;
    private $baseUrl;

    public function __construct($baseUrl = '')
    {
        $this->baseUrl = rtrim($baseUrl,'/');
    }


    /**
     * @param string $method
     * @param string $pattern
     * @param callable $callback
     * @param array $options
     * @return void
     */
    public function  addRoute(string $method, string $pattern, callable $callback, array $options =[]): void
    {
        $pattern = $this->baseUrl. rtrim($pattern,'/');
        $pattern = preg_replace('#/{([^\/]+)}#','/(?P<$1>[^/]+)',$pattern);
        $pattern = '#'.$pattern.'#';

        $this->routes[] = [
            'method' =>$method,
            'pattern' => $pattern,
            'callback'=>$callback,
            'options' =>$options
        ];
    }

    public function get(string $pattern,callable $callback,array $options =[]): void
    {
        $this->addRoute('GET',$pattern,$callback,$options);
    }

    public function post(string $pattern,callable $callback,array $options =[]): void
    {
        $this->addRoute('POST',$pattern,$callback,$options);
    }

    public function setNotFoundHandler(callable $callback): void
    {
        $this->notFoundCallBack = $callback;
    }
}