<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/12/13
 * Time: 11:47 PM
 */
class IOCContainer
{
    use ClassLoader;
    private static $instance;

    private $container = [];

    /**
     * @return IOCContainer
     */
    public static function getInstance(){
        if(!self::$instance){
            self::$instance = new IOCContainer();
        }
        return self::$instance;
    }

    /**
     * @param $clazz
     * @return object
     */
    public function resolve($clazz){
        $result = null;
        if(!$this->exists($clazz)){
            $this->findClass($clazz);
        }
        if($this->container[$clazz]){
            $result = $this->container[$clazz];
        }else{
            foreach($this->container as $obj){
                if(is_a($obj, $clazz)){
                    $result = $obj;
                    $this->register($obj, $clazz);
                }
            }
        }
        if(!$result){
            $result = new $clazz();
        }
        return $result;
    }

    public function register($object, $name=null){
        if(!$name){
            $this->register($object, get_class($object));
        }else{
            $this->container[$name] = $object;
        }
    }
}
