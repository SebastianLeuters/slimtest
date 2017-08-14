<?php

// Routes

//$app->get('/[{name}]', function ($request, $response, $args) {
//    // Sample log message
//    $this->logger->info("Slim-Skeleton '/' route");
//
//    // Render index view
//    return $this->renderer->render($response, 'index.phtml', $args);
//});

$routingPath = sprintf('%s/../config/routing.yml', __DIR__);

$routeContent = file_get_contents($routingPath);
$routes = Symfony\Component\Yaml\Yaml::parse($routeContent);

foreach($routes as $routeName => $route) {
    if(isset($route['path']) && isset($route['defaults']['_controller'])) {
        $app->map(isset($route['methods']) ? $route['methods'] : ['GET'], $route['path'],  $route['defaults']['_controller']);
    }
}
