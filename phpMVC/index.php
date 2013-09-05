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
	include "./classes/utils/Timer.php";
	$timer = Timer::create("Main","main");
	$initTimer = Timer::create("Initialization", "initialization");
	include "./classes/utils/ClassLoader.php";
    include "./classes/Config.php";
	include "./classes/utils/IOCContainer.php";
    include "./classes/exceptions/AutoloadingException.php";

    $container = IOCContainer::getInstance();

	/**
	 * @var MVCConfig $config
	 */
	$config = $container->resolve("MVCConfig");

	$config->initialize();

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
    /**
     * @var ControllerFactory controllerFactory
     */
    $controllerFactory = $container->resolve("ControllerFactory");
    $cmethod = $controllerFactory->getController($request);
    if($invoker->canInvoke($cmethod, $grantedAuthority)){
        $invoker->invoke($cmethod);
    }else{
        throw new InvalidGrantException("Access Denied");
    }
	$timer->stop();
	$times = array();
	foreach(Timer::getTimers() as $time){
		if(!isset($times[$time->getCategory()])){
			$times[$time->getCategory()] = 0;
		}
		$times[$time->getCategory()] += $time->getTime();
	}
	print_r(Timer::getTimers());
	print_r($times);
}catch(Exception $ex){
    if($ex instanceof HttpException){
        http_response_code($ex->getResponseCode());
    }else{
        http_response_code(500);
    }
    echo $ex->getMessage();
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
            }else{
                $message .= $arg;
            }
        }
        $message .=") called at [".$trace['file'].":".$trace['line']."]";
        array_push($arr, $message);
    }
//    echo "\n\n";
//    foreach($arr as $trace){
//        echo $trace."\n";
//    }
}
?>