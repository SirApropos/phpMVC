<?php
/**
 * User: Apropos (sir.apropos.of.nothing@gmail.com)
 * Date: 10/26/2014
 * Time: 5:07 PM
 */

class MethodInvoker {

	public static $mapping = [
		"fields" => [
			"container" => [
				"autowired" => true,
				"type" => "IOCContainer"
			]
		]
	];

	/**
	 * @var IOCContainer
	 */
	private $container;

	/**
	 * @param $obj
	 * @param $method
	 * @return mixed
	 * @throws InvocationException
	 * @throws NotImplementedException
	 */
	public function invoke($obj, $method){
		$clazz = ReflectionUtils::getReflectionClass($obj);
		if(!$method instanceof ReflectionMethod){
			$method = $clazz->getMethod($method);
		}
		$method->setAccessible(true);
		$args = [];
		foreach($method->getParameters() as $param) {
			$args[] = $this->satisfy($clazz, $method, $param);
		}
		return $method->invokeArgs($obj, $args);
	}

	protected function satisfy(ReflectionClass $clazz, ReflectionMethod $method, ReflectionParameter $param){
		$result = null;
		$satisfied = false;
		if($paramClazz = $param->getClass()){
			$result = $this->container->resolve($param->getClass()->getName());
			$satisfied = true;
		}else if($param->isDefaultValueAvailable()){
			$result = $param->getDefaultValue();
			$satisfied = true;
		}else if($param->isOptional()){
			//Is this even possible to hit?
			throw new NotImplementedException("Optional parameters are not implemented");
		}
		if(!$satisfied){
			throw new InvocationException("Could not satisfy parameter: ".$clazz->getName()."::".
				$method->getName()."::".$param->getName());
		}
		return $result;
	}
} 