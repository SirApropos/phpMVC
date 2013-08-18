<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 8/17/13
 * Time: 11:03 PM
 */

class MVCConfig {
    /**
     * @var IOCContainer
     */
    private $container;

    public function initialize(){
        $this->container = IOCContainer::getInstance();
        $filterManager = $this->createFilterManager();
        $this->container->register($filterManager);
        $this->configureFilters($filterManager);
        $this->container->register($this->createControllerFactory());
    }

    protected function createFilterManager(){
        return new FilterManager();
    }

    protected function configureFilters(FilterManager $filterManager){
        $filterManager->addFilter(new SimpleAuthenticationFilter());
    }

    protected function createControllerFactory(){
        return new SimpleControllerFactory();
    }
}