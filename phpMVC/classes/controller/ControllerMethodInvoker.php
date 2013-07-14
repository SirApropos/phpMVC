<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/13/13
 * Time: 2:19 PM
 */
class ControllerMethodInvoker
{
    /**
     * @var Mapper[]
     */
    private $mappers = [];

    public function invoke(ControllerMethod $cmethod){
        $method = $cmethod->getMethod();
        $args = [];
        $params =$method->getParameters();
        $container = IOCContainer::getInstance();
        foreach($params as $param){
            $clazz = $param->getClass();
            if($clazz){
                array_push($args, $container->resolve($clazz->getName()));
            }else{
                array_push($args, null);
            }
        }
        $response = $container->resolve("HttpResponse");
        $value = $method->invokeArgs($cmethod->getController(), $args);
        if($value instanceof View){
            $response->setView($value);
        }else{
            $response->setView(new BasicView($value));
        }
        $response->send();
    }
}
