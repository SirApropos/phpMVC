<?php
/**
 * User: Apropos (sir.apropos.of.nothing@gmail.com)
 * Date: 10/26/2014
 * Time: 5:27 PM
 */

class ControllerExceptionHandler {
	/**
	 * @var string
	 */
	private $method;

	/**
	 * @var string[]
	 */
	private $exceptions;

	public function __construct($method, array $exceptions){
		$this->method = $method;
		$this->exceptions = $exceptions;
	}

	public function canHandle($ex){
		$ex = $ex instanceof Exception ? get_class($ex) :
			$ex instanceof ReflectionClass ? $ex : new ReflectionClass($ex);
		$result = false;
		foreach($this->exceptions as $class){
			if($ex->getName() == $class || $ex->isSubclassOf($class)){
				$result = true;
				break;
			}
		}
		return $result;
	}

	public function handle(Controller &$controller, Exception $ex){
		$container = IOCContainer::getInstance();
		/**
		 * @var $invoker MethodInvoker
		 */
		$invoker = $container->resolve("MethodInvoker");
		$container->register($ex);
		$invoker->invoke($controller, $this->method);
	}
} 