<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 8/17/13
 * Time: 11:03 PM
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
		$filterManager->addFilter($this->container->newInstance("SimpleAuthenticationFilter"));
	}

	public function createControllerFactory(){
		return  $this->container->newInstance("SimpleControllerFactory");
	}

	public function createControllerMethodInvoker(){
		return $this->container->newInstance("ControllerMethodInvoker");
	}

	/**
	 * @return ClassLoader
	 */
	public function createClassLoader(){
		include_once($this->getClassesDir()."utils/SimpleCachingClassLoader.php");
		return new SimpleCachingClassLoader($this->getCacheDir());
	}

	public function getClassesDir()
	{
		return $this->conf['classes_dir'];
	}

	public function getControllerDir()
	{
		return $this->conf['controller_dir'];
	}

	public function getModelDir()
	{
		return $this->conf['model_dir'];
	}

	public function getTaglibDir()
	{
		return $this->conf['taglib_dir'];
	}

	public function getViewDir()
	{
		return $this->conf['view_dir'];
	}

	public function getCacheDir()
	{
		return $this->conf['cache_dir'];
	}

	public function getBaseDir()
	{
		return $this->conf['base_dir'];
	}

	public function getBasePath(){
		return $this->conf['base_path'];
	}

	public static function getInstance(){
		return self::$instance;
	}
}