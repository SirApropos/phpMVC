<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/12/13
 * Time: 11:50 PM
 */
class SimpleControllerFactory implements ControllerFactory
{
	/**
	 * @var HttpRequest
	 */
	private $request;

	/**
	 * @var array
	 */
	private $list_ignore = array(".","..");

	/**
	 * @var IOCContainer
	 */
	private $container;

	/**
	 * @var ClassLoader
	 */
	private $classLoader;

	/**
	 * @var MVCConfig
	 */
	private $config;

    function __construct()
    {
        $this->container = IOCContainer::getInstance();
	    $this->classLoader = ClassLoader::getInstance();
	    $this->config = MVCConfig::getInstance();
    }


    function getController(HttpRequest $request)
    {
        $this->request = $request;
        $method = $this->findController($this->config->getControllerDir());
        if(!$method){
            throw new HttpNotFoundException();
        }
        return $method;
    }

    private function findController($path){
        $dir = opendir($path);
        $result = null;
        while($file = readdir($dir)){
            if(!in_array($file, $this->list_ignore)){
                if(is_dir($file)){
                    $result = $this->findController($path.$file."/");
                }else{
	                $clazz = $this->classLoader->getClassName($file);
                    $this->classLoader->loadClass($clazz);
                    $result = $this->getControllerMethod($clazz);
                }
            }
            if($result){
                break;
            }
        }
        closedir($dir);
        return $result;
    }

    private function getControllerMethod($name){
	    $timer = Timer::create("Controller $name", "controller");
        $clazz = new ReflectionClass($name);
        $mappings = ReflectionUtils::getMapping($name,"ControllerMapping")->getMappings();
        $method = null;
        $pathFound = false;
        foreach($mappings as $mapping){
	        /**
	         * @var RequestMapping $mapping
	         */
	        $regex = str_replace("`","",$mapping->getPath());
            $regex = preg_replace("`\{[^\}]+\}`", "*", $regex);
            $regex = preg_replace("`([\[\]\{\}\.\(\)\?\*])`","\\\\$1",$regex);
            $regex = str_replace("\\*\\*",".*",$regex);
            $regex = str_replace("\\*","[^/]*", $regex);
            $regex = "`^".$regex."$`";
            if(preg_match($regex, $this->request->getPath())){
                $pathFound = true;
                $allowed_methods = $mapping->getAllowedMethods();
                if(is_array($allowed_methods)){
                    if(!in_array($this->request->getMethod(), $allowed_methods)){
                        continue;
                    }
                }
                $method = new ControllerMethod();
                $method->setController($this->container->newInstance($clazz));
                $method->setMapping($mapping);
            }
        }
	    $timer->stop();
        if($pathFound && !$method){
            throw new HttpMethodNotAllowedException();
        }
        return $method;
    }

}
