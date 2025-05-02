<?php

require_once __DIR__ . '/vendor/autoload.php';

$container = require __DIR__ . '/src/DependencyInjection/container.php';

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->post('/api/cv', [\App\Controller\CvController::class, 'getCv']);
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

$routeInfo = $dispatcher->dispatch(
    $httpMethod,
    parse_url($uri, PHP_URL_PATH)
);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo json_encode(['error' => 'No encontrado']);
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        $allowedMethods = $routeInfo[1];
        echo json_encode(['error' => 'Método no permitido']);
        break;

    case FastRoute\Dispatcher::FOUND:
        [$class, $method] = $routeInfo[1];
        $controller = $container->get($class);
        $controller->$method([]);
        break;
}
