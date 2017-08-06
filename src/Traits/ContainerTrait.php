<?php

namespace Traits;

use Psr\Container\ContainerInterface;

trait ContainerTrait {

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Get container
     *
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Set container
     *
     * @param $container
     * @return $this
     */
    public function setContainer($container)
    {
        $this->container = $container;
        return $this;
    }
}