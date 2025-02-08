<?php

namespace Core;

class Router
{
    private array $routes = [];
    private $notFoundCallBack;
    private array $middleware = [];
    private $baseUrl;
    private ErrorHandler $errorHandler;

    public function __construct($baseUrl = '', bool $isDev = false)
    {
        $this->baseUrl = rtrim($baseUrl,'/');
        $this->errorHandler = new ErrorHandler($isDev);
        $this->errorHandler->register();
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

    public function addMiddleware(callable $middleware){
        $this->middleware[] = $middleware;
    }

    public function dispatch(){
        try {
            $requestMethod = strtoupper($_SERVER['REQUEST_METHOD']);
            $requestUri = filter_var(parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH),FILTER_SANITIZE_URL);

            foreach ($this->routes as $route) {
                if ($route['method']===$requestMethod && preg_match($route['pattern'],$requestUri,$matches)){
                    $params = array_filter($matches,'is_string',ARRAY_FILTER_USE_KEY);
                    call_user_func_array($this->applyMiddleware($route['callback']),[$params]);
                    return;
                }
            }

            $this->handleNotFound();
        }catch (\Throwable $e){
            $this->errorHandler->handleException($e);
        }
    }

    private function applyMiddleware(callable $callback): callable
    {
        return array_reduce(array_reverse($this->middleware),function ($next,$middleware){
            return function ($params) use ($middleware, $next){
                    return $middleware($params,$next);
            };
        },
            $callback
        );
    }

    public function handleNotFound(){
        if ($this->notFoundCallBack){
            call_user_func($this->notFoundCallBack);
        }else{
            header("HTTP/1.0 404 Not Found");
            echo "404 Not Found";
        }
    }

}