<pre>
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
    include "./classes/Config.php";
    include "./classes/utils/ClassLoader.php";
    include "./classes/exceptions/AutoloadingException.php";

    //TODO: Optimize or remove autoloading entirely.
    //Probably build a class cache mapping classes to their respective files.
    //Then the directory scan only needs to be done once.
    //Probably as good as it would get while still allowing autoloading.
    class AutoLoader{
        use ClassLoader;

        public function autoload($name){
            if(!$this->findClass($name,"./classes/")){
                if(!$this->findClass($name, "./models/")){
                     throw new AutoloadingException($name);
                }
            }
        }
    }

    function __autoload($name){
        $autoloader = new AutoLoader();
        $autoloader->autoload($name);
    }

    $invoker = new ControllerMethodInvoker();
    $container = IOCContainer::getInstance();
    $request = new HttpRequest($_SERVER);
    $container->register($request);
    $controllerFactory = new SimpleControllerFactory();
    $cmethod = $controllerFactory->getController($request);
    $invoker->invoke($cmethod);
}catch(Exception $ex){
    if($ex instanceof HttpException){
        http_response_code($ex->getResponseCode());
    }else{
        http_response_code(500);
    }
    echo $ex->getMessage();
}
?>
</pre>