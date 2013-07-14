<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/13/13
 * Time: 12:25 AM
 */
class ControllerMethod
{
    private $controller;
    private $method;

    public function setController(Controller $controller)
    {
        $this->controller = $controller;
    }

    /**
     * @return Controller
     */
    public function getController()
    {
        return $this->controller;
    }

    public function setMethod(ReflectionMethod $method)
    {
        $method->setAccessible(true);
        $this->method = $method;
    }

    /**
     * @return ReflectionMethod
     */
    public function getMethod()
    {
        return $this->method;
    }
}
