<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};


// monolog
$container['logger'] = function ($container) {
    $settings = $container['settings']['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

# database connection
$container['db'] = function ($container) {
    $settings = $container['settings']['db'];
    $mysqlDatabase = new \Orm\MysqlDatabaseAdapter($settings);
    return $mysqlDatabase;

//    $dsn = 'mysql:host=' . $settings['host'] . ';dbname=' . $settings['database'] . ';charset=utf8';
//    $user = $settings['user'];
//    $password = $settings['password'];
//    $pdo = new \Slim\PDO\Database($dsn, $user, $password);
//    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['name'], $db['user'], $db['pass']);
//    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
//    return $pdo;
};

