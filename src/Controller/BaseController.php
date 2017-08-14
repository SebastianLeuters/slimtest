<?php

namespace Controller;

use Jgut\Slim\Controller\Base;
use Orm\DatabaseAdapterInterface;
use Psr\Http\Message\ResponseInterface;

abstract class BaseController extends Base {

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
     * @return DatabaseAdapterInterface
     */
    public function getManager() {
        return $this->getContainer()->get('db');
    }
}