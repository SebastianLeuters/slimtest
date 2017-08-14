<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

use Slim\Csrf\Guard;

// Register with container
$container = $app->getContainer();

$container['csrf'] = function (\Psr\Container\ContainerInterface $container) {
    $guard = new Guard();

    $guard->setFailureCallable(function ($request, $response, $next) {
        $request = $request->withAttribute("csrf_status", false);
        return $next($request, $response);
    });

    return $guard;
};



// Register middleware for all routes
// If you are implementing per-route checks you must not add this
$app->add($container->get('csrf'));

// Register component on container
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../templates', [
        'debug' => true,
//        'cache' => __DIR__ . '/../cache/Twig'
    ]);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');

    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));
    $view->addExtension(new Twig_Extension_Debug());
    $view->addExtension(new Twig\CsrfExtension($container['csrf']));
    return $view;
};



// Define your controllers
$controllers = [
    \Controller\DefaultController::class,
];

// Register Controllers
foreach (\Jgut\Slim\Controller\Resolver::resolve($controllers) as $controller => $callback) {
    $container[$controller] = $callback;
}