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

    function __construct()
    {
        array_push($this->mappers,new BasicMapper());
        array_push($this->mappers,new JsonMapper());
        array_push($this->mappers,new XmlMapper());
    }

    public function invoke(ControllerMethod $cmethod){
        $method = $cmethod->getMethod();
        $args = [];
        $params =$method->getParameters();
        $container = IOCContainer::getInstance();
        $request = $container->resolve("HttpRequest");
        $pathParts = preg_split("`/`",$request->getPath());
        $mappingParts = preg_split("`/`", $cmethod->getMapping()->getPath());
        $urlvars = [];
        foreach($mappingParts as $key => $part){
            //Should always be the case, but let's make sure.
            if(isset($pathParts[$key])){
                $matches = [];
                if(preg_match('`\{(.+)\}`',$part, $matches)){
                    $replacer = str_replace($matches[0], "", $part);
                    $urlvars[$matches[1]] = preg_replace("`^".$replacer."`","",$pathParts[$key]);
                }
            }
        }
        foreach($params as $param){
            $clazz = $param->getClass();
            $value = null;
            if($clazz){
                if($clazz->implementsInterface("Model")){
                    if($request->getBody()){
                        $contentType = $request->getHeaders()->getContentType();
                        foreach($this->mappers as $mapper){
                            if($mapper->canRead($contentType)){
                                $value = $mapper->read($request->getBody(), $clazz);
                                break;
                            }
                        }
                        if(is_null($value)){
                            throw new ModelBindException("No object mapper available to read type: ".$contentType);
                        }
                    }else{
                        if($param->isOptional()){
                            $value = $param->getDefaultValue();
                        }else{
                            throw new ModelBindException("No request body provided.");
                        }
                    }
                }else{
                    $value = $container->resolve($clazz->getName());
                }
            }else{
                $name = $param->getName();
                if(isset($urlvars[$name])){
                    $value = $urlvars[$name];
                }else{
                    if($param->isDefaultValueAvailable()){
                        $value = $param->getDefaultValue();
                    }else{
                        throw new ModelBindException("Could not satisfy dependency: ".get_class($cmethod->getController())."::".
                            $method->getName().'::$'.$name);
                    }
                }
            }
            array_push($args, $value);
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
