<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/12/13
 * Time: 11:47 PM
 */
class IOCContainer
{
	private static $instance;

	private $container = [];

	/**
	 * @var ClassLoader
	 */
	private $classloader;

	private function __construct(){
		$this->classloader = ClassLoader::getInstance();
		$this->register($this->classloader);
		$this->register($this);
	}

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
	 * @return bool
	 */
	public function contains($clazz){
		return !is_null($this->_findObject($clazz));
	}

	private function _findObject($clazz){
		$result = null;
		if($clazz instanceof ReflectionClass){
			$clazz = $clazz->getName();
		}
		if(!$this->classloader->classExists($clazz)){
			$this->classloader->loadClass($clazz);
		}else{
			if(isset($this->container[$clazz])){
				$result = $this->container[$clazz];
			}else{
				foreach($this->container as $obj){
					if(is_a($obj, $clazz)){
						$result = $obj;
						$this->register($obj, $clazz);
					}
				}
			}
		}
		return $result;
	}

	/**
	 * @param $clazz
	 * @return object
	 */
	public function resolve($clazz){
		$result = $this->_findObject($clazz);
		if(is_null($result)){
			$result = $this->newInstance($clazz);
			$this->register($result);
		}
		return $result;
	}

	public function newInstance($clazz){
		if(!$clazz instanceof ReflectionClass){
			$clazz = new ReflectionClass($clazz);
		}
		if(!$clazz->isInstantiable()){
			throw new ModelBindException("Cannot instantiate object of type: ".$clazz->getName());
		}
		$constructor = $clazz->getConstructor();
		$args = [];
		if($constructor){
			foreach($constructor->getParameters() as $param){
				$paramClazz = $param->getClass();
				if(!is_null($paramClazz)){
					array_push($args, $this->resolve($param->getClass()));
				}else{
					if($param->isDefaultValueAvailable()){
						array_push($args, $param->getDefaultValue());
					}else{
						throw new ModelBindException("Could not construct object of type ".$clazz->getName().
						   ": could not satisfy constructor. Constructor arguments must all have a type or default value.");
					}
				}
			}
		}
		$result = $clazz->newInstanceArgs($args);
		$this->autowire($result);
		return $result;
	}

	public function autowire($object){
		$mapping = ReflectionUtils::getMapping($object, "ModelMapping");
		/**
		 * @var ModelMapping mapping
		 */
		if(!is_null($mapping)){
			foreach($mapping->getMappings() as $name => $field){
				/**
				 * @var FieldMapping $field
				 */
				if($field->getAutowired()){
					if($field->getType()){
						ReflectionUtils::setProperty($object, $name, $this->resolve($field->getType()));
					}else{
						throw new ModelBindException(get_class($object)."::".$name." is marked as autowired but no type is specified.");
					}
				}
			}
		}
	}

	public function register($object, $name=null){
		if(!$name){
			$this->register($object, get_class($object));
		}else{
			$this->container[$name] = $object;
		}
	}
}
