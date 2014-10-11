<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 8/17/13
 * Time: 11:03 PM
 *
 * @property-read string $classes_dir
 * @property-read string $controller_dir
 * @property-read string $model_dir
 * @property-read string $taglib_dir
 * @property-read string $view_dir
 * @property-read string $cache_dir
 * @property-read string $base_dir
 */
class MVCConfig {
	/**
	 * @var IOCContainer
	 */
	private $container;

	private $conf;

	private static $instance;

	public function __construct($conf){
		$this->conf = $conf;
		self::$instance = $this;
	}

	public function initialize(){
		$classLoader = $this->createClassLoader();
		$this->container = IOCContainer::getInstance();
		$this->container->register($classLoader);
		$filterManager = $this->createFilterManager();
		$this->container->register($filterManager);
		$this->configureFilters($filterManager);
		$this->container->register($this->createControllerFactory());
		$this->container->register($this->createControllerMethodInvoker());
	}

	public function createFilterManager(){
		return new FilterManager();
	}

	public function configureFilters(FilterManager $filterManager){
		$filterManager->addFilter(new SimpleAuthenticationFilter());
	}

	public function createControllerFactory(){
		return new SimpleControllerFactory();
	}

	public function createControllerMethodInvoker(){
		return new ControllerMethodInvoker();
	}

	/**
	 * @return ClassLoader
	 */
	public function createClassLoader(){
		include_once($this->classes_dir."utils/SimpleCachingClassLoader.php");
		return new SimpleCachingClassLoader(null);
	}

	/**
	 * @param $var
	 * @return mixed
	 */
	public function __get($var){
		return isset($this->conf[$var]) ? $this->conf[$var] : null;
	}

	public static function getInstance(){
		return self::$instance;
	}
}