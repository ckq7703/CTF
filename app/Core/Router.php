<?php
class Router {
    private $routes = [];

    public function get($path, $callback) {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback) {
        $this->routes['POST'][$path] = $callback;
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = rtrim($uri, '/');
        // Bỏ prefix /SUBMIT/public hoặc /submit/public
        $uri = preg_replace('#/(?i)SUBMIT/public#', '', $uri);
        // Nếu URI rỗng, gán thành /
        $uri = $uri === '' ? '/' : $uri;
        // Debug: In ra URI để kiểm tra
        // echo "URI received: $uri<br>";

        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $route => $callback) {
                $pattern = preg_replace('#:([\w]+)#', '([^/]+)', $route);
                $pattern = "#^$pattern$#";
                if (preg_match($pattern, $uri, $matches)) {
                    array_shift($matches);
                    call_user_func_array($callback, $matches);
                    return;
                }
            }
        }
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found from Router";
    }
}