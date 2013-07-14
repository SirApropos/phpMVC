<pre>
<?php
try{
    include "./classes/Config.php";
    include "./classes/utils/ClassLoader.php";
    include "./classes/exceptions/AutoloadingException.php";

    //TODO: Optimize or remove autoloading entirely.
    class AutoLoader{
        use ClassLoader;

        public function autoload($name){
            if(!$this->findClass($name,"./classes/")){
                 throw new AutoloadingException($name);
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