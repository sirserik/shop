<?php

namespace Core;

class Router
{
    /**
     * Маршруты сгруппированы по HTTP методам для более быстрого поиска.
     *
     * @var array
     */
    private array $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'PATCH' => [],
        'DELETE' => [],
        'OPTIONS' => [],
        'HEAD' => [],
    ];

    private  $notFoundCallBack = null;
    private array $middleware = [];
    private string $baseUrl = '';
    private array $namedRoutes = []; // Массив для хранения именованных маршрутов.

    public function __construct(string $baseUrl = '')
    {
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    /**
     * Добавляет маршрут для метода GET.
     *
     * @param string $pattern
     * @param callable $callback
     * @param string|null $name Имя маршрута (необязательно).
     */
    public function get(string $pattern, callable $callback, string $name = null): void
    {
        $this->addRoute('GET', $pattern, $callback, $name);
    }

    /**
     * Добавляет маршрут для метода POST.
     *
     * @param string $pattern
     * @param callable $callback
     * @param string|null $name Имя маршрута (необязательно).
     */
    public function post(string $pattern, callable $callback, string $name = null): void
    {
        $this->addRoute('POST', $pattern, $callback, $name);
    }

    /**
     * Добавляет маршрут для метода PUT.
     *
     * @param string $pattern
     * @param callable $callback
     * @param string|null $name Имя маршрута (необязательно).
     */
    public function put(string $pattern, callable $callback, string $name = null): void
    {
        $this->addRoute('PUT', $pattern, $callback, $name);
    }

    /**
     * Добавляет маршрут для метода PATCH.
     *
     * @param string $pattern
     * @param callable $callback
     * @param string|null $name Имя маршрута (необязательно).
     */
    public function patch(string $pattern, callable $callback, string $name = null): void
    {
        $this->addRoute('PATCH', $pattern, $callback, $name);
    }

    /**
     * Добавляет маршрут для метода DELETE.
     *
     * @param string $pattern
     * @param callable $callback
     * @param string|null $name Имя маршрута (необязательно).
     */
    public function delete(string $pattern, callable $callback, string $name = null): void
    {
        $this->addRoute('DELETE', $pattern, $callback, $name);
    }

    /**
     * Добавляет маршрут для метода OPTIONS.
     *
     * @param string $pattern
     * @param callable $callback
     * @param string|null $name Имя маршрута (необязательно).
     */
    public function options(string $pattern, callable $callback, string $name = null): void
    {
        $this->addRoute('OPTIONS', $pattern, $callback, $name);
    }

    /**
     * Добавляет маршрут для метода HEAD.
     *
     * @param string $pattern
     * @param callable $callback
     * @param string|null $name Имя маршрута (необязательно).
     */
    public function head(string $pattern, callable $callback, string $name = null): void
    {
        $this->addRoute('HEAD', $pattern, $callback, $name);
    }


    public function setNotFoundHandler(callable $callback): void
    {
        $this->notFoundCallBack = $callback;
    }

    public function addMiddleware(callable $middleware): void
    {
        $this->middleware[] = $middleware;
    }

    public function group(string $prefix, callable $callback): void
    {
        $previousBaseUrl = $this->baseUrl;
        $this->baseUrl .= '/' . trim($prefix, '/');
        call_user_func($callback, $this);
        $this->baseUrl = $previousBaseUrl;
    }

    /**
     * Добавляет маршрут в массив маршрутов, сгруппированный по методам.
     *
     * @param string $method HTTP-метод.
     * @param string $pattern Шаблон URL маршрута.
     * @param callable $callback Функция обратного вызова.
     * @param string|null $name Имя маршрута (необязательно).
     */
    private function addRoute(string $method, string $pattern, callable $callback, string $name = null): void
    {
        $pattern = $this->baseUrl . '/' . trim($pattern, '/');
        $pattern = preg_replace('#/{([^/]+)}#', '/(?P<$1>[^/]+)', $pattern);
        $pattern = '#^' . $pattern . '$#';

        $this->routes[$method][] = [ // Маршруты теперь хранятся в массиве по методам
            'pattern' => $pattern,
            'callback' => $callback,
            'name' => $name, // Сохраняем имя маршрута
        ];

        if ($name) {
            $this->namedRoutes[$name] = [
                'pattern' => $pattern,
                'method' => $method,
            ];
        }
    }

    /**
     * Диспетчеризация запроса. Оптимизировано для поиска маршрутов по методу.
     */
    public function dispatch(): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Проверяем, есть ли маршруты для данного HTTP метода.
        if (isset($this->routes[$requestMethod])) {
            foreach ($this->routes[$requestMethod] as $route) {
                if (preg_match($route['pattern'], $requestUri, $matches)) {
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                    $this->executeMiddleware($params);
                    call_user_func($route['callback'], $params);
                    return;
                }
            }
        }

        $this->handleNotFound();
    }

    private function executeMiddleware(array $params): void
    {
        foreach ($this->middleware as $middleware) {
            $middleware($params);
        }
    }

    private function handleNotFound(): void
    {
        if ($this->notFoundCallBack) {
            call_user_func($this->notFoundCallBack);
        } else {
            header("HTTP/1.0 404 Not Found");
            echo "404 Not Found";
        }
    }

    /**
     * Генерация URL по имени маршрута и параметрам.
     *
     * @param string $name Имя маршрута.
     * @param array $params Параметры для подстановки в URL.
     * @return string|null URL маршрута или null, если маршрут не найден.
     */
    public function generateUrl(string $name, array $params = []): ?string
    {
        if (!isset($this->namedRoutes[$name])) {
            return null;
        }

        $routeData = $this->namedRoutes[$name];
        $url = $routeData['pattern'];

        foreach ($params as $key => $value) {
            $url = str_replace("(?P<{$key}>[^/]+)", $value, $url); // Заменяем параметры значениями
        }

        // Удаляем оставшиеся параметры (необязательные), если нужно. В данном случае, просто вернем как есть.
        // $url = preg_replace('#/\([^/]+\)\?#', '', $url); // Пример удаления необязательных параметров

        // Убираем якоря regex и базовый url, если нужно вернуть относительный путь
        $url = preg_replace('#\^#', '', $url);
        $url = preg_replace('#\$#', '', $url);
        $url = str_replace($this->baseUrl, '', $url);
        $url = ltrim($url, '/'); // Убираем лишний слеш в начале

        return $this->baseUrl . '/' . $url; // Возвращаем полный URL с базовым URL
    }
}