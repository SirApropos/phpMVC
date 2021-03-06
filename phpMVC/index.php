<?php
set_error_handler(function($errno , $errstr, $errfile, $errline){
	echo "$errstr in file $errfile on line: $errline\n\n";
	$allowed = [E_NOTICE, E_STRICT];
	if(!in_array($errno, $allowed)){
		debug_print_backtrace();
		die();
	}
});
try{
	$config = array();
	include("./mvcconfig.inc.php");
	include $config['classes_dir']."utils/Timer.php";
	include $config['config_path'];
	$clazz = new ReflectionClass($config['config_class']);
	/**
	 * @var MVCConfig $config
	 */

	$config = $clazz->newInstanceArgs([$config]);
	$timer = Timer::create("Main","main");
	$initTimer = Timer::create("Initialization", "initialization");
	include $config->getClassesDir()."utils/ClassLoader.php";
	include $config->getClassesDir()."utils/IOCContainer.php";

	$config->initialize();
	$container = IOCContainer::getInstance();

	include("./datasource.php");

	/**
	 * @var ControllerMethodInvoker $invoker
	 */
	$invoker = $container->resolve("ControllerMethodInvoker");
	$request = new HttpRequest($_SERVER);
	$container->register($request);
	$initTimer->stop();
	/**
	 * @var FilterManager $filterManager
	 */
	$filterManager = $container->resolve("FilterManager");
	$grantedAuthority = $filterManager->doFilter($request);
	$container->register($grantedAuthority);
	/**
	 * @var ControllerFactory controllerFactory
	 */
	$controllerFactory = $container->resolve("ControllerFactory");

	try{
		$cmethod = $controllerFactory->getController($request);
		$invoker->invoke($cmethod);
	}catch(HttpException $ex){
		if(($ex instanceof HttpNotFoundException) || $request->getMethod() != HttpMethod::OPTIONS){
			throw $ex;
		}
	}

	$timer->stop();
	if(isset($_GET['debug'])){
		$times = array();
		foreach(Timer::getTimers() as $time){
			if(!isset($times[$time->getCategory()])){
				$times[$time->getCategory()] = 0;
			}
			$times[$time->getCategory()] += $time->getTime();
		}
		print_r(Timer::getTimers());
		print_r($times);
	}
}catch(Exception $ex){
	echo $ex->getMessage();
	if($ex instanceof HttpException){
		http_response_code($ex->getResponseCode());
	}else{
		http_response_code(500);
		$arr = [];
		foreach($ex->getTrace() as $key => $trace){
			$message = "#$key ";
			$message .= $trace['class'].$trace['type'].$trace['function'];
			$message .= "(";
			$first = true;
			foreach($trace['args'] as $arg){
				if(!$first){
					$message.=", ";
				}else{
					$first = false;
				}
				if(is_object($arg)){
					$message .= get_class($arg)." ".JsonUtils::toJson($arg);
				}else if(is_array($arg)){
					$message .= JsonUtils::toJson($arg);
				}else{
					$message .= $arg;
				}
			}
			$message .=") called at [".$trace['file'].":".$trace['line']."]";
			array_push($arr, $message);
		}
	}
}
?>