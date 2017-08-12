<?php

namespace Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\PDO\Database;
use Traits\ContainerTrait;

abstract class BaseController {
    use ContainerTrait;

    // constructor receives container instance
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    /**
     * Renders a template
     *
     * @param ResponseInterface $response
     * @param string $template
     * @param array $params
     * @return mixed
     */
    public function render($response, $template, $params) {
        $view = $this->getContainer()->get('view');
        $response = $view->render($response, $template, $params);

        return $response;
    }

    /**
     * @return Database
     */
    public function getManager() {
        return $this->getContainer()->get('db');
    }
}